<?PHP

function display_topmenu()
{
    global $connect;
    global $logo_img_inv;
    global $user_table;
    global $files_table;
    global $message_table;
    global $files_dir;
   
    $sq = "select * from ".$message_table." where rec_ID = '".$_SESSION['logID']."' and family = 'message' and staus >0";
    $rs = mysqli_query($connect, $sq);
    $num_message = $rs->num_rows;
    $sq = "select * from ".$message_table." where rec_ID = '".$_SESSION['logID']."' and family = 'alarm' and staus > 0 ";
    $rs = mysqli_query($connect, $sq);
    $num_alarm = $rs->num_rows;

    $num_message = (!$num_message) ? 0 : $num_message;
    $num_alarm = (!$num_alarm) ? 0 : $num_alarm;

    $sq = "select ".$files_table.".url from ".$files_table." inner join ".$user_table." where ".$files_table.".file_key = ".$user_table.".file_key and ".$user_table.".ID='".$_SESSION['logID']."'and files.family='user'and files.file_Name='picture'";
    $rs = mysqli_query($connect, $sq);

    $url = mysqli_fetch_row($rs)[0];
    if($url) {
        $dest = $files_dir.$url;
        $fp = fopen($dest,"r");
        $img =  fread($fp, filesize($dest));
        fclose($fp);
        $img ="<img src='data:image/png;base64,".base64_encode($img)."' alt='profile'>";
    }
    $str = '<header><a href="index.html" class="logo"><img src="'.$logo_img_inv.'" width="60" height="30" alt="Logo"/></a><div class="user-profile"><a data-toggle="dropdown" class="dropdown-toggle">'.$img.'</a><span class="caret"></span><ul class="dropdown-menu pull-right"><li><a href="../users/edit-profile.html?mode=view&ID='.$_SESSION['logID'].'">'.msg("Account Settings").'</a></li><li><a href="../users/logout.php">'.msg("Logout").'</a></li></ul></div><ul class="mini-nav"><li><div onclick="window.open(\'../users/list_message.php\',\'_message\', \'width=600, height=600\')"><div class="fs1" aria-hidden="true" data-icon="&#xe040;"></div><span class="info-label">'.$num_message.'</span></div></li><li><a href="#"><div class="fs1" aria-hidden="true" data-icon="&#xe04c;"></div><span class="info-label-green">'.$num_alarm.'</span></a></li></ul></header>';
    return $str;
}

$msg_language = read_language_pack('submenu', $_SESSION['selected_language']);
$arr_submenu = array();

//	$arr_submenu[0] = array('dashboard',msg("Dashboard"), "../dashboard/dashboard.html", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>');
array_push($arr_submenu, array('order', msg("Order"), "/html/logistic/list_lcode.php",  '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe037;"></span></div>'));

if(query_auth($_SESSION['logID'], 'ware_info','l') ) {
    array_push($arr_submenu, array('warehouse', msg("warehouse"), "/html/warehouse/list_ware.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe021;"></span></div>',
                                array('stock', msg("stock Info"), "/html/warehouse/list_stock.php"),
                                array('ware', msg("List Warehouse"), "/html/warehouse/list_ware.php"),
                                array('sample', msg("list sample"), "/html/logistic/list_sample.php"),
                                array('serial', msg("list serial"), "/html/warehouse/list_serial.php"),
                            ));
}

array_push($arr_submenu, array('Codes', msg("Codes"), "/html/customer/list_ccode.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe086;"></span></div>',
                                array('ccode', msg("Ccode"), "/html/customer/list_ccode.php?cksel=1"),
                                array('pcode', msg("Pcode"), "/html/item_code/list_item.php?cksel=1"),
                                array('scode', msg("Scode"), "/html/item_code/list_item.php?cksel=2"),
                                array('mcode', msg("Mcode"), "/html/item_code/list_item.php?cksel=4"),
                                array('all_code', msg("ALL"), "/html/item_code/list_item.php?cksel=0"),
                            ));
array_push($arr_submenu, array('document', msg("Document Management"), "/html/document/list_doc.php?cat=sales", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe025;"></span></div>',
                                array('sales_doc', msg("Sales Doc."), "/html/document/list_doc.php?cat=sales"),
                                array('admin_doc', msg("Admin Doc."), "/html/document/list_doc.php?cat=admin"),
                                array('product_doc', msg("Product Doc."), "/html/document/list_doc.php?cat=product"),
//									array('quotation', msg("Write Quotation"), "../document/issue_sales_doc.php?mode=add&family=QT"),
//									array('PI	', msg("Write PI"), "../document/issue_sales_doc.php?mode=add&family=PI"),
//									array('PO', msg("Write PO"), "../document/issue_sales_doc.php?mode=add&family=PO"),
                            ));								
if(query_auth($_SESSION['logID'], 'sales_report','l') ) {
    array_push($arr_submenu, array('sales_rev', msg("SalesRevenue"), "/html/sales/sales_revenue_gross.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe025;"></span></div>',
                                array('sales_rev', msg("Sales Revenue"), "/html/sales/sales_revenue_gross.php"),
                                array('sales_personal', msg("Sales Person"), "/html/sales/sales_revenue_person.php"),
                                array('sales_monthly', msg("Sales Monthly"), "/html/sales/sales_revenue_month.php"),
                                array('sales_company', msg("Sales Customer"), "/html/sales/sales_revenue_company.php"),
                                array('sales_product', msg("Sales Product"), "/html/sales/sales_revenue_product.php"),
                                array('price_history', msg("Price History"), "/html/sales/sales_price_history.php?mode=price_history"),
                        ));
}
if(query_auth($_SESSION['logID'], 'sales_report','l') ) {
    array_push($arr_submenu[sizeof($arr_submenu)-1],	array('rebate', msg("List Rebate"), "/html/sales/list_rebate.php?mode=list_rebate"));
}
	
array_push($arr_submenu,  array('Help', msg("help"), "help.html", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0f6;"></span></div>'));
if(query_auth($_SESSION['logID'], 'factory','l') ) {
    array_push($arr_submenu, array('Factory', msg("Factory"), "../../vca_factory/index.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe003;"></span></div>'));
}
array_push($arr_submenu,  array('Management', msg("Management"), "/libs/php_info.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe090;"></span></div>',));
array_push($arr_submenu[sizeof($arr_submenu)-1],	array('user',msg("Users"), "/html/users/list_user.php",  '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe072;"></span></div>'));
if($_SESSION['logID'] == $admin) {
    array_push($arr_submenu[sizeof($arr_submenu)-1],	array('php_info', msg("PHP INFO"), "/libs/php_info.php"));
    array_push($arr_submenu[sizeof($arr_submenu)-1],	array('server_variable', msg("SERVER VARIABLE"), "/libs/server_variable_info.php"));
    array_push($arr_submenu[sizeof($arr_submenu)-1],	array('add_user', msg("Add User"), "/html/users/user.php?mode=add"));
    array_push($arr_submenu[sizeof($arr_submenu)-1],	array('s_query', msg("Query database"), "/libs/sql_query_input.php"));
    array_push($arr_submenu[sizeof($arr_submenu)-1],	array('files_table', msg("FILES TBL"), "/libs/list_files.php?mode=list"));
    array_push($arr_submenu[sizeof($arr_submenu)-1],	array('language', msg("LANGUAGE"), "/language/list_lang.php?mode=list"));
    array_push($arr_submenu[sizeof($arr_submenu)-1],	array('access_log', msg("ACESS LOG"), "/libs/access_log.php?mode=list"));
}

if($_SESSION['logID'] == $admin) {
	array_push($arr_submenu,  array('mail_marketing', msg("Mail Marketing"), "../mail_marketing/list_mail_customer.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe025;"></span></div>',
									array('receiver_list', msg("Receiver List"), "../mail_marketing/list_mail_customer.php"),
									array('sales_monthly', msg("Sales Monthly"), "../sales/sales_revenue_month.php"),
									array('price_history', msg("Price History"), "../sales/sales_price_history.php?mode=price_history"),
//									array('quotation', msg("Write Quotation"), "../document/issue_sales_doc.php?mode=add&family=QT"),
//									array('PI	', msg("Write PI"), "../document/issue_sales_doc.php?mode=add&family=PI"),
//									array('PO', msg("Write PO"), "../document/issue_sales_doc.php?mode=add&family=PO"),
								));				
	array_push($arr_submenu, array('Yudi',msg("Youdi"), "../../intranet/",  '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe037;"></span></div>'));
}

array_push($arr_submenu,  array('factory',msg("Factory"), "../partlist/list_partlist.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>'));


if($_SESSION['DB_GROUP'] == "factory") {

	$msg_language = read_language_pack('submenu', $_SESSION['selected_language']);
	$arr_submenu = array();

	array_push($arr_submenu,  array('partlist',msg("Partlist"), "../partlist/list_partlist.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>'));
	array_push($arr_submenu,  array('partprice',msg("Part Price"), "../partlist/list_buy.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>'));
	array_push($arr_submenu,  array('m_proc_doc',msg("Manu Process"), "../m_process/list_mproc_doc.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>'));
	array_push($arr_submenu,  array('m_plan',msg("Manu Plan"), "../m_plan/list_mplan.php", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>'));
	array_push($arr_submenu,  array('Codes', msg("Codes"), "../item_code/list_item.php?cksel=1", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe086;"></span></div>',
									array('pcode', msg("Pcode"), "../item_code/list_item.php?cksel=1"),
									array('scode', msg("Scode"), "../item_code/list_item.php?cksel=2"),
									array('mcode', msg("Mcode"), "../item_code/list_item.php?cksel=4"),
									array('all_code', msg("ALL"), "../item_code/list_item.php?cksel=0"),
								));


	array_push($arr_submenu,  array('intranet',msg("Intranet"), "../dashboard/dashboard.html", '<div class="icon"><span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span></div>'));



}

if($_SESSION['DB_GROUP'] == "marketing") {


}







function display_submenu($arr_submenu, $active)
{
	$active_ex = explode("/",$active);

	for($i=0; $i<20; $i++) 	{
		if(!$arr_submenu[$i][0] ) {
			continue;
		}
		$active_t = "";
		$sub_t ="";
		$tmp_sub="";
		$open_t = "";
		$sel_t = "";
		$class_t = "";
		
		if(trim($active_ex[0]) == trim($arr_submenu[$i][0])) {
			$active_t = "active";
		}
		
		if(is_array($arr_submenu[$i][4])) {
			$sub_t = "submenu";

			for($j=4; $j<sizeof($arr_submenu[$i]); $j++) 	{
				$active_s ="";
				if(trim($active_ex[1]) == trim($arr_submenu[$i][$j][0])) {
					$active_s = "class=\"active\"";
					$open_t = "open";
					$sel_t = "class=\"selected\"";
				}
				$tmp_sub .= "<li ".$active_s." ><a href=\"".$arr_submenu[$i][$j][2]."\" >".$arr_submenu[$i][$j][1]."</a></li>\n";
			}
			$tmp_sub = "<ul>".$tmp_sub."</ul>";
		}


		if($sub_t||$active_t||$open_t) {
			$class_t = "class=\"".$sub_t." ".$active_t." ".$open_t."\"";
		}
		$str .= "<li ".$class_t." ><a href=\"".$arr_submenu[$i][2]."\" ".$sel_t.">".$arr_submenu[$i][3]." ".$arr_submenu[$i][1]."</a>	".$tmp_sub."</li>\n";
	}

	$str = "<div id=\"mainnav\" class=\"hidden-phone hidden-tablet\"><ul style=\"display: block;\">".$str."</ul></div>";
	
	$str_js = "<script> $('.submenu > a').click(function(e){ e.preventDefault(); var submenu = $(this).siblings('ul'); var li = $(this).parents('li'); var submenus = $('#mainnav li.submenu ul'); var submenus_parents = $('#mainnav li.submenu'); if(li.hasClass('open')) { if(($(window).width() > 768) || ($(window).width() < 479)) { submenu.slideUp(); } else { submenu.fadeOut(250); } li.removeClass('open'); } else  { if(($(window).width() > 768) || ($(window).width() < 479)) { submenus.slideUp(); submenu.slideDown(); } else { submenus.fadeOut(250); submenu.fadeIn(250); } submenus_parents.removeClass('open'); li.addClass('open'); } }); var ul = $('#mainnav > ul'); $('#mainnav > a').click(function(e) { e.preventDefault(); var mainnav = $('#mainnav'); if(mainnav.hasClass('open')) { mainnav.removeClass('open'); ul.slideUp(250); } else { mainnav.addClass('open'); ul.slideDown(250); } }); </script>";
	
	$str .= $str_js;
	return $str;
}



function display_tabmenu($arr_submenu, $page)
{
	for($i=0; $i<sizeof($arr_submenu); $i++)	{
		$str .='<li><a href="'.$arr_submenu[$i][2].'">'.$arr_submenu[$i][1].'</a></li>';
		if(is_array($arr_submenu[$i][4])) {
			for($j=4; $j<sizeof($arr_submenu[$i]); $j++) {
				$str .='<li><a href="'.$arr_submenu[$i][$j][2].'">'.$arr_submenu[$i][$j][1].'</a></li>';
			}
		}
	}

	$str = ' <div class="navbar hidden-desktop"><div class="navbar-inner"><div class="container"><a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a><div class="nav-collapse collapse navbar-responsive-collapse"><ul class="nav">'.$str.'</ul></div> </div></div></div>';

	return $str;
}

function display_mobile_menu($arr_submenu, $page)
{
}

function display_foot($str)
{
	if(!$str) {
		$str = "&copy; VCA TECHNOLOGY";
	}
	$str = '<footer><p>'.$str.'</p></footer>';

    $str_js = "<script src='../js/jquery.scrollUp.js'></script><script type=\"text/javascript\">$(function () { $.scrollUp({ scrollName: 'scrollUp', topDistance: '300', topSpeed: 300, animation: 'fade', animationInSpeed: 400, animationOutSpeed: 400, scrollText: '".msg('Scroll to top')."', activeOverlay: false, }); });</script>";
	
	$str .= $str_js;
	return $str;
}


  function dash_box($title, $icon, $value, $color, $span)
  {
	if(substr($icon,0,2) == "&#") {
		$icon = '<span class="fs1" aria-hidden="true" data-icon="'.$icon.'"></span>';
	}
	else {
		$icon = '<i class=" '.$icon.' "></i>';
	}

	$str= '
			<div class="'.$span.'"><div class="widget"><div class="widget-header"><div class="title">'.$icon.' '.$title.'</div></div><div class="widget-body"><div class="current-statistics"><div class="'.$color.'" ><h3>'.$value.'</h3></div></div></div></div></div>';

	return $str;
  }

function alert_heading($info_head, $desc)
{
	$str = '<div class="alert alert-block alert-info fade in"><button data-dismiss="alert" class="close" type="button">x</button><h4 class="alert-heading">'.$info_head.'</h4><p>'.$desc.'</p></div>	';

	return $str;
}

function display_picture($path, $caption, $size)
{
	if(!$size)
			$size='span3';
	$str = ' <div class="'.$size.'"><div class="thumbnail"><img src="'.$path.'"><div class="caption"><p class="no-margin">'.$caption.'</p></div> </div></div>';
	return $str;
}

function mk_btn($label, $action, $type)
{
	if(!$type)  {
		$type = 0;
	}
	$class_type = array(" ","btn-info", "btn-success","btn-warning2","btn-warning","btn-danger","btn-primary", "hidden-tablet");

	$str = "<button type=\"button\" class=\"btn ".$class_type[$type]." \"  onclick=\"".$action."\">".$label."</button>";
	return $str;
}



function Pagination($thisP, $total, $toLink, $section = 10)
{
	$PrePageLinkImg  = "&#9668;";
	$NextPageLinkImg = "&#9658;";
	$PreSectorImg    = "<span style='letter-spacing: -6px' align='left'>&#9668;&#9668;</span>";
	$NextSectorImg   = "<span style='letter-spacing: -6px'>&#9658;&#9658;</span>";
	
	$p = strpos($toLink,"&page_no");
	$to_link_h = substr($toLink,0,$p);
	$to_link_f  = substr($toLink,$p+1, 200);
	$p = strpos($to_link_f,"&");
	$to_link_f  = substr($to_link_f,$p, 200);

	$toLink= $to_link_h.$to_link_f;


	if($total > $section) {
		$re_Total = $total;
		$sector = ceil($total/$section);
		$this_sector = ceil($thisP/$section) ;
		$start = ($this_sector - 1)  * $section + 1;
		if($start + $section > $total) {
			$last = $total;
		}
		else {
			$last = $start + $section -1;
		}
	}

	else {
		$start = 1;
		$last = $total;
		$this_sector = 1;
		$sector = 1;
	}

	for($i=$start; $i<=$last; $i++) 	{
		$toLink_ = "$PHP_SELF?page_no=".$i.$toLink;
		if($i==$thisP) {
			$j = $i;
			$re_PageMove = $re_PageMove. "<li class='active'><a>".$j."</a></li>";
		}
		else {
			$j=$i;
			$re_PageMove = $re_PageMove."<li><a href=".$toLink_.">".$j."</a></li>";
		}

	}
	
	if($thisP>1) {
		$toLink_ = "$PHP_SELF?page_no=".($thisP-1).$toLink;
		$before = "<li><a href=".$toLink_.">".$PrePageLinkImg."</a></li>";
	}
	
	else {
		$before = "<li class='disabled'><a>".$PrePageLinkImg."</a></li>";
	}
	
	if($thisP<$total) {
		$toLink_ = "$PHP_SELF?page_no=".($thisP+1).$toLink;
		$next = "<li><a href=".$toLink_.">".$NextPageLinkImg."</a></li>";
	}
	
	else {
		$next = "<li class='disabled'><a>".$NextPageLinkImg."</a></i>";
	}

	$re_PageMove = $before_sector.$before.$before_dot.$re_PageMove.$next_dot.$next.$next_sector;
	$re_PageMove ="<div class='pagination no-margin'><ul>".$re_PageMove."</ul></div>";
	return $re_PageMove;
}




/////////////////////////////////////////////  CHART //////////////////////////////////////////////////////////////////////////////////////////////////////

function line_chart($title, $x_label,$arr_score, $arr_line, $style, $str_control)
{
	$chart_inst = "chart".mktime().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
	$n_x_label = sizeof($x_label);
	$n_score = sizeof($arr_score);
	$tmp_color = array("rgb(255,0,0)","rgb(60,60,230)","rgb(151,181,121)","rgb(107,119,184)","rgb(143,0,64)","rgb(243,181,64)","rgb(200,109,230)","rgb(181,121,158)","rgb(10,50,158)","rgb(10,255,255)");


	for($i=0; $i<$n_x_label ; $i++) {
		$x_label_t .= " '".$x_label[$i]."',";
	}
	$x_label_t =  'labels:['.$x_label_t.']';

	
	for($i=0; $i<$n_score; $i++) {
		for($j=0; $j<$n_x_label; $j++) {
			if(!$arr_score[$i][$j]) {
				$arr_score[$i][$j]= 0;
			}
			$score[$i] .= $arr_score[$i][$j].",";
		}
		$score[$i] = 'data:['.$score[$i].']';
		
		if(!$arr_line[$i][color]) {
			$arr_line[$i][color]= $tmp_color[$i];
		}

		$line_names .= '<span  class="badge" style="background-color:'.$arr_line[$i][color].'" >'.$arr_line[$i][label].'</span>&nbsp';

		$dataset_str .= ' { label: "'.$arr_line[$i][label].'", '.$score[$i].', fillColor: "rgba(220,220,220,0)", strokeColor: "'.$arr_line[$i][color].'", pointColor: "'.$arr_line[$i][color].'", pointStrokeColor: "#fff", pointHighlightFill: "#fff", pointHighlightStroke: "rgba(220,220,220,1)", }, ';
	}

	$dataset_str = "datasets: [".$dataset_str."]";
	
	if(!$style) {
		$style = 'class=span6';
	}
	
	$ex_style = explode(",", $style);
	for($i=0; $i<sizeof($ex_style); $i++) {
		list($_key, $_val) = explode("=", $ex_style[$i]);
		if(trim($_key) == "height") {
			if($_val>100) {
				$_val = ceil($_val/100);
			}
			if($_val>10) {
				$_val = ceil($_val/10);
			}
			$_val *=10;
			$canvas_ht = 'height="'.$_val.'"';
		}
		else if($_key == "class") {
			$style = 'class="'.$_val.'"';
		}
	}


	$str = ' <div '.$style.' ><div class="widget"><div class="widget-header"><div class="title">'.$title.'</div><div class="tools pull-right">'.$str_control.'</div></div><div class="widget-body"><canvas id="'.$chart_inst.'" '.$canvas_ht.'></canvas><p align="center">'.$line_names.'</p></div></div></div>';

	$str_js = '<script> var data = {'.$x_label_t.','.$dataset_str.'}; var canvas = document.getElementById("'.$chart_inst.'"); if (typeof G_vmlCanvasManager !== "undefined") { canvas = G_vmlCanvasManager.initElement(canvas); } var ctx = canvas.getContext("2d"); var lineChart = new Chart(ctx).Line(data, { responsive: true, }); console.log(lineChart.generateLegend()); </script>';

	$str .=$str_js;
	return $str;
}


function bar_chart($title, $x_label,$arr_score, $arr_bar, $style, $str_control)
{
	$chart_inst = "chart".mktime().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
	$tmp_color = array("rgb(255,0,0)","rgb(60,60,230)","rgb(151,181,121)","rgb(107,119,184)","rgb(143,0,64)","rgb(243,181,64)","rgb(200,109,230)","rgb(181,121,158)");
	$n_x_label = sizeof($x_label);
	$n_score = sizeof($arr_score);

	for($i=0; $i<$n_x_label ; $i++) {
		$x_label_t .= "'".$x_label[$i]."',";
	}
	$x_label_t =  'labels:['.$x_label_t.']';
	
	for($i=0; $i<$n_score; $i++) {
		for($j=0; $j<$n_x_label; $j++) {
			if(!$arr_score[$i][$j]) {
				$arr_score[$i][$j]= 0;
			}
			$score[$i] .= $arr_score[$i][$j].",";
		}
		$score[$i] = 'data:['.$score[$i].']';
		if(!$arr_bar[$i][color]) {
			$arr_bar[$i][color]= $tmp_color[$i];
		}
		$line_names .= '<span  class="badge" style="background-color:'.$arr_bar[$i][color].'" >'.$arr_bar[$i][label].'</span>&nbsp';
	
		$arr_bar[$i][color] = trim($arr_bar[$i][color]);
		if( '#' == substr($arr_bar[$i][color],0,1))  {
			$r = hexdec(substr($arr_bar[$i][color],1,2));
			$g = hexdec(substr($arr_bar[$i][color],3,2));
			$b = hexdec(substr($arr_bar[$i][color],5,2));
		}
		else if('rgb' == substr($arr_bar[$i][color],0,3))  {
			$tmp= substr($arr_bar[$i][color], 0, strpos($arr_bar[$i][color],')'));
			$tmp = 	substr($tmp, strpos($tmp,'(' )+1, strlen($tmp));
			list($r,$g,$b) = explode(',', $tmp);
		}

		$arr_bar[$i][color] = 'rgba('.$r.','.$g.','.$b.',1)';
		$arr_bar[$i][highlight] = 'rgba('.$r.','.$g.','.$b.', 0.5)';


		$dataset_str .= ' { label:"'.$arr_bar[$i][label].'", '.$score[$i].', fillColor:"'.$arr_bar[$i][color].'", strokeColor :"'.$arr_bar[$i][color].'", highlightFill:"'.$arr_bar[$i][highlight].'", highlightStroke: "rgba(220,220,220,1)", }, ';
	}
	$dataset_str = "datasets: [".$dataset_str."]";
	
	if(!$style) {
		$style = 'class="span6"';
	}
	if('span' == substr($style,0,4)) {
		$style = 'calss="'.$style.'"';
	}
	

	$str = '<div '.$style.'><div class="widget"><div class="widget-header"><div class="title">'.$title.'</div><div class="tools pull-right">'.$str_control.'</div></div><div class="widget-body"><canvas id="'.$chart_inst.'"></canvas><p align="center">'.$line_names.'</p></div></div></div>	';

	$str_js = ' var data = {'.$x_label_t.','.$dataset_str.'}; var canvas = document.getElementById("'.$chart_inst.'"); if (typeof G_vmlCanvasManager !== "undefined") { canvas = G_vmlCanvasManager.initElement(canvas); }  var ctx = canvas.getContext("2d");  var lineChart = new Chart(ctx).Bar(data, { responsive: true, }); ';
	$str_js = '<script>'.$str_js.'</script>';	

	$str .=$str_js;
	return $str;
}

function doughnut_chart($title,$arr_data,$style, $str_control)
{
	$chart_inst = "chart".mktime().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

	for($i=0; $i<sizeof($arr_data); $i++)  {
		if(!$arr_data[$i][highlight]) {
			$arr_data[$i][color] = trim($arr_data[$i][color]);
			if($arr_data[$i][color][0] == '#')  {
				$r = hexdec(substr($arr_data[$i][color],1,2));
				$g = hexdec(substr($arr_data[$i][color],3,2));
				$b = hexdec(substr($arr_data[$i][color],5,2));
			}
			if('rgb' == substr($arr_data[$i][color],0,3))  {
				$tmp= substr($arr_data[$i][color], 0, strpos($arr_data[$i][color],')'));
				$tmp = 	substr($tmp, strpos($tmp,'(' )+1, strlen($tmp));
				list($r,$g,$b) = explode(',', $tmp);
			}
			$r += 20;  $g += 30; $b += 30;
			$arr_data[$i][highlight] = 'rgb('.$r.','.$g.','.$b.')';
		}
		$dataset_str .= '{label:\''.$arr_data[$i][label].'\', value:'.$arr_data[$i][value].', color: \''.$arr_data[$i][color].'\', highlight:\''.$arr_data[$i][highlight].'\' },';
		$line_names .= '<span  class="badge" style="background-color:'.$arr_data[$i][color].'" >'.$arr_data[$i][label].'</span>&nbsp';
	}
	
	if(!$style) {
		$style = 'class="span6"';
	}
	if('span' == substr($style,0,4)) {
		$style = 'calss="'.$style.'"';
	}
	
	$str = ' <div '.$style.'><div class="widget"><div class="widget-header"><div class="title">'.$title.'</div><div class="tools pull-right">'.$str_control.'</div>	</div><div class="widget-body"><canvas id="'.$chart_inst.'"></canvas><p align="center">'.$line_names.'</p></div></div></div> ';
	

$str_js = ' <script>	var data = ['.$dataset_str.']; 	var canvas = document.getElementById("'.$chart_inst.'"); if (typeof G_vmlCanvasManager !== "undefined") { canvas = G_vmlCanvasManager.initElement(canvas); };	var ctx = canvas.getContext("2d"); var lineChart = new Chart(ctx).Doughnut(data, { responsive: true,}); </script>';

	$str .=$str_js;
	return $str;


}

function pie_chart($title,$arr_data,$style, $str_control)
{
	$chart_inst = "chart".mktime().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
	$tmp_color = array("rgb(255,0,0)","rgb(60,60,230)","rgb(151,181,121)","rgb(107,119,184)","rgb(143,0,64)","rgb(243,181,64)","rgb(200,109,230)","rgb(181,121,158)");
	
	for($i=0; $i<sizeof($arr_data); $i++)  {
		if(!$arr_data[$i][color]) {
			$arr_data[$i][color] = $tmp_color[$i];
		}

		if(!$arr_data[$i][highlight]) {
			$arr_data[$i][color] = trim($arr_data[$i][color]);
			if($arr_data[$i][color][0] == '#')  {
				$r = hexdec(substr($arr_data[$i][color],1,2));
				$g = hexdec(substr($arr_data[$i][color],3,2));
				$b = hexdec(substr($arr_data[$i][color],5,2));
			}
			if('rgb' == substr($arr_data[$i][color],0,3))  {
				$tmp= substr($arr_data[$i][color], 0, strpos($arr_data[$i][color],')'));
				$tmp = 	substr($tmp, strpos($tmp,'(' )+1, strlen($tmp));
				list($r,$g,$b) = explode(',', $tmp);
			}
			$r += 20;  $g += 30; $b += 30;
			$arr_data[$i][highlight] = 'rgb('.$r.','.$g.','.$b.')';
		}
		$dataset_str .= '{label:\''.$arr_data[$i][label].'\', value:'.$arr_data[$i][value].', color: \''.$arr_data[$i][color].'\', highlight:\''.$arr_data[$i][highlight].'\' },';
		$line_names .= '<span  class="badge" style="background-color:'.$arr_data[$i][color].'" >'.$arr_data[$i][label].'</span>&nbsp';
	}
	
	if(!$style) {
		$style = 'class="span6"';
	}
	if('span' == substr($style,0,4)) {
		$style = 'calss="'.$style.'"';
	}
	
	$str = ' <div '.$style.'><div class="widget"><div class="widget-header"><div class="title">'.$title.'</div><div class="tools pull-right">'.$str_control.'</div>	</div><div class="widget-body"><canvas id="'.$chart_inst.'"></canvas><p align="center">'.$line_names.'</p></div></div></div> ';
	

$str_js = ' <script>	var data = ['.$dataset_str.']; 	var canvas = document.getElementById("'.$chart_inst.'"); if (typeof G_vmlCanvasManager !== "undefined") { canvas = G_vmlCanvasManager.initElement(canvas); };	var ctx = canvas.getContext("2d"); var lineChart = new Chart(ctx).Pie(data, { responsive: true,}); </script>';

	$str .=$str_js;
	return $str;

}


function polar_chart($title,$arr_data,$style, $str_control)
{
	$chart_inst = "chart".mktime().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

	for($i=0; $i<sizeof($arr_data); $i++)  {

		if(!$arr_data[$i][highlight]) {
			$arr_data[$i][color] = trim($arr_data[$i][color]);
			if($arr_data[$i][color][0] == '#')  {
				$r = hexdec(substr($arr_data[$i][color],1,2));
				$g = hexdec(substr($arr_data[$i][color],3,2));
				$b = hexdec(substr($arr_data[$i][color],5,2));
			}
			if('rgb' == substr($arr_data[$i][color],0,3))  {
				$tmp= substr($arr_data[$i][color], 0, strpos($arr_data[$i][color],')'));
				$tmp = 	substr($tmp, strpos($tmp,'(' )+1, strlen($tmp));
				list($r,$g,$b) = explode(',', $tmp);
			}
			$r += 20;  $g += 30; $b += 30;
			$arr_data[$i][highlight] = 'rgb('.$r.','.$g.','.$b.')';
		}
		
		$dataset_str .= '{label:\''.$arr_data[$i][label].'\', value:'.$arr_data[$i][value].', color: \''.$arr_data[$i][color].'\', highlight:\''.$arr_data[$i][highlight].'\' },';
		$line_names .= '<span  class="badge" style="background-color:'.$arr_data[$i][color].'" >'.$arr_data[$i][label].'</span>&nbsp';
	}
	
	if(!$style) {
		$style = 'class="span6"';
	}
	if('span' == substr($style,0,4)) {
		$style = 'calss="'.$style.'"';
	}
	
	$str = ' <div '.$style.'><div class="widget"><div class="widget-header"><div class="title">'.$title.'</div><div class="tools pull-right">'.$str_control.'</div>	</div><div class="widget-body"><canvas id="'.$chart_inst.'"></canvas><p align="center">'.$line_names.'</p></div></div></div> ';
	

$str_js = ' <script>	var data = ['.$dataset_str.']; 	var canvas = document.getElementById("'.$chart_inst.'"); if (typeof G_vmlCanvasManager !== "undefined") { canvas = G_vmlCanvasManager.initElement(canvas); };	var ctx = canvas.getContext("2d"); var lineChart = new Chart(ctx).PolarArea(data, { responsive: true,}); </script>';

	$str .=$str_js;
	return $str;

}



function radar_chart($title, $r_label,$arr_score, $arr_radar,$style, $str_control)
{
	$chart_inst = "chart".mktime().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
	$n_x_label = sizeof($r_label);
	$n_score = sizeof($arr_score);

	for($i=0; $i<$n_x_label ; $i++) {
		$x_label_t .= " '".$r_label[$i]."',";
	}
	$x_label_t =  'labels:['.$x_label_t.']';

	for($i=0; $i<$n_score; $i++) {
		for($j=0; $j<$n_x_label; $j++) {
			if(!$arr_score[$i][$j]) {
				$arr_score[$i][$j]= 0;
			}
			$score[$i] .= $arr_score[$i][$j].",";
		}
		$score[$i] = 'data:['.$score[$i].']';
		$line_names .= '<span  class="badge" style="background-color:'.$arr_radar[$i][color].'" >'.$arr_radar[$i][label].'</span>&nbsp';
	
		$arr_radar[$i][color] = trim($arr_radar[$i][color]);
		if( '#' == substr($arr_radar[$i][color],0,1))  {
			$r = hexdec(substr($arr_radar[$i][color],1,2));
			$g = hexdec(substr($arr_radar[$i][color],3,2));
			$b = hexdec(substr($arr_radar[$i][color],5,2));
		}
		else if('rgb' == substr($arr_radar[$i][color],0,3))  {
			$tmp= substr($arr_radar[$i][color], 0, strpos($arr_radar[$i][color],')'));
			$tmp = 	substr($tmp, strpos($tmp,'(' )+1, strlen($tmp));
			list($r,$g,$b) = explode(',', $tmp);
		}

		$arr_radar[$i][color] = 'rgba('.$r.','.$g.','.$b.',1)';
		$arr_radar[$i][fillColor] = 'rgba('.$r.','.$g.','.$b.', 0.3)';


		$dataset_str .= '{ label: "'.$arr_radar[$i][label].'", '.$score[$i].', fillColor:  "'.$arr_radar[$i][fillColor].'", strokeColor: "'.$arr_radar[$i][color].'", pointColor: "'.$arr_radar[$i][color].'", pointStrokeColor: "#fff", pointHighlightFill: "#fff", pointHighlightStroke: "rgba(220,220,220,1)", }, ';
	}

	$dataset_str = "datasets: [".$dataset_str."]";
	
	if(!$style) {
		$style = 'class="span6"';
	}
	if('span' == substr($style,0,4)) {
		$style = 'calss="'.$style.'"';
	}


	$str = ' <div '.$style.'><div class="widget"><div class="widget-header"><div class="title">'.$title.'</div><div class="tools pull-right">'.$str_control.'</div></div><div class="widget-body"><canvas id="'.$chart_inst.'"></canvas><p align="center">'.$line_names.'</p></div></div></div> ';

	$str_js = '<script>var data = {'.$x_label_t.','.$dataset_str.'};var canvas = document.getElementById("'.$chart_inst.'");if (typeof G_vmlCanvasManager !== "undefined") {canvas = G_vmlCanvasManager.initElement(canvas);}var ctx = canvas.getContext("2d");var lineChart = new Chart(ctx).Radar(data, {responsive: true,});console.log(lineChart.generateLegend());</script>
	';

	$str .=$str_js;
	return $str;
}

///////////////////////////////////////////////////////////////////



function mk_table($title, $label, $arr_data, $tool_str)
{
	for($i=0; $i<sizeof($label); $i++)	{
		$thead .= '<th>'.$label[$i].'</th>';
	}
	$thead = '<thead><tr>'.$thead.'</tr></thead>';
	
	for($i=0; $i<sizeof($arr_data); $i++)  {
		$tbody .= '<tr>';
		for($j=0; $j<sizeof($arr_data[$i]);$j++)  {
			$tbody .= '<td>'.$arr_data[$i][$j].'</td>';
		}
		$tbody .= '</tr>';
	}
	$tbody = '<tbody>'.$tbody.'</tbody>';
	if($tool_str) {
		$tool_str = '<div class="tools pull-right"><div class="input-append">'.$tool_str.'</div></div>';
	}

	$str = '
           <div class="span12">
              <div class="widget">
                <div class="widget-header"><div class="title">'.$title.'</div>'.$tool_str.'</div>
                <div class="widget-body">
                  <table class="table table-condensed table-striped table-bordered table-hover no-margin">
					'.$thead.$tbody.'
                    </table>
                </div>
              </div>
            </div>
		';

	return $str;

}

function mk_table_notitle($label, $arr_data)
{
	$colspan=1;
	for($i=0; $i<sizeof($label); $i++)	{
		if($label[$i+1] != '--') {
			if($colspan>1) {
				$thead .= '<th colspan="'.$colspan.'">'.$label[$i-$colspan+1].'</th>';
				$colspan = 1;
			}
			else {
				$thead .= '<th>'.$label[$i].'</th>';
			}
		}
		else {
			$colspan ++;
		}
		
	}
	$thead = '<thead><tr>'.$thead.'</tr></thead>';
	
	for($i=0; $i<sizeof($arr_data); $i++)  {
		$tbody .= '<tr>';
		for($j=0; $j<sizeof($arr_data[$i]);$j++)  {
			$tbody .= '<td>'.$arr_data[$i][$j].'</td>';
		}
		$tbody .= '</tr>';
	}
	$tbody = '<tbody>'.$tbody.'</tbody>';

	$str = '
           <div class="span12">
                  <table class="table table-condensed table-striped table-bordered table-hover no-margin">
					'.$thead.$tbody.'
                  </table>
            </div>
		';

	return $str;

}



function mk_overview($head,$data )
{
	for($i=0; $i<sizeof($data); $i++) {
		$chart_inst = "B".mktime().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$sp_dat = "";
		for($j=0; $j<sizeof($data[$i][value]); $j++)  {
			
			$sp_dat .= $data[$i][value][$j].",";

		}
		$str .= '<li><div class="left"><h4>'.$data[$i][score].'</h4><p>'.$data[$i][label].'</p></div><div class="chart"><span id="'.$chart_inst.'"> '.$sp_dat.'</span></div></li>';
	
		$str_js .= "$('#".$chart_inst."').sparkline('html', { type: 'bar',  barColor: '".$data[$i][bar_color]."', barWidth: 12, height: 30, });  ";


	}

	$str = '<ul class="stats">'.$str.'</ul>';
	$str = '
	          <div class="span4">
              <div class="widget no-margin">
                <div class="widget-header"><div class="title"><span class="fs1" aria-hidden="true" data-icon="'.$head[icon].'"></span>'.$head[title].'<span class="mini-title">'.$head[sub_title].'</span></div></div>
                <div class="widget-body">'.$str.'</div>
              </div>
            </div>
			';

	$str_js = "
		<script>
			  $(function () {
				  ".$str_js."
			  });
		</script>";

	$str .= $str_js;

	return $str;
}

function p_select($id, $class, $arr_option, $selected)
{
	if(!$class) {
		$class="span3";
	}
	if($selected) {
//		if(is_num($selected)) {
//			$arr_option[$selected][selected] = "selected";
//		}
//		else {
			for($i=0; $i<sizeof($arr_option); $i++) 	{
				if($arr_option[$i][value] == $selected) {
					$arr_option[$i][selected]= "selected";
				}
			}
//		}
	}
	
	for($i=0; $i<sizeof($arr_option); $i++) 	{
		$str .= '<option value="'.$arr_option[$i][value].'" '.$arr_option[$i][selected].'>'.$arr_option[$i][label].'</option>';
	}
	
	$str = '<select id="'.$id.'"  name="'.$id.'"   class="'.$class.'">'.$str.'</select>';
	return $str;
}


function help_message($title_message, $collapse_message)
{
	$message_instant ="M".mktime().rand(0,9).rand(0,9).rand(0,9).rand(0,9);

	$str = '<div class="accordion-group"><div class="accordion-heading"><a href="#'.$message_instant.'" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle"><i class="icon-info-sign icon-white"></i>'.$title_message.'</a></div><div class="accordion-body collapse" id="'.$message_instant.'" style="height: 0px;"><div class="accordion-inner">'.$collapse_message.'</div></div></div>';

	return $str;
}



///////

function check_date($date_str)
{
	if(substr($date_str,0,1) == '0') {
		return "";
	}

	return $date_str;
}

?>
