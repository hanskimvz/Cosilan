<!DOCTYPE html>
<?PHP
include "inc/sidebar.php";

?>
<html lang="en" >
<head>
  <meta charset="UTF-8">
<link rel="apple-touch-icon" type="image/png" href="https://static.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />
<meta name="apple-mobile-web-app-title" content="CodePen">
<link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />
<link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111" />
<title>NICEHANS</title>
<link rel='stylesheet' href='css/bootstrap.css'>
<link rel='stylesheet' href='css/all.css'>
<link rel='stylesheet' href='css/main.css'>
<script>
    window.console = window.console || function(t) {};
</script>
   
<script>
    if (document.location.search.match(/type=embed/gi)) {
        window.parent.postMessage("resize", "*");
}
</script>

</head>

<body translate="no" >
    <div class="page-wrapper chiller-theme toggled">
        <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
            <i class="fas fa-bars"></i>
        </a>
    <nav id="sidebar" class="sidebar-wrapper">
        <div class="sidebar-content">
            <div class="sidebar-brand">
                <a href="#">Intranet</a>
                <div id="close-sidebar">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="sidebar-header">
                <div class="user-pic">
                    <img class="img-responsive img-rounded" src="inc/user.jpg" alt="User picture">
                </div>
                <div class="user-info">
                    <span class="user-name"><?=$username?></span>
                    <span class="user-role"><?=$role?></span>
                    <span class="user-status">
                        <i class="fa fa-circle"></i>
                        <span>Online</span>
                    </span>
                </div>
            </div>
      <!-- sidebar-header  -->
        <div class="sidebar-menu">
            <ul><?=$pageSide?></ul>
      </div>
      <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    <div class="sidebar-footer"><?=$sliderFooter?></div>
  </nav>
  <!-- sidebar-wrapper  -->
  <main class="page-content">
    <div class="container">
      <h2 id = "title"></h2>
      <hr>
      <div class="row" id="contents">
      </div>
      <hr>

      <footer class="text-center">
        <div class="mb-2">
          <small>
            © 2020 made with <i class="fa fa-heart" style="color:red"></i> by - <a target="_blank" rel="noopener noreferrer" href="http://www.nichans.com">
              NiceHans
            </a>
          </small>
        </div>
        </div>
      </footer>

    </div>

  </main>
  <!-- page-content" -->
</div>
<!-- page-wrapper -->
    <!-- <script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js"></script> -->

<script src='js/jquery.js'></script>
<script src='js/popper.js'></script>
<script src='js/bootstrap.js'></script>
  
<script id="rendered-js" >
$(".sidebar-dropdown > a").click(function () {
    $(".sidebar-submenu").slideUp(200);
    if (
        $(this).
        parent().
        hasClass("active"))
    {
        $(".sidebar-dropdown").removeClass("active");
            $(this).
            parent().
            removeClass("active");
    } 
    else {
        $(".sidebar-dropdown").removeClass("active");
        $(this).
        next(".sidebar-submenu").
        slideDown(200);
        $(this).
        parent().
        addClass("active");
    }
});

$("#close-sidebar").click(function () {
    $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function () {
    $(".page-wrapper").addClass("toggled");
});
</script>


<script src='js/main.js'></script>

</body>

</html>
 <!--
Copyright (c) 2020 by Mohamed Azouaoui  (https://codepen.io/azouaoui-med/pen/wpBadb)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
-->
