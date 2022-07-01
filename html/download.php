<?PHP
$baseDir = "/home/www/";

if ($_GET['file']) {
    if (is_file($baseDir.'download/'.$_GET['file'])) {
        $targetFile = $baseDir.'download/'.$_GET['file'];
    }
    else if (is_file($baseDir.$_GET['file'])) {
        $targetFile = $baseDir.$_GET['file'];
    }
    else {
        print "file: '".$baseDir.$_GET['file']."' not exist!";
        exit;
    }
    $fname = trim(array_pop(explode("/", $targetFile)));
    $filesize = filesize($targetFile);
   
    if (file_exists($targetFile)) {
        header('Content-Description: File Transfer');
        header("Content-Type:application/octet-stream");
        header("Content-Disposition:attachment;filename=$fname");
        header("Content-Transfer-Encoding:binary"); 
        header("Content-Length:".$filesize);
        header("Cache-Control:cache,must-revalidate");
        header("Pragma:no-cache");
        header("Expires:0");
        ob_clean();
        flush();
        readfile($targetFile);
        flush();
        exit;
    } 
}

else if ($_GET['checkfile']) {
    if (is_file($baseDir.'download/'.$_GET['checkfile'])) {
        $targetFile = $baseDir.'download/'.$_GET['checkfile'];
    }
    else if (is_file($baseDir.$_GET['checkfile'])) {
        $targetFile = $baseDir.$_GET['checkfile'];
    }
    else {
        print "file: '".$baseDir.$_GET['checkfile']."' not exist!";
        exit;
    }
    $fname = trim(array_pop(explode("/", $targetFile)));
    $filesize = filesize($targetFile);

    print $fname;

    $rs =  array(
        "name" => $fname, 
        "location" => $targetFile,
        "size" => filesize($targetFile), 
        "ext"=> array_pop(explode(".", $targetFile)), 
        "date"=> date('Y-m-d H:i:s', filemtime($targetFile)+3600*8)
    );
    
    print_r($rs);

}
else if (isset($_GET['check']) && $_GET['check'] == '') { // check release version
    // print("check version<br>\n");
    if ($_GET['os']=='nt'){
        $arr_fname = [$target_Dir."cosilanBinWin64.tar.gz", $target_Dir."cosilanHtmlfiles.tar.gz"];
    }
    else if ($_GET['os']=='posix'){
        $arr_fname = [$target_Dir."cosilanBinLinux.tar.gz", $target_Dir."cosilanHtmlfiles.tar.gz"];
    }
    else {
        print ("{}");
        return;
    }
    $outputs = null;
    $arr_list= array();
    // $TIME_OFFSET = 8*3600;
    $TIME_OFFSET = 0;

    foreach ($arr_fname as $fname) {
        if (file_exists($fname)) {
            $filesize = filesize($down);    
            exec("tar tvf ".$fname, $outputs, $retval); 
        }
        else {
            print "no file";
        }

        if($outputs) {
            foreach ($outputs as $output) {
                $tab = array_reverse(explode(" ", $output));
                array_push($arr_list, ["name"=> $tab[0], "date"=> $tab[2]." ".$tab[1], "size"=> (int)$tab[3], "timestamp"=> strtotime($tab[2]." ".$tab[1]) + $TIME_OFFSET ]);
            }
        }
    }
    // print_r($arr_list);
    print (json_encode($arr_list, true));
    
}
else if (isset($_GET['language']) && $_GET['language'] == ''){
    $connect0 = @mysqli_connect('localhost', 'root', 'rootpass', 'common');

    if (!$connect0) {
        die("connect fail");
    }
    // $sq = "select * from cnt_demo.language into outfile '/tmp/lang.sql' fields terminated by ',' enclosed by \"'\" lines terminated by ';\n'";
    $sq = "select * from cnt_demo.language";
    print $sq;
    $rs = mysqli_query($connect0, $sq);
    while($row=mysqli_fetch_row($rs)){
        print_r($row);
    }

    print ($rs);


}

else if (isset($_GET['list']) && $_GET['list'] == '') { // list
    $arr_list = array();
    $targetDir = $baseDir."/download/";
    if (is_dir($targetDir)) {
        if ($dh = opendir($targetDir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file == '.' or $file == '..' or $file =='old') {
                    continue;
                }
                array_push($arr_list, 
                    array(
                        "index" => strtoupper($file), 
                        "name" => $file, 
                        "size" => filesize($targetDir.$file), 
                        "ext"=> array_pop(explode(".", $file)), 
                        "date"=> date('Y-m-d H:i:s', filemtime($targetDir.$file)+3600*8)
                    )
                );
            }
            closedir($dh);
        }
    }
    sort($arr_list);

    $tablebody = "<tr><th>Name</th><th>Size</th><th>Ext</th><th>date</th></tr>";
    for ($i=0; $i<sizeof($arr_list); $i++) {

        if($arr_list[$i]['size']<1000) {
            $arr_list[$i]['size'] = $arr_list[$i]['size']."B";
        }
        else if($arr_list[$i]['size']<1000000) {
            $arr_list[$i]['size'] = number_format($arr_list[$i]['size']/1000)."KB";
        }
        else if($arr_list[$i]['size']<1000000000) {
            $arr_list[$i]['size'] = number_format($arr_list[$i]['size']/1000000)."MB";
        }
        $arr_list[$i]['name'] = "<a href=download.php?file=".$arr_list[$i]['name'].">".$arr_list[$i]['name']."</a>";

        $tablebody .= "<tr>";
        $tablebody .= "<td>".$arr_list[$i]['name']."</td>";
        $tablebody .= "<td align='right'>".$arr_list[$i]['size']."</td>";
        $tablebody .= "<td>".$arr_list[$i]['ext']."</td>";
        $tablebody .= "<td>".$arr_list[$i]['date']."</td>";
        $tablebody .= "</tr>";
    }

    $tablebody = "<table>".$tablebody."</table>";

?>
    <html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
		<meta name="author" content="Bootlab">
		<title id='title'>Admin Tools</title>
        <style type="text/css">
            body {background-color: #fff; color: #222; font-family: sans-serif;}
            table {border-collapse: collapse; border: 0; width: 600px; box-shadow: 1px 2px 3px #eee;}
            .center {text-align: center;}
            .center table {margin: 1em auto; text-align: left;}
            .center th {text-align: center !important;}
            td, th {border: 1px solid #aaa; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
            h1 {font-size: 150%;}
            h2 {font-size: 125%;}
            .p {text-align: left;}
            .e {background-color: #ccf; width: 300px; font-weight: bold;}
            .h {background-color: #99c; font-weight: bold;}
            .v {background-color: #ddd; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
            .v i {color: #999;}
            img {float: right; border: 0;}
            hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
        </style>
	</head>
	<body id="body">
        <main class="content" id="pageContents">
            <div class = "row">
            <?=$browse_dir?>
            </div>
            <div class="row">
                <?=$tablebody?>
            </div>
        </main>
    </body>
</html>
<?PHP    
}


?>


