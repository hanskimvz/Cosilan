<?PHP
#last modify: 2020-10-26
session_start();
include "../libs/functions.php";
logincheck();

$msg = q_language('footfall.php');

function age_group($data, $range) {
	for ($i=0; $i<sizeof($range); $i++) {
		$a = $range[$i];
		$b = ($range[$i+1]-$range[$i]) < 0 ? 100- $range[$i]: ($range[$i+1]-$range[$i]);
//		print $a.":".$b."<br>";
		$rs[$i] = array_sum(array_slice($data,$a,$b));
	}
	return $rs;
}

function getViewByTime($time_ref, $view_by) {
	$arr = array();
	$to_e = explode("~", $time_ref);
	if($view_by == '10min') {
		$arr['g_sq'] = "group by year, month, day, hour, min ";
		$arr['dateformat'] = 'Y-m-d H:i';
		$arr['ts_from'] = strtotime($to_e[1]);
		$arr['ts_to'] = $arr['ts_from'] + 3600*24 -1;
		$arr['interval'] = 600;
	}
	else if($view_by == 'hour') {
		$arr['g_sq'] = "group by year, month, day, hour";
		$arr['dateformat'] = 'Y-m-d H:00';
		$arr['ts_from'] = strtotime($to_e[1]);
		$arr['ts_to'] = $arr['ts_from'] + 3600*24 -1;
		$arr['interval'] = 3600;
	}
	else if($view_by == 'day') {
		$arr['g_sq'] = "group by year, month, day ";
		$arr['dateformat'] = 'Y-m-d';
		$arr['ts_from'] = strtotime($to_e[0]);
		$arr['ts_to'] = strtotime($to_e[1]) + 3600*24 -1;
		$arr['interval'] = 3600*24;
	}
	else if($view_by == 'week') {
		$arr['g_sq'] = "group by year, week ";
		$arr['dateformat'] = 'Y-m-d';
		$arr['ts_from'] = strtotime(date("Y-m-1", strtotime($to_e[0])));
		$arr['ts_to'] = strtotime(date("Y-m-t", strtotime($to_e[1]))) + 3600*24 -1;	
		$arr['interval'] = 3600*24*7;
	}	
	else if($view_by == 'month') {
		$arr['g_sq'] = "group by year, month ";
		$arr['dateformat'] = 'Y-m';
		$arr['ts_from'] = strtotime(date("Y-m-1", strtotime($to_e[0])));
		$arr['ts_to'] = strtotime(date("Y-m-t", strtotime($to_e[1]))) + 3600*24 -1;			
		$arr['interval'] = 3600*24*31;
	}
	$arr['duration'] =  ceil(($arr['ts_to']-$arr['ts_from'])/$arr['interval']);
	
	return $arr;	
}

function Result2Json4Chart($rs, $ts_from, $interval, $duration, $dateformat, $msg) {
	global $thistime;
	$arr_result =  array();
	$arr_rs = array();
	$arr_label = array();
	while($assoc = mysqli_fetch_assoc($rs)) {
		$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
		$arr_result[$datetime][$assoc['counter_label']] = $assoc['sum'];
		if(!in_array($assoc['counter_label'], $arr_label)) {
			array_push($arr_label, $assoc['counter_label']);
		}		
	}
	for($i=0; $i<$duration; $i++) { 
		$datetimest = $ts_from + $interval*$i;
		$datetime = date($dateformat, $datetimest);
		for ($j=0; $j<sizeof($arr_label); $j++) {
			$arr_result[$arr_label[$j]][$i] = $arr_result[$datetime][$arr_label[$j]] ? $arr_result[$datetime][$arr_label[$j]]:	($datetimest > $thistime ? null : 0);	
		}
		$arr_rs['category']['timestamps'][$i] = $datetimest;
		$arr_rs['category']['datetimes'][$i] = $datetime;
	}
	for ($i=0; $i<sizeof($arr_label); $i++) {
		$arr_rs['data'][$i]['name'] = $msg[$arr_label[$i]] ? $msg[$arr_label[$i]] : $arr_label[$i]  ;
		$arr_rs['data'][$i]['data'] = $arr_result[$arr_label[$i]];
	}
	$arr_rs['time'] = round(microtime(true)-$s1,4);
	$arr_rs['title']['chart_title'] = $msg['footfall'];
	// print_arr($arr_rs);
	$json_str = json_encode($arr_rs, JSON_NUMERIC_CHECK);
	unset($arr_result);
	unset($arr_rs);
	unset($arr_label);
	return $json_str;
}
if($_GET['f'] == 'square') {
	$sq = "select code, name from ".$DB_CUSTOM['square'];
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows) ;$i++) {
		$row = mysqli_fetch_row($rs);
		if($i>0) {
			$code_str .= ',';
			$name_str .= ',';
		}
		$code_str .= '"'.$row[0].'"';
		$name_str .= '"'.$row[1].'"';
	}
	$json_str = '{"code":['.$code_str.'],"name":['.$name_str.']}';
}
else if($_GET['f'] == 'store') {
	$sq = "select code, name from ".$DB_CUSTOM['store']." where square_code ='".$_GET['sq_code']."' ";
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$row = mysqli_fetch_row($rs);
		if($i>0) {
			$code_str .=',';
			$name_str .=',';
		}
		$code_str .= '"'.$row[0].'"';
		$name_str .= '"'.$row[1].'"';
	}
	$json_str= '{"code":['.$code_str.'],"name":['.$name_str.']}';
}
else if($_GET['f'] == 'camera') {
	$sq = "select code, name from ".$DB_CUSTOM['camera']." where store_code ='".$_GET['st_code']."' ";
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$row = mysqli_fetch_row($rs);
		if($i>0) {
			$code_str .=',';
			$name_str .=',';
		}
		$code_str .= '"'.$row[0].'"';
		$name_str .= '"'.$row[1].'"';
	}
	$json_str= '{"code":['.$code_str.'],"name":['.$name_str.']}';
}
else if($_GET['f'] == 'all') {
	$sq = "select A.code, A.name, B.code, B.name, C.code, C.name  from ".$DB_CUSTOM['square']." as A inner join ".$DB_CUSTOM['store']." as B inner join ".$DB_CUSTOM['camera']." as C on A.code = B.square_code and C.store_code = B.code where C.enable_countingline='y' order by A.code asc, B.code asc, C.code asc";
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$row = mysqli_fetch_row($rs);
		if($i>0) {
			$code_str .=',';
		}
		$code_str .= '{"sq_code":"'.$row[0].'", "sq_name":"'.$row[1].'", "st_code":"'.$row[2].'","st_name":"'.$row[3].'", "cam_code":"'.$row[4].'", "cam_name":"'.$row[5].'"}';
	}

	$json_str= '['.$code_str.']';
}

else if ($_GET['fr'] == 'message') {
	if($_GET['href'] == 'admin') {
		if($_GET['act'] == 'list') {
			$sq = "select * from ".$DB_COMMON['message']." ";
			$rs = mysqli_query($connect0, $sq);
			for($i=0; $i<($rs->num_rows); $i++) {
				$assoc = mysqli_fetch_assoc($rs);
				if($i>0) {
					$str .= ',';
				}
				$str .= '{
					"pk":"'.$assoc['pk'].'", 
					"title":"'.$assoc['title'].'", 
					"body":"'.addslashes($assoc['body']).'", 
					"from":"'.$assoc['from_p'].'",
					"to":"'.$assoc['to_p'].'",
					"date":"'.$assoc['regdate'].'",
					"category":"'.addslashes($assoc['category']).'"
				}';
			}
			$json_str= '{"list":['.$str.'],"lang":{"number":"New messages"}}';
		}
		else if($_GET['act'] == 'write') {
			if($_POST['pk']) {
				$body = substr($_POST['body'], strlen('<div class="ql-editor" data-gramm="false" contenteditable="true">'), strpos($_POST['body'],'</div>')-strlen('<div class="ql-editor" data-gramm="false" contenteditable="true">'));
				$sq = "update ".$DB_COMMON['message']." set category = '".$_POST['category']."', title = '".addslashes(trim($_POST['title']))."', body = '".addslashes($body)."', from_p = '".$_SESSION['logID']."@".$_SESSION['db_name']."', to_p = '".$_POST['to_p']."' where pk = ".$_POST['pk'];
			}
			else {
				$body = substr($_POST['body'], 0, strpos($_POST['body'],'</div><div class="ql-clipboard"'));
				$body = substr($body,strpos($body,'>')+1,strlen($body));
				$body = trim($body);
				$sq = "insert into ".$DB_COMMON['message']."(regdate, category, title, body, from_p, to_p) values(now(), '".$_POST['category']."', '".addslashes(trim($_POST['title']))."', '".addslashes($body)."', '".$_SESSION['logID']."@".$_SESSION['db_name']."', '".$_POST['to_p']."') ";
			}
			print $sq;
//			$rs = mysqli_query($connect0, $sq);
			$str = '"'.$sq.'"';
			$json_str= '['.$str.']';
		}
		else if($_GET['act'] == 'view') {
			$sq = "select * from ".$DB_COMMON['message']." where pk= ".$_GET['pk'];
			$rs = mysqli_query($connect0, $sq);
			$assoc = mysqli_fetch_assoc($rs);
			
			$json_str .= '{
				"pk":"'.$assoc['pk'].'", 
				"title":"'.$assoc['title'].'", 
				"body":"'.addslashes($assoc['body']).'", 
				"from":"'.$assoc['from_p'].'",
				"to":"'.$assoc['to_p'].'",
				"date":"'.$assoc['regdate'].'",
				"category":"'.addslashes($assoc['category']).'"
			}';
			
		}
	}
	else {
		$sq = "select * from ".$DB_COMMON['message']." where to_p='".$_SESSION['logID']."@".$_SESSION['db_name']."' and flag='n'";
		$rs = mysqli_query($connect0, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			if($assoc['category'] == 'info') {
				$assoc['category'] = '<i class="align-middle fas fa-2x fa-info-circle"></i>';
			}
			if($i>0) {
				$str .= ',';
			}
			$str .= '{
				"title":"'.$assoc['title'].'", 
				"body":"'.addslashes($assoc['body']).'", 
				"from":"'.$assoc['from_p'].'",
				"date":"'.$assoc['regdate'].'",
				"category":"'.addslashes($assoc['category']).'"
			}';
		}
		$json_str= '{"newAlert":['.$str.'],"lang":{"number":"New messages"}}';
	}
}

else if ($_GET['fr'] == 'version') {
	
	$sq = "select * from common.".$DB_COMMON['message']." where category='version' order by regdate desc ";
	$rs = mysqli_query($connect0, $sq);
	$alert_num = $rs->num_rows;
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		if($i>0) {
			$str .= ',';
		}
		$str .= '{
			"title":"'.$assoc['title'].'", 
			"body":"'.addslashes($assoc['body']).'", 
			"date":"'.date("Y-m-d", strtotime($assoc['regdate'])).'",
			"category":"'.addslashes($assoc['category']).'"
		}';
	}
	$json_str= '['.$str.']';
}

else if ($_GET['fr'] == 'feedback') {
	if($_GET['act'] == "write") {
		$body = substr($_POST['body'],0,strpos($_POST['body'],'</div><div class="ql-clipboard"'));
		$body = substr($body,strpos($body,'>')+1,strlen($body));
		$body = trim($body);
		$sq = "insert into ".$DB_COMMON['message']."(regdate, category, title, body, from_p, to_p) values(now(), 'feedback', '".addslashes(trim($_POST['title']))."', '".addslashes($body)."', '".$_SESSION['logID']."@".$_SESSION['db_name']."', 'admin') ";
		$rs = mysqli_query($connect0, $sq);

		$str = '"'.$sq.'"';
		$json_str= '['.$str.']';
	}
	else {
		$sq = "select * from ".$DB_COMMON['message']." where category='feedback' order by regdate desc ";
		$rs = mysqli_query($connect0, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			if($i>0) {
				$str .= ',';
			}
			$str .= '{
				"title":"'.$assoc['title'].'", 
				"body":"'.addslashes($assoc['body']).'", 
				"date":"'.$assoc['regdate'].'",
				"category":"'.addslashes($assoc['category']).'",
				"from":"'.$assoc['from_p'].'"
			}';
		}
		$json_str= '['.$str.']';	
	}
	
}
########################################  MAIN PAGE ######################################
else if ($_GET['fr'] == 'dashBoard') {
	$msg=q_language('dashboard.php');
	$s1 = microtime(true);
//	print_arr($_GET);

	if($_GET['page'] == 'footfall') {
		
		$from_ts = strtotime($_GET['time_ref'])-3600*24*83;
		$to_ts =  strtotime($_GET['time_ref']) + 3600*24-1;
		$dateformat = "m-d";
		$arr_result= array();
		$sq  = "select store_code, square_code, counter_label, year, month, day, hour, min, sum(counter_val) as sum, counter_name from ".$DB_CUSTOM['count']." ";
		$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." ";
		$sq .= "and counter_label = 'entrance' "; 

		if($_GET['st'] ) {
			$sq .= "and store_code ='".$_GET['st']."' ";
		}
		if($_GET['sq'] ) {
			$sq .= "and square_code ='".$_GET['sq']."' ";
		}	
		$sq .= "group by year, month, day, counter_label ";	
	
//		print $sq;
//		print Query2Table($connect, $sq);
	
		$rs = mysqli_query($connect, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			$datetimest =  mktime($assoc['hour'], $assoc['min'], 0, $assoc['month'], $assoc['day'], $assoc['year']);
			$datetime =  date($dateformat,$datetimest);
			$arr_result[$datetime]['footfall'] = $assoc['sum'];
		}
		
		for($i=0; $i<84; $i++) {
			$datetimest = $from_ts + 3600*24*$i;
			$datetime = date($dateformat,$datetimest);
			if(!$arr_result[$datetime]['footfall']) {
				$arr_result[$datetime]['footfall'] = 0;
			}
			$visit_total += $arr_result[$datetime]['footfall'];
			if($datetimest > $thistime) {
				$arr_result[$datetime]['footfall']='NaN';
			}
			if($i>0) {
				$l_str_c .= ',';
				$d_str_c .= ',';
			}
			$l_str_c .= $datetimest;
			$d_str_c .= $arr_result[$datetime]['footfall'];
			if($i>=77) {
				if($i>77) {
					$l_str_b .= ',';
					$d_str_b1 .= ',';
					$d_str_b2 .= ',';
				}
				$l_str_b .= '"'.$msg[strtolower(date('D',$datetimest))].' '.$datetime.'"';
				$d_str_b2 .= $arr_result[$datetime]['footfall'];
				$datetime = date($dateformat,$datetimest-3600*24*7);
				$d_str_b1 .= $arr_result[$datetime]['footfall'];
			}
			
			
		}
		$visit_today = $arr_result[date($dateformat,$to_ts)]['footfall'];
		$visit_yesterday = $arr_result[date($dateformat,$to_ts-3600*24)]['footfall'];
		$visit_average = $visit_total / 84;
		
		$per_today = number_format($visit_today *100/ $visit_average,2);
		$per_yesterday = number_format($visit_yesterday *100/ $visit_average,2);
		$per_total = number_format($visit_total*100 / ($visit_average*84), 2);
		
		
		$s_str_b = '{"name":"'.$msg['lastweek'].'", "data":['.$d_str_b1.']},{"name":"'.$msg['thisweek'].'", "data":['.$d_str_b2.']}';
		$s_str_c = '{"name":"'.$msg['last12weeks'].'", "data":['.$d_str_c.']}';
		$c_str = '
			{"name":"'.$msg['today'].'", "value":"'.number_format($visit_today,0).'", "percent":"'.$per_today.'", "badge":"'.$msg['visitors'].'", "color":"#47bac1"},
			{"name":"'.$msg['average12weeks'].'", "value":"'.number_format($visit_average,0).'", "percent":0, "badge":"'.$msg['visitors'].'", "color":"#5b7dff"},
			{"name":"'.$msg['yesterday'].'", "value":"'.number_format($visit_yesterday,0).'", "percent":"'.$per_yesterday.'", "badge":"'.$msg['visitors'].'", "color":"#fcc100"},
			{"name":"'.$msg['total12weeks'].'", "value":"'.number_format($visit_total,0).'", "percent":"'.$per_total.'", "badge":"'.$msg['visitors'].'", "color":"#5fc27e"}
		';
		
		$json_str = '{
			"bar_data":['.$s_str_b.'],
			"bar_label":['.$l_str_b.'],
			"curve_data":['.$s_str_c.'],
			"curve_label":['.$l_str_c.'],
			"card_data":['.$c_str.'],
			"time":'.(round(microtime(true)-$s1,4)).'
		}';
		
	}
	
	else if($_GET['page'] == 'ageGender0') {
		$tag_div = array('age_1st','age_2nd','age_3rd','age_4th','age_5th','male','female');
		$title_div = array($msg['~17'],$msg['18~29'],$msg['30~44'],$msg['45~64'],$msg['65~'], $msg['male'], $msg['female']);
	
		$from_ts = strtotime($_GET['time_ref'])-3600*24*83;
		$to_ts =  strtotime($_GET['time_ref']) + 3600*24-1;	
		$dateformat = "m-d";
		$interval = 3600*24;
		
		
		$sq =  "select square_code, store_code, camera_code, year, month, day, sum(male) as male, sum(female) as female, sum(age_1st) as age_1st, sum(age_2nd) as age_2nd, sum(age_3rd) as age_3rd, sum(age_4th) as age_4th, sum(age_5th) as age_5th, sum(age_6th) as age_6th from ".$DB_CUSTOM['age_gender']." ";
		$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." ";
	
		if($_GET['sq']) {
			$sq .=  "and square_code ='".$_GET['sq']."' ";
		}
		if($_GET['st']) {
			$sq .=  "and store_code ='".$_GET['st']."' ";
		}
		$sq .= "group by year, month, day ";
		$sq .= "order by timestamp desc ";
//		print Query2Table($connect, $sq);
	
		$rs = mysqli_query($connect, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			$datetimest = mktime($assoc['hour'], $assoc['min'], 0, $assoc['month'], $assoc['day'], $assoc['year']);
			$datetime =  date($dateformat, $datetimest);
			for($j=0; $j < count($tag_div) ; $j++) {
				$arr_result[$datetime][$tag_div[$j]] = $assoc[$tag_div[$j]];
				$arr_result['total'][$tag_div[$j]] += $assoc[$tag_div[$j]];
			}
		}
		
		for($i=0; $i<sizeof($tag_div); $i++) {
			$d_str = '';
			for($j=0; $j<84; $j++) { 
				$datetimest = $from_ts + $interval*$j;
				$datetime = date($dateformat, $datetimest);
				if($i==0) {
					if($j>0){
						$l_str .=',';
					}
					$l_str .= $datetimest;
				}
				if(!$arr_result[$datetime][$tag_div[$i]]) {
					$arr_result[$datetime][$tag_div[$i]] = 0;
				}
				if($datetimest > $thistime) {
					$arr_result[$datetime][$tag_div[$i]] = '"NaN"';
				}
				if($j>0) {
					$d_str .= ',';
				}
				$d_str .=  $arr_result[$datetime][$tag_div[$i]];
			}
			if(!$arr_result['total'][$tag_div[$i]]) {
				$arr_result['total'][$tag_div[$i]] = 0;
			}
			if($i>0) {
				$s_str .= ',';
			}
			$s_str .= '{"name":"'.$title_div[$i].'", "data":['.$d_str.'], "total":'.$arr_result['total'][$tag_div[$i]].'}';
		}

		$json_str= '{
			"data":['.$s_str.'],
			"label":['.$l_str.'],
			"title":{
				"gender_bar":"'.$gender_title.'",
				"age_title":["'.$msg['average12weeks'].'", "'.$msg['today'].'"],
				"gender_title":["'.$msg['average12weeks'].'", "'.date("m-d",$to_ts-3600*24*2).'", "'.date("m-d",$to_ts-3600*24).'", "'.date("m-d",$to_ts).'"]
			}
		}';
	}


	
	else if($_GET['page'] == 'ageGender') {
		$s1 = microtime(true);
		
		$age_range = json_decode($configVars['MISC']['AGE_GROUP']);
		
		if(!$age_range) {
			$age_range = [0,18,30,45,65];
		}
		
		$gender_div = array($msg['male'], $msg['female']);
	
		$from_ts = strtotime($_GET['time_ref'])-3600*24*83;
		$to_ts =  strtotime($_GET['time_ref']) + 3600*24-1;	
		$dateformat = "m-d";
		$interval = 3600*24;
		
		$age_query_string = '';
		for($i=0; $i<100; $i++) {
			if($i>0) {
				$age_query_string .= ", ";
			}
			$age_query_string .= "sum(substring_index(substring_index(age, ',', ".($i+1)."),',',-1))";
			if($i<99) {
				$age_query_string .= ",','";
			}
		}
		$age_query_string  = "concat(".$age_query_string.") as age";
		
		$gender_query_string = "sum(substring_index(substring_index(gender, ',', 1),',',-1)), ',', sum(substring_index(substring_index(gender, ',', 2),',',-1))" ;
		$gender_query_string = "concat(".$gender_query_string.") as gender";
		
		$sq =  "select square_code, store_code, camera_code, year, month, day, ".$age_query_string.", ".$gender_query_string." from ".$DB_CUSTOM['age_gender']." ";
		$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." ";
	
		if($_GET['sq']) {
			$sq .=  "and square_code ='".$_GET['sq']."' ";
		}
		if($_GET['st']) {
			$sq .=  "and store_code ='".$_GET['st']."' ";
		}
		$sq .= "group by year, month, day ";
		$sq .= "order by timestamp desc ";
//		print $sq;
//		print Query2Table($connect, $sq);

		$rs = mysqli_query($connect, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			$datetimest = mktime($assoc['hour'], $assoc['min'], 0, $assoc['month'], $assoc['day'], $assoc['year']);
			$datetime =  date($dateformat, $datetimest);
			$arr_result[$datetime]['age'] = age_group(json_decode('['.$assoc['age'].']'), $age_range);
			$arr_result[$datetime]['gender'] = json_decode('['.$assoc['gender'].']');
		}
		for($i=0; $i<sizeof($age_range); $i++) {
			$d_str = '';
			for($j=0; $j<84; $j++) { 
				$datetimest = $from_ts + $interval*$j;
				$datetime = date($dateformat, $datetimest);
				$arr_result['total'][$i] += $arr_result[$datetime]['age'][$i];
				
				if($i==0) {
					if($j>0){
						$l_str .=',';
					}
					$l_str .= $datetimest;
				}
				if(!$arr_result[$datetime]['age'][$i]) {
					$arr_result[$datetime]['age'][$i] = 0;
				}
				if($datetimest > $thistime) {
					$arr_result[$datetime]['age'][$i] = '"NaN"';
				}
				if($j>0) {
					$d_str .= ',';
				}
				$d_str .=  $arr_result[$datetime]['age'][$i];
			}
			if(!$arr_result['total'][$i]) {
				$arr_result['total'][$i] = 0;
			}
			if($i>0) {
				$s_str .= ',';
			}
			$s_str .= '{"name":"'.$msg['agegroup'.$i].'", "data":['.$d_str.'], "total":'.$arr_result['total'][$i].'}';
		}
		for($i=0; $i<2; $i++) {
			$d_str = '';
			for($j=0; $j<84; $j++) { 
				$datetimest = $from_ts + $interval*$j;
				$datetime = date($dateformat, $datetimest);
				$arr_result['total'][$i] += $arr_result[$datetime]['gender'][$i];

				if(!$arr_result[$datetime]['gender'][$i]) {
					$arr_result[$datetime]['gender'][$i] = 0;
				}
				if($datetimest > $thistime) {
					$arr_result[$datetime]['gender'][$i] = '"NaN"';
				}
				if($j>0) {
					$d_str .= ',';
				}
				$d_str .=  $arr_result[$datetime]['gender'][$i];
			}
			if(!$arr_result['total'][$i]) {
				$arr_result['total'][$i] = 0;
			}
			$s_str .= ',';
			$s_str .= '{"name":"'.$gender_div[$i].'", "data":['.$d_str.'], "total":'.$arr_result['total'][$i].'}';
		}
		$json_str= '{
			"data":['.$s_str.'],
			"label":['.$l_str.'],			
			"title":{
				"gender_bar":"'.$gender_title.'",
				"age_title":["'.$msg['average12weeks'].'", "'.$msg['today'].'"],
				"gender_title":["'.$msg['average12weeks'].'", "'.date("m-d",$to_ts-3600*24*2).'", "'.date("m-d",$to_ts-3600*24).'", "'.date("m-d",$to_ts).'"]
			},
			"time":'.(round(microtime(true)-$s1,4)).'
		}';
	}

}
else if( $_GET['fr'] == 'dataGlunt')  {
	$msg=q_language('footfall.php');
	$arr_result = array();
	$arr_rs = array();
	$arr_label = array();

	$s1 = microtime(true);
	$arrViewBy = getViewByTime($_GET['time_ref'], $_GET['view_by'])	;
	
	$sq  = "select year, month, day, wday, hour, min, counter_name, counter_label, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
	$sq .= "where timestamp >=".$arrViewBy['ts_from']." and timestamp <".$arrViewBy['ts_to']." ";
	if ($_GET['labels'] != 'all') {
		$sq .= "and counter_label='entrance' ";
	}
	
	$sq .= $arrViewBy['g_sq'].", counter_label ";
	// print $sq;
	// print Query2Table($connect, $sq);
	$rs = mysqli_query($connect, $sq);
	$json_str = Result2Json4Chart($rs, $arrViewBy['ts_from'], $arrViewBy['interval'], $arrViewBy['duration'], $arrViewBy['dateformat'], $msg );
	
	// for($i=0; $i<($rs->num_rows); $i++) {
	// 	$assoc = mysqli_fetch_assoc($rs);
	// 	$datetime = date($arrViewBy['dateformat'], mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
	// 	$arr_result[$datetime][$assoc['counter_label']] = $assoc['sum'];
	// 	if(!in_array($assoc['counter_label'], $arr_label)) {
	// 		array_push($arr_label, $assoc['counter_label']);
	// 	}
	// }
	// for($i=0; $i<$arrViewBy['duration']; $i++) { 
	// 	$datetimest = $arrViewBy['ts_from'] + $arrViewBy['interval']*$i;
	// 	$datetime = date($arrViewBy['dateformat'], $datetimest);
	// 	for ($j=0; $j<sizeof($arr_label); $j++) {
	// 		$arr_result[$arr_label[$j]][$i] = $arr_result[$datetime][$arr_label[$j]] ? $arr_result[$datetime][$arr_label[$j]]:	($datetimest > $thistime ? null : 0);	
	// 	}

	// 	$arr_rs['category']['timestamps'][$i] = $datetimest;
	// 	$arr_rs['category']['datetimes'][$i] = $datetime;

	// }
	// // print_r($arr_result);
	// for ($i=0; $i<sizeof($arr_label); $i++) {
	// 	$arr_rs['data'][$i]['name'] = $msg[$arr_label[$i]] ? $msg[$arr_label[$i]] : $arr_label[$i]  ;
	// 	$arr_rs['data'][$i]['data'] = $arr_result[$arr_label[$i]];
	// }
	// $arr_rs['time'] = round(microtime(true)-$s1,4);
	// $arr_rs['title']['chart_title'] = $msg['footfall'];
	// // print_arr($arr_rs);
	// $json_str = json_encode($arr_rs, JSON_NUMERIC_CHECK);
	// unset($arr_result);
	// unset($arr_rs);
	// unset($arr_label);
}

else if($_GET['fr'] == 'latestFlow') {
	$msg=q_language('footfall.php');
	$s1 = microtime(true);
	$arr_result = array();
	$arr_rs = array();
	$arr_label = array();
//	print_arr($_GET);

	$dateformat = 'Y-m-d';
	$interval = 3600*24;
	$to_ts = strtotime(date("Y-m-d", $thistime)) + 3600*24 -1;
	if($_GET['view_on']=='7day') {
		$from_ts = strtotime(date("Y-m-d", $thistime))- 3600*24*7;
	}
	else if($_GET['view_on']=='4week') {
		$from_ts = strtotime(date("Y-m-d", $thistime))-3600*24*7*4;
	}		
	else if($_GET['view_on']=='12week') {
		$from_ts = strtotime(date("Y-m-d", $thistime))-3600*24*7*12;
	}
	$duration =  ceil(($to_ts-$from_ts)/$interval);
	
	$sq  = "select year, month, day, wday, hour, min, counter_name, counter_label, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
	$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." ";
	if ($_GET['labels'] != 'all') {
		$sq .= "and counter_label='entrance' ";
	}
	$sq .= "group by year, month, day, counter_label ";
	// print $sq;
	// print Query2Table($connect, $sq);
	$rs = mysqli_query($connect, $sq);


	$json_str = Result2Json4Chart($rs, $from_ts, $interval, $duration, $dateformat, $msg );
	// while($assoc = mysqli_fetch_assoc($rs)) {
	// 	$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
	// 	$arr_result[$datetime][$assoc['counter_label']] = $assoc['sum'];
	// 	if(!in_array($assoc['counter_label'], $arr_label)) {
	// 		array_push($arr_label, $assoc['counter_label']);
	// 	}		
	// }
	// for($i=0; $i<$duration; $i++) { 
	// 	$datetimest = $from_ts + $interval*$i;
	// 	$datetime = date($dateformat, $datetimest);
	// 	for ($j=0; $j<sizeof($arr_label); $j++) {
	// 		$arr_result[$arr_label[$j]][$i] = $arr_result[$datetime][$arr_label[$j]] ? $arr_result[$datetime][$arr_label[$j]]:	($datetimest > $thistime ? null : 0);	
	// 	}
	// 	$arr_rs['category']['timestamps'][$i] = $datetimest;
	// 	$arr_rs['category']['datetimes'][$i] = $datetime;
	// }
	// // print_arr($arr_result);
	// for ($i=0; $i<sizeof($arr_label); $i++) {
	// 	$arr_rs['data'][$i]['name'] = $msg[$arr_label[$i]] ? $msg[$arr_label[$i]] : $arr_label[$i]  ;
	// 	$arr_rs['data'][$i]['data'] = $arr_result[$arr_label[$i]];
	// }
	// $arr_rs['time'] = round(microtime(true)-$s1,4);
	// $arr_rs['title']['chart_title'] = $msg['footfall'];
	// // print_arr($arr_rs);
	// $json_str = json_encode($arr_rs, JSON_NUMERIC_CHECK);
	// unset($arr_result);
	// unset($arr_rs);
	// unset($arr_label);
}

else if($_GET['fr'] == 'compareByTime') {
	$s1 = microtime(true);
	$ts_ref[0] = strtotime($_GET['time_ref1']);
	$ts_ref[1] = strtotime($_GET['time_ref2']);
	$ts_ref[2] = strtotime($_GET['time_ref3']);
	
	$str_ts_ref1 =  "(timestamp >= ".$ts_ref[0]." and timestamp <".($ts_ref[0] + 3600*24 -1).") ";
	$str_ts_ref2 =  "(timestamp >= ".$ts_ref[1]." and timestamp <".($ts_ref[1] + 3600*24 -1).") ";
	$str_ts_ref3 =  "(timestamp >= ".$ts_ref[2]." and timestamp <".($ts_ref[2] + 3600*24 -1).") ";
	
	$sq  = "select year, month, day, wday, hour, min, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
	$sq .= "where (".$str_ts_ref1." or ".$str_ts_ref2." or ".$str_ts_ref3.") and counter_label = 'entrance' ";

	if($_GET['st'] ) {
		$sq .= "and store_code ='".$_GET['st']."' ";
	}
	if($_GET['sq'] ) {
		$sq .= "and square_code ='".$_GET['sq']."' ";
	}	

	$sq .= "group by year, month, day, hour";
	$dateformat = 'Y-m-d H:00';
	$duration = 3;
//	print $sq;
//	print Query2Table($connect, $sq);
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
		$arr_result[$datetime] = $assoc['sum'];
	}
	
	for($i=0; $i<sizeof($ts_ref); $i++) {
		$d_str = '';
		for($j=0; $j<24; $j++) {
			$ts = $ts_ref[$i] + 3600*$j;
			if($i==0) {
				if($j>0) {
					$l_str .= ',';
				}
				$l_str .= date('"H:00"', $ts);
			}
			$datetime = date($dateformat, $ts);
			if(!$arr_result[$datetime]) {
				$arr_result[$datetime] = 0;
			}
			if($ts > $thistime) {
				$arr_result[$datetime] = 'null';
			}
			if($j>0) {
				$d_str .=',';
			}
			$d_str .= $arr_result[$datetime];
		}

		if($s_str) {
			$s_str .= ',';
		}
		$s_str .= '{"name":"'.date("Y-m-d", $ts_ref[$i]).' '.$msg[strtolower(date('D', $ts_ref[$i]))].'", "data":['.$d_str.']}';
	}

	$json_str= '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"chart_title":"'.$msg['footfall'].'"
		},
		"time":'.(round(microtime(true)-$s1,4)).'
	}';
}

else if($_GET['fr'] == 'compareByPlace') {
	$s1 = microtime(true);
	$square[0] = $_GET['sq1']; $store[0] = $_GET['st1'];
	$square[1] = $_GET['sq2']; $store[1] = $_GET['st2'];
	$square[2] = $_GET['sq3']; $store[2] = $_GET['st3'];

	$site_sq = "";
	for($i=0; $i<3; $i++) {
		if($square[$i]) {
			if($site_sq) {
				$site_sq .= "or ";
			}
			if($store[$i]) {
				$site_sq .= "store_code = '".$store[$i]."' ";
			}
			else {
				$site_sq .= "square_code = '".$square[$i]."' ";
			}
		}
	}
	if($site_sq) {
		$site_sq = "and (".$site_sq.") ";
	}
	
	$to_e = explode("~", $_GET['time_ref']);
	if($_GET['view_by'] == 'hour') {
		$from_ts = strtotime($to_e[1]);
		$to_ts = strtotime($to_e[1]) + 3600*24-1;
		$dateformat = 'Y-m-dTH:00';
		$interval = 3600;
		$g_sq .= "group by year, month, day, hour, square_code, store_code";
	}
	else if($_GET['view_by'] == 'day') {
		$from_ts = strtotime($to_e[0]);
		$to_ts = strtotime($to_e[1]) + 3600*24-1;		
		$dateformat = 'Y-m-d';
		$interval = 3600*24;
		$g_sq .= "group by year, month, day, square_code, store_code";
	}	
	$duration = ceil(($to_ts- $from_ts) /$interval);
	for($i=0; $i<3; $i++) {
		if($store[$i]) {
			$sq = "select name, code from ".$DB_CUSTOM['store']." where code ='".$store[$i]."' ";
		}
		else {
			$sq = "select name, code from ".$DB_CUSTOM['square']." where code ='".$square[$i]."' ";
		}
		$row = mysqli_fetch_row(mysqli_query($connect, $sq));
		$site_name[$i] = $row[0];
		$site_code[$i] = $row[1];

	}
	
	$sq  = "select year, month, day, wday, hour, min, square_code, store_code, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
	$sq .= "where timestamp >= ".$from_ts."  and timestamp < ".$to_ts." and counter_label = 'entrance' ";
	
	if($site_sq) {
		$sq .= $site_sq;
	}
	$sq .= $g_sq;
	
//	print $sq;
//	print Query2Table($connect, $sq);
	$rs = mysqli_query($connect, $sq);
	for ($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
		if(in_array($assoc['store_code'], $store)) {
			$arr_rs[$assoc['store_code']][$datetime] = $assoc['sum'];
		}
		else {
			$arr_rs[$assoc['square_code']][$datetime] += $assoc['sum'];
		}
	}
	
	for($i=0; $i<3; $i++) {
		if(!$square[$i]) {
			continue;
		}
		for($j=0; $j<$duration; $j++) {
			$datetimest = $from_ts + $interval*$j;
			$datetime = date($dateformat, $datetimest);
			if($i==0) {
				if($j>0) {
					$l_str .= ',';
				}
				$l_str .= $datetimest;
//				$l_str .= '"'.$datetime.'"';
			}
			if(!$arr_rs[$site_code[$i]][$datetime] ) { 
				$arr_rs[$site_code[$i]][$datetime] = '0';
			}
			if($datetimest>$thistime) { 
				$arr_rs[$site_code[$i]][$datetime] = 'null';
			}
			
			if($j>0) {
				$d_str[$i] .= ',';
			}
			$d_str[$i] .= $arr_rs[$site_code[$i]][$datetime];
		}

		if($i>0) {
			$s_str .= ',';
		}
		$s_str .= '{"name":"'.$site_name[$i].'", "data":['.$d_str[$i].']}';
	}
//	print_arr($arr_rs);
	
	$json_str= '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"chart_title":"'.$msg['footfall'].'"
		},
		"time":'.(round(microtime(true)-$s1,4)).'
	}';
}
else if($_GET['fr'] == 'advancedAnalysis') {
	$msg=q_language('footfall.php');
	$s1 = microtime(true);
//	print_arr($_GET);
	$to_e = explode("~", $_GET['time_ref']);
	if($_GET['view_by'] == '10min') {
		$g_sq = "group by year, month, day, hour, min ";
		$dateformat = 'Y-m-d H:i';
		$from_ts = strtotime($to_e[1]);
		$to_ts = $from_ts + 3600*24 -1;
		$interval = 600;
	}
	else if($_GET['view_by'] == 'hour') {
		$g_sq = "group by year, month, day, hour";
		$dateformat = 'Y-m-d H:00';
		$from_ts = strtotime($to_e[1]);
		$to_ts = $from_ts + 3600*24 -1;
		$interval = 3600;
	}
	else if($_GET['view_by'] == 'day') {
		$g_sq = "group by year, month, day ";
		$dateformat = 'Y-m-d';
		$from_ts = strtotime($to_e[0]);
		$to_ts = strtotime($to_e[1]) + 3600*24 -1;
		$interval = 3600*24;
	}
	else if($_GET['view_by'] == 'month') {
		$g_sq = "group by year, month ";
		$dateformat = 'Y-m';
		$from_ts = strtotime(date("Y-m-1", strtotime($to_e[0])));
		$to_ts = strtotime(date("Y-m-t", strtotime($to_e[1]))) + 3600*24 -1;			
		$interval = 3600*24*31;
	}
//	print date("Y-m-d H:i:s", $from_ts).'~'.date("Y-m-d H:i:s", $to_ts);
	$duration =  ceil(($to_ts-$from_ts)/$interval);
	
	$sq  = "select year, month, day, wday, hour, min, counter_name, counter_label, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
	$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." and (counter_label='entrance' or counter_label='exit') ";
	
	$store_name = $msg['allsquare'];
	if($_GET['sq'] ) {
		$sq .= "and square_code ='".$_GET['sq']."' ";
		$store_name = mysqli_fetch_row(mysqli_query($connect, "select name from ".$DB_CUSTOM['square']." where code='".$_GET['sq']."' "))[0];
	}	
	if($_GET['st'] ) {
		$sq .= "and store_code ='".$_GET['st']."' ";
		$store_name .= '/'.(mysqli_fetch_row(mysqli_query($connect, "select name from ".$DB_CUSTOM['store']." where code='".$_GET['st']."' "))[0]);
	}

	$sq .= $g_sq;
	$sq .= ", counter_label ";
	
	// print $sq;
	// print Query2Table($connect, $sq);

	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$timest =  mktime($assoc['hour'],$assoc['min'], 0, $assoc['month'], $assoc['day'], $assoc['year']);
		$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
		$arr_result[$datetime][$assoc['counter_label']] = $assoc['sum'];
		$arr_result[$datetime]['timestamp'] = $timest;
	}
	// print_arr($arr_result);
	// $json_str = json_encode($arr_result);
	// print $json_str;
	$arr_rs = array();
	for($i=0; $i<$duration; $i++) { 
		$datetimest = $from_ts + $interval*$i;
		$datetime = date($dateformat, $datetimest);
		if(!$arr_result[$datetime]['entrance']) {
			$arr_result[$datetime]['entrance'] = 0;
		}
		if(!$arr_result[$datetime]['exit']) {
			$arr_result[$datetime]['exit'] = 0;
		}
		if(!$arr_result[$datetime]['timestamp'] ){
			$arr_result[$datetime]['timestamp'] = $datetimest;
		}

		if($datetimest > $thistime) {
			$arr_result[$datetime]['entrance'] = 'null'; //NaN
			$arr_result[$datetime]['exit'] = 'null'; //NaN
		}
		$arr_rs['data'][$i]['datetime'] = $datetime;
		$arr_rs['data'][$i]['timestamp'] =  $datetimest;
		$arr_rs['data'][$i]['entrance'] = $arr_result[$datetime]['entrance'];
		$arr_rs['data'][$i]['exit'] = $arr_result[$datetime]['exit'];
	}
	// print_arr($arr_result);
	
	// $arr_rs['data'] = $arr_result;
	$arr_rs['time'] = round(microtime(true)-$s1,4);
	$arr_rs['title']['chart_title'] = $msg['footfall'];



	$json_str = json_encode($arr_rs, JSON_NUMERIC_CHECK);
	// print $json_str;
	
// 	for($i=0, $l_str = "", $d_str=""; $i<$duration; $i++) { 
// 		$datetimest = $from_ts + $interval*$i;
// 		$datetime = date($dateformat, $datetimest);
// 		if($i>0) {
// 			$l_str .= ",";
// 			$d_str .= ",";
// 		}
// 		$l_str .= $datetimest;
// //		$l_str .= '"'.date($dateformat, $datetimest).'"';

// 		if(!$arr_result[$datetime]) {
// 			$arr_result[$datetime] = 0;
// 		}
// 		if($datetimest > $thistime) {
// 			$arr_result[$datetime] = 'null'; //NaN
// 		}
		
// 		$d_str .= $arr_result[$datetime];
// 		if($arr_result[$datetime] == 'null') {
// 			$arr_result[$datetime] = '';
// 		}
// 	}
//	print_arr($arr_result);
	// $d_str = '{"name":"'.$store_name.'", "data":['.$d_str.']}';
	// $json_str= '{
	// 	"data":['.$d_str.'],
	// 	"label":['.$l_str.'],
	// 	"title":{
	// 		"chart_title":"'.$msg['footfall'].'"
	// 	},
	// 	"time":'.(round(microtime(true)-$s1,4)).'
	// }';
}



else if($_GET['fr'] == 'kpi') {	

	$card_val[0] = 2500;
	$card_val[1] = 2300;
	$card_val[2] = 3.2457;
	$card_val[3] = 500;
	$card_val[4] = 30.1254;
	
	$json_str= '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"chart_title":"'.$chart_title.'"
		},
		"card_val":['.$card_val[0].','.$card_val[1].','.$card_val[2].','.$card_val[3].','.$card_val[4].']
	}';
}

else if($_GET['fr'] == 'trafficDistribution') {	
	$msg = q_language('footfall.php');
	$s1 = microtime(true);
//	print_arr($_GET);
	$to_e = explode("~", $_GET['time_ref']);
	$from_ts = strtotime($to_e[0]);
	$to_ts = strtotime($to_e[1]) + 3600*24-1;

	$sq  = "select year, month, day, wday, hour, min, counter_label, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
	$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." ";
	
	if($_GET['view_on'] == 'occupy') {
		$chart_title = $msg['occupy'];
		$sq .= "and (counter_label='entrance' or counter_label='exit')  ";
	}
	else {
		$chart_title = $msg['footfall'];
		$sq .= "and counter_label='entrance' ";
	}
	if($_GET['st'] ) {
		$sq .= "and store_code ='".$_GET['st']."' ";
	}
	if($_GET['sq'] ) {
		$sq .= "and square_code ='".$_GET['sq']."' ";
	}	

	$sq .= "group by year, month, day, hour, counter_label ";
	$dateformat = 'Y-m-d H:00';
	$duration = ceil(($to_ts- $from_ts) /3600/24);

//	print $sq;
//	print Query2Table($connect, $sq);
	
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
		$arr_result[$datetime][$assoc['counter_label']] = $assoc['sum'];
	}

	for($i=0; $i<$duration; $i++) {
		$d_str = '';
		$occupy = 0;
		for($j=0; $j<24; $j++) {
			$datetimest = $from_ts + 3600*$j + 3600*24*$i ;
			$datetime = date($dateformat, $from_ts + 3600*$j + 3600*24*$i );
			if($i==0) {
				if($j>0) {
					$l_str .= ',';
				}
				$l_str .= '"'.date("H:00", $datetimest).'"';
			}
			if(!$arr_result[$datetime]['entrance']) {
				$arr_result[$datetime]['entrance'] = 0;
			}
			if($j>0) {
				$d_str .= ',';
			}
			if($_GET['view_on'] == 'occupy') {
				if(date("H:00", $datetimest) == '06:00') {
					$occupy = 0;
				}
				else {
					$occupy += $arr_result[$datetime]['entrance'] - $arr_result[$datetime]['exit'];
					if(($occupy < 0) or ($datetimest > $thistime))  {
						$occupy = 0;
					}
				}
				$d_str .= $occupy;
			}
			else {
				$d_str .= $arr_result[$datetime]['entrance'];
			}
		}
		if($i>0) {
			$s_str = ','.$s_str;;
		}
		$n_str = date("Y-m-d", $from_ts + 3600*24*$i).' '.$msg[strtolower(date("D", $from_ts + 3600*24*$i))];
		$s_str = '{"name":"'.$n_str.'", "data":['.$d_str.']}'.$s_str;
		
	}
//	print_arr($arr_result);
	
	$json_str= '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"chart_title":"'.$chart_title.'"
		},
		"time":'.(round(microtime(true)-$s1,4)).'
	}';	
}

else if ($_GET['fr'] == 'trendAnalysis') {
	$msg=q_language('footfall.php');
//	print_arr($_GET);
//	$from_ts = strtotime($_GET['time_ref']);
		
	if(($_GET['view_by'] == '10min') or ($_GET['view_by'] == 'hour')) {
		$from_ts = strtotime($_GET['time_ref']);
		$to_ts = $from_ts + 3600*24-1;
		$from_ts_l = $from_ts - 3600*24*7;
		$to_ts_l = $from_ts_l + 3600*24-1;
		$from_ts_b = mktime(0,0,0, date("m",$from_ts), date("d",$from_ts), date("Y",$from_ts)-1); 
		$to_ts_b = $from_ts_b + 3600*24-1;
		$n_current = $msg['current'].'('.date("Y-m-d", $from_ts).')';
		$n_last = $msg['last'].'('.date("Y-m-d", $from_ts_l).')';
		$n_before = $msg['before'].'('.date("Y-m-d", $from_ts_b).')';
		if($_GET['view_by'] == '10min') {
			$g_sq = "group by year, month, day, hour, min ";
			$dateformat = 'Y-m-d H:i';
			$interval = 600;
		}
		else if($_GET['view_by'] == 'hour') {
			$g_sq = "group by year, month, day, hour";
			$dateformat = 'Y-m-d H:00';
			$interval = 3600;
		}
	}
	else if($_GET['view_by'] == 'day') {
		$to_ts = strtotime($_GET['time_ref']) + 3600*24-1;
		$from_ts = $to_ts - 3600*24*7 + 1;
		$from_ts_l = $from_ts - 3600*24*7;
		$to_ts_l = $from_ts_l + 3600*24*7-1;
		$from_ts_b = $from_ts - 3600*24*7*52;
		$to_ts_b = $from_ts_l + 3600*24*7-1;
		
		$n_current = $msg['current'].'('.date("Y-m-d", $from_ts).'~'.date("Y-m-d", $to_ts).')';
		$n_last = $msg['last'].'('.date("Y-m-d", $from_ts_l).'~'.date("Y-m-d", $to_ts_l).')';
		$n_before = $msg['before'].'('.date("Y-m-d", $from_ts_b).'~'.date("Y-m-d", $to_ts_b).')';	
		
		$g_sq = "group by year, month, day ";
		$dateformat = 'Y-m-d w';
		$interval = 3600*24;
	}
	$duration = ceil(($to_ts- $from_ts) /$interval);
	
	$sq  = "select year, month, day, wday, hour, min, counter_name, sum(counter_val) as sum, timestamp from ".$DB_CUSTOM['count']." ";
	$sq .= "where ( (timestamp >=".$from_ts." and timestamp <".$to_ts.") or (timestamp >=".$from_ts_l." and timestamp <".$to_ts_l.") or (timestamp >=".$from_ts_b." and timestamp <".$to_ts_b.")) ";
	$sq .= "and counter_label='entrance' ";

	if($_GET['st'] ) {
		$sq .= "and store_code ='".$_GET['st']."' ";
	}
	if($_GET['sq'] ) {
		$sq .= "and square_code ='".$_GET['sq']."' ";
	}	
	$sq .= $g_sq." order by timestamp asc ";
//	print $sq;
//	print Query2Table($connect, $sq);

	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
		$arr_result[$datetime] = $assoc['sum'];
	}
//	print_arr($arr_result);
	for($i=0; $i<$duration; $i++) { 
		$datetimest = $from_ts + $interval*$i;
		$datetime = date($dateformat, $datetimest);
		if($i>0) {
			$l_str .= ',';
			$d_str_c .= ',';
			$d_str_l .= ',';
			$d_str_b .= ',';
		}
		$l_str .= $datetimest;	

		if(!$arr_result[$datetime]) {
			$arr_result[$datetime] = 0;
		}
		if($datetimest > $thistime) {
			$arr_result[$datetime] = 'null'; //NaN
		}
		$d_str_c .= $arr_result[$datetime];
		
		$datetimest = $from_ts_l + $interval*$i;
		$datetime = date($dateformat, $datetimest);
		if(!$arr_result[$datetime]) {
			$arr_result[$datetime] = 0;
		}
		$d_str_l .= $arr_result[$datetime];
		
		$datetimest = $from_ts_b + $interval*$i;
		$datetime = date($dateformat, $datetimest);
		if(!$arr_result[$datetime]) {
			$arr_result[$datetime] = 0;
		}
		$d_str_b .= $arr_result[$datetime];
	}
//	print_arr($arr_result);
	$s_str = '{"name":"'.$n_current.'", "data":['.$d_str_c.']},';
	$s_str.= '{"name":"'.$n_last.'", "data":['.$d_str_l.']},';
	$s_str.= '{"name":"'.$n_before.'", "data":['.$d_str_b.']}';
	
	$json_str= '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"chart_title":"'.$msg['footfall'].'"
		}
	}';
	
}
else if($_GET['fr'] == 'compareByLabel') {
	$msg=q_language('footfall.php');
	$s1 = microtime(true);
//	print_arr($_GET);
	$arrViewBy = getViewByTime($_GET['time_ref'], $_GET['view_by'])	;


// $to_e = explode("~", $_GET['time_ref']);
// 	if($_GET['view_by'] == '10min') {
// 		$g_sq = "group by year, month, day, hour, min ";
// 		$dateformat = 'Y-m-d H:i';
// 		$from_ts = strtotime($to_e[1]);
// 		$to_ts = $from_ts + 3600*24 -1;
// 		$interval = 600;
// 	}
// 	else if($_GET['view_by'] == 'hour') {
// 		$g_sq = "group by year, month, day, hour";
// 		$dateformat = 'Y-m-d H:00';
// 		$from_ts = strtotime($to_e[1]);
// 		$to_ts = $from_ts + 3600*24 -1;
// 		$interval = 3600;
// 	}
// 	else if($_GET['view_by'] == 'day') {
// 		$g_sq = "group by year, month, day ";
// 		$dateformat = 'Y-m-d';
// 		$from_ts = strtotime($to_e[0]);
// 		$to_ts = strtotime($to_e[1]) + 3600*24 -1;
// 		$interval = 3600*24;
// 	}
// 	else if($_GET['view_by'] == 'month') {
// 		$g_sq = "group by year, month ";
// 		$dateformat = 'Y-m';
// 		$from_ts = strtotime(date("Y-m-1", strtotime($to_e[0])));
// 		$to_ts = strtotime(date("Y-m-t", strtotime($to_e[1]))) + 3600*24 -1;			
// 		$interval = 3600*24*31;
// 	}
// 	$duration =  ceil(($to_ts-$from_ts)/$interval);
	
	$sq  = "select year, month, day, wday, hour, min, counter_name, counter_label, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
	$sq .= "where timestamp >=".$arrViewBy['ts_from']." and timestamp <".$arrViewBy['ts_to']." ";
	
	$store_name = $msg['allsquare'];
	if($_GET['sq'] ) {
		$sq .= "and square_code ='".$_GET['sq']."' ";
		$store_name = mysqli_fetch_row(mysqli_query($connect, "select name from ".$DB_CUSTOM['square']." where code='".$_GET['sq']."' "))[0];
	}	
	if($_GET['st'] ) {
		$sq .= "and store_code ='".$_GET['st']."' ";
		$store_name .= '/'.(mysqli_fetch_row(mysqli_query($connect, "select name from ".$DB_CUSTOM['store']." where code='".$_GET['st']."' "))[0]);
	}
	$sq .= $arrViewBy['g_sq'].", counter_label ";;
	
	// print $sq;
	// print Query2Table($connect, $sq);
	$arr_label = array();
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$datetime = date($arrViewBy['dateformat'],mktime($assoc['hour'],$assoc['min'], 0, $assoc['month'], $assoc['day'], $assoc['year']));
		$arr_result[$datetime][$assoc['counter_label']] = $assoc['sum'];
		if(!in_array($assoc['counter_label'], $arr_label)) {
			array_push($arr_label, $assoc['counter_label']);
		}
	}
	// print_arr($arr_result);
	$arr_rs = array();
	for($i=0; $i<$arrViewBy['duration']; $i++) { 
		$datetimest = $arrViewBy['ts_from'] + $arrViewBy['interval']*$i;
		$datetime = date($arrViewBy['dateformat'], $datetimest);
		for ($j=0; $j<sizeof($arr_label); $j++) {
			// $arr_rs['data'][$i][$arr_label[$j]] = $arr_result[$datetime][$arr_label[$j]] ? $arr_result[$datetime][$arr_label[$j]]:	($datetimest > $thistime ? 'null' : 0);	
			$arr_result[$arr_label[$j]][$i] = $arr_result[$datetime][$arr_label[$j]] ? $arr_result[$datetime][$arr_label[$j]]:	($datetimest > $thistime ? null : 0);	
		}
		
		$arr_rs['category']['datetimes'][$i] = $datetime;
		$arr_rs['category']['timestamps'][$i] = $datetimest;

	}
	for ($i=0; $i<sizeof($arr_label); $i++) {
		$arr_rs['data'][$i]['name'] = $msg[$arr_label[$i]] ? $msg[$arr_label[$i]] : $arr_label[$i]  ;
		$arr_rs['data'][$i]['data'] = $arr_result[$arr_label[$i]];
	}
	$arr_rs['time'] = round(microtime(true)-$s1,4);
	$arr_rs['title']['chart_title'] = $msg['footfall'];

	// print_r($arr_rs);
	$json_str = json_encode($arr_rs, JSON_NUMERIC_CHECK);
}

else if ($_GET['fr'] == 'heatMap') {

	if($_GET['act'] == 'list') {
		
		$sq = "select device_info from ".$DB_CUSTOM['camera'] ." where enable_heatmap = 'y'";
		if($_GET['st'] ) {
			$sq .= " and  store_code='".$_GET['st']."'";
		}	
		else if($_GET['sq'] ) {
			$sq .= " and square_code='".$_GET['sq']."'";
		}
		$rs = mysqli_query($connect, $sq);
		$device_info= array();
		for ($i=0; $i<($rs->num_rows); $i++) {
			$row = mysqli_fetch_row($rs);
			$device_info[$i] = $row[0];
			
			$sqa = "select body from ".$DB_COMMON['snapshot']." where device_info='".$row[0]."'";
			$rsa = mysqli_query($connect0, $sqa);
			$img = mysqli_fetch_row($rsa)[0];
			
			if($i>0) {
				$d_str .= ',';
			}
			$d_str .= '{"device_info":"'.$row[0].'", "image":"'.$img.'"}'; 
		}
		$json_str= '['.$d_str.']';
	}
	
	else {
//		print_arr($_GET);
		if(!$_GET['scale']) {
			$scale = 10;
		}
		if($_GET['view_by'] == 'hour') {
			$from_ts = strtotime($_GET['time_ref']);
			$to_ts = $from_ts + 3600 - 1;
			$subtitle = date("Y-m-d H:i", $from_ts)." ~ ".date("Y-m-d H:i", $to_ts);
		}
		else if($_GET['view_by'] == 'day') {
			$from_ts = strtotime($_GET['time_ref']);
			$to_ts = $from_ts + 3600*24 - 1;
			$subtitle = date("Y-m-d", $from_ts);
		}
		
		$device_info = "mac=".$_GET['mac']."&brand=".$_GET['brand']."&model=".$_GET['model'];
		
		$sq = "select body_csv, concat(year,'/',month,'/',day,' ',hour,':00'), device_info,  timestamp from ".$DB_CUSTOM['heatmap']." where device_info = '".$device_info."' and timestamp >= ".$from_ts." and timestamp < ".$to_ts." ";
		
		$MAX = 0;
		$rs = mysqli_query($connect, $sq);
		while($row = mysqli_fetch_assoc($rs)) {
			$line = explode("\r\n",$row['body_csv']);
			for($y =0; $y<45; $y++) {
				$col = explode(",",$line[$y]);
				for($x=0; $x<80; $x++){
					$col[$x] = trim($col[$x]);
					$val[$x][$y] += (int)$col[$x];
					if($val[$x][$y] > $MAX) {
						$MAX = $val[$x][$y];
					}
				}
			}	
		}
		for($y = 0; $y< 45; $y++) {
			for($x =0, $ss = ''; $x<80; $x++) {
				if($val[$x][$y]) {
					if($d_str) {
						$d_str .= ",";
					}
					$d_str .= '{"x":'.($x*$scale).', "y":'.($y*$scale).', "value":'.$val[$x][$y].'}';
				}
			}
		}
		
	//	print $s_str;
		$img = '';
		$sq = "select body from ".$DB_COMMON['snapshot']." where device_info='".$device_info."'";
		$rs = mysqli_query($connect0, $sq);
		$img = mysqli_fetch_row($rs)[0];

		$title = $device_info;
		
	//	$img='';
	//	print $sq;
		$json_str= '{
			"max":'.$MAX.',
			"data":['.$d_str.'],
			"image":"'.$img.'",
			"scale":'.$scale.',
			"title":"'.$title.'",
			"subtitle":"'.$subtitle.'"
		}';
	}
}

else if ($_GET['fr'] == 'ageGender') {
	$msg=q_language('dashboard.php');
	$s1 = microtime(true);
	$age_range = json_decode($configVars['MISC']['AGE_GROUP']);
	if(!$age_range) {
		$age_range = [0,18,30,45,65];
	}	
	$gender_div = array($msg['male'], $msg['female']);
	
	$to_e = explode("~", $_GET['time_ref']);
	if($_GET['view_by'] == '10min') {
		$g_sq = "group by year, month, day, hour, min ";
		$dateformat = 'Y-m-d H:i';
		$from_ts = strtotime($to_e[1]);
		$to_ts = $from_ts + 3600*24 -1;
		$interval = 600;
	}
	else if($_GET['view_by'] == 'hour') {
		$g_sq = "group by year, month, day, hour ";
		$dateformat = 'Y-m-d H:00';
		$from_ts = strtotime($to_e[1]);
		$to_ts = $from_ts + 3600*24 -1;
		$interval = 3600;
		$gender_title = date("Y-m-d", $from_ts);
		$age_title = date("Y-m-d", $from_ts);		
	}
	else if($_GET['view_by'] == 'day') {
		$g_sq = "group by year, month, day ";
		$dateformat = 'Y-m-d';
		$from_ts = strtotime($to_e[0]);
		$to_ts = strtotime($to_e[1]) + 3600*24 -1;
		$interval = 3600*24;
	}
	else if($_GET['view_by'] == 'month') {
		$g_sq = "group by year, month ";
		$dateformat = 'Y-m';
		$from_ts = strtotime(date("Y-m-1", strtotime($to_e[0])));
		$to_ts = strtotime(date("Y-m-t", strtotime($to_e[1]))) + 3600*24 -1;			
		$interval = 3600*24*31;
	}	

	$age_query_string = '';
	for($i=0; $i<100; $i++) {
		if($i>0) {
			$age_query_string .= ", ";
		}
		$age_query_string .= "sum(substring_index(substring_index(age, ',', ".($i+1)."),',',-1))";
		if($i<99) {
			$age_query_string .= ",','";
		}
	}
	$age_query_string  = "concat(".$age_query_string.") as age";
	
	$gender_query_string = "sum(substring_index(substring_index(gender, ',', 1),',',-1)), ',', sum(substring_index(substring_index(gender, ',', 2),',',-1))" ;
	$gender_query_string = "concat(".$gender_query_string.") as gender";
	
	$sq =  "select square_code, store_code, camera_code, year, month, day, hour, min, ".$age_query_string.", ".$gender_query_string." from ".$DB_CUSTOM['age_gender']." ";
	$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." ";

	if($_GET['sq']) {
		$sq .=  "and square_code ='".$_GET['sq']."' ";
	}
	if($_GET['st']) {
		$sq .=  "and store_code ='".$_GET['st']."' ";
	}
	$sq .= $g_sq;
	$sq .= "order by timestamp asc ";
//	print $sq;
//	print Query2Table($connect, $sq);	
	
	$duration = ceil(($to_ts- $from_ts)/$interval);
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$datetimest = mktime($assoc['hour'], $assoc['min'], 0, $assoc['month'], $assoc['day'], $assoc['year']);
		$datetime =  date($dateformat, $datetimest);
		$arr_result[$datetime]['age'] = age_group(json_decode('['.$assoc['age'].']'), $age_range);
		$arr_result[$datetime]['gender'] = json_decode('['.$assoc['gender'].']');
	}	
	
	for($i=0; $i<sizeof($age_range); $i++) {
		$d_str = '';
		for($j=0; $j<$duration; $j++) { 
			$datetimest = $from_ts + $interval*$j;
			$datetime = date($dateformat, $datetimest);
			$arr_result['total'][$i] += $arr_result[$datetime]['age'][$i];
			if($i==0) {
				if($j>0){
					$l_str .=',';
				}
				$l_str .= $datetimest;
			}
			if(!$arr_result[$datetime]['age'][$i]) {
				$arr_result[$datetime]['age'][$i] = 0;
			}
			if($datetimest > $thistime) {
				$arr_result[$datetime]['age'][$i] = 'null';
			}
			if($j>0) {
				$d_str .= ',';
			}
			$d_str .=  $arr_result[$datetime]['age'][$i];
		}
		if($i>0) {
			$s_str .= ',';
		}
		if(!$arr_result['total'][$tag_div[$i]]) {
			$arr_result['total'][$tag_div[$i]] = 0;
		}
		$s_str .= '{"name":"'.$msg['agegroup'.$i].'", "data":['.$d_str.'], "total":'.$arr_result['total'][$i].'}';
	}
	for($i=0; $i<2; $i++) {
		$d_str = '';
		for($j=0;$j<$duration; $j++) { 
			$datetimest = $from_ts + $interval*$j;
			$datetime = date($dateformat, $datetimest);
			$arr_result['total'][$i] += $arr_result[$datetime]['gender'][$i];

			if(!$arr_result[$datetime]['gender'][$i]) {
				$arr_result[$datetime]['gender'][$i] = 0;
			}
			if($datetimest > $thistime) {
				$arr_result[$datetime]['gender'][$i] = '"NaN"';
			}
			if($j>0) {
				$d_str .= ',';
			}
			$d_str .=  $arr_result[$datetime]['gender'][$i];
		}
		if(!$arr_result['total'][$i]) {
			$arr_result['total'][$i] = 0;
		}
		$s_str .= ',';
		$s_str .= '{"name":"'.$gender_div[$i].'", "data":['.$d_str.'], "total":'.$arr_result['total'][$i].'}';
	}	
//	print_arr($d_str);
//	print_arr($arr_result);
	$json_str= '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"gender_bar":"'.$gender_title.'",
			"age_bar":"'.$age_title.'"
		},
		"time":'.(round(microtime(true)-$s1,4)).'
	}';
}

else if($_GET['fr'] == 'macSniff') {
	$to_e = explode("~", $_GET['time_ref']);
	if($_GET['view_by'] == '10min') {
		$g_sq = "group by year, month, day, hour, min ";
		$dateformat = 'Y-m-d H:i';
		$from_ts = strtotime($to_e[1]);
		$to_ts = $from_ts + 3600*24 -1;
		$interval = 600;
	}
	else if($_GET['view_by'] == 'hour') {
		$g_sq = "group by year, month, day, hour ";
		$dateformat = 'Y-m-d H:00';
		$from_ts = strtotime($to_e[1]);
		$to_ts = $from_ts + 3600*24 -1;
		$interval = 3600;
		$gender_title = date("Y-m-d", $from_ts);
		$age_title = date("Y-m-d", $from_ts);		
	}
	else if($_GET['view_by'] == 'day') {
		$g_sq = "group by year, month, day ";
		$dateformat = 'Y-m-d';
		$from_ts = strtotime($to_e[0]);
		$to_ts = strtotime($to_e[1]) + 3600*24 -1;
		$interval = 3600*24;
	}
	else if($_GET['view_by'] == 'month') {
		$g_sq = "group by year, month ";
		$dateformat = 'Y-m';
		$from_ts = strtotime(date("Y-m-1", strtotime($to_e[0])));
		$to_ts = strtotime(date("Y-m-t", strtotime($to_e[1]))) + 3600*24 -1;			
		$interval = 3600*24*31;
	}	

	$sq =  "select square_name, store_name, camera_name, device_info, year, month, day, hour, min, sum(male) as male, sum(female) as female, sum(age_1st) as age_1st, sum(age_2nd) as age_2nd, sum(age_3rd) as age_3rd, sum(age_4th) as age_4th, sum(age_5th) as age_5th, sum(age_6th) as age_6th, sum(age_7th) as age_7th from ".$DB_CUSTOM['mac']." ";
	$sq .= "where timestamp >= ".$from_ts." and timestamp < ".$to_ts." ";
	if($_GET['sq']) {
		$sq .=  "and square_code ='".$_GET['sq']."' ";
	}
	if($_GET['st']) {
		$sq .=  "and store_code ='".$_GET['st']."' ";
	}
	$sq .= $g_sq;
	$sq .= "order by timestamp asc ";
//	print $sq;
//	print Query2Table($connect, $sq);
	
	$duration = ceil(($to_ts- $from_ts)/$interval);
	$rs = mysqli_query($connect, $sq);
	
	$tag_div = array('age_1st','age_2nd','age_3rd','age_4th','age_5th','male','female');
	$title_div = array($msg['~17'],$msg['18~29'],$msg['30~44'],$msg['45~64'],$msg['65~'],$msg['male'], $msg['female']);	
	
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$datetimest = mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']);
		$datetime = date($dateformat, $datetimest);

		for($j=0; $j<sizeof($tag_div); $j++) {
			$arr_result[$datetime][$tag_div[$j]] = $assoc[$tag_div[$j]];
			$arr_result['total'][$tag_div[$j]] += $assoc[$tag_div[$j]];
		}
	}
	
	
	for($i=0; $i<sizeof($tag_div); $i++) {
		$d_str = '';
		for($j=0; $j<$duration; $j++) { 
			$datetimest = $from_ts + $interval*$j;
			$datetime = date($dateformat, $datetimest);
			if($i==0) {
				if($j>0){
					$l_str .=',';
				}
				$l_str .= $datetimest;
//				$l_str .= '"'.$datetime.'"';
			}
			if(!$arr_result[$datetime][$tag_div[$i]]) {
				$arr_result[$datetime][$tag_div[$i]] = 0;
			}
			if($datetimest > $thistime) {
				$arr_result[$datetime][$tag_div[$i]] = 'null';
			}
			if($j>0) {
				$d_str .= ',';
			}
			$d_str .=  $arr_result[$datetime][$tag_div[$i]];
		}
		if($i>0) {
			$s_str .= ',';
		}
		if(!$arr_result['total'][$tag_div[$i]]) {
			$arr_result['total'][$tag_div[$i]] = 0;
		}
		$s_str .= '{"name":"'.$tag_div[$i].'", "data":['.$d_str.'], "total":'.$arr_result['total'][$tag_div[$i]].'}';
	}
//	$s_str = '{"name":"'.$bubble_name.'", "data":[['.$x, $y, $size.'],],"';
	
	
//	print_arr($d_str);
//	print_arr($arr_result);
	$json_str= '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"gender_bar":"'.$gender_title.'",
			"age_bar":"'.$age_title.'"
		}
	}';

}

else if ($_GET['fr'] == 'summary') {
	$msg = q_language("summary.php");
	if($_GET['page'] == 'footfall') {
		$from_ts = strtotime($_GET['time_ref']) - 3600*24*27;
		$to_ts = strtotime($_GET['time_ref'])  + 3600*24 -1;
	
		$dateformat = "Y-m-d";
		$duration = 7;
		$interval =  3600*24;
	
		$arr_footfall_label = array($msg['3weeksbefore'], $msg['2weeksbefore'], $msg['lastweek'], $msg['thisweek']);
		
		$sq  = "select device_info, square_code, store_code, year, month, day, hour, min, wday, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
		$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." and counter_label='entrance' ";

		if($_GET['st'] ) {
			$sq .= "and store_code ='".$_GET['st']."' ";
		}
		if($_GET['sq'] ) {
			$sq .= "and square_code ='".$_GET['sq']."' ";
		}	

		$sq .= "group by year, month, day ";	
		$sq .= $g_sq." order by timestamp asc ";
		
	//	print $sq;
	//	print Query2Table($connect, $sq);
		$rs = mysqli_query($connect, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
			$arr_result[$datetime] = $assoc['sum'];
		}
		for($i=0; $i<4; $i++){
			$d_str = '';
			for($j=0; $j<7; $j++) {
				$datetimest = $from_ts + $interval*($i*7+$j);
				$datetime = date($dateformat, $datetimest);

				if(!$arr_result[$datetime]) {
					$arr_result[$datetime] = 0;
				}
				if($i==3) {
					if($j>0) {
						$l_str .= ',';
					}
					$total_this_week += $arr_result[$datetime];
					$l_str .= '"'.$msg[strtolower(date("D",$datetimest))].' '.date("m-d",$datetimest).'"';
//					$l_str .= '["'.$msg[strtolower(date("D",$datetimest))].'", "'.date("m-d",$datetimest).'"]';
				}
				if($i==2) {
					$total_last_week += $arr_result[$datetime];
				}
				if($i==1) {
					$total_week_before += $arr_result[$datetime];
				}
				if($arr_result[$datetime] > $max_visit['count']) {
					$max_visit['date'] = $datetime;
					$max_visit['count'] = $arr_result[$datetime];
				}
				
				if($datetimest > $thistime) {
					$arr_result[$datetime] = 'NaN'; //NaN
				}
				if($j>0) {
					$d_str .=',';
				}
				$d_str .= $arr_result[$datetime];
			}
			if($i>0) {
				$s_str .= ',';
			}
			$s_str .= '{"name":"'.$arr_footfall_label[$i].'", "data":['.$d_str.']}';
		}
		$chart_title = '"'.$msg['footfall'].'"';
		
		$line1 = $msg['comparingto'].$msg['adaybefore'].': '.number_format($arr_result[date($dateformat,$to_ts-3600*24*2)],0);
		$val1 = number_format($arr_result[date($dateformat,$to_ts-3600*24*2)],0);
		$line2 = $msg['comparingto'].$msg['lastweek'].'('.$msg[strtolower(date("D", $to_ts-3600*24*8))].'): '.number_format($arr_result[date($dateformat,$to_ts-3600*24*8)],0);
		$val2 = number_format($arr_result[date($dateformat,$to_ts-3600*24*8)],0);
		$card[0] = '"'.$msg['yesterday'].'", "'.date($dateformat,$to_ts-3600*24).'", "'.number_format($arr_result[date($dateformat,$to_ts-3600*24)],0).'", "'.$line1.'", "'.$val1.'", "'.$line2.'", "'.$val2.'"';

		$line1 = $msg['comparingto'].$msg['adaybefore'].': '.number_format($arr_result[date($dateformat,$to_ts-3600*24)],0);
		$val1 = number_format($arr_result[date($dateformat,$to_ts-3600*24)],0);
		$line2 = $msg['comparingto'].$msg['lastweek'].'('.$msg[strtolower(date("D", $to_ts))].'): '.number_format($arr_result[date($dateformat,$to_ts-3600*24*7)],0);
		$val2 = number_format($arr_result[date($dateformat,$to_ts-3600*24*7)],0);
		$card[1] = '"'.$msg['today'].'", "'.date($dateformat,$to_ts).'", "'.number_format($arr_result[date($dateformat,$to_ts)],0).'", "'.$line1.'", "'.$val1.'", "'.$line2.'", "'.$val2.'"';
		
		$line1 = $msg['comparingto'].$msg['lastweek'].': '.number_format($total_last_week,0);
		$val1 = number_format($total_last_week,0);
		$line2 = $msg['comparingto'].$msg['2weeksbefore'].': '.number_format($total_week_before,0);
		$val2 = number_format($total_week_before,0);
		$card[2] = '"'.$msg['recent7days'].'", "'.date($dateformat,$to_ts-3600*24*7+1).' ~ '.date($dateformat,$to_ts).'", "'.number_format($total_this_week,0).'", "'.$line1.'", "'.$val1.'", "'.$line2.'", "'.$val2.'"';
		
		$line1 = $msg['comparingto'].$msg['today'].': ';//.number_format($max_visit['pre'],0);
		$line2 = $msg['comparingto'].$msg['yesterday'].': ';//.number_format($max_visit['post'],0);
		$card[3] = '"'.$msg['maxvisitday'].'", "'.$max_visit['date'].'", "'.number_format($max_visit['count'],0).'", "'.$line1.'", "'.$val1.'", "'.$line2.'", "'.$val2.'"';
		
		$json_str= '{
			"data":['.$s_str.'],
			"label":['.$l_str.'],
			"title":{
				"chart_title":'.$chart_title.'
			},
			"card":[
				['.$card[0].'],['.$card[1].'],['.$card[2].'],['.$card[3].']
			]
		}';
	}
	
	else if($_GET['page'] == 'ageGender') {
		$s1 = microtime(true);
		$age_range = json_decode($configVars['MISC']['AGE_GROUP']);
		if(!$age_range) {
			$age_range = [0,18,30,45,65];
		}	
		$gender_div = array($msg['male'], $msg['female']);
		
		$from_ts = strtotime($_GET['time_ref']) - 3600*24*6;
		$to_ts = $from_ts + 3600*24*7 -1;
		$dateformat = "Y-m-d";
//		print date("Y-m-d H:i:s", $from_ts).'~'.date("Y-m-d H:i:s", $to_ts);
		
		$tag_div = array('age_1st','age_2nd','age_3rd','age_4th','age_5th','male','female');
		$title_div = array($msg['~17'],$msg['18~29'],$msg['30~44'],$msg['45~64'],$msg['65~'],$msg['male'], $msg['female']);	

		$age_query_string = '';
		for($i=0; $i<100; $i++) {
			if($i>0) {
				$age_query_string .= ", ";
			}
			$age_query_string .= "sum(substring_index(substring_index(age, ',', ".($i+1)."),',',-1))";
			if($i<99) {
				$age_query_string .= ",','";
			}
		}
		$age_query_string  = "concat(".$age_query_string.") as age";
		
		$gender_query_string = "sum(substring_index(substring_index(gender, ',', 1),',',-1)), ',', sum(substring_index(substring_index(gender, ',', 2),',',-1))" ;
		$gender_query_string = "concat(".$gender_query_string.") as gender";
		
		$sq =  "select square_code, store_code, camera_code, year, month, day, hour, min, ".$age_query_string.", ".$gender_query_string." from ".$DB_CUSTOM['age_gender']." ";
		$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." ";
		
		if($_GET['st'] ) {
			$sq .= "and store_code ='".$_GET['st']."' ";
		}
		if($_GET['sq'] ) {
			$sq .= "and square_code ='".$_GET['sq']."' ";
		}			
		
		$rs = mysqli_query($connect, $sq);
		$assoc = mysqli_fetch_assoc($rs);
		
//		print_arr($assoc);
		$arr_result['total']['age'] = age_group(json_decode('['.$assoc['age'].']'), $age_range);
		$arr_result['total']['gender'] = json_decode('['.$assoc['gender'].']');		

		for($i=0; $i <sizeof($age_range); $i++) {
			if(!$arr_result['total']['age'][$i]) {
				$arr_result['total']['age'][$i] = 0;
			}
			if($i>0) {
				$s_str .= ',';
			}
			$s_str .= '{"name":"'.$msg['agegroup'.$i].'", "total":'.$arr_result['total']['age'][$i].'}';
		}
		for($i=0; $i <2; $i++) {
			if(!$arr_result['total']['gender'][$i]) {
				$arr_result['total']['gender'][$i] = 0;
			}
			$s_str .= ',';
			$s_str .= '{"name":"'.$gender_div[$i].'", "total":'.$arr_result['total']['gender'][$i].'}';
		}
		
		$chart_title = '["'.$msg['agegroup'].'", "'.$msg['gender'].'"]';
		
		if(!$chart_title) {
			$chart_title = '""';
		}
		$json_str= '{
			"data":['.$s_str.'],
			"title":{
				"chart_title":'.$chart_title.'
			}
		}';
	}
}

else if ($_GET['fr'] == 'standard') {
//	print_arr($_GET);
	$msg = q_language("standard.php");
	if($_GET['page'] == 'footfall_rising_rank') {
		$to_ts = strtotime($_GET['time_ref']) + 3600*24*(6-date("w",strtotime($_GET['time_ref'])))+ 3600*24 -1;
		$from_ts = $to_ts - 3600*24*21 +1;

		$dateformat = "Y-m-d";
		$duration = 21;
		$interval = 3600*24;
	
		$arr_footfall_label = array($msg['2weeksbefore'], $msg['lastweek'], $msg['thisweek']);
		
		$sq  = "select device_info, square_code, store_code, year, month, day, hour, min, wday, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
		$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." and counter_label='entrance' ";

		if($_GET['st'] ) {
			$sq .= "and store_code ='".$_GET['st']."' ";
		}
		if($_GET['sq'] ) {
			$sq .= "and square_code ='".$_GET['sq']."' ";
		}	

		$sq .= "group by year, month, day ";	
		$sq .= " order by timestamp asc ";
		
	//	print $sq;
	//	print Query2Table($connect, $sq);
		$rs = mysqli_query($connect, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
			$arr_result[$datetime] = $assoc['sum'];
		}
		
		for($i=0; $i<3; $i++) {
			$d_str = '';
			for($j=0; $j<7; $j++) {
				$datetimest = $from_ts + ($i*7 + $j)*$interval;
				$datetime = date($dateformat, $datetimest);
				if($i==2) {
					if($j>0) {
						$l_str .= ',';
					}
//					$l_str .= '["'.$msg[strtolower(date("D", $datetimest))].'", "'.date("m-d", $datetimest).'"]';
					$l_str .= '"'.$msg[strtolower(date("D", $datetimest))].'"';
				}
				if(!$arr_result[$datetime]) {
					$arr_result[$datetime]= 0;
				}
				if($datetimest > $thistime) {
					$arr_result[$datetime]= 'null';
				}				
				if($j>0) {
					$d_str .= ',';
				}
				$d_str .= $arr_result[$datetime];
			}
			if($i>0) {
				$s_str .= ',';
			}
			$s_str .= '{"name":"'.$arr_footfall_label[$i].'", "data":['.$d_str.']}'; 
		}
		
		$chart_title = $msg['7dayscomparison'];
//		print_arr($arr_result);
	}
	else if($_GET['page'] == 'footfall_hourly') {
		$from_ts = strtotime($_GET['time_ref']) - 3600*24*7;
		$to_ts = strtotime($_GET['time_ref'])  + 3600*24*7 -1;
		$dateformat = "Y-m-d H:00";
		$interval = 3600;
	
		$arr_footfall_label = array($msg['lastweek'], $msg['thisweek']);
		
		$sq  = "select device_info, square_code, store_code, year, month, day, hour, min, wday, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
		$sq .= "where ((timestamp >=".$from_ts." and timestamp <".$to_ts.") or (timestamp >=".($from_ts+3600*24*7)." and timestamp <".($to_ts+3600*24*7)."))  and counter_label='entrance' ";

		if($_GET['st'] ) {
			$sq .= "and store_code ='".$_GET['st']."' ";
		}
		if($_GET['sq'] ) {
			$sq .= "and square_code ='".$_GET['sq']."' ";
		}	

		$sq .= "group by year, month, day, hour ";	
		$sq .=" order by timestamp asc ";
		
	//	print $sq;
	//	print Query2Table($connect, $sq);
		$rs = mysqli_query($connect, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
			$arr_result[$datetime] = $assoc['sum'];
		}
		
		for($i=0; $i<2; $i++) {
			$d_str = '';
			for($j=0; $j<24; $j++) {
				$datetimest = $from_ts + ($i*7*24 + $j)*$interval;
				$datetime = date($dateformat, $datetimest);
				if($i==0) {
					if($j>0) {
						$l_str .= ',';
					}
					$l_str .= $datetimest;
//					$l_str .= '"'.date("H:00", $datetimest).'"';
				}
				if(!$arr_result[$datetime]) {
					$arr_result[$datetime]= 0;
				}
				if($datetimest > $thistime) {
					$arr_result[$datetime]= 'null';
				}
				if($j>0) {
					$d_str .= ',';
				}
				$d_str .= $arr_result[$datetime]; 
			}
			if($i>0) {
				$s_str .= ',';
			}
			$s_str .= '{"name":"'.$arr_footfall_label[$i].'('.date("Y-m-d", $from_ts+ $i*7*3600*24).')", "data":['.$d_str.']}'; 
		}
		
		$chart_title = $msg['7dayscomparison'];
//		print_arr($arr_result);
		
		
	}
	else if($_GET['page'] == 'footfall_device') {
		$from_ts = strtotime($_GET['time_ref']);
		$to_ts = strtotime($_GET['time_ref'])  + 3600*24 -1;
		$dateformat = "Y-m-d H:00";
		$interval = 3600;
		$duration = ceil(($to_ts-$from_ts)/$interval);
//		print date("Y-m-d H:i:s", $from_ts).'~'.date("Y-m-d H:i:s", $to_ts);
		$sq  = "select device_info, square_code, store_code, year, month, day, hour, min, wday, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
		$sq .= "where ((timestamp >=".$from_ts." and timestamp <".$to_ts.") or (timestamp >=".($from_ts+3600*24*7)." and timestamp <".($to_ts+3600*24*7)."))  and counter_label='entrance' ";
		if($_GET['st'] ) {
			$sq .= "and store_code ='".$_GET['st']."' ";
		}
		if($_GET['sq'] ) {
			$sq .= "and square_code ='".$_GET['sq']."' ";
		}	

		$sq .= "group by year, month, day, hour, device_info ";	
		$sq .=" order by timestamp asc ";
//		print $sq;
//		print Query2Table($connect, $sq);
		$arr_device =  array();
		$rs = mysqli_query($connect, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			$datetimest =  mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']);
			$datetime = date($dateformat,$datetimest);
			$arr_result[$datetime][$assoc['device_info']] = $assoc['sum'];
			if(!in_array($assoc['device_info'], $arr_device)) {
				array_push($arr_device, $assoc['device_info']);
			}
		}
//		print_arr($arr_device);
		for($i=0; $i<sizeof($arr_device); $i++) {
			$d_str = '';
			for($j=0; $j<$duration; $j++) {	
				$datetimest = $from_ts + $j*$interval;
				$datetime = date($dateformat, $datetimest);
				if($i==0) {
					if($j>0) {
						$l_str .= ',';
					}
//					$l_str .= '"'.date("H:00", $datetimest).'"';
					$l_str .= $datetimest;
				}				
				if(!$arr_result[$datetime][$arr_device[$i]]) {
					$arr_result[$datetime][$arr_device[$i]] =0;
				}
				if($datetimest > $thistime) {
					$arr_result[$datetime][$arr_device[$i]] = 'null';
				}
				if($j>0) {
					$d_str .= ',';
				}
				$d_str .= $arr_result[$datetime][$arr_device[$i]];
			}
			if($i>0) {
				$s_str .= ',';
			}
			$mac = array_pop(explode("=",explode("&", $arr_device[$i])[0]));
			$sq = "select name from ".$DB_CUSTOM['camera']." where mac = '".$mac."' ";
			$dev_name = mysqli_fetch_row(mysqli_query($connect, $sq))[0];
			$dev_name .= '['.$mac.']';
			$s_str .= '{"name":"'.$dev_name.'", "data":['.$d_str.']}';
		}
		
//		print_arr($arr_result);	
	}
	$json_str = '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"chart_title":"'.$chart_title.'"
		}
	}';
	
}

else if ($_GET['fr'] == 'premium') {
//	print_arr($_GET);
	$msg = q_language("standard.php");

	$from_ts = strtotime($_GET['time_ref']) -3600*24*83;
	$to_ts = strtotime($_GET['time_ref']) + 3600*24 -1;
	
	$dateformat = "Y-m-d";
	$interval = 3600*24;
	$duration = ceil(($to_ts - $from_ts)/$interval);

	$arr_footfall_label = array($msg['2weeksbefore'], $msg['lastweek'], $msg['thisweek']);
	
//		print date("Y-m-d H:i:s D", $from_ts).'~'.date("Y-m-d H:i:s D", $to_ts);

	if($_GET['page'] == 'footfall') {
		
	}
	else if($_GET['page'] == 'footfall_square') {
		$div = 'square_code';
		$table_tag = $DB_CUSTOM['square'];
	}
	else if($_GET['page'] == 'footfall_store') {
		$div = 'store_code';
		$table_tag = $DB_CUSTOM['store'];
	}

	else if($_GET['page'] == 'footfall_device') {
		$div = 'camera_code';
		$table_tag = $DB_CUSTOM['camera'];
	}

	$sq  = "select device_info, square_code, store_code, camera_code, year, month, day, hour, min, wday, sum(counter_val) as sum from ".$DB_CUSTOM['count']." ";
	$sq .= "where timestamp >=".$from_ts." and timestamp <".$to_ts." and counter_label='entrance' ";

	if($_GET['st'] ) {
		$sq .= "and store_code ='".$_GET['st']."' ";
	}
	if($_GET['sq'] ) {
		$sq .= "and square_code ='".$_GET['sq']."' ";
	}	

	$sq .= "group by year, month, day ";
	if($div) {
		$sq.= ",".$div." ";	
	}
	$sq .= "order by timestamp asc ";
	
//		print $sq;
//		print Query2Table($connect, $sq);
	$arr_div =  array();
	$rs = mysqli_query($connect, $sq);
	for($i=0; $i<($rs->num_rows); $i++) {
		$assoc = mysqli_fetch_assoc($rs);
		$datetime = date($dateformat, mktime($assoc['hour'],$assoc['min'],0,$assoc['month'], $assoc['day'], $assoc['year']));
		if(!in_array($assoc[$div], $arr_div)) {
			array_push($arr_div, $assoc[$div]);
		}
		$arr_result[$datetime][$assoc[$div]] = $assoc['sum'];
	}
	$arr_div_tag = array();
	for($i=0; $i<sizeof($arr_div); $i++) {
		$sq = "select name from ".$table_tag." where code = '".$arr_div[$i]."' ";
		$rs = mysqli_query($connect, $sq);
		$arr_div_tag[$i] = mysqli_fetch_row($rs)[0];
	}
	if(sizeof($arr_div_tag) == 1) {
		$arr_div_tag[0] = $msg['total'];
	}
	
	for($i=0; $i<sizeof($arr_div); $i++) {
		$d_str = '';
		for($j=0; $j<$duration; $j++) {
			$datetimest = $from_ts + $j*$interval;
			$datetime = date($dateformat, $datetimest);
			if($i == 0) {
				if($j>0) {
					$l_str .= ',';
				}
				$l_str .= $datetimest;
			}
			if(!$arr_result[$datetime][$arr_div[$i]]) {
				$arr_result[$datetime][$arr_div[$i]] = 0;
			}
			if($datetimest>$thistime) {
				$arr_result[$datetime][$arr_div[$i]] = '"NaN';
			}
			if($j>0) {
				$d_str .= ',';
			}
			$d_str .= $arr_result[$datetime][$arr_div[$i]];
		
		}
		if($i>0) {
			$s_str .= ',';
		}
		$s_str .= '{"name":"'.$arr_div_tag[$i].'", "data":['.$d_str.']}';
	}
//		print_arr($arr_result);
	

	
	
	$json_str = '{
		"data":['.$s_str.'],
		"label":['.$l_str.'],
		"title":{
			"chart_title":"'.$chart_title.'"
		}
	}';
	
}
	
else if ($_GET['fr'] == 'sensors') {
	$msg = q_language("sensors.php");
	
	if($_GET['act'] == 'info') {
		$tag_square = '&#9109;';
		$tag_check = '&#10004;';
		$tag_square = '<i class="align-middle fas fa-fw fa-1x fa-expand"></i>';
		$tag_check  = '<i class="align-middle fas fa-fw fa-1x fa-check"></i>';
		
		$sq  = "select A.code, A.store_code, A.square_code, A.usn, A.product_id, A.name, A.comment, if(A.enable_countingline='y', '".$tag_check."','".$tag_square."') as enable_countingline, if(A.enable_heatmap='y', '".$tag_check."','".$tag_square."') as enable_heatmap, if(A.enable_snapshot='y', '".$tag_check."','".$tag_square."') as enable_snapshot, if(A.enable_face_det='y', '".$tag_check."','".$tag_square."') as enable_face_det, if(A.enable_macsniff='y', '".$tag_check."','".$tag_square."') as enable_macsniff, A.flag, A.device_info, B.pk as fpk , concat(if(B.lic_pro='y','PRO',''),' ', if(B.lic_surv='y','SURV',''),' ', if(B.lic_count='y','COUNT','')) as license, if(B.face_det='y', '".$tag_check."','".$tag_square."') as face_det, if(B.heatmap='y', '".$tag_check."','".$tag_square."') as heatmap, if(B.countrpt='y', '".$tag_check."','".$tag_square."') as countrpt, if(B.macsniff='y', '".$tag_check."','".$tag_square."') as macsniff, B.initial_access, B.last_access, B.db_name, B.param, C.body as snapshot, D.name as store_name from ".$DB_CUSTOM['camera']." as A inner join common.".$DB_COMMON['param']." as B inner join common.".$DB_COMMON['snapshot']." as C inner join ".$DB_CUSTOM['store']." as D on A.device_info = B.device_info and A.device_info = C.device_info and A.store_code = D.code where A.pk=".$_GET['pk'];

		$rs = mysqli_query($connect, $sq);
		$arr_result = mysqli_fetch_assoc($rs);
		$ex = explode("&", $arr_result['device_info']);
		foreach ($ex as $A=>$B) {
			list($_key, $_val) = explode("=", $B);
			$arr_result[$_key] = $_val;
		}
		
		$zone = array();
		$line = explode("\n",$arr_result['param']);
		for($i=0; $i<count($line)-1; $i++) {
			if(strpos(" ".$line[$i], "VCA.Ch0.Zn")) {
				list($key,$val) = explode("=", $line[$i]);
				$ex_key = explode(".",$key);
				$p = substr($ex_key[2],2,strlen($ex_key[2]));
				$zone[$p][$ex_key[3]] = trim($val);
			}
		}
		
		foreach($zone as $idx => $A) {
			$z_str = '';
			foreach($A as $key =>$val) {
				if($z_str) {
					$z_str.= ',';
				}
				$z_str .= '"'.$key.'":"'.$val.'"';
			}
			if($zone_str) {
				$zone_str .= ',';
			}
			$zone_str .= '{'.$z_str.'}';
		}
//		print $zone_str;
		
	
		$arr_result['functions'] = '"'.addslashes($arr_result['countrpt']).'", "'.addslashes($arr_result['heatmap']).'", "'.addslashes($arr_result['face_det']).'", "'.addslashes($arr_result['macsniff']).'"';
		$arr_result['features'] = '"'.addslashes($arr_result['enable_countingline']).'", "'.addslashes($arr_result['enable_heatmap']).'", "'.addslashes($arr_result['enable_face_det']).'", "'.addslashes($arr_result['enable_macsniff']).'"';
		
//		print_arr($zone);
//		$zone_str = draw_zone($zone, $arr_result['snapshot'], 'width=800; height=450');		
		
		$json_str= '{
			"info":{
				"zone":['.$zone_str.'],
				"device_info": "'.$arr_result['device_info'].'", 
				"code": "'.$arr_result['code'].'", 
				"regdate":"'.$arr_result['regdate'].'", 
				"name":"'.$arr_result['name'].'",
				"square_name":"'.$arr_result['square_name'].'",
				"store_name":"'.$arr_result['store_name'].'",
				"snapshot":"'.$arr_result['snapshot'].'",
				"mac":"'.$arr_result['mac'].'",
				"brand":"'.$arr_result['brand'].'",
				"model":"'.$arr_result['model'].'",
				"usn":"'.$arr_result['mac'].'",
				"product_id":"'.$arr_result['product_id'].'",
				"initial_access":"'.$arr_result['initial_access'].'",
				"last_access":"'.$arr_result['last_access'].'",
				"license":"'.$arr_result['license'].'",
				"functions":['.$arr_result['functions'].'],
				"features":['.$arr_result['features'].'],
				"comment":"'.$arr_result['comment'].'"
			}
		}';

	}
	else {	
		$sq = "select A.pk, A.code, A.store_code, A.square_code, A.device_info, A.usn, A.product_id, A.name, A.comment, B.body as snapshot, B.regdate, C.name as store_name, D.name as square_name from ".$DB_CUSTOM['camera']." as A inner join common.".$DB_COMMON['snapshot']." as B  inner join ".$DB_CUSTOM['store']." as C inner join ".$DB_CUSTOM['square']." as D on A.device_info = B.device_info and A.store_code = C.code and A.square_code = D.code ";
		
		if($_GET['st'] ) {
			$sq .= " and  A.store_code='".$_GET['st']."'";
		}	
		else if($_GET['sq'] ) {
			$sq .= " and A.square_code='".$_GET['sq']."'";
		}
	//	print $sq;

		$rs = mysqli_query($connect, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$row = mysqli_fetch_assoc($rs);
			if($i>0) {
				$body_str .= ',';
			}
			$body_str .= '{
				"device_info": "'.$row['device_info'].'", 
				"regdate":"'.$row['regdate'].'", 
				"name":"'.$row['name'].'",
				"square_name":"'.$row['square_name'].'",
				"store_name":"'.$row['store_name'].'",
				"snapshot":"'.$row['snapshot'].'",
				"pk":"'.$row['pk'].'"
			}';
		}
		$json_str= '{
			"list":['.$body_str.'],
			"lang": {
				"store name":"'.$msg['storename'].'",
				"square name":"'.$msg['squarename'].'",
				"device info":"'.$msg['deviceinfo'].'",
				"memo":"'.$msg['memo'].'",
				"detail":"'.$msg['detail'].'"
			}
		}';
	}	
	
}

else if ($_GET['fr'] == 'sitemap') {
	$arr_square =  array();
	$arr_store = array();
	$arr_camera = array();
	$arr_list = array();
	$cam_num = array();
	$sq = "select * from ".$DB_CUSTOM['square']." ";
	$arr_square = Query2Array($connect,$sq);


	for($i=0; $i<sizeof($arr_square); $i++) {
		$sq = "select * from ".$DB_CUSTOM['store']." where square_code='".$arr_square[$i]['code']."'";
		$arr_store = Query2Array($connect,$sq);
		if(!sizeof($arr_store)) {
			$arr_store[0]['name'] ='';
		}
		for($j=0; $j<sizeof($arr_store); $j++) {
			$sq = "select * from ".$DB_CUSTOM['camera']." where store_code = '".$arr_store[$j]['code']."'";
			$num = mysqli_query($connect, $sq)->num_rows;
			if(!$num) {
				$num = 1;
			}
			$cam_num[$i] +=$num;
		}

		
		for($j=0; $j<sizeof($arr_store); $j++) {
			$sq = "select * from ".$DB_CUSTOM['camera']." where store_code = '".$arr_store[$j]['code']."'";
			$arr_camera = Query2Array($connect, $sq);
//			print_arr($arr_camera);
			if(!sizeof($arr_camera)) {
				$arr_camera[0]['name'] ='';
			}
			for($k=0; $k<sizeof($arr_camera); $k++) {
				$sq = "select body from common.".$DB_COMMON['snapshot']." where device_info = '".$arr_camera[$k]['device_info']."' ";
				// print $sq;
				$row = mysqli_fetch_row(mysqli_query($connect0, $sq));
				if($row[0]) {
					$img_b64 = '<img src="'.$row[0].'" height="50px" width="89px" data-toggle="modal" data-target="#modalSnapshot" OnClick="viewSnapshot(this)" onMouseOver="this.style.cursor=\'pointer\'">';
				}
				else {
					$img_b64 = '';
				}
				
				$sq = "select usn, lic_pro, lic_surv, lic_count, face_det, heatmap, countrpt, macsniff  from ".$DB_COMMON['param']." where device_info = '".$arr_camera[$k]['device_info']."' ";
				$assoc = mysqli_fetch_assoc(mysqli_query($connect0, $sq));
				
				
				$tbody .= '<tr>';
				if($j==0 and $k==0) {
					$tbody .= '<td rowspan="'.($cam_num[$i]).'">'.$arr_square[$i]['name'].'</td>';
				}
				if($k==0) {
					$tbody .= '<td rowspan="'.(sizeof($arr_camera)).'">'.$arr_store[$j]['name'].'</td>';
				}

				$tbody .= '<td style="padding-top:1px; padding-bottom:1px;" width="100px">'.$img_b64.'</td>';
				$tbody .= '<td>'.$arr_camera[$k]['name'].'</td>';
				$arr_camera[$k]['enable_countingline'] = ($arr_camera[$k]['enable_countingline'] == 'y' ) ? '<i class="align-middle fas fa-fw fa-1x fa-check"></i>' :'';
				$arr_camera[$k]['enable_heatmap'] = $arr_camera[$k]['enable_heatmap'] == 'y' ? '<i class="align-middle fas fa-fw fa-1x fa-check"></i>' :'';
				$arr_camera[$k]['enable_face_det'] = $arr_camera[$k]['enable_face_det'] == 'y' ? '<i class="align-middle fas fa-fw fa-1x fa-check"></i>' :'';
				$arr_camera[$k]['enable_macsniff'] = $arr_camera[$k]['enable_macsniff'] == 'y' ? '<i class="align-middle fas fa-fw fa-1x fa-check"></i>' :'';
				$tbody .= '<td align="center">'.$arr_camera[$k]['enable_countingline'].'</td>';
				$tbody .= '<td align="center">'.$arr_camera[$k]['enable_heatmap'].'</td>';
				$tbody .= '<td align="center">'.$arr_camera[$k]['enable_face_det'].'</td>';
				$tbody .= '<td align="center">'.$arr_camera[$k]['enable_macsniff'].'</td>';
				$tbody .= '</tr>';
			}
		}
	}

	$json_str= '{"tbody": "'.addslashes($tbody).'" }';
	$json_str = $tbody;
	
}

#########################################  ADMIN PAGE #################################################################################
else if ($_GET['fr'] == 'square') {
	if($_GET['mode'] == 'modify') {
		if(!$_POST['pk']) { 
			$regdate = date("Y-m-d H:i:s"); 
			$sq = "insert into ".$DB_CUSTOM['square']."(regdate) values('".$regdate."')";
			$rs = mysqli_query($connect, $sq);
			$sq = "select pk from ".$DB_CUSTOM['square']." where regdate = '".$regdate."'";
			$rs = mysqli_query($connect, $sq);
			$_POST['pk']  = mysqli_fetch_row($rs)[0];
		}
		$sq = "update ".$DB_CUSTOM['square']." set code = '".trim($_POST['code'])."',  name = '".addslashes(trim($_POST['name']))."', comment = '".addslashes(trim($_POST['comment']))."', addr_state = '".addslashes(trim($_POST['addr_state']))."', addr_city = '".addslashes(trim($_POST['addr_city']))."', addr_b = '".addslashes(trim($_POST['addr_b']))."' ";
		$sq .="where pk=".$_POST['pk'];
		// print $sq;
		$rs = mysqli_query($connect, $sq);
		if($rs) {
			print "update OK";
		}
		else {
			print "update Fail";
		}		
	}
	else if($_GET['mode'] == 'delete') {
		$sq = "select ID from ".$DB_COMMON['account']." where ID = '".$_SESSION['logID']."' and (role='admin' or role='root') and passwd = '".$_POST['passwd']."' ";
		$rs = mysqli_query($connect0, $sq);
		if($rs->num_rows) {
			$sq = "select code from ".$DB_CUSTOM['store']." where pk=".$_GET['pk'];
			$rs = mysqli_query($connect, $sq);
			$sq_code = mysqli_fetch_row($rs)[0];
			$sq = "select code from ".$DB_CUSTOM['store']." where store_code = '".$sq_code."' ";
			$rs = mysqli_query($connect, $sq);
			if($rs->num_rows) {
				print "This square has Store(s)";
			}
			else {
				$sq = "delete from ".$DB_CUSTOM['square']." where pk=".$_GET['pk'];
				print $sq;
				$rs = mysqli_query($connect, $sq);
				if($rs) {
					print "square ".$sq_code." delete OK";
				}
			}
		}
		else {
			print "No right to delete or password not match!";
		}		
	}	
	
	else if($_GET['mode'] == 'view') {
		$sq = "select pk, code, name, addr_state, addr_city, addr_b, comment from ".$DB_CUSTOM['square']." where pk = ".$_GET['pk'];
		// print $sq;
		$rs = mysqli_query($connect, $sq);
		if(!$rs) {
			print '{"info":"Error in query"}';
			exit;
		}
		$arr_result = mysqli_fetch_assoc($rs);
		if(!$arr_result['code']) {
			$arr_result['code'] = 'SQ'.time().rand(0,9).rand(0,9).rand(0,9);
		}
		// print_arr($arr_result);
		$json_str = json_encode($arr_result);
	}
}

else if ($_GET['fr'] == 'store') {
	if($_GET['mode'] =='modify') {
		// print_r($_POST);
		if(!$_POST['pk']) { 
			$regdate = date("Y-m-d H:i:s"); 
			$sq = "insert into ".$DB_CUSTOM['store']."(regdate) values('".$regdate."')";
			$rs = mysqli_query($connect, $sq);
			$sq = "select pk from ".$DB_CUSTOM['store']." where regdate = '".$regdate."'";
			$rs = mysqli_query($connect, $sq);
			$_POST['pk'] = mysqli_fetch_row($rs)[0];
		}
		
		if(!is_numeric($_POST['area'])) {
			$_POST['area'] = 0;
		}

		$sq = "update ".$DB_CUSTOM['store']." set code = '".trim($_POST['code'])."', name = '".addslashes(trim($_POST['name']))."', comment = '".addslashes(trim($_POST['comment']))."', addr_state = '".addslashes(trim($_POST['addr_state']))."', addr_city = '".addslashes(trim($_POST['addr_city']))."', addr_b = '".addslashes(trim($_POST['addr_b']))."', square_code = '".trim($_POST['square_code'])."', phone = '".addslashes(trim($_POST['phone']))."', fax = '".addslashes(trim($_POST['fax']))."', contact_person = '".addslashes(trim($_POST['contact_person']))."', contact_tel = '".addslashes(trim($_POST['contact_tel']))."', open_hour = ".trim($_POST['open_hour']).", close_hour = ".trim($_POST['close_hour']).", sniffing_mac = '".trim($_POST['sniffing_mac'])."', area = ".$_POST['area']." where pk=".$_POST['pk'];
		// print $sq; 
		$rs = mysqli_query($connect, $sq);
		if($rs) {
			print "update OK";
		}
		else {
			print "update Fail";
		}	
	}
	else if($_GET['mode'] == 'delete') {
		$sq = "select ID from ".$DB_COMMON['account']." where ID = '".$_SESSION['logID']."' and (role='admin' or role='root') and passwd = '".$_POST['passwd']."' ";
		$rs = mysqli_query($connect0, $sq);
		if($rs->num_rows) {
			$sq = "select code from ".$DB_CUSTOM['store']." where pk=".$_GET['pk'];
			$st_code = mysqli_fetch_row(mysqli_query($connect, $sq))[0];
			$sq = "select code from ".$DB_CUSTOM['camera']." where store_code = '".$st_code."' ";
			$rs = mysqli_query($connect, $sq);
			if($rs->num_rows) {
				print "This store has Camera(s)";
			}
			else {
				$sq = "delete from ".$DB_CUSTOM['store']." where pk=".$_GET['pk'];
				print $sq;
				$rs = mysqli_query($connect, $sq);
				if($rs) {
					print "store ".$st_code." delete OK";
				}
			}
		}
		else {
			print "No right to delete or password not match!";
		}		
	}

	else if($_GET['mode'] =='view') {
		$sq = "select pk, code, square_code, name, addr_state, addr_city, addr_b, phone, fax, contact_person, contact_tel, open_hour, close_hour, comment, sniffing_mac, area from ".$DB_CUSTOM['store']." where pk = ".$_GET['pk'];
		$rs = mysqli_query($connect, $sq); 
		if(!$rs) {
			print '{"info":"Error in query"}';
			exit;
		}
		$arr_result = mysqli_fetch_assoc($rs);
		if(!$arr_result['code']) {
			$arr_result['code'] = 'ST'.time().rand(0,9).rand(0,9).rand(0,9);
			$sq = "select code from ".$DB_CUSTOM['square']." where pk=".$_GET['sqpk'];
			// print $sq;
			$arr_result['square_code'] = mysqli_fetch_row(mysqli_query($connect, $sq))[0];
		}
		// print_arr($arr_result);
		$json_str = json_encode($arr_result);

	}
}

else if ($_GET['fr'] == 'camera') {	
	if($_GET['mode'] == 'modify') {
		print "<pre>"; 	print_r($_POST);	print "</pre>";
		$device_info = "mac=".trim($_POST['mac'])."&brand=".trim($_POST['brand'])."&model=".trim($_POST['model']);
		$sq = "select store_code from  ".$DB_CUSTOM['camera']." where pk=".$_GET['pk'];
		$old_store_code = mysqli_fetch_row(mysqli_query($connect, $sq))[0];

		$sq = "update ".$_SESSION['db_name'].".".$DB_CUSTOM['camera']." set name='".addslashes(trim($_POST['name']))."', device_info = '".$device_info."', mac='".trim($_POST['mac'])."', usn='".trim($_POST['usn'])."',  model='".trim($_POST['model'])."', brand='".trim($_POST['brand'])."', product_id='".trim($_POST['product_id'])."', enable_countingline='".($_POST['enable_countingline']=='true'?'y':'n')."', enable_heatmap='".($_POST['enable_heatmap']=='true'?'y':'n')."', enable_face_det='".($_POST['enable_face_det']=='true'?'y':'n')."', enable_macsniff='".($_POST['enable_macsniff']=='true'?'y':'n')."', flag='".($_POST['flag']=='true'?'y':'n')."', comment='".addslashes(trim($_POST['comment']))."' where pk=".$_GET['pk'];
		// print $sq;
		$rs = mysqli_query($connect, $sq);
		if($rs) {
			print "Update OK";
		}
		else {
			print "Fail";
		}

		if($old_store_code != $_POST['store_code']) {
			$sq = "update ".$DB_CUSTOM['camera']." set store_code='".trim($_POST['store_code'])."' where pk=".$_GET['pk'];
			$rs = mysqli_query($connect, $sq);
			if(!$rs) {
				print "FAIL";
			}		
			
			$sq = "update ".$DB_CUSTOM['count']." set  square_code = '".$_POST['square_code']."', store_code = '".$_POST['store_code']."', camera_code='".$_POST['code']."'  where device_info = '".$device_info."' ";
			$rs = mysqli_query($connect, $sq);
			if(!$rs) {
				print "FAIL";
			}		
			$sq = "update ".$DB_CUSTOM['heatmap']." set  square_code = '".$_POST['square_code']."', store_code = '".$_POST['store_code']."', camera_code='".$_POST['code']."'  where device_info = '".$device_info."' ";
			$rs = mysqli_query($connect, $sq);
			if(!$rs) {
				print "FAIL";
			}	
			$sq = "update ".$DB_CUSTOM['age_gender']." set  square_code = '".$_POST['square_code']."', store_code = '".$_POST['store_code']."', camera_code='".$_POST['code']."'  where device_info = '".$device_info."' ";
			$rs = mysqli_query($connect, $sq);
			if(!$rs) {
				print "FAIL";
			}	
		}
		if( ($_POST['enable_countingline']=='true') and $_POST['ct_labels']) {
			for($i=0; $i< sizeof($_POST['ct_labels']); $i++) {
				$sq = "select pk from ".$DB_CUSTOM['counter_label']." where camera_code='".$_POST['code']."' and counter_name = '".$_POST['ct_names'][$i]."'";
				$rs = mysqli_query($connect, $sq);
				if($rs->num_rows) {
					$sq = " update ".$DB_CUSTOM['counter_label']." set counter_label = '".trim($_POST['ct_labels'][$i])."' where camera_code='".$_POST['code']."' and counter_name = '".trim($_POST['ct_names'][$i])."'";
				}
				else {
					$sq = "insert into ".$DB_CUSTOM['counter_label']."(camera_code, counter_name, counter_label) values('".$_POST['code']."', '".trim($_POST['ct_names'][$i])."', '".trim($_POST['ct_labels'][$i])."')";
				}				
				print ($sq);
				$rs = mysqli_query($connect, $sq);
				if($rs) {
					print "\t Counter Label Update OK";
				}
			}
		}		
	}

	else if($_GET['mode'] == 'delete') {
		$sq = "select ID from ".$DB_COMMON['account']." where ID = '".$_SESSION['logID']."' and (role='admin' or role='root') and passwd = '".$_POST['passwd']."' ";
		$rs = mysqli_query($connect0, $sq);
		if($rs->num_rows) {
			$sq = "select device_info, code as camera_code from ".$DB_CUSTOM['camera']." where pk=".$_GET['pk'];
			$assoc = mysqli_fetch_assoc(mysqli_query($connect, $sq));
			$sq = "update common.".$DB_COMMON['param']." set db_name='none' where device_info = '".$assoc['device_info']."' ";
			$rs1 = mysqli_query($connect0, $sq);
			if($rs1) {
				print "Camera ".$assoc['device_info']." moves to db[none]";
			}			
			$sq = "delete from ".$DB_CUSTOM['camera']." where pk=".$_GET['pk'];
			$rs2 = mysqli_query($connect, $sq);
			if($rs2) {
				print "<br>Camera ".$assoc['device_info']." deleted";
			}
			$sq = "delete from ".$DB_CUSTOM['counter_label']." where camera_code = '".$assoc['camera_code']."' ";
			$rs3 = mysqli_query($connect, $sq);
			if($rs3) {
				print "<br>Counter label for  ".$assoc['device_info']." deleted";
			}
			if($rs1 && $rs2 && $rs3){
				print "<br>delete OK";
			}
		}
		else {
			print "no right to delete or password not match!";
		}
			
	}

	else if($_GET['mode'] == 'view') {
		// 
		$sq = "select A.pk as fpk, A.device_info, A.usn, A.product_id, A.lic_pro, A.lic_surv, A.lic_count, A.face_det, A.heatmap, A.countrpt, A.macsniff, A.param, A.initial_access, A.last_access, B.body as snapshot, B.regdate as regdate, C.pk as pk, C.code, C.name, C.store_code, C.enable_countingline, C.enable_heatmap, C.enable_face_det, C.enable_macsniff, C.flag from common.".$DB_COMMON['param']." as A inner join common.".$DB_COMMON['snapshot']." as B inner join ".$DB_CUSTOM['camera']." as C on A.device_info = B.device_info and A.device_info= C.device_info where C.pk=".$_GET['pk'];
		// print $sq;
		$rs = mysqli_query($connect, $sq);
		if(!$rs) {
			print  "No Record";
			exit;
		}		
		$arr_result = mysqli_fetch_assoc($rs);

		$arr_result['license'] = '';
		$exstr = explode("&", $arr_result['device_info']);
		$arr_result['mac'] = trim(array_pop(explode("=", $exstr[0])));
		$arr_result['brand'] = trim(array_pop(explode("=", $exstr[1])));
		$arr_result['model'] = trim(array_pop(explode("=", $exstr[2])));

		$arr_result['license'] = (($arr_result['lic_pro'] =='y')? "PRO":"")." ".(($arr_result['lic_surv'] =='y')? "SURV":"")." ".(($arr_result['lic_count'] =='y')? "COUNT":"");
		$arr_result['license'] = trim($arr_result['license']);
		$zone = array();
		$lines = explode("\n",$arr_result['param']);
		$i=0;
		foreach($lines as $line) {
			// print "\n".$line;
			if(preg_match('/(VCA.Ch0.Zn)[0-9]/', $line )) {
				list($key, $val) = explode("=", $line);
				$ex_key = explode(".",$key);
				// $p = substr($ex_key[2],2,strlen($ex_key[2]));
				
				// if($old_p != $p) {
				// 	$i++;		
				// 	$old_p = $p;
				// }
				if($zone[$i][$ex_key[3]]){
					$i++;
				}
				$zone[$i][$ex_key[3]] = trim($val);
			}
		}

		// print_r($zone);		exit;
		// for($i=0; $i<count($line)-1; $i++) {
		// 	list($key, $val) = explode("=", $line[$i]);
		// 	if(strpos(" ".$line[$i], "VCA.Ch0.Zn")) {
		// 		$ex_key = explode(".",$key);
		// 		$p = substr($ex_key[2],2,strlen($ex_key[2]));
		// 		$zone[$p][$ex_key[3]] = trim($val);
		// 	}
		// }

		$arr_result['zone'] = $zone;
		$sq = "select counter_name as name, counter_label as label, flag from ".$DB_CUSTOM['counter_label']." where camera_code = '".$arr_result['code']."' ";
		// print $sq;
		$rs = mysqli_query($connect, $sq);
		while ($assoc = mysqli_fetch_assoc($rs)) {
			$arr_result[$assoc['name']] = $assoc['label'];
		}


		unset($arr_result['param']);
		unset($arr_result['lic_pro']);
		unset($arr_result['lic_surv']);
		unset($arr_result['lic_count']);
		// print_r	($arr_result);
		$json_str = json_encode($arr_result);
		
	
	}
	else if($_GET['mode'] == 'list') {


	}

}
else if ($_GET['fr'] == 'floating_camera') {
	$msg = q_language('camera.php');
	if($_GET['mode'] == 'list') {
		$sq = "select A.*, B.body as snapshot, B.regdate as regdate from ".$DB_COMMON['param']." as A inner join ".$DB_COMMON['snapshot']." as B on A.device_info = B.device_info where db_name ='none' or db_name is null order by last_access desc ";
		$rs = mysqli_query($connect0, $sq);
		for($i=0; $i<($rs->num_rows); $i++) {
			$assoc = mysqli_fetch_assoc($rs);
			if(time() - strtotime($assoc['regdate']) <3600) {
				$assoc['regdate'] = '<span style="color:#00F">'.$assoc['regdate'].'</span>';
			}
			$str_body .='
			<div class="col-12 col-md-6 col-lg-6">
				<div class="card">
					<div class="card-header">
						<span class="float-right">'.$assoc['regdate'].'<br><span type="button" OnClick="addDeviceToStore(\''.$_GET['st_code'].'\',\''.$assoc['device_info'].'\')" class="btn btn-sm btn-primary float-right mt-2" >'.$msg['addtostore'].'</span></span>
						<h3 class="card-title mb-0"><b>'.str_replace("&","<br>",str_replace("=",": ",$assoc['device_info'])).'</b></h3>
					</div>
					<img class="card-img-top" src="'.$assoc['snapshot'].'"></img>		
				</div>
			</div>';		
		}
		$json_str = <<<EOPAGE
		<div class="col-12 col-md-12 col-lg-12" style="position:relative; ">
			<div class="row">
			$str_body
			</div>
		</div>
EOPAGE;
	}
	else if($_GET['mode'] == 'addToStore') {
		print_r($_GET);
		$device_info = 'mac='.$_GET['mac'].'&brand='.$_GET['brand'].'&model='.$_GET['model'];
		
		$sq = "select device_info, usn, product_id from ".$DB_COMMON['param']." where device_info='".$device_info."' ";
		$rs = mysqli_query($connect0, $sq);
		$assoc = mysqli_fetch_assoc($rs);
		
		$sq = "select square_code from ".$DB_CUSTOM['store']." where code = '".$_GET['st_code']."' ";
		$rs = mysqli_query($connect, $sq);
		$assoc['square_code'] = mysqli_fetch_row($rs)[0];
		
		$code = 'C'.time().rand(0,9).rand(0,9);
		$sq = "insert into ".$DB_CUSTOM['camera']."(regdate, code, name, store_code, square_code, device_info, usn, product_id) values (now(), '".$code."', '".$code."', '".$_GET['st_code']."', '".$assoc['square_code']."', '".$device_info."', '".$assoc['usn']."', '".$assoc['product_id']."' )";
		print $sq;
		$rs = mysqli_query($connect, $sq);
		if($rs) {
			$sq = "update ".$DB_COMMON['param']." set db_name = '".$_SESSION['db_name']."' where device_info = '".$device_info."' ";
			print $sq;
			$rs = mysqli_query($connect0, $sq);
			if($rs) {
				print "code=".$code." update OK";  
			}
		}		
	}
	
	else if($_GET['mode'] == 'modifyParam') {
//		print_r($_POST);
		$device_info = "mac=".trim($_POST['mac'])."&brand=".trim($_POST['brand'])."&model=".trim($_POST['model']);
		$_POST['enable_countingline'] = $_POST['enable_countingline'] == 'true' ? 'y' : 'n' ;
		$_POST['enable_heatmap'] = $_POST['enable_heatmap'] == 'true' ? 'y' : 'n' ;
		$_POST['enable_face_det'] = $_POST['enable_face_det'] == 'true' ? 'y' : 'n' ;
		$_POST['enable_macsniff'] = $_POST['enable_macsniff'] == 'true' ? 'y' : 'n' ;
		$_POST['flag'] = $_POST['flag'] == 'true' ? 'y' : 'n' ;
			
		$sq = "select square_code from ".$DB_CUSTOM['store']." where code='".$_POST['store_code']."'";
		$rs = mysqli_query($connect, $sq);
		$_POST['square_code'] = mysqli_fetch_row($rs)[0];
			
		$sq = "update ".$DB_CUSTOM['camera']." set name = '".addslashes(trim($_POST['name']))."', device_info = '".$device_info."',usn='".trim($_POST['usn'])."', product_id='".trim($_POST['product_id'])."', enable_countingline='".$_POST['enable_countingline']."', enable_heatmap='".$_POST['enable_heatmap']."', enable_face_det='".$_POST['enable_face_det']."', enable_macsniff='".$_POST['enable_macsniff']."', square_code = '".$_POST['square_code']."', store_code = '".$_POST['store_code']."', flag='".$_POST['flag']."', comment='".addslashes(trim($_POST['comment']))."' where pk = ".$_POST['pk'];
//		print $sq;
		$rs = mysqli_query($connect, $sq);
		if(!$rs) {
			print "FAIL";
		}
		
		$sq = "update ".$DB_CUSTOM['count']." set  square_code = '".$_POST['square_code']."', store_code = '".$_POST['store_code']."', camera_code='".$_POST['code']."'  where device_info = '".$device_info."' ";
		$rs = mysqli_query($connect, $sq);
		if(!$rs) {
			print "FAIL";
		}		
		$sq = "update ".$DB_CUSTOM['heatmap']." set  square_code = '".$_POST['square_code']."', store_code = '".$_POST['store_code']."', camera_code='".$_POST['code']."'  where device_info = '".$device_info."' ";
		$rs = mysqli_query($connect, $sq);
		if(!$rs) {
			print "FAIL";
		}	
		$sq = "update ".$DB_CUSTOM['age_gender']." set  square_code = '".$_POST['square_code']."', store_code = '".$_POST['store_code']."', camera_code='".$_POST['code']."'  where device_info = '".$device_info."' ";
		$rs = mysqli_query($connect, $sq);
		if(!$rs) {
			print "FAIL";
		}	

		
		if( ($_POST['enable_countingline']== 'y') and $_POST['ct_label']) {
			$ex_label = explode('},', $_POST['ct_label']);
			for($i=0; $i< sizeof($ex_label); $i++) {
				$ex_label[$i] = substr($ex_label[$i],1,strlen($ex_label[$i]));
				if(!$ex_label[$i]) {
					continue;
				}
				list($ct_name, $ct_label) = explode(":", $ex_label[$i]);
				$sq = "select pk from ".$DB_CUSTOM['counter_label']." where camera_code='".$_POST['code']."' and counter_name = '".$ct_name."'";
				$rs = mysqli_query($connect, $sq);
				if($rs->num_rows) {
					$sq = " update ".$DB_CUSTOM['counter_label']." set counter_label = '".$ct_label."' where camera_code='".$_POST['code']."' and counter_name = '".$ct_name."'";
				}
				else {
					$sq = "insert into ".$DB_CUSTOM['counter_label']."(camera_code, counter_name, counter_label) values('".$_POST['code']."', '".$ct_name."', '".$ct_label."')";
				}				
//				print $sq;
				$rs = mysqli_query($connect, $sq);
				if(!$rs) {
					print "FAIL";
				}
			}
		}
	}

	else if($_GET['mode'] == 'delete') {
		$sq = "select ID from ".$DB_COMMON['account']." where ID = '".$_SESSION['logID']."' and (role='admin' or role='root') and passwd = '".$_POST['passwd']."' ";
		$rs = mysqli_query($connect0, $sq);
		if($rs->num_rows) {
			$sq = "select device_info from ".$DB_CUSTOM['camera']." where pk=".$_GET['pk'];
			$rs = mysqli_query($connect, $sq);
			$device_info = mysqli_fetch_row($rs)[0];
//			print $device_info;
			$sq = "update ".$DB_COMMON['param']." set db_name='none' where device_info = '".$device_info."' ";
			$rs = mysqli_query($connect0, $sq);
			$sq = "delete from ".$DB_CUSTOM['camera']." where pk=".$_GET['pk'];
			$rs = mysqli_query($connect, $sq);
			if($rs) {
				print "store ".$device_info." delete OK";
			}
		}
		else {
			print "no right to delete or password not match!";
		}
			
	}
	
	else if($_GET['mode'] == 'viewParam') {
		$device_info = "mac=".$_GET['mac']."&brand=".$_GET['brand']."&model=".$_GET['model'];
		$tag_square = '&#9109;';
		$tag_check = '&#10004;';
		$tag_square = '<i class="align-middle fas fa-fw fa-1x fa-expand"></i>';
		$tag_check  = '<i class="align-middle fas fa-fw fa-1x fa-check"></i>';
			
		$sq = "select A.pk as fpk, A.device_info, A.usn, A.product_id, A.lic_pro, A.lic_surv, A.lic_count, 
		if(A.face_det='y','".$tag_check."','".$tag_square."') as face_det , 
		if(A.heatmap='y','".$tag_check."','".$tag_square."') as heatmap, 
		if(A.countrpt='y','".$tag_check."','".$tag_square."') as countrpt, 
		if(A.macsniff='y','".$tag_check."','".$tag_square."') as macsniff, 
		A.initial_access, A.last_access, A.db_name, A.param, B.body as snapshot, B.regdate as regdate, C.pk as pk, C.code, C.name, C.store_code, C.square_code, 
		if(C.enable_countingline='y','checked','') as enable_countingline, 
		if(C.enable_heatmap='y','checked','') as enable_heatmap, 
		if(C.enable_snapshot='y','checked','') as enable_snapshot, 
		if(C.enable_face_det='y','checked','') as enable_face_det, 
		if(C.enable_macsniff='y','checked','') as enable_macsniff, 
		if(C.flag='y','checked','') as flag from common.".$DB_COMMON['param']." as A inner join common.".$DB_COMMON['snapshot']." as B inner join ".$DB_CUSTOM['camera']." as C on A.device_info = B.device_info and A.device_info= C.device_info where A.device_info ='".$device_info."' ";
//		print $sq;
		$rs = mysqli_query($connect, $sq);
		$arr_result = mysqli_fetch_assoc($rs);		

		$exstr = explode("&", $arr_result['device_info']);
		$arr_result['mac'] = trim(array_pop(explode("=", $exstr[0])));
		$arr_result['brand'] = trim(array_pop(explode("=", $exstr[1])));
		$arr_result['model'] = trim(array_pop(explode("=", $exstr[2])));
		
		$sq = "select code, name from ".$DB_CUSTOM['store']." ";
		$rs = mysqli_query($connect, $sq);
//		$option_store_name = "<option value= \"none\">none</option>";
		$option_store_name = "";
		while ($row= mysqli_fetch_row($rs)) {
			$option_store_name .= "<option value=\"".$row[0]."\" ".(($row[0] == $arr_result['store_code']) ? "selected" : "").">".$row[1]."</option>";
			if($row[0] == $arr_result['store_code']) {
				$arr_result['store_name'] = $row[1];
			}
		}
		
		$arr_result['disable_countingline'] = $arr_result['countrpt'] == $tag_square ? "disabled":"";
		$arr_result['disable_heatmap'] = $arr_result['heatmap'] == $tag_square ? "disabled":"";
		$arr_result['disable_face_det'] = $arr_result['face_det'] == $tag_square ? "disabled":"";
		$arr_result['disable_macsniff'] = $arr_result['macsniff'] == $tag_square ? "disabled":"";
		
		$ct_name = array();
		$ex_list_counter = explode("\n", $arr_result['param']);
		for($i = 0,$c = 0; $i<sizeof($ex_list_counter); $i++) {
			if( !strncmp("VCA.Ch0.Ct",$ex_list_counter[$i],10) and strpos($ex_list_counter[$i], ".name")) {
				$ct_name[$c] = trim(array_pop(explode("=", $ex_list_counter[$i])));
				$c++;
			}
		}
		for($i=0; $i<count($ct_name); $i++) {
			$sq = "select counter_label from ".$DB_CUSTOM['counter_label']." where counter_name='".$ct_name[$i]."' and camera_code='".$arr_result['code']."' ";
			$ct_label = mysqli_fetch_row(mysqli_query($connect, $sq))[0];
			$camera_label_table_body .= '
				<input type="hidden" id="ct_name['.$i.']" value="'.$ct_name[$i].'">
				<tr>
					<td>'.$ct_name[$i].'</td>
					<td>
						<select id="ct_label['.$i.']" class="form-control" >
							<option value="none">'.$msg['none'].'</option>
							<option value="outside" '.($ct_label=='outside'? "selected": "").'>'.$msg['outside'].'</option>
							<option value="entrance" '.($ct_label=='entrance'? "selected": "").'>'.$msg['entrance'].'</option>
							<option value="exit" '.($ct_label=='exit'? "selected": "").'>'.$msg['exit'].'</option>
						</select>
					</td>
				</tr>' ;
		}
		$camera_label_table_body = '
			<table class="table table-striped table-sm table-bordered">
				<tr><th>'.$msg['countername'].'</th><th>'.$msg['counterlabel'].'</th></tr>'.
				$camera_label_table_body.
			'</table>';		
		$show_count_label = $arr_result['enable_countingline'] == "checked"? "":"none";
		
		
		if($arr_result['lic_pro'] =='y') {
			$arr_result['license'] = "/PRO";
		}
		if($arr_result['lic_surv'] =='y') {
			$arr_result['license'] .= "/SURV";
		}
		if($arr_result['lic_count'] =='y') {
			$arr_result['license'] .= "/COUNT";
		}
	
		$zone = array();
		$line = explode("\n",$arr_result['param']);
		for($i=0; $i<count($line)-1; $i++) {
			list($key,$val) = explode("=", $line[$i]);
			if(strpos(" ".$line[$i], "VCA.Ch0.Zn")) {
				$ex_key = explode(".",$key);
				$p = substr($ex_key[2],2,strlen($ex_key[2]));
				$zone[$p][$ex_key[3]] = trim($val);
			}
		}
		foreach($zone as $idx => $A) {
			$z_str = '';
			foreach($A as $key =>$val) {
				if($z_str) {
					$z_str.= ',';
				}
				$z_str .= '"'.$key.'":"'.$val.'"';
			}
			if($zone_str) {
				$zone_str .= ',';
			}
			$zone_str .= '{'.$z_str.'}';
		}			
		
		if($_GET['fmt'] == 'json') {
			$json_str= '{
				"info":{
					"zone":['.$zone_str.'],
					"device_info": "'.$arr_result['device_info'].'", 
					"code": "'.$arr_result['code'].'", 
					"regdate":"'.$arr_result['regdate'].'", 
					"name":"'.$arr_result['name'].'",
					"square_name":"'.$arr_result['square_name'].'",
					"store_name":"'.$arr_result['store_name'].'",
					"snapshot":"'.$arr_result['snapshot'].'",
					"mac":"'.$arr_result['mac'].'",
					"brand":"'.$arr_result['brand'].'",
					"model":"'.$arr_result['model'].'",
					"usn":"'.$arr_result['mac'].'",
					"product_id":"'.$arr_result['product_id'].'",
					"initial_access":"'.$arr_result['initial_access'].'",
					"last_access":"'.$arr_result['last_access'].'",
					"license":"'.$arr_result['license'].'",
					"functions":['.$arr_result['functions'].'],
					"features":['.$arr_result['features'].'],
					"comment":"'.$arr_result['comment'].'"
				}
			}';
			
		}
		else {
			$json_str = <<<EOPAGE
			<div class="card col-12 col-md-12 col-lg-12" style="position:relative; ">
				<div class="card-header">
					<span class="float-right">$arr_result[regdate]</span>
					<h3 class="card-title mb-0"><b>$arr_result[name]</b></h3>
				</div>
				<canvas id="zone_config" width="800" height="450"></canvas>
				<div class="card-body">
					<div class="form-row">
						<input type="hidden" id="pk" value="$arr_result[pk]">
						<input type="hidden" id="fr" value="$_GET[fr]">
						<div class="form-group col-md-4"><label>$msg[code]</label><input type="text" id="code" class="form-control" value="$arr_result[code]" readonly></div>
						<div class="form-group col-md-8"><label>$msg[name]</label><input type="text" id="name" class="form-control" value="$arr_result[name]"></div>
						<div class="form-group col-md-3"><label>$msg[mac]</label><input type="text" id="mac" class="form-control" value="$arr_result[mac]" readonly></div>
						<div class="form-group col-md-2"><label>$msg[brand]</label><input type="text" id="brand" class="form-control" value="$arr_result[brand]" readonly></div>
						<div class="form-group col-md-2"><label>$msg[model]</label><input type="text" id="model" class="form-control" value="$arr_result[model]" readonly></div>
						<div class="form-group col-md-3"><label>$msg[usn]</label><input type="text" id="usn" class="form-control" value="$arr_result[usn]" readonly></div>
						<div class="form-group col-md-2"><label>$msg[productid]</label><input type="text" id="product_id" class="form-control" value="$arr_result[product_id]" readonly></div>
						<div class="form-group col-md-3"><label>$msg[store]</label><select id="store_code" class="form-control">$option_store_name</select></div>
						<div class="form-group col-md-3"><label>$msg[installdate]</label><input type="text" class="form-control" value="$arr_result[initial_access]" readonly></div>
						<div class="form-group col-md-3"><label>$msg[lastaccess]</label><input type="text" class="form-control" value="$arr_result[last_access]" readonly></div>
						<div class="form-group col-md-3"><label>$msg[license]</label><input type="text" class="form-control" value="$arr_result[license]" readonly></div>
						<div class="form-group col-md-12"><label>$msg[function]</label>
							<div class="form-group mb-0">
								<label class="form-check-inline col-md-2 mb-0">$arr_result[countrpt]$msg[countdb]</label>
								<label class="form-check-inline col-md-2 mb-0">$arr_result[heatmap]$msg[heatmap]</label>
								<label class="form-check-inline col-md-2 mb-0">$arr_result[face_det]$msg[face]</label>
								<label class="form-check-inline col-md-2 mb-0">$arr_result[macsniff]$msg[macsniff]</label>
							</div>
						</div>
						<div class="form-group col-md-12 mt-0"><label>$msg[feature]</label>
							<div class="form-group mb-0">
								<label class="form-check-inline col-md-2 mb-0">
									<input class="form-check-input" type="checkbox" id="enable_countingline" OnChange="showCounterLabel()" $arr_result[enable_countingline] $arr_result[disable_countingline]>$msg[countingline]
								</label>
								<label class="form-check-inline col-md-2 mb-0">
									<input class="form-check-input" type="checkbox" id="enable_heatmap" $arr_result[enable_heatmap] $arr_result[disable_heatmap]>$msg[heatmap]
								</label>
								<label class="form-check-inline col-md-2 mb-0">
									<input class="form-check-input" type="checkbox" id="enable_face_det" $arr_result[enable_face_det] $arr_result[disable_face_det]>$msg[ageandgender]
								</label>
								<label class="form-check-inline col-md-2 mb-0">
									<input class="form-check-input" type="checkbox" id="enable_macsniff" $arr_result[enable_macsniff] $arr_result[disable_macsniff]>$msg[macsniffing]
								</label>
								<label class="form-check-inline col-md-2 mb-0">
									<input class="form-check-input" type="checkbox" id="flag" $arr_result[flag]>$msg[activate]
								</label>
							</div>
						</div>
						<div class="form-group col-md-12" id="counter_label" style="display:$show_count_label;">
							$camera_label_table_body
						</div>
						<div class="form-group col-md-12">
							<label>$msg[comment]</label>
							<textarea id="comment" class="form-control">$arr_result[comment]</textarea>
						</div>
					</div>
					<div class="float-right"><button type="button" class="btn  btn-sm btn-warning" OnClick="document.getElementById('delete_pad').style.display='block';">$msg[delete]</button></div>
					<div class="text-center"><button type="button" class="btn btn-primary" OnClick="modifyDeviceInfo()">$msg[save_changes]</button></div>					
					</div>
				</div>
			</div>
EOPAGE;
		}
	}
}

else if($_GET['fr'] == 'web_update') {
// for update web page from windows standalone	
	$server = "49.235.119.5";
	if($_GET['mode'] == 'update') {
		$body = file_get_contents("http://".$server."/release.php?file_download=true");
		$lines = explode("\r\n", $body);
		for($i=0; $i<sizeof($lines); $i++) {
			if(!$lines[$i]) {
				continue;
			}
			if(strpos(" ".$lines[$i], "##########") ==1) {
				if($fp) {
					fclose($fp);
				}
				$fname = substr($lines[$i],11,strlen($lines[$i])-11);
				$fname = '../'.$fname;
				print "\r\n<br>".$fname." ";
				$fp = fopen($fname,"w");
			}
			else {
				print "#";
				fwrite($fp, $lines[$i]."\r\n");
			}
		}
		if($fp) {
			fclose($fp);
		}

		$fname = "language_v".$version.".tbl.sql";
		$rs = system("..\..\MariaDB\bin\mysql.exe -uroot -prootpass cnt_demo < ".$fname." --default-character-set utf8mb4"); 
		print_r($rs);
	}
	$fname = "version.ini";
	$fpp = fopen($fname,'w');
	fwrite($fpp, "current_version = ".$version);
	fclose($fpp);
}

//Header("Content-type: text/json");
print $json_str;
	
	
	
	
?>