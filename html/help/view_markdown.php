<!DOCTYPE html>
<?PHP
session_start();
include ("dbconnect.php");
require_once("./parsedown/Parsedown.php");
// $Parsedown = new Parsedown();

// $fname = "parsedown/README.md";
// $fname = "simplemde.md";
// $fp = fopen($fname, "r");
// $body = fread($fp, filesize($fname));
// fclose($fp);

// print $Parsedown->text($body);



if (!$_GET['table']){
    $_GET['table'] = 'paragraph';
}

if ($_GET['pk']){
    $sq = "select code from ".$_GET['table']." where pk=".$_GET['pk'];
    $code = mysqli_fetch_row(mysqli_query($connect, $sq))[0];
    echo '<script>location.href=("view_markdown.php?code='.$code.'")</script>';
}

if ($_GET['code']) {
    // $sq = "select * from ".$_GET['table']." where pk=".$_GET['pk'];
    $sq = "select * from ".$_GET['table']." where code='".$_GET['code']."'";
    // print $sq;
    $rs = mysqli_query($connect, $sq);
    $assoc =  mysqli_fetch_assoc($rs);
    
    $Parsedown = new Parsedown();
    $content = $Parsedown->text($assoc['body']);
}


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HELP</title>
</head>
<style>
    body {
        background: #CCCCCC;
    }
    h2{
        text-align: center;
        background: #EFEFEF;
        padding: 8px 6px;
    }
    code{
        background: #ECECEC;
        display: block;
    }
    table {border-collapse: collapse; border: 0; box-shadow: 1px 2px 3px #eee;}
    th {border: 1px solid #aaa; font-size: 75%; vertical-align: baseline; padding: 3px 6px;}
    td {border: 1px solid #aaa; font-size: 75%; vertical-align: baseline; padding: 3px 6px;}
    .container {
        width: 1000px;
        margin: 0 auto;
        background: #f6f6f6;
        border: 1px solid #FDFDFD;
        border-radius: 3px;
        padding: 10px;
    }
</style>
<body>
    <h2><?=$assoc['title']?></h2>
    <div class="container"><?=$content?></div>

</body>
</html>
