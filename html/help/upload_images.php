
<!DOCTYPE html>
<?PHP
session_start();
include_once ("dbconnect.php");

print_r($_POST);
if(!$_GET['table']){
  $_GET['table'] = 'images';
}
/*
CREATE TABLE `images` (
    `pk` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `code` varchar(63) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `body` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `regdate` datetime DEFAULT NULL,
    `flag` enum ('y', 'n')  DEFAULT 'n',
    PRIMARY KEY (`pk`)
  ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci |
*/

if (isset($_POST) && $_POST) {
    $blocks = array();
    $arr_sq =  array();
    $regdate = date("Y-m-d H:i:s");

    $ex_body = explode("<img", $_POST['body']);

    for ($i=0; $i<sizeof($ex_body); $i++){
        $pos_s = strpos($ex_body[$i], "data:image");
        $pos_e = strpos($ex_body[$i], "\">");
        if (!$pos_s) {
            continue;
        }
        $block = substr($ex_body[$i], $pos_s, $pos_e-$pos_s);
        array_push($blocks, $block);
    }
    print "<br>\n";
    for ($i=0; $i<sizeof($blocks); $i++){
        print "<img src=\"".$blocks[$i]."\"><br>\n";
        $sq = "insert into document.".$_GET['table']."(code, body, regdate) values('".$_POST['code'].$i."','".addslashes($blocks[$i])."', '".$regdate."')";
        array_push($arr_sq, $sq);
    }

    print "<pre>"; print_r($arr_sq); print "</pre>";

    for ($i=0; $i<sizeof($arr_sq); $i++){
        $rs = mysqli_query($connect, $arr_sq[$i]);
        if ($rs) {
            print "OK";
        }
        else {
            print "FAIL";
        }
    }
    
    // if ($_POST['enable_html_tag']) {
    //   $body = $_POST['raw_html'];
    // }
    // else {
    //   $body = (quill2html($_POST['body']));
    // }

    // $body = proc_text($body);
    // if ($_GET['pk'] == 0) {
    //     $sq = "insert into ".$_GET['table']."(regdate) values('".$regdate."')";
    //     $rs = mysqli_query($connect, $sq);
    //     $sq = "select pk from ".$_GET['table']." where regdate='".$regdate."'";
    //     $rs = mysqli_query($connect, $sq);
    //     $_GET['pk'] = mysqli_fetch_row($rs)[0];
    // }

    // $sq= "update ".$_GET['table']." set code='".$_POST['code']."', title='".addslashes(trim($_POST['title']))."', body='".addslashes($body)."', last_modified = '".$regdate."', flag=0 where pk=".$_GET['pk'];
    // $rs= mysqli_query($connect, $sq);
}



$sq = "select * from ".$_GET['table']." where pk=".$_GET['pk'];
$rs = mysqli_query($connect, $sq);
$assoc =  mysqli_fetch_assoc($rs);

// print_r($assoc);
$contents =  $assoc['body'];

if (!$assoc['code']){
    $assoc['code'] = 'I'.time();
}
?>
<html lang="en">
<head>
<!-- Include stylesheet -->
<link href="/help/quill/quill.snow.css" rel="stylesheet">
<!-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet"> -->
<style>
#form-container {
  /* width: 1024px; */
  width:100%;
}

.row {
  margin-top: 15px;
}
.row.form-group {
  padding-left: 15px;
  padding-right: 15px;
}
.btn {
  margin-left: 15px;
}

.change-link {
  background-color: #000;
  border-bottom-left-radius: 6px;
  border-bottom-right-radius: 6px;
  bottom: 0;
  color: #fff;
  opacity: 0.8;
  padding: 4px;
  position: absolute;
  text-align: center;
  width: 150px;
}
.change-link:hover {
  color: #fff;
  text-decoration: none;
}

img {
  width: 200px;
}

#editor-container {
  height: 800px;
  
}
</style>
</head>
<body>
    <!-- Create the editor container -->
  <div id="form-container" class="container">
    <form method="POST" ENCTYPE="multipart/form-data">
      <input name="code" type="hidden" value="<?=$assoc['code']?>">
      <div class="row form-group">
        <button class="btn btn-primary" type="submit">Save Profile</button>
        <input name="body" type="hidden">
        <div id="editor-container">
            <p><?=$contents?></p>
        </div>
      </div>
      <div class="row">
        <button class="btn btn-primary" type="submit">Save Profile</button>
      </div>
    </form>
  </div>
</body>
<!-- Include the Quill library -->
<script src='/help/quill/jquery.min.js'></script>
<script src="/help/quill/quill.js"></script>

<!-- Initialize Quill editor -->
<script>
var toolbarOptions = [
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    ['blockquote', 'code-block','image','video','link'],

    // [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    [{ 'direction': 'rtl' }],                         // text direction


    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    // [{ 'font': [] }],
    [{ 'align': [] }],

    ['clean']                                         // remove formatting button
];
var quill = new Quill('#editor-container', {
  modules: {
    toolbar: toolbarOptions
    // toolbar: [
    //   ['font', 'header', 'bold', 'italic','underline','strike'],
    //   ['link', 'blockquote', 'code-block', 'image'],
    //   [{ list: 'ordered' }, { list: 'bullet' }]
    // ]
  },
  placeholder: 'Compose an epic...',
  theme: 'snow',
});

var form = document.querySelector('form');
form.onsubmit = function() {
  // Populate hidden form on submit
  var body = document.querySelector('input[name=body]');
//   body.value = JSON.stringify(quill.getContents());
  body.value = document.getElementsByClassName("ql-editor")[0].innerHTML;
};
</script>

</html>
