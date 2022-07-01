<!DOCTYPE html>
<?PHP
if (isset($_GET['checkDB'])) {
    $connect0 = mysqli_connect('localhost', $_POST['admin_id'], $_POST['admin_pw'], 'common');
    if(!$connect0) {
        print "DB Select Error, maybe ID and password are wrong!!";
        exit();
    }
    // $sq =  "show grants for $_POST['admin_id']@localhost";
    $sq = "select Grant_priv from mysql.user where User='".$_POST['admin_id']."' ";
    print $sq;
    $rs = mysqli_query($connect0, $sq);

    $row = mysqli_fetch_row($rs);
    print $row[0];
    if ($row[0] !='Y') {
        print "DB Select Error, maybe ID does not have auth!!";
        exit();
    }
    exit();
}
for ($i=0; $i<3; $i++) {
	chdir("../");
	if (is_dir("bin")) {
		$ROOT_DIR = getcwd();
		break;
	}
}
$dirname = $ROOT_DIR.'/bin/';
$fname = $ROOT_DIR."/bin/param.db";
// print $fname;

$db = new SQLite3($fname);

if($_POST) {
    if (!$_POST['SERVICE']['COUNTING']) {
        $_POST['SERVICE']['COUNTING'] = 'no';
    }
    if (!$_POST['SERVICE']['FACE']) {
        $_POST['SERVICE']['FACE'] = 'no';
    }
    if (!$_POST['SERVICE']['MAC_SNIFF']) {
        $_POST['SERVICE']['MAC_SNIFF'] = 'no';
    }
    if (!$_POST['SERVICE']['SNAPSHOT']) {
        $_POST['SERVICE']['SNAPSHOT'] = 'no';
    }
    if (!$_POST['SERVICE']['START_ON_BOOT']) {
        $_POST['SERVICE']['START_ON_BOOT'] = 'no';
    }

    // print "<pre>";
    // print_r($_POST);
    foreach ($_POST as $session => $option ){
        foreach($option as $key =>$val) {
            $sq = "update config_tbl set entryValue = '".$val."' where SECTION='".$session."' and entryName='".$key."';";
            // print $sq."\n";
            $rs = $db->exec($sq) or die(print_r($db->lastErrorMsg(), true));
        }
    }
    // print "</pre>";

}

if (!$_GET['sector']) {
    $_GET['sector'] = 'config';
}

if ($_GET['sector'] == 'config') { // TLSS config 
    $sq = "select * from config_tbl ;";
    // print $sq;
    $rs = $db->query($sq);

    // print_r($rs);
    while ($row = $rs->fetchArray()) {
        // print "arr_line['".$row['SECTION']."']['".$row['entryName']."'];\n";
        if ($row['datatype'] == 'yesno') {
            $arr_line[$row['SECTION']][$row['entryName']] = '<tr><td>'.$row['entryName'].'</td><td><input class="ml-2" type="checkbox" name="'.$row['SECTION'].'['.$row['entryName'].']" value="yes" '.($row['entryValue']=='yes'? "checked": "").' '.($row['readonly']? 'disabled':'').'></td><td>'.$row['description'].'</tr>';
        }
        else if ($row['datatype'] == 'select') {
            $arr_sel = explode(",",$row['option']);
            $option_str = '';
            for ($i=0; $i<sizeof($arr_sel); $i++){
                $option_str .= '<input type="radio" class="ml-4 mr-1" name="'.$row['SECTION'].'['.$row['entryName'].']" value="'.$arr_sel[$i].'"'.($row['entryValue']==$arr_sel[$i]? " checked": "").' '.($row['readonly']? 'disabled':'').'>'.$arr_sel[$i];
            }
            $arr_line[$row['SECTION']][$row['entryName']] = '<tr><td>'.$row['entryName'].'</td><td>'.$option_str.'</td><td>'.$row['description'].'</tr>';

        }
        else {
        $arr_line[$row['SECTION']][$row['entryName']] = '<tr><td>'.$row['entryName'].'</td><td><input class="form-control" type="text" name="'.$row['SECTION'].'['.$row['entryName'].']" value="'.$row['entryValue'].'" '.($row['readonly']? 'readonly':'').'></td><td>'.$row['description'].'</tr>';
        }
        $arr_line[$row['SECTION']][$row['entryName']] .= "\n";
    }

    // print $dirname;
    $sq = "select entryValue from config_tbl where SECTION='LICENSE' and entryName='CODE';";
    $lic_code = $db->querySingle($sq);
    $sq = "select entryValue from config_tbl where SECTION='SERVICE' and entryName='START_ON_BOOT';";
    $startOnBoot = $db->querySingle($sq);
    $db->close();

    // print strtoupper(PHP_OS);
    if(strtoupper(PHP_OS) == 'LINUX') {
        if($_SERVER['HTTP_HOST'] == $CLOUD_SERVER) {
            $ex_str = sprintf('python3.8 %sfunction4php.py chkLic %s', $dirname, $lic_code);
        }
        else {
            $ex_str = sprintf('python3 %sfunction4php.py chkLic %s', $dirname, $lic_code);
        }
    }
    else if (strtoupper(PHP_OS) == 'WINNT') {
        $ex_str = sprintf('%sfunction4php.exe chkLic %s', $dirname, $lic_code);
        $stonboot_ex = sprintf('%sfunction4php.exe startOnBoot %s', $dirname, $startOnBoot);
        // print($stonboot_ex);
        $a =  exec($stonboot_ex);
        // print ($a);
    }
    // print $ex_str;
    $a = exec($ex_str);
    $b = explode(',', $a);
    if(trim($b[1])) {
        $lic_code_str = "<font color=#0000FF><u>Valid until: ".$b[1];
    }
    else {
        $lic_code_str = "<font color=#FF0000><u>Invalid Lic Code";
    }
    $mac = $b[2];
    $lic_code_str .= " for machine mac: ".$mac."</u><font>";
    // print($a);


    $table_body .= '<tr><td colspan="3" style="padding-top:10px"><b>[ROOT]</b></td></tr>';
    $table_body .= $arr_line['ROOT']['DOCUMENT_TITLE'];
    $table_body .= $arr_line['ROOT']['HOST_TITLE'];
    $table_body .= $arr_line['ROOT']['LOGO_PATH'];
    $table_body .= $arr_line['ROOT']['DEVELOPER'];
    $table_body .= $arr_line['ROOT']['APPLICATION'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[SERVICE]</b></td></tr>';
    $table_body .= $arr_line['SERVICE']['MODE'];
    $table_body .= $arr_line['SERVICE']['COUNT_EVENT'];
    $table_body .= $arr_line['SERVICE']['COUNTING'];
    $table_body .= $arr_line['SERVICE']['FACE'];
    $table_body .= $arr_line['SERVICE']['MAC_SNIFF'];
    $table_body .= $arr_line['SERVICE']['SNAPSHOT'];
    $table_body .= $arr_line['SERVICE']['PROBE_INTERVAL'];
    $table_body .= $arr_line['SERVICE']['ROOT_DIR'];
    $table_body .= $arr_line['SERVICE']['START_ON_BOOT'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[MYSQL]</b></td></tr>';
    $table_body .= $arr_line['MYSQL']['HOST'];
    $table_body .= $arr_line['MYSQL']['USER'];
    $table_body .= $arr_line['MYSQL']['PASSWORD'];
    $table_body .= $arr_line['MYSQL']['DB'];
    $table_body .= $arr_line['MYSQL']['CHARSET'];
    $table_body .= $arr_line['MYSQL']['RECYCLING_TIMESTAMP'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[PORT]</b></td></tr>';
    $table_body .= $arr_line['PORT']['TLSS'];
    $table_body .= $arr_line['PORT']['COUNT_EVENT'];
    $table_body .= $arr_line['PORT']['MACSNIFF'];
    $table_body .= $arr_line['PORT']['FACE'];
    $table_body .= $arr_line['PORT']['SNAPSHOT'];
    $table_body .= $arr_line['PORT']['QUERY_DB'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[DB_COMMON]</b></td></tr>';
    $table_body .= $arr_line['DB_COMMON']['USER'];
    $table_body .= $arr_line['DB_COMMON']['ACCOUNT'];
    $table_body .= $arr_line['DB_COMMON']['PARAM'];
    $table_body .= $arr_line['DB_COMMON']['SNAPSHOT'];
    $table_body .= $arr_line['DB_COMMON']['COUNTING'];
    $table_body .= $arr_line['DB_COMMON']['COUNT_EVENT'];
    $table_body .= $arr_line['DB_COMMON']['FACE'];
    $table_body .= $arr_line['DB_COMMON']['HEATMAP'];
    $table_body .= $arr_line['DB_COMMON']['MAC'];
    $table_body .= $arr_line['DB_COMMON']['ACCESS_LOG'];
    $table_body .= $arr_line['DB_COMMON']['MESSAGE'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[DB_CUSTOM]</b></td></tr>';
    $table_body .= $arr_line['DB_CUSTOM']['ACCOUNT'];
    $table_body .= $arr_line['DB_CUSTOM']['COUNT'];
    $table_body .= $arr_line['DB_CUSTOM']['HEATMAP'];
    $table_body .= $arr_line['DB_CUSTOM']['AGE_GENDER'];
    $table_body .= $arr_line['DB_CUSTOM']['MACSNIFF'];
    $table_body .= $arr_line['DB_CUSTOM']['SQUARE'];
    $table_body .= $arr_line['DB_CUSTOM']['STORE'];
    $table_body .= $arr_line['DB_CUSTOM']['CAMERA'];
    $table_body .= $arr_line['DB_CUSTOM']['COUNTER_LABEL'];
    $table_body .= $arr_line['DB_CUSTOM']['LANGUAGE'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[FPP]</b></td></tr>';
    $table_body .= $arr_line['FPP']['HOST'];
    $table_body .= $arr_line['FPP']['API_KEY'];
    $table_body .= $arr_line['FPP']['API_SRCT'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[WEATHER]</b></td></tr>';
    $table_body .= $arr_line['WEATHER']['HOST'];
    $table_body .= $arr_line['WEATHER']['API_KEY'];
    $table_body .= $arr_line['WEATHER']['API_SRCT'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[MISC]</b></td></tr>';
    $table_body .= $arr_line['MISC']['AGE_GROUP'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[VERSION]</b></td></tr>';
    $table_body .= $arr_line['VERSION']['WEB'];
    $table_body .= $arr_line['VERSION']['BIN'];
    $table_body .= '<tr><td colspan="3" style="padding-top:20px"><b>[LICENSE]</b><span class="ml-4">'.$lic_code_str.'</span></td></tr>';
    $table_body .= $arr_line['LICENSE']['CODE'];

    $table_body = '<table class="table table-striped table-sm table-bordered table-hover" >
        <form method="POST" ENCTYPE="multipart/form-data" id="submitForm" >
            <tbody>'.$table_body.'</tbody>
        </form>
    </table>';

}

else if ($_GET['sector'] == 'change') { // Changes 
    $sq = "select * from info_tbl where category='change_log' order by entryName, entryValue asc;";
    // print $sq;
    $rs = $db->query($sq);
    // print_r($rs);
    while ($row = $rs->fetchArray()) {
        $table_body .= '<tr>
            <td>'.($cur_entry==$row[2] ? "":$row[2]).'</td>
            <td>'.$row[3].'</td>
            <td>'.$row[4].'</td>
        
        </tr>';
        $cur_entry = $row[2];
        

    }
    $table_body = '<table class="table table-striped table-sm table-bordered table-hover" >
        <tbody>'.$table_body.'</tbody>
    </table>';    

}

else if ($_GET['sector'] == 'param') { 
    $sq = "select * from param_tbl ";
    // print $sq;
    $rs = $db->query($sq);
    // print_r($rs);
    while ($row = $rs->fetchArray()) {
        $table_body .= '<tr>
            <td>'.$row[0].'</td>
            <td>'.$row[1].'</td>
            <td>'.$row[2].'</td>
            <td>'.$row[3].'</td>
            <td>'.$row[4].'</td>
            <td>'.$row[5].'</td>
            <td>'.$row[6].'</td>
            <td>'.$row[7].'</td>
            <td>'.$row[8].'</td>
            <td>'.$row[9].'</td>
            <td>'.$row[10].'</td>
        
        </tr>';
        

    }
    $table_body = '<table class="table table-striped table-sm table-bordered table-hover" >
        <tbody>'.$table_body.'</tbody>
    </table>';    

}


?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
		<meta name="author" content="Bootlab">
		<title id='title'>Admin Tools</title>
		<link href="css/app.css" rel="stylesheet">
	</head>

	<body id="body">
        <main class="content" id="pageContents">
            <div class = "row">
            <?=$browse_dir?>
            </div>
            <div class="row"><?=$table_body?>
             </div>
            <div class="row">
                <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#centeredModalDanger" OnClick="checkData()">Submit</button>
                <button type="button" class="btn btn-default ml-5 mr-3" OnClick="location.href=('./config.php')">Cancel</button>
                <div id="err_contents" class="form-group"></div>
            </div>
        </main>

        <div class="modal fade" id="centeredModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Summary</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="form-group">
                            <label>Admin ID</label>
                            <input class="form-control form-control-lg" type="text" name="ID" id="admin_id" placeholder="Enter AdminID" value=""/>
                        </div>
                        <div class="form-group">
                            <label>Admin Password</label>
                            <input class="form-control form-control-lg" type="password" name="password" id="admin_pw" autocomplete="on" placeholder="Enter Admin password" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="submit_btn" class="btn btn-danger" OnClick="submitForm()" data-dismiss="modal">Proceed</button>
                    </div>
                </div>
            </div>
        </div>


    </body>
    <script src="js/app.js"></script>
</html>
<script>
function checkData(){
    document.getElementById("err_contents").innerHTML = '';
}
function submitForm(){
    var adminID = document.getElementById("admin_id");
    var adminPw = document.getElementById("admin_pw");
    var errCont = document.getElementById("err_contents");
    var url = "./config.php?checkDB";
    var posting = $.post(url, {
        admin_id: adminID.value, 
        admin_pw: adminPw.value
    });
    posting.done(function(data) {
        console.log(data);
        if (data.indexOf("Error")>0) {
            errCont.innerHTML = '<font color=#FF0000>Admin ID or Password not match!</font>';
            return;
        }
        document.getElementById("submitForm").submit();
    });
    
}
</script>