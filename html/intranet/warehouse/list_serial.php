<!DOCTYPE html>
<html>
<?PHP
if(!$mode) {
	$mode = 'list_serial';
}


include "./ware.php";
$page_moving = Pagination($page_no, $total_page,"&mode=$mode&item=$item&search=$search&page_max=$page_max&act=$act");
$action = "./list_serial.php?mode=list_serial&item=$item&item2=$item2&search=$search&page_max=$page_max&act=$act";
$act == 'ware_managegement';

?>

<head>
<meta charset="utf-8">
<title><?PHP echo  msg('Title')?></title>
<meta name="author" content="">
<meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
<meta name="description" content="">
<meta name="author" content="">

<script type="text/javascript" src="/js/html5shiv.js"></script>
<link href="/css/icomoon.css" rel="stylesheet">
<link href="/css/ionicons.min.css?v=1.5.2" rel="stylesheet">
<link href="/css/font-awesome.min.css" rel="stylesheet">		
<link href="/css/main.css" rel="stylesheet">
<link href="/css/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet">
<link href="/css/wysiwyg/wysiwyg-color.css" rel="stylesheet">

<script src="/js/wysiwyg/wysihtml5-0.3.0.js"></script>
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/jquery.scrollUp.js"></script>

<script src="/js/wysiwyg/bootstrap-wysihtml5.js"> </script>
<script src="/js/date-picker/date.js"  type="text/javascript"></script>
<script src="/js/date-picker/daterangepicker.js" type="text/javascript"></script>

<script src="/js/excanvas.js"></script>
<script src="/js/Chart.js"></script>

<script src="/js/jquery.sparkline.js"></script>
</head>

<body>
<?PHP
if($_GET['act'] =='serial') {
	echo  '<div class="container-fluid">';
	echo '<div class="dashboard-wrapper no-margin" style="min-height: 540px;">';
	echo '<div class="main-container">';
}
else {
	print display_topmenu();
	echo  '<div class="container-fluid">';
	print display_submenu($arr_submenu, 'warehouse/serial');
	echo '<div class="dashboard-wrapper">';
	echo '<div class="main-container">';
	print display_tabmenu($arr_submenu, 'warehouse/serial');
}
?>

<div class="row-fluid">
<form name = "form1" method = "post" action = "<?PHP echo $action?>" >
<div class="span6"><?PHP echo $page_moving?></div>
<div class="span4"><?PHP echo msg('serial number')?>:&nbsp;&nbsp;<input type='text' name='search' value ='<?PHP echo $search?>'  class="input-large"></div>
<div class="span1"><?PHP echo mk_btn(msg('submit'), "submit()", 1)?></div>
<div class="span1"> <?PHP echo msg('Total')?>:<?PHP echo number_format($total_record) ?> </div>
 </form>
 <?PHP
 if($_SESSION['logID'] == $admin) {
 ?>
 <input type="text" name="xxxxxx" value="<?PHP echo $sq?>" class="input-block-level">
<?PHP
 }?>
<div class="row-fluid">
<?PHP
$arr_label = array(msg('serial number'), msg('item code'), msg('model'), msg('company name'), msg('classification'), msg('date'));

for ($i=0; $i<$sizeof_serial; $i++) {
	$arr_serial[$i][INorOUT] = $arr_serial[$i][INorOUT] ? "(".msg('IN').")" : "(".msg('out').")";
	$arr_data[$i][0] = $arr_serial[$i][serial_number];
	$arr_data[$i][1] = $arr_serial[$i][idx].$arr_serial[$i][item_code];
	$arr_data[$i][2] = $arr_serial[$i][model];
	$arr_data[$i][3] = $arr_serial[$i][company_name];
	$arr_data[$i][4] = $arr_serial[$i][INorOUT].logistic_info('',$arr_serial[$i][fenlei],'text','all');
	$arr_data[$i][5] = date(msg('dateformat'), strtotime($arr_serial[$i][l_date]));
}

print mk_table_notitle($arr_label, $arr_data);
?>
</div>
</div><!--main-container-->
</div><!--dashboard-wrapper-->
</div><!--container-fluid-->
<?PHP
print display_foot();
?>
</body>
</html>
