<?PHP

$connect0 = @mysqli_connect('localhost','rt_user', '13579', 'cosilanStatus');

$sq = "select * from status ";
$rs  = mysqli_query($connect0, $sq);
// print "<pre>";
while ($assoc = mysqli_fetch_assoc($rs)){
    
    $assoc['working_time'] = (int)(($assoc['last_time']-$assoc['start_time'])/(3600*24))."D ".date("H:i:s", $assoc['last_time']-$assoc['start_time']);
    $idle_device_secs = time() -$assoc['last_device_access'];
    if ($idle_device_secs>600){
        $assoc['idle'] = '<font color="#FF0000">'.(int)($idle_device_secs/(3600*24)).'D '.date("H:i:s", $idle_device_secs).'</font>';
    }
    else {
        $assoc['idle'] = (int)($idle_device_secs/(3600*24)).'D '.date("H:i:s", $idle_device_secs);
    }
    $output = null;
    $x = exec("python3 ../bin/function4php.pyc chkLicMac ".$assoc['mac']." ".$assoc['license_code'], $output);
    
    // print_r($assoc);
    // print_r($output);
    $assoc['exp_date'] = explode(",",$output[0])[1];
    $table_body .= "<tr>
        <td>".$assoc['pk']."</td>
        <td>".$assoc['ID']."</td>
        <td>".$assoc['ip_addr']."</td>
        <td>".$assoc['mac']."</td>
        <td>".$assoc['license_code']."</td>
        <td>".$assoc['exp_date']."</td>
        <td>".$assoc['regdate']."</td>
        <td>".date("Y-m-d H:i:s", $assoc['timestamp']+3600*8)."</td>
        <td>".date("Y-m-d H:i:s", $assoc['start_time']+3600*8)."</td>
        <td>".date("Y-m-d H:i:s", $assoc['last_time']+3600*8)."</td>
        <td>".$assoc['connecting_device']."</td>
        <td>".$assoc['active_device']."</td>
        <td>".date("Y-m-d H:i:s", $assoc['last_device_access']+3600*8)."</td>
        <td>".$assoc['working_time']."</td>
        <td>".$assoc['idle']."</td>
        <td>".number_format($assoc['temp_max']/1000,2)."</td>
        <td>".number_format($assoc['temp_cur']/1000,2)."</td>
    </tr>";

}

// print "</pre>";
$HTML_BODY = '<table>
<thead>
<tr>
<th>No</th>
<th>ID</th>
<th>IP Addr</th>
<th>MAC</th>
<th>Lic. Code</th>
<th>exp_date</th>
<th>Install Date</th>
<th>Report datetime</th>
<th>Start</th>
<th>Last</th>
<th>Devices</th>
<th>Active</th>
<th>Device Access</th>
<th>Working</th>
<th>Idle Device</th>
<th>Temp Max</th>
<th>Temp</th>

</tr></thead>
<tbody>'.$table_body.'</tbody></table>';
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
            /* pre {margin: 0; font-family: monospace;}
            a:link {color: #009; text-decoration: none; background-color: #fff;}
            a:hover {text-decoration: underline;} */
            table {border-collapse: collapse; border: 0; width: 100%; box-shadow: 1px 2px 3px #eee;}
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
                <div><?=$list_log?></div>
                <?=$HTML_BODY?>
            </div>
        </main>
    </body>
</html>