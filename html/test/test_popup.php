<!DOCTYPE html>
<html lang="en" >

<head>

  <meta charset="UTF-8">
  
<!-- <link rel="apple-touch-icon" type="image/png" href="https://static.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />
<meta name="apple-mobile-web-app-title" content="CodePen">

<link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

<link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111" />


  <title>CodePen - Responsive CSS and JS Pop-up</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat|Srisakdi" rel="stylesheet"> -->
  
  
  
<style>
html, body {
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0;
  position: relative;
}

body {
  background-color: #E3E3E3;
  text-align: center;
  display: -webkit-box;
  display: flex;
}
body .container {
  width: 100%;
  min-width: 320px;
  height: auto;
  margin: 0 auto;
  padding: 60px 30px;
  text-align: center;
  background: white;
  border-top: 80px solid rgba(154, 211, 222, 0.3);
  border-bottom: 80px solid rgba(154, 211, 222, 0.3);
  box-sizing: border-box;
  align-self: center;
}

h1, h2 {
  font-family: 'Srisakdi', cursive;
  font-weight: 400;
}

p, button {
  font-family: 'Montserrat', sans-serif;
}

h1 {
  font-size: 60px;
  text-align: center;
  color: #666666;
  margin: 0 0 40px 0;
}

h2 {
  font-size: 45px;
  text-align: center;
  color: #666666;
  margin: 0 0 30px 0;
}

p {
  color: #666666;
}

button {
  background: #9AD3DE;
  border: 0;
  border-radius: 4px;
  padding: 18px 30px;
  font-size: 18px;
  color: #FFFFFF;
  cursor: pointer;
}
button:focus {
  outline: none;
}
button:hover {
  background: #c0e4eb;
}

.popup {
  background: rgba(100, 100, 100, 0.5);
  position: fixed;
  display: none;
  z-index: 5000;
  height: 100%;
  width: 100%;
  left: 0;
  top: 0;
}
.popup > div {
  max-width: 600px;
  width: 70%;
  position: fixed;
  -webkit-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
  left: 50%;
  top: 50%;
  background: #FFFFFF;
  padding: 30px;
  z-index: 5001;
  text-align: center;
  border: 5px solid #9AD3DE;
  border-radius: 10px;
  box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.4);
}
</style>

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
  <div class="container">
	<h1>Responsive Popup</h1>
	<button data-js="open">Click me</button>
</div>

<div class="popup">
	<h2>This is my popup</h2>
    <img src="../inc/1.png" width="1024" />
	<p>Lorem ipsum dolor sit amet consectetur, adipiscing elit lacinia mus, sapien nibh imperdiet tempus. Vitae massa semper mi sagittis a cum cursus fusce per, gravida tellus metus purus litora nam ultricies donec, nibh dis ligula ad facilisi penatibus condimentum aenean.</p>

	<p>Aliquet odio id vulputate ad sodales blandit tempor, neque facilisi turpis dis curabitur ac velit potenti, montes bibendum pretium lacinia lobortis aenean. Orci integer eu tincidunt  scelerisque iaculis, porta elementum sagittis proin penatibus magna tempor.</p>
	<button name="close">Close</button>
</div>
    <!-- <script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js"></script> -->

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <!-- <script src='../js/app.js'></script> -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
  
      <script id="rendered-js" >
function popupOpenClose(popup) {

  /* Add div inside popup for layout if one doesn't exist */
  if ($(".wrapper").length == 0) {
    $(popup).wrapInner("<div class='wrapper'></div>");
  }

  /* Open popup */
  $(popup).show();

  /* Close popup if user clicks on background */
  $(popup).click(function (e) {
    if (e.target == this) {
      if ($(popup).is(':visible')) {
        $(popup).hide();
      }
    }
  });

  /* Close popup and remove errors if user clicks on cancel or close buttons */
  $(popup).find("button[name=close]").on("click", function () {
    if ($(".formElementError").is(':visible')) {
      $(".formElementError").remove();
    }
    $(popup).hide();
  });
}

$(document).ready(function () {
  $("[data-js=open]").on("click", function () {
    popupOpenClose($(".popup"));
  });
});
//# sourceURL=pen.js
    </script>

  

</body>

</html>

