<style>
    body {
        background: #FFFFFF;
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
    td {border: 1px solid #aaa; font-size: 75%; vertical-align: top; padding: 3px 6px;}
    .container {
        width: 1000px;
        margin: 0 auto;
        background: #f6f6f6;
        border: 1px solid #FDFDFD;
        border-radius: 3px;
        padding: 10px;
    }
</style>
<?PHP
include ("dbconnect.php");
$sq = "select * from images order by regdate desc";

if ($_GET['limit']) {
    $sq .= " limit ".$_GET['limit'];
}


print $sq;
$rs = mysqli_query($connect, $sq);


$arr_rs = array();
while ($assoc=mysqli_fetch_assoc($rs)){
    array_push($arr_rs, $assoc);
}

$table_body = '';
for ($i=0; $i<sizeof($arr_rs); $i+=5){
    $table_body .= '<tr>';
    for($j=0; $j<5; $j++) {
        $table_body .= '<td>'.$arr_rs[$i+$j]['pk'].'-'.$arr_rs[$i+$j]['code'].'</td>';
    }
    $table_body .= '</tr><tr>';
    for($j=0; $j<5; $j++) {
        $table_body .= '<td><img src="'.$arr_rs[$i+$j]['body'].'" width="300"></img></td>';
    }
    $table_body .= '</tr>';
}
$table_body = '<table>'.$table_body.'</table>';
print $table_body;

?>