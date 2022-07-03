
<?php
include ("dbconnect.php");
$img_type = "image/png";
$img = "";
if (!isset($_GET['code'])) {
    $_GET['code'] = "I16568391600";
}

$sq = "select * from document.images where code='".$_GET['code']."' ";
print $sq;
$rs = mysqli_query($connect, $sq);
$body = mysqli_fetch_assoc($rs)['body'];
if ($body){
    list($head, $img_64) = explode(";base64,", $body);

    $img_type =  explode(":", $head)[1];
    $img = base64_decode($img_64);
    // print $img_type;
    // print $img_64;
}
Header("Content-type:".$img_type."");
ob_clean();
flush();
echo $img;
flush();
?>


