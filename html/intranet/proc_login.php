<?PHP
	print_r($_POST);

	session_start();
	unset($_SESSION['logID']);
	unset($_SESSION['userip']);
	unset($_SESSION['db']);

	session_destroy();
	setcookie("userseq","",time()-3600,"/");
	setcookie("logID","",time()-3600,"/");
	session_start();

	include "libs/dbconnect.php";

	$sq = "select ID, passwd from ".$user_table." where (ID='".$_POST['username']."')";
	print $sq;
	$arr_user = mysqli_fetch_array(mysqli_query($connect, $sq));
	print($arr_user['passwd']);
	print($_POST['password']);
	if( $arr_user['passwd'] == $_POST['password'] ) {
		$href = 'logistic/list_lcode.php';
		$db = $dbname;
	}
	
	print $dbname;


//	print $db;
	if($db) {
		$userseq = md5($_POST['username']."test");
		$location_href = $_POST['location_href'];

		$sq = "insert access_log(IP_addr, regdate, ID, act) values('".$_SERVER['REMOTE_ADDR']."', '".date('Y-m-d H:i:s')."', '".$_POST['username']."', '".addslashes($_SERVER['HTTP_USER_AGENT'])."')";
		@mysqli_query($connect, $sq);

		$_SESSION['logID']  = $_POST['username'];
		$_SESSION['userip'] = $_SERVER['REMOTE_ADDR'];

		setcookie("userseq",md5($_POST['username']."test"),"0","/");
		setcookie("selected_language",$_POST['selected_language'],"0","/");
		if(!$location_href) {
			$location_href = $href;
		}
		
		print_r($_POST);
		print_r($_SESSION);

		// Header("Location:".$location_href."");
	}
	else {
		echo "<script>alert('Wrong ID or password');history.back();</script>";
		exit;
	}


?>


