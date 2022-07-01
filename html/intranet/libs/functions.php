<?PHP
session_start();
$today = date("Y-m-d");	
$today_y = date("Y");
$today_m = date("m");
$today_d = date("d");

function print_arr($a)
{
	if(!$a) {
		$a = $_POST;
	}
	
	 print '<pre>'; 
	 print_r($a); 
	 print '</pre>';
}

function Dec2Hex($num, $len)
{
	$num =  strtoupper(dechex($num));
	$len = $len- strlen($num);

	for($i=0; $i<$len; $i++) {
		$num = "0".$num;
	}
	return "0x".$num;
}

function Dec2Bin($num,$len)
{
	for($i=0; $i<20; $i++,$len--) {
		$rest = $num%2;
		$num = ($num-($num%2))/2;
		$bin = $rest.$bin;
		if(($num<=0)&&($len<=1)) break;
	}
	return $bin."B";
}

function Query2Array($connect, $sq)
{
	$arr_result = array();
	$arr_field = array();
	if(	$rs = mysqli_query($connect, $sq)) {
		$cols = $rs ->field_count;
		$rows = $rs->num_rows;
		if(!$rows) {
//			return 0;
		}
	
		for ($i=0; $i<$cols; $i++) {
			$fields = mysqli_fetch_field($rs);
			$arr_field[$i] = ($fields->name);
		}
		
		for($i = 0; $i<$rows; $i++) {
			$row = mysqli_fetch_row($rs);
			for($j = 0; $j < $cols; $j++) {
				$arr_result[$i][$arr_field[$j]] = $row[$j];
			}
		}
	}
	return $arr_result;
}


function user_info($nname, $ID, $type_t, $Onchange='')
{
	global $connect;
	global $user_table;
	global $admin;
	if( ($nname == "name_chi") or ($nname == "name_eng") or ($nname == "name_kor") ) {
		$lang=$nname;
	}
	else {
		$lang = "name_chi";
		if($_COOKIE['selected_language'] == "eng") {
			$lang = "name_eng";
		}
		else if($_COOKIE['selected_language'] == "kor") {
			$lang = "name_kor";
		}
	}

	if($_SESSION['logID'] == $admin)  {
		$sq = "select * from $user_table order by status desc ";
	}
	else  {
		$sq = "select * from $user_table where status>0";
	}
	
	$arr_user = array();
	$arr_user = Query2Array($connect, $sq);
	for($i=0; $i<sizeof($arr_user); $i++) {
		if($type_t == 'text')  {
			if($arr_user[$i][ID] == $ID) {
				return $arr_user[$i][$lang];
			}
		}
		else if($type_t =='select') {
			if($arr_user[$i][ID] == $ID) {
				$str .= "<option value='".$arr_user[$i][ID]."' selected>".$arr_user[$i][$lang]."</option>\n";
			}
			else {
				$str .= "<option value='".$arr_user[$i][ID]."' >".$arr_user[$i][$lang]."</option>\n";
			}
		}
	}
	if($type_t =='select') {
		$str = "<select  name='".$nname."'  class='input-medium' ".$Onchange.">".$str."</select>";
	}
	return $str;	
}

function query_auth($ID, $var_name, $auth)
{
	global $auth_table;
	global $user_table;
	global $connect;
	$ID = trim($ID);
	$var_name = trim($var_name);

	$sq = "select right_bit from ".$auth_table."  where ID='".$ID."'  and var_name = '".$var_name."' order by pk desc limit 1";
	$right_bit = mysqli_fetch_row(mysqli_query($connect, $sq))[0];
	if($auth) {
		if(!preg_match("/[0-9]/", $auth)) { // not number
			$tmp = " ".trim($auth);
			$auth = 0;
			if(strpos($tmp,'r')) {
				$auth |= 1;
			}
			if(strpos($tmp,'w')) {
				$auth |= 2;
			}
			if(strpos($tmp,'m')) {
				$auth |= 4;
			}
			if(strpos($tmp,'l')) {
				$auth |= 8;
			}
		}
		if($right_bit & $auth) return true;
	}
	else return false;
}


function PageMoving($thisP, $total, $toLink, $section = 10)
{
	$PrePageLinkImg  = "&#9668;";
	$NextPageLinkImg = "&#9658;";
	$PreSectorImg    = "<span style='letter-spacing: -6px' align='left'>&#9668;&#9668;</span>";
	$NextSectorImg   = "<span style='letter-spacing: -6px'>&#9658;&#9658;</span>";
	
	$p = strpos($toLink,"&page_no");
	$to_link_h = substr($toLink,0,$p);
	$to_link_f  = substr($toLink,$p+1, 200);
	$p = strpos($to_link_f,"&");
	$to_link_f  = substr($to_link_f,$p, 200);

	$toLink= $to_link_h.$to_link_f;
//	$toLink= $toLink."&".$p;


	


//	$PrePageLinkImg  = "<";
//	$NextPageLinkImg = ">";
//	$PreSectorImg    = "<span style='letter-spacing: -4px' align='left'><<</span>";
//	$NextSectorImg   = "<span style='letter-spacing: -4px'>>></span>";

	if($total > $section)
	{
		$re_Total = $total;
		$sector = ceil($total/$section);
		$this_sector = ceil($thisP/$section) ;
		$start = ($this_sector - 1)  * $section + 1;
		if($start + $section > $total)
			$last = $total;
		else 
			$last = $start + $section -1;
	}

	else 
	{
		$start = 1;
		$last = $total;
		$this_sector = 1;
		$sector = 1;
	}

	for($i=$start; $i<=$last; $i++)
	{
		$toLink_ = "$PHP_SELF?page_no=".$i.$toLink;
		if($i==$thisP)
		{
			$j = "<b>".$i."</b>";
			$re_PageMove = $re_PageMove. "<span class='page_num'>".$j."</span>";
		}
		else 
		{
			$j=$i;
			$re_PageMove = $re_PageMove."<span class='page_num'><a href=".$toLink_.">".$j."</a></span>";
		}

	}
	

	if($thisP>1)
	{
		$toLink_ = "$PHP_SELF?page_no=".($thisP-1).$toLink;
		$before = "<a href=".$toLink_.">".$PrePageLinkImg."</a>";
	}
	
	else
	{
		$before = $PrePageLinkImg;
	}
	
	if($thisP<$total)
	{
		$toLink_ = "$PHP_SELF?page_no=".($thisP+1).$toLink;
		$next = "<a href=".$toLink_.">".$NextPageLinkImg."</a>";
	}
	
	else
	{
		$next = $NextPageLinkImg;
	}

	if($sector > 1)
	{
		if($this_sector>1)
		{
			$toLink_ = "$PHP_SELF?page_no=".($thisP-$section).$toLink;
			$before_sector = "<a href=".$toLink_.">".$PreSectorImg."</a>";
			$before_dot = ". . .";
		}
		else
		{
			$before_sector = $PreSectorImg;
			$before_dot = "";
		}
		if($sector > $this_sector)
		{
			if($thisP + $section > $total)
			{
				$pg_ = $total;
			}
			else $pg_ = $thisP + $section;

			$toLink_ = "$PHP_SELF?page_no=".($pg_).$toLink;
			$next_sector = "<a href=".$toLink_.">".$NextSectorImg."</a>";
			$next_dot = ". . .";
		}
		
		else 
		{
			$next_sector = $NextSectorImg;
			$next_dot = "";
		}
	}
	
	else 
	{
		$before_sector = $PreSectorImg;
		$next_sector = $NextSectorImg;
		$next_dot = "";
		$before_dot = "";
	}


	
	$before_sector = "<span class='page_num'>".$before_sector."</span>";
	$before =  "<span class='page_num'>".$before."</span>";
	$next =  "<span class='page_num'>".$next."</span>";
	$next_sector =  "<span class='page_num'>".$next_sector."</span>";
	if($next_dot) $next_dot =  "<span class='page_num'>".$next_dot."</span>";
	if($before_dot) $before_dot =  "<span class='page_num'>".$before_dot."</span>";

	$re_PageMove = $before_sector.$before.$before_dot.$re_PageMove.$next_dot.$next.$next_sector;
	$re_PageMove ="
		<TABLE cellPadding=0 cellSpacing=0 width='100%'  border='0' bordercolor='#7ba6e4' style='border-collapse:collapse' align='center'>
		<tr><td align='center' >".$re_PageMove."</td>
		</tr>
		</table>
		";
	return $re_PageMove;
}

function make_button($nname, $value, $action, $width, $height, $over_color, $out_color, $border)
{
	if(!$action)
		$action = "";
	$action=str_replace(" ","",$action);
	
	if(strpos($width,'bold'))
						$bold = "font-weight:bold;";

	$str_exp = explode(',',$width);
	
	if($str_exp[1]) // string input
	{
		for($i=0; $i<sizeof($str_exp); $i++)
		{
			list($a,$b) = explode('=', $str_exp[$i]);
			if(trim($a) == 'width')
				$width= trim($b);
			if(trim($a) == 'height')
				$height= trim($b);
			if(trim($a) == 'overcolor')
				$over_color= trim($b);
			if(trim($a) == 'outcolor')
				$out_color= trim($b);
			if(trim($a) == 'border')
				$border= trim($b);
			if(trim($a) == 'color')
				$color= trim($b);
			if(trim($a) == 'size')
				$size= trim($b);
			if(trim($a) == 'face')
				$face= trim($b);
			if(trim($a) == 'round')
				$round= trim($b);

		}
	}
	
	if(!$over_color) 	$over_color = "#FFC0B0";
	if(!$out_color )	$out_color = "#D0A080";
	if(!$border)		$border = "1px solid #D00000";
	if(!$size)			$size = 12;


	if(!strpos($size,'px'))		$size .= 'px';
	if(!strpos($width,'px'))	 	$width .= 'px';
	if(!strpos($height,'px'))		$height .= 'px';

	if($round)			$round =  "-webkit-border-radius:.".$round."em; ";
	if($border)		$border = "border:".$border.";";
	if($size)			$size = "font-size:".$size.";";
	if($face)			$face = "font-family:".$face."; ";
	if($color)			$color = "color:".$color."; ";
	if($width)			$width = "width:".$width.";";
	if($height)		$height = "height:".$height.";";

	
	$str = "<input type='button' name='".$nname."'  value='".$value."' onclick=".$action." style=' ".$round.$border.$size.$face.$color.$bold.$width.$height." text-align:center; background-color:".$out_color.";' onMouseOver=this.style.backgroundColor='".$over_color."' onMouseOut=this.style.backgroundColor='".$out_color."' >";
	return $str;
}

function make_td_button($value, $action, $width, $height, $over_color, $out_color, $border)
{
	$value = str_replace("\n","<br>",$value);
	$value = str_replace("  ","<br>",$value);

	if(!$height)	$height="25";
	if(!$align)	$align="center";


	if(!$action)
		$action = "";
	$action=str_replace(" ","",$action);
	
	if(strpos($width,'bold'))
						$bold = "font-weight:bold;";

	$str_exp = explode(',',$width);
	
	if($str_exp[1]) // string input
	{
		for($i=0; $i<sizeof($str_exp); $i++)
		{
			list($a,$b) = explode('=', $str_exp[$i]);
			if(trim($a) == 'width')
				$width= trim($b);
			else if(trim($a) == 'height')
				$height= trim($b);
			else if(trim($a) == 'overcolor')
				$over_color= trim($b);
			else if(trim($a) == 'outcolor')
				$out_color= trim($b);
			else if(trim($a) == 'border')
				$border= trim($b);
			else if(trim($a) == 'color')
				$color= trim($b);
			else if(trim($a) == 'size')
				$size= trim($b);
			else if(trim($a) == 'face')
				$face= trim($b);
			else if(trim($a) == 'round')
				$round= trim($b);
			else if(trim($a) == 'align')
				$align= trim($b);

		}
	}
	
	if(!$over_color) 	$over_color = "#CCEEFF";
	if(!$out_color )	$out_color = "#C0C0B0";
	if(!$border)		$border = "0px solid #A0A0A0";
	if(!$size)			$size = 12;


	if($round)		$round =  "-webkit-border-radius:.".$round."em; ";
	if($border)		$border = "border:".$border.";";
	if($size)			$size = "font-size:".$size.";";
	if($face)			$face = "font-family:".$face."; ";
	if($color)		$color = "color:".$color."; ";
	if($width)		$width = "width:".$width."; ";
	if($height)		$height = "height:".$height."; ";
	if($align)		$align = "text-align:".$align."; ";

//	$str = "<td  onclick=".$action." ".$width.$height. "align=center style=' ".$round.$border.$size.$face.$color.$bold."  background-color:".$out_color.";' onMouseOver=this.style.backgroundColor='".$over_color."' onMouseOut=this.style.backgroundColor='".$out_color."' >".$value."</td>";
	$str = "<td  onclick=".$action." style=' padding-right:5px; padding-left:5px;".$round.$border.$size.$face.$color.$bold.$width.$height.$align."  cursor:hand; background-color:".$out_color."; '  onMouseOver=this.style.backgroundColor='".$over_color."' onMouseOut=this.style.backgroundColor='".$out_color."' >".$value."</td>";

//	echo $str;
	return $str;
}

function text2html($text)
{
	$text = str_replace(" ", "&nbsp;", $text);
	$text = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $text);
	$text = str_replace("<", "&lt;", $text);
	$text = str_replace(">", "&gt;", $text);
	$text = str_replace("\n", "<br>", $text);
//	$text = nl2br($text);
	return $text;

}
function text2htmltable($text)
{	
	$text = str_replace(" ", "&nbsp;", $text);
	$text = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $text);
	$text = str_replace("<", "&lt;", $text);
	$text = str_replace(">", "&gt;", $text);
//	$text = "<tr><td>".$text;
	$text = str_replace("\n", "</td></tr><tr><td>", $text);
//	$text = $text."</td></tr>";
	return $text;

}

function loginchk()
{
	global $LOGIN_PAGE;

	if(!$_SESSION['logID'] or !$_COOKIE['userseq'] or  (md5($_SESSION['logID']."test") != $_COOKIE['userseq']) )	{
		echo "
				<form name='login' action='".$LOGIN_PAGE."' method='post'>
				<input type='hidden' name='location_href' value='".$_SERVER['REQUEST_URI']."'>
				</form>";
		echo "<script>login.submit()</script>";
		exit;
	}
	else {
		return true;
	}
}

function q_uri($str)
{
	if(strpos(" ".$_SERVER[REQUEST_URI],$str))
		return true;
	
	return false;
}

function cert_code($lcode)
{
	$code = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

	for($i=0; $i<strlen($lcode); $i++)
	{
		$tet .=ord($lcode[$i])*($i+1);
	}

	for($i=0; $i<strlen($tet); $i++)
	{
		$tt = substr($tet,$i,2);
		$tt %= sizeof($code);
		$cert .= $code[$tt];
	}
	return $cert;
}

//////////////////////////////////// FILES /////////////////////////////////////////////////////

function view_img_embeded($img)
{
	$img = base64_encode($img );
	$img = "data:image/png;base64,".$img;

	return $img;
}

function view_doc($url, $filename, $method)
{
	global $files_dir;

	if(!$method) {
		$method = "post";
	}
	$folders = array("image_files", "sheet_files", "warehouse_log","document_files", "datasheet_files");
	for($i=0; $i<sizeof($folders); $i++) {
		if(strpos(" ".$url, $folders[$i])) {
			$dest = $folders[$i];
			break;
		}
	}
	if(!$filename) {
		$filename = array_pop(explode("/", $url));
	}

	$ext = strtoupper(array_pop(explode(".",$filename)));

	$dest = $files_dir.$dest."/".$filename;
	$dest = str_replace("//","/",$dest);

//	print $dest."--".$ext;
	if($dest) {
		if($method=='post') {
			echo "
			<form name='form_view_doc' name='s' action='/vca_user/libs/view_doc.php' method='post' ENCTYPE='multipart/form-data'>
			<input type='hidden' name='dest' value='".$dest."'>
			<input type='hidden' name='ext' value='".$ext."'>
			<form>
			";
			echo "<script>form_view_doc.submit();</script>";
		}
		else if($method=="link") {
			$arr_file[dest] = $dest;
			$arr_file[filename] = $filename;
			$arr_file[ext] = $ext;
			$arr_file['link'] = "../libs/view_doc.php?dest=".$dest."&ext=".$ext;
			return $arr_file;
		}
	}
}



function write_to_file_db($file_key, $url, $body, $str)
{
	global $files_dir;
	global $files_table;

	if(!$file_key) {
		echo "no file key";
		return false;
	}
	if(!$url) {
		echo "no url";
		return false;
	}
	if(!$body) {
		echo "no Body";
		return false;
	}

	if($str) {
		$s_str = explode(",", $str);
		for($i=0; $i<sizeof($s_str); $i++) {
			list($key, $val) = explode("=",$s_str[$i]);
			$key = trim($key);
			$val = trim($val);
			${$key} = $val;
		}
	}

	$dest = $files_dir.$url;
	$dest = str_replace("//","/", $dest);

	$fp = fopen($dest,"w");
	if(!$fp)  	{
		echo "ERR: cannot open fp:".$dest."<br>";
		return false;
	}
	if(!fwrite($fp, $body)) {
		echo "fail to write file";
	}
	@fclose($fp);
	$filesize = filesize($dest);

	$sq = "insert into $files_table(file_key, file_Name, file_Size, url, family, regdate) values('".$file_key."', '$filename', $filesize, '".$url."' ,'$family', now())";
	if(!mysql_query($sq))
	{
		print $sq."Write to File DB, FAIL";
		return false;
	}
	return true;
}


function upload_file_to_file_db($file_key, $file, $dest, $str)
{
	global $files_dir;
	global $files_table;
	
	$ext = array_pop(explode(".",$file["name"]));

	if(!$file_key) {
		echo "no file key";
		return false;
	}
	if(!$file) {
		echo "no file";
		return false;
	}
	if(!$dest) {
		echo "no dest";
		return false;
	}

	if($str) {
		$s_str = explode(",", $str);
		for($i=0; $i<sizeof($s_str); $i++) {
			list($key, $val) = explode("=",$s_str[$i]);
			$key = trim($key);
			$val = trim($val);
			${$key} = $val;
		}
	}
	$url = $dest."/X".mktime().rand(0,9).rand(0,9).rand(0,9).".".$ext;;
	$dest = $files_dir."/".$url;
	$dest = str_replace("//","/", $dest);


	if(!copy($file["tmp_name"], $dest)) 	{ 
		echo $file["tmp_name"]."->".$dest." COPY ERROR"; 
		return false; 
	}

	if(!unlink($file["tmp_name"])) {
		echo "Deleting temp file failed"; 
		return false;
	}
	$filesize = filesize($dest);

	$sq = "insert into $files_table(file_key, file_Name, file_Size, url, family, regdate) values('".$file_key."', '$filename', $filesize, '".$url."' ,'$family', now())";
	if(!mysql_query($sq))
	{
		print $sq."Write to File DB, FAIL";
		return false;
	}
	return true;
}

/*
function copy_file_to_db($file, $str) //  copy_file_to_db($file," file_key =".$file_key.", dest=../files/image_files/, family=mcode, filename=picture");
{
	global $DOCUMENT_ROOT;
//	$file = $_FILES[$fileatt];
	$filename = $file["name"];
	$filesize = ceil($file['size']);
	$ext = explode(".", $filename); 
	$ext_ = strtolower(array_pop($ext));

	$str = str_replace(" ","", $str);
	$cmd = explode(",",$str);
	
	// file_key, dest, org_filename, family

	for($i=0; $i<sizeof($cmd); $i++)
	{
		$s_cmd = explode("=",$cmd[$i]);
		switch($s_cmd[0])
		{
			case 'file_key':
				$file_key = $s_cmd[1];
				break;
			case 'dest':
				$dest_org = $s_cmd[1];
				break;
			case 'family':
				$family = $s_cmd[1];
				break;
			case 'filename':
				$org_filename = $s_cmd[1];
				break;
			default:
				$comment .= $cmd[$i].",";
				break;

		}
	}


	$newfilename = mktime().rand(0,9).rand(0,9).rand(0,9).".".$ext_;
	$dest_org .=$newfilename;

	if(substr($dest_org,0,1) == '/')
		$dest = $DOCUMENT_ROOT.$dest_org;
	else
		$dest = $dest_org;


	if(!$family) $family = "etc";
	if(!$org_filename) $org_filename = $filename;
	if(!$file_key)	$file_key = mktime().rand(0,9).rand(0,9).rand(0,9);

print $dest."<br>";
print $dest_org."<br>";

	if(!copy($file["tmp_name"], $dest))
	{ 
		echo "<script language='javascript'>alert('$dest COPY ERROR'); history.back();</script>"; 
		exit; 
	}

	if(!unlink($file["tmp_name"]))
	{
		echo "<script language='javascript'>alert('Deleting temp file failed.'); history.back();</script>"; 
		exit; 
	}
	$comment =  addslashes($comment);
	$insQuery = "insert into files(file_key, file_Name, file_Size, File_ReName, family, comment, regdate) values('$file_key', '$org_filename', $filesize, '$dest_org' ,'$family', '$comment', now())";
	change_db('intranet');
	if(!mysql_query($insQuery))
	{
		print $insQuery."NowFAIL";
		exit;
	}
	return $file_key;

}

function query_file_from_db($str) //  copy_file_to_db($file," file_key =".$file_key.", dest=../files/image_files/, family=mcode, filename=picture");
{
	global $files_table;
//	change_db('intranet');
	$selQuery = "select * from ".$files_table." where ".$str;
//	print $selQuery;
	$arr_files = Query2Array($selQuery);

	return $arr_files;

}
*/
function is_eng($str) 
{
	for($i = 0; $i < strlen($str); $i++)  
	{
		$char = ord($str[$i]);
		if($char >= 0xa1 && $char <= 0xfe)
		return false;
	}
	return true;
 }
 
 function is_num($num)
 {
	for($i=0; $i<strlen($num); $i++) {
		$c_num = substr($num, $i,1);
		if(!preg_match("/[0-9]/",$c_num)) {
			if($c_num != '.') 
				return false;
		}
	}
	return true;
 }

/*

function str_cut($str, $s, $n, $replace="...")
{
	$strLength = strlen($str);
	$n*=2;
	for($i=0; $i<$strLength; $i++)
	{
//		if(!(ord($str[$i]) >= 0xa1 && ord($str[$i]) <= 0xfe))
		if(ord($str[$i]) >127)
			$n--;
	}


	if($strLength <= $n)
		return $str;
	
	if(ord($str[$n])>127) 
		$n++;
	$str = substr($str,0, $n);

	$str .= $replace;
	return $str;
}
*/
function str_cut($str, $s, $n, $replace)
{
	if(strlen($str)>$n)
	{
		for($i=$s; $i<$n; $i++)
		{
			if(ord($str[$i])>127)
				$i+=2;
		}
		$str = substr($str,$s,$i);
		$str .= $replace;
	}
	return $str;
}

function strcut_utf8($str, $len, $checkmb=false, $tail='')
{
 
 preg_match_all('/[\xE0-\xFF][\x80-\xFF]{2}|./', $str, $match); // target for BMP
 
 $m = $match[0];
 $slen = strlen($str); // length of source string
 $tlen = strlen($tail); // length of tail string
 $mlen = count($m); // length of matched characters
 
 if ($slen <= $len)
	 return $str;
 if (!$checkmb && $mlen <= $len) 
	 return $str;
 
 $ret = array();
 $count = 0;
	for ($i=0; $i < $len; $i++) {
		$count += ($checkmb && strlen($m[$i]) > 1)?2:1;
		if ($count + $tlen > $len) break;
		$ret[] = $m[$i];
	}
 
}
////////////////////////  Language  /////////////////////////////////////

function read_language_pack($page, $language='chi')
{
	global $language_table;
	global $connect;

	if(!$language) {
		$language = $_COOKIE['selected_language'];
	}
//	global $msg_language;
	if(!$language)
		$language= "chi";
	$sq = "select var, ".$language." as lang_s from $language_table  ";
	if($page)
		$sq .= "where page='".$page."' or page='common' or page='Title' ";
//	print $sq;

	$arr_lang = Query2Array($connect, $sq);
//	$msg_language = $arr_lang;
	return $arr_lang;

}



function msg($en, $language='chi')
{
	global $msg_language;
	global $selected_language;


	$tmp = str_replace(" ","",$en);
	$tmp = strtoupper($tmp);

	for($i=0; $i<sizeof($msg_language); $i++)
	{
		$msg_language[$i]['var'] = strtoupper(str_replace(" ","",$msg_language[$i]['var']));
		if($tmp == $msg_language[$i]['var']) {
			$str =  $msg_language[$i][lang_s];
			break;
		}
	}

	if(!trim($str))
		$str = $en;

	if($size > strlen($str))
	{
		for($i= $size-strlen($str); $i>=0; $i-=2)
		{
			$str = "&nbsp;".$str."&nbsp";
		}
	}

	return $str;
}

function number_to_chinese($num_t)
{
	$num_ch= array('', msg('number one'), msg('number Two'), msg('number Three'), msg('number Four'), msg('number Five'), msg('number Six'), msg('number Seven'), msg('number Eight'), msg('number Nine'));
	$num_ch_unit_shi = array('',  msg('number Ten'), msg('number Hundred'),msg('number Thousand'));
	$num_ch_unit_wan = array('', msg('number Ten Thousand'), msg('number Hundred Million'));

	$return_val = ""; 
	if(!is_numeric($num_t)) 	{ 
		return false; 
	} 
	$num_t = number_format($num_t,2);
	$num_t = str_replace(",","",$num_t);

	list($num, $fen) = explode(".",$num_t);
	$num = strrev($num); 
	for($i =strlen($num)-1; $i>=0; $i--) 	{ 
		$digit = substr($num, $i, 1); 
		if($digit == '-') {
			$return_val.='(-)';
		}
		else {
			$return_val .= $num_ch[$digit];
		}
		if($digit=="-") {
			continue; 
		}

		if($digit != 0) 	{ 
			$return_val .= $num_ch_unit_shi[$i%4];
		} 

		if($i % 4 == 0) { 
			$return_val .= $num_ch_unit_wan[floor($i/4)];
		} 
	} 
	$return_val .= msg('CNY UNIT');

	if($fen)	{
		if($fen[0]) $return_val .= $num_ch[$fen[0]].msg('Money 0.1');
		if($fen[1]) $return_val .= $num_ch[$fen[1]].msg('Money 0.01');
	}
	return $return_val.msg('Money Tail'); 
}

/////////////////////////////////////////////////// Users /////////////////////////////////////////////////////

function department_info($nname, $sel, $type_t='text')
{
	global $language_table;
	$sq = "select ".$_COOKIE['selected_language']." from ".$language_table." where page='Variable' and var='Department' ";
	$arr_department = Query2Array($sq);

	for($i=0; $i<sizeof($arr_department); $i++) {
		$dept = explode('/', $arr_department[$i][$_COOKIE['selected_language']]);
		if( ($type_t=="text") and ($dept[0] == $sel) ) {
			return $dept[1];
		}
		else if($type_t=="select")  {
			if(($dept[0] == $sel) ){
				$str .= "<option value=".$dept[0]." selected>".$dept[1]."</option>";
			}
			else  {
				$str .= "<option value=".$dept[0]." >".$dept[1]."</option>";
			}
		}
	}
/*
	$msg_department_list = array( "--", msg('Admin Department'), msg('Sales Department'), msg('Technical Department'), msg('Manufacturing Department'));

	if($type_t == 'text') {
		return  $msg_department_list[$sel];
	}

	foreach($msg_department_list as $num=>$department) 	{
		if($num == $sel) $sel_t="selected";
		else $sel_t="";
		$str .= "<option value='".$num."' ".$sel_t." style='padding-top:5px;'>".$department."</option>\n";
	}
*/
	if($type_t=="select")  {
		$str="<select name='".$nname."' >".$str."</select>";
	}
	return $str;	

}

function jobtitle_info($nname, $sel, $type_t='text')
{
	global $language_table;

	$sq = "select ".$_COOKIE['selected_language']." from ".$language_table." where page='Variable' and var='Job_title' ";
//	print $sq;
	$arr_title = Query2Array($sq);
	for($i=0; $i<sizeof($arr_title); $i++) {
		$title = explode('/', $arr_title[$i][$_COOKIE['selected_language']]);
		if( ($type_t=="text") and ($title[0] == $sel) ) {
				return $title[1];
		}
		else if( $type_t=="select")  {
			if ($title[0] == $sel) {
				$str .= "<option value=".$title[0]." selected>".$title[1]."</option>";
			}
			else   {
				$str .= "<option value=".$title[0]." >".$title[1]."</option>";
			}
		}
	}
	if($type_t=="select")   {
		$str="<select name='".$nname."'>".$str."</select>";
	}


	return $str;	
	
}

/////////////////////////////////////////////////////// Logistic  /////////////////////////////////////////////////////////////////////////////////////////////

function bank_info($nname, $sel,$type_t)
{
	global $selected_language;
	global $language_table;

	$lang = "chi";
	if($selected_language == "eng") {
		$lang = "eng";
	}
	else if($selected_language == "kor") {
		$lang = "kor";
	}
	
	$sq = "select * from $language_table where page='Variable' and var='Bank Info' ";
//	print $sq;
	$arr_bank = Query2Array($sq);
	for($i=0; $i<sizeof($arr_bank); $i++) {
		$bank = explode('/', $arr_bank[$i][$_COOKIE['selected_language']]);

		if( ($type_t=="text") and ($bank[0] == $sel) ) {
				$str = $bank[1]."  ".$bank[2]." (".$bank[3].")";
				return $str;
		}
		else if( ($type_t=="document") and ($bank[0] == $sel) ) {
				$str  = msg('Advising Bank').":&nbsp;".$bank[1]."<br>";
				$str .= msg('Account Number').":&nbsp;".$bank[2]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				if($bank[4]) {
					$str .= msg('Swift Code').":&nbsp;".$bank[4];
				}
				$str .= "<br>";
				$str .= msg('Account Name').":&nbsp;".$bank[3];
				return $str;
		}
		
		if($type_t == "select") {
			$bank[1] = array_shift(explode(",",$bank[1]));
			if($bank[0] == $sel) {
				$str .= "<option value =".$bank[0]." selected>".$bank[1]." ".$bank[2]." (".$bank[3].") </option>\n";
			}
			else {
				$str .= "<option value =".$bank[0]." >".$bank[1]." ".$bank[2]." (".$bank[3].") </option>\n";
			}
		}
	}

	$str="<select name='".$nname."' class='input-block-level'>".$str."</select>";
	return $str;


}
function logistic_info($nname, $sel, $type_t, $inout=0, $act='', $auto='', $form='') // for ware table
{
	global $PHP_SELF;
	global $connect;
	global $language_table;
	if($inout ==1) {
		$inout = "in";
	}

	else if($inout==0) {
		$inout="out";
	}

	$sq = "select ".$_COOKIE['selected_language']." from ".$language_table." where page='Variable' and var='logistic_info' ";

	$arr_logistic = Query2Array($connect, $sq);
	for($i=0; $i<sizeof($arr_logistic); $i++) {
		$logistic = explode('/', $arr_logistic[$i][$_COOKIE['selected_language'] ]);
		if(($type_t == 'text') && ($logistic[0] == $sel)) {
			return $logistic[1];
		}
		else if(($type_t == 'label') && ($logistic[0] == $sel)) {
			return $logistic[2];
		}

		if($inout=='in') {
			if( $logistic[0]<10 ) {
				continue;
			}
		}
		else if($inout=='out') {
			if( ($logistic[0]>=10)&&($logistic[0]<20) ) {
				continue;
			}
		}
		if($type_t == 'select') {
			if($logistic[0]==$sel) {
				$str .= "<option value =".$logistic[0]." selected>".$logistic[1]."</option>\n";
			}
			else {
				$str .= "<option value =".$logistic[0]." >".$logistic[1]."</option>\n";
			}
		}

		else if($type_t=='checkbox') {
			if( ($sel>>$logistic[0])&1) {
				$tt = "checked";
			}
			else {
				$tt = "";
			}
			if($auto=='auto') {
				$auto = "onchange='auto_submit(".$form.")'";
			}
			$str .= "<input type='checkbox' name=".$nname."[".$logistic[0]."] ".$tt." class='input-medium' ".$auto.">".$logistic[1];
		}
	}


	if($type_t == 'select') {
		$str="<select name='".$nname."'  id='".$nname."'  ".$act." class='input-medium' >".$str."</select>";
	}


	return $str;		
}

function MobileCheck() { 
	$MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");

	$checkCount = 0; 
	for($i=0; $i<sizeof($MobileArray); $i++){ 
		if(preg_match("/$MobileArray[$i]/", strtolower($_SERVER['HTTP_USER_AGENT']))) { 
			$checkCount++; 
			break; 
		} 
	} 
	return ($checkCount >= 1) ? "Mobile" : "Computer"; 
}

/////////////////////////////////////////// MESSAGE //////////////////////////////////////

function send_message($from, $to, $comment)
{
	global $message_table;
	$comment = addslashes($comment);

	$sq = "insert into $message_table(regdate,from_id, to_id, comment) values(now(),'$from', '$to', '$comment')";
	if(mysql_query($sq) ) {
		print $sq."ok<br>";
	}


}

function sendmail($tos, $replys, $title, $content, $att )
{
	global $DOCUMENT_ROOT;
//	print $DOCUMENT_ROOT;
	require_once("/var/www/html/PhpMailer/class.phpmailer.php");

	$smtp_use = 'mail.youdi.net';
	$smtp_mail_id = "sender@youdi.net";
	$smtp_mail_pw = "abcd1234"; 
	$from = $smtp_mail_id;
	$fromName = "YOUDI INFO";

	$smtp_use = 'smtp.163.com';
	$smtp_mail_id = "youdishuma0@163.com";
	$smtp_mail_pw = "rootpass"; 
	$from = $smtp_mail_id;
	$fromName = "YOUDI INFO";

	$tos	= str_replace(" ","",$tos);
	$replys= str_replace(" ","",$replys);
	$to = explode(',',$tos);
//	$reply = explode(',',$replys);



	$mail = new PHPMailer(true);
	$mail->CharSet = "UTF-8";
	$mail->IsSMTP();
	try 
	{
		$mail->Host = $smtp_use; 
		$mail->SMTPAuth = true;  
		$mail->Port = 465;    
	  $mail->SMTPSecure = "ssl";
		$mail->Username   = $smtp_mail_id; 
		$mail->Password   = $smtp_mail_pw; 

		$mail->SetFrom($from, $fromName); 
		for($i=0; $i<sizeof($reply); $i++)
		{
			$mail->AddReplyTo($reply[$i], $replyName[$i]);
		}

		for($i=0; $i<sizeof($to); $i++)
		{
			$mail->AddAddress($to[$i], $toName[$i]);  
		}

		$mail->Subject = $title;         
		$mail->MsgHTML($content); 
//		$mail->MsgHTML(file_get_contents('contents.html'));
		if($att)
		{
			$mail->AddAttachment($att);      // attachment
		}
		$mail->Send();              //Send
		$rt =  "OK to send.";
	} 
	catch (phpmailerException $e) 
	{
		echo $e->errorMessage();
	} 
	catch (Exception $e) 
	{
		echo $e->getMessage();
	}
	
	if($rt) return true;

}
