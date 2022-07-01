<!DOCTYPE html>

<?
if(!$mode) $mode='list';
include "./item.php";

$page_moving = Pagination($page_no, $total_page,"&mode=list&act=$act&search=$search&page_max=$page_max&cksel=$cksel&row=$row");
$action = "./list_item_insert.php?mode=list&act=$act&cksel=$cksel&page_max=$page_max&row=$row";
//$action = "list_item.php?mode=list&cksel=$cksel&page_max=$page_max";

?>

<head>
<meta charset="utf-8">
<title><?= msg('Title')?></title>
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
<div class="container-fluid">
<div class="dashboard-wrapper no-margin">
<div class="main-container">
 
<div class="span6"><?=$page_moving?></div>
<form class="form-horizontal" name="form1" method="post" action="<?=$action?>"   ENCTYPE="multipart/form-data" >
<div class="span2"><input type='text' name='search' size='20' value ='<?=$search?>'  class="input-large"></div>
<div class="span2"><?=mk_btn(msg('submit'), "submit()", 1)?></div>
 </form>
 <div class="span2 pull-right"><?=mk_btn(msg('Add Code'), "window.open('./item_info.php?mode=add')",3)?>&nbsp; <?=msg('Total')?>:<?=number_format($total_record) ?> </div>

 </div>
 
 <div class="row-fluid"><div class="span12">
<?
$arr_label = array(msg('Code'), msg('Model'), msg('Product Name'), msg('Specification'), msg('Brand'), msg('Unit'), 'hit', 'S');
for ($i=0; $i<$size_code; $i++) {
	$arr_data[$i][0] =$arr_code[$i][code];
	$arr_data[$i][1] =$arr_code[$i][model];
	$arr_data[$i][2] =$arr_code[$i][name];
	$arr_data[$i][3] =$arr_code[$i][spec];
	$arr_data[$i][4] =$arr_code[$i][brand];
	$arr_data[$i][5] =$arr_code[$i][unit];
	$arr_data[$i][6] =$arr_code[$i][hit];
	$arr_data[$i][7] =$arr_code[$i][status];
}
echo mk_table_notitle($arr_label, $arr_data);

?>

</div></div>
</div><!--main-container-->
</div><!--dashboard-wrapper-->
</div><!--container-fluid-->
<?
print display_foot();
?>
</body>
</html>


