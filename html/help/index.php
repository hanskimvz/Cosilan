<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
	<meta name="author" content="Bootlab">
	<link rel="shortcut icon" href="/favicon.ico">
	<title>商业智能</title>
	<link rel="stylesheet" href="/css/app.css">
</head>
<?PHP
session_start();
include ("dbconnect.php");
require_once("./parsedown/Parsedown.php");
// print_r($_GET);

$tableBody = "";
if ($_GET['fr'] == "view") {
	// include "pages.php";
	// include "view_markdown.php";
	print '	<style>
    h2{
        text-align: center;
        background: #EFEFEF;
        padding: 8px 6px;
    }
    table {border-collapse: collapse; border: 0; box-shadow: 1px 2px 3px #eee;}
    th {border: 1px solid #aaa; font-size: 100%; vertical-align: baseline; padding: 3px 6px;}
    td {border: 1px solid #aaa; font-size: 100%; vertical-align: baseline; padding: 3px 6px;}
	</style>';
	$sq = "select * from paragraph where code='".$_GET['code']."'";
    // print $sq;
    $rs = mysqli_query($connect, $sq);
    $assoc =  mysqli_fetch_assoc($rs);
    
    $Parsedown = new Parsedown();
    $tableBody = $Parsedown->text($assoc['body']);
	$tableBody = '<h2>'.$assoc['title'].'</h2>'.$tableBody;
}

function simpleBodyText($str,$search){
	$str = strip_tags($str);
	$st = strpos($str, $search);
	if ($st) {
		$st =-10;
	}
	if ($st<0){
		$st = 0;
	}
	$ed = $st+500;
	return mb_substr($str, $st, $ed);
}

if(isset($_GET['search'])) {
	// $sq = "select pk, code, title, body, regdate, last_modified, flag from document.paragraph where title like '%".trim($_GET['search'])."%' or body like '%".trim($_GET['search'])."%' ";
	$sq = "select pk, code, title, body, regdate, last_modified, flag from document.paragraph where title like '%".trim($_GET['search'])."%' or hashtag like '%".trim($_GET['search'])."%' ";
	$rs = mysqli_query($connect, $sq);
	while ($assoc = mysqli_fetch_assoc($rs)){
		// print_r($assoc);
		// $view_href = '?fr=view&code='.$assoc['code'].'';
		$view_href = 'view_markdown.php?code='.$assoc['code'].'';
		// $view_href = 'view.php?code='.$assoc['code'].'';
		$assoc['title'] = '<a href="'.$view_href.'" target="view_doc">'.$assoc['title'].'</a>';
		$tableBody .= '<tr>
			<td><p>'.$assoc['title'].' ---- '.date("Y-m-d", strtotime($assoc['last_modified'])).'</br>
			'.simpleBodyText($assoc['body'], trim($_GET['search'])).'</p></td>
			</tr>';
	}
	if (!$tableBody){
		$tableBody = "No Record, Phlease Try Other Keywords!";
	}
	$tableBody = '<table class="table table-sm table-striped">
		<tbody>'.$tableBody.'</tbody></table>';
}

if (!$tableBody) {
	$tableBody = '<table class="table table-striped"><tbody>Try Keyword for Search</tbody></table>';

}

$sq = "select pk, code, class, category, title, seq, flag from paragraph order by seq asc";
$rs = mysqli_query($connect, $sq);
$arr_rs = array(
	"Cosilan"=>array(),
	"VCA" =>array(),
	// "Howto"=>array(),
	"Product"=>array(),
);
while ($assoc=mysqli_fetch_assoc($rs)){
	array_push($arr_rs[$assoc['class']], $assoc);
}
// print_r($arr_rs);
$sideMenu = '';
foreach($arr_rs as $cls=>$arr_cat) {
	if ($cls == 'None' || $cls==''|| $cls=='Howto') {
		continue;
	}
	$sideMenu .= '<li class="sidebar-item">
		<a href="#'.$cls.'" data-toggle="collapse" class="sidebar-link collapsed"><span class="align-middle">'.$cls.'</span></a>
		<ul id="'.$cls.'" class="sidebar-dropdown list-unstyled collapse">';
		for($i=0; $i<sizeof($arr_cat); $i++){
			if (!$arr_cat[$i]['flag']) {
				continue;
			}
			$sideMenu .= '<li id="'.$arr_cat[$i]['code'].'" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=view&code='.$arr_cat[$i]['code'].'" ><span class="align-middle">'.$arr_cat[$i]['category'].'</span></a></li>';
		}
	$sideMenu .='
		</ul>
	</li>';

}

$sideMenu = '<nav class="sidebar sidebar-sticky">
	<div class="sidebar-content ">
		<a class="sidebar-brand" href="/help/"><span class="align-middle ml-2">HELP PAGE</span></a> 
		<ul class="sidebar-nav">'.$sideMenu.'</ul>
	</div>
</nav>';



$header = <<<EOBLCOK
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bootlab">
    <title id='title'>Help Page</title>
    <link href="/css/app.css" rel="stylesheet">
</head>
EOBLCOK;

$searchForm = <<<EOBLOCK
<form method="GET" class="form-inline d-none d-sm-inline-block" ENCTYPE="multipart/form-data">
<input type="text" class="form-control form-control-no-border"  placeholder="Search" name="search" value="$_GET[search]" size="100">
<button type="submit" class="btn btn-warning" >查找</button>
</form>
EOBLOCK;

$contents_body =   <<<EOBLOCK
<html lang="en">
$header
<body>
	<div class="wrapper">	
		<div class="main" id="main_page">
			<nav class="navbar sidebar-sticky navbar-expand navbar-light bg-white">
				<i class="hamburger align-self-center"></i>
				$searchForm				
			</nav>
			<main class="content">
				<div class="container-fluid p-0" >
					<div class="row">
					$tableBody
					</div>
				</div>
			</main>
		</div>
	</div>
</body>
<script src="/js/app.js"></script>
</html>
EOBLOCK;

$sideMenux = <<<EOBLOCK
<nav class="sidebar sidebar-sticky">
	<div class="sidebar-content ">
		<a class="sidebar-brand" href="/help/"><span class="align-middle ml-2">HELP PAGE</span></a> 
		<ul class="sidebar-nav">
			<li class="sidebar-item">
				<a href="#cosilan" data-toggle="collapse" class="sidebar-link collapsed"><span class="align-middle">平台软件(COSILAN)</span></a>
				<ul id="cosilan" class="sidebar-dropdown list-unstyled collapse">
					<li id="install" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=install" ><span class="align-middle">安装</span></a></li>
					<li id="operate" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=operate" ><span class="align-middle">运行</span></a></li>
					<li id="config" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=config" ><span class="align-middle">环境配置</span></a></li>
					<li id="admin_page" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=admin_page" ><span class="align-middle">网页（管理者）</span></a></li></li>
					<li id="main_page" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=main_page" ><span class="align-middle">网页（主网页）</span></a></li>
				</ul>
			</li>
			<li class="sidebar-item">
				<a href="#device" data-toggle="collapse" class="sidebar-link collapsed"><span class="align-middle">对比分析</span></a>
				<ul id="device" class="sidebar-dropdown list-unstyled collapse">
					<li id="hardware" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=hardware"><span class="align-middle">硬件</span></a></li>
					<li id="webpage" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=ipn_webpage"><span class="align-middle">设备网页</span></a></li>
				</ul>
			</li>
			<li class="sidebar-item">
				<a href="#vca" data-toggle="collapse" class="sidebar-link collapsed"><span class="align-middle">智能分析(VCA)</span></a>
				<ul id="vca" class="sidebar-dropdown list-unstyled collapse">
					<li id="summary" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=summary" ><span class="align-middle">汇总</span></a></li>
					<li id="standard" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=standard" ><span class="align-middle">标准</span></a></li>
					<li id="premium" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=premium" ><span class="align-middle">高级</span></a></li>
					<li id="export" class="sidebar-item"><a class="sidebar-link" href="/help/?fr=export" ><span class="align-middle">导出数据库</span></a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>

EOBLOCK;

?>

<body>
	<div class="wrapper">
		<?=$sideMenu?>
		<div class="main" id="main_page">
			<nav class="navbar sidebar-sticky navbar-expand navbar-light bg-white">
				<a class="sidebar-toggle d-flex mr-2"><i class="hamburger align-self-center"></i></a>
				<?=$searchForm ?>
			</nav>
			<main class="content">
				<div class="container-fluid p-0" >
					<?=$tableBody?>
				</div>
			</main>			
		</div>	
	</div>
</body>
<script src="/js/app.js"></script>
<script>
function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        // vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

Get = getUrlVars();
if (!Get['fr']){
	Get['fr'] = 'account';
}

console.log(Get);

var a = '';
var b = '';	

a = document.getElementById(Get['code']);

if (a) {
	a.classList.add("active");
}

if (b) {
	b.classList.add("show");
}
</script>

</html>