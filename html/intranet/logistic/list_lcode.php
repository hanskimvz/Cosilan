<!DOCTYPE html>
 <?PHP
if(!$_GET['mode']) {
	$mode = 'list';
}

include "./logistic.php";

$act_q .= "&page_max=$page_max";

$page_moving = Pagination($page_no, $total_page, "&".$act_q);
$action = "./list_lcode.php";


?>
  
<html lang="en">

<head>
<meta charset="utf-8">
<title><?PHP echo msg('Title')?></title>
<meta name="author" content="">
<meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
<meta name="description" content="">
<meta name="author" content="">

<script type="text/javascript" src="../js/html5shiv.js"></script>
<!-- <link href="../css/app.css" rel="stylesheet"> -->
<link href="../css/ionicons.min.css?v=1.5.2" rel="stylesheet">
<link href="../css/font-awesome.min.css" rel="stylesheet">		
<link href="../css/main.css" rel="stylesheet">
<link href="../css/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet">
<link href="../css/wysiwyg/wysiwyg-color.css" rel="stylesheet">

<script src="../js/wysiwyg/wysihtml5-0.3.0.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/jquery.scrollUp.js"></script>

<script src="../js/wysiwyg/bootstrap-wysihtml5.js"> </script>
<script src="../js/date-picker/date.js"  type="text/javascript"></script>
<script src="../js/date-picker/daterangepicker.js" type="text/javascript"></script>

<script src="../js/excanvas.js"></script>
<script src="../js/Chart.js"></script>

<!-- <script src="../js/app.js"></script> -->


</head>

<body  >
<?PHP
print display_topmenu();
?>
<div class="container-fluid">
<?PHP
// print display_submenu($arr_submenu, 'order');
?>
<div class="dashboard-wrapper">
<div class="main-container">
<?PHP
// print display_tabmenu($arr_submenu, 'order');
?>

<div class="row-fluid">
<form class="form-horizontal" name="form1" method="post" action="<?PHP echo $action?>"   ENCTYPE="multipart/form-data" >
<div class="span6"><?PHP echo $page_moving?></div>
<div class="span2"><input type='text' name='search' size='20' value ='<?PHP echo $search?>'  class="input-large"></div>
<div class="span2"><?PHP echo mk_btn(msg('submit'), "submit()", 1)?></div>
 <div class="span2" style="text-align:right;"><?PHP echo mk_btn(msg('Write Order'), "window.open('./lcode_info.php?mode=view')",3)?>&nbsp; <?PHP echo msg('Total')?>:<?PHP echo number_format($total_record) ?> </div>
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
  print "UYTYUIN";
$arr_label = array(msg('LCODE'), msg('Company Name'), msg('Classification'), msg('Process'),'--', '--', '--', '--',  msg('Order Date'), msg('Expected Delivery Date'), msg('Ware Out Date'), msg('Delivery Date'),  msg('Applicant'), msg('Status') );

print "UYTYUIN";
for ($i=0; $i<$sizeof_lcode; $i++) {
	$arr_data[$i][0] = $arr_lcode[$i][code];
	$arr_data[$i][1] = $arr_lcode[$i][company_name];
	$arr_data[$i][2] = $arr_lcode[$i][fenlei];
	$arr_data[$i][3] = $pop_doc[$i][0];
	$arr_data[$i][4] = $pop_doc[$i][1];
	$arr_data[$i][5] = $pop_proc[$i][0];
	$arr_data[$i][6] = $pop_proc[$i][1];
	$arr_data[$i][7] = $pop_doc[$i][2];
	$arr_data[$i][8] = $arr_lcode[$i][order_date];
	$arr_data[$i][9] = $arr_lcode[$i][exp_out_date];
	$arr_data[$i][10] = $arr_lcode[$i][out_date];
	$arr_data[$i][11] = $arr_lcode[$i][ship_date];
	$arr_data[$i][12] = $arr_lcode[$i][person_id];
	$arr_data[$i][13] = $pop_proc[$i][2];
}

echo mk_table_notitle($arr_label,$arr_data);

 ?>
 </div>
</div></div>
</div><!--main-container-->
</div><!--dashboard-wrapper-->
</div><!--container-fluid-->
<?PHP
print display_foot();
?>
</body>
</html>


