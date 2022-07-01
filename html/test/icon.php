<?PHP
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<!-- https://appstack.bootlab.io/documentation.html -->
<?PHP
if(!$connect) {
	$frame_page = "";
}
?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
<meta name="author" content="Bootlab">
<title><?=$HOST_DOC_TITLE?></title>
<link rel="stylesheet" href="../css/app.css">
<link rel="stylesheet" href="../css/font-awesome.min.css">
</head>
	<body>
<?PHP
$str_feather = '
activity, airplay, alert-circle, alert-octagon, alert-triangle, align-center, align-justify, align-left, align-right, anchor, aperture, archive, arrow-down-circle, arrow-down-left, arrow-down-right, arrow-down, arrow-left-circle, arrow-left, arrow-right-circle, arrow-right, arrow-up-circle, arrow-up-left, arrow-up-right, arrow-up, at-sign, award, bar-chart-2, bar-chart, battery-charging, battery, bell-off, bell, bluetooth, bold, book-open, book, bookmark, box, briefcase, calendar, camera-off, camera, cast, check-circle, check-square, check, chevron-down, chevron-left, chevron-right, chevron-up, chevrons-down, chevrons-left, chevrons-right, chevrons-up, chrome, circle, clipboard, clock, cloud-drizzle, cloud-lightning, cloud-off, cloud-rain, cloud-snow, cloud, code, codepen, coffee, command, compass, copy, corner-down-left, corner-down-right, corner-left-down, corner-left-up, corner-right-down, corner-right-up, corner-up-left, corner-up-right, cpu, credit-card, crop, crosshair, database, delete, disc, dollar-sign, download-cloud, download, droplet, edit-2, edit-3, edit, external-link, eye-off, eye, facebook, fast-forward, feather, file-minus, file-plus, file-text, file, film, filter, flag, folder-minus, folder-plus, folder, frown, gift, git-branch, git-commit, git-merge, git-pull-request, github, gitlab, globe, grid, hard-drive, hash, headphones, heart, help-circle, home, image, inbox, info, instagram, italic, key, layers, layout, life-buoy, link-2, link, linkedin, list, loader, lock, log-in, log-out, mail, map-pin, map, maximize-2, maximize, meh, menu, message-circle, message-square, mic-off, mic, minimize-2, minimize, minus-circle, minus-square, minus, monitor, moon, more-horizontal, more-vertical, mouse-pointer, move, music, navigation-2, navigation, octagon, package, paperclip, pause-circle, pause, pen-tool, percent, phone-call, phone-forwarded, phone-incoming, phone-missed, phone-off, phone-outgoing, phone, pie-chart, play-circle, play, plus-circle, plus-square, plus, pocket, power, printer, radio, refresh-ccw, refresh-cw, repeat, rewind, rotate-ccw, rotate-cw, rss, save, scissors, search, send, server, settings, share-2, share, shield-off, shield, shopping-bag, shopping-cart, shuffle, sidebar, skip-back, skip-forward, slack, slash, sliders, smartphone, smile, speaker, square, star, stop-circle, sun, sunrise, sunset, tablet, tag, target, terminal, thermometer, thumbs-down, thumbs-up, toggle-left, toggle-right, trash-2, trash, trello, trending-down, trending-up, triangle, truck, tv, twitter, type, umbrella, underline, unlock, upload-cloud, upload, user-check, user-minus, user-plus, user-x, user, users, video-off, video, voicemail, volume-1, volume-2, volume-x, volume, watch, wifi-off, wifi, wind, x-circle, x-square, x, youtube, zap-off, zap, zoom-in, zoom-out, default';

$ex_str_feather = explode(',', $str_feather);
print "<table>";
print "<tr>";
for($i=0; $i<sizeof($ex_str_feather); $i++) {
	print '<td><i class="align-middle mr-3" data-feather="'.trim($ex_str_feather[$i]).'"></i>'.$ex_str_feather[$i].'</td>';
//	print '<span onclick="alert(\''.$ex_str_feather[$i].'\')"><i class="align-middle mr-3" data-feather="'.trim($ex_str_feather[$i]).'"></i></span>';
	
	if(!(($i+1)%10)) {
		print "</tr><tr>";
	}


}

print "</tr>";
print "</table>";
$fname = $_SERVER['DOCUMENT_ROOT']."/css/font-awesome.min.css";
//$fname = $_SERVER['DOCUMENT_ROOT']."/css/fontawesome_all.css";
$fp = fopen($fname,"r");
$font_all = fread($fp, filesize($fname));
fclose($fp);

$arr_font_awesome = explode(".", $font_all );
$awesome = array();
$n=0;

for($i=0; $i<sizeof($arr_font_awesome); $i++) {
	if(strpos(" ".$arr_font_awesome[$i],"fa-") && strpos(" ".$arr_font_awesome[$i],":before")) {
		list($arr_font_awesome[$i],$t, $arr_font_awesome_t[$i] ) = explode(":",$arr_font_awesome[$i]);
		$arr_font_awesome_t[$i] =  substr($arr_font_awesome_t[$i],2,4);
		$awesome[$n] =  '<span onclick="alert(\''.$arr_font_awesome[$i].'=='.$arr_font_awesome_t[$i].'\')"><i class="fa fa-3x '.$arr_font_awesome[$i].' "></i></span>';
		$n++;
	}
}

for($i=0; $i<sizeof($awesome); $i++)	{
	print $awesome[$i] ;
}

	
	
	
?>
<script src="../js/app.js"></script>
    </body>
    </html>