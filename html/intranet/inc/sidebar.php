<?php
$msg = array(
    'order'         => "order",
    'warehouse'     => "仓库",
    'liststock'     => "list stock",
    'listwarehouse' => "list warehouse",
    'listsample'    => "list sample",
    'listserial'    => "list Serial",
    'codes'         => "编码",
    'listccode'     => "客户代码",
    'listpcode'     => "产品代码",
    'listscode'     => "半成品代码",
    'listmcode'     => "材料代码",
    'listcodes'     => "所以代码",


);

$pageSide = <<<EOB
    <li id="order" class="active">
        <a href="?fr=order">
            <i class="fa fa-book"></i>
            <span>$msg[order]</span>
        </a>
    </li>
    <li id = "warehouse" class="sidebar-dropdown">
        <a href="#"><i class="fa fa-tachometer-alt"></i>
            <span>$msg[warehouse]</span>
        </a>
        <div class="sidebar-submenu">
            <ul>
                <li ><a href="?fr=list_stock">$msg[liststock]</a></li>
                <li ><a href="?fr=list_warehouse">$msg[listwarehouse]</a></li>
                <li ><a href="?fr=list_sample">$msg[listsample]</a></li>
                <li ><a href="?fr=list_serial">$msg[listserial]</a></li>
            </ul>
        </div>
    </li>
    <li class="sidebar-dropdown">
        <a href="#"><i class="fa fa-tachometer-alt"></i>
            <span>$msg[codes]</span>
        </a>
        <div class="sidebar-submenu">
            <ul>
                <li ><a href="?fr=list_ccode">$msg[listccode]</a></li>
                <li ><a href="?fr=list_pcode">$msg[listpcode]</a></li>
                <li ><a href="?fr=list_scode">$msg[listscode]</a></li>
                <li ><a href="?fr=list_mcode">$msg[listmcode]</a></li>
                <li ><a href="?fr=list_codes">$msg[listcodes]</a></li>
            </ul>
        </div>
    </li>
    <li id="document" class="sidebar-dropdown" >
        <a href="#"><i class="fa fa-book"></i>
            <span>$msg[document]</span>
        </a>
        <div class="sidebar-submenu" id="docu_tag">
            <ul>
                <li ><a href="?fr=sales_doc">$msg[salesdoc]</a></li>
                <li ><a href="?fr=admin_doc">$msg[admindoc]</a></li>
                <li ><a href="?fr=product_doc">$msg[productdoc]</a></li>
                <li ><a href="?fr=write_qt">$msg[writeqt]</a></li>
                <li ><a href="?fr=write_po">$msg[writepo]</a></li>
                <li ><a href="?fr=write_pi">$msg[writepi]</a></li>
            </ul>
        </div>
    </li>
    <li id="rebate" >
        <a href="?fr=rebate">
            <i class="fa fa-book"></i>
            <span>$msg[rebate]</span>
        </a>
    </li>

    <li class="header-menu"><span>Admin</span></li>
    <li class="sidebar-dropdown">
        <a href="#"><i class="fa fa-book"></i>
            <span>$msg[management]</span>
        </a>
         <div class="sidebar-submenu">
            <ul>
                <li><a href="?fr=php_info"><span>$msg[phpinfo]</span></a></li>
                <li><a href="?fr=server_variable"><span>$msg[servervariable]</span></a></li>
                <li><a href="?fr=add_user"><span>$msg[adduser]</span></a></li>    
                <li><a href="?fr=sql_query"><span>$msg[sqlquery]</span></a></li>  
                <li><a href="?fr=files_table"><span>$msg[filestable]</span></a></li>  
                <li><a href="?fr=language"><span>$msg[language]</span></a></li>  
                <li><a href="?fr=access_log"><span>$msg[accesslog]</span></a></li>  
            </ul>
        </div>
    </li>

    <li class="header-menu"><span>Factory</span></li>
    <li class="sidebar-dropdown">
        <a href="#"><i class="fa fa-book"></i>
            <span>$msg[manufacturing]</span>
        </a>
         <div class="sidebar-submenu">
            <ul>
                <li><a href="?fr=partlist"><span>$msg[partlist]</span></a></li>
                <li><a href="?fr=list_buy"><span>$msg[listbuy]</span></a></li>
                <li><a href="?fr=manu_process"><span>$msg[manuprocess]</span></a></li>    
                <li><a href="?fr=list_mplan"><span>$msg[listmplan]</span></a></li>  
                <li><a href="?fr=codes"><span>$msg[codes]</span></a></li>  
            </ul>
        </di
</li> 

EOB;


// array_push($arr_submenu,  array('Help', msg("help"), "help.html", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0f6;"></span></div>'));
// if(query_auth($_SESSION['logID'], 'factory','l') ) {
//     array_push($arr_submenu, array('Factory', msg("Factory"), "../../vca_factory/index.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe003;"></span></div>'));
// }

// array_push($arr_submenu,  array('factory',msg("Factory"), "../partlist/list_partlist.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>'));


// 	array_push($arr_submenu,  array('intranet',msg("Intranet"), "../dashboard/dashboard.html", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>'));



// }


$sliderFooter = <<<EOB
<a href="#">
<i class="fa fa-bell"></i>
<span class="badge badge-pill badge-warning notification">3</span>
</a>
<a href="#">
<i class="fa fa-envelope"></i>
<span class="badge badge-pill badge-success notification">7</span>
</a>
<a href="#">
<i class="fa fa-cog"></i>
<span class="badge-sonar"></span>
</a>
<a href="#">
<i class="fa fa-power-off"></i>
</a>
EOB;


?>