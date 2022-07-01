<?PHP
for ($i=0; $i<3; $i++) {
	chdir("../");
	if (is_dir("bin")) {
		$ROOT_DIR = getcwd();
		break;
	}
}
$dirname = $ROOT_DIR.'/bin/';

$fname = $ROOT_DIR."/bin/param.db";
$db = new SQLite3($fname);


if(!$_GET['mode']) {
    $_GET['mode'] = 'list';
}

if (!$_GET['table']) {
    $_GET['table'] = 'config';
}

$_GET['table'] .= "_tbl";

if($_POST) {
    // print_r($_POST);
    if($_POST['userid'] == 'root' && $_POST['password'] == 'pass') {
        $thistime = time();
        // $thistime = 1625046034;
        if(!$_POST['prino']) { 
            $sq = "insert into ".$_GET['table']."(entryValue) values('".$thistime."')";
            $rs = $db->exec($sq);
            $sq = "select prino from ".$_GET['table']." where entryValue='".$thistime."' ";
            $_POST['prino'] = $db->querySingle($sq);
        }
        $sq_t = "";
        foreach($_POST as $_key => $_val){
            // print $_key."=".$_val;
            $_val = str_replace("'", "&#039;", $_val);
            if ($_key == 'prino' or $_key == 'userid' or $_key == 'password') {
                continue;
            }
            if ($_key == 'regdate') {
                $_val = $thistime;
            }
            if($_POST['prino']) {
                if ($sq_t) {
                    $sq_t .= ",";
                }
                $sq_t .= " ".$_key." = '".$_val."' ";
            }
        }
        $sq = "update ".$_GET['table']." set ".$sq_t." where prino=".$_POST['prino'];
        // print $sq;
        $rs = $db->exec($sq) or die(print_r($db->errorInfo(), true));
    }
    else {
        print "Not Authorized";
    }
}


// if($_POST && $_POST['SECTION'] && $_POST['NAME'] && $_POST['VALUE']) {
//     if (!$_POST['SECTION']) {

//     }
//     if (!$_POST['READONLY']) {
//         $_POST['READONLY'] = 0;
//     }
//     foreach($_POST as $A => $B)    {
//         $_POST[$A] = str_replace("'", "&#039;", $B);
//     }
//     // print_r($_POST);

//     if (!$_POST['prino']){
//         $sq = "select prino from ".$_GET['table']." where SECTION='".trim($_POST['SECTION'])."' and entryName='".trim($_POST['NAME'])."'";
//         $_POST['prino'] = $db->querySingle($sq);
//     }

//     if($_POST['prino']) {
//         $sq = "update ".$_GET['table']." set SECTION='".trim($_POST['SECTION'])."', entryName='".trim($_POST['NAME'])."', entryValue='".trim($_POST['VALUE'])."', description='".trim($_POST['DESC'])."', datatype='".$_POST['DATATYPE']."', readonly=".$_POST['READONLY'].", option='".trim($_POST['OPTION'])."' where prino=".$_POST['prino'].";";

//     }
//     else {
//         $sq = "insert INTO ".$_GET['table']." (SECTION, entryName, entryValue, description, datatype, readonly, option) VALUES('".trim($_POST['SECTION'])."', '".trim($_POST['NAME'])."', '".trim($_POST['VALUE'])."', '".trim($_POST['DESC'])."', '".$_POST['DATATYPE']."', ".$_POST['READONLY'].", '".trim($_POST['OPTION'])."' );" ;    
//     }
//     // print $sq;
//     $rs = $db->exec($sq) or die(print_r($db->errorInfo(), true));
// }

if ($_GET['mode'] == 'create'){
    $sq = "CREATE TABLE IF NOT EXISTS ".$_GET['table']."(
        prino INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        SECTION TEXT,
        entryName TEXT,
        entryValue TEXT,
        description TEXT,
        datatype TEXT default 'sz',
        readonly INTEGER default 0,
        option TEXT
        )";

    $rs = $db->exec($sq);
}

else if ($_GET['mode'] == 'list') {
    $sq = "PRAGMA table_info(".$_GET['table'].")";
    // print $sq;
    $rs = $db->query($sq);
    $fields =  array();
    $table_head='<tr>';
    $modify_fields .='<tr>';
    while ($row = $rs->fetchArray()) {
        array_push($fields, $row['name']);
        $table_head.='<th>'.explode("_",$row['name'])[0].'</th>';

        if ($row['name'] == 'datatype') {
            $modify_fields .='<td><select  class="form-control" name="'.$row['name'].'" id="'.$row['name'].'">
                <option value="sz">sz</option>
                <option value="int">int</option>
                <option value="select">select</option>
                <option value="mselect">mselect</option>
                <option value="yesno">yesno</option>
                <option value="port">port</option>
                <option value="ipv4">ipv4</option>
            </select></td>';
        }
        // else if ($row['name'] == 'readonly') {
        //     $modify_fields .='<td><input type="checkbox" class="form-control" name="'.$row['name'].'" value="1" id="'.$row['name'].'"></td>';
        // }
        else {
            $modify_fields .='<td><input type="text" class="form-control" name="'.$row['name'].'" id="'.$row['name'].'"></td>';
        }

    }
    $table_head .= '</tr>';
    $modify_fields .= '</tr>';    
    $sq = "SELECT * FROM ".$_GET['table']." ";
    // print $sq;
    $rs = $db->query($sq);

    // print_r($rs);
    $table_body = '';
    while ($row = $rs->fetchArray()) {
        $table_body .= '<tr>';
        $table_body .= '<td><span Onclick="viewLine('.$row['prino'].')" style="cursor:pointer">'.$row['prino'].'</span></td>';
        for($i=1; $i<sizeof($fields); $i++) {
            $table_body .= '<td>'.$row[$fields[$i]].'</td>';
        }
        $table_body .= '</tr>';
    }
}

else if ($_GET['mode'] == 'view') {
    $sq = "SELECT * FROM ".$_GET['table']." where prino=".$_GET['prino'];
    // print $sq;
    $rs = $db->query($sq);
    $row = $rs->fetchArray();
    print json_encode($row);
    // print_r($row);

    $db->close();
    exit;
}



$db->close();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
		<meta name="author" content="Bootlab">
		<title id='title'>Admin Tools</title>
		<link href="css/app.css" rel="stylesheet">
		<link rel="stylesheet" href="css/all.css">	
	</head>

	<body id="body">
        <main class="content" id="pageContents">
            <div class="row">
                <table class="table table-striped table-sm table-bordered table-hover" >
                    <form method="POST" ENCTYPE="multipart/form-data" id="submitForm" >
                        <thead>
                            <tr>
                                <td colspan="<?=(sizeof($fields)-8)?>"></td>
                                <td colspan="3">ID:<input type="text" class="form-control form-inline" name="userid" value="<?=$_POST['userid']?>"></td>
                                <td colspan="3">PW:<input type="text" class="form-control" name="password" value="<?=$_POST['password']?>"></td>
                                <td colspan="2"><button type="button" class="btn btn-primary mt-3" OnClick="submit()">Submit</button></td>
                            </tr>
                                <?=$table_head?>
                        </thead>
                        <tbody>
                            <?=$modify_fields?>
                            <?=$table_body?>
                        </tbody>
                    </form>
                </table>
            </div>
        </main>
    </body>
    <script src="js/app.js"></script>
</html>
<script>
function ApplyJsonToField(response){
	arr_key = Object.keys(response);
	for (i=0; i< arr_key.length; i++) {
		id = document.getElementById(arr_key[i]);
		if (id) {
			if ((id.type == "text") || (id.type == "hidden") || (id.type == "select-one")) {
				id.value = response[arr_key[i]];
			}
			else if (id.type == "checkbox") {
				if (response[arr_key[i]] =='y') {
					id.checked = true;
				}
			}
		}
	}
}

function viewLine(prino) {
    url = './config_admin.php?mode=view&table=<?=explode("_", $_GET['table'])[0]?>&prino=' + prino;
    console.log(url);
    // var gets = $.post(url);
    console.log( document.getElementById("DATATYPE"));
    $.getJSON(url, function(response) {
        console.log(response);
        ApplyJsonToField(response);
    });


}
</script>