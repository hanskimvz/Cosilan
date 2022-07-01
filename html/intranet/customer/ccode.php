<?PHP
session_start();

include  $_SERVER['DOCUMENT_ROOT']."/intranet/libs/dbconnect.php";
include  $_SERVER['DOCUMENT_ROOT']."/intranet/libs/functions.php";
include  $_SERVER['DOCUMENT_ROOT']."/intranet/ui.php";
// include  $_SERVER['DOCUMENT_ROOT']."/intranet/libs/city_list_china.php";

$msg_language = read_language_pack('Company');//

if(!$mode) {
	$mode = $_GET['mode'] ? $_GET['mode'] : "list";
}

loginchk();

function check_code_exist($code, $table)
{
	global $ccode_table;
	$sq= "select * from ".$table." where ccode = '".$code."' ";
	$num = mysql_num_rows(mysql_query($sq));
	print $sq.$num;
	if($num)
	{
		$sq = "select company_name from $ccode_table where code='".$code."'";
		$company_name = mysql_result(mysql_query($sq),0);

		echo "<script>alert(' ".$code.":".$company_name." exists in table ".$table."(".$num.")');history.back();</script>";
		return true;
	}
	return false;
}

function check_company_name_exist($company_name)
{
	global $city_list;
	global $ccode_table;
	$elim = array("（",  "）","(",")", msg('ltd.'), msg('plc.'), msg('inc.'), msg('digital'), msg('technology'), msg('information'), msg('electronic'),msg('intelligent'), msg('china')," " );
	$str = $company_name;
	for($i=0; $i<sizeof($city_list); $i++) 	{
		for($j=0; $j<sizeof($city_list[$i]); $j++) {
			$str = str_replace($city_list[$i][$j].msg('city'), "", $str);
			$str = str_replace($city_list[$i][$j].msg('state'), "", $str);
			$str = str_replace($city_list[$i][$j], "", $str);
		}
	}
	for($i=0; $i<sizeof($elim); $i++) 	{
		$str = str_replace($elim[$i], "", $str);
	}
	$str = trim($str);
	$sq = "select company_name from ".$ccode_table." where company_name like '%".$str."%'" ;
	print $sq;
	$old_name = mysql_result(mysql_query($sq),0);
	if($old_name) {
		return $old_name;
	}
	return false;
}


function tel_number($str)
{
	global $addr_state;
	global $addr_city;
	
	$area_code = ddd_code($addr_city);
	if(!$area_code)
		$area_code = ddd_code($addr_state);

	if(!trim($str)) 
		return "";

	$str = str_replace(" ","", $str);
	$str = str_replace("-","", $str);
	$str = str_replace("－","", $str);
	$str = str_replace("(","", $str);
	$str = str_replace(")","", $str);
	
	$str = trim($str);
	if( substr($str,0,2) == "00")
		$str = "+".substr($str,2,100);

	if( substr($str,0,1) != "+")
	{
		if( substr($str,0,1) == "0")
			$str = substr($str,1,100);

		$str = "+86".$str;
		
	}
	$str = str_replace("+86","0",$str);
	if((substr($str,0,3) != "010") && (substr($str,0,2) == "01"))
	{
		$str=substr($str,1,100);
	}

	if($addr_state != "--")
	{
		if(substr($str,0,strlen($area_code)) == $area_code)
		{
			$str = $str = substr($str,0,strlen($area_code))." ".substr($str,strlen($area_code),100);
		}
	}
	
	return $str;
}

print $mode;
switch($mode)
{
	case 'list':
		
		$search = trim($_POST['search']) ? trim($_POST['search']): trim($_GET['search']);
		if($search) {
			$act_q = "&search=".$search;
			$s_sq = "( company_name like '%".$search."%'  or code like '%".$search."%'  or addr_state like '%".$search."%'  or addr_city like '%".$search."%' or address_b like '%".$search."%' or ceo like '%".$search."%' or contact_person like '%".$search."%'   or contact_person2 like '%".$search."%'  or contact_person3 like '%".$search."%' or youdi_charge_id like '%".$search."%' )";
			if(substr($search, 0,1 ) == "/") {
				list($a,$b) = explode("=", substr($search,1,strlen($search)));
				$rs = mysqli_query($connect, "select * from $ccode_table where ".$a." like '%".$b."%'");
				if($rs) {
					$s_sq = " (".$a." like '%".$b."%') ";
				}
			}
		}

		else if($_POST['company_name'] or $_POST['address'] or $_POST['contact'] or $_POST['person_id_check']) {
			if($_POST['company_name']) {
				if($s_sq) {
					$s_sq .= " and ";
				}
				$s_sq .= "( company_name like '%".trim($_POST['company_name'])."%')";
			}
			if($_POST['address']) {
				if($s_sq) {
					$s_sq .= " and ";
				}
				$s_sq .= " (addr_state like '%".trim($_POST['address'])."%'  or addr_city like '%".trim($_POST['address'])."%' or address_b like '%".trim($_POST['address'])."%' )";
			}
			if($_POST['contact']) {
				if($s_sq) {
					$s_sq .= " and ";
				}
				$s_sq .= " (contact_person like '%".trim($_POST['contact'])."%'  or contact_person2 like '%".trim($_POST['contact'])."%' or contact_person3 like '%".trim($_POST['contact'])."%' )";
			}

			if($_POST['person_id_check']) {
				if($s_sq) {
					$s_sq .= " and ";
				}
				$s_sq .= " (youdi_charge_id = '".$_POST['person_id']."')";
			}

			$limit_page = 1;
		}

		
		if(!query_auth($_SESSION['logID'],'ccode_info','l') ) {
			if($s_sq) {
				$s_sq .= "and ";
			}
			$s_sq .= "youdi_charge_id = '".$_SESSION['logID']."'";
		}

		if($s_sq) {
			$s_sq = " where ".$s_sq;
		}
		$sq = "select * from $ccode_table " .$s_sq." order by  hit desc, code asc";
			
		$page_max = 25;
		$total_record = mysqli_num_rows(mysqli_query($connect, $sq));
		
		if(!$page_no) {
			$page_no = 1;
		}

		$total_page = ceil($total_record / $page_max); // each page has 30 lines
		$offset = ($page_no - 1)* $page_max;
		if(!$limit_page) {
			$sq .=  "  limit $offset, $page_max";
		}

		$arr_company = array();
		$arr_company = Query2Array($connect, $sq);
		$sizeof_company = sizeof($arr_company);

		$act = $_GET['act'] ? $_GET['act'] : 'ccode_management';
		
		if($act == 'ccode_management') {
			for ($i=0; $i< $sizeof_company; $i++) 	{
				$arr_company[$i]['code'] = "<a href='./ccode_info.php?mode=view&page_no=".$_GET['page_no']."&pk=".$arr_company[$i]['pk']."'>".$arr_company[$i]['code']."</a>";
				$arr_company[$i]['company_name'] = "<a href='./ccode_info.php?mode=view&page_no=".$_GET['page_no']."&pk=".$arr_company[$i]['pk']."'>".$arr_company[$i]['company_name']."</a>";
				list($arr_company[$i]['phone1']) = explode("x",$arr_company[$i]['phone1']);
				list($arr_company[$i]['phone2']) = explode("x",$arr_company[$i]['phone2']);
				list($arr_company[$i]['fax']) = explode("x",$arr_company[$i]['fax']);
				$arr_company[$i]['youdi_charge_id'] = user_info($name_lang, $arr_company[$i]['youdi_charge_id'],'text');
			}
		}
		else 		{
			for ($i=0; $i< $sizeof_company; $i++)		{
				$arr_company[$i][code]="<a href='./ccode.php?mode=insert&act=".$act."&pk=".$arr_company[$i][pk]."'>".$arr_company[$i][code]."</a>";
			}
		}
		
		break;

	case 'list_all':

		$sq = "select * from $ccode_table order by company_name asc";
		$arr_company = array();
		$arr_company = Query2Array($sq);
		$sizeof_company = sizeof($arr_company);
		for ($i=0; $i< $sizeof_company; $i++)
		{
			$pop1[$i] = "window.open('./ccode_info.php?mode=view&pk=".$arr_company[$i][pk]."','ccode_info','height=500px,width=680px, menubar=no, toolbar=no, location=no, scrollbars=no')";
			list($arr_company[$i][phone1]) = explode("x",$arr_company[$i][phone1]);
			list($arr_company[$i][phone2]) = explode("x",$arr_company[$i][phone2]);
			list($arr_company[$i][fax]) = explode("x",$arr_company[$i][fax]);

			if($arr_company[$i][company_name] == $arr_company[$i-1][company_name] )
				$err_same[$i] = "ERR";

		}

		break;

		
	case 'view':
//		print_arr($_GET);
		$sq =  "select * from ".$ccode_table." where pk=".$_GET['pk'];
		$arr_company = mysql_fetch_array(mysql_query($sq));

		if( ($_SESSION['logID'] != $arr_company[youdi_charge_id] ) && !query_auth($_SESSION['logID'],'ccode_info','r') ) 	{
			echo "<script> alert('".msg('you dont have right to view')."'); history.back();</script>";
			exit;
		}

		break;
		
	case 'modify':
		if( ($_SESSION['logID'] != $arr_company[youdi_charge_id] ) && !query_auth($_SESSION['logID'],'ccode_info','wm') )  {
			echo "<script> alert('".msg('you dont have right to edit')."');history.back();</script>";
			exit;
		}

		$code = trim($_POST['code']);
		$company_name = addslashes(trim($_POST['company_name']));
		$ceo = addslashes(trim($_POST['ceo']));
		$address = addslashes($_POST['address']);
		$address_b = addslashes(trim($_POST['address_b']));
		$addr_state = addslashes($_POST['addr_state']);
		$addr_city = addslashes($_POST['addr_city']);
		$zip_code = trim($_POST['zip_code']);
		$ID = trim($_POST['ID']);
		$passwd = trim($_POST['passwd']);
		$homepage = trim(str_replace("http://","",$_POST['homepage']));
		$homepage = addslashes($homepage);
		$phone1 = tel_number($_POST['phone1']);
		$phone2 = tel_number($_POST['phone2']);
		$phone3 = tel_number($_POST['phone3']);
		$contact_person = addslashes(trim($_POST['contact_person']));
		$contact_person2 = addslashes(trim($_POST['contact_person2']));
		$contact_person3 = addslashes(trim($_POST['contact_person3']));
		$contact_tel = tel_number($_POST['contact_tel']);
		$contact_tel2 = tel_number($_POST['contact_tel2']);
		$contact_tel3 = tel_number($_POST['contact_tel3']);
		$fax = tel_number($_POST['fax']);
		$email = addslashes(trim($_POST['email']));
		$email2 = addslashes(trim($_POST['email2']));
		$email3 = addslashes(trim($_POST['email3']));
		
		if(strtolower($code) == 'delete') {
			if(check_code_exist($_POST['old_code'], $code_table)) {
				exit;
			}
			if(check_code_exist($_POST['old_code'], $ware_table)) {
				exit;
			}
			$delQuery = "delete from $ccode_table where pk=".$_GET['pk'];
			if(mysql_query($delQuery)) {
				echo $_POST['old_code'].$_POST['ID']."is deleted";
				$href = "./list_ccode.php?mode=list&page_no=".$_GET['page_no'];
				echo "<script>location.href=('".$href."')</script>";
			}
			exit;
		}
		
		if($code != $_POST['old_code']) {
			if(check_code_exist($code, $ccode_table)) {
				exit;
			}
			if($_POST['old_code']) {
				$sq = "update ".$lcode_table." set ccode='".$code."' where ccode = '".$_POST['old_code']."'"; // lcode table update
				print $sq;
				if(mysql_query($sq)) {
					print "<font color=#0000FF>OK</font><br>";
				}
				else {
					print "<font color=#FF0000>FAIL</font><br>"; 
				}
				
				$sq = "update ".$ware_table." ccode='".$code."' where ccode = '".$_POST['old_code']."'"; // ware table update
				print $sq;
				if(mysql_query($sq)) {
					print "<font color=#0000FF>OK</font><br>";
				}
				else {
					print "<font color=#FF0000>FAIL</font><br>";
				}
				$sq = "update ".$rebate_table." ccode='".$code."' where ccode = '".$_POST['old_code']."'"; // ware table update
				print $sq;
				if(mysql_query($sq)) {
					print "<font color=#0000FF>OK</font><br>";
				}
				else {
					print "<font color=#FF0000>FAIL</font><br>";
				}
			}
		}

		if($ID != $_POST['old_ID']) {
			$sq =  "select code from $ccode_table where ID = '".$ID."' ";
			$num = mysql_num_rows(mysql_query($sq));
			if($num)  {
				echo "<script> alert('ID:".$ID." ".msg('already exist')."');history.back();</script>";
				exit;
			}
		}


		if($company_name != $_POST['old_company_name']) {
			$new_name = check_company_name_exist($company_name);
			if($new_name) {
				echo "<script>
					if(!confirm(' \' ".$new_name." \' ".msg('already  exist')."')) {
						history.back();
					}
					</script>";
//				exit;
			}
		}

		for($i=0; $i<10; $i++)  {
			$family_n |= $_POST['family'][$i];
		}
		

		$sq = "update ".$ccode_table." set code='".$code."', company_name='".$company_name."', ceo='".$ceo."', phone1='".$phone1."', phone2='".$phone2."', phone3='".$phone3."', fax='".$fax."',  address_b='".$address_b."', addr_state='".$addr_state."', addr_city='".$addr_city."', zip_code='".$zip_code."', homepage='".$homepage."',  contact_person='".$contact_person."', contact_tel='".$contact_tel."', email='".$email."', contact_person2='".$contact_person2."', contact_tel2='".$contact_tel2."', email2='".$email2."', contact_person3='".$contact_person3."', contact_tel3='".$contact_tel3."', email3='".$email3."', comment='".$comment."', ID='".$ID."', passwd='".$passwd."', youdi_charge_id='".$_POST['youdi_charge_id']."', file_key='".$_POST['file_key']."', family=".$family_n." where pk = ".$_GET['pk'];

		print "<br>".$sq;
		if(mysql_query($sq))	{
			print "OK";
			$href = "./ccode_info.php?mode=view&page_no=".$_GET['page_no']."&pk=".$_GET['pk'];
			echo "<script>location.href=('".$href."')</script>";
		}	
		else	{
			print "  Fail";
			exit;
		}	

		break;

	case 'add':
		if(!query_auth($_SESSION['logID'],'ccode_info','w') ) 	{
			echo "<script> alert('".msg('you dont have right to edit')."');self.close();</script>";
			exit;
		}
		$regdate = date("Y-m-d H:i:s");
		$sq = "select code from $ccode_table order by code desc limit 1";
		$code =  mysql_result(mysql_query($sq),0); 
		$code = substr($code,1,strlen($code)) +1;
		for($i=strlen($code); $i>0; $i--) {
			$zero .="0";
		}
		$code = "C".$zero.$code;
		
		$sq = "insert into $ccode_table(regdate, code, hit) values('$regdate', '$code',0)";
		print $sq;
		
		mysql_query($sq);

		$sq = "select pk from $ccode_table where regdate='$regdate'";
		$pk =  mysql_result(mysql_query($sq),0);
		
		$href = "./ccode_info.php?mode=view&pk=".$pk;
		echo "<script>location.href=('".$href."')</script>";

		break;

	case 'insert':
		print "Insert Start";
		
		
		$sq =  "select * from $ccode_table where pk=$pk";
		$arr_company = mysql_fetch_array(mysql_query($sq));
		
		if(!$arr_company[hit])
			$arr_company[hit] = 0;
		$arr_company[hit]++;
		$sq = "update ".$ccode_table." set hit=".$arr_company[hit]." where pk=".$pk;
		mysql_query($sq);


		if(!$form)
			$form = 'form1';

		if($arr_company[addr_city])
		{
			$arr_company[address] = $arr_company[addr_state].$msg_state.$arr_company[addr_city].$msg_city.$arr_company[address_b];
		}
		else if($arr_company[addr_state])
			$arr_company[address] = $arr_company[addr_state].$msg_city.$arr_company[address_b];
		else if($arr_company[address_b])
			$arr_company[address] = $arr_company[address_b];


		if( ($act=='ware') or ($act == "pl") )   {
			echo "<script>
				opener.".$form.".ccode.value ='".$arr_company[code]."';
				opener.".$form.".company_name.value ='".$arr_company[company_name]."';
				self.close();
				</script>";
		}
		else if($act=='logi')
		{
			echo	"<script>opener.".$form.".ccode.value ='".$arr_company[code]."';</script>";
			echo	"<script>opener.".$form.".company_name.value ='".$arr_company[company_name]."';</script>";
			echo	"<script>	opener.".$form.".contact_person.value ='".$arr_company[contact_person]."';</script>";
			echo	"<script>opener.".$form.".contact_tel.value ='".$arr_company[contact_tel]."';</script>";
			echo	"<script>	opener.".$form.".contact_person2.value ='".$arr_company[contact_person2]."';</script>";
			echo	"<script>opener.".$form.".contact_tel2.value ='".$arr_company[contact_tel2]."';</script>";
			echo	"<script>	opener.".$form.".contact_person3.value ='".$arr_company[contact_person3]."';</script>";
			echo	"<script>opener.".$form.".contact_tel3.value ='".$arr_company[contact_tel3]."';</script>";
			echo	"<script>	opener.".$form.".contact_person1.value ='".$arr_company[ceo]."';</script>";
			echo	"<script>opener.".$form.".contact_tel1.value ='".$arr_company[phone1]."';</script>";
			echo	"<script>	opener.".$form.".contact_fax.value ='".$arr_company[fax]."';</script>";
			echo	"<script>	opener.".$form.".ship_address.value ='".$arr_company[address]."';</script>";
			echo	"<script>	self.close();</script>";
		}
		
		else if($act=='sales_doc')
		{
			echo "<script>opener.".$form.".ccode.value ='".$arr_company[code]."'</script>";
			echo "<script>opener.".$form.".company_name.value ='".$arr_company[company_name]."'</script>";
			echo "<script>opener.".$form.".contact_person.value ='".$arr_company[contact_person]."'</script>";
			echo "<script>opener.".$form.".contact_person_cc.value ='".$arr_company[contact_person]."'</script>";
			echo "<script>opener.".$form.".contact_tel.value ='".$arr_company[contact_tel]."'</script>";
			echo "<script>opener.".$form.".contact_fax.value ='".$arr_company[fax]."'</script>";
			echo "<script>opener.".$form.".ship_to.value ='".$ship_to."'</script>";
			echo "<script>$('#ship_to').append(hello);</script>";

			echo "<script>opener.".$form.".bill_to.value ='".$arr_company[address]."'</script>";
			echo "<script>self.close();</script>";
		}
		else if($act=='price_history')	{
			echo "<script>opener.".$form.".ccode.value ='".$arr_company[code]."'</script>";
			echo "<script>opener.".$form.".company_name.value ='".$arr_company[company_name]."'</script>";
			echo "<script>self.close();</script>";
		}
		break;


}
?>
