<!DOCTYPE html>
<?PHP
session_start();

if(!$selected_language) 
	$selected_language = "chi";

if($selected_language=="chi") $lang_tab=1;
else if($selected_language=="kor") $lang_tab=2;
else if($selected_language=="eng") $lang_tab=3;

$msg_language = array(
//// LOGIN
	array('LOGIN', '登录', '로그인','Login'),
	array('ID', '用户帐号','아이디', 'ID'),
	array('PASSWORD', '密码','비밀번호','Password'),
	array('ID_holder','用户帐号','ID'),
	array('PW_holder', '密码','Password'),
	array('Sign in','登录','입장', 'Enter'),
	array('Remember me','记住我'),
	array('Not Registred?','没注册？'),
	array('Register here','这里注册'),
	array('Wrong ID or password','用户名或者密码错！！'),
	array('LOG out', '推出'),
	array('Chinese', '中文', '中文', '中文'),
	array('English', 'English', 'English', 'English'),
	array('Korean', '한글', '한글', '한글'),

);


function msg($en, $size=0)
{
	global $msg_language;
	global $lang_tab;
	if(!$size) {
		$size = 0;
	}
	
	$en = str_replace(" ","",$en);
	$en = strtoupper($en);

	for($i=0; $i<sizeof($msg_language); $i++)
	{
		$msg_language[$i][0] = str_replace(" ","",$msg_language[$i][0]);
		$msg_language[$i][0] = strtoupper($msg_language[$i][0]);
		if($en == $msg_language[$i][0]) {
			$str =  $msg_language[$i][$lang_tab];
			break;
		}
	}

	if($size > strlen($str))
	{
		for($i= $size-strlen($str); $i>=0; $i-=2)
		{
			$str = "&nbsp;".$str."&nbsp";
		}
	}
	return $str;
}




?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<!-- Title here -->
		<title><?PHP echo msg('LOGIN')?></title>
		<!-- Description, Keywords and Author -->
		<meta name="description" content="Your description">
		<meta name="keywords" content="Your,Keywords">
		<meta name="author" content="ResponsiveWebInc">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Styles -->
		<!-- Bootstrap CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">		
		<!-- Custom CSS -->
		<!--link href="./css/style.css" rel="stylesheet"-->
		<link rel="shortcut icon" href="#">

<script type="text/javascript">
function check() {
	if($("#username").val() == "<?PHP echo msg('ID_holder')?>") {
		$("#username").focus();
		return false;
	}
	if($("#password").val() == "") {
		$("#password").focus();
		return false;
	}
}
</script>
<style>
body{
	font-size: 13px;
	line-height: 23px;
	color: #666;
	background:#111;  
	padding-top: 45px;
	-webkit-font-smoothing: antialiased;
}
/* Login and Register form */

.admin-form{
	max-width: 500px;
	margin: 50px auto;
}

.admin-form form{ padding-top: 10px; }
/* Widget */

.widget {
  margin-top: 10px;
  margin-bottom: 20px; 
  background: #fff;
}



.widget .padd{
	padding: 15px;
}

.widget .widget-head{
  background-color: #f5f5f5;
  border: 1px solid #ddd;
  color: #777;
  font-size: 18px;
  padding: 12px 15px;
}

.widget .widget-head .widget-icons i{
	font-size: 14px;
	margin: 0px 4px;
}

.widget .widget-head .widget-icons a{
	color: #aaa;
}

.widget .widget-head .widget-icons a:hover{
	color: #888;
}

.widget .widget-content{
	border-left: 1px solid #ddd;
	border-right:1px solid #ddd;
	border-bottom: 1px solid #ddd;
}

.widget .widget-foot{
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-top: 0px;
  padding: 8px 15px;
  font-size: 13px;
  color: #555;
}



.widget.worange .widget-head{
  background-color: #f88529;
  border: 1px solid #f88529;
  color: #fff;
}


.widget.wred .widget-head .widget-icons a,
.widget.wblue .widget-head .widget-icons a,
.widget.wlightblue .widget-head .widget-icons a,
.widget.worange .widget-head .widget-icons a,
.widget.wgreen .widget-head .widget-icons a,
.widget.wviolet .widget-head .widget-icons a{
	color: #fff;
}

.widget.wred .widget-head .widget-icons a:hover,
.widget.wblue .widget-head .widget-icons a:hover,
.widget.wlightblue .widget-head .widget-icons a:hover,
.widget.worange .widget-head .widget-icons a:hover,
.widget.wgreen .widget-head .widget-icons a:hover,
.widget.wviolet .widget-head .widget-icons a:hover{
	color: #eee;
}



</style>



	</head>
<?PHP
$location_href  ="";
if($_POST['username']&&$_POST['password']){
	echo "	
				<div class='admin-form'>
				<form name='proc1' action='./proc_login.php' method='post'>
				<input type='hidden' name='username' value='".$_POST['username']."'>
				<input type='hidden' name='password' value='".$_POST['password']."'>
				<input type='hidden' name='location_href' value='".$_POST['location_href']."'>
				<input type='hidden' name='selected_language' value='".$_POST['selected_language']."'>
				</form>
				</div>";
	echo "<script>proc1.submit()</script>";
	exit;
}
?>
	



<body>

		<!-- Form area -->
<div class="admin-form">
			<!-- Widget starts -->
			<div class="widget worange">
				<!-- Widget head -->
				<div class="widget-head"><i class="fa fa-lock"></i><?PHP echo msg('LOGIN',10)?> </div>
				<div class="widget-content">
					<div class="padd">
						<!-- Login form -->
						<form class="form-horizontal" name="login_form" method="post"  onsubmit="return check()" >
						<input type="hidden" name="location_href" value = "<?PHP echo $_POST['location_href']?>" >
							<!-- Email -->
							<div class="form-group" height="100">
								  <label class="control-label col-lg-3" for="inputID"><?PHP echo msg('ID')?></label>
								  <div class="col-lg-9">
									<input type="text" name="username" class="form-control" id="username" placeholder="<?PHP echo msg('ID_holder')?>">
								  </div>
							</div>
							<!-- Password -->
							<div class="form-group" height="100">
								  <label class="control-label col-lg-3" for="inputPassword"><?PHP echo msg('PASSWORD')?></label>
								  <div class="col-lg-9">
									<input type="password" name="password" class="form-control" id="inputPassword" placeholder="<?PHP echo msg('PW_holder')?>">
								  </div>
							</div>
							<!-- Remember me checkbox and sign in button -->
							<div class="form-group" height="100">
								<div class="col-lg-6 col-lg-offset-3">
								</div>
								<div class="col-lg-9 col-lg-offset-3">
									<button type="submit" class="btn btn-danger"><?PHP echo msg('Sign in',16)?></button><div class="pull-right"><select name="selected_language"><option value="chi"><?PHP echo msg('chinese')?></option><option value="kor"><?PHP echo msg('korean')?></option><option value="eng"><?PHP echo msg('english')?></option></select></div>
								</div>
							</div>
						</form>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="widget-foot">
				  <?PHP echo msg('Not Registred?')?> <a href="<?PHP echo $register_href?>"><?PHP echo msg('Register here')?></a>
				</div>
			</div>  
		</div>
<script type="text/javascript">
login_form.username.focus();
</script>
	</body>	

</html>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  
<link rel="apple-touch-icon" type="image/png" href="https://static.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />
<meta name="apple-mobile-web-app-title" content="CodePen">

<link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

<link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111" />


  <meta charset="utf-8">
  <meta name='viewport' content='width=device-width, initial-scale=1'>

  <title>CodePen - Slide Sign In/Sign Up form</title>

  <link rel="stylesheet" media="screen" href="https://static.codepen.io/assets/fullpage/fullpage-4de243a40619a967c0bf13b95e1ac6f8de89d943b7fc8710de33f681fe287604.css" />
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,700,700italic,900,900italic" rel="stylesheet" />

  
<link rel="apple-touch-icon" type="image/png" href="https://static.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />
<meta name="apple-mobile-web-app-title" content="CodePen">

<link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

<link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111" />




  <title>CodePen - Slide Sign In/Sign Up form</title>
  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>


  <style>
    html { font-size: 15px; }
    html, body { margin: 0; padding: 0; min-height: 100%; }
    body { height:100%; display: flex; flex-direction: column; }
    .referer-warning {
      background: black;
      box-shadow: 0 2px 5px rgba(0,0,0, 0.5);
      padding: 0.75em;
      color: white;
      text-align: center;
      font-family: 'Lato', 'Lucida Grande', 'Lucida Sans Unicode', Tahoma, Sans-Serif;
      line-height: 1.2;
      font-size: 1rem;
      position: relative;
      z-index: 2;
    }
    .referer-warning h1 { font-size: 1.2rem; margin: 0; }
    .referer-warning a { color: #56bcf9; } /* $linkColorOnBlack */
  </style>
</head>

<body class="">
  <div class="referer-warning">
    <h1>⚠️ Do not enter passwords or personal information on this page. ⚠️</h1>
      This is a code demo posted by a web developer on <a href="https://codepen.io">codepen.io</a>.
    <br />
    A referer from CodePen is required to render this page view, and your browser is not sending one (<a href="https://blog.codepen.io/2017/10/05/regarding-referer-headers/" target="_blank">more details</a>).</h1>
  </div>

  <div id="result-iframe-wrap" role="main">

    <iframe
      id="result"
      srcdoc="
<!DOCTYPE html>
<html lang=&quot;en&quot; >

<head>

  <meta charset=&quot;UTF-8&quot;>
  
<link rel=&quot;apple-touch-icon&quot; type=&quot;image/png&quot; href=&quot;https://static.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png&quot; />
<meta name=&quot;apple-mobile-web-app-title&quot; content=&quot;CodePen&quot;>

<link rel=&quot;shortcut icon&quot; type=&quot;image/x-icon&quot; href=&quot;https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico&quot; />

<link rel=&quot;mask-icon&quot; type=&quot;&quot; href=&quot;https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg&quot; color=&quot;#111&quot; />


  <title>CodePen - Slide Sign In/Sign Up form</title>
  
  <link rel=&quot;stylesheet&quot; href=&quot;https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css&quot;>

  
  
<style>
:root {
	/* COLORS */
	--white: #e9e9e9;
	--gray: #333;
	--blue: #0367a6;
	--lightblue: #008997;

	/* RADII */
	--button-radius: 0.7rem;

	/* SIZES */
	--max-width: 758px;
	--max-height: 420px;

	font-size: 16px;
	font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen,
		Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif;
}

body {
	-webkit-box-align: center;
	        align-items: center;
	background-color: var(--white);
	background: url(&quot;https://res.cloudinary.com/dbhnlktrv/image/upload/v1599997626/background_oeuhe7.jpg&quot;);
	background-attachment: fixed;
	background-position: center;
	background-repeat: no-repeat;
	background-size: cover;
	display: grid;
	height: 100vh;
	place-items: center;
}

.form__title {
	font-weight: 300;
	margin: 0;
	margin-bottom: 1.25rem;
}

.link {
	color: var(--gray);
	font-size: 0.9rem;
	margin: 1.5rem 0;
	text-decoration: none;
}

.container {
	background-color: var(--white);
	border-radius: var(--button-radius);
	box-shadow: 0 0.9rem 1.7rem rgba(0, 0, 0, 0.25),
		0 0.7rem 0.7rem rgba(0, 0, 0, 0.22);
	height: var(--max-height);
	max-width: var(--max-width);
	overflow: hidden;
	position: relative;
	width: 100%;
}

.container__form {
	height: 100%;
	position: absolute;
	top: 0;
	-webkit-transition: all 0.6s ease-in-out;
	transition: all 0.6s ease-in-out;
}

.container--signin {
	left: 0;
	width: 50%;
	z-index: 2;
}

.container.right-panel-active .container--signin {
	-webkit-transform: translateX(100%);
	        transform: translateX(100%);
}

.container--signup {
	left: 0;
	opacity: 0;
	width: 50%;
	z-index: 1;
}

.container.right-panel-active .container--signup {
	-webkit-animation: show 0.6s;
	        animation: show 0.6s;
	opacity: 1;
	-webkit-transform: translateX(100%);
	        transform: translateX(100%);
	z-index: 5;
}

.container__overlay {
	height: 100%;
	left: 50%;
	overflow: hidden;
	position: absolute;
	top: 0;
	-webkit-transition: -webkit-transform 0.6s ease-in-out;
	transition: -webkit-transform 0.6s ease-in-out;
	transition: transform 0.6s ease-in-out;
	transition: transform 0.6s ease-in-out, -webkit-transform 0.6s ease-in-out;
	width: 50%;
	z-index: 100;
}

.container.right-panel-active .container__overlay {
	-webkit-transform: translateX(-100%);
	        transform: translateX(-100%);
}

.overlay {
	background-color: var(--lightblue);
	background: url(&quot;https://res.cloudinary.com/dbhnlktrv/image/upload/v1599997626/background_oeuhe7.jpg&quot;);
	background-attachment: fixed;
	background-position: center;
	background-repeat: no-repeat;
	background-size: cover;
	height: 100%;
	left: -100%;
	position: relative;
	-webkit-transform: translateX(0);
	        transform: translateX(0);
	-webkit-transition: -webkit-transform 0.6s ease-in-out;
	transition: -webkit-transform 0.6s ease-in-out;
	transition: transform 0.6s ease-in-out;
	transition: transform 0.6s ease-in-out, -webkit-transform 0.6s ease-in-out;
	width: 200%;
}

.container.right-panel-active .overlay {
	-webkit-transform: translateX(50%);
	        transform: translateX(50%);
}

.overlay__panel {
	-webkit-box-align: center;
	        align-items: center;
	display: -webkit-box;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	        flex-direction: column;
	height: 100%;
	-webkit-box-pack: center;
	        justify-content: center;
	position: absolute;
	text-align: center;
	top: 0;
	-webkit-transform: translateX(0);
	        transform: translateX(0);
	-webkit-transition: -webkit-transform 0.6s ease-in-out;
	transition: -webkit-transform 0.6s ease-in-out;
	transition: transform 0.6s ease-in-out;
	transition: transform 0.6s ease-in-out, -webkit-transform 0.6s ease-in-out;
	width: 50%;
}

.overlay--left {
	-webkit-transform: translateX(-20%);
	        transform: translateX(-20%);
}

.container.right-panel-active .overlay--left {
	-webkit-transform: translateX(0);
	        transform: translateX(0);
}

.overlay--right {
	right: 0;
	-webkit-transform: translateX(0);
	        transform: translateX(0);
}

.container.right-panel-active .overlay--right {
	-webkit-transform: translateX(20%);
	        transform: translateX(20%);
}

.btn {
	background-color: var(--blue);
	background-image: -webkit-gradient(linear, left top, right top, from(var(--blue)), color-stop(74%, var(--lightblue)));
	background-image: linear-gradient(90deg, var(--blue) 0%, var(--lightblue) 74%);
	border-radius: 20px;
	border: 1px solid var(--blue);
	color: var(--white);
	cursor: pointer;
	font-size: 0.8rem;
	font-weight: bold;
	letter-spacing: 0.1rem;
	padding: 0.9rem 4rem;
	text-transform: uppercase;
	-webkit-transition: -webkit-transform 80ms ease-in;
	transition: -webkit-transform 80ms ease-in;
	transition: transform 80ms ease-in;
	transition: transform 80ms ease-in, -webkit-transform 80ms ease-in;
}

.form > .btn {
	margin-top: 1.5rem;
}

.btn:active {
	-webkit-transform: scale(0.95);
	        transform: scale(0.95);
}

.btn:focus {
	outline: none;
}

.form {
	background-color: var(--white);
	display: -webkit-box;
	display: flex;
	-webkit-box-align: center;
	        align-items: center;
	-webkit-box-pack: center;
	        justify-content: center;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	        flex-direction: column;
	padding: 0 3rem;
	height: 100%;
	text-align: center;
}

.input {
	background-color: #fff;
	border: none;
	padding: 0.9rem 0.9rem;
	margin: 0.5rem 0;
	width: 100%;
}

@-webkit-keyframes show {
	0%,
	49.99% {
		opacity: 0;
		z-index: 1;
	}

	50%,
	100% {
		opacity: 1;
		z-index: 5;
	}
}

@keyframes show {
	0%,
	49.99% {
		opacity: 0;
		z-index: 1;
	}

	50%,
	100% {
		opacity: 1;
		z-index: 5;
	}
}
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage(&quot;resize&quot;, &quot;*&quot;);
  }
</script>


</head>

<body translate=&quot;no&quot; >
  <div class=&quot;container right-panel-active&quot;>
			<!-- Sign Up -->
			<div class=&quot;container__form container--signup&quot;>
				<form action=&quot;#&quot; class=&quot;form&quot; id=&quot;form1&quot;>
					<h2 class=&quot;form__title&quot;>Sign Up</h2>
					<input type=&quot;text&quot; placeholder=&quot;User&quot; class=&quot;input&quot; />
					<input type=&quot;email&quot; placeholder=&quot;Email&quot; class=&quot;input&quot; />
					<input type=&quot;password&quot; placeholder=&quot;Password&quot; class=&quot;input&quot; />
					<button class=&quot;btn&quot;>Sign Up</button>
				</form>
			</div>

			<!-- Sign In -->
			<div class=&quot;container__form container--signin&quot;>
				<form action=&quot;#&quot; class=&quot;form&quot; id=&quot;form2&quot;>
					<h2 class=&quot;form__title&quot;>Sign In</h2>
					<input type=&quot;email&quot; placeholder=&quot;Email&quot; class=&quot;input&quot; />
					<input type=&quot;password&quot; placeholder=&quot;Password&quot; class=&quot;input&quot; />
					<a href=&quot;#&quot; class=&quot;link&quot;>Forgot your password?</a>
					<button class=&quot;btn&quot;>Sign In</button>
				</form>
			</div>

			<!-- Overlay -->
			<div class=&quot;container__overlay&quot;>
				<div class=&quot;overlay&quot;>
					<div class=&quot;overlay__panel overlay--left&quot;>
						<button class=&quot;btn&quot; id=&quot;signIn&quot;>Sign In</button>
					</div>
					<div class=&quot;overlay__panel overlay--right&quot;>
						<button class=&quot;btn&quot; id=&quot;signUp&quot;>Sign Up</button>
					</div>
				</div>
			</div>
		</div>
    <script src=&quot;https://static.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js&quot;></script>

  
  
      <script id=&quot;rendered-js&quot; >
const signInBtn = document.getElementById(&quot;signIn&quot;);
const signUpBtn = document.getElementById(&quot;signUp&quot;);
const fistForm = document.getElementById(&quot;form1&quot;);
const secondForm = document.getElementById(&quot;form2&quot;);
const container = document.querySelector(&quot;.container&quot;);

signInBtn.addEventListener(&quot;click&quot;, () => {
  container.classList.remove(&quot;right-panel-active&quot;);
});

signUpBtn.addEventListener(&quot;click&quot;, () => {
  container.classList.add(&quot;right-panel-active&quot;);
});

fistForm.addEventListener(&quot;submit&quot;, e => e.preventDefault());
secondForm.addEventListener(&quot;submit&quot;, e => e.preventDefault());
//# sourceURL=pen.js
    </script>

  

</body>

</html>
 
"
      sandbox="allow-downloads allow-forms allow-modals allow-pointer-lock allow-popups allow-presentation  allow-scripts allow-top-navigation-by-user-activation" allow="accelerometer; ambient-light-sensor; camera; encrypted-media; geolocation; gyroscope; microphone; midi; payment; vr" allowTransparency="true"
      allowpaymentrequest="true" allowfullscreen="true" class="result-iframe">
      </iframe>

  </div>
</body>
</html>
