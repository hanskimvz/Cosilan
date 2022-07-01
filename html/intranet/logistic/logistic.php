<?PHP
session_start();
//$DB_GROUP = "intranet";
$_SESSION['DB_GROUP']  = "intranet";
// print $_SERVER['DOCUMENT_ROOT'];

include  $_SERVER['DOCUMENT_ROOT']."/intranet/libs/dbconnect.php";
include  $_SERVER['DOCUMENT_ROOT']."/intranet/libs/functions.php";
include  $_SERVER['DOCUMENT_ROOT']."/intranet/ui.php";


$sheets_folder = $files_dir."sheet_files/";

$msg_language = read_language_pack('logistic');

$flag_ok = "&#9679;";

// loginchk();
if(!$mode) {
	$mode = $_GET['mode'] ? $_GET['mode'] : "list";
}

switch($mode)
{
	case 'cancel':
		$sq = "select code, ccode from $lcode_table where pk=$pk";
		$arr_lcode = mysql_fetch_array(mysql_query($sq));
		
		$sq = "select * from $cart_table where lcode='".$arr_lcode[code]."'";
		print $sq;
		if(!$arr_lcode[ccode] || !mysql_num_rows(mysql_query($sq)) )
		{
			$sq = "delete from $lcode_table where pk=$pk";
			mysql_query($sq);
			echo "<script>opener.location.reload()</script>";
		}
		echo "<script>self.close()</script>";
		break;
	
	case 'list':

		$action = "mode=list";
		$act_q = "";

		$search_sq = array();
		if($_GET['ccode']) {
			$search_sq[0] = " ccode='".$_GET['ccode']."'";
			$act_q = "&ccode=".$_GET['ccode'];
		}
		if($_GET['fenlei']) {
			$search_sq[1] = " fenlei=".$_GET['fenlei'];
			$act_q = "&fenlei=".$_GET['fenlei'];
		}
		if($_GET['person_id']) {
			$search_sq[2] =" person_id='".$_GET['person_id']."'";
			$act_q = " person_id=".$_GET['person_id'];
		}
		
		$search = trim($_POST['search']) ? trim($_POST['search']): trim($_GET['search']);
		if($search) {
			$act_q = "&search=".$search;
			if(strtoupper(substr($search,0,1)) == "L") {
				$search_sq[0] = " code like '".$search."%'";
			}

			$sq = "select code from ".$ccode_table." where company_name like '%".$search."%'";
			$arr_ccode = Query2Array($connect, $sq);
			for($i=0; $i<sizeof($arr_ccode); $i++) {
				if($search_sq[1]) {
					$search_sq[1] .= " or";
				}
				$search_sq[1] .= " ccode ='".$arr_ccode[$i][code]."'";
			}

			$lang = $_COOKIE['selected_language'] ? $_COOKIE['selected_language'] : "chi";
			$sq = "select * from ".$language_table." where page='Variable' and var='logistic_info'  and ".$lang." like '%".$search."%' ";
			$arr_fenlei = Query2Array($connect, $sq);
			for($i=0; $i<sizeof($arr_fenlei); $i++) {
				if(strpos(" ".$arr_fenlei[$i][$lang], $search)) {
					list($fenlei) = explode("/",$arr_fenlei[$i][$lang]);
					if($search_sq[2]) {
						$search_sq[2] .= " or ";
					}
					$search_sq[2] .= " fenlei = ".$fenlei;
				}
			}

			$sq = "select * from ".$user_table." where ID like '%".$search."%' or name_chi like '%".$search."%' or  name_eng like '%".$search."%' or  name_kor like '%".$search."%' ";
			$arr_user = Query2Array($connect, $sq);
			for($i=0; $i<sizeof($arr_user); $i++) {
				if($search_sq[3]) {
					$search_sq[3] .= " or ";
				}
				$search_sq[3] .= "person_id = '".$arr_user[$i][ID]."'";
			}

		}

		for($i=0; $i<10; $i++)	{
			if($search_sq[$i]) {
				if($str_search) {
					$str_search .= " or ";
				}
				$str_search .= "(".$search_sq[$i].")";
			}
		}

		if($str_search) {
			$str_search = "  (".$str_search.")";
		}
		if( !query_auth($_SESSION['logID'],'logi_info', "l") and strpos($_SERVER['REQUEST_URI'],'list_lcode') ) {
			if($str_search) {
				$str_search .= " and ";
			}
			else {
				$str_search .= " person_id = '".$_SESSION['logID']."'";
			}
		} 
		if($str_search) {
			$str_search = " where ".$str_search;
		}

		$sq = "select * from ".$lcode_table." ".$str_search." order by regdate desc, order_date desc";

		$page_max = 22;
		$total_record = (mysqli_query($connect, $sq))->num_rows;

		if(!$page_no) {
			$page_no = 1;
		}
		$total_page = ceil($total_record / $page_max); 
		$offset = ($page_no - 1)* $page_max;
		$sq .=  "  limit $offset, $page_max";

		$arr_lcode = array();
		$arr_lcode = Query2Array($connect, $sq);

		$sizeof_lcode = sizeof($arr_lcode);

		$act = $_GET['act'] ? $_GET['act'] : 'order_management';
		
		for ($i=0; $i<$sizeof_lcode; $i++)
		{
			$sqa = "select company_name from $ccode_table where code = '".$arr_lcode[$i]['ccode']."'";
			$arr_lcode[$i]['company_name'] = mysqli_fetch_row(mysqli_query($connect, $sqa))[0];
			$arr_lcode[$i]['company_name'] = "<a href= '?mode=list&ccode=".$arr_lcode[$i]['ccode']."&act=".$act."'>".$arr_lcode[$i]['company_name']."</a>";
            
            $arr_lcode[$i]['order_date'] = check_date($arr_lcode[$i]['order_date']);
			$arr_lcode[$i][exp_out_date] = check_date($arr_lcode[$i][exp_out_date]);	
			$arr_lcode[$i][out_date] = check_date($arr_lcode[$i][out_date]);
			$arr_lcode[$i][ship_date] = check_date($arr_lcode[$i][ship_date]);
           
			$arr_lcode[$i][fenlei] = "<a href= '?mode=list&act=".$act."&fenlei=".$arr_lcode[$i][fenlei]."'>".logistic_info('fenlei', $arr_lcode[$i]['fenlei'], 'label')."</a>"; 
			$arr_lcode[$i][person_id] = "<a href= '?mode=list&act=".$act."&person_id=".$arr_lcode[$i][person_id]."'>".user_info($name_lang,$arr_lcode[$i]['person_id'],'text')."</a>";

	
			if($act == 'order_management') {
				
				if(query_auth($_SESSION['logID'], 'logi_info', 'r') ) {
					if($arr_lcode[$i][total_amount]>=100000000) {
						$arr_lcode[$i][company_name] .= "&nbsp;(".number_format($arr_lcode[$i][total_amount]/100000000,1).msg('x100000000').")";
					}
					else if($arr_lcode[$i][total_amount]>=10000) {
						$arr_lcode[$i][company_name] .= "&nbsp;(".number_format($arr_lcode[$i][total_amount]/10000,1).msg('x10000').")";
					}
					else if($arr_lcode[$i][total_amount]) {
						$arr_lcode[$i][company_name] .= "&nbsp;(".number_format($arr_lcode[$i][total_amount],0).")";
					}
				}					
				$sqa = "select pk, url, file_Name from ".$files_table." where (family='logistic' or family = 'sales') and file_key = '".$arr_lcode[$i][file_key]."'  order by pk asc ";
				$arr_files = Query2Array($connect, $sqa);
				for($j=0; $j<sizeof($arr_files); $j++)
				{
					if($arr_files[$j][file_Name] == 'wareout') {
						$pop_doc[$i][0] = "<div class=\"icon\" onClick=\"window.open('./logistic.php?mode=view_doc&url=". $arr_files[$j][url]."&doc=ware_out&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=800px, width=800px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')\" style=\"cursor:pointer\" title=\"".msg('warehouse sheet')."\"><span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe025;\"></span></div>";
					}
					else if($arr_files[$j][file_Name] == 'contract') {
						$pop_doc[$i][1] = "<div class=\"icon\" onClick=\"window.open('./logistic.php?mode=view_doc&url=". $arr_files[$j][url]."&doc=contract&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=1000px, width=800px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')\" style=\"cursor:pointer\" title=\"".msg('contract')."\"><span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe025;\" style=\"color:#30A000;\"></span></div>";

					}
					else if($arr_files[$j][file_Name] == 'packing_list') {
						$doc[5] = "packing_list";				$url[5] = $arr_files[$j][url];		$proc[$i][5] = "<font size=4>&#9679;</font>";
						$pop_doc[$i][2] = "<div class=\"icon\" onClick=\"window.open('./logistic.php?mode=view_doc&url=". $arr_files[$j][url]."&doc=packing_list&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=1000px, width=800px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')\" style=\"cursor:pointer\" title=\"".msg('packing list')."\"><span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe025;\" style=\"color:#0030A0;\"></span></div>";
					}
				}
              
				$pop_proc[$i][0] = "<span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe139;\" style=\"color:#D0D0D0;\"></span>";
				$pop_proc[$i][1] = "<span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe139;\" style=\"color:#D0D0D0;\"></span>";
				$pop_proc[$i][2] = "<span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe139;\" style=\"color:#D0D0D0;\"></span>";

				if($arr_lcode[$i][out_date])	{
					$pop_proc[$i][0] = "<span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe0fe;\" style=\"color:#808080;\"></span>";
				}
				$pop_proc[$i][0] = "<div class=\"icon\" onClick=\"window.open('./proc.php?tab=out_date&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=490px, width=300px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')\" style=\"cursor:pointer\" title=\"".msg('ware out')."\">".$pop_proc[$i][0]."</div>";

				if($arr_lcode[$i][ship_date])	{
					$pop_proc[$i][1] =  "<span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe0fe;\" style=\"color:#808080;\"></span>";
				}
				$pop_proc[$i][1] =  "<div class=\"icon\" onClick=\"window.open('./proc.php?tab=ship_date&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=490px, width=300px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')\" style=\"cursor:pointer\" title=\"".msg('delivery')."\">".$pop_proc[$i][1]."</div>";

				if($arr_lcode[$i][process]&0x80) {
					$pop_proc[$i][2] =  "<span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe0fe;\" style=\"color:#808080;\" title=\"".msg('completed')."\"></span>";
				}
				if($arr_lcode[$i][process]&0x40)	{
					$pop_proc[$i][2] =  "<span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe0fd;\" style=\"color:#808080;\" title=\"".msg('delete')."\"></span>";
				}
				if($arr_lcode[$i][process]&0x20)	 {
					$pop_proc[$i][2] .=  "<span class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe078;\" style=\"color:#808080;\"  title=\"".msg('hidden')."\"></span>";
				}
				$pop_proc[$i][2] =  "<div class=\"icon\" onClick=\"window.open('./proc.php?tab=process&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=250px, width=300px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')\" style=\"cursor:pointer\" >".$pop_proc[$i][2]."</div>";

				$color = "#0000FF";
				if($arr_lcode[$i][process]<64) 	{
//					$sqa = "select sum(quantity) from $cart_table where lcode='".$arr_lcode[$i][code]."'";
//					$lcode_Q = mysql_result(mysql_query($sqa),0);

//					$sqa = "select sum(quantity) from $ware_table where lcode ='".$arr_lcode[$i][code]."'";
//					$ware_Q = mysql_result(mysql_query($sqa),0);
				
					$sqa = "select sum($cart_table.quantity) - sum($ware_table.quantity) as qty_diff from $cart_table inner join $ware_table where $cart_table.lcode='".$arr_lcode[$i][code]."' and $ware_table.lcode='".$arr_lcode[$i][code]."'";
//					if($lcode_Q != $ware_Q) {
					if(mysqli_fetch_row(mysqli_query($connect, $sqa))[0] ) {
						$color = "#FF0000";
					}
                }
                
				$arr_lcode[$i][out_date] = "<span onClick=\"window.open('./write_pl.php?mode=view&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=900px, width=900px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')\" style=\"color:".$color."; cursor:pointer;\" >".$arr_lcode[$i][out_date]."</span>";
				$color = "#000000";
				if($arr_lcode[$i][dist_info]) {
					$color = "#005aaa";
				}
				$arr_lcode[$i][ship_date] = "<div class=\"icon\" onClick=\"window.open('./proc.php?tab=distribution&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=250px, width=300px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')\" style=\"color:".$color.";cursor:pointer\"		>".$arr_lcode[$i][ship_date]."</div>";
				
				$arr_lcode[$i][code] = "<a href= './view_lcode_info.php?mode=view&pk=".$arr_lcode[$i][pk]."&page_no=".$page_no."&ck_sel=".$ck_sel."&page_max=".$page_max."&act=".$act."&user=".$user."&valid=".$valid."'><b>".$arr_lcode[$i][code]."</b></a>";
               
            }

			else if ($act == "ware") {
				$sqa = "select * from $cart_table where lcode = '".$arr_lcode[$i][code]."'";
				$arr_cart = Query2Array($connect, $sqa);
				for($k=0; $k<sizeof($arr_cart); $k++)  {
					$arr_cart[$k][model] = mysqli_fetch_row(mysqli_query($connect, "select model from $item_table where code= '".$arr_cart[$k][item_code]."' and family= '".$arr_cart[$k][family]."'"))[0];
					$arr_cart[$k][item_code] = "<a href ='./logistic.php?mode=insert&act=".$act."&pk=".$arr_cart[$k][pk]."'>".$arr_cart[$k][item_code]."</a>";

					$arr_lcode[$i][cart_table] .="<tr><td></td><td>".$arr_cart[$k][item_code]."</td><td>".$arr_cart[$k][model]."</td><td>".$arr_cart[$k][item_name]."</td><td>".$arr_cart[$k][quantity]."</td><td>".$arr_cart[$k][comment]."</td></tr>";
				}
				if($arr_lcode[$i][cart_table] ) {
					$arr_lcode[$i][cart_table]  = '<table  class="table table-condensed table-striped table-bordered table-hover no-margin"><tr><th>&nbsp;</th><th>'.msg('item_code').'</th><th>'.msg('model').'</th><th>'.msg('item name').'</th><th>'.msg('quantity').'</th><th>'.msg('comment').'</th></tr>   '.$arr_lcode[$i][cart_table].'</table>';
				}
			}
			
			else 	{
				$arr_lcode[$i][code] ="<a href= './logistic.php?mode=insert&act=".$act."&pk=".$arr_lcode[$i][pk]."'>".$arr_lcode[$i][code]."</a>";
				$sqa = "select file_ReName from ".$files_table." where (family='logistic' or family = 'sales') and file_key = '".$arr_lcode[$i][file_key]."'  and file_Name = 'wareout' order by pk desc limit 1";
				$doc[1] = "wareout";				$url[1] = mysqli_fetch_row(mysqli_query($connect, $sqa))[0];		if($url[1])		$proc[$i][1] = "<font size=4>&#9679;</font>";
				for($j=0; $j<17; $j++)
				{
					$popup[$i][$j] = "window.open('./logistic.php?mode=view_doc&url=".$url[$j]."&doc=".$doc[$j]."&pk=".$arr_lcode[$i][pk]."', 'view_doc','height=".$new_window_h[$j]."px, width=".$new_window_w[$j]."px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')";
				}

			}
           
        }
        
		break;
	



	case 'view':
		if(!$_GET['pk'])	{
			$sq = "select pk from ".$lcode_table." where code = '".$_GET['lcode']."'";
			$_GET['pk'] = mysql_result(mysql_query($sq),0);
		}
		
		$sq = "select * from $lcode_table where pk = ".$_GET['pk'];
		$arr_lcode = mysql_fetch_array(mysql_query($sq));
		
		if(strpos($_SERVER['REQUEST_URI'],'view_lcode_info') ) {
			if( !(query_auth($_SESSION['logID'],'logi_info',1) or ($arr_lcode[person_id] == $_SESSION['logID']) )) 	{
				echo "<script>alert('".msg('No right')."');history.back();</script>";
				exit;
			}
		}
		
		$arr_lcode[order_date] = check_date($arr_lcode[order_date]);
		$arr_lcode[exp_out_date] = check_date($arr_lcode[exp_out_date]);
		$arr_lcode[exp_return_date] = check_date($arr_lcode[exp_return_date]);
		$arr_lcode[out_date] = check_date($arr_lcode[out_date]);
		$arr_lcode[ship_date] = check_date($arr_lcode[ship_date]);

		if(!$arr_lcode[order_date]) {
			$arr_lcode[order_date] = $today;
		}

		if(!$arr_lcode[exp_out_date]) {
			$arr_lcode[exp_out_date] = date("Y-m-d", strtotime('+1 days'));
		}
			
		if(!$arr_lcode[exp_return_date]) {
			$arr_lcode[exp_return_date]= date("Y-m-d", strtotime('+1 month'));
		}

		if(!$arr_lcode[wrap_method]) {
			$arr_lcode[wrap_method] = msg('Standard Packing');
		}
		if(!$arr_lcode[ship_method]) {
			$arr_lcode[ship_method] = msg('Quick Service');
		}


		$sq = "select company_name, contact_person, contact_tel, contact_person2, contact_tel2, contact_person3, contact_tel3 from ".$ccode_table." where code = '".$arr_lcode[ccode]."'";
		$arr_company = mysql_fetch_array(mysql_query($sq));

		$sq = "select * from ".$files_table." where (family='logistic' or family ='sales') and file_key = '".$arr_lcode[file_key]."' order by pk asc";
//		print $sq;
		$arr_files = Query2Array($sq);
		for($i=0; $i<sizeof($arr_files); $i++) {
			if($arr_files[$i][file_Name] == 'wareout') {
				$arr_lcode[wareout] = $arr_files[$i][url];
			}
			if($arr_files[$i][file_Name] == 'contract') {
				$arr_lcode[contract] = $arr_files[$i][url];
			}
			if($arr_files[$i][file_Name] == 'packing_list') {
				$arr_lcode[packing_list] = $arr_files[$i][url];
			}

		}
		$sq = "select * from ".$cart_table." where lcode='".$arr_lcode[code]."'";
		$arr_cart = Query2Array($sq);
		$sizeof_cart = sizeof($arr_cart);
		for($i=0; $i<$sizeof_cart; $i++) 		{
			$sq = "select * from ".$item_table." where code='".$arr_cart[$i][item_code]."' and family='".$arr_cart[$i][family]."'";
			$rs = mysql_fetch_array(mysql_query($sq));
			$arr_cart[$i][model] = $rs[model];
			$arr_cart[$i][spec] = $rs[spec];
			$arr_cart[$i][subtotal] = $arr_cart[$i][price]*$arr_cart[$i][quantity];
		}
		
		$arr_lcode[price_total_contract] = $arr_lcode[total_amount] - $arr_lcode[delivery_charge] - $arr_lcode[tax_amount];

		break;

	case 'modify':
//		print_arr($_POST); 
		
		if( (!query_auth($_SESSION['logID'],'logi_info', 'm') and ($_SESSION['logID'] != $_POST['person_id'])  and $_GET['pk']) or (!query_auth($_SESSION['logID'],'logi_info', 'w') and !$_GET['pk'])) {
			echo "<script>alert('".msg('No right')."');history.back();</script>";
			exit;
		}

		if(!$_GET['pk']) { //new
			$regdate = date("Y-m-d H:i:s");
			$sq = "insert into ".$lcode_table."(code, regdate) values('".$_POST['code']."', '".$regdate."')";
			mysql_query($sq);
		
			$sq = "select pk from ".$lcode_table." where regdate='".$regdate."' and code='".$_POST['code']."' ";
			$_GET['pk'] =  mysql_result(mysql_query($sq),0);
		}
		else if($_POST['company_name'] == 'delete') {
			$sq = "delete from ".$lcode_table." where pk = ".$_GET['pk'];
			print $sq;
			if(mysql_query($sq)) {
				$sq = "delete from ".$cart_table." where lcode = '".$_POST['code']."'";
				print $sq;
				if(mysql_query($sq)) {
					print "LCODE:".$_POST['code']." and PK:".$_GET['pk']." DELETED";
					$href = "./list_lcode.php?mode=list&pk=".$_GET['pk']."&page_no=".$_GET['page_no']."&page_max=".$_GET['page_max']."&act=".$_GET['act'];
					echo "<script>location.href = ('".$href."')</script>";
				}
			}
			else {
				print $sq."FAIL<br>";
			}
			exit;
		}
		$delivery_charge = $_POST['delivery_charge'] ? $_POST['delivery_charge'] : 0;
		$rebate = $_POST['rebate'] ? $_POST['rebate'] : 0 ;
		$tax_amount = $_POST['tax_amount'] ? $_POST['tax_amount'] : 0;
		$price_total = $_POST['price_total'] ? $_POST['price_total'] : 0;
		$total_amount = $_POST['total_amount'] ? $_POST['total_amount'] :0;

		$ship_address = addslashes($_POST['ship_address']);
		$comments = addslashes($_POST['comments']);
		
		$sq = "update ".$lcode_table." set code='".$_POST['code']."', ccode='".$_POST['ccode']."', fenlei=".$_POST['fenlei'].", price_total=".$price_total.", tax_amount=".$tax_amount.",  delivery_charge=".$delivery_charge.", rebate=".$rebate.", total_amount=".$total_amount.", order_date='".$_POST['order_date']."', exp_return_date='".$_POST['exp_return_date']."', exp_out_date='".$_POST['exp_out_date']."', out_date='".$_POST['out_date']."', ship_date='".$_POST['ship_date']."', wrap_method='".$_POST['wrap_method']."', pay_delivery='".$_POST['pay_delivery']."', ship_method='".$_POST['ship_method']."', ship_address='".$ship_address."', contact_person='".$_POST['contact_person']."', contact_tel='".$_POST['contact_tel']."', contact_fax='".$_POST['contact_fax']."', person_id='".$_POST['person_id']."', comment='".$comment."', file_key='".$_POST['file_key']."'  where pk =".$_GET['pk'];
		
		print $sq;
		if(mysql_query($sq)) {
			print "Lcode table update OK<br>";
		}
		else {
			print "<font color=#FF0000>FAIL</font>";
			break;
		}
// Cart Update
		for($i=0; $i<100; $i++) {
			$price[$i] = $_POST['price'][$i] ? $_POST['price'][$i] : 0;
			$quantity[$i] = $_POST['quantity'][$i] ? $_POST['quantity'][$i] : 0;
			$taxrate[$i] = $_POST['taxrate'][$i] ? $_POST['taxrate'][$i] : 0;
			$unit_rebate[$i] = $_POST['unit_rebate'][$i] ? $_POST['unit_rebate'][$i] : 0;
			$unit_price[$i] = $_POST['unit_price'][$i] ? $_POST['unit_price'][$i] : 0;

			$cart_comment[$i] = addslashes(trim($_POST['cart_comment'][$i]));

			if($_POST['cart_pk'][$i]) {  // Modify or Delete
				if(trim($_POST['item_code'][$i]) == trim(msg('delete'))) {
					$sq = "delete from $cart_table where pk=".$cart_pk[$i];
				}
				else {
					$sq = "update ".$cart_table." set item_code='".$_POST['item_code'][$i]."', item_name='".$_POST['item_name'][$i]."', family='".$_POST['family'][$i]."', price=".$price[$i].", quantity=".$quantity[$i].", taxrate=".$taxrate[$i].", unit_rebate=".$unit_rebate[$i].", unit_price=".$unit_price[$i].", comment='".$cart_comment[$i]."' where pk=".$_POST['cart_pk'][$i];
				}
			}
			else {  // Insert
				if($_POST['item_code'][$i]) {
					$regdate = date("Y-m-d H:i:s");
					$sq = "insert into ".$cart_table."(regdate, lcode, item_code, family, item_name, quantity, price, taxrate, unit_rebate, unit_price, comment) values('".$regdate."', '".$_POST['code']."', '".$_POST['item_code'][$i]."', '".$_POST['family'][$i]."', '".$_POST['item_name'][$i]."', ".$quantity[$i].", ".$price[$i].", ".$taxrate[$i].", ".$unit_rebate[$i].", ".$unit_price[$i].", '".$cart_comment[$i]."')";
				}
				else {
					break;
				}
			}
			print $sq;

			if(mysql_query($sq)) {
				print "Cart table ".$i." update OK<br>";
			}
			else {
				print "<font color=#FF0000>FAIL</font>";
				break;
			}
		}

		if($_POST['fenlei']==20)  { // Xfer from sample to sales.
			$sq = "update ".$ware_table." set l_date ='".$_POST['exp_return_date']."' , fenlei=20 where lcode='".$_POST['code']."'";
			print $sq;
			if(mysql_query($sq)) {
				print "<font color=#0000FF>OK</font>";
			}
			else {
				print "<font color=#FF0000>FAIL</font>";
				break;
			}
		}
	
		$sq = "select process from ".$lcode_table." where pk=".$_GET['pk']; // Update Process
		$process = mysql_result(mysql_query($sq),0);
		$process &= 255;
		$process |= 0x01;
		if($hidden_s) {
			$process |= 0x20;
		}
		$sq = "update ".$lcode_table." set process =".$process." where pk = ".$_GET['pk'];
		print $sq;
		if(mysql_query($sq)) {
			print "<font color=#0000FF>OK</font>";
		}
		else {
			print "<font color=#FF0000>FAIL</font>";
			break;
		}

		$href = "./view_lcode_info.php?mode=view&pk=".$_GET['pk'];
		echo "<script>location.href = ('".$href."')</script>";

		break;

	case 'add':
		if(!query_auth($_SESSION['logID'],'logi_info', 'w')) {
			echo "<script>alert('".msg('You dont have right')."');self.close();</script>";
			exit;
		}
		$code = "L".date("ymdHi",mktime()).rand(0,9).rand(0,9).rand(0,9);
		$regdate = date("Y-m-d H:i:s");

		$insQuery = "insert into $lcode_table(code, regdate, person_id) values('$code', '$regdate', '$logID')";
		print $insQuery;
		mysql_query($insQuery);

		$sq = "select pk from $lcode_table where regdate='$regdate'";
		$pk =  mysql_result(mysql_query($sq),0);
		
		$href = "./lcode_info.php?mode=view&pk=$pk";
		echo "<script>location.href=('".$href."')</script>";

		break;

	case 'view_doc':
		if($_GET['url']) {
			echo "<form name = 'view_doc_form'  method='post' action = '../logistic/logistic.php?mode=view_doc'  ENCTYPE='multipart/form-data'>
					<input type='hidden' name='url' value='".$_GET['url']."'>
					<input type='hidden' name='doc' value='".$_GET['doc']."'>
					<input type='hidden' name='pk' value='".$_GET['pk']."'>
					</form>";
			echo "<script>view_doc_form.submit()</script>"; 
			exit;
		}
//		print_arr();
		if(!$_POST['url']) {
			echo "<script>alert('".msg('No Url')."'); self.close();</script>";
			exit;
		}
		$sq = "select person_id from $lcode_table where pk=".$_POST['pk'];
		$ID = mysql_result(mysql_query($sq),0);
		if( ($_SESSION['logID'] != $ID) and !query_auth($_SESSION['logID'], trim($_POST['doc']), "r") ) {
			echo "<script>alert('".msg('No Right')."'); self.close();</script>";
			exit;
		} 

		$dest = $files_dir.$_POST['url'];
		$ext = strtolower(array_pop(explode(".", $dest)));
		$fp = fopen($dest,"r");
		$html =  fread($fp, filesize($dest));
		fclose($fp);
//		print $dest;
		if( ($ext == "htm") or ($ext=="html") or ($ext=="mht") or ($ext=="mhtml") ) {
			$html = str_replace("\n"," ", $html);
			$html = str_replace("\r"," ", $html);
			$html = str_replace("\t"," ", $html);
			for($i=0; $i<10; $i++) {
				$html = str_replace("  "," ", $html);
			}
			$html = str_replace("> ",">", $html);
			$html = str_replace(" >",">", $html);
			Header("Content-type:text/html");
		}
		else if( ($ext == 'png') or ($ext == 'jpg') or ($ext == 'bmp') or ($ext == 'gif') )  {
			$html ="<img src='data:image/".$ext.";base64,".base64_encode($html)."'>";
		}
		else if( ($ext == "xls") or ($ext=="xlsx"))  {
			Header("Content-type:application/vnd.ms-excel");
		}
		else if( ($ext == "doc") or ($ext=="docx"))  {
			Header("Content-type:application/vnd.ms-word");
		}
		else if( ($ext == "ppt") or ($ext=="pptx"))  {
			Header("Content-type:application/vnd.ms-powerpoint");
		}
		else if( $ext == "pdf")  {
			Header("Content-type:application/pdf");
		}
		else {
			$fname =date("YmdHis").".".$ext;
			header('Content-Type:application/force-download');
			header("content-Disposition:filename={$fname}");
		}


		if($_POST['doc'] == "contract") {
//			$tp_table = strpos($html, "<table")+strlen("<table");
//			$html = substr($html, 0, $tp_table)." class=\"my_window_open\" id=\"p_area\" ".substr($html, $tp_table);
			
			$html = str_replace("<body>", "<body><div  class=\"my_window_open\" id=\"p_area\" style=\"position:absolute;top:0; left:20; width:720; height:1000px;\">", $html);
			$html =str_replace("</body>","</div></body>", $html);
			
			$html = '<link href="/css/main.css" rel="stylesheet"><script src="/js/wysiwyg/wysihtml5-0.3.0.js"></script><script src="/js/jquery.min.js"></script><script src="/js/jquery.popupoverlay.js"></script><script src="/js/excanvas.js"></script><script type="text/javascript" src="/js/html2canvas.js"></script>'.$html;

			$html .= '<script>function download(){html2canvas(document.getElementById("p_area"), {onrendered: function(canvas) {document.getElementById("img_val").value = canvas.toDataURL("image/jpg",0); document.form1.action = "../sales/sales.php?mode=save_png";document.form1.target = "view_doc";	document.form1.submit();}});}</script>';

/*
			$html .= '<script>
			function download()
			{
				document.location.href=("../sales/makeimg_po.php");				

			}
			</script>	';
*/	
			$html .='<div id="my_window" class="well" style="max-width:60em;"><form class="form-horizontal" name="form1" method="post"   ENCTYPE="multipart/form-data"><input type="hidden" name="url" value="'.$_POST['url'].'"><input type="hidden" name="img_val" id="img_val" value="" />';

			if(query_auth($_SESSION['logID'], 'stamp', 'w'))  {
				$html .= '<script>function place_stamp(){	form1.action = "../sales/sales.php?mode=place_stamp";form1.submit();	}</script>';
				$html .= '<input type="checkbox" name="stamp_tag">&nbsp;'.msg('Place Stamp').'&nbsp;<input type="password" name="passwd"  class="input-medium" >&nbsp;'.mk_btn(msg('confirm'), 'place_stamp()', 3).' <br>	';
			}

			$html .= '<br>&nbsp;'.mk_btn(msg('download contract'),"download()",2).'&nbsp;&nbsp;&nbsp;<button class="my_window_close">'.msg('cancel').'</button>&nbsp;
				</form>	</div><script>$(document).ready(function() {$("#my_window").popup();});</script>';



		}
		print $html;		
		break;




////////////////////////////////////             CART              //////////////////////////////////////////////
	case 'list_cart':
		$price_product_inc_tax	 = 0;
		$selQuery =  "select * from $cart_table where lcode='$lcode'";
		$arr_cart = array();
		$arr_cart = Query2Array($selQuery);
		$sizeof_cart = sizeof($arr_cart);
		if($add=="yes")
			$mouse_action_style = "onMouseOver=this.style.backgroundColor='#CCEEFF' onMouseOut=this.style.backgroundColor=''";
		for($i=0; $i<$sizeof_cart; $i++)
		{
			$selQuery = "select model from $item_table where code = '".$arr_cart[$i][item_code]."' and family ='".$arr_cart[$i][family]."'";
			$arr_cart[$i][model] = mysql_result(mysql_query($selQuery),0);
			
			$unit_price_total += ($arr_cart[$i][unit_price]*$arr_cart[$i][quantity]);
			$tax_total += ($arr_cart[$i][unit_price]+$arr_cart[$i][unit_rebate])*$arr_cart[$i][taxrate]/100*$arr_cart[$i][quantity];
			$rebate_total +=  $arr_cart[$i][unit_rebate]*$arr_cart[$i][quantity];
			$price_total += ($arr_cart[$i][price]*$arr_cart[$i][quantity]);

			if($add=="yes")
				$arr_cart[$i][pop] = "window.open('./cart_info.php?mode=view_cart&pk=".$arr_cart[$i][pk]."' ,'cart_info','height=200px,width=720px')";
			else if($act=='insert')
			{
				$arr_cart[$i][item_code] = "<a href ='./logistic.php?mode=insert&act=item&pk=".$arr_cart[$i][pk]."'>".$arr_cart[$i][item_code]."</a>";
			}
		}
		$unit_price_total = round($unit_price_total,2);
		$tax_total = round($tax_total,2);
		$rebate_total = round($rebate_total,2);

//		print $unit_price_total.",br>" ;
//		print $tax_total.",br>" ;
//		print $rebate_total.",br>";
	
		break;

	case 'view_cart':
		$sq =  "select * from $cart_table where pk=$pk";
		$arr_cart = mysql_fetch_array(mysql_query($sq));
		$sq = "select * from $item_table where code = '".$arr_cart[item_code]."' and family='".$arr_cart[family]."'";
		$rs = mysql_fetch_array(mysql_query($sq));
		$arr_cart[model] = $rs[model];
		$arr_cart[name_alias] = $rs[name_alias];
		$name_alias = explode(",", $arr_cart[name_alias]);
		
		$sq = "select person_id from $lcode_table where code='".$arr_cart[lcode]."'"; 
		$arr_cart[person_id] =   mysql_result(mysql_query($sq),0);

		break;

	case 'modify_cart':
		if($item_code=='delete')
		{
			$delQuery = "delete from $cart_table  where pk=$pk";
			print $delQuery;
			$result = mysql_query ($delQuery);	
			if($result)
			{
				echo "DELETED";
				echo "<script>opener.location.reload()</script>";	
				echo "<script>self.close()</script>";	
			}
			exit;
		}
		if(strpos($price,','))
			$price = str_replace(',','',$price);
		if(strpos($quantity,','))
			$quantity = str_replace(',','',$quantity);
		if(!$price)
			$price = 0;
		if(!$quantity)
			$quantity = 0;
		if(!$unit_rebate)
			$unit_rebate = 0;

		$tax = ($price*$taxrate/100) / (1+$taxrate/100);
		$unit_price = $price - $tax - $unit_rebate;
		
		$comment = addslashes($comment);
	
		$sq = "update $cart_table set item_code='$item_code', item_name='$item_name', family='$family', price=$price, quantity=$quantity, taxrate=$taxrate, unit_rebate=$unit_rebate, unit_price=$unit_price, comment='$comment' where pk=$pk";
		print $sq;
		
		$result = mysql_query ($sq);	
		if($result)
		{
			print "OK";	
			echo "<script>opener.frames['list_cart'].height=100;</script>";			
			echo "<script>opener.frames['list_cart'].location.reload()</script>";	
			echo "<script>self.close()</script>";		
		}
		else
			print $updQuery." :FAIL.<br>";

		break;

	case 'add_cart':
		$regdate = date("Y-m-d H:i:s");
		$insQuery = "insert into $cart_table(lcode,regdate) values('$lcode','$regdate')";
		print $insQuery;
		mysql_query($insQuery);

		$sq = "select pk from $cart_table where regdate='$regdate'";
		$pk =  mysql_result(mysql_query($sq),0);
		
		$href = "./cart_info.php?mode=view_cart&pk=$pk";
		echo "<script>location.href=('".$href."')</script>";
		break;

////////////////////////////PL /////////////////////////////////////////////////////
	case 'view_pl': // packing list
/*
		if($act == "standard") {
			$arr_item_code = array("14-101-1000", "98-101-1007", "40-020-1020", "98-101-1001","98-101-1003","98-101-1011","98-101-1004","98-101-1009","98-101-1005","98-101-1010","98-101-1002",);
			$arr_family = array("pcode", "mcode", "mcode", "mcode", "mcode", "mcode", "mcode", "mcode", "mcode", "mcode", "mcode", "mcode", "mcode", );
			$sizeof_accessory = sizeof($arr_item_code);
			for($i=0; $i<10; $i++) {
				$arr_packingbom[$i][item_code] = $arr_item_code[$i];
				$arr_packingbom[$i][family] = $arr_family[$i];
				$sqa = "select * from $item_table where code='".$arr_packingbom[$i][item_code]."' and family = '".$arr_packingbom[$i][family]."' ";
				$rs = mysql_fetch_array(mysql_query($sqa));
				$arr_packingbom[$i][model] = $rs[model];
				$arr_packingbom[$i][name] = $rs[name];
			}
			print_r($rs);

		}
*/
		$sq = "select * from ".$lcode_table." where pk = ".$_GET['pk'];
		$arr_lcode = mysql_fetch_array(mysql_query($sq));
		$arr_lcode[company_name] = mysql_result(mysql_query("select company_name from ".$ccode_table." where code = '".$arr_lcode[ccode]."'"),0);

		$sq =  "select * from $cart_table where lcode='".$arr_lcode[code]."'"; 
		$arr_cart = Query2Array($sq);
		print_arr($arr_cart);

		$sq = "select * from ".$ware_table." where lcode='".$arr_lcode[code]."'";
		$arr_ware = Query2Array($sq);
		$sizeof_ware = sizeof($arr_ware);

		for($i=0; $i<$sizeof_ware; $i++) 	{
			$sq = "select model, name from $item_table where code='".$arr_ware[$i][item_code]."' and family='".$arr_ware[$i][family]."' ";
			$rs = mysql_fetch_array(mysql_query($sq));
			$arr_ware[$i][model] = $rs[model];
			$arr_ware[$i][name] =  $rs[name];
			$sqa = "select * from $serial_table where wcode = '".$arr_ware[$i][wcode]."'";
			$arr_serial = Query2Array($sqa);
			$sizeof_serial = sizeof($arr_serial);
			for($j=0; $j<sizeof($arr_serial);$j++)	{
				$serial_numbers[$i] .= $arr_serial[$j][serial_number];
				if(strlen($arr_serial[$j][serial_number])>30){
					$serial_numbers[$i] .= "\n";
				}
				else 	{
					if($j%5==4) {
						$serial_numbers[$i] .= "\n";
					}
					else {
						$serial_numbers[$i] .= "  ";
					}
				}
			}
//			$sqa = "select * from ".$packingbom_table."  where item_code='".$arr_ware[$i][item_code]."' and family='".$arr_ware[$i][family]."' and pack_code  ";


		}

		break;
		
//		$sq =  "select * from ".$packingbom_table." where plcode = '".$arr_lcode[code]."'";
//		print $sq;
		$arr_packingbom = Query2Array($sq);
		$sizeof_accessary = sizeof($arr_packingbom);
		
		if(!$sizeof_accessary) {
			$sizeof_accessary = 10;
			$sq =  "select * from ".$packingbom_table." where plcode = '".$arr_lcode[packing_code]."'";
			
		}
		
		for($i=0; $i<$sizeof_accessary; $i++)  {
			$sqa = "select model, name from $item_table where code='".$arr_packingbom[$i][item_code]."' and family='".$arr_packingbom[$i][family]."' ";
			$rs = mysql_fetch_array(mysql_query($sqa));
			$arr_packingbom[$i][model] = $rs[model];
			$arr_packingbom[$i][name] = $rs[name];
		}



		break;

case 'write_pl':
	print_arr();
	$regdate = date("Y-m-d H:i:s");
	if(substr($_POST['code'],0,1) == "L") {
		$sq = "delete from $packingbom_table where pack_code='".$_POST['code']."'";
		mysql_query($sq);
		for($i=$_POST['sizeof_ware']; $i<100; $i++) {
			if($_POST['model'][$i]) {
				$comment[$i] = addslashes(trim($_POST['comment']));
				$sq = "insert into ".$packingbom_table." (regdate, pack_code, item_code, family, quantity, l_date, comment) values( '".$regdate."',	'".$code."', 	'".$item_code[$i]."', '".$family[$i]."', ".$quantity[$i].", '".$l_date[$i]."', '".$comment[$i]."') ";
				if(mysql_query($sq)) {
					print "OK<br>";
				}
				else {
					print "FAIL<br>";
				}

			}
		}
		$href = "./makehtml_packinglist.php?mode=view_pl&pk=".$_POST['pk'];
		echo "<script>location.href=('".$href."')</script>";	
	}



	break;
/*
insert into packing_bom(pack_code, item_code, family, quantity) values("PK15432122345323", "98-101-1001", "mcode", 1);
insert into packing_bom(pack_code, item_code, family, quantity) values("PK15432122345323", "98-101-1002", "mcode", 1);
insert into packing_bom(pack_code, item_code, family, quantity) values("PK15432122345323", "98-101-1001", "mcode", 2);
insert into packing_bom(pack_code, item_code, family, quantity) values("PK15432122345323", "98-101-1020", "mcode", 1);
insert into packing_bom(pack_code, item_code, family, quantity) values("PK15432122345323", "40-020-1020", "mcode", 1);
*/

//////////////////////////////
	case 'list_sample':

//		print_arr();
		$arr_sample = array();
		$arr_ware = array();
		$arr_ccode = array();

		$search = trim($_POST['search']) ? trim($_POST['search']) :trim($_GET['search']);
		if($search) {
			$sq = " lcode like '%".$search."%' or item_code like '%".$search."%' or serial_number like '%".$search."%'or person_id like '%".$search."%' or comment like '%".$search."%'";
		}

		if($sq) {
			$sq = " where ".$sq;
		}
		$sq = "select * from ".$sample_table ." ".$sq;
//		print $sq;	
		
		$sq .= " order by pk desc";
		if(!$page_max)
			$page_max = 22;
		$total_record = mysql_num_rows(mysql_query($sq));
		if(!$page_no) 
			$page_no = 1;
		$total_page = ceil($total_record / $page_max); // each page has 30 lines
		$offset = ($page_no - 1)* $page_max;
		$sq .=  "  limit $offset, $page_max";

		$arr_sample = Query2Array($sq);
//		print_arr($arr_sample);
		$sizeof_sample = sizeof($arr_sample);
		for($i=0; $i<$sizeof_sample;$i++)
		{	
			$sqa = "select * from ".$ware_table." where lcode='".$arr_sample[$i][lcode]."'";
			$arr_ware = mysql_fetch_array(mysql_query($sqa));
			$arr_sample[$i][company_name] = mysql_result(mysql_query("select company_name from ".$ccode_table." where code='".$arr_ware[ccode]."'"),0);
			$arr_sample[$i][fenlei] = logistic_info('fenlei', $arr_ware[fenlei], 'text');


			$arr_sample[$i][model_name] = mysql_result(mysql_query("select model from ".$item_table." where code='".$arr_sample[$i][item_code]."' and family='".$arr_sample[$i][family]."'"),0);
			$arr_sample[$i][out_date] = $arr_ware[l_date];
			$arr_sample[$i][exp_return_date] = mysql_result(mysql_query("select exp_return_date from ".$lcode_table." where code='".$arr_sample[$i][lcode]."'"),0);
			$arr_sample[$i][apply_id] = $arr_ware[apply_id];
			
			$arr_sample[$i][return_date] = check_date($arr_sample[$i][return_date]);
			$sqa = "select ".$files_table.".url from ".$files_table." inner join ".$lcode_table." where ".$files_table.".file_key = ".$lcode_table.".file_key and ".$lcode_table.".code='".$arr_sample[$i][lcode]."' and (".$files_table.".family='logistic'  or ".$files_table.".family='sales') and ".$files_table.".file_Name='wareout' order by ".$files_table.".pk desc ";
	//		print $sqa;
			$url = mysql_result(mysql_query($sqa),0);
	
			$arr_sample[$i][lcode] = "<span type='button' onclick=\"window.open('./logistic.php?mode=view_doc&url=".$url."&doc=ware_out','log','height=600px, width=700px, menubar=no, toolbar=no, location=no')\" style=\"cursor:pointer;\">".$arr_sample[$i][lcode]."</span>";


//			$popup1[$i] =  "window.open('".$url."', 'view_doc','height=800px, width=1024px, menubar=no, toolbar=no, location=no, scrollbars=yes, resize=yes')";
//			$popup[$i][0] = "window.open('/libs/popupCal.php?s=".$arr_sample[$i][exp_return_date]."&id=form1.return_date','set_date','width=220px,height=180px')";
			$popup2[$i] = "window.open('./proc.php?lcode=".$arr_sample[$i][lcode]."&tab=10&sample_pk=".$arr_sample[$i][pk]."&s=".$arr_sample[$i][return_date]."','set_date','width=320px,height=340px')";
			$arr_sample[$i][serial_number] = "<span type=\"button\" Onclick=\"window.open('../warehouse/list_serial.php?mode=list_serial&act=serial&search=".$arr_sample[$i][serial_number]."', 'serial' , 'height=610px, width=900px, menubar=no, toolbar=no, location=no')\">".$arr_sample[$i][serial_number]."</span>";

	
			if($arr_sample[$i][status] == 1) {
				$arr_sample[$i][status] = "<span type=\"button\" onclick=\"window.open('./check_sample.php?mode=view_sample_status&pk=".$arr_sample[$i][pk]."','log','height=460px, width=700px, menubar=no, toolbar=no, location=no')\" style=\"cursor:pointer;color:#808080;\" class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe0fe;\" title=\"".msg('completed')."\" ></span>";
			}
			else if($arr_sample[$i][status]==2) {
				$arr_sample[$i][status] = "<span type=\"button\" onclick=\"window.open('./check_sample.php?mode=view_sample_status&pk=".$arr_sample[$i][pk]."','log','height=460px, width=700px, menubar=no, toolbar=no, location=no')\" style=\"cursor:pointer;color:#808080;\" class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe037;\" title=\"".msg('count in sales-text')."\" ></span>";
			}
			else if($arr_sample[$i][status]==3) {
				$arr_sample[$i][status] = "<span type=\"button\" onclick=\"window.open('./check_sample.php?mode=view_sample_status&pk=".$arr_sample[$i][pk]."','log','height=460px, width=700px, menubar=no, toolbar=no, location=no')\" style=\"cursor:pointer;color:#808080;\" class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe0a8;\" title=\"".msg('loss')."\" ></span>";
			}
			else {
				$arr_sample[$i][status] = "<span type=\"button\" onclick=\"window.open('./check_sample.php?mode=view_sample_status&pk=".$arr_sample[$i][pk]."','log','height=460px, width=700px, menubar=no, toolbar=no, location=no')\" style=\"cursor:pointer;color:#808080;\" class=\"fs1\" aria-hidden=\"true\" data-icon=\"&#xe139;\" title=\"".msg('valid')."\" ></span>";
			}

		}
		
		break;

	case 'view_sample_status':
		$sq = "select  ".$sample_table.".lcode, ".$sample_table.".item_code, ".$sample_table.".family, ".$sample_table.".serial_number, ".$sample_table.".return_date,  ".$sample_table.".person_id,  ".$sample_table.".comment, ".$lcode_table.".code, ".$lcode_table.".ccode, ".$lcode_table.".out_date, ".$lcode_table.".person_id as apply_id, ".$lcode_table.".exp_return_date, ".$lcode_table.".pk as lcode_pk from ".$sample_table." inner join ".$lcode_table." where ".$sample_table.".lcode = ".$lcode_table.".code and ".$sample_table.".pk=".$_GET['pk'];
//		print $sq; 
		$arr_sample = mysql_fetch_array(mysql_query($sq));
	//	print_arr($arr_sample);

		$arr_sample[company_name] = mysql_result(mysql_query("select company_name from ".$ccode_table." where code='".$arr_sample[ccode]."'"),0);
		$arr_sample[product_name] = mysql_result(mysql_query("select name from ".$item_table." where code='".$arr_sample[item_code]."' and family='".$arr_sample[family]."' "),0);

		break;
/*
	case 'ins_sample':

		$arr_sample = array();
		$arr_serial = array();
		$sq = "select * from $ware_table where fenlei=6 or fenlei=7 order by pk asc";

		$arr_sample = Query2Array($sq);

		$sizeof_sample = sizeof($arr_sample);

		for($i=0; $i<$sizeof_sample;$i++)
		{
			$regdate = date("Y-m-d H:i:s");
			$lcode = $arr_sample[$i][lcode];
			$fenlei = $arr_sample[$i][fenlei];
			$item_code = $arr_sample[$i][item_code];
			$family = $arr_sample[$i][family];

			$person_id = $arr_sample[$i][apply_id];
			$comment = $arr_sample[$i][comment];
			
			$sq = "select * from $serial_table where wcode= '".$arr_sample[$i][wcode]."'";
			$arr_serial = Query2Array($sq);
			for($j=0; $j<sizeof($arr_serial); $j++)
			{
				$serial_number = $arr_serial[$j][serial_number];
				$status = $arr_serial[$j][status];
				$sq = "insert into $sample_table(regdate, lcode,	item_code, family, serial_number, return_date,	person_id,	status, 	comment )     values('$regdate', '$lcode',	'$item_code', '$family', '$serial_number', '$return_date',	'$person_id',	$status, 	'$comment' )";
				print $sq;
//				if(mysql_query($sq))
					print "OK<br>";
//				else
//					print "FAIL<BR>";
			}
	
		}
		

		break;
*/
/*


	case 'upd_sample':
		$arr_sample = array();
		$arr_serial = array();
		$sq = "select * from sample";
		$arr_sample = Query2Array($sq);
		for($i=0; $i<sizeof($arr_sample); $i++)
		{
			$arr_sample[$i][ccode] = mysql_result(mysql_query("select ccode from $lcode_table where code='".$arr_sample[$i][lcode]."'"),0);
			if(!$arr_sample[$i][ccode])
				continue;
		if($arr_sample[$i][return_date] !='0000-00-00')
				continue;
			$arr_sample[$i][out_date] = mysql_result(mysql_query("select out_date from $lcode_table where code='".$arr_sample[$i][lcode]."'"),0);

//			print $arr_sample[$i][pk];
//			print "/";
//			print $arr_sample[$i][return_date];
//			print "/";
//			print $arr_sample[$i][ccode];
//			print "/";

			$sq = "select * from $serial_table where serial_number= '".$arr_sample[$i][serial_number]."' and family='".$arr_sample[$i][family]."' and item_code='".$arr_sample[$i][item_code]."' ";
			$arr_serial = Query2Array($sq);
			$sizeof_serial = sizeof($arr_serial);
			for($j=0; $j<$sizeof_serial; $j++)
			{	
//				$arr_serial[$j][model] = mysql_result(mysql_query("select model from $item_table where code='".$arr_serial[$j][item_code]."' and family='".$arr_serial[$j][family]."' "),0);
				$sq = "select * from $ware_table where wcode='".$arr_serial[$j][wcode]."' and ccode='".$arr_sample[$i][ccode]."' and INorOUT =1";
//				print $sq;
				$rs = mysql_fetch_array(mysql_query($sq));

				if(($rs[ccode] == $arr_sample[$i][ccode])&&($arr_serial[$j][serial_number] == $arr_sample[$i][serial_number])&&($rs[l_date]>$arr_sample[$i][out_date]) )
				{
//					print $j;
//					print "/";
//					print $arr_serial[$j][serial_number] ;
//					print "/";
//					print  $rs[INorOUT];
//					print "/";
//					print $rs[l_date];
					$sq = "update $sample_table set return_date='".$rs[l_date]."'  , status =1 where pk=".$arr_sample[$i][pk];
					print $sq;
					
					mysql_query($sq);
					print $arr_sample[$i][out_date];
				}
			}
			print"<br>";
		}
		break;

*/
//////////////////////////////
	case 'update_process':

//		print_arr($_POST);
		
		$sq = "select process from $lcode_table where pk=".$_POST['pk'];
		$process = mysql_result(mysql_query($sq),0);

		//process = xxxx 0000: 0x00 NO
		//process = xxxx 0001: 0x01 ware_out docu
		//process = xxxx 0010: 0x02
		//process = xxxx 0100: 0x04
		//process = xxxx 1000: 0x08 ware out complete
		//process = 1000 xxxx: 0x80 completed order 128
		//process = 01x0 xxxx: 0x40 canceled order 64
		//process = 0x10 xxxx: 0x20 hidden order 32
		
		if($_POST['tab']=='out_date') {
			if(!$_POST['out_date']) {
				$process &= ~0x04; // 1111 1011
			}
			else {
				$process |= 0x04;
			}
			$sq = "update ".$lcode_table." set out_date='".$_POST['out_date']."', process = ".$process	." where pk = ".$_POST['pk'];
			$rs = mysql_query($sq);
		}
		else if($_POST['tab'] == 'ship_date') {
			if(!$_POST['out_date']) {
				$process &= ~0x08;//1111 0111
			}
			else {
				$process |= 0x08;
			}
			$sq = "update ".$lcode_table." set ship_date = '".$_POST['out_date']."', process = ".$process."	where pk = ".$_POST['pk'];
			$rs = mysql_query($sq);
		}
		else if($_POST['tab']=='process') {
			$sq = "select passwd from ".$user_table." where ID='".$_SESSION['logID']."'";
			$log_passwd = mysql_result(mysql_query($sq),0);
			if($log_passwd != $_POST['passwd']) {
				echo msg_misc()."<br><br><br>";
				echo "<input type='button' value ='CLOSE' onclick='self.close()'>";
				exit;
			}
			$process &= 0x3F;
			if($_POST['process_s']) {
				$process |= $_POST['process_s'];
			}
			$sq = "update ".$lcode_table." set  process = ".$process." where pk = ".$_POST['pk'];
			$rs = mysql_query($sq);
		}
		else if($_POST['tab']=='distribution') {
			$sq = "update ".$lcode_table." set  dist_info = '".addslashes(trim($_POST['dist_info']))."' where pk = ".$_POST['pk'];
			$rs = mysql_query($sq);
		}



		else if($_POST['tab'] == 10) {
			$return_date = trim($_POST['return_date']);
			$sq = "update ".$sample_table." set return_date='".$return_date."' ,status=".$_POST['fenlei'].", person_id='".$_POST['person_id']."' where pk=".$_POST['sample_pk'];

			if($_POST['fenlei'] == $_POST['array_delete_tab'] ) {
				$sq = "delete from  ".$sample_table." where pk=".$_POST['sample_pk'];				
				echo "<script>
					if(!confirm('".msg('Are you sure to delete?')."')) {
					self.close();
					}</script>";
				
			}
			$rs = mysql_query($sq);
		}
		print $sq;
		if($rs) {
			print "OK";	
			echo "<script>opener.location.reload()</script>";
			echo "<script>self.close()</script>";
		}
		else {
			print "FAIL.<br>";
		}

		break;


	case 'insert':
		print "Insert Start";

		if(!$form)
			$form = 'form1';

		if($act=='ware')
		{
			$sq = "select * from $cart_table where pk=$pk";
			$arr_cart = mysql_fetch_array(mysql_query($sq));
			$sq = "select  * from $item_table where code = '".$arr_cart[item_code]."' and family='".$arr_cart[family]."'";
			$arr_item = mysql_fetch_array(mysql_query($sq));
			$sq = "select * from $lcode_table where code='".$arr_cart[lcode]."' ";
			$arr_lcode = mysql_fetch_array(mysql_query($sq));
			$sq = "select company_name from $ccode_table where code='".$arr_lcode[ccode]."'";
			$arr_lcode[company_name] = mysql_result(mysql_query($sq),0);

			echo "<script>opener.".$form.".quantity.value ='".$arr_cart[quantity]."';</script>";
			echo "<script>opener.".$form.".unit_price.value ='".$arr_cart[unit_price]."';</script>";
			echo "<script>opener.".$form.".item_code.value ='".$arr_cart[item_code]."';</script>";
			echo "<script>opener.".$form.".model.value ='".$arr_item[model]."';</script>";
			echo "<script>opener.".$form.".name.value ='".$arr_cart[item_name]."';</script>";
			echo "<script>opener.".$form.".spec.value ='".$arr_item[spec]."';</script>";
			echo "<script>opener.".$form.".family.value ='".$arr_cart[family]."';</script>";

			echo "<script>opener.".$form.".lcode.value ='".$arr_lcode[code]."';</script>";
			echo "<script>opener.".$form.".ccode.value ='".$arr_lcode[ccode]."';</script>";
			echo "<script>opener.".$form.".company_name.value ='".$arr_lcode[company_name]."';</script>";	
			
			if($arr_lcode[INorOUT] == 0) {
				echo "<script>opener.".$form.".IO[1].selected =true;</script>";
			}

			$sq = "select * from $language_table where page='Variable' and var='logistic_info' ";
			$arr_logistic = Query2Array($sq);

			echo '<script>
				var i;
				var selectValue = opener.document.getElementById("fenlei") ;
				for(i=selectValue.length-1; i>-1; i--) 	{
					selectValue.remove(i);
				}
				</script>';
						
			for($i = 0; $i<sizeof($arr_logistic); $i++) {
				$exp = explode("/", $arr_logistic[$i][chi]);
				if($arr_lcode[fenlei] == $exp[0]) {
					echo "<script>selectValue.add(new Option('".$exp[1]."', ".$exp[0].", false, false),1);</script>";
					break;
				}
			}
			$sq = " select * from ".$user_table." where status > 0 ";
			$arr_user = Query2Array($sq);
			for($idx=0, $i=0; $i<sizeof($arr_user); $i++) {
				if($arr_user[$i][ID] == $arr_lcode[person_id]) 	{
					echo "<script>opener.".$form.".apply_id[".$i."].selected = true;</script>";
					break;
				}
			}	
			echo "<script>self.close();</script>";
			exit;


			print_arr($arr_lcode);

			print $sq;
			break;
			$selQuery =  "select * from $lcode_table where pk=$pk";
			$arr_lcode = mysql_fetch_array(mysql_query($selQuery));

			$sq = "select company_name from $ccode_table where code='".$arr_lcode[ccode]."'";
			$arr_lcode[company_name] = mysql_result(mysql_query($sq),0);

			$sq = "select * from $user_table where status>0";
			$arr_user = array();
			$arr_user = Query2Array($sq);

			for($idx=0, $i=0; $i<sizeof($arr_user); $i++)
			{
				if($arr_user[$i][ID] == $arr_lcode[person_id])
				{
					$idx = $i;
					break;
				}
			}	

			for($fidx=0, $i=0; $i<30; $i++)
			{
				if($msg_logistic_list[$i][0] == $arr_lcode[fenlei])
				{
					$fidx = $i;
					break;
				}
			}	
			print  $arr_lcode[fenlei]. sizeof($msg_logistic_list);

			echo "<script>opener.".$form.".lcode.value ='".$arr_lcode[code]."';</script>";
			echo "<script>opener.".$form.".ccode.value ='".$arr_lcode[ccode]."';</script>";
			echo "<script>opener.".$form.".company_name.value ='".$arr_lcode[company_name]."';</script>";				
			echo "<script>opener.".$form.".apply_id[".$idx."].selected =true;</script>";
//			echo "<script>opener.".$form.".fenlei[".$fidx."].selected =true;</script>";
			echo "<script>location.href=('../logistic/list_cart.php?act=insert&lcode=".$arr_lcode[code]."');</script>";

		}
		else if($act=='item')
		{
			$sq = "select * from $cart_table where pk=$pk";
			$arr_cart = mysql_fetch_array(mysql_query($sq));
			$sq = "select  * from $item_table where code = '".$arr_cart[item_code]."' and family='".$arr_cart[family]."'";
			$arr_code = mysql_fetch_array(mysql_query($sq));

			echo "<script>opener.".$form.".quantity.value ='".$arr_cart[quantity]."';</script>";
			echo "<script>opener.".$form.".unit_price.value ='".$arr_cart[unit_price]."';</script>";
			echo "<script>opener.".$form.".item_code.value ='".$arr_code[code]."';</script>";
			echo "<script>opener.".$form.".model.value ='".$arr_code[model]."';</script>";
			echo "<script>opener.".$form.".name.value ='".$arr_code[name]."';</script>";
			echo "<script>opener.".$form.".spec.value ='".$arr_code[spec]."';</script>";
			echo "<script>opener.".$form.".family.value ='".$arr_code[family]."';</script>";
			echo "<script>self.close();</script>";
		}

		else if($act=='doc')
		{
			$sq =  "select * from $lcode_table where pk=$pk";
			$arr_lcode = mysql_fetch_array(mysql_query($sq));
//			print_arr($arr_lcode);

			$sq = "select company_name from $ccode_table where code='".$arr_lcode[ccode]."'";
			$company_name = mysql_result(mysql_query($sq),0);

			$sq = "select url from $files_table where file_key='".$arr_lcode[file_key]."' and family='sales' and file_Name='contract' order by pk desc limit 1";
			$docu = mysql_result(mysql_query($sq),0);


			
			$sq = "select * from $cart_table where lcode='".$arr_lcode[code]."'";
			$arr_cart = Query2Array($sq);
			$size_cart = sizeof($arr_cart);

			echo "<script>opener.".$form.".lcode.value ='".$arr_lcode[code]."'</script>";
			echo "<script>opener.".$form.".docu.value ='".$docu."'</script>";
			echo "<script>opener.".$form.".st_date.value ='".$arr_lcode[ship_date]."'</script>";
			echo "<script>opener.".$form.".ccode.value ='".$arr_lcode[ccode]."'</script>";
			echo "<script>opener.".$form.".company_name.value ='".$company_name."'</script>";
			echo "<script>opener.".$form.".contact_person.value ='".$arr_lcode[contact_person]."'</script>";
			echo "<script>opener.".$form.".price_total.value ='".$arr_lcode[price_total]."'</script>";
			
			for($i=0; $i<$size_cart; $i++)
			{
				echo "<script>opener.".$form.".elements['item_code[".$i."]'].value ='".$arr_cart[$i][item_code]."'</script>";
				echo "<script>opener.".$form.".elements['price[".$i."]'].value ='".$arr_cart[$i][price]."'</script>";
				echo "<script>opener.".$form.".elements['quantity[".$i."]'].value ='".$arr_cart[$i][quantity]."'</script>";
				echo "<script>opener.".$form.".elements['comment[".$i."]'].value ='".$arr_cart[$i][comment]."'</script>";
			
				$sq = "select  * from $item_table where code = '".$arr_cart[$i][item_code]."' and family='".$arr_cart[$i][family]."'";
				$arr_item = mysql_fetch_array(mysql_query($sq));
				echo "<script>opener.".$form.".elements['model[".$i."]'].value ='".$arr_item[model]."'</script>";
				echo "<script>opener.".$form.".elements['name[".$i."]'].value ='".$arr_item[name]."'</script>";

				$sq = "select wcode from $ware_table where lcode='".$arr_lcode[code]."' and item_code = '".$arr_cart[$i][item_code]."' and family='".$arr_cart[$i][family]."'";
				$wcode =  mysql_result(mysql_query($sq),0);		
			
				$sq = "select * from $serial_table where wcode='".$wcode."' and item_code = '".$arr_cart[$i][item_code]."' and family='".$arr_cart[$i][family]."'";
				print $sq;
				$arr_serial = Query2Array($sq);
				for($j=0; $j<sizeof($arr_serial); $j++)
				{
					$snr[$i] .= $arr_serial[$j][serial_number]."/";
				}
				echo "<script>opener.".$form.".elements['snr[".$i."]'].value ='".$snr[$i]."'</script>";

			}
			echo "<script>self.close();</script>";
		}


		break;
		

}
?>
