
<?PHP
session_start();
// print_r($_SESSION);
include ("dbconnect.php");
// print_r($_GET);

if (isset($_GET['modify'])){
    print $_POST['flag'];
    $flag = $_POST['flag'] == 'true' ? 1 : 0;
    

    $sq = "update paragraph set class='".$_POST['cls']."', category='".addslashes(trim($_POST['cat']))."', seq=".$_POST['seq'].", flag=".$flag." where pk=".$_POST['pk'];
    print $sq;
    $rs = mysqli_query($connect, $sq);
    if($rs) {
        print "OK";
    }
    else {
        print "FAIL";
    }

    exit();
}


$sq = "select pk, code, class, category, title, regdate, last_modified, seq, flag from paragraph order by class asc, seq asc";
$rs = mysqli_query($connect, $sq);
while ($assoc = mysqli_fetch_assoc($rs)){
    // print_r($assoc);
    $pk = $assoc['pk'];
    $view_href = 'view_markdown.php?code='.$assoc['code'].'';
    if ($_SESSION['logID'] == 'hanskim') {
        $assoc['code'] = '<a href="edit_markdown.php?pk='.$pk.'" target="modify_page">'.$assoc['code'].'</a>';
    }
    $assoc['pk'] = '<a href="'.$view_href.'">'.$pk.'</a>';

    $HTML_BODY .= '<tr>
        <td>'.$assoc['pk'].'</td>
        <td>'.$assoc['code'].'</td>
        <td><select id="class['.$pk.']" class="form-control form-control-sm">
            <option value="None"></option>
            <option value="VCA" '.($assoc['class']=='VCA'? "selected":"").'>VCA</option>
            <option value="Cosilan" '.($assoc['class']=='Cosilan'? "selected":"").'>Cosilan</option>
            <option value="Howto" '.($assoc['class']=='Howto'?"selected":"").'>Howto</option>
            <option value="Product" '.($assoc['class']=='Product'?"selected":"").'>Product</option>
        </select></td>
        <td><input type="text" id="category['.$pk.']" value="'.$assoc['category'].'" class="form-control form-control-sm" size="1" ></td>
        <td>'.$assoc['title'].'</td>
        <td>'.$assoc['regdate'].'</td>
        <td>'.$assoc['last_modified'].'</td>
        <td><input type="text" id="seq['.$pk.']" value="'.$assoc['seq'].'" class="form-control form-control-sm" size="1" ></td>
        <td><input type="checkbox" id="chk_flag['.$pk.']" '.($assoc['flag']?"checked":"").'></td>';
        if ($_SESSION['logID'] == 'hanskim') {
            $HTML_BODY .= '<td><button class="btn btn-sm btn-primary" onClick="submit_this('.$pk.')" >submit</button></td>';
        }
        $HTML_BODY .= '</tr>';
}

$HTML_BODY = '<table class="table table-sm table-striped table-hover">
    <thead>
    <tr>
    <th>Pk</th>
    <th>Code</th>
    <th>Class</th>
    <th>Category</th>
    <th>Title</th>
    <th>Regdate</th>
    <th>Last Modified</th>
    <th>Seq No.</th>
    <th>Flag</th>
    </tr></thead>
    <tbody>'.$HTML_BODY.'</tbody></table>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bootlab">
    <title id='title'>HELP Page</title>
    <link href="/css/app.css" rel="stylesheet">
	</head>
	<body id="body">
        <main class="content" id="pageContents">
            <div class="row">
                <?=$HTML_BODY?>
            </div>
        </main>
    </body>
    <script src="/js/app.js"></script>
</html>

<script>
function submit_this(t){
    console.log(t);
    cls = document.getElementById('class['+t+']').value;
    cat = document.getElementById('category['+t+']').value;
    seq = document.getElementById('seq['+t+']').value;
    flag = document.getElementById('chk_flag['+t+']').checked;
    if (!seq){
        seq =0;
    }
    console.log(cls,cat,  seq, flag)
    let url ="?modify";
    var posting = $.post(url, {
        pk:t,
        cls:cls,
        cat:cat,
        seq:seq,
        flag:flag

    });
    posting.done(function(data) {
        console.log(data);
    });	
}

</script>
