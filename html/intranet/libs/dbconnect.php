<?PHP
// include "/var/www/libs/db_info.php";
$host = 'localhost';
$user = 'int_user';
$pass = '13579';
$dbname = 'intranet';

$connect = @mysqli_connect($host, $user, $pass, $dbname);

if(!$connect) {
	echo "DB ".$dbname." Select Error";
	exit;
}

$admin = "hanskim";
$user_table = "users";
$auth_table = "auth";
$language_table = "language";
$message_table = "message";

$ccode_table = "company";
$cart_table = "cart";
$lcode_table = "logistic";
$item_table = "item_code";
$ware_table = "warehouse";
$stock_table = "stock";
$serial_table = "serial";
$order_table = "orders";
$rebate_table = "rebate";
$sample_table = "sample";
$files_table = "files";

$mail_customer_table =  "mail_customer";
$mail_body_table = "mail_body";

$LOGIN_PAGE = "/intranet/login.php";

$rights = "All Rights Reserved By Youdi Co., Ltd.";	
$css = "/vca_user/common/style.css";
$css_awsome = "/intranet/common/font-awesome.min.css";

$logo_img_inv = "../common/VCA_logo_inverse_ico.png";
$logo_img = "../common/VCA_logo_normal_small.png";
$icon = "<img src='/vca_user/common/VCA_logo_normal_icon.png' >";
$files_dir = "/var/www/files/";



$partlist_table = "partlist";
$bom_table = "bom";
$mprocess_table = "manu_process";
$manu_doc_table = "m_process_doc";

$packingbom_table = "packing_bom";
?>







