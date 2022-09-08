<?php
session_start();
include_once ("dbconnect.php");

if (!isset($_GET['table'])){
    $_GET['table'] = 'paragraph';
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save-file'])){
    // print_r($_POST);
    $regdate = date("Y-m-d H:i:s");
    if (!$_GET['pk']) {
        $sq = "insert into ".$_GET['table']."(regdate) values('".$regdate."')";
        $rs = mysqli_query($connect, $sq);
        $sq = "select pk from ".$_GET['table']." where regdate='".$regdate."'";
        $rs = mysqli_query($connect, $sq);
        $_GET['pk'] = mysqli_fetch_row($rs)[0];
    }

    $sq= "update ".$_GET['table']." set code='".$_POST['code']."', title='".addslashes(trim($_POST['title']))."', body='".addslashes($_POST['body'])."', hashtag='".addslashes($_POST['hashtag'])."', last_modified = '".$regdate."' where pk=".$_GET['pk'];
    // print $sq;
    $rs= mysqli_query($connect, $sq);
    if ($rs){
        print "OK";
        echo '<script>location.href=("edit_markdown.php?pk='.$_GET['pk'].'")</script>';
        exit();
    }
}


$sq = "select * from ".$_GET['table']." where pk = ".$_GET['pk'];
$rs = mysqli_query($connect, $sq);
$arr_rs =  mysqli_fetch_assoc($rs);
if (!$arr_rs['code']){
    $arr_rs['code'] = 'P'.time();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HELP</title>
        
    <link rel="stylesheet" href="simplemde.min.css">
    <script src="simplemde.min.js"></script>
</head>
<style>
    body {
        background: #CCCCCC;
    }
    h3{
        text-align: center;
    }
    .container {
        width: 1000px;
        margin: 0 auto;
        background: #f6f6f6;
        border: 1px solid #2C3E50;
        border-radius: 3px;
        padding: 5px;
    }
    input {border: 1px solid #aaa; font-size: 100%; vertical-align: baseline; padding: 4px 5px;}
</style>
<body>
    <h3>Cosilan Help</h3>
    <div class="container">
        <form method="post">
        <input type="hidden" name="pk" value="<?=$arr_rs['pk']?>">
        <input type="hidden" name="code" value="<?=$arr_rs['code']?>">
        <br>
        <span>Title:</span>
        <input type="text" name="title" name="title" value="<?=$arr_rs['title']?>"  autocomplete="off" size="100"/><br>
        <br>
        <span>Hashtag:</span>
        <input type="text" name="hashtag"  value="<?=$arr_rs['hashtag']?>" autocomplete="off" size="100"/><br>
        </br>
        <textarea id="content" name="body" cols="66" rows="15"><?=$arr_rs['body']?></textarea><br>
        <button type="submit" name="save-file">Save Changes</button>
    </form>
    </div>

</body>
<script src='/js/jquery.min.js'></script>
<script>
    var simplemde = new SimpleMDE({ element: document.getElementById("content") });
</script>
</html>