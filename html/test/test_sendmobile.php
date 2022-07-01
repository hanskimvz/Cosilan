<button onclick="sendMobileCode()">aaa</button>
<script src="/js/app.js"></script>
<script>
console.log("HHHH");
function sendMobileCode(){
    var id = "1fe3c39c73225b81a31769a2bb263fc60e3b9e6b0f5a8b8b59c73326059376c3864d2dbe8a43167290fca63682a51dde";
    var url = "https://fuwu.most.gov.cn/govserviceplatform/fruser/sendMobileCode";
    var phone = "15360120581";

    var posting = $.post(url,{frtelephone:phone, cardid:1, id:id});
		posting.done(function(data) {
			console.log(data);
			
		});

}



</script>
