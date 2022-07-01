<?php

if ($_GET['fr'] == 'view_pic') {

}



$sq = "select * from document.management where bookname = '".$_GET['fr']."' ";
$tableBody = $sq;
$rs = mysqli_query($connect, $sq);
$assoc = mysqli_fetch_assoc($rs);
$subject  = $assoc['subject'];
if (!$$subject ) {
    $tableBody = "no records";
}
// $code = "C".time().rand(0,9).rand(0,9).rand(0,9);
// $sq = "insert into document.management(code, bookname, subject) values('".$code."', 'install', '安装Cosilan 0.95 beta')";
// $tableBody = $sq;
// $rs = mysqli_query($connect, $sq);
// print ($rs);
$sq = "select title, body, seq from document.paragraph where pri_code='".$assoc['code']."' order by seq asc";
$rs = mysqli_query($connect, $sq);
$tableBody = '';
while ($assoc = mysqli_fetch_assoc($rs)) {
    $tableBody .= $assoc['body'];
}



$tableBody = '<div class="row">
    <div class="mx-auto col-lg-10 col-xl-8">
        <h1 class="h3 mb-3">'.$subject.'</h1>
		<hr class="my-4">
    		<div class="alert alert-primary mb-5" role="alert">
				<div class="alert-message">
					<strong>Note:</strong> If you\'re not looking for any customizations or are not comfortable with Node.js or Webpack, you could use the pre-compiled (ready-to-use)
						files available in the dist folder. In that case, you can skip the set-up below.
				</div>
			</div>

			<div id="installation" class="mb-5">
            '.$tableBody.'
			</div>
    </div>
</div>';
?>
