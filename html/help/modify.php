
<!DOCTYPE html>
<?PHP
session_start();
include_once ("dbconnect.php");

// print_r($_POST);
// // print ($_POST['body']);

// function quill2html($json_str){
//     $htmlbody ="";
//     $arr = json_decode($json_str, true);
//     print "<pre>";    print_r($arr); print "</pre>";
//     for ($i=0; $i<sizeof($arr['ops']); $i++) {
//         if (isset($arr['ops'][$i]['insert']['image'])) {
//             $str = '<img src="'.$arr['ops'][$i]['insert']['image'].'"></img>';
//         }
//         else {
//             $str = $arr['ops'][$i]['insert'];
//             if ($arr['ops'][$i]['attributes']['italic']){
//                 $str = '<i>'.$str.'</i>';
//             }
//             if ($arr['ops'][$i]['attributes']['underline']){
//                 $str = '<u>'.$str.'</u>';
//             }        
//             if ($arr['ops'][$i]['attributes']['bold']){
//                 $str = '<b>'.$str.'</b>';
//             }          
//             if ($arr['ops'][$i]['attributes']['link']){
//                 $str = '<a href="'.$arr['ops'][$i]['attributes']['link'].'" target="_blank">'.$str.'</a>';
//             }             
//             if ($arr['ops'][$i]['attributes']['font']){
//                 $str = '<span style="font-family:"'.$arr['ops'][$i]['attributes']['font'].'">'.$str.'</p>';
//             }
//             if ($arr['ops'][$i]['attributes']['color']){
//                 $str = '<font color="'.$arr['ops'][$i]['attributes']['color'].'">'.$str.'</font>';
//             }            
//         } 
//         $htmlbody .= $str;

//     }

//     return nl2br($htmlbody);
// }

// $htmlbody = quill2html($_POST['body']);
// print ($htmlbody);

function quill2html($body) {
  // print "<pre>".$body."</pre>";
  $htmlbody = trim($body);
  // $htmlbody = str_replace("<p>___&lt;table&gt;___</p>", "<table><tr><td>HELLO</td></tr></table>", $htmlbody);

  

  return $htmlbody;
}

function proc_text($body){
  $body = str_replace("</p>", "</p>\n", $body);
  $body = str_replace("</iframe>", "</iframe>\n", $body);
  $lines = explode("\n", $body);
  
  $body = "";
  for ($i=0; $i<sizeof($lines); $i++){
    if (!trim($lines[$i])) {
      continue;
    }
    // if (strpos(' '.$lines[$i], '<img src="data:image/') >0) {
    //   $line = trim($lines[$i]);
    //   $st = strpos($line, '<img src="data:image/');
    //   $line = substr($line, $st+4);
    //   $ed = strpos($line, '">');
    //   print ($st."-".$ed);

    //   print (substr($line, 0, 100));
    //   print ("-----");
    //   print (substr($line, strlen($line)-100, strlen($line)));
    //   print ("end</br>\n");
    // }
    $body .= $lines[$i]."\n";
  }
  return $body;
}


if(!$_GET['table']){
  $_GET['table'] = 'paragraph';
}

if (isset($_POST) && $_POST) {
    $regdate = date("Y-m-d H:i:s");

    if ($_POST['enable_html_tag']) {
      $body = $_POST['raw_html'];
    }
    else {
      $body = (quill2html($_POST['body']));
    }

    $body = proc_text($body);
    if ($_GET['pk'] == 0) {
        $sq = "insert into ".$_GET['table']."(regdate) values('".$regdate."')";
        $rs = mysqli_query($connect, $sq);
        $sq = "select pk from ".$_GET['table']." where regdate='".$regdate."'";
        $rs = mysqli_query($connect, $sq);
        $_GET['pk'] = mysqli_fetch_row($rs)[0];
    }

    $sq= "update ".$_GET['table']." set code='".$_POST['code']."', title='".addslashes(trim($_POST['title']))."', body='".addslashes($body)."', last_modified = '".$regdate."', flag=0 where pk=".$_GET['pk'];
    $rs= mysqli_query($connect, $sq);
}



$sq = "select * from ".$_GET['table']." where pk=".$_GET['pk'];
$rs = mysqli_query($connect, $sq);
$assoc =  mysqli_fetch_assoc($rs);

// print_r($assoc);
$contents =  $assoc['body'];

if (!$assoc['code']){
    $assoc['code'] = 'P'.time();
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
      <div class="row">
        <div class="col-xs-8">
          <div class="form-group">
            <label for="title">Title</label>
            <input class="form-control" name="title" type="text" value="<?=$assoc['title']?>" size="130">
          </div>
        </div>
      </div>
      <div class="row form-group">
        <label for="Text">Body</label>
        <button class="btn btn-primary" type="submit">Save Profile</button>
        <input name="body" type="hidden">
        <div id="editor-container">
            <p><?=$contents?></p>
        </div>
      </div>
      <div class="row">
        <button class="btn btn-primary" type="submit">Save Profile</button>
      </div>
      <div class="row">
        <input type="checkbox" name="enable_html_tag">HTML TAG
      </div>
      <div class="row">
        <p>
          <textarea name="raw_html" rows="100" cols="200"><?=$contents?></textarea>
        </p>
      </div>
    </form>
  </div>
</body>
<!-- Include the Quill library -->
<script src='/help/quill/jquery.min.js'></script>
<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script> -->
<script src="/help/quill/quill.js"></script>
<!-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> -->

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
  
//   console.log("Submitted", $(form).serialize(), $(form).serializeArray());
  
  // No back end to actually submit to!
//   alert('Open the console to see the submit data!')
//   return false;
};
</script>

</html>
