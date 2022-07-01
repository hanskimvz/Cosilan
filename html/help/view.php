<!DOCTYPE html>

<?PHP 
session_start();
include ("dbconnect.php");

if (!$_GET['table']){
    $_GET['table'] = 'paragraph';
}

if ($_GET['pk']){
    $sq = "select code from ".$_GET['table']." where pk=".$_GET['pk'];
    $code = mysqli_fetch_row(mysqli_query($connect, $sq))[0];
    echo '<script>location.href=("view.php?code='.$code.'")</script>';
}

if ($_GET['code']) {
    // $sq = "select * from ".$_GET['table']." where pk=".$_GET['pk'];
    $sq = "select * from ".$_GET['table']." where code='".$_GET['code']."'";
    // print $sq;
    $rs = mysqli_query($connect, $sq);
    $assoc =  mysqli_fetch_assoc($rs);
    if($_SESSION['logID']) {
        $assoc['title'] = '<a href="modify.php?pk='.$assoc['pk'].'">'.$assoc['title'].'</a>';
    }
}


?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bootlab">
    <title id='title'>Help Page</title>
    <link href="/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/all.css">	
<style>
.ql-syntax {
  background-color: #23241f;
  color: #f8f8f2;
  /* overflow: visible; */
}

</style>
</head>
<body>
    <main class="content">
	    <div class="container-fluid p-0">
    		<h1 class="h3 mb-3"><?=$assoc['title']?></h1>
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <?=$assoc['body']?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</main>

</body>
            <script src="/js/app.js"></script>
</html>