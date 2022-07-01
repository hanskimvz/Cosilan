<?PHP
date_default_timezone_set ( "Asia/Shanghai" ); 

if($_POST) {
	$err = 0;
	$message = "";
	$mysql_host = 'localhost';
	$mysql_user = 'root';
	$mysql_password = 'rootpass';
	
	$db_name = trim($_POST['DB_name']);
	$ID = trim($_POST['userID']);

	if(!$db_name) {
		$message .= "<br>".date("Y-m-d H:i:s").": DB name err<br>";
		$err |= 0x01;
	}
	if(!trim($ID)) {
		$message .= "<br>".date("Y-m-d H:i:s").": ID err <br>";
		$err |= 0x02;
	}
	if(!trim($_POST['passwd1']) or (trim($_POST['passwd1']) != trim($_POST['passwd1']))) {
		$message .= "<br>".date("Y-m-d H:i:s").": Password err <br>";
		$err |= 0x04;
	}

	if(!$err) {
		$connect0 = @mysqli_connect($mysql_host, $mysql_user, $mysql_password, 'common');
		$sq = "show databases like '".$db_name."' ";
		$rs = mysqli_query($connect0, $sq);
		if($rs->num_rows) {
			$message .= "<br>".date("Y-m-d H:i:s").": DB Name already exist ";
			$err |= 0x10;
		}
	
		$sq = "select ID from users where ID='".$ID."' ";
		$rs = mysqli_query($connect0, $sq);
		if($rs->num_rows) {
			$message .= "<br>".date("Y-m-d H:i:s").": Admin ID : '".$ID."' already exist ";
			$err |= 0x20;
		}
	}
	if(!$err) {
		$sq = "create database ".$db_name." ";
		$rs = mysqli_query($connect0, $sq);
		$message .= "<br>".date("Y-m-d H:i:s").": Database ".$db_name." created";
		
		$connect  = @mysqli_connect($mysql_host, $mysql_user, $mysql_password, $db_name);
		if(!$connect) {
			$message .= "<br>".date("Y-m-d H:i:s").": DB Select Error";
		}
		else {
			system("mysqldump -u".$mysql_user." -p".$mysql_password." --no-data cnt_demo > /home/www/release/cnt_demo_no_data.sql");
			$message .="<br>".date("Y-m-d H:i:s").": Refrence DB backuped";
			system("mysqldump -u".$mysql_user." -p".$mysql_password." cnt_demo language > /home/www/release/language.tbl.sql");
			$message .="<br>".date("Y-m-d H:i:s").": Refrence DB, Language Table backuped";
			
			system("mysql -u".$mysql_user." -p".$mysql_password." ".$db_name." < /home/www/release/cnt_demo_no_data.sql");
			$message .="<br>".date("Y-m-d H:i:s").": DB ".$db_name." restored";
			system("mysql -u".$mysql_user." -p".$mysql_password." ".$db_name." < /home/www/release/language.tbl.sql");
			$message .="<br>".date("Y-m-d H:i:s").": DB ".$db_name.", Language Table restored";
			
			$code = "U".time().rand(0,9).rand(0,9).rand(0,9);
			$sq = "insert into users(regdate, code, ID, passwd, db_name, flag, role) values(now(), '".$code."', '".$ID."', '".trim($_POST['passwd1'])."', '".$db_name."', 'y', 'admin')";
			$rs = mysqli_query($connect0, $sq);
	
			if($rs) {
				$message .= "<br>".date("Y-m-d H:i:s").": DB ".$db_name." admin ID: ".$ID." created.";
			}
		
			$sq = "grant insert, select, update, delete, alter on ".$db_name.".* to 'ct_user'@'localhost'";
			$rs = mysqli_query($connect0, $sq);
			if($rs) {
				$message .= "<br>".date("Y-m-d H:i:s").": Setting Granted";
			}
			$sq = "flush privileges";
			$rs = mysqli_query($connect0, $sq);
			if($rs) {
				$message .= "<br>".date("Y-m-d H:i:s").": Flush privileges";
			}
			
		}
	}
	print "Error Code: ".$err;
	print $message;

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
<meta name="author" content="Bootlab">
<link rel="shortcut icon" href="/">
<title>Business Intelligence</title>
<link rel="stylesheet" href="/css/app.css">
</head>
<body>
	<div class="row">
		<div class="col-6">
			<div class="card">
				<div class="card-header"><h5 class="card-title mb-0">Create New DB</h5></div>
				<div class="card-body">
					<form name="info_form" class="form-horizontal" method="POST" ENCTYPE="multipart/form-data">
						<div class="form-group">
							<label>DB name</label>
							<input type="text" name="DB_name" class="form-control"  value="<?=$_POST['DB_name']?>">
						</div>									
						<div class="form-row">
							<div class="form-group col-md-4">
								<label>Admin ID</label>
								<input type="text" name="userID" class="form-control" value="<?=$_POST['userID']?>">
							</div>
							<div class="form-group col-md-4">
								<label>Admin PW</label>
								<input type="password" name="passwd1" class="form-control" value="">
							</div>
							<div class="form-group col-md-4">
								<label>Admin PW</label>
								<input type="password" name="passwd2" class="form-control" value="">
							</div>										
						</div>
						<button type="submit" name="btn" class="btn btn-primary" value="private">保存</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
