<?PHP
// wget http://49.235.119.5/download.php?file=../html/inc/network.php -O /var/www/html/inc/network.php

for ($i=0; $i<3; $i++) {
    chdir("../");
    if (is_dir("bin")) {
        $ROOT_DIR = getcwd();
        break;
    }
}    
$fname = $ROOT_DIR."/bin/param.db";
$db = new SQLite3($fname);


function mask2cidr($mask){
    $dq = explode(".",$mask);
    for ($i=0; $i<4 ; $i++) {
        $bin[$i]=str_pad(decbin($dq[$i]), 8, "0", STR_PAD_LEFT);
    }
    $bin = implode("",$bin); 
    return strlen(rtrim($bin,"0"));
}

function cidr2mask($cidr){
    return long2ip(-1 << (32 - (int)$cidr));
}



function getNetworkFromParam(){
    global $db;
    $sq = "select * from param_tbl where group1='system' and group2='network' ";
    $rs = $db->query($sq);
    $arr_rs = array();
    while ($row = $rs->fetchArray()) {
        $arr_rs[$row['groupPath'].'.'.$row['entryName']] = $row['entryValue'];
    }
    
    // print_r($arr_rs);
    return $arr_rs;
}

function getNetworkFromSystem(){
    $output = null;
    $retval = null;
    exec('/usr/bin/nmcli device show', $output, $retval);
    if ($retval != 0){
        print "Fail to excute";
    }
    // print_r($output);
    $arr_rs = array();
    $device = '';
    for ($i=0; $i<sizeof($output); $i++){
        $output[$i] = trim($output[$i]);
        if (!$output[$i]) {
            continue;
        }
        $exp = explode(": ", $output[$i]);
        $exp[0] = trim($exp[0]);
        $exp[1] = trim($exp[1]);
        // print $exp[0]."=".$exp[1];
        if (strcmp($exp[0], "GENERAL.DEVICE") == 0) {
            $device =  $exp[1];
            continue;
        }
        else if (strncmp($exp[0], "IP4.ROUTE[", 10) == 0 or strncmp($exp[0], "IP6.ROUTE[", 10) == 0) {
            continue;
        }
        else if ( strcmp($exp[0], "GENERAL.CON-PATH") == 0 or strcmp($exp[0], "WIRED-PROPERTIES.CARRIER") == 0) {
            continue;
        }
        if ($device == 'lo'){
            continue;
        }
        else if (strcmp($exp[0], "IP4.ADDRESS[1]") == 0) {
            list($arr_rs["system.network.".$device.".ip4.address"], $netmask) =  explode("/", $exp[1]);
            $arr_rs["system.network.".$device.".ip4.subnetmask"] = cidr2mask($netmask);
        }
        else if (strcmp($exp[0], "IP6.ADDRESS[1]") == 0) {
            list($arr_rs["system.network.".$device.".ip6.address"], $netmask) =  explode("/", $exp[1]);
            $arr_rs["system.network.".$device.".ip6.subnetmask"] = $netmask;
        }
        else {
            $arr_rs["system.network.".$device.".".strtolower($exp[0])] = $exp[1];
        }
    }
    
    
    if ( isset($arr_rs['system.network.eth0.ip4.domain[1]']) and strcmp($arr_rs['system.network.eth0.ip4.domain[1]'], "DHCP") == 0){
        $arr_rs['system.network.eth0.ip4.mode'] = "dhcp";
    }
    else {
        $arr_rs['system.network.eth0.ip4.mode'] = "static";
    }

    if ( isset($arr_rs['system.network.eth0.ip6.address'])){
        $arr_rs['system.network.eth0.ip6.enable'] = 'yes';
    }
    else {
        $arr_rs['system.network.eth0.ip6.enable'] = 'no';
    }

    // print_r($arr_rs);

    return $arr_rs;

}

function syncParamFromSystem($arr_rs){
    global $db;
    // $arr_rs = getNetworkFromSystem();
    $arr_sq = array();
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.general.hwaddr']."' where group1='system' and group2='network' and group3='eth0' and group4='hwaddr'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip4.mode']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='mode'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip4.address']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='address'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip4.subnetmask']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='subnetmask'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip4.gateway']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='gateway'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip4.dns[1]']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='dns1'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip4.dns[2]']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='dns2'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip6.address']."' where group1='system' and group2='network' and group3='eth0' and group4='ip6' and group5='address'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip6.subnetmask']."' where group1='system' and group2='network' and group3='eth0' and group4='ip6' and group5='subnetmask'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip6.gateway']."' where group1='system' and group2='network' and group3='eth0' and group4='ip6' and group5='gateway'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip6.dns[1]']."' where group1='system' and group2='network' and group3='eth0' and group4='ip6' and group5='dns1'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.eth0.ip6.dns[2]']."' where group1='system' and group2='network' and group3='eth0' and group4='ip6' and group5='dns2'");
    array_push($arr_sq, "update param_tbl set entryValue='".$arr_rs['system.network.wlan0.general.hwaddr']."' where group1='system' and group2='network' and group3='wlan0' and group4='hwaddr'");

    for ($i=0; $i<sizeof($arr_sq); $i++) {
        // print $arr_sq[$i];
        $rs = $db->exec($arr_sq[$i]);
        // print_r($rs);
    }

}

if (isset($_POST) and $_POST){
    print_r($_POST);
// [eth0_ip4_mode] => static 
// [eth0_ip4_address] => 192.168.1.200 
// [eth0_ip4_subnetmask] => 255.255.0.0 
// [eth0_ip4_gateway] => 192.168.1.1 
// [eth0_ip4_dns] => Array ( [1] => 8.8.8.8 [2] => 192.168.1.1 )
    if (!$_POST['eth0_ip6_enable']) {
        $_POST['eth0_ip6_enable'] = 'no';
    }
    $arr_sq =  array();

    array_push($arr_sq, "update param_tbl set entryValue='".$_POST['eth0_ip4_mode']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='mode'");
    array_push($arr_sq, "update param_tbl set entryValue='".$_POST['eth0_ip4_address']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='address'");
    array_push($arr_sq, "update param_tbl set entryValue='".$_POST['eth0_ip4_subnetmask']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='subnetmask'");
    array_push($arr_sq, "update param_tbl set entryValue='".$_POST['eth0_ip4_gateway']."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='gateway'");
    array_push($arr_sq, "update param_tbl set entryValue='".$_POST['eth0_ip4_dns'][1]."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='dns1'");
    array_push($arr_sq, "update param_tbl set entryValue='".$_POST['eth0_ip4_dns'][2]."' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='dns2'");
    array_push($arr_sq, "update param_tbl set entryValue='".$_POST['eth0_ip6_enable']."' where group1='system' and group2='network' and group3='eth0' and group4='ip6' and group5='enable'");
    array_push($arr_sq, "update param_tbl set entryValue='yes' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='changed'");


    for ($i=0; $i<sizeof($arr_sq); $i++) {
        print $arr_sq[$i];
        $rs = $db->exec($arr_sq[$i]);
        // print_r($rs);
    }


    $CHANGED= false;
    for ($i=0; $i<10; $i++){
        $arr_rs = getNetworkFromSystem();
        sleep(1);
        if ($_POST['eth0_ip4_mode'] == "dhcp" and $arr_rs['system.network.eth0.ip4.mode'] == 'dhcp') {
            $CHANGED = true;
        }

        else if($_POST['eth0_ip4_mode'] == "static" and $arr_rs['system.network.eth0.ip4.mode'] == 'static') {
            if ($arr_rs['system.network.eth0.ip4.address'] == $_POST['eth0_ip4_address'] and 
                $arr_rs['system.network.eth0.ip4.subnetmask'] == $_POST['eth0_ip4_subnetmask'] and 
                $arr_rs['system.network.eth0.ip4.gateway'] == $_POST['eth0_ip4_gateway'] and 
                $arr_rs['system.network.eth0.ip4.dns[1]'] == $_POST['eth0_ip4_dns'][1] and
                $arr_rs['system.network.eth0.ip4.dns[2]'] == $_POST['eth0_ip4_dns'][2] ) {
                $CHANGED = true;    
            }
        }
        
        if ($CHANGED == true){
            $sq = "update param_tbl set entryValue='no' where group1='system' and group2='network' and group3='eth0' and group4='ip4' and group5='changed'";
            $rs = $db->exec($sq);
            print "changed";
            
            echo '<script>window.parent.location.href=("http://'.$arr_rs['system.network.eth0.ip4.address'].'/inc/network.php")</script>';
            break;
        }
        sleep(1);
    }
    sleep(1);
    

}

$arr_rs = getNetworkFromSystem();
print_r($arr_rs);
syncParamFromSystem($arr_rs);
$db->close();













// $output = null;
// $retval = null;
// exec('/usr/bin/nmcli device show', $output, $retval);
// // if ($retval != 0){

// // }
// // print_r($output);
// $arr_rs = array();
// $device = '';
// for ($i=0; $i<sizeof($output); $i++){
//     $output[$i] = trim($output[$i]);
//     if (!$output[$i]){
//         continue;
//     }
//     $exp = explode(": ", $output[$i]);
//     $exp[0] = trim($exp[0]);
//     $exp[1] = trim($exp[1]);
//     // print $exp[0]."=".$exp[1];
//     if (strcmp($exp[0], "GENERAL.DEVICE") == 0) {
//         $device =  $exp[1];
//     }
//     else if (strcmp($exp[0], "IP4.ADDRESS[1]") == 0) {
//         list($arr_rs[$device]['IP4.ADDRESS'], $arr_rs[$device]['IP4.NETMASK']) =  explode("/", $exp[1]);
//         $arr_rs[$device]['IP4.NETMASK'] = cidr2mask( $arr_rs[$device]['IP4.NETMASK']);
//     }
//     else {
//         $arr_rs[$device][$exp[0]] = $exp[1];
//     }
// }


// if ( isset($arr_rs['eth0']['IP4.DOMAIN[1]']) and strcmp($arr_rs['eth0']['IP4.DOMAIN[1]'], "DHCP") == 0){
//     $arr_rs['eth0']['IP4DHCP'] = "checked";
// }
// else {
//     $arr_rs['eth0']['IP4STATIC'] = "checked";
// }
// // print_r($arr_rs);



$htmlbody ='
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bootlab">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
    <main class="content">
        <form name="info_form" class="form-horizontal" method="POST" ENCTYPE="multipart/form-data">
            <div class="card col-md-5">
                <div class="card-header"><span class="float-right ml-3"> '.$arr_rs['system.network.eth0.general.state'].'</span> <h5 class="card-title mb-0">IPv4</h5>  </div>
                <div class="card-body">
                    <div class="row">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="eth0.ip4.mode" value="dhcp"  OnChange="showBlock(this)" '.($arr_rs['system.network.eth0.ip4.mode']=="dhcp" ? "checked":"checked").' >
                        <span class="form-check-label">Obtain an IP address via DHCP</span>
                    </label>
                    <table class="table table-striped table-sm table-hover ml-4" id="ip4dhcp"  style="display:none">
                        <tr>
                            <td><label>IP Address</label></td>
                            <td><input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip4.address'].'" readonly></td>
                        </tr><tr>
                            <td><label>Subnet mask</label></td>
                            <td><input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip4.subnetmask'].'" readonly></td>
                        </tr><tr>
                            <td><label>Gateway address</label></td>
                            <td><input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip4.gateway'].'" readonly></td>
                        </tr><tr>
                            <td><label>DNS address</label></td>
                            <td><input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip4.dns[1]'].'" readonly></td>
                        </tr><tr>
                            <td><label>    </label></td>
                            <td><input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip4.dns[2]'].'" readonly></td>
                        </tr>
                    </table>
                    </div>
                    <div  class="row">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="eth0.ip4.mode" value="static"  OnChange="showBlock(this)" '.($arr_rs['system.network.eth0.ip4.mode']=="static" ? "checked":"").'>
                        <span class="form-check-label">Use the following IP address </span>
                    </label>
                    <table class="table table-striped table-sm table-hover ml-4" id="ip4static" style="display:none">
                        <tr>
                            <td><label>IP Address</label></td>
                            <td><input type="text" class="form-control" name="eth0.ip4.address" value="'.$arr_rs['system.network.eth0.ip4.address'].'"></td>
                        </tr><tr>
                            <td><label>Subnet mask</label></td>
                            <td><input type="text" class="form-control" name="eth0.ip4.subnetmask" value="'.$arr_rs['system.network.eth0.ip4.subnetmask'].'"></td>
                        </tr><tr>
                            <td><label>Gateway address</label></td>
                            <td><input type="text" class="form-control" name="eth0.ip4.gateway" value="'.$arr_rs['system.network.eth0.ip4.gateway'].'"></td>
                        </tr><tr>
                            <td><label>DNS address</label></td>
                            <td><input type="text" class="form-control" name="eth0.ip4.dns[1]" value="'.$arr_rs['system.network.eth0.ip4.dns[1]'].'"></td>
                        </tr><tr>
                            <td><label>    </label></td>
                            <td><input type="text" class="form-control" name="eth0.ip4.dns[2]" value="'.$arr_rs['system.network.eth0.ip4.dns[2]'].'"></td>
                    </tr>
                    </table>
                    </div>
                </div>
            </div>

            <div class="card col-md-5">
                <div class="card-header"><h5 class="card-title mb-0">IPv6</h5></div>
                <div class="card-body">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="eth0.ip6.enable" value="yes" OnChange="showBlock(this)" '.($arr_rs['system.network.eth0.ip6.enable']=="yes" ? "checked":"").'>
                        <span class="form-check-label">Enable IPv6</span>
                    </label>
                    <table class="table table-striped table-sm table-hover ml-4" id="ipv6" >
                        <tr>
                            <th><label>IP Address</label></th>
                            <td>
                                <input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip6.address'].'" readonly>
                                <input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip6.address[2]'].'" readonly>
                                <input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip6.address[3]'].'" readonly>
                            </td>
                        </tr><tr>
                            <td><label>Gateway address</label></td>
                            <td><input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip6.gateway'].'" readonly></td>
                        </tr><tr>
                            <td><label>DNS address</label></td>
                            <td><input type="text" class="form-control"  value="'.$arr_rs['system.network.eth0.ip6.dns[1]'].'" readonly></td>
                        </tr>
                    </table>
                </div>
            </div>            
<!--
            <div class="card col-md-6">
                <div class="card-header"><h5 class="card-title mb-0">WLAN</h5></div>
                <div class="card-body">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="IPv4DHCP" value="DHCP">
                        <span class="form-check-label">Obtain an IP address via DHCP</span>
                    </label>
                    <table class="table table-striped table-sm table-hover ml-4" >
                        <tr><th><label>IP Address</label></th><td><input type="text" name="ip_addr" class="form-control"  value="'.$arr_result['ip_addr'].'"></td></tr>
                        <tr><td><label>Subnet mask</label></td><td><input type="text" name="netmask" class="form-control"  value="'.$arr_result['netmask'].'"></td></tr>
                        <tr><td><label>Gateway address</label></td><td><input type="text" name="gateway" class="form-control"  value="'.$arr_result['gateway'].'"></td></tr>
                    </table>
                </div>
            </div>


            <div class="card col-md-6">
                <div class="card-header"><h5 class="card-title mb-0">IPv6</h5></div>
                <div class="card-body">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="IPv4DHCP" value="DHCP">
                        <span class="form-check-label">Obtain an IP address via DHCP</span>
                    </label>
                    <table class="table table-striped table-sm table-hover ml-4" >
                        <tr><th><label>IP Address</label></th><td><input type="text" name="ip_addr" class="form-control"  value="'.$arr_result['ip_addr'].'"></td></tr>
                        <tr><td><label>Subnet mask</label></td><td><input type="text" name="netmask" class="form-control"  value="'.$arr_result['netmask'].'"></td></tr>
                        <tr><td><label>Gateway address</label></td><td><input type="text" name="gateway" class="form-control"  value="'.$arr_result['gateway'].'"></td></tr>
                    </table>
                </div>
            </div>
-->

            <button type="submit" name="btn" class="btn btn-primary" value="change_network">$msg[save_changes]</button>
        </form>
    </main>
</body>
<script src="/js/app.js"></script> 
';

print $htmlbody;

?>
<script>
function showBlock(e) {
    console.log(e.type, e.name, e.value, e.checked);

    ip4mode = document.getElementsByName('eth0.ip4.mode');
    if (ip4mode[0].checked == true) {
        document.getElementById('ip4dhcp').style.display="";
        document.getElementById('ip4static').style.display='none';
    }

    else if (ip4mode[1].checked == true) {
        document.getElementById('ip4dhcp').style.display='none';
        document.getElementById('ip4static').style.display="";
    }

    ip6enable = document.getElementsByName('eth0.ip6.enable');
    if (ip6enable[0].checked == true) {
        document.getElementById('ipv6').style.display="";
    }
    else {
        document.getElementById('ipv6').style.display='none';
    }
}

showBlock('');

</script>