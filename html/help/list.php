<!DOCTYPE html>
<?PHP
session_start();
// print_r($_SESSION);
include ("dbconnect.php");
// print_r($_GET);



// require_once("./parsedown/Parsedown.php");
// $Parsedown = new Parsedown();

// $fp = fopen("parsedown/README.md", "r");
// $body = fread($fp, filesize("parsedown/README.md"));
// fclose($fp);

// print $Parsedown->text($body);

// echo $Parsedown->text('Hello _Parsedown_!'); # prints: <p>Hello <em>Parsedown</em>!</p>
// echo $Parsedown->line('Hello _Parsedown_!'); # prints: Hello <em>Parsedown</em>!

$sq = "select pk, code, title, regdate, last_modified, flag from paragraph";
$rs = mysqli_query($connect, $sq);
while ($assoc = mysqli_fetch_assoc($rs)){
    // print_r($assoc);
    $view_href = 'view_markdown.php?code='.$assoc['code'].'';
    $assoc['title'] = '<a href="'.$view_href.'" target="view_page">'.$assoc['title'].'</a>';
    if ($_SESSION['logID'] == 'hanskim') {
        $assoc['code'] = '<a href="modify.php?pk='.$assoc['pk'].'" target="modify_page">'.$assoc['code'].'</a>';
    }
    $assoc['pk'] = '<a href="'.$view_href.'">'.$assoc['pk'].'</a>';
    $HTML_BODY .= '<tr>
        <td>'.$assoc['pk'].'</td>
        <td>'.$assoc['code'].'</td>
        <td>'.$assoc['title'].'</td>
        <td>'.$assoc['regdate'].'</td>
        <td>'.$assoc['last_modified'].'</td>
        <td>'.$assoc['flag'].'</td>
        </tr>';
}

$HTML_BODY = '<table class="table table-sm table-striped">
    <thead>
    <tr>
    <th>Pk</th>
    <th>Code</th>
    <th>Title</th>
    <th>Regdate</th>
    <th>Last Modified</th>
    <th>Flag</th>
    </tr></thead>
    <tbody>'.$HTML_BODY.'</tbody></table>';
?>

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

