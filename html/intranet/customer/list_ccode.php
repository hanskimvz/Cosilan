<!DOCTYPE html>
<?PHP
if(!$_GET['mode']) {
	$mode = 'list';
}

include "./ccode.php";
$act_q .= "&page_max=$page_max";

$page_moving = Pagination($page_no, $total_page,"&".$act_q);

$action = "list_ccode.php?act=".$_GET['act'];

if($act == "ccode_management") {
	$top_menu_ui = display_topmenu();
	$sub_menu_ui = display_submenu($arr_submenu, 'Codes/ccode');
	$tab_menu_ui = display_tabmenu($arr_submenu, 'Codes/ccode');
}
else {
	$dashboard_nomargin = "no-margin";
}

?>
<head>
<meta charset="utf-8">
<title><?PHP echo  msg('Title')?></title>
<meta name="author" content="">
<meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
<meta name="description" content="">
<meta name="author" content="">

<link rel="stylesheet" href="../css/bootstrap.min.css" >
<link rel="stylesheet" href="../css/jquery-ui.css"> 
<link rel="stylesheet" href="../css/jquery.gritter.css">
<link rel="stylesheet" href="../css/font-awesome.min.css" >		
<link rel="stylesheet" href="../css/style.css" >
<link rel="stylesheet" href="../css/widgets.css">



<script src="../js/wysiwyg/wysihtml5-0.3.0.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/jquery.scrollUp.js"></script>

<script src="../js/wysiwyg/bootstrap-wysihtml5.js"> </script>
<script src="../js/date-picker/date.js"  type="text/javascript"></script>
<script src="../js/date-picker/daterangepicker.js" type="text/javascript"></script>

<script src="../js/excanvas.js"></script>
<script src="../js/Chart.js"></script>
<script src="../js/jquery.popupoverlay.js"></script>
<script src="../js/jquery.sparkline.js"></script>
</head>
<body>
<?PHP echo $top_menu_ui?>
<div class="container-fluid">
<?PHP echo $sub_menu_ui?>
<div class="dashboard-wrapper <?PHP echo $dashboard_nomargin?>">
<div class="main-container">
<?PHP echo $tab_menu_ui?>
<div class="row-fluid">
<form name = "form1" method = "post" action = "<?PHP echo $action?>" >

<div class="span6"><?PHP echo $page_moving?></div>
<div class="span2"><input type='text' name='search' size='20' value ='<?PHP echo $search?>'  class="input-large"></div>
<div class="span2"><?PHP echo mk_btn(msg('submit'), "submit()", 1)?><button class="my_window_open">A</button></div>

 <div class="span2 pull-right">
 <?PHP
if( query_auth($_SESSION['logID'],'ccode_info','wm') ) {
	print  mk_btn(msg('Add Customer Code'), "window.open('./ccode_info.php?mode=add')",3);
 }
print "&nbsp;";
print  msg('Total').":".number_format($total_record);
?> 
</div>
 </form>
 </div>
 <?PHP
 if($_SESSION['logID'] == $admin) {
 ?>
 <input type="text" name="xxxxxx" value="<?PHP echo $sq?>" class="input-block-level">
<?PHP
 }?>
 <div class="row-fluid">
 <?PHP
$arr_label = array(msg('Code'), msg('Company Name'), msg('Representative'), msg('Telephone')."1", msg('Telephone')."2", msg('Fax'), msg('Contact'), msg('Person in Charge'), "f/h");
for ($i=0; $i<$sizeof_company; $i++) {
	$arr_data[$i] = array($arr_company[$i][code], $arr_company[$i][company_name], $arr_company[$i][ceo], $arr_company[$i][phone1], $arr_company[$i][phone2], $arr_company[$i][fax], $arr_company[$i][contact_person], $arr_company[$i][youdi_charge_id], $arr_company[$i][family]."/".$arr_company[$i][hit]);
}

print mk_table_notitle($arr_label, $arr_data);
 ?>
 </div>

<!-- Add content to the popup -->
<div id="my_window" class="well" style="max-width:44em;">
<form class="form-horizontal"  name='form2'  action="<?PHP echo $action?>" target="ww_window" method="post" ENCTYPE="multipart/form-data">
<table class="table table-condensed table-striped table-bordered table-hover no-margin">
<TR>
<TD><?PHP echo msg('Code')?></TD>
<TD colspan="3"><input name = "code" type ="text"   class="input-medium"></TD>
</tr>
<tr>
<TD><?PHP echo msg('Company Name')?></TD>
<TD colspan="3"><input name = "company_name" type ="text"  class="input-xlarge"></TD>
</tr>
<tr>
<TD><?PHP echo msg('Address')?></TD>
<TD colspan="3"><input name = "address" type ="text"  class="input-xlarge"></TD>
</tr>
<tr>
<TD><?PHP echo msg('Contact')?></TD>
<TD colspan="3"><input name = "contact" type ="text"  class="input-xlarge"></TD>
</tr>

<tr>
<td><?PHP echo msg('Person In Charge')?></td>
<td colspan="3"><input type="checkbox" name="person_id_check"><?PHP echo user_info('person_id', $_SESSION['logID'],'select')?></td>
</tr>
</table>
<br />
<div align="center"><button class="btn-info" onclick="submit()"><?PHP echo msg('confirm')?></button>&nbsp;&nbsp;&nbsp;<button class="my_window_close"><?PHP echo msg('cancel')?></button></div>
</form>
</div>



<script>
	$(document).ready(function() {

	  // Initialize the plugin
	  $('#my_window').popup();
	});

</script>

</div><!--main-container-->
</div><!--dashboard-wrapper-->
</div><!--container-fluid-->
<?PHP
print display_foot();
?>
</body>
		<!-- Javascript files -->
		<!-- jQuery -->
		<script src="../js/jquery.js"></script>
		<!-- Bootstrap JS -->
		<script src="../js/bootstrap.min.js"></script>
		<!-- jQuery UI -->
		<script src="../js/jquery-ui.min.js"></script> 
		<!-- jQuery Gritter -->
		<script src="../js/jquery.gritter.min.js"></script>
		<!-- Respond JS for IE8 -->
		<script src="../js/respond.min.js"></script>
		<!-- HTML5 Support for IE -->
		<script src="../js/html5shiv.js"></script>
		<!-- Custom JS -->
		<script src="../js/custom.js"></script>
</html>

