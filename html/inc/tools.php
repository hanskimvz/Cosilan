<!DOCTYPE html>
<?PHP
for ($i=0; $i<3; $i++) {
	chdir("../");
	if (is_dir("bin")) {
		$ROOT_DIR = getcwd();
		break;
	}
}
if (strtoupper(PHP_OS) == 'LINUX') {
   $backupDir = "/home/backup/";
}
else {
    // windows
    $backupDir = $ROOT_DIR."/db_backup";
}

if (isset($_GET['checkDB'])) {
    $connect0 = mysqli_connect('localhost', $_POST['admin_id'], $_POST['admin_pw'], 'common');
    if(!$connect0) {
        print "DB Select Error, maybe ID and password are wrong!!";
        exit();
    }
    // $sq =  "show grants for $_POST['admin_id']@localhost";
    $sq = "select Grant_priv from mysql.user where User='".$_POST['admin_id']."' ";
    print $sq;
    $rs = mysqli_query($connect0, $sq);

    $row = mysqli_fetch_row($rs);
    print $row[0];
    if ($row[0] !='Y') {
        print "DB Select Error, maybe ID does not have auth!!";
        exit();
    }
    exit();
}

if ($_GET['file']) {

    $fname = $_GET['file'];
    $F_NAME =  $backupDir.$fname;
    if (file_exists($F_NAME)) {
        $filesize = filesize($F_NAME);
        header('Content-Description: File Transfer');
        header("Content-Type:application/octet-stream");
        header("Content-Disposition:attachment;filename=$fname");
        header("Content-Transfer-Encoding:binary"); 
        header("Content-Length:".$filesize);
        header("Cache-Control:cache,must-revalidate");
        header("Pragma:no-cache");
        header("Expires:0");
        ob_clean();
        flush();
        readfile($F_NAME);
        flush();

        unlink($F_NAME); 
        print "completed";
    } 

    exit;
}

$ChkRadio[$_POST['role']] = "checked";

$HTML_BODY = <<<EOBLOCK
<main class="main d-flex justify-content-center w-100">
    <div class="container d-flex flex-column">
        <div class="row h-100">
            <div class="col-sm-12 col-md-6 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <form method="POST" ENCTYPE="multipart/form-data" id="submitForm" >
                        <div class="form-row">
                            <div class="form-group form-inline col-md-6">
                                <label>Admin ID:</label>
                                <input type="text" id="admin_id" name="admin_id" class="form-control ml-2" value="$_POST[admin_id]" />
                            </div>
                            <div class="form-group form-inline col-md-6">
                                <label>Admin Password:</label>
                                <input type="password" id="admin_pw" name="admin_pw" class="form-control ml-2" value="$_POST[admin_pw]"/>
                            </div>
                        </div>
                        <div class="form-group col-md-12 ">
                            <label class="form-check form-check-inline mt-1">
                                <input class="form-check-input" type="radio"name="role" id="role[init]" value="init_database" $ChkRadio[init_database] OnChange="add_content('init')" />
                                <span class="form-check-label">Init Database</span>
                            </label>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="role[backup]" value="backup_database" $ChkRadio[backup_database] OnChange="add_content('backup')" />
                                <span class="form-check-label">Backup Database</span>
                            </label>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="role[restore]" value="restore_database" $ChkRadio[restore_database] OnChange="add_content('restore')" />
                                <span class="form-check-label">Restore Database</span>
                            </label>

                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="role[migrate]" value="migrate_database" $ChkRadio[migrate_database] OnChange="add_content('migrate')" />
                                <span class="form-check-label">Migrate Database</span>
                            </label>
                        </div>
                                        
                        <div id="main_body" class="form-group"></div>
                    </form>
                    <div class="progress mb-3"><div id="progress_bar" class="progress-bar bg-warning" role="progressbar" style="width:0%"></div></div>
                    <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#centeredModalDanger" OnClick="checkData()">Submit</button>
                    <button type="button" class="btn btn-secondary ml-3" onClick ="location.href=('./tools.php')">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</main>

EOBLOCK;

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
		<meta name="author" content="Bootlab">
		<title id='title'>Admin Tools</title>
		<link href="/css/app.css" rel="stylesheet">
		<link rel="stylesheet" href="/css/all.css">	
	</head>

	<body id="body"><?=$HTML_BODY?>
        
        <div class="modal fade" id="centeredModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Summary</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body m-3">
                        <div id="err_contents" class="form-group"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="submit_btn" class="btn btn-danger" OnClick="submitForm()" data-dismiss="modal">Proceed</button>
                    </div>
                </div>
            </div>
        </div>
        <iframe name="dn" width="0" height="0"></iframe>
    </body>
	<script src="/js/app.js"></script>
</html>

<script>
    page = window.location.href.split('/').pop();
    // console.log(page);
</script>

<script>

function add_content(){
    var roles = document.getElementsByName("role");
    var cont_id = document.getElementById("main_body");
    var role = '';
    for (let i=0; i<roles.length; i++){
        if (roles[i].checked) {
            role = roles[i].value;
            break;
        }
    }
    
    if (role == 'init_database') {
        cont_id.innerHTML = '';
    }
    else if (role == 'restore_database') {
        cont_id.innerHTML = '<input id="file" type="file" name="file" />';
    }
    else if (role == 'backup_database') {
        cont_id.innerHTML = '' +
            '<label class="form-check form-check-inline">' +
                '<input class="form-check-input ml-5" type="checkbox" name="download" id="download" checked />' +
                '<span class="form-check-label">Download</span>' +
            '</label>';
    }
    else if (role == 'migrate_database') {
        cont_id.innerHTML = '<input id="file" type="file" name="file" />';
    }
    Progress(0);
}

function checkData(){
    var adminID = document.getElementById("admin_id");
    var adminPw = document.getElementById("admin_pw");
    var roles = document.getElementsByName("role");
    var sbmbtn = document.getElementById("submit_btn");

    var role = '';
    var nErr = 0;
    for (let i=0; i<roles.length; i++){
        if (roles[i].checked) {
            role = roles[i].value;
            break;
        }
    }
    console.log(adminID.value); console.log(adminPw.value); console.log(role);
    document.getElementById("err_contents").innerHTML = '';
    if (!adminID.value) {
        document.getElementById("err_contents").innerHTML += '<p class="text-danger mb-0">ID can\'t be empty!!</p>';
        nErr = 1;
    }
    if (!adminPw.value) {
        document.getElementById("err_contents").innerHTML += '<p class="text-danger mb-0">Password can\'t be empty!!</p>';
        nErr = 2;
    }
    if (!role)   {
        document.getElementById("err_contents").innerHTML += '<p class="text-danger mb-0">Role must be selected!!</p>';
        nErr = 3;
    }
    
    if (nErr) {
        sbmbtn.style.display = 'None';
        return;
    }
    else {
        sbmbtn.style.display = '';
    }

    var url = "./tools.php?checkDB";
    var posting = $.post(url, {
        admin_id: adminID.value, 
        admin_pw: adminPw.value
    });
    posting.done(function(data) {
        console.log(data);
        if (data.indexOf("Error")>0) {
            sbmbtn.style.display = 'None';
            document.getElementById("err_contents").innerHTML += '<p class="text-danger mb-0">'+ data + '</p>';
        }
        else {
            if(role == 'init_database'){
                document.getElementById("err_contents").innerHTML += '<p class="text-warning mb-0">All data will be erased.<br /> Please back up first!!</p>';
            }
            else if(role == 'restore_database'){
                document.getElementById("err_contents").innerHTML += '<p class="text-primary mb-0">File Name: <u>'+ document.getElementById("file").value +'</u></p>';
            }
            document.getElementById("err_contents").innerHTML += '<p class="text-primary mb-0">Are you sure to proceed?</p>';
        }
    });
}

function submitForm(){
    document.getElementById("submitForm").submit();
}
function Progress(val){
    document.getElementById("progress_bar").innerHTML= val + "%";
    document.getElementById("progress_bar").style.width= val +"%";
}


add_content();
</script>

<?PHP
if($_POST) {
    // print_r($_POST);
    $connect0 = mysqli_connect('localhost', $_POST['admin_id'], $_POST['admin_pw'], 'common');
    if(!$connect0) {
        print "DB Select Error, maybe ID and password are wrong!!";
    }


    if ($_POST['role'] == 'init_database' || $_POST['role'] == 'restore_database') {
        $fname = "/var/www/bin/db_ref";
        if ($_POST['role'] == 'restore_database') {
            if ($_FILES["file"]["error"] > 0) {
                echo '<script>document.getElementById("main_body").innerHTML += "<p class="text-danger">Downloading '.$_FILES["file"]["error"].'</p>"; </script>';
            }
            else {
                $str = '<p></p>'.
                    '<p>Upload: '.$_FILES["file"]["name"].'</p>'.
                    '<p>Type: '.$_FILES["file"]["type"].'</p>'.
                    '<p>Type: '.$_FILES["file"]["type"].'</p>'.
                    '<p>Size: '.($_FILES["file"]["size"] / 1024).' KB</p>'.
                    '<p>Stored in: '.$_FILES["file"]["tmp_name"].'</p>';
                echo '<script>document.getElementById("main_body").innerHTML += "'.$str.'"; </script>';
                $fname = $_FILES["file"]["tmp_name"];
                
            }
        }
        $sq = "show databases where `Database` not like 'mysql' and `Database` not like '%_schema' and `Database` not like 'test'";
        $rs = mysqli_query($connect0, $sq);

        while($row = mysqli_fetch_row($rs)){
            print_r($row);
        }
        // print $fname;
        $sq = ""; $err = 0;
        $f = fopen($fname, 'r');
        $body = fread($f, filesize($fname));
        fclose($f);
        $lines  = explode("\n", $body);
        $t_line = sizeof($lines);
        for ($i=0; $i<$t_line; $i++){
            $line = trim($lines[$i]);
            if(!strncmp($line, '--',2)) {
                continue;
            }
            $sq .= $line;
            // print $line;
            if (!strncmp(strstr($line,";"),';',1)) {
                print "<br />".$sq;
                $rs = mysqli_query($connect0, $sq);
                if ($rs) {
                    print ("OK");
                }
                else {
                    print ("Fail");
                    $err ++;
                }
                $sq = "";
            }
            $percent = ceil((($i+1)/$t_line)*100);
            echo '<script>Progress('.$percent.')</script>';
        }
        echo '<script>document.getElementById("main_body").innerHTML += "<p>No of Errors: '.$err.'</p>"; </script>';

    }
    else if ($_POST['role'] == 'backup_database') {
        $F_NAME = sprintf("%s/db_backup_%s_%s.sql",$backupDir, date("Ymd"), date("Hi"));

        $cmd = "mysqldump -u".$_POST['admin_id']." -p".$_POST['admin_pw']." --databases common > ".$F_NAME;
        // print $cmd;
        $result = exec($cmd);
        echo '<script>Progress(20)</script>';
        $cmd = "mysqldump -u".$_POST['admin_id']." -p".$_POST['admin_pw']."  --databases cnt_demo >> ".$F_NAME;
        // print $cmd;
        $result = exec($cmd);
        echo '<script>Progress(40)</script>';
        
        
        if($_POST['download']) {
            $filesize = filesize($F_NAME);
            $fname = array_pop(explode("/", $F_NAME));
            echo '<script>document.getElementById("main_body").innerHTML += "<p>Downloading '.$fname.' .....</p>"; </script>';
            echo '<script>document.getElementById("download").checked = true;</script>';
            // echo '<script>location.href=("./tools.php?file='.$fname.'");</script>';
            echo '<script>window.open("./tools.php?file='.$fname.'","dn");</script>';
         }
        else {
            echo '<script>document.getElementById("main_body").innerHTML += "<p>Back up completed to: '.$F_NAME.'</p>"; </script>';
            echo '<script>document.getElementById("download").checked = false;</script>';
            
        }
        echo '<script>Progress(100)</script>';

    }

    else if ($_POST['role'] == 'migrate_database') {
        # 1. database file to database 
        # 2. comapre database with database new tmp
        $common_temp_db_name = "common_tmp".time().rand(0,9).rand(0,9).rand(0,9);
        $userdata_temp_db_name = "cnt_demo_tmp".time().rand(0,9).rand(0,9).rand(0,9);
        if ($_FILES["file"]["error"] > 0) {
            echo '<script>document.getElementById("main_body").innerHTML += "<p class="text-danger">Downloading '.$_FILES["file"]["error"].'</p>"; </script>';
        }
        else {
            $str = '<p></p>'.
                '<p>Upload: '.$_FILES["file"]["name"].'</p>'.
                '<p>Type: '.$_FILES["file"]["type"].'</p>'.
                '<p>Type: '.$_FILES["file"]["type"].'</p>'.
                '<p>Size: '.($_FILES["file"]["size"] / 1024).' KB</p>'.
                '<p>Stored in: '.$_FILES["file"]["tmp_name"].'</p>';
            echo '<script>document.getElementById("main_body").innerHTML += "'.$str.'"; </script>';
            $fname = $_FILES["file"]["tmp_name"];
            
        }
        $sq = ""; $err = 0;
        $f = fopen($fname, 'r');
        $body = fread($f, filesize($fname));
        fclose($f);
        $lines  = explode("\n", $body);
        $t_line = sizeof($lines);
        for ($i=0; $i<$t_line; $i++){
            $line = trim($lines[$i]);
            if(!strncmp($line, '--',2)) {
                continue;
            }
            if (!strncmp($line, "CREATE DATABASE", strlen("CREATE DATABASE"))) {
                $line = str_replace('common', $common_temp_db_name, $line);
                $line = str_replace('cnt_demo', $userdata_temp_db_name, $line);
            }
            else if (!strncmp($line, "USE", strlen("USE"))){
                $line = str_replace('common', $common_temp_db_name, $line);
                $line = str_replace('cnt_demo', $userdata_temp_db_name, $line);
            }
            $sq .= $line;
            // print $line;
            if (!strncmp(strstr($line,";"),';',1)) {
                print "<br />".$sq;
                // $rs = mysqli_query($connect0, $sq);
                // if ($rs) {
                //     print ("OK");
                // }
                // else {
                //     print ("Fail");
                //     $err ++;
                // }
                $sq = "";
            }
            $percent = ceil((($i+1)/$t_line/2)*100);
            echo '<script>Progress('.$percent.')</script>';
        }
        
        $sq = "show tables";







        $sq = "drop database `".$common_temp_db_name."` ";
        // $rs = mysqli_query($connect0, $sq);
        if ($rs) {
            echo '<script>document.getElementById("main_body").innerHTML += "<p>'.$sq.': OK</p>"; </script>';    
        }
        else {
            echo '<script>document.getElementById("main_body").innerHTML += "<p>'.$sq.': Fail</p>"; </script>';    
            $err++;
        }

        // $sq = "drop database `".$userdata_temp_db_name."` ";
        if ($rs) {
            echo '<script>document.getElementById("main_body").innerHTML += "<p>'.$sq.': OK</p>"; </script>';    
        }
        else {
            echo '<script>document.getElementById("main_body").innerHTML += "<p>'.$sq.': Fail</p>"; </script>';    
            $err++;
        }        

        echo '<script>document.getElementById("main_body").innerHTML += "<p>No of Errors: '.$err.'</p>"; </script>';
    }
    
}



?>

