
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"> 
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
  <meta name="description" content="中国气象局提供权威的天气预报、气象预警、卫星云图、雷达图等专业服务产品"> 
  <meta name="keywords" content="中国气象局、气象局、天气、天气预报、气候、气象、气候、气候变化、预警"> 
  <link rel="shortcut icon" href="https://weather.cma.cn/assets/favicon.ico"> 
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <link rel="stylesheet" type="text/css" href="https://at.alicdn.com/t/font_1145929_t7e3a52mhxh.css"> 
  <link rel="stylesheet" type="text/css" href="http://weather.cma.cn/assets/lib/mCustomScrollbar/jquery.mCustomScrollbar.min.css"> 
  <link rel="stylesheet" href="http://weather.cma.cn/assets/lib/bootstrap-3.3.7-dist/css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="http://weather.cma.cn/assets/cma/css/pub.css?v=20201013070815"> 
  <script type="text/javascript" src="http://weather.cma.cn/assets/lib/jquery-1.9.1.min.js"></script> 
  <!--[if lt IE 9]>
　<script src="https://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.js"></script>
  <script src="/static/bootstrap-slider/html5shiv.min.js"></script>
<![endif]--> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"> 
  <meta http-equiv="X-UA-Compatible" content="IE=9"> 
  <script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?c758855eca53e5d78186936566552a13";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script> 
  <script>
	var ctx = '/web';
</script> 
  <title>中国气象局-天气预报-城市预报</title> 
  <link href="http://weather.cma.cn/assets/lib/leaflet/leaflet.css" rel="stylesheet" type="text/css"> 
  <link rel="stylesheet" href="http://weather.cma.cn/assets/lib/leaflet-sidebar/src/L.Control.Sidebar.css"> 
 </head> 
 <body> 
  <nav class="navbar navbar-default top-navbar" style="margin-bottom:0;"> 
   <div class="container"> 
    <div class="collapse navbar-collapse" style="padding:0;"> 
     <ul class="nav navbar-nav"> 
      <li class="active_"><a href="http://weather.cma.cn">主站首页</a></li> 
      <li class="active_"><a href="http://weather.cma.cn/2011zwxx/2011zbmgk/2011zjld/2011zjzlym/index.html">领导主站</a></li> 
      <li class="active_"><a href="http://weather.cma.cn/2011zwxx/2011zbmgk/">部门概况</a></li> 
      <li class="active_"><a href="http://weather.cma.cn/2011xwzx/2011xqxxw/">新闻资讯</a></li> 
      <li class="active_"><a href="http://weather.cma.cn/root7/auto13139/">信息公开</a></li> 
      <li class="active_"><a href="http://weather.cma.cn">服务办事</a></li> 
      <li class="active_"><a href="/web">天气预报</a></li> 
     </ul> 
    </div> 
   </div> 
  </nav> 
  <nav id="header" class="navbar" style="margin-bottom:0;"> 
   <div class="container"> 
    <div class="collapse navbar-collapse"> 
     <ul class="nav navbar-nav"> 
      <li class="active"><a href="//weather.cma.cn" style="background-color: #fff;"><img src="https://weather.cma.cn//assets/cmalogo.png"></a></li> 
     </ul> 
     <form class="navbar-form navbar-right" style="margin-top:15px;"> 
      <div class="searchForm"> 
       <input type="text" id="searchInput" placeholder="输入城市名称查询天气" autocomplete="off" class="ac_input"> 
       <i class="iconfont icon-search"></i> 
       <div id="selectCityDiv" style="display: none;"> 
        <ul class="nav nav-tabs tab2 tabrank tabflag" role="tablist"> 
         <li class="active"><a class="tabflag" href="#gn" data-toggle="tab" aria-expanded="true">国内</a></li> 
         <li class=""><a class="tabflag" href="#gw" data-toggle="tab" aria-expanded="false">国外</a></li> 
        </ul> 
        <div class="tab-content" style="overflow: hidden; border-top:1px solid #ddd; padding:4px;"> 
         <div class="tab-pane active" id="gn" style="padding:0 15px;"> 
          <div class="row citylist"> 
           <div class="col-xs-2 city">
            <a href="/web/weather/58367.html">上海</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/51463.html">乌鲁木齐</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/52889.html">兰州</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/54511.html">北京</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/58238.html">南京</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/58606.html">南昌</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/58968.html">台北</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/58321.html">合肥</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/53463.html">呼和浩特</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/50953.html">哈尔滨</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/53772.html">太原</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/59287.html">广州</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/56294.html">成都</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/55591.html">拉萨</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/56778.html">昆明</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/58457.html">杭州</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/57494.html">武汉</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/54342.html">沈阳</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/54823.html">济南</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/59758.html">海口</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/59493.html">深圳</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/53698.html">石家庄</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/58847.html">福州</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/52866.html">西宁</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/57036.html">西安</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/57816.html">贵阳</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/57083.html">郑州</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/57516.html">重庆</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/53614.html">银川</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/54161.html">长春</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/57679.html">长沙</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/54517.html">天津</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/45005.html">香港</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/45011.html">澳门</a>
           </div> 
           <div class="col-xs-2 city">
            <a href="/web/weather/59431.html">南宁</a>
           </div> 
          </div> 
         </div> 
         <div class="tab-pane" id="gw" style="padding:0 15px;"> 
          <div class="row citylist"> 
           <div class="col-xs-3 city">
            <a href="/web/weather/062721.html">喀土穆</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/003772.html">伦敦</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/068816.html">开普敦</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/061641.html">达喀尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05021.html">弗里敦</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/065563.html">亚穆苏克罗</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/063260.html">摩加迪沙</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/064950.html">雅温得</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05020.html">朱巴</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/063980.html">维多利亚</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/061832.html">科纳克里</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/065472.html">阿克拉</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/067665.html">卢萨卡</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/067341.html">马普托</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/064500.html">利伯维尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05019.html">温得和克</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/061442.html">努瓦克肖特</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05025.html">罗安达</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/063125.html">吉布提市</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/063450.html">亚的斯亚贝巴</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/063740.html">内罗毕</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/065125.html">阿布贾</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/064700.html">恩贾梅纳</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05024.html">布拉柴维尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/067775.html">哈拉雷</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/060390.html">阿尔及尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05018.html">多多马</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/063894.html">达累斯萨拉姆</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/064390.html">布琼布拉</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/008589.html">普拉亚</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/063705.html">坎帕拉</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05017.html">班珠尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/065387.html">洛美</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/064387.html">基加利</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/060135.html">拉巴特</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/067083.html">塔那那利佛</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/060715.html">突尼斯市</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040103.html">的黎波里</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/062010.html">的黎波里</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/062366.html">开罗</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/064810.html">马拉博</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05009.html">蒙罗维亚</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/061291.html">巴马科</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/047108.html">首尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/044292.html">乌兰巴托</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/048698.html">新加坡市</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/097390.html">帝力</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/048647.html">吉隆坡</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/048074.html">内比都</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/048991.html">金边</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/048820.html">河内</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/048940.html">万象</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/096315.html">斯里巴加湾市</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/041571.html">伊斯兰堡</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/043466.html">科伦坡</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/041923.html">达卡</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/044454.html">加德满都</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/043555.html">马累</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/041217.html">阿布扎比</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040582.html">科威特城</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/017130.html">安卡拉</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/041170.html">多哈</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/041256.html">马斯喀特</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040100.html">贝鲁特</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040438.html">利雅得</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/041150.html">麦纳麦</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040754.html">德黑兰</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040650.html">巴格达</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040948.html">喀布尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/037864.html">巴库</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/037545.html">第比利斯</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/037788.html">埃里温</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/035188.html">阿斯塔纳</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/038353.html">比什凯克</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/038836.html">杜尚别</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/038457.html">塔什干</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/048455.html">曼谷</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/096741.html">雅加达</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/098425.html">马尼拉</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/041404.html">萨那</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05010.html">尼科西亚</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/027612.html">莫斯科</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/011035.html">维也纳</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/016716.html">雅典</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/012375.html">华沙</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/013274.html">贝尔格莱德</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/011520.html">布拉格</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/015614.html">索非亚</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/011816.html">布拉迪斯拉发</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/013615.html">地拉那</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/014240.html">萨格勒布</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/014654.html">萨拉热窝</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/013462.html">波德戈里察</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/026038.html">塔林</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/026730.html">维尔纽斯</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/014014.html">卢布尔雅那</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/012843.html">布达佩斯</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/013586.html">斯科普里</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/015420.html">布加勒斯特</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/026422.html">里加</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/033345.html">基辅</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/026850.html">明斯克</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/033815.html">基希讷乌</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05005.html">瓦莱塔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/008535.html">里斯本</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05011.html">卢森堡</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/093439.html">惠灵顿</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/094035.html">莫尔斯比港</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05007.html">阿皮亚</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05022.html">阿洛菲</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05004.html">苏瓦</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05014.html">帕利基尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05013.html">阿瓦鲁阿</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05003.html">努库阿洛法</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05012.html">维拉港</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/085574.html">圣地亚哥</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05023.html">乔治敦</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/006240.html">阿姆斯特丹</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/086580.html">蒙得维的亚</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/080415.html">加拉加斯</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05016.html">帕拉马里博</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G84071.html">基多</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/084628.html">利马</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/078762.html">圣何塞</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/078806.html">巴拿马城</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05015.html">圣萨尔瓦多</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05006.html">圣多明各</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/078970.html">西班牙港</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/078862.html">圣约翰</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05026.html">罗索</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05008.html">圣乔治</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05002.html">布里奇顿</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/078224.html">哈瓦那</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/G05001.html">金斯敦</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/047662.html">东京</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/072405.html">华盛顿</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/006180.html">哥本哈根</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/094926.html">堪培拉</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/076680.html">墨西哥城</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040080.html">大马士革</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/﻿001492.html">奥斯陆</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040270.html">安曼</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/061052.html">尼亚美</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/083377.html">巴西利亚</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/007149.html">巴黎</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/087582.html">布宜诺斯艾利斯</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/006451.html">布鲁塞尔</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/047058.html">平壤</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/042299.html">廷布</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/076405.html">拉巴斯</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/002464.html">斯德哥尔摩</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/042182.html">新德里</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/010385.html">柏林</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/065344.html">波多诺伏</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/071628.html">渥太华</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/064650.html">班吉</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/065503.html">瓦加杜古</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/016242.html">罗马</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/040183.html">耶路撒冷</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/067001.html">莫罗尼</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/002974.html">赫尔辛基</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/061990.html">路易港</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/064210.html">金沙萨</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/038880.html">阿什哈巴德</a>
           </div> 
           <div class="col-xs-3 city">
            <a href="/web/weather/008221.html">马德里</a>
           </div> 
          </div> 
         </div> 
        </div> 
       </div> 
      </div> 
     </form> 
    </div> 
   </div> 
  </nav> 
  <nav class="navbar navbar-default cma-navbar" style="margin-bottom:10px;"> 
   <div class="container"> 
    <a href="/" target="_self" style="width:12.5%;">首页</a> 
    <a href="/web/channel-d3236549863e453aab0ccc4027105bad.html" target="_self" style="width:12.5%;">天气实况</a> 
    <a href="/web/channel-380.html" target="_self" style="width:12.5%;">气象公报</a> 
    <a href="/web/alarm/map.html" target="_self" style="width:12.5%;">气象预警</a> 
    <a href="/web/weather/map.html" target="_self" style="width:12.5%;">城市预报</a> 
    <a href="http://weather.cma.cn" target="_blank" style="width:12.5%;">天气资讯</a> 
    <a href="http://weather.cma.cn/2011xwzx/2011xztbd/" target="_blank" style="width:12.5%;">气象专题</a> 
    <a href="http://weather.cma.cn/kppd/index.html" target="_blank" style="width:12.5%;">气象科普</a> 
   </div> 
  </nav> 
  <div class="container"> 
   <ol class="breadcrumb mybreadcrumb" id="breadcrumb"> 
    <li><span class="glyphicon glyphicon-home myglyphicon-home" aria-hidden="true"></span> <a href="/">首页</a></li> 
    <li><a href="/web/text/area.html">国内</a></li> 
    <li><a href="/web/text/XB/ASN.html">陕西</a></li> 
    <li class="active">西安</li> 
   </ol> 
   <div class="row"> 
    <div class="col-xs-12"> 
     <div class="city_real" style=""> 
      <div class="row"> 
       <div class="col-xs-8" id="cityPosition"> 
        <i class="iconfont icon-weizhi pull-left"></i> 
        <div class="dropdown pull-left"> 
         <button type="button" data-toggle="dropdown"><span>国内</span> <span class="caret"></span></button> 
         <ul class="dropdown-menu type-select" style="max-height:270px;overflow-y: scroll;">
          <li data-value="0">国内</li>
          <li data-value="1">国外</li>
         </ul> 
        </div> 
        <div class="pull-left split-line">
         |
        </div> 
        <div class="dropdown pull-left"> 
         <button type="button" data-toggle="dropdown"><span>陕西</span> <span class="caret"></span></button> 
         <ul class="dropdown-menu province-select" style="max-height:270px;overflow-y: scroll;">
          <li data-value="ABJ">北京市</li>
          <li data-value="ATJ">天津市</li>
          <li data-value="AHE">河北省</li>
          <li data-value="ASX">山西省</li>
          <li data-value="ANM">内蒙古自治区</li>
          <li data-value="ALN">辽宁省</li>
          <li data-value="AJL">吉林省</li>
          <li data-value="AHL">黑龙江省</li>
          <li data-value="ASH">上海市</li>
          <li data-value="AJS">江苏省</li>
          <li data-value="AZJ">浙江省</li>
          <li data-value="AAH">安徽省</li>
          <li data-value="AFJ">福建省</li>
          <li data-value="AJX">江西省</li>
          <li data-value="ASD">山东省</li>
          <li data-value="AHA">河南省</li>
          <li data-value="AHB">湖北省</li>
          <li data-value="AHN">湖南省</li>
          <li data-value="AGD">广东省</li>
          <li data-value="AGX">广西壮族自治区</li>
          <li data-value="AHI">海南省</li>
          <li data-value="ACQ">重庆市</li>
          <li data-value="ASC">四川省</li>
          <li data-value="AGZ">贵州省</li>
          <li data-value="AYN">云南省</li>
          <li data-value="AXZ">西藏自治区</li>
          <li data-value="ASN">陕西省</li>
          <li data-value="AGS">甘肃省</li>
          <li data-value="AQH">青海省</li>
          <li data-value="ANX">宁夏回族自治区</li>
          <li data-value="AXJ">新疆维吾尔自治区</li>
          <li data-value="AXG">香港特别行政区</li>
          <li data-value="AAM">澳门特别行政区</li>
          <li data-value="ATW">台湾省</li>
         </ul> 
        </div> 
        <div class="pull-left split-line">
         |
        </div> 
        <div class="dropdown pull-left"> 
         <button type="button" data-toggle="dropdown">西安 <span class="caret"></span></button> 
         <ul class="dropdown-menu station-select" style="max-height:270px;overflow-y: scroll;">
          <li><a href="/web/weather/57036.html">西安</a></li>
          <li><a href="/web/weather/57048.html">咸阳</a></li>
          <li><a href="/web/weather/57143.html">商洛</a></li>
          <li><a href="/web/weather/57245.html">安康</a></li>
          <li><a href="/web/weather/57016.html">宝鸡</a></li>
          <li><a href="/web/weather/53845.html">延安</a></li>
          <li><a href="/web/weather/53646.html">榆林</a></li>
          <li><a href="/web/weather/57127.html">汉中</a></li>
          <li><a href="/web/weather/57045.html">渭南</a></li>
          <li><a href="/web/weather/53947.html">铜川</a></li>
          <li><a href="/web/weather/57041.html">三原</a></li>
          <li><a href="/web/weather/57044.html">临潼</a></li>
          <li><a href="/web/weather/57153.html">丹凤</a></li>
          <li><a href="/web/weather/57035.html">乾县</a></li>
          <li><a href="/web/weather/57134.html">佛坪</a></li>
          <li><a href="/web/weather/53658.html">佳县</a></li>
          <li><a href="/web/weather/57038.html">兴平</a></li>
          <li><a href="/web/weather/57113.html">凤县</a></li>
          <li><a href="/web/weather/57025.html">凤翔</a></li>
          <li><a href="/web/weather/57119.html">勉县</a></li>
          <li><a href="/web/weather/57021.html">千阳</a></li>
          <li><a href="/web/weather/57049.html">华县</a></li>
          <li><a href="/web/weather/57055.html">华阴</a></li>
          <li><a href="/web/weather/57213.html">南郑</a></li>
          <li><a href="/web/weather/53950.html">合阳</a></li>
          <li><a href="/web/weather/53756.html">吴堡</a></li>
          <li><a href="/web/weather/53738.html">吴旗</a></li>
          <li><a href="/web/weather/57032.html">周至</a></li>
          <li><a href="/web/weather/57154.html">商南</a></li>
          <li><a href="/web/weather/57128.html">城固</a></li>
          <li><a href="/web/weather/57043.html">大荔</a></li>
          <li><a href="/web/weather/57028.html">太白</a></li>
          <li><a href="/web/weather/53751.html">子洲</a></li>
          <li><a href="/web/weather/53748.html">子长</a></li>
          <li><a href="/web/weather/57211.html">宁强</a></li>
          <li><a href="/web/weather/57137.html">宁陕</a></li>
          <li><a href="/web/weather/53841.html">安塞</a></li>
          <li><a href="/web/weather/53725.html">定边</a></li>
          <li><a href="/web/weather/53945.html">宜君</a></li>
          <li><a href="/web/weather/53857.html">宜川</a></li>
          <li><a href="/web/weather/53931.html">富县</a></li>
          <li><a href="/web/weather/57042.html">富平</a></li>
          <li><a href="/web/weather/57155.html">山阳</a></li>
          <li><a href="/web/weather/57024.html">岐山</a></li>
          <li><a href="/web/weather/57247.html">岚皋</a></li>
          <li><a href="/web/weather/57248.html">平利</a></li>
          <li><a href="/web/weather/53567.html">府谷</a></li>
          <li><a href="/web/weather/53850.html">延川</a></li>
          <li><a href="/web/weather/53854.html">延长</a></li>
          <li><a href="/web/weather/57023.html">彬县</a></li>
          <li><a href="/web/weather/53832.html">志丹</a></li>
          <li><a href="/web/weather/57132.html">户县</a></li>
          <li><a href="/web/weather/57026.html">扶风</a></li>
          <li><a href="/web/weather/53938.html">旬邑</a></li>
          <li><a href="/web/weather/57242.html">旬阳</a></li>
          <li><a href="/web/weather/57123.html">杨凌</a></li>
          <li><a href="/web/weather/57140.html">柞水</a></li>
          <li><a href="/web/weather/53740.html">横山</a></li>
          <li><a href="/web/weather/57034.html">武功</a></li>
          <li><a href="/web/weather/57030.html">永寿</a></li>
          <li><a href="/web/weather/57233.html">汉阴</a></li>
          <li><a href="/web/weather/57033.html">泾阳</a></li>
          <li><a href="/web/weather/57126.html">洋县</a></li>
          <li><a href="/web/weather/57057.html">洛南</a></li>
          <li><a href="/web/weather/53942.html">洛川</a></li>
          <li><a href="/web/weather/57031.html">淳化</a></li>
          <li><a href="/web/weather/53757.html">清涧</a></li>
          <li><a href="/web/weather/57054.html">潼关</a></li>
          <li><a href="/web/weather/53949.html">澄城</a></li>
          <li><a href="/web/weather/53848.html">甘泉</a></li>
          <li><a href="/web/weather/57124.html">留坝</a></li>
          <li><a href="/web/weather/57106.html">略阳</a></li>
          <li><a href="/web/weather/53941.html">白水</a></li>
          <li><a href="/web/weather/57254.html">白河</a></li>
          <li><a href="/web/weather/57027.html">眉县</a></li>
          <li><a href="/web/weather/57232.html">石泉</a></li>
          <li><a href="/web/weather/57029.html">礼泉</a></li>
          <li><a href="/web/weather/53651.html">神木</a></li>
          <li><a href="/web/weather/53750.html">米脂</a></li>
          <li><a href="/web/weather/57231.html">紫阳</a></li>
          <li><a href="/web/weather/53754.html">绥德</a></li>
          <li><a href="/web/weather/57037.html">耀县</a></li>
          <li><a href="/web/weather/53948.html">蒲城</a></li>
          <li><a href="/web/weather/57047.html">蓝田</a></li>
          <li><a href="/web/weather/57129.html">西乡</a></li>
          <li><a href="/web/weather/57343.html">镇坪</a></li>
          <li><a href="/web/weather/57144.html">镇安</a></li>
          <li><a href="/web/weather/57238.html">镇巴</a></li>
          <li><a href="/web/weather/57039.html">长安</a></li>
          <li><a href="/web/weather/53929.html">长武</a></li>
          <li><a href="/web/weather/57003.html">陇县</a></li>
          <li><a href="/web/weather/57020.html">陈仓</a></li>
          <li><a href="/web/weather/53735.html">靖边</a></li>
          <li><a href="/web/weather/53955.html">韩城</a></li>
          <li><a href="/web/weather/57040.html">高陵</a></li>
          <li><a href="/web/weather/57022.html">麟游</a></li>
          <li><a href="/web/weather/53944.html">黄陵</a></li>
          <li><a href="/web/weather/53946.html">黄龙</a></li>
         </ul> 
        </div> 
       </div> 
       <div class="col-xs-4 text-right " id="pubtime">
        更新
       </div> 
      </div> 
      <div id="city_real_temp" class=""> 
       <span id="temperature">&nbsp;</span> 
      </div> 
      <ul class="real_item "> 
       <li><i class="iconfont icon-qiya"></i> <span id="pressure">&nbsp;</span></li> 
       <li><i class="iconfont icon-humidity"></i> <span id="humidity">&nbsp;</span></li> 
       <li><i class="iconfont icon-jiangshuiliang"></i> <span id="precipitation">&nbsp;</span></li> 
       <li><i class="iconfont icon-Windpower"></i> <span id="wind">&nbsp;</span></li> 
      </ul> 
     </div> 
    </div> 
   </div> 
   <div class="row mt15"> 
    <div class="col-xs-9"> 
     <div class="hp"> 
      <div class="hd">
       7天天气预报（2020/10/13 08:00发布）
      </div> 
      <div id="dayList" class="row hb days " style="padding-bottom:0;"> 
       <div class="pull-left day actived"> 
        <div class="day-item">
         星期二
         <br>10/13
        </div> 
        <div class="day-item dayicon">
         <img src="https://weather.cma.cn//static/img/w/icon/w7.png">
        </div> 
        <div class="day-item">
         小雨
        </div> 
        <div class="day-item">
         西南风
        </div> 
        <div class="day-item">
         微风
        </div> 
        <div class="day-item bardiv">
         <div class="bar" style="top:5px; bottom:0px;">
          <div class="high">
           14℃
          </div>
          <div class="low">
           11℃
          </div>
         </div>
        </div> 
        <div class="day-item nighticon">
         <img src="https://weather.cma.cn//static/img/w/icon/w7.png">
        </div> 
        <div class="day-item">
         小雨
        </div> 
        <div class="day-item">
         西南风
        </div> 
        <div class="day-item">
         微风
        </div> 
       </div> 
       <div class="pull-left day "> 
        <div class="day-item">
         星期三
         <br>10/14
        </div> 
        <div class="day-item dayicon">
         <img src="https://weather.cma.cn//static/img/w/icon/w7.png">
        </div> 
        <div class="day-item">
         小雨
        </div> 
        <div class="day-item">
         西北风
        </div> 
        <div class="day-item">
         微风
        </div> 
        <div class="day-item bardiv">
         <div class="bar" style="top:6px; bottom:1px;">
          <div class="high">
           13℃
          </div>
          <div class="low">
           10℃
          </div>
         </div>
        </div> 
        <div class="day-item nighticon">
         <img src="https://weather.cma.cn//static/img/w/icon/w8.png">
        </div> 
        <div class="day-item">
         中雨
        </div> 
        <div class="day-item">
         西风
        </div> 
        <div class="day-item">
         微风
        </div> 
       </div> 
       <div class="pull-left day "> 
        <div class="day-item">
         星期四
         <br>10/15
        </div> 
        <div class="day-item dayicon">
         <img src="https://weather.cma.cn//static/img/w/icon/w8.png">
        </div> 
        <div class="day-item">
         中雨
        </div> 
        <div class="day-item">
         西风
        </div> 
        <div class="day-item">
         微风
        </div> 
        <div class="day-item bardiv">
         <div class="bar" style="top:7px; bottom:2px;">
          <div class="high">
           12℃
          </div>
          <div class="low">
           9℃
          </div>
         </div>
        </div> 
        <div class="day-item nighticon">
         <img src="https://weather.cma.cn//static/img/w/icon/w7.png">
        </div> 
        <div class="day-item">
         小雨
        </div> 
        <div class="day-item">
         西南风
        </div> 
        <div class="day-item">
         微风
        </div> 
       </div> 
       <div class="pull-left day "> 
        <div class="day-item">
         星期五
         <br>10/16
        </div> 
        <div class="day-item dayicon">
         <img src="https://weather.cma.cn//static/img/w/icon/w2.png">
        </div> 
        <div class="day-item">
         阴
        </div> 
        <div class="day-item">
         西风
        </div> 
        <div class="day-item">
         微风
        </div> 
        <div class="day-item bardiv">
         <div class="bar" style="top:3px; bottom:2px;">
          <div class="high">
           16℃
          </div>
          <div class="low">
           9℃
          </div>
         </div>
        </div> 
        <div class="day-item nighticon">
         <img src="https://weather.cma.cn//static/img/w/icon/w2.png">
        </div> 
        <div class="day-item">
         阴
        </div> 
        <div class="day-item">
         东风
        </div> 
        <div class="day-item">
         微风
        </div> 
       </div> 
       <div class="pull-left day "> 
        <div class="day-item">
         星期六
         <br>10/17
        </div> 
        <div class="day-item dayicon">
         <img src="https://weather.cma.cn//static/img/w/icon/w1.png">
        </div> 
        <div class="day-item">
         多云
        </div> 
        <div class="day-item">
         南风
        </div> 
        <div class="day-item">
         微风
        </div> 
        <div class="day-item bardiv">
         <div class="bar" style="top:2px; bottom:1px;">
          <div class="high">
           17℃
          </div>
          <div class="low">
           10℃
          </div>
         </div>
        </div> 
        <div class="day-item nighticon">
         <img src="https://weather.cma.cn//static/img/w/icon/w1.png">
        </div> 
        <div class="day-item">
         多云
        </div> 
        <div class="day-item">
         西南风
        </div> 
        <div class="day-item">
         微风
        </div> 
       </div> 
       <div class="pull-left day "> 
        <div class="day-item">
         星期日
         <br>10/18
        </div> 
        <div class="day-item dayicon">
         <img src="https://weather.cma.cn//static/img/w/icon/w0.png">
        </div> 
        <div class="day-item">
         晴
        </div> 
        <div class="day-item">
         西南风
        </div> 
        <div class="day-item">
         微风
        </div> 
        <div class="day-item bardiv">
         <div class="bar" style="top:0px; bottom:0px;">
          <div class="high">
           19℃
          </div>
          <div class="low">
           11℃
          </div>
         </div>
        </div> 
        <div class="day-item nighticon">
         <img src="https://weather.cma.cn//static/img/w/icon/w1.png">
        </div> 
        <div class="day-item">
         多云
        </div> 
        <div class="day-item">
         东南风
        </div> 
        <div class="day-item">
         微风
        </div> 
       </div> 
       <div class="pull-left day "> 
        <div class="day-item">
         星期一
         <br>10/19
        </div> 
        <div class="day-item dayicon">
         <img src="https://weather.cma.cn//static/img/w/icon/w1.png">
        </div> 
        <div class="day-item">
         多云
        </div> 
        <div class="day-item">
         东北风
        </div> 
        <div class="day-item">
         微风
        </div> 
        <div class="day-item bardiv">
         <div class="bar" style="top:0px; bottom:1px;">
          <div class="high">
           19℃
          </div>
          <div class="low">
           10℃
          </div>
         </div>
        </div> 
        <div class="day-item nighticon">
         <img src="https://weather.cma.cn//static/img/w/icon/w1.png">
        </div> 
        <div class="day-item">
         多云
        </div> 
        <div class="day-item">
         东北风
        </div> 
        <div class="day-item">
         微风
        </div> 
       </div> 
      </div> 
      <div class="mt15"> 
       <table class="hour-table" id="hourTable_0" style=""> 
        <tbody>
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-time"></i> 时间</td> 
          <td>11:00</td> 
          <td>14:00</td> 
          <td>17:00</td> 
          <td>20:00</td> 
          <td>23:00</td> 
          <td>02:00</td> 
          <td>05:00</td> 
          <td>08:00</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-tianqi"></i> 天气</td> 
          <td class="wicon"><img src="http://weather.cma.cn/static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="http://weather.cma.cn/static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="http://weather.cma.cn/static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="http://weather.cma.cn/static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="http://weather.cma.cn/static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="http://weather.cma.cn/static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="http://weather.cma.cn/static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="http://weather.cma.cn/static/img/w/icon/w7.png"></td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-temp"></i> 气温</td> 
          <td>13.2℃</td> 
          <td>13.9℃</td> 
          <td>12.7℃</td> 
          <td>11.4℃</td> 
          <td>12.2℃</td> 
          <td>13.1℃</td> 
          <td>12.7℃</td> 
          <td>11.8℃</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-raindrops"></i> 降水</td> 
          <td>2.1mm</td> 
          <td>1.4mm</td> 
          <td>1.8mm</td> 
          <td>0.9mm</td> 
          <td>0.7mm</td> 
          <td>0.9mm</td> 
          <td>2mm</td> 
          <td>1.8mm</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-Windpower"></i> 风速</td> 
          <td>2m/s</td> 
          <td>1.5m/s</td> 
          <td>2m/s</td> 
          <td>1m/s</td> 
          <td>1.2m/s</td> 
          <td>1.9m/s</td> 
          <td>1.7m/s</td> 
          <td>1.4m/s</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-fengxiang"></i> 风向</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西北风</td> 
          <td>西南风</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-qiya"></i> 气压</td> 
          <td>965.2hPa</td> 
          <td>963.5hPa</td> 
          <td>963.6hPa</td> 
          <td>964.8hPa</td> 
          <td>965.7hPa</td> 
          <td>965.2hPa</td> 
          <td>965.5hPa</td> 
          <td>966.7hPa</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-humidity"></i> 湿度</td> 
          <td>85.5%</td> 
          <td>85.8%</td> 
          <td>87.6%</td> 
          <td>89.4%</td> 
          <td>88.8%</td> 
          <td>91.4%</td> 
          <td>87.9%</td> 
          <td>74.8%</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-yunliang_huabanfuben"></i> 云量</td> 
          <td>100%</td> 
          <td>99.8%</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>100%</td> 
         </tr> 
        </tbody>
       </table> 
       <table class="hour-table" id="hourTable_1" style="display:none;"> 
        <tbody>
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-time"></i> 时间</td> 
          <td>08:00</td> 
          <td>11:00</td> 
          <td>14:00</td> 
          <td>17:00</td> 
          <td>20:00</td> 
          <td>23:00</td> 
          <td>02:00</td> 
          <td>05:00</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-tianqi"></i> 天气</td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-temp"></i> 气温</td> 
          <td>11.8℃</td> 
          <td>11.6℃</td> 
          <td>12.3℃</td> 
          <td>12.8℃</td> 
          <td>11.6℃</td> 
          <td>10.5℃</td> 
          <td>10.6℃</td> 
          <td>10.6℃</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-raindrops"></i> 降水</td> 
          <td>1.8mm</td> 
          <td>2mm</td> 
          <td>1.6mm</td> 
          <td>1.2mm</td> 
          <td>0.7mm</td> 
          <td>1.7mm</td> 
          <td>1.9mm</td> 
          <td>2.4mm</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-Windpower"></i> 风速</td> 
          <td>1.4m/s</td> 
          <td>1.7m/s</td> 
          <td>2m/s</td> 
          <td>2m/s</td> 
          <td>1m/s</td> 
          <td>1.2m/s</td> 
          <td>1.2m/s</td> 
          <td>1.3m/s</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-fengxiang"></i> 风向</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>东南风</td> 
          <td>西南风</td> 
          <td>西北风</td> 
          <td>西南风</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-qiya"></i> 气压</td> 
          <td>966.7hPa</td> 
          <td>968.2hPa</td> 
          <td>967.3hPa</td> 
          <td>966.9hPa</td> 
          <td>967.6hPa</td> 
          <td>967.9hPa</td> 
          <td>966.8hPa</td> 
          <td>966.2hPa</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-humidity"></i> 湿度</td> 
          <td>74.8%</td> 
          <td>87.7%</td> 
          <td>90.4%</td> 
          <td>92.6%</td> 
          <td>93.4%</td> 
          <td>94.1%</td> 
          <td>94.8%</td> 
          <td>95.2%</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-yunliang_huabanfuben"></i> 云量</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>99.4%</td> 
          <td>97.2%</td> 
          <td>98.4%</td> 
          <td>98.6%</td> 
          <td>99.6%</td> 
          <td>99.5%</td> 
         </tr> 
        </tbody>
       </table> 
       <table class="hour-table" id="hourTable_2" style="display:none;"> 
        <tbody>
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-time"></i> 时间</td> 
          <td>08:00</td> 
          <td>11:00</td> 
          <td>14:00</td> 
          <td>17:00</td> 
          <td>20:00</td> 
          <td>23:00</td> 
          <td>02:00</td> 
          <td>05:00</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-tianqi"></i> 天气</td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w8.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w8.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-temp"></i> 气温</td> 
          <td>11℃</td> 
          <td>11.6℃</td> 
          <td>11.8℃</td> 
          <td>12.2℃</td> 
          <td>11.8℃</td> 
          <td>11.5℃</td> 
          <td>10.8℃</td> 
          <td>10.4℃</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-raindrops"></i> 降水</td> 
          <td>4.1mm</td> 
          <td>3.4mm</td> 
          <td>2.7mm</td> 
          <td>2.5mm</td> 
          <td>1.4mm</td> 
          <td>0.2mm</td> 
          <td>2.1mm</td> 
          <td>0.2mm</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-Windpower"></i> 风速</td> 
          <td>1.6m/s</td> 
          <td>1.5m/s</td> 
          <td>1.9m/s</td> 
          <td>1.7m/s</td> 
          <td>0.9m/s</td> 
          <td>2.1m/s</td> 
          <td>1.6m/s</td> 
          <td>1.8m/s</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-fengxiang"></i> 风向</td> 
          <td>西北风</td> 
          <td>西南风</td> 
          <td>西北风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-qiya"></i> 气压</td> 
          <td>967.1hPa</td> 
          <td>968.1hPa</td> 
          <td>966.6hPa</td> 
          <td>966.2hPa</td> 
          <td>966.9hPa</td> 
          <td>968hPa</td> 
          <td>968.1hPa</td> 
          <td>967.5hPa</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-humidity"></i> 湿度</td> 
          <td>93.8%</td> 
          <td>92.5%</td> 
          <td>93.3%</td> 
          <td>94.2%</td> 
          <td>94.6%</td> 
          <td>96.1%</td> 
          <td>94.5%</td> 
          <td>92.8%</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-yunliang_huabanfuben"></i> 云量</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>100%</td> 
          <td>97.8%</td> 
          <td>98.5%</td> 
          <td>68.6%</td> 
         </tr> 
        </tbody>
       </table> 
       <table class="hour-table" id="hourTable_3" style="display:none;"> 
        <tbody>
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-time"></i> 时间</td> 
          <td>08:00</td> 
          <td>11:00</td> 
          <td>14:00</td> 
          <td>17:00</td> 
          <td>20:00</td> 
          <td>23:00</td> 
          <td>02:00</td> 
          <td>05:00</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-tianqi"></i> 天气</td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w7.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w2.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w2.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w2.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w2.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w2.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w2.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w2.png"></td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-temp"></i> 气温</td> 
          <td>10℃</td> 
          <td>12.7℃</td> 
          <td>16.4℃</td> 
          <td>16℃</td> 
          <td>12.7℃</td> 
          <td>10.5℃</td> 
          <td>10.3℃</td> 
          <td>9.4℃</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-raindrops"></i> 降水</td> 
          <td>0.1mm</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-Windpower"></i> 风速</td> 
          <td>1.4m/s</td> 
          <td>1.5m/s</td> 
          <td>2.8m/s</td> 
          <td>2.5m/s</td> 
          <td>0.9m/s</td> 
          <td>1.3m/s</td> 
          <td>2m/s</td> 
          <td>1.6m/s</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-fengxiang"></i> 风向</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>东南风</td> 
          <td>东南风</td> 
          <td>东北风</td> 
          <td>东北风</td> 
          <td>西北风</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-qiya"></i> 气压</td> 
          <td>968.4hPa</td> 
          <td>968.9hPa</td> 
          <td>966.8hPa</td> 
          <td>964.8hPa</td> 
          <td>965.8hPa</td> 
          <td>966.4hPa</td> 
          <td>966.2hPa</td> 
          <td>965.9hPa</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-humidity"></i> 湿度</td> 
          <td>91.4%</td> 
          <td>62%</td> 
          <td>71.6%</td> 
          <td>73.9%</td> 
          <td>82%</td> 
          <td>88.3%</td> 
          <td>85.3%</td> 
          <td>85.6%</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-yunliang_huabanfuben"></i> 云量</td> 
          <td>99.9%</td> 
          <td>80%</td> 
          <td>80%</td> 
          <td>80%</td> 
          <td>80%</td> 
          <td>80%</td> 
          <td>80%</td> 
          <td>87.3%</td> 
         </tr> 
        </tbody>
       </table> 
       <table class="hour-table" id="hourTable_4" style="display:none;"> 
        <tbody>
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-time"></i> 时间</td> 
          <td>08:00</td> 
          <td>11:00</td> 
          <td>14:00</td> 
          <td>17:00</td> 
          <td>20:00</td> 
          <td>23:00</td> 
          <td>02:00</td> 
          <td>05:00</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-tianqi"></i> 天气</td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w2.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-temp"></i> 气温</td> 
          <td>10.7℃</td> 
          <td>16.7℃</td> 
          <td>17.6℃</td> 
          <td>17.2℃</td> 
          <td>13.7℃</td> 
          <td>12.4℃</td> 
          <td>11.4℃</td> 
          <td>10.4℃</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-raindrops"></i> 降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-Windpower"></i> 风速</td> 
          <td>1.3m/s</td> 
          <td>2m/s</td> 
          <td>3.1m/s</td> 
          <td>2.2m/s</td> 
          <td>1.4m/s</td> 
          <td>1.4m/s</td> 
          <td>1.5m/s</td> 
          <td>1.8m/s</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-fengxiang"></i> 风向</td> 
          <td>东北风</td> 
          <td>东北风</td> 
          <td>东北风</td> 
          <td>东北风</td> 
          <td>东北风</td> 
          <td>西南风</td> 
          <td>东南风</td> 
          <td>西北风</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-qiya"></i> 气压</td> 
          <td>966.3hPa</td> 
          <td>967.5hPa</td> 
          <td>964.6hPa</td> 
          <td>962.8hPa</td> 
          <td>963.6hPa</td> 
          <td>-</td> 
          <td>963.9hPa</td> 
          <td>-</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-humidity"></i> 湿度</td> 
          <td>67.4%</td> 
          <td>58.9%</td> 
          <td>65.7%</td> 
          <td>66.2%</td> 
          <td>69.5%</td> 
          <td>72.8%</td> 
          <td>74.1%</td> 
          <td>77.9%</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-yunliang_huabanfuben"></i> 云量</td> 
          <td>85.9%</td> 
          <td>21.1%</td> 
          <td>16.3%</td> 
          <td>79.9%</td> 
          <td>76.2%</td> 
          <td>24.4%</td> 
          <td>46.1%</td> 
          <td>10.1%</td> 
         </tr> 
        </tbody>
       </table> 
       <table class="hour-table" id="hourTable_5" style="display:none;"> 
        <tbody>
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-time"></i> 时间</td> 
          <td>08:00</td> 
          <td>11:00</td> 
          <td>14:00</td> 
          <td>17:00</td> 
          <td>20:00</td> 
          <td>23:00</td> 
          <td>02:00</td> 
          <td>05:00</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-tianqi"></i> 天气</td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-temp"></i> 气温</td> 
          <td>12.5℃</td> 
          <td>15.5℃</td> 
          <td>18.9℃</td> 
          <td>18℃</td> 
          <td>15℃</td> 
          <td>11.2℃</td> 
          <td>11.2℃</td> 
          <td>11.2℃</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-raindrops"></i> 降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-Windpower"></i> 风速</td> 
          <td>1.5m/s</td> 
          <td>1.8m/s</td> 
          <td>3.1m/s</td> 
          <td>2.2m/s</td> 
          <td>0.9m/s</td> 
          <td>1.9m/s</td> 
          <td>1.9m/s</td> 
          <td>1.7m/s</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-fengxiang"></i> 风向</td> 
          <td>东北风</td> 
          <td>西北风</td> 
          <td>东北风</td> 
          <td>东南风</td> 
          <td>东北风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>东南风</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-qiya"></i> 气压</td> 
          <td>964.2hPa</td> 
          <td>-</td> 
          <td>962.1hPa</td> 
          <td>-</td> 
          <td>961.3hPa</td> 
          <td>-</td> 
          <td>962.1hPa</td> 
          <td>-</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-humidity"></i> 湿度</td> 
          <td>70%</td> 
          <td>62.5%</td> 
          <td>63.6%</td> 
          <td>65.7%</td> 
          <td>70.9%</td> 
          <td>82.6%</td> 
          <td>80.3%</td> 
          <td>67.8%</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-yunliang_huabanfuben"></i> 云量</td> 
          <td>10.1%</td> 
          <td>0%</td> 
          <td>2.7%</td> 
          <td>0%</td> 
          <td>10%</td> 
          <td>10.1%</td> 
          <td>79.9%</td> 
          <td>79.9%</td> 
         </tr> 
        </tbody>
       </table> 
       <table class="hour-table" id="hourTable_6" style="display:none;"> 
        <tbody>
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-time"></i> 时间</td> 
          <td>08:00</td> 
          <td>11:00</td> 
          <td>14:00</td> 
          <td>17:00</td> 
          <td>20:00</td> 
          <td>23:00</td> 
          <td>02:00</td> 
          <td>05:00</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-tianqi"></i> 天气</td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w0.png"></td> 
          <td class="wicon"><img src="https://weather.cma.cn//static/img/w/icon/w1.png"></td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-temp"></i> 气温</td> 
          <td>13.8℃</td> 
          <td>17.3℃</td> 
          <td>18.8℃</td> 
          <td>18℃</td> 
          <td>15.4℃</td> 
          <td>12.9℃</td> 
          <td>11.7℃</td> 
          <td>10.5℃</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-raindrops"></i> 降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
          <td>无降水</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-Windpower"></i> 风速</td> 
          <td>1.2m/s</td> 
          <td>1.8m/s</td> 
          <td>3.5m/s</td> 
          <td>1.8m/s</td> 
          <td>1m/s</td> 
          <td>1.4m/s</td> 
          <td>1.9m/s</td> 
          <td>1.7m/s</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-fengxiang"></i> 风向</td> 
          <td>东北风</td> 
          <td>西北风</td> 
          <td>西南风</td> 
          <td>东北风</td> 
          <td>东南风</td> 
          <td>西南风</td> 
          <td>西南风</td> 
          <td>西北风</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-qiya"></i> 气压</td> 
          <td>962.2hPa</td> 
          <td>-</td> 
          <td>960.4hPa</td> 
          <td>-</td> 
          <td>960.1hPa</td> 
          <td>-</td> 
          <td>961.1hPa</td> 
          <td>-</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-humidity"></i> 湿度</td> 
          <td>59.5%</td> 
          <td>54.3%</td> 
          <td>63%</td> 
          <td>63.6%</td> 
          <td>69.5%</td> 
          <td>87.3%</td> 
          <td>87.2%</td> 
          <td>83.6%</td> 
         </tr> 
         <tr> 
          <td style="background-color:#eee;"><i class="iconfont icon-yunliang_huabanfuben"></i> 云量</td> 
          <td>16.9%</td> 
          <td>76.7%</td> 
          <td>10.1%</td> 
          <td>79.9%</td> 
          <td>10.1%</td> 
          <td>79.9%</td> 
          <td>10.1%</td> 
          <td>79.9%</td> 
         </tr> 
        </tbody>
       </table> 
      </div> 
     </div> 
     <div class="hp mt15" style="display:none;"> 
      <div class="hd">
       气候背景
      </div> 
      <div id="myChart" class="row hb" style="height:500px;"></div> 
     </div> 
    </div> 
    <div class="col-xs-3"> 
     <!--卫星云图 start--> 
     <div class="hp"> 
      <div class="hd">
       卫星云图
      </div> 
      <div class="row hb" style="padding:0;"> 
       <a href="/web/channel-d3236549863e453aab0ccc4027105bad.html" class="product"><img src="https://weather.cma.cn/file/2020/10/12/SEVP_NSMC_WXBL_FY4A_ETCC_ACHN_LNO_PY_20201012224900000.JPG?v=1602544051222"></a> 
      </div> 
     </div> 
     <!--卫星云图 end--> 
     <!--雷达图 start--> 
     <div class="hp mt15"> 
      <div class="hd">
       降水预报
      </div> 
      <div class="row hb" style="padding:0;"> 
       <a href="/web/channel-339.html" class="product"><img src="https://weather.cma.cn/file/2020/10/13/SEVP_NMC_STFC_SFER_ER24_ACHN_L88_P9_20201013000002400.JPG?v=1602535401132"></a> 
      </div> 
     </div> 
     <!--雷达图 end--> 
     <!--天气资讯 start--> 
     <div class="hp mt15"> 
      <div class="hd">
       天气资讯
      </div> 
      <div class="row hb"> 
       <a href="http://weather.cma.cn/2011xwzx/2011xqxxw/2011xqxyw/202010/t20201010_564443.html" title="许健民气象卫星创新中心正式揭牌" class="newitem" target="_blank">许健民气象卫星创新中心正式揭牌</a> 
       <a href="http://weather.cma.cn/2011xzt/2020zt/20200915/" title="【专题】风云气象卫星事业50周年" class="newitem" target="_blank">【专题】风云气象卫星事业50周年</a> 
       <a href="http://weather.cma.cn/2011xwzx/2011xqxxw/2011xqxyw/202009/t20200925_563867.html" title="全球气象业务发展行动计划印发 要求强化主体责任做好任务落实" class="newitem" target="_blank">全球气象业务发展行动计划印发 要求强化主体责任做好任务落实</a> 
       <a href="http://weather.cma.cn/2011xwzx/2011xqxxw/2011xqxyw/202009/t20200909_562791.html" title="风云卫星国际用户应急保障管理办法出台" class="newitem" target="_blank">风云卫星国际用户应急保障管理办法出台</a> 
       <a href="http://weather.cma.cn/2011xwzx/2011xqxxw/2011xqxyw/202008/t20200831_562010.html" title="中国气象局启动风云卫星应用先行计划" class="newitem" target="_blank">中国气象局启动风云卫星应用先行计划</a> 
       <a href="http://weather.cma.cn/2011xwzx/2011xqxxw/2011xqxyw/202008/t20200818_561169.html" title="气象与航天部门在京召开星地通公司发展座谈会" class="newitem" target="_blank">气象与航天部门在京召开星地通公司发展座谈会</a> 
       <a href="http://weather.cma.cn/2011xwzx/2011xqxxw/2011xqxyw/202007/t20200730_559643.html" title="我国卫星遥感综合应用体系建设成果丰硕" class="newitem" target="_blank">我国卫星遥感综合应用体系建设成果丰硕</a> 
       <a href="http://weather.cma.cn/2011xwzx/2011xqxxw/2011xqxyw/202007/t20200724_559188.html" title="世界气象中心（北京）为老挝定制交互网站产品显成效" class="newitem" target="_blank">世界气象中心（北京）为老挝定制交互网站产品显成效</a> 
       <a href="http://weather.cma.cn/2011xwzx/2011xgzdt/202007/t20200714_558384.html" title="宁波：发布航运气象指数 覆盖六条国内外航线" class="newitem" target="_blank">宁波：发布航运气象指数 覆盖六条国内外航线</a> 
       <a href="http://weather.cma.cn/2011xwzx/2011xqxxw/2011xqxyw/202007/t20200707_557775.html" title="全球客观天气预报产品投入业务运行覆盖11621个城市" class="newitem" target="_blank">全球客观天气预报产品投入业务运行覆盖11621个城市</a> 
      </div> 
     </div> 
     <!--天气资讯 end--> 
     <div id="jingdian" class="hp mt15" style="display:none;"> 
      <div class="hd">
       周边景点
      </div> 
      <div class="row hb" id="jingdianWrap"> 
      </div> 
     </div> 
    </div> 
   </div> 
  </div> 
  <div class="footer"> 
   <div class="container clearfix" style="padding: 20px 0;"> 
    <div class="f_left clearfix"> 
     <div class="dcs">
      <a href="http://bszs.conac.cn/sitename?method=show&amp;id=10C5A3062A721232E053022819AC4A2F" target="_blank"><img src="https://weather.cma.cn//assets/ydyl/img/blue_error.png"></a>
     </div> 
     <div class="content"> 
      <p>中国气象报社 版权所有</p> 
      <p>地址：北京市海淀区中关村南大街46号</p> 
      <p>邮政编码：100081</p> 
      <p>京ICP备05004897号 京公网安备11041400161号</p> 
     </div> 
    </div> 
    <div class="f_right"> 
     <div class="footer-header">
      相关链接
     </div> 
     <ul class="links clearfix"> 
      <li><a href="https://www.yidaiyilu.gov.cn" target="_blank">中国一带一路网</a></li> 
      <li><a href="http://www.typhoon.org.cn" target="_blank">中国台风网</a></li> 
      <li><a href="#" target="_blank">中国新闻网</a></li> 
      <li><a href="http://www.people.com.cn" target="_blank">人民网</a></li> 
      <li><a href="http://weather.cma.cn" target="_blank">中国气象网</a></li> 
      <li><a href="https://public.wmo.int/zh-hans" target="_blank">世界气象组织</a></li> 
      <li><a href="http://www.gov.cn" target="_blank">中国政府网</a></li> 
      <li><a href="http://www.xinhuanet.com" target="_blank">新华网</a></li> 
     </ul> 
    </div> 
   </div> 
  </div> 
  <script src="http://weather.cma.cn/assets/lib/jquery.cookie.js" type="text/javascript"></script> 
  <script src="http://weather.cma.cn/assets/lib/mCustomScrollbar/jquery.mCustomScrollbar.min.js" type="text/javascript"></script> 
  <script src="http://weather.cma.cn/assets/lib/jquery.autocomplete.js" type="text/javascript"></script> 
  <script src="http://weather.cma.cn/assets/lib/bootstrap-hover-dropdown.min.js" type="text/javascript"></script> 
  <script src="http://weather.cma.cn/assets/lib/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script> 
  <script src="http://weather.cma.cn/assets/lib/echarts.min.js" type="text/javascript"></script> 
  <script>
	$(function(){
		$('.dropdown-toggle').dropdownHover();
		
		$("#searchInput").autocomplete('/api/autocomplete', {
			width: 345,
			max: 10,
			scroll: true,
			scrollHeight: 400,
			matchCase:true,
			parse:function(rows){
				var parsed = [];
				for (var i=0; i < rows.data.length; i++) {
					var row = rows.data[i];
					if (row) {
						parsed[parsed.length] = {
							data: row,
							value: row.split('|')[0]
						};
					}
				}
				return parsed;
			},
			focusEvent:function(input, event){
				var v = $(input).val();
				if(v == ''){
					$('#selectCityDiv').show();
				} else {
					$('#selectCityDiv').hide();
				}
			},
			formatItem: function(row) {
				$('#selectCityDiv').hide();
				var arr = row.split('|');
				return '<div class="row-fluid"><div class="pull-left">' + arr[1] + '</div><div class="pull-right">' + arr[3] + '</div></div>';
			}
		}).result(function(event, row, formatted) {
			var arr = row.split('|');
			$("#searchInput").val(arr[1]);
			window.location.href = '/web/weather/' + arr[0] + '.html';
		});
		
		$(document).click(function(e){
			var obj = $(e.srcElement || e.target);
			var c = obj.attr('class') || null;
			var id = obj.attr('id') || null;
			console.log(c)
			console.log(id)
			if((c != null && c.indexOf('tabflag') > -1) || (id != null && (id == 'selectCityDiv' || id == 'searchInput'))) {
				return true;
			} else {
				$('#selectCityDiv').hide();
			}
		});
	});
</script> 
  <script type="text/javascript" src="http://weather.cma.cn/assets/lib/mCustomScrollbar/jquery.mCustomScrollbar.min.js"></script> 
  <script type="text/javascript" src="http://weather.cma.cn/assets/lib/echarts.min.js"></script> 
  <script type="text/javascript" src="http://weather.cma.cn/assets/ydyl/js/weather.js"></script> 
  <script type="text/javascript" src="http://weather.cma.cn/assets/lib/leaflet/leaflet.js"></script> 
  <script type="text/javascript" src="http://weather.cma.cn/assets/lib/leaflet-sidebar/src/L.Control.Sidebar.js"></script> 
  <script type="text/javascript" src="http://weather.cma.cn/assets/cma/js/alarmMap.js?v=20201013070815"></script> 
  <script type="text/javascript" src="http://weather.cma.cn/assets/cma/js/weather.js?v=20201013070815"></script> 
  <script>
		$(function() {
			// 点击逐日预报时，在下方显示对应的精细化预报
			$('#dayList > div.day').click(function(){
				$('#hourTable_' + $(this).index()).show().siblings().hide();
				$(this).addClass('actived').siblings().removeClass('actived');
			});
			
			CMA.init();
			
			// 显示站点天气实况
			CMA.getNow('57036');
			
			$("#hourValues").mCustomScrollbar({
				theme:"dark-3",
			    horizontalScroll:true
      });
//#############################      
      stationid = '57036';
      $.getJSON('http://weather.cma.cn/api/now/' + stationid, function(ret){
        console.log(ret);
      });
//######################			
			// 显示站点气候背景
			$.getJSON('http://weather.cma.cn/api/climate',{stationid: '57036'}, function(ret) {
        console.log(ret);
				if(ret.data.length == 0) {
					return;
				}
				$('#myChart').parent().show();
				$('#climateWrap').show();
				var xAxisData = [];
				var maxTempData = [];
				var minTempData = [];
				var rainfallData = [];
				var dateb = ret.data.beginYear;
				var datee = ret.data.endYear;
				for(var i = 0; i < ret.data.data.length; i++){
					var d = ret.data.data[i];
					xAxisData.push(d.month + '月');
					maxTempData.push(d.maxTemp);
					minTempData.push(d.minTemp);
					rainfallData.push(d.rainfall);
				}
				Weather.initChart('西安)', dateb, datee, maxTempData, minTempData, rainfallData, xAxisData);
			});
			
			$.getJSON('http://weather.cma.cn/api/jingdian/weather?pcode=ASN', function(res){
        console.log(res);
				var html = [];
				for(var i = 0; i < res.data.length; i++){
					var d = res.data[i];
					
					html.push('<a href="/web/weather/' + d.location.stationId + '.html" class="report-item" style="margin-bottom:5px;">');
					html.push('	<div style="color:#1261ad; padding-bottom:5px; font-weight:bold;">' + d.location.name + '</div>');
					html.push('	<div class="row">');
					html.push('	    <div class="col-xs-8">' + (d.weather.dayText == d.weather.nightText ? d.weather.dayText : (d.weather.dayText + '转' + d.weather.nightText)) + '</div>');
					html.push('	    <div class="col-xs-4">' + (d.weather.high + '/' + d.weather.low) + '℃</div>');
					html.push('	</div>');
					html.push('</a>');
				}
				
				if(res.data.length > 0) {
					$('#jingdianWrap').html(html.join(''));
					$('#jingdian').show();
				}
				
			});
		});
	</script>  
  <input type="hidden" name="页面生成时间" value="2020-10-13 07:08:15">
 </body>
</html>


{
    "msg":"success",
    "code":0,
    "data":{
        "location":{
            "id":"59287",
            "name":"广州",
            "path":"中国, 广东, 广州"
        },
        "now":{
            "precipitation":0.0,
            "temperature":23.8,
            "pressure":1003.0,
            "humidity":76.0,
            "windDirection":"西北风",
            "windDirectionDegree":359.0,
            "windSpeed":4.4,
            "windScale":"3级"
        },
        "alarm":[{
            "id":"44000041600000_20201012112555",
            "title":"广东发布气象灾害(台风)二级预警",
            "signaltype":"其它气象灾害",
            "signallevel":"橙色",
            "effective":"2020/10/12 11:22",
            "eventType":"11B99",
            "severity":"ORANGE"
        }],
        "lastUpdate":"2020/10/13 08:05"
    }
}