
<!DOCTYPE html>
<html>
<head>
<link rel="dns-prefetch" href="http://i.tq121.com.cn">
<meta charset="utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>惠州天气预报,惠州7天天气预报,惠州15天天气预报,惠州天气查询 - 中国天气网</title>
<meta http-equiv="Content-Language" content="zh-cn">
<meta name="keywords" content="惠州天气预报,hztq,惠州今日天气,惠州周末天气,惠州一周天气预报,惠州15日天气预报,惠州40日天气预报" />
<meta name="description" content="惠州天气预报，及时准确发布中央气象台天气信息，便捷查询北京今日天气，惠州周末天气，惠州一周天气预报，惠州15日天气预报，惠州40日天气预报，惠州天气预报还提供惠州各区县的生活指数、健康指数、交通指数、旅游指数，及时发布惠州气象预警信号、各类气象资讯。" />
<!-- 城市对比上线  -->
<link type="text/css" rel="stylesheet" href="https://c.i8tq.com/cityListCmp/cityListCmp.css?20191230" />
<link type="text/css" rel="stylesheet" href="https://c.i8tq.com/cityListCmp/weathers.css?20191230" /> 
<style>
     .xyn-zan {
            width: 30px;
            height: 51px;
            background: url(http://i.tq121.com.cn/i/weather2015/city/zan-bj.png) no-repeat;
            margin-bottom: 7px;
            text-align: center
        }

        .xyn-zan img {
            padding-top: 5px;
            cursor: pointer;
        }

        .xyn-zan p {
            line-height: 15px;
            color: #a4a4a4;
        }

        .xyn-weather-box {
            width: 354px;
            height: 261px;
            border: 1px solid #c2e5ff;
            background: #fff;
            position: absolute;
            top: -67px;
            left: 250px;
            z-index: 999999;
            display: none;
        }

        .xyn-weather-box h2 {
            border-bottom: 1px solid #e5ebef;
            height: 34px;
            line-height: 34px;
            font-weight: normal;
            font-size: 14px;
            padding-left: 13px;
            color: #7e7e7e;
            position: relative;
        }

        .xyn-weather-box h2 span {
            color: #7e7e7e;
            font-size: 16px;
        }

        .xyn-weather-box h2 span img {
            vertical-align: middle;
            padding-right: 8px;
        }

        .xyn-weather-box h2 .xyn-delete {
            float: right;
            margin-top: 13px;
            margin-right: 13px;
            cursor: pointer;
        }

        .xyn-wb-text {
            color: #7b7b7b;
            padding-top: 12px;
            padding-left: 10px;
        }

        .xyn-wb-text span {
            color: #377cb9;
            font-size: 18px;
            padding-left: 3px;
        }

        .xyn-wb-btn {
            text-align: center;
            margin-top: 10px;
        }

        .xyn-wb-btn input {
            width: 180px;
            height: 26px;
            line-height: 26px;
            text-align: center;
            color: #fff;
            background: #77c2eb;
            cursor: pointer;
        }

        .main-box-div {
            width: 50px;
            float: left;
            margin-left: 6px;
            margin-right: 6px;
            margin-top: 13px;
            cursor: pointer;
        }

        .xyn-icon-bj {
            width: 26px;
            height: 24px;
            border: 1px solid #fff;
            margin: auto;
            cursor: pointer;
        }

        .xyn-icon-bj.cur {
            background: #f0faff;
            border: 1px solid #a5d9ff;
        }

        .xyn-icon-bj span {
            background: url(http://i.tq121.com.cn/i/weather2015/city/icon-img.png) no-repeat;
            width: 21px;
            height: 20px;
            display: block;
            margin-top: 2px;
            margin-left: 2px;
        }

        .xyn-icon-bj span.icon1 {
            background-position: 0 0;
        }

        .xyn-icon-bj span.icon2 {
            background-position: -58px 0px;
        }

        .xyn-icon-bj span.icon3 {
            background-position: -130px 0;
        }

        .xyn-icon-bj span.icon4 {
            background-position: -205px 0;
        }

        .xyn-icon-bj span.icon5 {
            background-position: -280px 0;
        }

        .xyn-icon-bj span.icon6 {
            background-position: 0 -50px;
        }

        .xyn-icon-bj span.icon7 {
            background-position: -58px -48px;
        }

        .xyn-icon-bj span.icon8 {
            background-position: -130px -45px;
        }

        .xyn-icon-bj span.icon9 {
            background-position: -206px -45px;
        }

        .xyn-icon-bj span.icon10 {
            background-position: -277px -46px;
        }

        .xyn-icon-text {
            text-align: center;
        }

        .xyn-cont-box {
            width: 310px;
            margin: auto;
        }

        #select {
            font-size: 12px;
            width: 57px;
            margin: auto;
            border: 1px solid #dfe6eb;
            position: absolute;
            top: 7px;
            left: 140px;
        }

        #select ul {
            margin: 0px;
            padding: 0px;
            background: #fff;
            float: none;
            height: auto;
            width: 57px;
            position: static;
            z-index: 9999;
            height: 160px;
            overflow-y: scroll;
            display: none;
        }

        #select ul li {
            border: none;
            float: none;
            font-size: 12px;
            height: 20px;
            margin-left: 0;
            position: static;
            text-align: left;
        }

        #select ul li,
        #select span {
            width: 100%;
            height: 20px;
            line-height: 20px;
            display: block;
            text-indent: 4px;
            font-size: 12px;
        }

        #select span {
            background: url(http://i.tq121.com.cn/i/weather2015/city/xiala.png) 42px center no-repeat;
        }

        #select ul li:hover,
        #select span:hover {
            color: #377cb9;
            cursor: pointer;
        }

        .yq-weather {
            position: absolute;
            right: 30px;
            top: 75px;
            cursor: pointer;
            color: #7f7f7f;
        }

        .yq-weather img {
            vertical-align: middle;
            padding-right: 5px;
        }

        .sk01 .succ {
            display: none;
            width: 222px;
            height: 124px;
            background: #fff;
            border: 1px solid #77c2ec;
            box-shadow: 1px 4px 4px #aaa;
            margin-left: 70px;
            margin-top: -62px;
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 3;
            color: #252525;
            border-radius: 2px
        }

        .sk01 .succ h1 {
            font-size: 20px;
            margin-left: 40px;
            margin-top: 39px
        }

        .sk01 .succ p {
            font-size: 12px;
            margin-left: 40px;
            margin-top: 0
        }

        .sk01 .img {
            background-position: -78px -904px;
            width: 70px;
            height: 35px;
            position: absolute;
            left: 116px;
            top: 30px
        }
    </style>

   
   
</head>
<body>
<input id="colorid" type="hidden" value="预报">
<input id="colorids" value="http://www.weather.com.cn/forecast/" type="hidden">
<input id="town" type="hidden" value="weather1dn">
<link href="http://i.tq121.com.cn/c/weather2017/headStyle_1.css" rel="stylesheet">

<!-- 体验新版 tong -->
	<div class="newPort">
		<div class="newPortBox"></div>
		<div class="closeNewPort"></div>
	</div>
<div class="weather_li">
  <div class="nav_li_box">
    <div class="weather_li_left">
      <a href="http://www.weather.com.cn/">首页</a>
      <a href="http://www.weather.com.cn/forecast/">预报</a>
      <a href="http://www.weather.com.cn/radar/">雷达</a>
      <a href="http://www.weather.com.cn/satellite/">云图</a>
      <a href="http://www.weather.com.cn/live/">临近预报</a>
      <a href="http://products.weather.com.cn/">专业产品</a>
      <a href="http://news.weather.com.cn/">资讯</a>
      <a href="http://www.weather.com.cn/life/">生活</a>
      <a href="http://www.weather.com.cn/traffic/">交通</a>
      <a href="http://www.weather.com.cn/weatherMap/indexSky.html?defaultCaseType=mySky" >我的天空</a>
      <a href="" class="shengjz"></a>
       
      <div href="javascript:void(0)" class="more_li">更多
        <div class="weather_li_open" style="width:555px;">
          <p>
            <a href="http://www.weather.com.cn/alarm/">预警</a>
            <a href="http://typhoon.weather.com.cn/" target="_blank">台风路径</a>
            <a href="http://www.weather.com.cn/space/">空间天气</a>
            <a href="http://p.weather.com.cn/">图片</a>
            <a href="http://video.weather.com.cn/">视频</a>
            <a href="http://www.weather.com.cn/zt/">专题</a>
            <a href="http://www.weather.com.cn/air/">环境</a>
            <a href="http://www.weather.com.cn/trip/">旅游</a>
            <a href="http://www.sportsweather.cn/golf/">高尔夫</a>
            <a href="http://www.weather.com.cn/forecast/skiweather.shtml">滑雪</a>
            <a href="http://www.weather.com.cn/aviation/">航空</a>
            <a href="http://www.weather.com.cn/beltroad/">一带一路</a>
          </p>
          <p style="border:none;" class="erp">
            <a href="http://www.weather.com.cn/tqbx/">保险</a>
            <a href="http://www.weather.com.cn/slpd/index.shtml">水利</a>
            <a href="http://www.weather.com.cn/agriculture/pest/">农业·病虫害</a>           
            <a  href="http://www.weather.com.cn/science/">科普</a>
            <a href="http://www.weather.com.cn/fzjz/">减灾</a>
            <a href="http://www.weather.com.cn/climate/">生态</a>
            <!-- <a href="http://marketing.weather.com.cn/">商业合作</a> -->
            <a href="https://cj.weather.com.cn/">天气插件</a>
            <a href="http://www.weather.com.cn/province/">省级站</a>
          </p>
        </div>
      </div>                                                  
    </div>
    <div class="weather_li_right">
      <div id="w_weather" class="w_weather"></div>
      <!--登陆后的情况-->
      <span class="top_list head-right" style="display:none;" id="logined">
        <li class="li_wd">
          <a class="top_list_title"><img class="head-imgs" src="http://i.tq121.com.cn/i/weather2015/user/my-head.png" id="userimg"/></a>
          <ul class="top-list-hidden" style="width:100px;">
 <a href="http://www.weather.com.cn/weatherMap/list.html" target="_blank" id="myCustomSky"><li>我定制的天空</li></a>
            <a href="http://u2.weather.com.cn/weathercenter/userself.html"><li>账号设置</li></a>
            <a href="javascript:void(0);" onclick="logout(window.location.href)"><li>退出</li></a>
          </ul>
          <div class="clear"></div>
        </li>
       
      </span>
      <!--登陆后的情况-->
      <!--未登录的情况-->
      <span class="weather-login" style="display:none;" id="unlogined">
        <a class="login-icon" onclick="login(window.location.href)">登录</a><a class="login-zhuce" onclick="regist(window.location.href)">注册</a>
      </span>
      <!--未登录的情况-->
    </div>
    <div class="clear:both"></div>
  </div>
</div>
<div class="weather_li_head">
  <div class="weather_li_box">
    <div class="w_li_logo fl">
      <a href="http://www.weather.com.cn/" target="_blank"></a>
      <span></span>
    </div>
    <div class="search-box fr">

      <!-- tong0903 top-->
      <link rel="stylesheet" href="https://c.i8tq.com/weather2020/search/searchCityList.css" />
      <div class="search clearfix">
        <div class="select_li " style="display:none;">
          <p>天气<i></i></p>
          <ul class="select_box">
            <li class="tianqi cur">天气</li>
            <li class="zixun">资讯</li>
          </ul>
        </div>
        <input type="text" value="输入城市、乡镇、街道、景点名称 查天气" id="txtZip" class="textinput text" autocomplete="off">
        <div id="zhong_search">
          <iframe src="http://promotion.chinaso.com/chinasosearch/chinaso-weather1.html" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
        </div>
          <div class="input-btn"><img src="https://i.i8tq.com/weather2020/search/search.png"></div>
        <div class="clear"></div>
      </div>

      <div class="inforesult"></div>
      <div id="show">
        <ul></ul>
        <dl class="qcode"><a href="http://p.weather.com.cn/#search" target="_blank" style="width:376px;"><img  src="https://i.i8tq.com/weather2020/search/rbAd.png" style="width:100%;"></a></dl>
      </div>
      <div class="city-box">
        <div class="city-tt">
          <a href="javascript:void(0)" class="cur">国内</a>
          <a href="javascript:void(0)" >本地</a>
          <a href="javascript:void(0)" >国际</a>
        </div>
        <div class="w_city city_guonei" style="display:block; padding-bottom:0">
          <dl>
            <p class="category">热门城市</p>
            <dd>
              <a href="http://www.weather.com.cn/weather1d/101010100.shtml#search" title="北京" target="_blank">北京</a>
              <a href="http://www.weather.com.cn/weather1d/101020100.shtml#search" title="上海" target="_blank">上海</a>
              <a href="http://www.weather.com.cn/weather1d/101270101.shtml#search" title="成都" target="_blank">成都</a>
              <a href="http://www.weather.com.cn/weather1d/101210101.shtml#search" title="杭州" target="_blank">杭州</a>
              <a href="http://www.weather.com.cn/weather1d/101190101.shtml#search" title="南京" target="_blank">南京</a>
              <a href="http://www.weather.com.cn/weather1d/101030100.shtml#search" title="天津" target="_blank">天津</a>
              <a href="http://www.weather.com.cn/weather1d/101280601.shtml#search" title="深圳" target="_blank">深圳</a>
              <a href="http://www.weather.com.cn/weather1d/101040100.shtml#search" title="重庆" target="_blank">重庆</a>
              <a href="http://www.weather.com.cn/weather1d/101110101.shtml#search" title="西安" target="_blank">西安</a>
              <a href="http://www.weather.com.cn/weather1d/101280101.shtml#search" title="广州" target="_blank">广州</a>
              <a href="http://www.weather.com.cn/weather1d/101120201.shtml#search" title="青岛" target="_blank">青岛</a>
              <a href="http://www.weather.com.cn/weather1d/101200101.shtml#search" title="武汉" target="_blank">武汉</a>
            </dd>
          </dl>
          <dl>
            <p class="category">热门景点</p>
            <dd class="jind">
              <a href="http://www.weather.com.cn/weather1d/10101010018A.shtml#search" title="故宫" target="_blank">故宫</a>
              <a href="http://www.weather.com.cn/weather1d/10130051008A.shtml#search" title="阳朔漓江" target="_blank">阳朔漓江</a>
              <a href="http://www.weather.com.cn/weather1d/10118090107A.shtml#search" title="龙门石窟" target="_blank">龙门石窟</a>
              <a href="http://www.weather.com.cn/weather1d/10109022201A.shtml#search" title="野三坡" target="_blank">野三坡</a>
              <a href="http://www.weather.com.cn/weather1d/10101020015A.shtml#search" title="颐和园" target="_blank">颐和园</a>
              <a href="http://www.weather.com.cn/weather1d/10127190601A.shtml#search" title="九寨沟" target="_blank">九寨沟</a>
              <a href="http://www.weather.com.cn/weather1d/10102010007A.shtml#search" title="东方明珠" target="_blank">东方明珠</a>
              <a href="http://www.weather.com.cn/weather1d/10125150503A.shtml#search" title="凤凰古城" target="_blank">凤凰古城</a>
              <a href="http://www.weather.com.cn/weather1d/10111010119A.shtml#search" title="秦始皇陵" target="_blank">秦始皇陵</a>
              <a href="http://www.weather.com.cn/weather1d/10125060301A.shtml#search" title="桃花源" target="_blank">桃花源</a>
            </dd>
          </dl>
         <dl id="searchCityProvince";style="margin-bottom:5px;border:none;">
            <p class="category">选择省市</p>
            <dd id="searchCityList"></dd>
          </dl>
         <dl style="margin-bottom:5px;border:none;display:none;">
            <dt>高球</dt>
            <dd>
              <a href="http://www.sportsweather.cn/weather/10102090003F.shtml#search" title="佘山" target="_blank">佘山</a>
              <a href="http://www.sportsweather.cn/weather/10129010601F.shtml#search" title="春城湖畔" target="_blank">春城湖畔</a>
              <a href="http://www.sportsweather.cn/weather/10101070004F.shtml#search" title="华彬庄园" target="_blank">华彬庄园</a>
              <a href="http://www.sportsweather.cn/weather/10128060113F.shtml#search" title="观澜湖" target="_blank">观澜湖</a>
              <a href="http://www.sportsweather.cn/weather/10131010107F.shtml#search" title="依必朗" target="_blank">依必朗</a>
              <a href="http://www.sportsweather.cn/weather/10102080001F.shtml#search" title="旭宝" target="_blank">旭宝</a>
              <a href="http://www.sportsweather.cn/weather/10131021101F.shtml#search" title="博鳌" target="_blank">博鳌</a>
              <a href="http://www.sportsweather.cn/weather/10129140501F.shtml#search" title="玉龙雪山" target="_blank">玉龙雪山</a>
              <a href="http://www.sportsweather.cn/weather/10128010103F.shtml#search" title="番禺南沙" target="_blank">番禺南沙</a>
              <a href="http://www.sportsweather.cn/weather/10101040001F.shtml#search" title="东方明珠" target="_blank">东方明珠</a>
            </dd>
          </dl>
          <div class="city-box-province">
              <div class="province-top">
                  <p class="proGoback">
                      <span class="province-back"><<返回</span>
                      <span class="province-level" data-level="1">全国</span>
                  </p>
                      <span class="province-area"><span>河北</span>下辖区域</span>
              </div>
              <div class="w_city_province">
                  <dl>
                      <dd id="cityList_city">
                      </dd>
                  </dl>
              </div>
          </div>
          <dl class="qcode"><a href="http://p.weather.com.cn/#search" target="_blank" style="width:376px;"><img  src="https://i.i8tq.com/weather2020/search/rbAd.png" style="width:100%;"></a></dl>
        </div>
        <div class="w_city city_guonei gn">
          <dl>
             <p class="category">周边城市</p>
             <dd class="diq"></dd>
          </dl>
          <dl>
            <p class="category">周边景点</p>
            <dd class="jind"></dd>
          </dl>
          <dl>
            <p class="category">本地乡镇</p>
            <dd class="jind"></dd>
          </dl>

          <dl class="qcode"><a href="http://p.weather.com.cn/#search" target="_blank" style="width:376px;"><img  src="https://i.i8tq.com/weather2020/search/rbAd.png" style="width:100%;"></a></dl>
        </div>
          <div class="w_city city_guonei gj">
              <dl>
                  <p class="category">热门城市</p>
                  <dd class="jind">
                      <a href="http://www.weather.com.cn/weather1d/106010100.shtml#search" title="曼谷" target="_blank">曼谷</a>
                      <a href="http://www.weather.com.cn/weather1d/106010100.shtml#search" title="曼谷" target="_blank">东京</a>
                      <a href="http://www.weather.com.cn/weather1d/102010100.shtml#search" title="首尔" target="_blank">首尔</a>
                      <a href="http://www.weather.com.cn/weather1d/105010100.shtml#search" title="吉隆坡" target="_blank">吉隆坡</a>
                      <a href="http://www.weather.com.cn/weather1d/104010100.shtml#search" title="新加坡" target="_blank">新加坡</a>
                      <a href="http://www.weather.com.cn/weather1d/202010100.shtml#search" title="巴黎" target="_blank">巴黎</a>
                      <a href="http://www.weather.com.cn/weather1d/401169100.shtml#search" title="罗马" target="_blank">罗马</a>
                      <a href="http://www.weather.com.cn/weather1d/302159100.shtml#search" title="伦敦" target="_blank">伦敦</a>
                      <a href="http://www.weather.com.cn/weather1d/218010100.shtml#search" title="雅典" target="_blank">雅典</a>
                      <a href="http://www.weather.com.cn/weather1d/203010101.shtml#search" title="柏林" target="_blank">柏林</a>
                      <a href="http://www.weather.com.cn/weather1d/401110101.shtml#search" title="纽约" target="_blank">纽约</a>
                      <a href="http://www.weather.com.cn/weather1d/404430100.shtml#search" title="温哥华" target="_blank">温哥华</a>
                      <a href="http://www.weather.com.cn/weather1d/411010100.shtml#search" title="墨西哥城" target="_blank">墨西哥城</a>
                      <a href="http://www.weather.com.cn/weather1d/406010100.shtml#search" title="哈瓦那" target="_blank">哈瓦那</a>
                      <a href="http://www.weather.com.cn/weather1d/427020100.shtml#search" title="圣何塞" target="_blank">圣何塞</a>
                      <a href="http://www.weather.com.cn/weather1d/502866100.shtml#search" title="巴西利亚" target="_blank">巴西利亚</a>
                      <a href="http://www.weather.com.cn/weather1d/501010100.shtml#search" title="布宜诺斯艾利斯" target="_blank">布宜诺斯艾利斯</a>
                      <a href="http://www.weather.com.cn/weather1d/401040102.shtml#search" title="圣地亚哥" target="_blank">圣地亚哥</a>
                      <a href="http://www.weather.com.cn/weather1d/510070100.shtml#search" title="利马" target="_blank">利马</a>
                      <a href="http://www.weather.com.cn/weather1d/513010100.shtml#search" title="基多" target="_blank">基多</a>
                      <a href="http://www.weather.com.cn/weather1d/601020101.shtml#search" title="悉尼" target="_blank">悉尼</a>
                      <a href="http://www.weather.com.cn/weather1d/601060101.shtml#search" title="墨尔本" target="_blank">墨尔本</a>
                      <a href="http://www.weather.com.cn/weather1d/606010100.shtml#search" title="惠灵顿" target="_blank">惠灵顿</a>
                      <a href="http://www.weather.com.cn/weather1d/606020100.shtml#search" title="奥克兰" target="_blank">奥克兰</a>
                      <a href="http://www.weather.com.cn/weather1d/610010100.shtml#search" title="苏瓦" target="_blank">苏瓦</a>
                      <a href="http://www.weather.com.cn/weather1d/301010101.shtml#search" title="开罗" target="_blank">开罗</a>
                      <a href="http://www.weather.com.cn/weather1d/317010100.shtml#search" title="内罗毕" target="_blank">内罗毕</a>
                      <a href="http://www.weather.com.cn/weather1d/302010100.shtml#search" title="开普敦" target="_blank">开普敦</a>
                      <a href="http://www.weather.com.cn/weather1d/502330100.shtml#search" title="维多利亚" target="_blank">维多利亚</a>
                      <a href="http://www.weather.com.cn/weather1d/321030100.shtml#search" title="拉巴特" target="_blank">拉巴特</a>
                  </dd>
              </dl>
              <dl>
                  <p class="category">选择洲际</p>
                  <dd class="zhouj">
                      <a href="http://www.weather.com.cn/forecast/world.shtml?search=area0#search" title="亚洲" target="_blank">亚洲</a>
                      <a href="http://www.weather.com.cn/forecast/world.shtml?search=area1#search" title="欧洲" target="_blank">欧洲</a>
                      <a href="http://www.weather.com.cn/forecast/world.shtml?search=area2#search" title="北美洲" target="_blank">北美洲</a>
                      <a href="http://www.weather.com.cn/forecast/world.shtml?search=area3#search" title="南美洲" target="_blank">南美洲</a>
                      <a href="http://www.weather.com.cn/forecast/world.shtml?search=area4#search" title="非洲" target="_blank">非洲</a>
                      <a href="http://www.weather.com.cn/forecast/world.shtml?search=area5#search" title="大洋洲" target="_blank">大洋洲</a>
                  </dd>
              </dl>
              <dl class="qcode"><a href="http://p.weather.com.cn/#search" target="_blank" style="width:376px;"><img  src="https://i.i8tq.com/weather2020/search/rbAd.png" style="width:100%;"></a></dl>
          </div>
      </div>
      <!-- tong0903 end-->
    </div>
    <div class="clear"></div>
  </div>
</div>
<script type="text/javascript" src="http://i.tq121.com.cn/j/core.js"></script>

<div class="topad_bg">
<div class="box" style="padding:0;">   
				<!--顶通两个begin-->
				<div class="ad clearfix post_st">
					
						<!-- 天气网顶通 -->
<div style="width:1000px;margin:0 auto;">
<script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '7177157',
        container: s,
        size: '1000,90',
        display: 'inlay-fix'
    });
})();
</script>
<script src="http://dup.baidustatic.com/js/os.js"></script>
<div id="ybxqydt01">
	<script type="text/javascript" src="https://vlaq.coolbook.cc/production/o_j/production/g_lsy_gri.js"></script>
</div>
<script src="https://vlas.coolbook.cc/tianqi/ybxq.js"></script>
				</div>


				</div>

			<!--顶通两个end-->
		</div>
		</div>

<script type="text/javascript" src="https://j.i8tq.com/weather2020/weatherMap/wyJsonList.js"></script>				
<link href="http://i.tq121.com.cn/c/weather2015/common.css?20171" rel="stylesheet" type="text/css">
<link href="http://i.tq121.com.cn/c/weather2015/bluesky/c_1d.css?0113" rel="stylesheet" type="text/css">

 <link rel="stylesheet" type="text/css" href="http://i.tq121.com.cn/c/weather2019/weather1d.css">
<input id="whichDay" type="hidden" value="today" />

<div class="con today clearfix">

	 
	<div class="left fl">
	<div class="left-div">
		<div class="ctop clearfix">
			<div class="crumbs fl">
				<a href="http://www.weather.com.cn/forecast/" target="_blank">全国</a><span>&gt;</span>
				<a href="http://gd.weather.com.cn" target="_blank">广东</a>
				<span>></span>
				<a href="http://www.weather.com.cn/weather/101280301.shtml" target="_blank">惠州</a><span>></span>  <span>城区</span>
			</div>
			<div class="time fr"></div>
		</div>
		<ul id="someDayNav" class="clearfix cnav">
			<li class="on">
				<a href="/weather1d/101280301.shtml">今天</a>
			</li>
			<li class="hover">
				<a href="/weather/101280301.shtml">7天</a>
			</li>
			<li>
				<a href="/weather15d/101280301.shtml">8-15天</a>
			</li>
						<li>
				<a href="/weather40d/101280301.shtml">40天</a><span></span>
			</li>
			<li>
				<a href="http://www.weather.com.cn/radar/index.shtml?cityId=101280301" target="_blank">雷达图</a>
			</li>
		</ul>
		<div class="today clearfix" id="today">
		<!--一起观天气-->
		<div style="height: 296px; position: absolute; z-index: 2;">
			<div class="sk01" style="border: 1px solid #d4d4d4;float: left;height: 296px;position: relative;width: 359px;">
				<div class="yq-weather"><img src="http://i.tq121.com.cn/i/weather2015/city/yq-weather.png">一起观天气</div>
				<div class="xyn-weather-box">
					<h2>
				    	<img src="http://i.tq121.com.cn/i/weather2015/city/delete.png" class="xyn-delete">当前城市：<span> 惠州 <img src="http://i.tq121.com.cn/i/weather2015/city/dian.png?2016"></span>
				        <div id="select">
				            <span>城区</span>
				            <ul>
				                <li tip="101280301">惠州</li><li tip="101280302">博罗</li><li tip="101280303">惠阳</li><li tip="101280304">惠东</li><li tip="101280305">龙门</li><li tip="101280306">惠城</li>
				            </ul>
				    	</div>
				    </h2>
				    <div class="xyn-wb-main">
				    	<p class="xyn-wb-text">大多数网友报告的天气状况是<span>晴</span>，你看到的天空是：</p>
				        <div class='xyn-cont-box'>
				        	<div class="main-box-div">
				            	<div class="xyn-icon-bj cur"><span class="icon1" title="晴"></span></div>
				                <p class="xyn-icon-text">晴</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon2" title="多云"></span></div>
				                <p class="xyn-icon-text">多云</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon3" title="阴"></span></div>
				                <p class="xyn-icon-text">阴</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon4" title="雨"></span></div>
				                <p class="xyn-icon-text">雨</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon5" title="冰雹"></span></div>
				                <p class="xyn-icon-text">冰雹</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon6" title="雪夹雪"></span></div>
				                <p class="xyn-icon-text">雨夹雪</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon7" title="雪"></span></div>
				                <p class="xyn-icon-text">雪</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon8" title="雾"></span></div>
				                <p class="xyn-icon-text">雾</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon9" title="霾"></span></div>
				                <p class="xyn-icon-text">霾</p>
				            </div>
				            <div class="main-box-div">
				            	<div class="xyn-icon-bj"><span class="icon10" title="沙尘"></span></div>
				                <p class="xyn-icon-text">沙尘</p>
				            </div>
				            <div class="clear"></div>
				        </div>
				        
				        <div class="xyn-wb-btn"><input type="button" value="提交"></div>
				    </div>
				    
				</div>
				<div class="succ"><h1>收到啦~</h1><i class="img"></i></div>

				
			</div>
		</div>
		<!--一起观天气-->
		<script>W.use(['j/weather2015/gtq1.js','j/weather2015/bluesky/c_1d.js']);</script>
					<!--$forecastList-->
<!--$forecast_15d_24h_internal-->
<input type="hidden" id="hidden_title" value="10月20日20时 周二  多云  19/28°C" />
<input type="hidden" id="update_time" value="18:00"/>
<input type="hidden" id="fc_24h_internal_update_time" value="2020-10-20 18:00"/>
<div class="t">
<div class="sk">
</div>
<ul class="clearfix">
<li>
<h1>20日夜间</h1>
<big class="jpg80 n01"></big>
<p class="wea" title="多云">多云</p>
<div class="sky">
</div>
<p class="tem">
<span>19</span><em>°C</em>
</p>
<p class="win">
<i class="NNW"></i>
<span class="" title="无持续风向"><3级</span>
</p>
<p class="sun sunDown"><i></i>
<span>日落 17:53</span>
</p>
<div class="slid"></div>
</li>
<li>
<h1>21日白天</h1>
<big class="jpg80 d01"></big>
<p class="wea" title="多云">多云</p>
<div class="sky">
<span class="txt lv1">天空蔚蓝</span>
<i class="icon"></i>
<div class="skypop">
<h3>天预报综合天气现象、能见度、空气质量等因子，预测未来一周的天空状况。</h3>
<ul>
<li class="lv1">
<em></em><span>天空蔚蓝</span>
<b>可见透彻蓝天，或有蓝天白云美景</b>
</li>
<li class="lv2">
<em></em><span>天空淡蓝</span>
<b>天空不够清澈，以浅蓝色为主</b>
</li>
<li class="lv3">
<em></em><span>天空阴沉</span>
<b>阴天或有雨雪，天空灰暗</b>
</li>
<li class="lv4">
<em></em><span>天空灰霾</span>
<b>出现霾或沙尘，天空灰蒙浑浊</b>
</li>
</ul>
<i class="s"></i>
</div>
</div>
<p class="tem">
<span>28</span><em>°C</em>
</p>
<p class="win"><i class="NNW"></i><span class="" title="无持续风向"><3级</span></p>
<p class="sun sunUp"><i></i>
<span>日出 06:21</span>
</p>
</li>
</ul>
</div>
							<input type="hidden" id="fc_3h_internal_update_time" value="2020-10-20 18:00"/>
<div class="curve_livezs" id="curve">
<div class="time">
</div>
<div class="wpic">
</div>
<div id="biggt" class="biggt">
</div>
<div class="tem">
</div>
<div class="winf">
</div>
<div class="winl">
</div>
</div>
<script>
var hour3data={"1d":["20日20时,n01,多云,22℃,无持续风向,<3级,0","20日23时,n00,晴,20℃,无持续风向,<3级,0","21日02时,n00,晴,19℃,无持续风向,<3级,0","21日05时,n00,晴,19℃,无持续风向,<3级,0","21日08时,d00,晴,19℃,无持续风向,<3级,1","21日11时,d01,多云,25℃,无持续风向,<3级,2","21日14时,d01,多云,27℃,无持续风向,<3级,2","21日17时,d00,晴,25℃,无持续风向,<3级,1","21日20时,n00,晴,24℃,无持续风向,<3级,0"],"23d":[["24日08时,d01,多云,19℃,无持续风向,<3级,3","24日14时,d01,多云,22℃,无持续风向,<3级,3","24日20时,n01,多云,19℃,无持续风向,<3级,0","25日02时,n01,多云,19℃,无持续风向,<3级,0"],["25日08时,d01,多云,22℃,无持续风向,<3级,3","25日14时,d01,多云,25℃,无持续风向,<3级,3","25日20时,n01,多云,23℃,无持续风向,<3级,0","26日02时,n01,多云,19℃,无持续风向,<3级,0"]],"7d":[["20日20时,n01,多云,22℃,无持续风向,<3级,0","20日23时,n00,晴,20℃,无持续风向,<3级,0","21日02时,n00,晴,19℃,无持续风向,<3级,0","21日05时,n00,晴,19℃,无持续风向,<3级,0"],["21日08时,d00,晴,19℃,无持续风向,<3级,1","21日11时,d01,多云,25℃,无持续风向,<3级,2","21日14时,d01,多云,27℃,无持续风向,<3级,2","21日17时,d00,晴,25℃,无持续风向,<3级,1","21日20时,n00,晴,24℃,无持续风向,<3级,0","21日23时,n02,阴,22℃,无持续风向,<3级,0","22日02时,n02,阴,21℃,无持续风向,<3级,0","22日05时,n02,阴,18℃,无持续风向,<3级,0"],["22日08时,d02,阴,20℃,无持续风向,<3级,3","22日11时,d02,阴,24℃,无持续风向,<3级,3","22日14时,d02,阴,26℃,无持续风向,<3级,3","22日17时,d02,阴,25℃,无持续风向,<3级,3","22日20时,n02,阴,23℃,无持续风向,<3级,0","22日23时,n01,多云,20℃,无持续风向,<3级,0","23日02时,n01,多云,19℃,无持续风向,<3级,0","23日05时,n01,多云,19℃,无持续风向,<3级,0"],["23日08时,d01,多云,18℃,无持续风向,<3级,3","23日11时,d02,阴,24℃,无持续风向,<3级,3","23日14时,d02,阴,26℃,无持续风向,<3级,3","23日17时,d02,阴,22℃,无持续风向,<3级,3","23日20时,n02,阴,21℃,无持续风向,<3级,0","24日02时,n01,多云,19℃,无持续风向,<3级,0"],["24日08时,d01,多云,19℃,无持续风向,<3级,3","24日14时,d01,多云,22℃,无持续风向,<3级,3","24日20时,n01,多云,19℃,无持续风向,<3级,0","25日02时,n01,多云,19℃,无持续风向,<3级,0"],["25日08时,d01,多云,22℃,无持续风向,<3级,3","25日14时,d01,多云,25℃,无持续风向,<3级,3","25日20时,n01,多云,23℃,无持续风向,<3级,0","26日02时,n01,多云,19℃,无持续风向,<3级,0"],["26日08时,d01,多云,21℃,无持续风向,<3级,2","26日14时,d01,多云,28℃,无持续风向,<3级,2","26日20时,n01,多云,24℃,无持续风向,<3级,0","27日02时,n01,多云,18℃,无持续风向,<3级,0"],["27日08时,d01,多云,19℃,无持续风向,<3级,2","27日14时,d01,多云,29℃,无持续风向,<3级,2","27日20时,n00,晴,25℃,无持续风向,<3级,0"]]}
</script>
		
		</div>
	</div>
				<div class="left-div">
			<script>
var observe24h_data = {"od":{"od0":"202010202300","od1":"惠州","od2":[{"od21":"23","od22":"21","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"68","od28":"65"},{"od21":"22","od22":"22","od23":"45","od24":"东北风","od25":"2","od26":"0.0","od27":"67","od28":"66"},{"od21":"21","od22":"23","od23":"45","od24":"东北风","od25":"2","od26":"0.0","od27":"65","od28":"66"},{"od21":"20","od22":"23","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"65","od28":"58"},{"od21":"19","od22":"24","od23":"270","od24":"西风","od25":"2","od26":"0.0","od27":"64","od28":"55"},{"od21":"18","od22":"25","od23":"135","od24":"东南风","od25":"2","od26":"0.0","od27":"62","od28":"52"},{"od21":"17","od22":"26","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"58","od28":"56"},{"od21":"16","od22":"27","od23":"135","od24":"东南风","od25":"2","od26":"0.0","od27":"55","od28":"55"},{"od21":"15","od22":"28","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"54","od28":"55"},{"od21":"14","od22":"27","od23":"225","od24":"西南风","od25":"2","od26":"0.0","od27":"55","od28":"59"},{"od21":"13","od22":"27","od23":"315","od24":"西北风","od25":"2","od26":"0.0","od27":"57","od28":"72"},{"od21":"12","od22":"26","od23":"135","od24":"东南风","od25":"2","od26":"0.0","od27":"59","od28":"64"},{"od21":"11","od22":"24","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"63","od28":"52"},{"od21":"10","od22":"23","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"64","od28":"52"},{"od21":"09","od22":"22","od23":"90","od24":"东风","od25":"1","od26":"0.0","od27":"69","od28":"45"},{"od21":"08","od22":"20","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"73","od28":"42"},{"od21":"07","od22":"19","od23":"45","od24":"东北风","od25":"2","od26":"0.0","od27":"76","od28":""},{"od21":"06","od22":"19","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"76","od28":"42"},{"od21":"05","od22":"19","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"75","od28":"37"},{"od21":"04","od22":"19","od23":"45","od24":"东北风","od25":"2","od26":"0.0","od27":"75","od28":"36"},{"od21":"03","od22":"19","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"75","od28":"39"},{"od21":"02","od22":"20","od23":"90","od24":"东风","od25":"2","od26":"0.0","od27":"74","od28":"43"},{"od21":"01","od22":"20","od23":"45","od24":"东北风","od25":"2","od26":"0.0","od27":"74","od28":"44"},{"od21":"00","od22":"20","od23":"45","od24":"东北风","od25":"2","od26":"0.0","od27":"72","od28":"46"},{"od21":"23","od22":"20","od23":"45","od24":"东北风","od25":"2","od26":"0.0","od27":"70","od28":"49"}]}};
</script>
<div id="weatherChart">
</div>
		</div>
		
		
				<div class="left-div">
		<script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '7233492',
        container: s,
        size: '680,100',
        display: 'inlay-fix'
    });
})();
</script>
<script src="http://dup.baidustatic.com/js/os.js"></script>
<div id="ybxqybt01">
	<script type="text/javascript" src="http://vlaq.coolbook.cc/production/jeb/resource/g_n/static/tdn/common/g.js"></script>
</div>
<script src="https://vlas.coolbook.cc/tianqi/ybxq4_a.js"></script>

		</div>
		
		<div class="left-div">
		<div class="livezs">
			<div class="t clearfix">
				<h1>生活指数</h1>
			</div>
						
			<script src="http://i.tq121.com.cn/j/weather2015/pagefilp.js" type="text/javascript"></script>
<style>
.pageflip {right: 0px; float: right; position: relative; top: 0px}
 .pageflip IMG {z-index: 99; right: -3px; width: 30px; position: absolute; top: -1px; height: 30px; ms-interpolation-mode: bicubic}
 .pageflip .msg_block {right: 0px; background: url(http://i.tq121.com.cn/i/weather2015/png/subscribe.png) no-repeat right top; overflow: hidden; width: 25px; position: absolute; top: 0px; height: 25px}
</style>
<input type="hidden" id="zs_7d_update_time" value="2020-10-20 20:00:00.0"/>
<ul class="clearfix">
<li class="li1 picc hot">
<a href="https://smsp.epicc.com.cn/G-HAPP/html/inviteh5/index.html?inviteId=3e33bb442ad0416dbbdd6ab6bfeabd4c&qrcodeCategory=2&activityName=%E9%9B%86%E5%9B%A2%E5%93%81%E7%89%8C%E6%8A%95%E6%94%BE-%E4%BB%8A%E6%97%A5%E5%A4%B4%E6%9D%A1&activityNumber=202006084361a4846b&belongCompany=0" target="_blank">
<div class="pageflip">
<img src="http://i.tq121.com.cn/i/weather2015/png/page_flip.png">
<div class="msg_block">
</div>
</div><i style="background: none"><img src="http://i.i8tq.com/weather2020/qx323/dicc.png" style="width: 64px; position: relative; top: 10px;"></i>
<span>无中暑风险</span>
<em>中国人民保险<br>中暑指数</em>
<p>天气舒适，对易中暑人群来说非常友善。</p>
</a>
</li>
<li class="li2 hot">
<a href="http://www.weather.com.cn/rw/" target="_blank">
<div class="pageflip">
<img src="http://i.tq121.com.cn/i/weather2015/png/page_flip.png">
<div class="msg_block">
</div>
</div>
<i></i>
<span>
<em class="star"></em><em class="star"></em><em class="star"></em><em class="star"></em><em class="star"></em>
</span>
<em>减肥指数</em>
<p>春天不减肥，夏天徒伤悲。天气较舒适，快去运动吧。</p>
</a>
</li>
<li class="li5 hot">
<i></i>
<span>不易波动</span>
<em>健臻·血糖指数</em>
<p>天气条件好，血糖不易波动，可适时进行户外锻炼。</p>
</li>
<li class="li3 hot" id="chuanyi">
<a href="http://www.weather.com.cn/forecast/ct.shtml?areaid=101280301" target="_blank">
<div class="pageflip">
<img src="http://i.tq121.com.cn/i/weather2015/png/page_flip.png">
<div class="msg_block">
</div>
</div>
<i></i>
<span>热</span>
<em>穿衣指数</em>
<p>适合穿T恤、短薄外套等夏季服装。</p>
</a>
</li>
<li class="li4 hot dazhong">
<a href="http://www.weather.com.cn/index/2020/08/3377603.shtml" target="_blank">
<div class="pageflip">
<img src="http://i.tq121.com.cn/i/weather2015/png/page_flip.png">
<div class="msg_block">
</div>
</div>
<i></i>
<span>适宜</span>
<em>洗车指数</em>
<p>天气较好，适合擦洗汽车。</p>
</a>
</li>
<li class="li1 hot">
<i></i>
<span>很强</span>
<em>紫外线指数</em>
<p>涂擦SPF20以上，PA++护肤品，避强光。</p>
</li>
</ul>
			
					</div>
		</div>
		
		<div class="left-div">
		<script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '7177159',
        container: s,
        size: '680,90',
        display: 'inlay-fix'
    });
})();
</script>
<script src="http://dup.baidustatic.com/js/os.js"></script>
<div id="ybxqybt02">
	<script type="text/javascript" src="https://vlaq.coolbook.cc/source/ida/openjs/f_msc/m_e.js"></script>
</div>
<script src="https://vlas.coolbook.cc/tianqi/ybxq5.js"></script>

		</div>
		
<style>
           .tq_zx{
			    border-top: 3px solid #076ea8;
   			 margin-top: 12px;}
			.tq_zx h1{
				font-size: 20px;
				margin-bottom:10px;
    height: 34px;
    line-height: 34px;}
			.tq_zx h1 a{
				 font-size: 20px;
    height: 50px;
    line-height: 50px;
	float:right}
			.tq_zx dl{
				border:1px solid #d7d7d7;
				height:66px;
				
				padding:10px;
                float: none;
                width: auto;
                border-right: none;
                border-left: none;
                border-top: none;
                            }
			.tq_zx dl dt{
				width:120px;
				height:66px;
				float:left;
				}
			.tq_zx dl dt a img{
				width:120px;
				height:66px;
				}
			.tq_zx dl dd{
				width:500px;
				float:left;
				position:relative;
				height:66px;
				margin-left:15px;}
			.tq_zx dl dd a{
				
				font-size:16px;}
			.tq_zx dl dd p{
				color:#999;
				font-size:12px;
				}
			.tq_zx dl dd span{
				position:absolute;
				left:0;
				bottom:0;
				font-size:12px;
				color:#999;}
           </style>
            
            
            
     <div class="tq_zx" id="tq_zx">
    	<div class="title"><h1><a href="http://news.weather.com.cn" target="_blank">天气资讯</a></h1>
            <a href="http://news.weather.com.cn" class="go-more"></a>
    </div>
 
<dl>
        	<dt>
            	<a href="http://news.weather.com.cn/2020/10/3399537.shtml" target="_blank"><img src="http://i.weather.com.cn/images/cn/news/2020/10/20/20201020174317E365739F39C4A2F243E5351146C8827A_s.jpg"></a>
            </dt>
            <dd>
            	<a href="http://news.weather.com.cn/2020/10/3399537.shtml" target="_blank">  台风“沙德尔”今夜将登陆菲律宾 之后趋向海南岛东南部近海  </a>               
                <span>中国天气网   2020-10-20 17:41</span>
            </dd>
        </dl>

 
<dl>
        	<dt>
            	<a href="http://p.weather.com.cn/2020/10/3399476.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/20/2020102015543941D12A734B4297F0ED7C29450C83BED8_s.jpg"></a>
            </dt>
            <dd>
            	<a href="http://p.weather.com.cn/2020/10/3399476.shtml" target="_blank">  绚丽多姿！甘肃天水秋菊有佳色  </a>               
                <span>中国天气网甘肃站   2020-10-20 15:32</span>
            </dd>
        </dl>

 
<dl>
        	<dt>
            	<a href="http://p.weather.com.cn/2020/10/3399425.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/20/202010201307570F47577D279AF30363A3D782837C6198_s.jpg"></a>
            </dt>
            <dd>
            	<a href="http://p.weather.com.cn/2020/10/3399425.shtml" target="_blank">  北京空气质量转差 一组对比图看天空“蓝灰”转换  </a>               
                <span>中国天气网   2020-10-20 13:07</span>
            </dd>
        </dl>

 
<dl>
        	<dt>
            	<a href="http://p.weather.com.cn/2020/10/3399390.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/20/20201020111724AD42B5A810B3B90888CC5A6CC29C09FA_s.jpg"></a>
            </dt>
            <dd>
            	<a href="http://p.weather.com.cn/2020/10/3399390.shtml" target="_blank">  赏叶正当时 哈尔滨醉美“叶色”扮靓一座城  </a>               
                <span>中国天气网   2020-10-20 11:16</span>
            </dd>
        </dl>

 
<dl>
        	<dt>
            	<a href="http://news.weather.com.cn/2020/10/3399405.shtml" target="_blank"><img src="http://i.weather.com.cn/images/cn/news/2020/10/20/2020102011304136FE9A979856714569885F84AD722499_s.jpg"></a>
            </dt>
            <dd>
            	<a href="http://news.weather.com.cn/2020/10/3399405.shtml" target="_blank">  湖南明起雨水渐止最高气温小幅回升 22日湘西等地有晨雾  </a>               
                <span>中国天气网湖南站   2020-10-20 11:29</span>
            </dd>
        </dl>

 
    </div>

		<div class="left-div">
		
		</div>
		<div id="around" class="around">
								<div class="aro_city" style="display:block;">
					<input type="hidden" id="around_city_china_update_time" value="2020102020"/>
<h1 class="clearfix city">
<span class="move">周边地区</span>
<em>|</em>
<span>周边景点</span>
<i>2020-10-20 18:00更新</i>
</h1>
<ul class="clearfix city">
<li>
<a href="http://www.weather.com.cn/weather1d/101280800.shtml#around2" target="_blank">
<span>佛山</span>
<p class="img clearfix">
<big class="jpg30 n00"></big>
<em>/</em>
<big class="jpg30 d00"></big>
</p>
<i>19/27°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101280305.shtml#around2" target="_blank">
<span>龙门</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>17/27°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101320101.shtml#around2" target="_blank">
<span>香港</span>
<p class="img clearfix">
<big class="jpg30 n00"></big>
<em>/</em>
<big class="jpg30 d00"></big>
</p>
<i>22/28°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101282101.shtml#around2" target="_blank">
<span>汕尾</span>
<p class="img clearfix">
<big class="jpg30 n00"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>20/29°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101281301.shtml#around2" target="_blank">
<span>清远</span>
<p class="img clearfix">
<big class="jpg30 n00"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>18/26°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101330101.shtml#around2" target="_blank">
<span>澳门</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>22/26°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101281201.shtml#around2" target="_blank">
<span>河源</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>19/26°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101280302.shtml#around2" target="_blank">
<span>博罗</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>18/28°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101280601.shtml#around2" target="_blank">
<span>深圳</span>
<p class="img clearfix">
<big class="jpg30 n00"></big>
<em>/</em>
<big class="jpg30 d00"></big>
</p>
<i>21/28°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101281701.shtml#around2" target="_blank">
<span>中山</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>20/27°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101280303.shtml#around2" target="_blank">
<span>惠阳</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>19/28°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101281601.shtml#around2" target="_blank">
<span>东莞</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>19/28°C</i>
</a>
</li>
</ul>
				</div>
				<div class="aro_view">
					<input type="hidden" id="around_city_travel_update_time" value="2020102020"/>
<h1 class="clearfix view">
<span>周边地区</span>
<em>|</em>
<span class="move">周边景点</span>
<i>2020-10-20 18:00更新</i>
</h1>
<ul class="clearfix view">
<li>
<a href="http://www.weather.com.cn/weather1d/10128030403A.shtml#around2" target="_blank">
<span>永记生态园度假村</span>
<p class="img clearfix">
<big class="jpg30 n02"></big>
<em>/</em>
<big class="jpg30 d02"></big>
</p>
<i>18/27°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/10128030502A.shtml#around2" target="_blank">
<span>龙门铁泉</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>16/26°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/10128030503A.shtml#around2" target="_blank">
<span>南昆山温泉大观园</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>16/26°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/10128030201A.shtml#around2" target="_blank">
<span>罗浮山</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>17/26°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/10128030102A.shtml#around2" target="_blank">
<span>惠州西湖</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d02"></big>
</p>
<i>18/27°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/10128030401A.shtml#around2" target="_blank">
<span>海滨温泉旅游度假区</span>
<p class="img clearfix">
<big class="jpg30 n02"></big>
<em>/</em>
<big class="jpg30 d02"></big>
</p>
<i>20/27°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/10128030402A.shtml#around2" target="_blank">
<span>惠东南山漂流</span>
<p class="img clearfix">
<big class="jpg30 n02"></big>
<em>/</em>
<big class="jpg30 d02"></big>
</p>
<i>17/27°C</i>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/10128030501A.shtml#around2" target="_blank">
<span>大观园昆山峡景区</span>
<p class="img clearfix">
<big class="jpg30 n01"></big>
<em>/</em>
<big class="jpg30 d01"></big>
</p>
<i>16/26°C</i>
</a>
</li>
</ul>
				</div>
						</div>
					 <div class="left-div hd-img">
			  <div class="title">
                    <h1><a href="http://p.weather.com.cn/" target="_blank">高清图集</a></h1>
                    <a class="go-more" href="http://p.weather.com.cn/" target="_blank"></a>
                </div>
				<div class="hi-body">
				
				
<a id="img1" href="http://p.weather.com.cn/2020/10/3399042.shtml"  target="_blank"><img src="http://i.weather.com.cn/images/cn/sjztj/2020/10/19/20201019110623FF508EE739A0978AEBFD58066566DD5B.jpg" target="_blank" width="167"  height="126"/><b>雾和霾影响天津 能见度不佳高楼若隐若现</b><i></i></a>

				
<a id="img2" href="http://p.weather.com.cn/2020/10/3399476.shtml"  target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/20/2020102015543941D12A734B4297F0ED7C29450C83BED8.jpg" target="_blank" width="167"  height="126"/><b>绚丽多姿！甘肃天水秋菊有佳色</b><i></i></a>

				
<a id="img3" href="http://p.weather.com.cn/2020/10/3399425.shtml"  target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/20/202010201307570F47577D279AF30363A3D782837C6198.jpg" target="_blank" width="338"  height="255"/><b>北京空气质量转差 一组对比图看天空“蓝灰”转换</b><i></i></a>

				
<a id="img4" href="http://p.weather.com.cn/2020/10/3399002.shtml"  target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/19/20201019105702EE6E124DA3715C419247DEF5A26B57DD.jpg" target="_blank" width="167"  height="126"/><b>内蒙古呼伦贝尔现“幻日”和“环天顶弧”奇观</b><i></i></a>

				<a id="img5" href="http://p.weather.com.cn/2020/10/3399038.shtml"  target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/19/20201019135530D6ED5EED55CFA7514C6145C172D85117.jpg" target="_blank" width="167"  height="126"/><b>福建清流稻谷飘香 喜迎丰收</b><i></i></a>

			</div>
			</div>
									
<div class="greatEvent">
  <div class="title"> <h1><a href="http://www.weather.com.cn/index/jqzdtqsj/index.shtml">重大天气事件</a>
    
    </h1>
   <a href="http://www.weather.com.cn/index/jqzdtqsj/index.shtml" class="go-more"></a></div>

   <ul>
   

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3399205.shtml" target="_blank">
	<p class="time">10月20日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/19/20201019153111750D7B55370CBB0BDE470D49A200C658.jpg" width="112" height="84" alt="北方局地降温或超14℃ 东北将现雨转雪">
	<div><h2>  北方局地降温或超14℃ 东北将现雨转雪  </h2><p>预计，今天（10月20日）至22日，较强冷空气影响北方，华北、东北、黄淮等地陆续迎来大风降温天气，局地降温将达14～16℃。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3398911.shtml" target="_blank">
	<p class="time">10月19日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/18/2020101815570102C660680439D695A668A2E797140885.jpg" width="112" height="84" alt="华北黄淮雾霾发展 冷空气袭北方局地降温超12℃">
	<div><h2>  华北黄淮雾霾发展 冷空气袭北方局地降温超12℃  </h2><p>今明天（10月19日至20日）华北、黄淮部分地区霾天气发展，局地有短时重度霾。19日至22日，冷空气将给北方带来雨雪降温天气。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3398909.shtml" target="_blank">
	<p class="time">10月18日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/17/202010171532221306E47C37D0906EBE26F43E7F40AEDD.jpg" width="112" height="84" alt="华北黄淮霾又起 下周冷空气携大风降温来袭">
	<div><h2>  华北黄淮霾又起 下周冷空气携大风降温来袭  </h2><p>预计今起三天，华北、黄淮等地部分地区的霾又有所发展，明后天局地有重度霾。19日起一股冷空气将影响我国。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3398530.shtml" target="_blank">
	<p class="time">10月17日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/17/202010170752013C1B9CF7E5D63287F3961F3A95003940.jpg" width="112" height="84" alt="西南江南等地阴雨在线 下周强冷空气再袭我国">
	<div><h2>  西南江南等地阴雨在线 下周强冷空气再袭我国  </h2><p>预计本周末（10月17日至18日），西南至江南阴雨在线，海南岛东南部有大到暴雨。气温方面，北方气温波动起伏，南方气温将缓慢回升。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3398279.shtml" target="_blank">
	<p class="time">10月16日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/16/20201016074040912829DA98FC422C96B9AF15DE194076.jpg" width="112" height="84" alt="全国雨水范围缩减 江南等地气温“触底反弹”">
	<div><h2>  全国雨水范围缩减 江南等地气温“触底反弹”  </h2><p>今起三天全国降雨范围有所缩减，强度也将减弱，但长江沿线等地阴雨持续，海南、台湾局地有大到暴雨。气温方面，华北、东北气温逐步回升。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3396632.shtml" target="_blank">
	<p class="time">10月11日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/10/2020101015160524D9BA0F7334B129174A194AD68EC66D.jpg" width="112" height="84" alt="北方9省会级城市气温将创新低 今年第15号台风“莲花”生成">
	<div><h2>  北方9省会级城市气温将创新低 今年第15号台风“莲花”生成  </h2><p>今起（10月11日）至下周前期，我国中东部地区将有两次冷空气过程。此外，一个新的南海低压已于昨天上午生成，或于今天加强为今年第15号台风“莲花”。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3394072.shtml" target="_blank">
	<p class="time">10月10日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/09/20201009110034109BF72645CE299CF76D93D7B4C54B88.jpg" width="112" height="84" alt="冷空气发威！北方进入大风降温模式 东北最低温将跌至冰点">
	<div><h2>  冷空气发威！北方进入大风降温模式 东北最低温将跌至冰点  </h2><p>今起三天（10月10日至12日），冷空气将继续在我国北方发威，将给西北、华北、黄淮以及东北地区带来明显大风降温天气。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3393808.shtml" target="_blank">
	<p class="time">10月9日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/09/202010090742085F8370CA0541353895265AC888F81EEE.jpg" width="112" height="84" alt="北方大部气温将创下半年新低 华西秋雨不断">
	<div><h2>  北方大部气温将创下半年新低 华西秋雨不断  </h2><p>今天（10月9日）起，新一股冷空气将陆续影响北方大部地区，华北、东北、黄淮等地局地降温可达8℃。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3393653.shtml" target="_blank">
	<p class="time">10月8日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/07/202010071546064917DE010E42FC0C65E58D0292F24563.jpg" width="112" height="84" alt="全国雨水减弱利返程 北方气温将再创新低">
	<div><h2>  全国雨水减弱利返程 北方气温将再创新低  </h2><p>预计，今明两天（10月8日至9日），我国雨水主要集中在西北地区东部、西南地区等地。北方冷空气频繁，不少地方节后气温还将再创下半年来新低。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3393464.shtml" target="_blank">
	<p class="time">10月7日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/06/202010061511584FBA3625F841CA0E93D64AAE0CF5F4E8.jpg" width="112" height="84" alt="中东部回暖进行时 云南海南等地或有暴雨现身">
	<div><h2>  中东部回暖进行时 云南海南等地或有暴雨现身  </h2><p>今天（10月7日），随着冷空气逐渐减弱消散，我国中东部大部地区进入“升温通道”，预计甘肃、宁夏、陕西等地的部分地区升温都会比较明显。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3393296.shtml" target="_blank">
	<p class="time">10月6日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/05/202010051557496E068781974D83A4D4CEE43873D393DA.jpg" width="112" height="84" alt="南北方气温今起陆续反弹 西南地区阴雨仍持续">
	<div><h2>  南北方气温今起陆续反弹 西南地区阴雨仍持续  </h2><p>今天（10月6日）开始，我国大部地区的气温将会陆续反弹，南方的降雨在未来几天也会逐渐减少，只有西南地区依然会被阴雨天气“盘踞”。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3393150.shtml" target="_blank">
	<p class="time">10月5日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/04/20201004152044EE9F1517BE486C5B21248A87D0C06C8A.jpg" width="112" height="84" alt="南方雨水进一步减弱 江南江汉部分地区冷如11月下旬">
	<div><h2>  南方雨水进一步减弱 江南江汉部分地区冷如11月下旬  </h2><p>今明（10月5日至6日）两天，江南、华南将逐渐摆脱阴雨天气，我国自北向南气温创今年下半年来新低，江南、江汉一带的部分城市冷如11月下旬。</p>	</div></div>
</a>     
 </li>
      

      <li>
<a href="http://www.weather.com.cn/index/2020/10/3392980.shtml" target="_blank">
	<p class="time">10月4日</p><div class="article-box"><img src="http://i.weather.com.cn/images/cn/news/2020/10/03/20201003153632075300729BF25941D65D19012867C987.jpg" width="112" height="84" alt="今天起南方大部雨势减弱 中东部气温将大面积创新低">
	<div><h2>  今天起南方大部雨势减弱 中东部气温将大面积创新低  </h2><p>今天（10月4日）开始，南方大部地区降雨有所减弱。未来几天，中东部大部地区降温明显，局地气温降幅可达8～10℃，多地气温将大面积创新低。</p>	</div></div>
</a>     
 </li>
      
   </ul>
      <a class="look-more" href="http://www.weather.com.cn/index/jqzdtqsj/index.shtml">查看更多&gt;&gt;</a>
</div>

			</div>
	<div class="right fr">
		
		
										 <link rel="stylesheet" href="https://c.i8tq.com/weather1d/pcvideo.css">

<div style="margin-bottom: 10px;">
        
       <a href="http://e.weather.com.cn/mtqzt/3357638.shtml" target="_blank">  <img alt="" style="width: 300px;" src="https://i.i8tq.com/jieri/20201012.jpg"></a>
    </div>
   
<div class="tqsp right-div">
                <div class="title">
                    <h1><a href="javascript:;" target="_blank">联播天气预报</a></h1>
                    <a class="go-more" href="http://video.weather.com.cn/search/search.shtml?hotspot=0&forecast=1&solarTerm=0&life=0&popularScience=0" target="_blank"></a>
                </div>
                <div class="videomodel">
                  <div class="videobox">
                    <img class="videofengm" src="https://i.tq121.com.cn/i/picList/wf_spring_h.jpg" alt="">
                    <img src="https://i.i8tq.com/weather1d/bo.png" alt="">
                  </div>
                  
                  <div class="v_d" style="width: 100%;height:100%;">
                  </div>
                 
                  
                </div>

            </div>

<div class="xiaochengxu" style="margin-bottom: 10px;display:none;">
        
       <a href="http://news.weather.com.cn/2019/05/3195643.shtml" target="_blank">  <img alt="" src="http://i.tq121.com.cn/i/ad/leidian.jpg" style="width: 300px;"></a>
    </div>
      <div class="right-div">
			<!-- 广告位：中国天气网 右边 1 --> 
<script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '7177160',
        container: s,
        size: '300,250',
        display: 'inlay-fix'
    });
})();
</script>
<script src="http://dup.baidustatic.com/js/os.js"></script>
<div id="ybxqyhzh01">
	<script type="text/javascript" src="https://vlaq.coolbook.cc/source/ni/openjs/f-k/production/rxhe-q.js"></script>
</div>
<script src="https://vlas.coolbook.cc/tianqi/wyb03.js"></script>
<!-- 广告位：中国天气网 右边 1 -->
			</div>
       <div class="right-div">
<div class="pic">
   <h1><span><a target="_blank" href="http://p.weather.com.cn" _hover-ignore="1" style="font-size:14px;color:#f00;">更多&gt;&gt;</a></span><a href="http://p.weather.com.cn" target="_blank">高清图集</a></h1>
     <a class="go-more" href="http://p.weather.com.cn" target="_blank"></a>
   <div id='scrollPic' class="m">
      <ul class="bigImg clearfix">  
             
         <li><a href="http://p.weather.com.cn/2020/10/3399425.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/20/202010201307570F47577D279AF30363A3D782837C6198.jpg" alt="一组对比图看北京天空“蓝灰”转换" width="300" height="227"></a></li>
           
         <li><a href="http://p.weather.com.cn/2020/10/3399106.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/19/20201019161847C8F2C5115BFE0BDDF0F3B18F6FE6D4D5.jpg" alt="重庆南川迎来迁徙候鸟 姿态曼妙宛如精灵" width="300" height="227"></a></li>
           
         <li><a href="http://p.weather.com.cn/2020/10/3399390.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/20/20201020111724AD42B5A810B3B90888CC5A6CC29C09FA.jpg" alt="哈尔滨醉美“叶色”扮靓一座城" width="300" height="227"></a></li>
           
         <li><a href="http://p.weather.com.cn/2020/10/3399002.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/19/20201019105702EE6E124DA3715C419247DEF5A26B57DD.jpg" alt="呼伦贝尔现“幻日”和“环天顶弧”奇观" width="300" height="227"></a></li>
           
         <li><a href="http://p.weather.com.cn/2020/10/3398174.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/15/202010151615248BBD968FC79F8864ACC3BB560AF8F9D6.jpg" alt="内蒙古科尔沁秋意正浓 多彩秋景令人陶醉" width="300" height="227"></a></li>
           
         <li><a href="http://p.weather.com.cn/2020/10/3399038.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2020/10/19/20201019135530D6ED5EED55CFA7514C6145C172D85117.jpg" alt="福建清流稻谷飘香 喜迎丰收" width="300" height="227"></a></li>
         

      </ul>
					
      <ul class="botIcon clearfix">
      </ul>
      <p></p>
      <div class="bottomBg"></div>
      <div class="rollLeft"></div>
      <div class="rollRight"></div>
   </div>
</div>
<!--焦点图end-->
<ul class="list">
   <li><a href="http://p.weather.com.cn/2020/09/3390879.shtml" target="_blank" style="color:" > 一行字一幅秋 这样的秋景你见过吗？ </a></li>  

   <li><a href="http://p.weather.com.cn/2020/09/3390088.shtml" target="_blank" style="color:" > 黑龙江抚远第一缕阳光下的山河壮阔 </a></li>  

   
<li><script type="text/javascript" src="http://news.baizhan.net/coop/hezuo/08/kaijia_1.js"></script></li>
<li><script type="text/javascript" src="http://mini2.eastday.com/jsfile/kuaiya_1.js"></script></li>
</ul> 
</div> 
<a href="https://tianqixiaomi.tmall.com/search.htm?spm=a1z10.1-b-s.0.0.69bc76b8h03XV4&search=y" target="_blank">
                <img src="https://i.i8tq.com/jieri/tianqixiaomi.png?05" style="width:100%;">
              </a>

			
   

						<div class="right-div">
			
			</div>
			<div class="tqsp video right-div">
                <div class="title">
                    <h1><a href="http://video.weather.com.cn/" target="_blank">天气视频</a></h1>
                    <a class="go-more" href="http://video.weather.com.cn/" target="_blank"></a>
                </div>
                               
          </div>
						 <div class="right-div">
			<div class="chartPH">
				<h1 class="clearfix">
					<span>热点</span>
					<i >视频</i>
					<i>图片</i>
					<i class="on" >文章</i>
				</h1>
				<ul id='hot'>
										<li class="hover"><span class="ord"><i>1</i></span><span class="city"><a  target="_blank" href="http://news.weather.com.cn/2020/09/3388358.shtml">台风“红霞”将连升两级！明起三天华...</a></span></li><li class="hover"><span class="ord"><i>2</i></span><span class="city"><a  target="_blank" href="http://news.weather.com.cn/2020/09/3388371.shtml">全国添衣指数地图出炉 看看你家要加...</a></span></li><li class="hover"><span class="ord"><i>3</i></span><span class="city"><a  target="_blank" href="http://news.weather.com.cn/2020/09/3388629.shtml">台风预警！“红霞”携风雨影响海南广...</a></span></li>
<li class=""><span class="ord"><i>4</i></span><span class="city"><script type="text/javascript" src="http://news.baizhan.net/coop/hezuo/08/kaijia_2.js"></script></span></li>
<li class=""><span class="ord"><i>5</i></span><span class="city"><script type="text/javascript" src="http://mini2.eastday.com/jsfile/kuaiya_2.js"></script></span></li>

									</ul>
				
				<ul id='pic' style="display:none;">
										<li class="hover"><span class="ord"><i>1</i></span><span class="city"><a  target="_blank" href="http://p.weather.com.cn/2020/09/3388399.shtml">贵州惠水降暴雨致积水严重 民众出行...</a></span></li><li class="hover"><span class="ord"><i>2</i></span><span class="city"><a  target="_blank" href="http://p.weather.com.cn/2020/09/3388136.shtml">重庆歌乐山葱莲花雨中盛开 洁白无瑕...</a></span></li><li class="hover"><span class="ord"><i>3</i></span><span class="city"><a  target="_blank" href="http://p.weather.com.cn/2020/09/3388388.shtml">雨中哈尔滨落叶纷飞 秋意渐浓</a></span></li>
<li class=""><span class="ord"><i>4</i></span><span class="city"><script type="text/javascript" src="http://news.baizhan.net/coop/hezuo/08/kaijia_2.js"></script></span></li>
<li class=""><span class="ord"><i>5</i></span><span class="city"><script type="text/javascript" src="http://mini2.eastday.com/jsfile/kuaiya_2.js"></script></span></li>

									</ul>
				<ul id="video">
										<li class="hover"><span class="ord"><i>1</i></span><span class="city"><a  target="_blank" href="http://video.weather.com.cn/v/video/a/m/content_20710.shtml">9月16日联播天气 南方多地强降雨...</a></span></li><li class="hover"><span class="ord"><i>2</i></span><span class="city"><a  target="_blank" href="http://video.weather.com.cn/v/video/a/m/content_20748.shtml">9月17日联播天气：西南地区遭遇强...</a></span></li><li class="hover"><span class="ord"><i>3</i></span><span class="city"><a  target="_blank" href="http://video.weather.com.cn/v/video/a/m/content_20706.shtml">青海玉树市区遭遇强对流 天降冰雹车...</a></span></li>
<li class=""><span class="ord"><i>4</i></span><span class="city"><script type="text/javascript" src="http://news.baizhan.net/coop/hezuo/08/kaijia_2.js"></script></span></li>
<li class=""><span class="ord"><i>5</i></span><span class="city"><script type="text/javascript" src="http://mini2.eastday.com/jsfile/kuaiya_2.js"></script></span></li>

									</ul>
			</div>
			</div>
						<div class="right-div">
				<div style="margin-top:5px;margin-bottom:5px;">
<!-- 广告位：中国天气网画中画02 -->
<script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '7177162',
        container: s,
        size: '300,250',
        display: 'inlay-fix'
    });
})();
</script>
<script src="http://dup.baidustatic.com/js/os.js"></script>
<div id="ybxqyhzh02">
	<script src="https://vlaq.coolbook.cc/site/ida/fs/resource/s/fja.js"></script>
</div>
<script src="https://vlas.coolbook.cc/tianqi/ybxq3.js"></script>
</div>

</div>


						<div class="right-div">
			<div class="pic travel">
				<h1> <span><a href="http://www.weather.com.cn/life/" target="_blank">&gt;&gt;</a></span> <a target="_blank" href="http://www.weather.com.cn/life/">生活旅游</a></h1>
				<a class="go-more" href="http://www.weather.com.cn/life/" target="_blank"></a>
				<div class="scrollPic">
					<ul class="bigImg clearfix">
						
   <li><a href="http://www.weather.com.cn/life/2020/10/3399069.shtml" target="_blank"><img src="http://i.weather.com.cn/images/cn/life/2020/10/20/202010200946553BD9DD7EFE691DDDA7CDCF924E4B0D8A.jpg" alt="霜降节气养生需注意三防 易发疾病都有哪些？" width="300" height="227"  ></a></li>
   
   <li><a href="http://www.weather.com.cn/life/2020/10/3399068.shtml" target="_blank"><img src="http://i.weather.com.cn/images/cn/life/2020/10/20/202010200948497BF4C67AA248DECC8E1BDC00EE9015A3.jpg" alt="霜降养生：调养脾胃是关键 滋阴汤水最适宜" width="300" height="227"  ></a></li>
   
   <li><a href="http://www.weather.com.cn/life/2020/10/3398989.shtml" target="_blank"><img src="http://i.weather.com.cn/images/cn/life/2020/10/19/202010191439044D2AFA27CF3BF22E22913C6B175A71B1.jpg" alt="霜降节气赏菊吃柿子 人们开始“备冬”" width="300" height="227"  ></a></li>
   

						
   <li><a href="http://www.weather.com.cn/life/2020/07/3364520.shtml" target="_blank"><img src="http://i.weather.com.cn/images/cn/sjztj/2020/07/20/20200720142523B5F07D41B4AC4336613DA93425B35B5E.jpg" alt="雨后峨眉沟壑尽显 金顶显真容" width="300" height="227"></a></li>
   
   <li><a href="http://www.weather.com.cn/life/2019/10/3253319.shtml" target="_blank"><img src="http://pic.weather.com.cn/images/cn/photo/2019/10/28/20191028144048D58023A73C43EC6EEB61610B0AB0AD74.jpg" alt="秋意浓 蓝天映衬下的哈尔滨伏尔加庄园" width="300" height="227"></a></li>
   
   <li><a href="http://www.weather.com.cn/life/2019/10/3253318.shtml" target="_blank"><img src="http://i.weather.com.cn/images/cn/sjztj/2019/10/29/201910281008514BABD1DE0A34725E596E45D93BD838D0.jpg" alt="大美新疆—帕米尔高原好风光" width="300" height="227"></a></li>
   

					</ul>
					
					<ul class="botIcon clearfix">
					</ul>
					<p></p>
					<div class="bottomBg"></div>
					<div class="rollLeft"></div>
					<div class="rollRight"></div>
				</div>
			</div>
			<ul class="list">
				
   <li><a href="http://www.weather.com.cn/life/2019/10/3253317.shtml" target="_blank">湖北神农架晨雾缭绕 秋色迷人</a></li>
   
   <li><a href="http://www.weather.com.cn/life/2018/11/2957048.shtml" target="_blank">换个视角看秋景 竟然这么美！</a></li>
   

				<li><script type="text/javascript" src="http://news.baizhan.net/coop/hezuo/08/kaijia_3.js"></script></li>
<li><script type="text/javascript" src="http://mini2.eastday.com/jsfile/kuaiya_3.js"></script></li>

			</ul>
			</div>
						
		
						<div class="right-div">
				<div class="hotSpot">
<h3>景点推荐</h3>
<h4 class="title"><span class="name">景区</span><span class="weather">天气</span><span class="wd">气温</span>
<span class="zs">旅游指数</span></h4>
<ul class="on">
<li>
<a href="http://www.weather.com.cn/weather1d/101310201.shtml" target="_blank">
<span class="name">三亚</span>
<span class="weather">多云</span>
<span class="wd">22/28℃</span>
<span class="zs">适宜</span>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101271906.shtml" target="_blank">
<span class="name">九寨沟</span>
<span class="weather">阴</span>
<span class="wd">7/17℃</span>
<span class="zs">适宜</span>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101290201.shtml" target="_blank">
<span class="name">大理</span>
<span class="weather">中到大雨</span>
<span class="wd">13/16℃</span>
<span class="zs">较不宜</span>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101251101.shtml" target="_blank">
<span class="name">张家界</span>
<span class="weather">小雨转多云</span>
<span class="wd">13/24℃</span>
<span class="zs">适宜</span>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101300501.shtml" target="_blank">
<span class="name">桂林</span>
<span class="weather">阴转多云</span>
<span class="wd">16/23℃</span>
<span class="zs">适宜</span>
</a>
</li>
<li>
<a href="http://www.weather.com.cn/weather1d/101120201.shtml" target="_blank">
<span class="name">青岛</span>
<span class="weather">多云</span>
<span class="wd">15/20℃</span>
<span class="zs">适宜</span>
</a>
</li>
</ul>
</div>
				</div>
						
			<div class="right-div">

<script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '7177164',
        container: s,
        size: '300,250',
        display: 'inlay-fix'
    });
})();
</script>
<script src="http://dup.baidustatic.com/js/os.js"></script>
<div id="ybxqyhzh03">
	<script type="text/javascript" src="http://vlaq.coolbook.cc/source/je/bg/resource/t/openjs/tg/kd.js"></script>
</div>
<script src="https://vlas.coolbook.cc/tianqi/ybxh03_a.js"></script>


</div>
			
			<div class="right-div qxcp">
			<div class="title">
			<h1><a href="http://products.weather.com.cn/" target="_blank">气象产品</a></h1>
			<a class="go-more" href="http://products.weather.com.cn/" target="_blank"></a>
			
			</div>
			
			<div class="qxcp-body">
					<a style="height:225px;display:none;" href="http://www.weather.com.cn/index/zxqxgg1/new_wlstyb.shtml" target="_blank">
                    <img src="http://i.tq121.com.cn/i/weather2019/img-chanpin.png" width="275" alt="">
					</a>
                    <a href="http://www.weather.com.cn/index/zxqxgg1/new_wlstyb.shtml" target="_blank">未来三天全国天气预报</a>
                    <a href="http://products.weather.com.cn/product/Index/index/procode/YB_WD_ZG24" target="_blank">全国最高气温分布</a>
                    <a href="http://products.weather.com.cn/product/Index/index/procode/JC_JSL_02405" target="_blank">全国降水实况</a>
                </div>
			
			</div>
		
			
				 <div class="right-div qxfw">
                <div class="title">
                    <h1>气象服务</h1>
                </div>
                <div class="qxfw-body">
                    <h1><a href="http://www.weather.com.cn/wzfw/kfzx/index.shtml" target="_blank">气象服务热线</a></h1>
                    <p>拨打400-6000-121进行气象服务咨询、建议、合作与投诉</p>
                    <h1>天气预报电话查询</h1>
                    <p>拨打12121或96121进行天气预报查询</p>
                    <h1><a href="http://e.weather.com.cn/" target="_blank">手机查询</a></h1>
                    <p>随时随地通过手机登录中国天气WAP版查看各地天气资讯</p>
                    <img width="300" src="http://i.tq121.com.cn/i/weather2019/img-qrcode-2.png" alt="">
                    <!-- <span>查看手机版中国天气</span> -->
                </div>
            </div>

			<div class="right-div">
			<script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '6828831',
        container: s,
        size: '300,250',
        display: 'inlay-fix'
    });
})();
</script>
<script src="http://dup.baidustatic.com/js/os.js"></script>
<!-- 广告位：天气网pc站预报页左对联 -->
  <div id="zdl" style="width:122px;">
    <div>
<script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '6012517',
        container: s,
        size: '120,240',
        display: 'inlay-fix'
    });
})();
</script>
</div>
</div>

<!-- 广告位：天气网pc站预报页右弹窗 -->
<div id="ytc" style="width:300px;">
 <script>
(function() {
    var s = "_" + Math.random().toString(36).slice(2);
    document.write('<div id="' + s + '"></div>');
    (window.slotbydup=window.slotbydup || []).push({
        id: '6012520',
        container: s,
        size: '300,250',
        display: 'inlay-fix'
    });
})();
</script>

</div>

			</div>	
		
		</div>
</div>


<!-- 悬浮 -->
<style>
      .r-x-box {
            width: 50px;
            border-left: 0;
            border-right: 0;
            border-bottom: 0;
            left: 0;
            position: fixed;
            left: 50%;
            margin-left: -553px;
            position: fixed;
            top: 370px;
        }

        .r-x1 {
            width: 50px;
            height: 50px;
            cursor: pointer;
        }

        .r-x-5 {
            background: url(http://i.tq121.com.cn/i/news/right-d9.png) no-repeat;
        }

        .r-x-5:hover {
            background: url(http://i.tq121.com.cn/i/news/right-d10.png) no-repeat;
        }

        .r-x-7 {
            background: url(http://i.tq121.com.cn/i/news/right-d13.png) no-repeat;
        }

        .r-x-7:hover {
            background: url(http://i.tq121.com.cn/i/news/right-d14.png) no-repeat;
        }

        .r-x-8 {
            background: url(http://i.tq121.com.cn/i/weather2017/left_d1.png) no-repeat;
        }

        .r-x-8:hover {
            background: url(http://i.tq121.com.cn/i/weather2017/left_d2.png) no-repeat;
        }
</style>
<div class="r-x-box" >
<a href="http://www.weather.com.cn/weather1dn/101280301.shtml"><div class="r-x1 r-x-8"></div></a>
         <a href="http://www.weather.com.cn/index/feedback_201409.shtml#forecast#old" target="_blank"><div class="r-x1 r-x-5"></div></a>
    
         <!-- 城市对比上线  -->
    <div class="cmpCityBtn">
      <img src="https://i.i8tq.com/cityListCmp/cmpCityBtn.png">
      <p>城市对比</p>
    </div>
	<a title="回到顶部" class="gotop" href="javascript:void(0)"><div class="r-x1 r-x-7"></div></a>
</div>
<div class="cmpBottomModel dis">
  <div class="errorMsg dis">对比栏已有所选内容，请重新选择</div>
  <div class="cmpBottomInner">
    <div class="cmpBottomHeader">
      <ul>
        <li class="cur" id="cmpFirst">对比栏</li>
        <li>最近浏览</li>
      </ul>
      <span>隐藏</span>
    </div>
    <ul class="cmpBottomCityList" id="cmpModel">
    </ul>
    <ul class="cmpBottomCityList dis" id="historyCity">
      <li>
        <p class="date">2019年08月22日</p>
        <p class="city">天津</p>
        <div class="cityWeatherIcon">
          <i class="housr_icons d01"></i>
          <i class="housr_icons d01"></i>
        </div>
        <p class="weather">晴</p>
        <p class="temp">32~20℃</p>
        <div class="btnList">
          <span class="cmpBtn">对比</span>
        </div>
      </li>
      <li>
        <p class="date">2019年08月22日</p>
        <p class="city">天津</p>
        <div class="cityWeatherIcon">
          <i class="housr_icons d01"></i>
          <i class="housr_icons d01"></i>
        </div>
        <p class="weather">晴</p>
        <p class="temp">32~20℃</p>
        <div class="btnList">
          <span class="cmpBtn">对比</span>
        </div>
      </li>
      <li>
        <p class="date">2019年08月22日</p>
        <p class="city">天津</p>
        <div class="cityWeatherIcon">
          <i class="housr_icons d01"></i>
          <i class="housr_icons d01"></i>
        </div>
        <p class="weather">晴</p>
        <p class="temp">32~20℃</p>
        <div class="btnList">
          <span class="cmpBtn">对比</span>
        </div>
      </li>
      <li>
        <p class="date">2019年08月22日</p>
        <p class="city">天津</p>
        <div class="cityWeatherIcon">
          <i class="housr_icons d01"></i>
          <i class="housr_icons d01"></i>
        </div>
        <p class="weather">晴</p>
        <p class="temp">32~20℃</p>
        <div class="btnList">
          <span class="cmpBtn">对比</span>
        </div>
      </li>
      <li>
        <p class="date">2019年08月22日</p>
        <p class="city">天津</p>
        <div class="cityWeatherIcon">
          <i class="housr_icons d01"></i>
          <i class="housr_icons d01"></i>
        </div>
        <p class="weather">晴</p>
        <p class="temp">32~20℃</p>
        <div class="btnList">
          <span class="cmpBtn">对比</span>
        </div>
      </li>
    </ul>
  </div>
  <div class="addCityCmp dis">
    <div class="addCityCmpInner">
      <div class="close"></div>
      <div class="cityUl">
        <div class="cityLi">
          <span>选择日期</span>
          <input type="text" class="dateInput" placeholder="请选择日期" readonly>
          <span>选择城市</span>
          <input type="text" class="cityInput" placeholder="请输入城市">
          <i>清空</i>
          <div class="showDate dis">
            <ul>
            </ul>
          </div>              
          <div class="showCity dis">
            <ul></ul>
          </div>
        </div>
        <div class="cityLi">
          <span>选择日期</span>
          <input type="text" class="dateInput" placeholder="请选择日期" readonly>
          <span>选择城市</span>
          <input type="text" class="cityInput" placeholder="请输入城市">
          <i>清空</i>    
          <div class="showDate dis">
            <ul>
            </ul>
          </div>              
          <div class="showCity dis">
            <ul></ul>
          </div>
        </div>
        <div class="cityLi">
          <span>选择日期</span>
          <input type="text" class="dateInput" placeholder="请选择日期" readonly>
          <span>选择城市</span>
          <input type="text" class="cityInput" placeholder="请输入城市">
          <i>清空</i>    
          <div class="showDate dis">
            <ul>
            </ul>
          </div>               
          <div class="showCity dis">
            <ul></ul>
          </div>
        </div>
        <div class="cityLi">
          <span>选择日期</span>
          <input type="text" class="dateInput" placeholder="请选择日期" readonly>
          <span>选择城市</span>
          <input type="text" class="cityInput" placeholder="请输入城市">
          <i>清空</i>  
          <div class="showDate dis">
            <ul>
            </ul>
          </div>             
          <div class="showCity dis">
            <ul></ul>
          </div>
        </div>
      </div>
      <div class="addCityLi dis"><img src="https://i.i8tq.com/cityListCmp/addCityLi.png"><span>添加城市</span></div>
      <div class="saveCloseBtn">
        <div class="saveBtn">保存</div>
        <div class="closeBtn">取消</div>
      </div>
    </div>
  </div>
</div>


<!-- 悬浮end -->

<script>W.use(['j/weather2015/c_common.js']);</script>
<script type="text/javascript" src="http://i.tq121.com.cn/j/jquery-1.8.2.js"></script>
<script type="text/javascript">
  $(function() {
   
   $.getScript("http://video.weather.com.cn/hotnews/jsonp_list.html",function(){
          addBox(data);
      })
   
});
function addBox(result){
    $content="";
            $.each(result,function(index,obj){
                
                if(index==0){
                 $content+="<a title=\""+obj['title']+"\" class=\"l\" href=\"http://video.weather.com.cn"+obj['url']+"\" target=\"_blank\"><img width=\"300\" alt=\""+obj['title']+"\"  src=\""+obj['photo']+"\"><i></i></a> ";
                }else if(index>0 && index<3){
                    $content+="<p><a title=\""+obj['title']+"\" href=\"http://video.weather.com.cn"+obj['url']+"\" target=\"_blank\">"+obj['title']+"</a></p>";
                }
                
            });
            $(".video .title").after($content);
        }
</script>
<script type="text/javascript" src="http://i.tq121.com.cn/j/weather2017/ads/tools.js"></script>
<script type="text/javascript" src="http://i.tq121.com.cn/j/weather2017/ads/ads.js"></script>
<script type="text/javascript" src="http://i.tq121.com.cn/j/weather2017/ads/main.js"></script>
<!-- 城市对比上线  -->
<script type="text/javascript" src="https://j.i8tq.com/cityListCmp/cityListCmp.js?20191230"></script>

<script>W.use(['j/weather2015/c_gg.js']);</script>
<style>
#abs { z-index:10 ;display:block;}
#adposter_6287{display:block;}
</style>

<script>W.use('j/weather2015/publicHead.js?2019');</script>
<div class="footer">
  <div class="block">
    <div class="Lcontent" style="width:558px;">
      <dl style="width:280px; margin-right:22px;">
        <dt>
          <h3>网站服务</h3>
        </dt>
        <dd>
          <p><a href="http://www.weather.com.cn/wzfw/gywm/" target="_blank">关于我们</a><a href="http://www.weather.com.cn/wzfw/lxwm/" target="_blank">联系我们</a><a href="http://www.weather.com.cn/wzfw/sybz/" target="_blank">帮助</a><a href="http://www.weather.com.cn/wzfw/ryzp/" target="_blank">人员招聘</a></p>
          <p><a href="http://www.weather.com.cn/wzfw/kfzx/" target="_blank">客服中心</a><a href="http://www.weather.com.cn/wzfw/bqsm/" target="_blank">版权声明</a><a href="http://www.weather.com.cn/wzfw/wzls/" target="_blank">律师</a><a href="http://www.weather.com.cn/wzfw/wzdt/" target="_blank">网站地图</a></p>
        </dd>
      </dl>
      <dl style="width:150px;">
        <dt>
          <h3>营销中心</h3>
        </dt>
        <dd>
          <p><a href="http://marketing.weather.com.cn/wzhz/index.shtml" target="_blank">商务合作</a><a href="http://ad.weather.com.cn/index.shtml" target="_blank">广告服务</a></p>
        </dd>
      </dl>
      <div class="clear"></div>
    </div>
    <div class="friendLink" style="width:418px;margin-right:15px;">
      <h3>相关链接</h3>
      <p><a href="http://www.cma.gov.cn/" target="_blank">中国气象局</a><a href="http://www.chinamsa.org" target="_blank">中国气象服务协会</a> <a href="http://www.weathertv.cn/" target="_blank">中国天气频道</a><a href="http://www.xn121.com/" target="_blank">中国兴农网</a></p>
      
    </div>
    <div class="serviceinfo" style="position:relative;">
<p><span>客服邮箱：<a href="mailto:service@weather.com.cn">service@weather.com.cn</a></span><span style="width:220px;">广告服务：<b>010-58991910</b></span><span><a href="http://www.beian.miit.gov.cn/" target="_blank">京ICP证010385号</a>　京公网安备11041400134号</span></p>
      <p><span>客服热线：<b><a href="http://www.weather.com.cn/wzfw/kfzx/index.shtml" target="_blank">400-6000-121</a></b></span><span style="width:220px;">  商务合作：<b>010-58991806</b><b style="display:  block;margin-left: 60px;">010-58991938</b></span><span>增值电信业务经营许可证B2-20050053</span></p>
<a id="___szfw_logo___" target="_blank" href="http://www.bcpcn.com/product/pjia/da/BCP65914858F778322.html"><img src="http://i.tq121.com.cn/i/weather2017/cx_new.png" style="position:absolute;right:10px;top:25px;"></a>
    </div>
    <div class="clear"></div>
  </div>
  <div class="aboutUs"> 中国天气网版权所有，未经书面授权禁止使用&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspCopyright&copy;<a href="http://www.pmsc.cn/" target="_blank">中国气象局公共气象服务中心</a> All Rights Reserved (2008-2020)</div>
</div>



<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F080dabacb001ad3dc8b9b9049b36d43b' type='text/javascript'%3E%3C/script%3E"));
</script>


<script type="text/javascript">
window._wat = window._wat || [];
(function() {
    var wm = document.createElement("script");
    wm.src = "http://analyse.weather.com.cn/js/v1/wa.js?site_id=1";
    var s = document.getElementsByTagName("script")[0]; 
    s.parentNode.insertBefore(wm, s);
})();
</script>
	<script src="https://j.i8tq.com/weather1d/pcvideo.js?20190718"></script>
<!--[if IE]>
    <script src="http://api.html5media.info/1.2.2/html5media.min.js"></script>
    <![endif]-->
<!-- tong0903 top-->
<script type="text/javascript" src="https://j.i8tq.com/weather2020/search/city.js"></script>
<script type="text/javascript" src="https://j.i8tq.com/weather2020/search/searchCityList.js"></script>
<!-- tong0903 end-->

</body>
</html>