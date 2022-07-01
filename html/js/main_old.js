
function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        // vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
Get = getUrlVars();
console.log(Get);

// page_t = location.href.split('/').pop().split('?');
// page = page_t[0];
// if(page_t[1]) {
// 	fr_t = page_t[1].split('&');
// 	for(i=0; i<fr_t.length; i++) {
// 		x = fr_t[i].split('=');
// 		if( x[0] =='fr') {
// 			 fr = x[1];
// 		}
// 		else if( x[0] =='db') {
// 			 db = x[1];
// 		}
// 	}
// }
//console.log(page);
// console.log(fr);
var a = '';
var b = '';	

// if(Get['fr'] == "footfall") {
// 	b = document.getElementById(Get['fr']);
// 	a = document.getElementById(Get['db']);
// }
// else {
	a = document.getElementById(Get['fr']);
// }
if (a) {
	a.classList.add("active");
}

// if (b) {
// 	b.classList.add("show");
// }


var configDateTo ={
	singleDatePicker: true,
	showDropdowns: true,
//	startDate: moment(new Date()).format(date_picker_option_locale[_selected_language].format),
	"locale": date_picker_option_locale[_selected_language],
};
var configDateFrom ={
	singleDatePicker: true,
	showDropdowns: true,
//	startDate: moment(new Date()-3600*24*7*1000).format(date_picker_option_locale[_selected_language].format),
	"locale": date_picker_option_locale[_selected_language],
};

document.addEventListener("DOMContentLoaded", function(event) {
	$("input[name=\"refdate\"]").daterangepicker(configDateTo);
	$("input[name=\"refdate_from\"]").daterangepicker(configDateFrom);
});

function addJavascript(jsname) {
	var th = document.getElementsByTagName('head')[0];
	var s = document.createElement('script');
	s.setAttribute('type','text/javascript');
	s.setAttribute('src',jsname);
	th.appendChild(s);
}

function listSquare(squareId) {
  	var url_query = "./inc/query.php?f=square";
	if(!squareId) {
		squareId = "#square";
	}
	$.getJSON(url_query, function(data) {
		for(i=0; i< data["code"].length; i++) {
			$(squareId).append("<option value='"+ data["code"][i] + "'>"+ data["name"][i] +"</option>");
		}
	});	
}

function timeToDatetime(arr_label, dateformat) {
	cat_res = new Array();
	for(i=0; i<arr_label.length; i++) {
		var outDate = moment(new Date(arr_label[i]*1000)).format(dateformat);
		cat_res.push(outDate);
	}
	return cat_res;
}

function maxFromArray(arr) {
	var max = 0;
	for(i=0; i<arr.length; i++) {
		if(arr[i] > max) {
			max = arr[i];
		}
	}
	return max;
}

function viewSnapshot(e){
	// console.log(e.src);
	var id = document.getElementById('snapShot');
	id.innerHTML = '<img src="' + e.src + '" height="620" />';
}

function changeSpot(num){
	if(!num) {
		id = "square";
		store = "store";
	}
	else if( num == 1) {
		id = "square1";
		store = "store1";
	}
	else if( num == 2) {
		id = "square2";
		store = "store2";
	}
	else if( num == 3) {
		id = "square3";
		store = "store3";
	}
	
	square = document.getElementById(id).value;
	st_id = document.getElementById(store);
	st_id.length = 1;
	var url_query = "./inc/query.php?f=store&sq_code=" + square;

	$.getJSON(url_query, function(data) {
		for (i=0; i<data["code"].length; i++) {
			st_id.add(new Option (data["name"][i], data["code"][i], i+1));
		}
	});
	if(Get['fr'] == "heatmap") {
		listDevice();
	}
	else {
		doAnalysis();
	}
}  

function changeStore() {
	if(Get['fr'] == "heatmap") {
		listDevice();
	}
	else {
		doAnalysis();
	}
}



var refresh = 1;
function changeDate(ref) {
	console.log(refresh);
	if(refresh) {
		refresh -=1;
		return false;
	}
	var view_by = document.getElementById("view_by").value;
	var thisDate = new Date();
	var thisTime = new Date().getTime();
	console.log(view_by, ref);

	if((ref == -1) || (ref == 1)) {
		var ref_time = document.getElementById("refdate").value;
		var d_t = new Date(Date.parse(ref_time));

		if(view_by == "month") {
			var myDate = new Date(d_t.getFullYear(),d_t.getMonth() + ref + 1,0, 0,0,0,0);
			console.log(myDate.getTime() - (new Date(thisDate.getFullYear(),thisDate.getMonth(),0,0,0,0,0)));
			if((myDate.getTime() - (new Date(thisDate.getFullYear(),thisDate.getMonth()+1,1,0,0,0,0))) >0) {
				return false;
			}
			configDateTo.startDate = moment(myDate).format(date_picker_option_locale[_selected_language].format);
		}
		else {
			var myDate = new Date(d_t.getFullYear(),d_t.getMonth(),d_t.getDate()+ref, 0,0,0,0);
			if((myDate.getTime() - thisDate.getTime()) >0) {
				return false;
			}
			configDateTo.startDate = moment(myDate).format(date_picker_option_locale[_selected_language].format);
		}
		console.log(configDateTo.startDate );
		$("input[name=\"refdate\"]").daterangepicker(configDateTo);
	}
	
	else if( (ref == -2) || (ref == 2)) {
		var ref_time = document.getElementById("refdate_from").value;
		var d_t = new Date(Date.parse(ref_time));
		var myTime = d_t.getTime();
		if(myTime > thisTime) {
//			ref = 0;
//			return false;
		}

		if(view_by == "month") {
			configDateFrom.startDate = moment(new Date(d_t.getFullYear(),d_t.getMonth()+(ref/2), 1, 0,0,0,0)).format(date_picker_option_locale[_selected_language].format);
		}
		else {
			configDateFrom.startDate = moment(new Date(d_t.getFullYear(),d_t.getMonth(),d_t.getDate()+(ref/2), 0,0,0,0)).format(date_picker_option_locale[_selected_language].format);
		}
		console.log(configDateFrom.startDate );
		$("input[name=\"refdate_from\"]").daterangepicker(configDateFrom);
	}
	else {
		doAnalysis();
	}
}

function changeViewBy(view_by) {
	document.getElementById("view_by").value = view_by;
	if(document.getElementById("10min")) {
		document.getElementById("10min").style.backgroundColor = "";
	}
	if(document.getElementById("hour")) {
		document.getElementById("hour").style.backgroundColor="";
	}
	if(document.getElementById("day")) {
		document.getElementById("day").style.backgroundColor="";
	}
	if(document.getElementById("week")) {
		document.getElementById("week").style.backgroundColor="";
	}
	if(document.getElementById("month")) {
		document.getElementById("month").style.backgroundColor="";
	}
	document.getElementById(view_by).style.backgroundColor="#fcc100";

	if(view_by == "day") {
		document.getElementById("date_additional").style.display='';
		var ref_time = document.getElementById("refdate").value;
		var d_t = new Date(Date.parse(ref_time));
		configDateTo.startDate = moment(new Date()).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate\"]").daterangepicker(configDateTo);
		configDateFrom.startDate = moment(new Date(d_t.getFullYear(),d_t.getMonth(),d_t.getDate()-7, 0,0,0,0)).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate_from\"]").daterangepicker(configDateFrom);		
	}
	else if(view_by == "month") {
		document.getElementById("date_additional").style.display='';
		var ref_time = document.getElementById("refdate").value;
		var d_t = new Date(Date.parse(ref_time));
		configDateTo.startDate = moment(new Date()).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate\"]").daterangepicker(configDateTo);
		configDateFrom.startDate = moment(new Date(d_t.getFullYear()-1,d_t.getMonth(),1, 0,0,0,0)).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate_from\"]").daterangepicker(configDateFrom);		
	}
	else if(view_by == "hour") {
		document.getElementById("date_additional").style.display='none';
		var ref_time = document.getElementById("refdate").value;
		var d_t = new Date(Date.parse(ref_time));
		configDateTo.startDate = moment(new Date()).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate\"]").daterangepicker(configDateTo);
		configDateFrom.startDate = moment(new Date(d_t.getFullYear(),d_t.getMonth(),d_t.getDate()-1, 0,0,0,0)).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate_from\"]").daterangepicker(configDateFrom);	

	}
	else if(view_by == "10min") {
		document.getElementById("date_additional").style.display='none';
		configDateTo.startDate = moment(new Date()).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate\"]").daterangepicker(configDateTo);
		configDateFrom.startDate = moment(new Date()).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate_from\"]").daterangepicker(configDateFrom);	

	}
	
	if(document.getElementById("fr").value == 'trendAnalysis') {
		document.getElementById("date_additional").style.display='none';
	}
}

function json_array_sum(arr) {
	var sum = 0;
	for(i=0; i<arr.length; i++){
		sum += arr[i];
	}
	return sum;
}
function arraySum(arr) {
	var sum = 0;
	for(i=0; i<arr.length; i++) {
		sum += arr[i];
	}
	return sum;
}

var	tooltip_time = "HH:mm"; 
var	tooltip_date = "yyyy-MM-dd"; 
var	tooltip_datetime = "yyyy-MM-dd HH:mm"; 

var colors_ref = [ '#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'];
var options_curve = {
	chart: {
		height: 180,
		type: "line",
		zoom:{ enabled:false, },
		toolbar: {
			show: false,
		},
	},
	colors: colors_ref,
	dataLabels: {enabled: true,	},
	series: [],
	title: { text: "", },
	legend: { show:true, showForSingleSeries: true, position:"top", offsetX: 0, floating: true,},
	stroke: { curve: "smooth", width:3,},
	markers: { size:0 },
	noData: { text: "Loading..." },
	xaxis: {
		type: "datetime",
		labels:{
			show:true,
			showDuplicates: true,
			datetimeFormatter: {
				year: "yyyy",
				month: "yyyy-MM",
				day: "MM/dd",
				hour: "HH:mm",
			},
		}		
	},
	tooltip: {
		y:{
			enable: true,
			formatter: function (val) {
				if(val) {
					var reg = /(^[+-]?\d+)(\d{3})/; 
					val +='';
					while (reg.test(val)) {
						val = val.replace(reg, '$1' + ',' + '$2'); 
					}
					return val;
				}
			},
		},
	},
	dataLabels: {
		enabled: true,	
		formatter: function (val) {
			var reg = /(^[+-]?\d+)(\d{3})/; 
			val +='';
			while (reg.test(val)) {
				val = val.replace(reg, '$1' + ',' + '$2'); 
			}
			return val;
		},
	}	
};	

var options_bar = {
	chart: { type: "bar", height: 180, zoom:{ enabled:false, }, toolbar: {show: false,}},
	series: [],
	colors: colors_ref,
	dataLabels: {enabled: true,	},
	legend: { show:true, showForSingleSeries: true, position:"top", offsetX: 0, floating: true,},
	plotOptions: {
		bar: {
			columnWidth: '75%',
//			distributed: true,
			dataLabels: {
				enabled: true,
				position: "top",
			},				
		}
	},
	dataLabels: {
	  enabled: true,
	  formatter: function (val) {
		var reg = /(^[+-]?\d+)(\d{3})/; 
		val +='';
		while (reg.test(val)) {
			val = val.replace(reg, '$1' + ',' + '$2'); 
		}
		return val;
	  },		  
	  offsetY: -16,
	  style: {
		fontSize: '12px',
		colors: ["#A04758"]
	  }
	},
	stroke: {
		show: true,
		width: 1,
		colors: ['transparent']
	},
	xaxis: {
		type: 'category',
		categories: [],
		labels: {
			show:true,
//			rotate: -30,
//			rotateAlways: true,
			trim:true,
			style: {
				colors: [],
				fontSize: '12px',
				fontFamily: 'Helvetica, Arial, sans-serif',
				cssClass: 'apexcharts-xaxis-label',
			},
			// offsetY: -8,
			minHeight: 30,
		},
	},
	yaxis: {
		floating: false,
		labels: {
			show: true,
			align: 'right',
			minWidth: 0,
			maxWidth: 160,
			style: {
				colors: [],
				fontSize: '12px',
				fontFamily: 'Helvetica, Arial, sans-serif',
				cssClass: 'apexcharts-yaxis-label',
			},
			offsetX: 0,
			offsetY: 0,
			rotate: 0,
			formatter: function (val) {
				return Math.round(val);				 
			},
		},
	},
	grid: {
		show: true,
		xaxis: {
			lines: { show: true, }
		}, 			
		yaxis: {
			lines: { show: true }
		}, 			
	}
};
	
if(Get['fr'] == "dashboard") {	
	addJavascript("../js/genderGraph.js");
	
	var footfall_bar = new ApexCharts(document.querySelector("#footfall_bar_chart"), options_bar);
	var footfall_curve = new ApexCharts(document.querySelector("#footfall_curve_chart"), options_curve);
	footfall_bar.render();	
	footfall_curve.render();
	
	var third = document.getElementById('third_block').value;
	if (third == 'age_gender') {
		// var age_ave = new ApexCharts(document.querySelector("#age_ave_chart"), options_bar);
		// var age_today = new ApexCharts(document.querySelector("#age_today_chart"), options_bar);
		// age_ave.render();
		// age_today.render();
	
		// var age_curve = new ApexCharts(document.querySelector("#age_curve_chart"), options_curve);
		// var gender_curve = new ApexCharts(document.querySelector("#gender_curve_chart"), options_curve);
		// age_curve.render();
		// gender_curve.render();	

		// var config_gender_bar = {
		// 	data:[],
		// 	titlie:"", 
		// 	label:["male", "female"],
		// 	fontsize: 15,
		// }
	}

	function doAnalysis() {
		var time_ref = document.getElementById("refdate").value;
		var square = document.getElementById("square").value;
		var store = document.getElementById("store").value;

		var url = "./inc/query.php?fr=dashBoard&page=card&fm=json&sq="+square+"&st="+store+"&time_ref="+time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			for(i=0; i<4; i++) {
				document.getElementById("card["+i+"][title]").innerHTML = response[i]["display"];
				document.getElementById("card["+i+"][badge]").innerHTML = response[i]["badge"];
				document.getElementById("card["+i+"][badge]").style.backgroundColor = response[i]["color"];
				document.getElementById("card["+i+"][value]").innerHTML = response[i]["value"].toLocaleString('en-US', { minimumFractionDigits: 0 });
				document.getElementById("card["+i+"][percent]").innerHTML = response[i]["percent"] + "%";
				document.getElementById("card["+i+"][progress]").style.width = response[i]["percent"] + "%";
				document.getElementById("card["+i+"][progress]").style.backgroundColor = response[i]["color"];
			}			
		});

		
		var url = "./inc/query.php?fr=dashBoard&page=footfall&fm=json&sq="+square+"&st="+store+"&time_ref="+time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			document.getElementById('footfall_title').innerHTML = response["display"];
			footfall_bar.updateSeries(response["bar_data"]);
			footfall_bar.updateOptions({
				colors: ["rgb(201, 203, 207)","rgb(153, 102, 255)"],
				xaxis: {
					categories :response['bar_label'],
				},
				yaxis:{
//					max: Math.round(Math.max(maxFromArray(response["bar_data"][0]["data"]),maxFromArray(response["bar_data"][1]["data"]))*1.2/1000)*1000,
				},		
				plotOptions: {
					bar: {
						horizontal: false,
						columnWidth: '60%',
						dataLabels: {
							enabled: true,
							position: "top",
						},
					}
				},
			});			
			
			footfall_curve.updateSeries(response["curve_data"] );
			footfall_curve.updateOptions({
				chart: { height: 150,},	
				colors: ["rgb(153, 102, 255)"],	
				dataLabels: {enabled: false,},
				xaxis: {
					categories: timeToDatetime(response["curve_label"],"YYYY-MM-DD HH:mm"),
				},
				tooltip: {
					x: { format: tooltip_date,},
				},
			});			

		});

		if (third == 'age_gender') {
			var age_ave = new ApexCharts(document.querySelector("#age_ave_chart"), options_bar);
			var age_today = new ApexCharts(document.querySelector("#age_today_chart"), options_bar);
			age_ave.render();
			age_today.render();
		
			var age_curve = new ApexCharts(document.querySelector("#age_curve_chart"), options_curve);
			var gender_curve = new ApexCharts(document.querySelector("#gender_curve_chart"), options_curve);
			age_curve.render();
			gender_curve.render();	
	
			var config_gender_bar = {
				data:[],
				titlie:"",
				label:["male", "female"],
				fontsize: 15,
			}			
			var url = "./inc/query.php?fr=dashBoard&page=ageGender&fm=json&sq="+square+"&st="+store+"&time_ref="+time_ref;
			console.log(url);
			$.getJSON(url, function(response) {
				console.log(response);
				var total_ave = 0;
				var total_tod = 0;
				var max_ave = 0;
				var max_tod = 0;
				
				var arr_data = new Array();
				var arr_label = new Array();
				var arr_color = new Array();
				for(i=0; i<(response["data"].length-2); i++) {
					if(response["data"][i]["total"] > max_ave) {
						max_ave = response["data"][i]["total"];
					}
					if(response["data"][i]["data"][83] > max_tod) {
						max_tod= response["data"][i]["data"][83]
					}
					total_ave += response["data"][i]["total"]*1;
					total_tod += response["data"][i]["data"][83]*1;
				}
				for(i=0; i<(response["data"].length-2); i++) {
					arr_label.push(response["data"][i]["name"]);
					arr_data.push(response["data"][i]["total"]*100/total_ave);
					if(response["data"][i]["total"] == max_ave) {
						arr_color.push("rgb(255, 99, 132)");
					}
					else {
						arr_color.push("rgb(201, 203, 207)");
					}
				}
				age_ave.updateSeries([{name:response["title"]["age_title"][0], data:arr_data}]);
				age_ave.updateOptions({
					colors: arr_color,
					dataLabels: {
						formatter: function (val) {
							return Math.round(val)+"%";
						},		  
						offsetY: -16,
						style: { fontSize: '12px', colors: ["#2047F8"] }
					},					
					xaxis: {
						type: 'category',
						categories: arr_label,
						labels: { rotate: -30, rotateAlways: true, offsetY: -8,	minHeight: 30, },
					},				
					yaxis:{
						floating: true,
						max: Math.max(max_ave*100/total_ave, max_tod*100/total_tod) + 10,
						labels: {
							formatter: function (val) {
								return Math.round(val);				 
							},
						},
					},
					plotOptions: {
						bar: { columnWidth: '60%', distributed: true, }
					},
					grid: {
						yaxis: {
							lines: { show: false }
						}, 			
					}				
				});	
				
				var arr_data = new Array();
				var arr_color = new Array();
				for(i=0; i<(response["data"].length-2); i++) {
					arr_data.push(response["data"][i]["data"][83]*100/total_tod);
					if(response["data"][i]["data"][83] == max_tod) {
						arr_color.push("rgb(255, 99, 132)");
					}
					else {
						arr_color.push("rgb(201, 203, 207)");
					}
				}
				age_today.updateSeries([{name:response["title"]["age_title"][1], data:arr_data}]);				
				age_today.updateOptions({
					colors: arr_color,
					dataLabels: {
						formatter: function (val) {
							return Math.round(val)+"%";
						},		  
						offsetY: -16,
						style: { fontSize: '12px', colors: ["#2047F8"] }
					},					
					xaxis: {
						type: 'category',
						categories: arr_label,
						labels: { rotate: -30, rotateAlways: true, offsetY: -8,	minHeight: 30, },
					},				
					yaxis:{
						floating: true,
						max: Math.max(max_ave*100/total_ave, max_tod*100/total_tod) + 10,
						labels: {
							formatter: function (val) {
								return Math.round(val);				 
							},
						},
					},
					plotOptions: {
						bar: { columnWidth: '60%', distributed: true, }
					},
					grid: {
						yaxis: {
							lines: { show: false }
						}, 			
					}				
				});	

			
				var arr_data = new Array();
				for(i=0; i<(response["data"].length-2); i++) {
					arr_data.push(response["data"][i]);
				}

				age_curve.updateSeries(arr_data);
				age_curve.updateOptions({
					chart: { height: 150,},
					dataLabels: {enabled: false,},				
					xaxis: {
						categories: timeToDatetime(response["label"],"YYYY-MM-DD HH:mm"),
					},
					colors: colors_ref,
					tooltip: {x: {format: tooltip_date,},
					},
				});	
				
				var arr_data = new Array();
				for(i=response["data"].length-2; i<response["data"].length; i++) {
					arr_data.push(response["data"][i]);
				}

				gender_curve.updateSeries(arr_data);
				gender_curve.updateOptions({
					chart: { height: 150,},
					dataLabels: {enabled: false,},					
					xaxis: {
						categories: timeToDatetime(response["label"],"YYYY-MM-DD HH:mm"),
					},
					colors: ["rgb(54, 162, 235)","rgb(255, 99, 132)"],
					tooltip: {
						x: { format: tooltip_date, },
					},
				});					
				
				for(i=0; i<4; i++) {
					var g_id = document.getElementById("gender"+(i+1));
					config_gender_bar.title = response["title"]["gender_title"][i];
					config_gender_bar.label = [response["data"][5]["name"], response["data"][6]["name"]];
					if(i==0) {
						config_gender_bar.data = [response["data"][5]["total"], response["data"][6]["total"]]
					}
					else {
						config_gender_bar.data = [response["data"][5]["data"][80+i], response["data"][6]["data"][80+i]];
					}
					genderBar(g_id, config_gender_bar);
				}
			});
		}
		else if (third == 'curve_by_label') {
			var curve_chart = new ApexCharts(document.querySelector("#third_block_curve_chart"), options_curve);
			curve_chart.render();


			var url = "./inc/query.php?fr=dashBoard&page=curveByLabel&fm=json&sq="+square+"&st="+store+"&time_ref="+time_ref;
			console.log(url);
			colors = new  Array();

			$.getJSON(url, function(response) {
				console.log(response);
				for (i=0; i <response["data"].length; i++){
					colors.push(response["data"][i]['color']);
				}
				
				curve_chart.updateOptions({
					chart: { height: 300, },
					legend: {floating: false,},
					xaxis: {
						categories: timeToDatetime(response["category"]['timestamps'],"YYYY-MM-DD HH:mm"),
					},
					colors: colors,
					tooltip: {x: {format: tooltip_date,},
					},
					title:{
						text: response["title"]["chart_title"],
					},
					grid: {
						borderColor: '#e7e7e7',
						row: { colors: ['#f3f3f3', 'transparent'], 	opacity: 0.5},
					},						
				});	
				curve_chart.updateSeries( response["data"]);
			});
		}

		else if (third == 'table') {
			var url = "./inc/query.php?fr=dashBoard&page=table&fm=json&sq="+square+"&st="+store+"&time_ref="+time_ref;
			console.log(url);

		}		

	}
	
}

else if(Get['fr'] == "dataGlunt") {
	var chart = new ApexCharts(document.querySelector("#chart_curve"), options_curve);
	chart.render();

	function doAnalysis() {
		var square_ref = document.getElementById("square").value;
		var store_ref = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		var time_ref = document.getElementById("refdate_from").value + '~' + document.getElementById("refdate").value;
		if(!time_ref) {
			return false;
		}
		var url = "./inc/query.php?fr=dataGlunt&fm=json&labels=" + Get['labels'] + "&sq=" + square_ref + "&st=" + store_ref + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		
		var tooltip_fmt = tooltip_time;
		var dateformat = tooltip_time;
		if(view_by == "day"){
			tooltip_fmt = tooltip_date; 
			dateformat = tooltip_time;
		}		
		else if(view_by == "month"){
			tooltip_fmt = "yyyy-MM"; 
			dateformat = "yyyy-MM"; 
		}		

		$.getJSON(url, function(response) {
			console.log(response);
			chart.updateSeries(response["data"]);
			chart.updateOptions({
				chart: {
					height: 500,
					// dropShadow: {
						// enabled: true,
					// 	color: '#000',
					// 	top: 18,
					// 	left: 7,
					// 	blur: 10,
					// 	opacity: 0.2
					// },						
				},
				stroke: { width:5,},
				xaxis: {
					categories: timeToDatetime(response["category"]['timestamps'],datetime_picker_option_locale[_selected_language].format),
				},
				tooltip: { x: { format: tooltip_fmt, }, },
				title:{ text: response["title"]["chart_title"], },
				// grid: {
				// 	borderColor: '#e7e7e7',
				// 	row: { colors: ['#f3f3f3', 'transparent'], 	opacity: 0.5},
				// },					
			});
		
			str_table = '<tr><th>Datetime</th>';
			for(i=0; i<response['data'].length; i++) {
				str_table += '<th>'+ response['data'][i]['name'] +'</th>';
			}
			str_table += '</tr>';
			
			for(i=0; i<response['category']['timestamps'].length; i++) {
				str_table += '<tr>';
				str_table += '<td>' + moment(new Date((response['category']['timestamps'][i]-3600*8)*1000)).format("YYYY-MM-DD HH:mm") + '</td>';
				for(j=0; j<response['data'].length; j++) {
					if (response['data'][j]['data'][i] == null) {
						response['data'][j]['data'][i] ='';
					}
					str_table += '<td>' + response['data'][j]['data'][i] +'</td>';
				}
				str_table += '</tr>';
			}
			
			str_table ='<table class="table table-striped table-bordered table-hover no-margin">'+str_table+'</table>';
			document.getElementById("footfall_table").innerHTML = str_table;
		});
	}
}

else if(Get['fr'] == "dataGlunt_old") {
	var chart = new ApexCharts(document.querySelector("#chart_curve"), options_curve);
	chart.render();

	function doAnalysis() {
		var square_ref = document.getElementById("square").value;
		var store_ref = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		var time_ref = document.getElementById("refdate_from").value + '~' + document.getElementById("refdate").value;
		if(!time_ref) {
			return false;
		}
		var url = "./inc/query.php?fr=dataGlunt&fm=json&labels=" + Get['labels'] + "&sq=" + square_ref + "&st=" + store_ref + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		var tooltip_fmt = tooltip_datetime;
		if(view_by == "day"){
			tooltip_fmt = tooltip_date; 
		}		
		else if(view_by == "month"){
			tooltip_fmt = "yyyy-MM"; 
		}		

		$.getJSON(url, function(response) {
			console.log(response);
			chart.updateSeries(	response["data"]);
			chart.updateOptions({
				chart: {
					height: 500,
					dropShadow: {
						enabled: true,
						color: '#000',
						top: 18,
						left: 7,
						blur: 10,
						opacity: 0.2
					},						
				},
				stroke: { width:5,},
				xaxis: {
					categories: timeToDatetime(response["category"]['timestamps'],datetime_picker_option_locale[_selected_language].format),
				},
				tooltip: { x: { format: tooltip_fmt, }, },
				title:{ text: response["title"]["chart_title"], },
				grid: {
					borderColor: '#e7e7e7',
					row: { colors: ['#f3f3f3', 'transparent'], 	opacity: 0.5},
				},					
			});
		
			str_table = '<tr><th>Datetime</th>';
			for(i=0; i<response['data'].length; i++) {
				str_table += '<th>'+ response['data'][i]['name'] +'</th>';
			}
			str_table += '</tr>';
			
			for(i=0; i<response['category']['timestamps'].length; i++) {
				str_table += '<tr>';
				str_table += '<td>' + moment(new Date((response['category']['timestamps'][i]-3600*8)*1000)).format("YYYY-MM-DD HH:mm") + '</td>';
				for(j=0; j<response['data'].length; j++) {
					if (response['data'][j]['data'][i] == null) {
						response['data'][j]['data'][i] ='';
					}
					str_table += '<td>' + response['data'][j]['data'][i] +'</td>';
				}
				str_table += '</tr>';
			}
			
			str_table ='<table class="table table-striped table-bordered table-hover no-margin">'+str_table+'</table>';
			document.getElementById("footfall_table").innerHTML = str_table;
		});
	}
}
else if(Get['fr'] == "latestFlow") {
	function changeViewOn(viewon) {
		document.getElementById("view_on").value = viewon;
		document.getElementById("7day").style.backgroundColor="";
		document.getElementById("4week").style.backgroundColor="";
		document.getElementById("12week").style.backgroundColor="";
		document.getElementById(viewon).style.backgroundColor="#fcc100";
		doAnalysis();
	}
	
	var chart = new ApexCharts(document.querySelector("#chart_curve"), options_curve);
	chart.render();
	
	function doAnalysis() {
		var square_ref = document.getElementById("square").value;
		var store_ref = document.getElementById("store").value;
		var view_on = document.getElementById("view_on").value;
		var url = "./inc/query.php?fr=latestFlow&fm=json&labels=" + Get['labels'] + "&sq=" + square_ref + "&st=" + store_ref + "&view_on=" + view_on;
		console.log(url);
		var tooltip_fmt = "yyyy-MM-dd";
		$.getJSON(url, function(response) {
			console.log(response);
			chart.updateSeries(	response["data"]);
			chart.updateOptions({
				chart: {
					height: 500,
					dropShadow: {
						enabled: true,
						color: '#000',
						top: 18,
						left: 7,
						blur: 10,
						opacity: 0.2
					},						
				},
				stroke: { width:5,},
				xaxis: {
					categories: timeToDatetime(response['category']['timestamps'],datetime_picker_option_locale[_selected_language].format),
				},
				tooltip: { x: { format: tooltip_fmt, }, },
				title:{ text: response["title"]["chart_title"], },
				grid: {
					borderColor: '#e7e7e7',
					row: { colors: ['#f3f3f3', 'transparent'], 	opacity: 0.5},
				},					
			});

			str_table = '<tr><th>Datetime</th>';
			for(i=0; i<response['data'].length; i++) {
				str_table += '<th>'+ response['data'][i]['name'] +'</th>';
			}
			str_table += '</tr>';
			
			for(i=0; i<response['category']['timestamps'].length; i++) {
				str_table += '<tr>';
				str_table += '<td>' + moment(new Date((response['category']['timestamps'][i]-3600*8)*1000)).format("YYYY-MM-DD HH:mm") + '</td>';
				for(j=0; j<response['data'].length; j++) {
					if (response['data'][j]['data'][i] == null) {
						response['data'][j]['data'][i] ='';
					}
					str_table += '<td>' + response['data'][j]['data'][i] +'</td>';
				}
				str_table += '</tr>';
			}
			
			str_table ='<table class="table table-striped table-bordered table-hover no-margin">'+str_table+'</table>';
			document.getElementById("footfall_table").innerHTML = str_table;
		});
	}
	$(document).ready(function(){
		doAnalysis();
	});
}

else if(Get['fr'] == "trendAnalysis") {
	var chart_b = new ApexCharts(document.querySelector("#chart_bar"), options_bar);
	var chart_c = new ApexCharts(document.querySelector("#chart_curve"), options_curve);
	chart_b.render();
	chart_c.render();

	function doAnalysis() {
		var square_ref = document.getElementById("square").value;
		var store_ref = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		var time_ref = document.getElementById("refdate").value;
		var url = "./inc/query.php?fr=trendAnalysis&fm=json&sq=" + square_ref + "&st=" + store_ref + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		var tooltip_fmt = "HH:mm";
		var dateformat = "HH:mm";
		if(view_by == "day"){
			tooltip_fmt = tooltip_date;
			var dateformat = "YYYY-MM-DD(ddd)";
		}
		$.getJSON(url, function(response) {
			console.log(response);
			chart_c.updateSeries( response["data"]);
			chart_c.updateOptions({
				chart: { height: 500, },
				legend: {floating: false,},
				xaxis: {
					categories: timeToDatetime(response["category"]['timestamps'],datetime_picker_option_locale[_selected_language].format),
				},
				tooltip: {x: {format: tooltip_fmt,},
				},
				title:{
					text: response["title"]["chart_title"],
				},
			});

			chart_b.updateOptions({
				chart: { height: 500, },
				legend: {floating: false,},				
				xaxis: { categories :['Total'], },
				tooltip: { x: { format: tooltip_fmt, },	},
				title:{	text: response["title"]["chart_title"],	},
			});
			chart_b.updateSeries([
				{name: response['data'][0]['name'], data: [arraySum(response["data"][0]["data"])]},
				{name: response['data'][1]['name'], data: [arraySum(response["data"][1]["data"])]},
				{name: response['data'][2]['name'], data: [arraySum(response["data"][2]["data"])]}
			]);
			
			str_table = '<tr>' +
							'<th>Datetime</th>' +
							'<th>' + response["data"][0]["name"] + '</th>' +
							'<th>' + response["data"][1]["name"] +'</th>' +
							'<th>' + response["data"][2]["name"] +'</th>' +
						'</tr>';
			for(i=0; i<response["data"][0]['data'].length; i++) {
				str_table += '<tr>';
				str_table += '<th>' + moment(new Date((response['category']['timestamps'][i]-3600*8)*1000)).format(dateformat) + '</th>' + 
							 '<td>' + response["data"][0]["data"][i] + '</td>' +
							 '<td>' + response["data"][1]["data"][i] + '</td>' +
							 '<td>' + response["data"][2]["data"][i] + '</td>';
				str_table += '</tr>';
			}
			str_table ='<table class="table table-striped table-bordered table-hover no-margin">' + str_table + '</table>';
			document.getElementById("footfall_table").innerHTML = str_table;			
		});
	};
}

else if(Get['fr'] == "advancedAnalysis" || Get['fr'] == "compareByLabel" ) {
	var chart = new ApexCharts(document.querySelector("#chart_curve"), options_curve);
	chart.render();

	function doAnalysis() {
		var square_ref = document.getElementById("square").value;
		var store_ref = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		var time_ref = document.getElementById("refdate_from").value + '~' + document.getElementById("refdate").value;
		if(!time_ref) {
			return false;
		}
		// var url = "./inc/query.php?fr=advancedAnalysis&fm=json&sq=" + square_ref + "&st=" + store_ref + "&view_by=" + view_by + "&time_ref=" + time_ref;
		var url = "./inc/query.php?fr=" + Get['fr'] +"&fm=json&sq=" + square_ref + "&st=" + store_ref + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		var tooltip_fmt = tooltip_datetime;
		if(view_by == "day"){
			tooltip_fmt = tooltip_date; 
		}		
		else if(view_by == "month"){
			tooltip_fmt = "yyyy-MM"; 
		}		

		$.getJSON(url, function(response) {
			console.log(response);
			chart.updateSeries(response["data"]);
			chart.updateOptions({
				chart: {
					height: 500,
					dropShadow: {
						enabled: true,
						color: '#000',
						top: 18,
						left: 7,
						blur: 10,
						opacity: 0.2
					},						
				},
				stroke: { width:5,},
				xaxis: {
					categories: timeToDatetime(response["category"]['timestamps'],datetime_picker_option_locale[_selected_language].format),
				},
				tooltip: { x: { format: tooltip_fmt, }, },
				title:{ text: response["title"]["chart_title"], },
				grid: {
					borderColor: '#e7e7e7',
					row: { colors: ['#f3f3f3', 'transparent'], 	opacity: 0.5},
				},					
			});

			str_table = '<tr><th>Datetime</th>';
			for (j=0; j<response['data'].length; j++){
				str_table += '<th>'+response["data"][j]["name"]+'</th>';
			}
			str_table += '</tr>';
			for(i=0; i<response['category']['timestamps'].length; i++) {
				str_table += '<tr><td>' + moment(new Date((response['category']['timestamps'][i]-3600*8)*1000)).format("YYYY-MM-DD HH:mm") + '</td>';
				for (j=0; j<response['data'].length; j++){
					str_table += '<th>'+ response["data"][j]["data"][i] + '</td>';
				}
				str_table += '</tr>';
			}
			str_table ='<table class="table table-striped table-bordered table-hover no-margin">' + str_table + '</table>';
			document.getElementById("footfall_table").innerHTML = str_table;			
		});
	}
}

// else if(Get['fr'] == "compareByLabel") {
// 	var chart = new ApexCharts(document.querySelector("#chart_curve"), options_curve);
// 	chart.render();

// 	function doAnalysis() {
// 		var square_ref = document.getElementById("square").value;
// 		var store_ref = document.getElementById("store").value;
// 		var view_by = document.getElementById("view_by").value;
// 		var time_ref = document.getElementById("refdate_from").value + '~' + document.getElementById("refdate").value;
// 		if(!time_ref) {
// 			return false;
// 		}
// 		var url = "./inc/query.php?fr=compareByLabel&fm=json&sq=" + square_ref + "&st=" + store_ref + "&view_by=" + view_by + "&time_ref=" + time_ref;
// 		console.log(url);
// 		var tooltip_fmt = tooltip_datetime;
// 		if(view_by == "day"){
// 			tooltip_fmt = tooltip_date; 
// 		}		
// 		else if(view_by == "month"){
// 			tooltip_fmt = "yyyy-MM"; 
// 		}		

// 		$.getJSON(url, function(response) {
// 			console.log(response);
// 			chart.updateSeries(response['data']);

// 			chart.updateOptions({
// 				chart: {
// 					height: 500,
// 					dropShadow: {
// 						enabled: true,
// 						color: '#000',
// 						top: 18,
// 						left: 7,
// 						blur: 10,
// 						opacity: 0.2
// 					},						
// 				},
// 				stroke: { width:5,},
// 				xaxis: {
// 					categories: timeToDatetime(response['category']['timestamps'], datetime_picker_option_locale[_selected_language].format),
// 					// categories: cat_res,
					
// 				},
// 				tooltip: { x: { format: tooltip_fmt, }, },
// 				title:{ text: response["title"]["chart_title"], },
// 				grid: {
// 					borderColor: '#e7e7e7',
// 					row: { colors: ['#f3f3f3', 'transparent'], 	opacity: 0.5},
// 				},					
// 			});
		
// 			str_table = '<tr><th>Datetime</th>';
// 			for(i=0; i<response['data'].length; i++) {
// 				str_table += '<th>'+ response['data'][i]['name'] +'</th>';
// 			}
// 			str_table += '</tr>';
			
// 			for(i=0; i<response['category']['timestamps'].length; i++) {
// 				str_table += '<tr>';
// 				str_table += '<td>' + moment(new Date((response['category']['timestamps'][i]-3600*8)*1000)).format("YYYY-MM-DD HH:mm") + '</td>';
// 				for(j=0; j<response['data'].length; j++) {
// 					if (response['data'][j]['data'][i] == null) {
// 						response['data'][j]['data'][i] ='';
// 					}
// 					str_table += '<td>' + response['data'][j]['data'][i] +'</td>';
// 				}
// 				str_table += '</tr>';
// 			}
			
// 			str_table ='<table class="table table-striped table-bordered table-hover no-margin">'+str_table+'</table>';
// 			document.getElementById("footfall_table").innerHTML = str_table;
// 		});
// 	}
// }

else if(Get['fr'] == "kpi") {
	function doAnalysis() {
		console.log("kpi Analysis");
		var square_ref = document.getElementById("square").value;
		var store_ref = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		var time_ref = document.getElementById("refdate_from").value + '~' + document.getElementById("refdate").value;
		if(!time_ref) {
			return false;
		}
		var url = "./inc/query.php?fr=kpi&fm=json&sq=" + square_ref + "&st=" + store_ref + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			for(i=0; i<response["card_val"].length; i++) {
				document.getElementById("card_val["+i+"]").innerHTML  = response["card_val"][i];
			}

		});
	}
}

else if(Get['fr'] == "promotionAnalysis") {
	function doAnalysis() {
		console.log("Promotion Analysis");
		
		
		
	}
}

else if(Get['fr'] == "brandOverview") {
	function doAnalysis() {
		console.log("Brand Overview");
		
		
		
	}
}

else if(Get['fr'] == "weatherAnalysis") {
	function doAnalysis() {
		console.log("Weather Analysis");
		
		
		
	}
}

else if(Get['fr'] == "compareByTime") {
	document.addEventListener("DOMContentLoaded", function(event) {
		$("input[name=\"refdate1\"]").daterangepicker(configDateTo);
		configDateTo.startDate = moment(new Date()-3600*24*7*1000).format(date_picker_option_locale[_selected_language].format); 
		$("input[name=\"refdate2\"]").daterangepicker(configDateTo);
		configDateTo.startDate = moment(new Date()-3600*24*14*1000).format(date_picker_option_locale[_selected_language].format); 
		$("input[name=\"refdate3\"]").daterangepicker(configDateTo);
	});
	
	var load_num = 3;
	function changeDate() {
		console.log(load_num);
		if(load_num) {
			load_num--;
			return false;
		}
		var ref_time1 = document.getElementById("refdate1").value;
		var ref_time2 = document.getElementById("refdate2").value;
		var ref_time3 = document.getElementById("refdate3").value;
		myTime1 = new Date(Date.parse(ref_time1)).getTime();
		myTime2 = new Date(Date.parse(ref_time2)).getTime();
		myTime3 = new Date(Date.parse(ref_time3)).getTime();
		thisTime = new Date().getTime();
		
		if(myTime1 > thisTime) {
			configDateTo.startDate = moment(thisTime).format(date_picker_option_locale[_selected_language].format);
			$("input[name=\"refdate1\"]").daterangepicker(configDateTo);
		}
		if(myTime2 > thisTime) {
			configDateTo.startDate = moment(thisTime).format(date_picker_option_locale[_selected_language].format);
			$("input[name=\"refdate2\"]").daterangepicker(configDateTo);
		}
		if(myTime3 > thisTime) {
			configDateTo.startDate = moment(thisTime).format(date_picker_option_locale[_selected_language].format);
			$("input[name=\"refdate3\"]").daterangepicker(configDateTo);
		}
		doAnalysis();
	}
	$(document).ready(function(){
		document.getElementById("refdate1").style.borderColor = colors_ref[0];
		document.getElementById("refdate2").style.borderColor = colors_ref[1];
		document.getElementById("refdate3").style.borderColor = colors_ref[2];
	});	
	

	var chart_b = new ApexCharts(document.querySelector("#chart_bar"), options_bar);
	var chart_c = new ApexCharts(document.querySelector("#chart_curve"), options_curve);
	chart_b.render();
	chart_c.render();
	chart_b.updateOptions({chart: {height:500,}});
	chart_c.updateOptions({chart: {height:500,}});

	function doAnalysis() {
		var time_ref1 = document.getElementById("refdate1").value;
		var time_ref2 = document.getElementById("refdate2").value;
		var time_ref3 = document.getElementById("refdate3").value;
		var square = document.getElementById("square").value;
		var store = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;

		var url = "./inc/query.php?fr=compareByTime&fm=json&sq="+square+"&st="+store+"&view_by="+view_by+"&time_ref1="+time_ref1+"&time_ref2="+time_ref2+"&time_ref3="+time_ref3;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			chart_c.updateSeries( response["data"]);
			chart_c.updateOptions({
				chart: { height: 500, },
				legend: {floating: false,},
				xaxis: {
					// type: "categories",
					// categories :response['category']['timestamps'],
					categories: timeToDatetime(response['category']['timestamps'], datetime_picker_option_locale[_selected_language].format),
				},
				tooltip: { x: { format: tooltip_datetime,}, },
				title:{ text: response["title"]["chart_title"], },
			});

			chart_b.updateOptions({
				chart: {height: 500, },
				legend: {floating: false,},
				xaxis: { categories :['Total'], },
				title:{ text: response["title"]["chart_title"], },
			});
			// chart_b.updateSeries([
			// 	{name: response['data'][0]['name'], data: [arraySum(response["data"][0]["data"])]},
			// 	{name: response['data'][1]['name'], data: [arraySum(response["data"][1]["data"])]},
			// 	{name: response['data'][2]['name'], data: [arraySum(response["data"][2]["data"])]}
			// ]);
			var arr_ds = new Array();
			var sum = 0;
			for (var i=0; i<response['data'].length; i++){
				if(response['data'][i]) {
					sum = arraySum(response["data"][i]["data"]);
					// console.log(i, sum);
					arr_ds.push({name: response['data'][i]['name'], data: [sum]});
				}
			}
			chart_b.updateSeries(arr_ds);			
			
		});
	};

	$(document).ready(function(){
		doAnalysis();
	});
}

else  if(Get['fr'] == "compareByPlace") {
	listSquare("#square1");
	listSquare("#square2");
	listSquare("#square3");

	$(document).ready(function(){
		document.getElementById("square1").style.borderColor = colors_ref[0];
		document.getElementById("square2").style.borderColor = colors_ref[1];
		document.getElementById("square3").style.borderColor = colors_ref[2];
		document.getElementById("store1").style.borderColor = colors_ref[0];
		document.getElementById("store2").style.borderColor = colors_ref[1];
		document.getElementById("store3").style.borderColor = colors_ref[2];
	});		

	var chart_b = new ApexCharts(document.querySelector("#chart_bar"), options_bar);
	var chart_c = new ApexCharts(document.querySelector("#chart_curve"), options_curve);
	chart_b.render();
	chart_c.render();
	chart_b.updateOptions({chart: {height:500,}});
	chart_c.updateOptions({chart: {height:500,}});

	function doAnalysis() {
		var square_ref1 = document.getElementById("square1").value;
		var square_ref2 = document.getElementById("square2").value;
		var square_ref3 = document.getElementById("square3").value;
		var store_ref1 = document.getElementById("store1").value;
		var store_ref2 = document.getElementById("store2").value;
		var store_ref3 = document.getElementById("store3").value;
		var view_by = document.getElementById("view_by").value;
		var time_ref = document.getElementById("refdate_from").value + '~' + document.getElementById("refdate").value;
		
		if((square_ref1==0) && (square_ref2==0) && (square_ref3==0)) {
			console.log(chart_b);
			return false;
		}
		var url = "./inc/query.php?fr=compareByPlace&fm=json&labels=" + Get['labels'] + "&sq1=" + square_ref1 + "&sq2=" + square_ref2 + "&sq3=" + square_ref3 + "&st1=" + store_ref1 + "&st2=" + store_ref2 + "&st3=" + store_ref3 + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		var tooltip_fmt = tooltip_datetime;
		if(view_by == "day"){
			tooltip_fmt = tooltip_date; 
		}
		
		$.getJSON(url, function(response) {
			console.log(response);
			chart_c.updateSeries(response["data"]);
			chart_c.updateOptions({
				chart: { height: 500, },
				legend: {floating: false,},
				xaxis: {
					type: "datetime",
					categories: timeToDatetime(response['category']['timestamps'], datetime_picker_option_locale[_selected_language].format),
				},
				tooltip: { x: { format: tooltip_fmt,}, },
				title:{ text: response["title"]["chart_title"], },
			});
			
			chart_b.updateOptions({
				legend: { show:true, showForSingleSeries: true, position:"top", offsetX: 0, floating: false,},
				chart: {height:500,},
				xaxis: {categories :['Total'],},
				title:{	text: response["title"]["chart_title"],	},
			});
			
			var arr_ds = new Array();
			var sum = 0;
			for (var i=0; i<response['data'].length; i++){
				if(response['data'][i]) {
					sum = arraySum(response["data"][i]["data"]);
					// console.log(i, sum);
					arr_ds.push({name: response['data'][i]['name'], data: [sum]});
				}
			}
			chart_b.updateSeries(arr_ds);
		});
	};
	
}

else if(Get['fr'] == "trafficDistribution") {
	function changeViewOn(view_on) {
		document.getElementById("view_on").value = view_on
		document.getElementById("visit").style.backgroundColor="";
		document.getElementById("occupy").style.backgroundColor="";
		document.getElementById(view_on).style.backgroundColor="#fcc100";
		doAnalysis();
	}
	var options = {
		chart: {
			height: 350,
			type: "heatmap",
		},
		dataLabels: {
			enabled: true,
			style: {
				fontSize: '12px',
				colors: ["#3047D8"]
			}	
		},
		colors: ["#330000"], 
		series: [],
		title: { text: "", },
		xaxis: {
			type: "category",
		},
		noData: { text: "Loading..." },
	}
	
	var chart = new ApexCharts(document.querySelector("#apexcharts-heatmap"),options);
	chart.render();

	function doAnalysis() {
		if(document.getElementById("refdate_from").value == document.getElementById("refdate").value ) {
			return false;
		}
		var square = document.getElementById("square").value;
		var store = document.getElementById("store").value;
		var view_on = document.getElementById("view_on").value;
		var time_ref = document.getElementById("refdate_from").value + '~' + document.getElementById("refdate").value;

		var url = "./inc/query.php?fr=trafficDistribution&fm=json&sq=" + square + "&st=" + store +"&view_on=" + view_on + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			chart.updateSeries( response["data"]);
			var c_height  = response["data"].length*50;
			if( c_height <350) {
				c_height = 350;
			}
			chart.updateOptions({
				chart: {
					height: c_height,
				},
				xaxis: {
					categories : response["label"],
				},
				title:{
					text: response["title"]["chart_title"],
				},
			});
//			console.log(chart);
		});
		
	};

	$(document).ready(function(){
		configDateFrom.startDate = moment(new Date()-3600*24*7*1000).format(date_picker_option_locale[_selected_language].format);
		$("input[name=\"refdate_from\"]").daterangepicker(configDateFrom);
	});
}

else if(Get['fr'] == "heatmap") {
	function changeTime(ref) {
		var ref_hour = document.getElementById("reftime").value;
		var myHour = ref_hour.split(":")[0]*1;
		var thisTime = new Date().getTime();
		if(ref == -1) {
			myHour -= 1;
		}
		else if(ref == 1){
			myHour += 1;
		}

		if(myHour>23) {
			myHour = 0;
			var ref_time = document.getElementById("refdate").value;
			myTime = new Date(Date.parse(ref_time)).getTime() + 3600*24*1000;
			document.getElementById("refdate").value = moment(myTime).format("YYYY-MM-DD");
		}
		else if (myHour <0){
			myHour = 23;
			var ref_time = document.getElementById("refdate").value;
			myTime = new Date(Date.parse(ref_time)).getTime() - 3600*24*1000;
			document.getElementById("refdate").value = moment(myTime).format("YYYY-MM-DD");
		}
		
		if(myTime > thisTime) {
			myTime = thisTime;
			document.getElementById("refdate").value = moment(myTime).format("YYYY-MM-DD");
		}
		document.getElementById("reftime").value = myHour + ":00";
		doAnalysis();
	}	
	
	function changeViewBy(view_by) {
		document.getElementById("view_by").value = view_by;
		document.getElementById("hour").style.backgroundColor="";
		document.getElementById("day").style.backgroundColor="";
		document.getElementById(view_by).style.backgroundColor="#fcc100";
		if(view_by == 'hour') {
			document.getElementById("time_plane").style.display="";
		}
		else {
			document.getElementById("time_plane").style.display="none";
		}
		doAnalysis();
	}

	function listDevice() {
		var d_id =  document.getElementById("deviceSlider");
		var square_ref = document.getElementById("square").value;
		var store_ref = document.getElementById("store").value;
		var pad_id = document.getElementById("heatmapPad");
		pad_id.style.display = 'none';

		var url = "./inc/query.php?fr=heatMap&act=list&fm=json&sq=" + square_ref + "&st=" + store_ref;
		console.log(url);

		d_id.innerHTML ='';
		$.getJSON(url, function(response) {
			console.log(response);
			var image_tag = '';
			for(i=0; i<response.length; i++ ) {
				image_tag = '<img src="'+ response[i]["image"] +'" width="160" height="90" type="button"></img>';
				d_id.innerHTML += '<div class="card ml-2"  OnClick="setDeviceId(\''+response[i]["device_info"]+'\');"><div class="card-body w-100" align="center">' + image_tag + '</div></div>';
			}
		});
		
		
	}
	
	function setDeviceId(str) {
		var dev_id = document.getElementById("s_device");
		dev_id.value = str;
		doAnalysis();
	}
	
	var hm_option = {
		config: {
			radius: 45,
			maxOpacity: .7
		},
		image:{
			src :"",
			width: 800,
			height:450,
		},
		data: {
			max:0,
			data:[{x:0,y:0,value:0}],
		},
		tooltip:{
			id:"tooltipInstance",
		},
	}
	
	var hmc = document.getElementById("heatmapContainer");
	hmc.style.background = "url(" + hm_option.image.src + ") no-repeat";
	hmc.style.backgroundSize = hm_option.image.width + "px "+ hm_option.image.height + "px";
	hmc.style.height = hm_option.image.height + "px";
	hmc.style.width = hm_option.image.width + "px";
	
	var heatmapInstance = h337.create({
		container:hmc, 
		radius:hm_option.config.radius,
		maxOpacity:hm_option.config.radius
	});	
	
	function doAnalysis() {
		var view_by = document.getElementById("view_by").value;
		var time_ref = document.getElementById("refdate").value + " " + document.getElementById("reftime").value;
		var device_ref = document.getElementById("s_device").value;
		
		if(!time_ref) {
			return false;
		}
		if(!device_ref) {
			return false;
		}
		var pad_id = document.getElementById("heatmapPad");
		pad_id.style.display = '';
		
		var url = "./inc/query.php?fr=heatMap&act=draw&fm=json&" + device_ref + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			hm_option.data = {  
				max: response["max"], 
				data: response["data"],
			};
			
			document.getElementById("heatmapHeader").innerHTML = '<h5 class="card-title float-right mb-0">' + response["subtitle"] + '</h5><h5 class="card-title mb-0">' + response["title"] + '</h5>';
			if(response["image"]) {
				hm_option.image.src = response["image"];
				hmc.style.background = "url(" + hm_option.image.src + ") no-repeat";
				hmc.style.backgroundSize = hm_option.image.width + "px "+ hm_option.image.height + "px";
			}
			heatmapInstance.setData(hm_option.data);
			
			if(hm_option.tooltip.id) {
				var tooltip = document.getElementById(hm_option.tooltip.id);
				hmc.onmousemove = function(ev) {
					var x = ev.layerX;
					var y = ev.layerY;
					var value = heatmapInstance.getValueAt({
						x: x,
						y: y
					}); 
					tooltip.style.display = "block";
					updateTooltip(tooltip, x, y, value);
				};
				hmc.onmouseout = function() {
					tooltip.style.display = "none";
				};
			}
			
		});
		
		
	}
	$(document).ready(function(){
		listDevice()
	});
	
}

else if (Get['fr'] =="agegender") {
//	addJavascript("../js/genderGraph.js");
	var gender_bar = new ApexCharts(document.querySelector("#genderBarChart"), options_curve);
	var age_bar = new ApexCharts(document.querySelector("#ageBarChart"), options_curve);
	var age_graph = new ApexCharts(document.querySelector("#ageBGraph"), options_bar);
	gender_bar.render();
	age_bar.render();
	age_graph.render();
	var config_gender_graph = {
		data:[],
		title:"Gener total",
		label:["male", "female"],
		width:"100px",
		height:"190px",
	}
	
	var colors_gender_ref = ['rgb(54, 162, 235)', 'rgb(255, 99, 132)'];
	var colors_age_ref =  ['rgb(255, 99, 132)', 'rgb(255, 159, 64)', 'rgb(255, 205, 86)', 'rgb(75, 192, 192)', 'rgb(54, 162, 235)', 'rgb(153, 102, 255)', 'rgb(201, 203, 207)', 'rgb(60, 60, 60)','rgb(255,255,255)'];

	function doAnalysis() {
		var time_ref = document.getElementById("refdate_from").value + "~" + document.getElementById("refdate").value;
		var square = document.getElementById("square").value;
		var store = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		
		tooltip_fmt = tooltip_datetime;
		if(view_by == "day") {
			tooltip_fmt = tooltip_date;
		}
		
		var url = "./inc/query.php?fr=ageGender&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			gender_bar.updateSeries(response["data"]['gender']);
			gender_bar.updateOptions({
				chart: { type: "bar", height: 250, stacked:true, stackType:"100%"},
				xaxis: {
					type: "datetime",
					categories: timeToDatetime(response["label"],"YYYY-MM-DD HH:mm"),
				},
				yaxis:{
					min:0,
					max:100,
				},
				dataLabels: {
					offsetY: 0,
					offsetX: 5,
					style: {
						fontSize: '12px',
						colors: ['#FFF']
					 },
					formatter: function (val) {
						return Math.round(val) + "%";				 
					},					 
				},
				plotOptions: {
					bar: {
						columnWidth: '150%',
						dataLabels: {
							enabled: true,
							position: "center",
						},				
					}
				},				
				colors: ['rgb(54, 162, 235)', 'rgb(255, 99, 132)'],
				tooltip: {x: {format: tooltip_fmt,},},
			});	
			
			age_bar.updateSeries(response['data']['age']);
			age_bar.updateOptions({
				chart: { type: "bar", height: 250, stacked:true, stackType:"100%"},
				colors: colors_age_ref,
				xaxis: {
					type: "datetime",
					categories: timeToDatetime(response["label"],"YYYY-MM-DD HH:mm"),
				},
				yaxis:{
					min:0,
					max:100,
				},				
				dataLabels: {
//					enabled: false,
					offsetY: 0,
					offsetX: 5,
					style: {
						fontSize: '12px',
						colors: ['#FFF']
					 },
					formatter: function (val) {
						return Math.round(val) + "%";				 
					},	
				},
				plotOptions: {
					bar: {
						columnWidth: '400%',
						dataLabels: {
							enabled: true,
							position: "center",
						},				
					}
				},				
				
				tooltip: {x: {format: "HH:mm",},},
			});	

			// age_graph.updateSeries([{name:response["title"]["age_bar"], data:response['total']['age']}]);
			// age_graph.updateSeries([{name:response['total']['age']['name'], data:response['total']['age']['data']}]);
			age_graph.updateSeries([{name:'AGE', data:response['total']['age']['data']}]);
			var total_age = arraySum(response['total']['age']['data']);
			// console.log(total_age);
			age_graph.updateOptions({
				chart: { type: "bar", height: 250},
				colors: colors_age_ref,
				dataLabels: {
					//enabled: true,
					formatter: function (val) {
						return Math.round(val*100/total_age)+"%";
					},
				},
				xaxis: {
					type: "categories",
					categories:response['total']['age']['name'],
				},
				yaxis:{
					// max: Math.round(max_ave*100/total_ave) + 10,
					labels: {
						formatter: function (val) {
							return Math.round(val);				 
						},
					},
				},
				grid: {
					yaxis: {
						lines: { show: false }
					}, 			
				},
				plotOptions: {
					bar: {
						columnWidth: '75%',
						distributed: true,
					},
				},				
			});
			// var total_ave = 0;
			// var max_ave = 0;

			
			// var arr_data = new Array();
			// var arr_label = new Array();
			// for(i=0; i<(response["data"].length-2); i++) {
			// 	if(response["data"][i]["total"] > max_ave) {
			// 		max_ave = response["data"][i]["total"];
			// 	}
			// 	total_ave += response["data"][i]["total"]*1;
			// }
			// for(i=0; i<(response["data"].length-2); i++) {
			// 	arr_label.push(response["data"][i]["name"]);
			// 	arr_data.push(response["data"][i]["total"]*100 / total_ave);
			// }
			// age_graph.updateSeries([{name:response["title"]["age_bar"], data:arr_data}]);				
			// age_graph.updateOptions({
			// 	chart: { type: "bar", height: 250},
			// 	colors: colors_age_ref,
			// 	dataLabels: {
			// 		enabled: true,
			// 		formatter: function (val) {
			// 			return Math.round(val)+"%";
			// 		},
			// 	},
			// 	xaxis: {
			// 		type: "categories",
			// 		categories: arr_label,
			// 	},
			// 	yaxis:{
			// 		max: Math.round(max_ave*100/total_ave) + 10,
			// 		labels: {
			// 			formatter: function (val) {
			// 				return Math.round(val);				 
			// 			},
			// 		},
			// 	},
			// 	grid: {
			// 		yaxis: {
			// 			lines: { show: false }
			// 		}, 			
			// 	},
			// 	plotOptions: {
			// 		bar: {
			// 			columnWidth: '75%',
			// 			distributed: true,
			// 		},
			// 	},				
			// });	



//			gender_bar.updateOptions({
//				chart: {height:500,},
//				colors: ['rgb(54, 162, 235)', 'rgb(255, 99, 132)'],
//				xaxis: {categories :['Total'],},
//				title:{	text: response["title"]["chart_title"],	},
//			});
			
/*			
			gender_bar.data.labels = response["label"];
			age_bar.data.labels = response["label"];
			
			config_gender_bar.options.title.text =  response["title"]["gender_bar"];
			config_age_bar.options.title.text =  response["title"]["age_bar"];
			config_age_graph.options.title.text =  response["title"]["age_bar"];
			
			gender_bar.data.datasets=[];
			age_bar.data.datasets=[];
			for(i=0; i<(response["data"].length); i++) {
				if(i < (response["data"].length-2)) {
					age_bar.data.datasets.push({label: response["data"][i]["name"], backgroundColor: colors_ref[i], borderColor: colors_ref[i], data: response["data"][i]["data"], pointRadius: 0.5,lineTension: 0.5, fill:false});
				}
				else {
					gender_bar.data.datasets.push({label: response["data"][i]["name"], backgroundColor: colors_gender_ref[i-response["data"].length+2], borderColor: colors_gender_ref[i-response["data"].length+2], data: response["data"][i]["data"], pointRadius: 0.5, lineTension: 0.5, fill:false});
				}

			}
			gender_bar.update();
			
			age_bar.update();

			config_age_graph.data.labels = [];
			config_age_graph.data.datasets[0].data=[];
			config_age_graph.data.datasets[1].data=[];
			var max = 0, total = 0;
			for(i=0; i<(response["data"].length-2); i++) {
				if(response["data"][i]["total"]> max ){
					max = response["data"][i]["total"];
				}
				total += response["data"][i]["total"];
			}
			
			for(i=0; i<(response["data"].length-2); i++) {
				config_age_graph.data.labels.push(response["data"][i]["name"]);
				if(response["data"][i]["total"] == max) {
					config_age_graph.data.datasets[0].data.push("NaN");
					config_age_graph.data.datasets[1].data.push(new Number(response["data"][i]["total"]*100/total).toFixed(2));
				}
				else {
					config_age_graph.data.datasets[0].data.push(new Number(response["data"][i]["total"]*100/total).toFixed(2));
					config_age_graph.data.datasets[1].data.push("NaN");
				}

			}
			age_graph.update();
*/	
			var g_id = document.getElementById("genderGraph");
			config_gender_graph.data = response["total"]['gender'];
			config_gender_graph.title = response["title"]["gender_bar"];
			config_gender_graph.label = [response["data"]["gender"][0]['name'], response["data"]['gender'][1]["name"]]

			// config_gender_graph.data = [response["data"]['gender'][0]["total"], response["data"]['[6]["total"]];
			// config_gender_graph.label = [response["total"][5]["name"], response["data"][6]["name"]];
			// config_gender_graph.title = response["title"]["gender_bar"];
		
			genderBar(g_id, config_gender_graph);
		
		});
	}
}

else if(Get['fr'] == "macsniff") {
	var _seed = 42
		Math.random = function() {
		_seed = (_seed * 16807) % 2147483647
		return (_seed - 1) / 2147483646
	}
	function generateData(baseval, count, yrange) {
		var i = 0;
		var series = [];
		while (i < count) {
			var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;;
			var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
			var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;

			series.push([x, y, z]);
			baseval += 86400000;
			i++;
		}
		return series;
	}

	var options = {
		series: [
			{ name: 'Bubble1', data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, { min: 10, max: 60	}) },
			{ name: 'Bubble2', data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, { min: 10, max: 60	}) },
			{ name: 'Bubble3', data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, { min: 10,	max: 60	}) },
			{ name: 'Bubble4', data: generateData(new Date('11 Feb 2017 GMT').getTime(), 20, { min: 10, max: 60	}) }
		],
		chart: {
			height: 350,
			type: 'bubble',
		},
		dataLabels: {
			enabled: false
		},
		fill: {
			opacity: 0.8
		},
		title: {
			text: 'Simple Bubble Chart'
		},
		xaxis: {
			tickAmount: 12,
			type: 'category',
		},
		yaxis: {
			max: 70
		}
	};

	var chart = new ApexCharts(document.querySelector("#chart"), options);
	chart.render();
	
	function doAnalysis() {
		var time_ref = document.getElementById("refdate_from").value + "~" + document.getElementById("refdate").value;
		var square = document.getElementById("square").value;
		var store = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		
		tooltip_fmt = tooltip_datetime;
		if(view_by == "day") {
			tooltip_fmt = tooltip_date;
		}
		console.log(options.series);
		var url = "./inc/query.php?fr=macSniff&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
//			chart.updateSeries( response["data"] );

		});
		
	}
	
	
	
	
	
}

else if(Get['fr'] == "summary") {
	var footfall_curve = new ApexCharts(document.querySelector("#footfall_curve_chart"), options_curve);
	var age_total = new ApexCharts(document.querySelector("#ageGraph"), options_bar);
	var gender_total = new ApexCharts(document.querySelector("#genderGraph"), options_bar);
	footfall_curve.render();
	age_total.render();
	gender_total.render();
	
	var config_gender_graph = {
		data:[],
		title:"Gener Total",
		label:["male", "female"],
		width: "60",
		height: "120",
//		fontsize: "15px",
	}
	
	function doAnalysis() {
		var time_ref = document.getElementById("refdate").value;
		var square = document.getElementById("square").value;
		var store = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		
		var url = "./inc/query.php?fr=summary&page=footfall&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			footfall_curve.updateSeries( response["data"] );
			footfall_curve.updateOptions({
				chart: { height: 450, },				
				xaxis: {
					type:"categories",
					categories: response["label"],
				},
				tooltip: {
					x: {
//						format: tooltip_fmt,
					},
				},
			});	

			for(i=0; i<response["card"].length; i++) {
				document.getElementById("card["+i+"]").innerHTML  = card_small(response["card"][i][0], response["card"][i][1], response["card"][i][2], response["card"][i][3], response["card"][i][4], response["card"][i][5], response["card"][i][6]);
			}
		});

		var url = "./inc/query.php?fr=summary&page=ageGender&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			var total_ave = 0;
			var max_ave = 0;
			
			var arr_data = new Array();
			var arr_label = new Array();
			var arr_color = new Array();
			for(i=0; i<(response["data"].length-2); i++) {
				if(response["data"][i]["total"] > max_ave) {
					max_ave = response["data"][i]["total"];
				}
				total_ave += response["data"][i]["total"]*1;
			}
			for(i=0; i<(response["data"].length-2); i++) {
				arr_label.push(response["data"][i]["name"]);
				arr_data.push(response["data"][i]["total"]*100 / total_ave);
				if(response["data"][i]["total"] == max_ave) {
					arr_color.push("rgb(255, 99, 132)");
				}
				else {
					arr_color.push("rgb(201, 203, 207)");
				}
			}
			age_total.updateSeries([{name:response["title"]["chart_title"][0], data:arr_data}]);				
			age_total.updateOptions({
				colors: arr_color,
				dataLabels: {
					enabled: true,
					formatter: function (val) {
						return Math.round(val)+"%";
					},
				},
				xaxis: {
					type: "categories",
					categories: arr_label,
				},
				yaxis:{
					max: Math.round(max_ave*100/total_ave) + 10,
					labels: {
						formatter: function (val) {
							return Math.round(val);				 
						},
					},
				},
				grid: {
					yaxis: {
						lines: { show: false }
					}, 			
				},
				plotOptions: {
					bar: {
						columnWidth: '75%',
						distributed: true,
					},
				},				
			});	

			var g_id = document.getElementById("genderGraph");
			config_gender_graph.data = [response["data"][response["data"].length-2]["total"], response["data"][response["data"].length-1]["total"]];
			config_gender_graph.label = [response["data"][response["data"].length-2]["name"], response["data"][response["data"].length-1]["name"]];
			config_gender_graph.title = response["title"]["chart_title"][1];
			genderBar(g_id, config_gender_graph);
			
			console.log(config_gender_graph);
		});
	}
	
	function card_small(title, date, value, line1_title, line1_val, line2_title, line2_val) {
		var str = ''+
			'<div class="float-right text-info">'+ date + '</div>' +
			'<h4 class="mb-2">' + title + '</h4>' +
			'<div class="mb-1"><strong>' + value + '</strong>' + '</div>'+
			'<div class="float-right">'+ line1_val + '</div>' +
			'<div>' + line1_title + '</div>' +
			'<div class="float-right">' + line2_val + '</div>' +
			'<div>' + line2_title +' </div>' ;
		return str;
	}
	
}

else if(Get['fr'] == "standard") {

	var footfall_curve = new ApexCharts(document.querySelector("#footfall_rising_rank"), options_curve);
	var footfall_hourly = new ApexCharts(document.querySelector("#footfall_hourly"), options_curve);
	var footfall_device = new ApexCharts(document.querySelector("#footfall_device"), options_curve);
	
	footfall_curve.render();
	footfall_hourly.render();
	footfall_device.render();

	function doAnalysis() {
		var time_ref = document.getElementById("refdate").value;
		var square = document.getElementById("square").value;
		var store = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		
		var url = "./inc/query.php?fr=standard&page=footfall_rising_rank&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			footfall_curve.updateSeries( response["data"] );
			footfall_curve.updateOptions({
				chart: { height: 350, },	
				colors : ['#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'],
				xaxis: {
					type: "categories",
					categories: response["label"],
				},
				tooltip: {
					x: { format: "",},
				},
			});	
		});

		var url = "./inc/query.php?fr=standard&page=footfall_hourly&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			footfall_hourly.updateSeries( response["data"] );
			footfall_hourly.updateOptions({
				chart: { height: 350, },	
				colors:['#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'],
				xaxis: {
					categories: timeToDatetime(response["label"],"YYYY-MM-DD HH:mm"),
				},
				tooltip: { x: { format: "HH:mm", }, },
			});
		});
		
		var url = "./inc/query.php?fr=standard&page=footfall_device&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			
			console.log(response);
			footfall_device.updateSeries(response["data"]);
			footfall_device.updateOptions({
				chart: { height: 350, },	
				xaxis: {
					categories: timeToDatetime(response["label"],"YYYY-MM-DD HH:mm"),
				},
				tooltip: {
					x: { format: "HH:mm", },
				},
			});
		});
	}
}

else if(Get['fr'] == "premium") {
	var footfall_chart = new ApexCharts(document.querySelector("#footfall_chart"), options_curve);
	var footfall_square = new ApexCharts(document.querySelector("#footfall_square"), options_curve);
	var footfall_store = new ApexCharts(document.querySelector("#footfall_store"), options_curve);
	var footfall_device = new ApexCharts(document.querySelector("#footfall_device"), options_curve);
	footfall_chart.render();
	footfall_square.render();
	footfall_store.render();
	footfall_device.render();

	function doAnalysis() {
		var time_ref = document.getElementById("refdate").value;
		var square = document.getElementById("square").value;
		var store = document.getElementById("store").value;
		var view_by = document.getElementById("view_by").value;
		
		var url = "./inc/query.php?fr=premium&page=footfall&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			footfall_chart.updateSeries(response["data"]);
			footfall_chart.updateOptions({
				chart: { height: 350, },	
				colors:['#008FFB'],
				legend: { showForSingleSeries: false},
				stroke: {width:5,},
				xaxis: {
					categories: timeToDatetime(response["label"], date_picker_option_locale[_selected_language].format),
				},
				tooltip: {
					x: {format: "yyyy-MM-dd",},
				},
			});
		});
		
		var url = "./inc/query.php?fr=premium&page=footfall_square&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			footfall_square.updateSeries(response["data"]);
			footfall_square.updateOptions({
				chart: { height: 350, },	
				xaxis: {
					categories: timeToDatetime(response["label"], date_picker_option_locale[_selected_language].format),
				},
				tooltip: {
					x: {format: "yyyy-MM-dd",},
				},
			});
		});

		var url = "./inc/query.php?fr=premium&page=footfall_store&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			footfall_store.updateSeries( response["data"]);
			footfall_store.updateOptions({
				chart: { height: 350, },	
				xaxis: {
					categories: timeToDatetime(response["label"], date_picker_option_locale[_selected_language].format),
				},
				tooltip: {
					x: { format: "yyyy-MM-dd", },
				},
			});
		});
		
		var url = "./inc/query.php?fr=premium&page=footfall_device&fm=json&sq=" + square + "&st=" + store + "&view_by=" + view_by + "&time_ref=" + time_ref;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			footfall_device.updateSeries( response["data"] );
			footfall_device.updateOptions({
				chart: { height: 350, },	
				stroke: {width:3,},
				xaxis: {
					categories: timeToDatetime(response["label"], date_picker_option_locale[_selected_language].format),
				},
				tooltip: {
					x: { format: "yyyy-MM-dd", },
				},
			});
		});
	}
}

else if(Get['fr'] == "export") {
	document.addEventListener("DOMContentLoaded", function(event) {
		configDateFrom.startDate = moment(new Date()-3600*24*7*1000).format(date_picker_option_locale[_selected_language].format)
		$("input[id=\"refdate_from\"]").daterangepicker(configDateFrom);
		$("input[id=\"refdate\"]").daterangepicker(configDateTo);
	});

	var config = {
		square : [],
		store : [],
		camera:[],
		counter_label: ['n','n','n','n'],
		group: "none",
		startDate: "",
		endDate: "",
		interval: "daily",
		reportfmt: "table",
		order: "asc",
		api_key: "",
		
	};
		
  	var url_query = "./inc/query.php?f=all";
	
	console.log(url_query);
	$.getJSON(url_query, function(data) {
		console.log(data);
//		config.camera = (data);
		for(i=0; i<data.length; i++) {
			config.camera.push({"sq_code":data[i]["sq_code"], "st_code":data[i]["st_code"], "code":data[i]["cam_code"], "name":data[i]["cam_name"], "checked":false, "display":true});
			if(i==0) {
				config.store.push({"sq_code":data[i]["sq_code"], "code":data[i]["st_code"], "name":data[i]["st_name"], "checked":false, "display":false});
				config.square.push({"code":data[i]["sq_code"], "name":data[i]["sq_name"], "checked":false, "display":true});
			}
			else {
				if(data[i-1]["st_code"] != data[i]["st_code"]) {
					config.store.push({"sq_code":data[i]["sq_code"], "code":data[i]["st_code"], "name":data[i]["st_name"], "checked":false, "display":false});
				}
				if(data[i-1]["sq_code"] != data[i]["sq_code"]) {
					config.square.push({"code":data[i]["sq_code"], "name":data[i]["sq_name"], "checked":false, "display":true});
				}
			}
		}
//		console.log(config);
		for(i=0; i< config.square.length; i++) {
			document.getElementById("square_pad").innerHTML += '' +
				'<label class="custom-control custom-checkbox ml-3">' +
					'<input type="checkbox" class="custom-control-input" id="' + config.square[i]["code"] + '" OnChange="changeSquare() ">' +
					'<span class="custom-control-label">' + config.square[i]["name"] + '</span>' +
				'</label>';
		}
	});

	function changeSquare() {
		for(j=0; j<config.store.length; j++) {
			config.store[j].display = false;
		}
		for(i=0; i<config.square.length; i++) {
			config.square[i].checked = document.getElementById(config.square[i]["code"]).checked;
			if(config.square[i].checked) {
				for(j=0; j<config.store.length; j++) {
					if(config.square[i]["code"] == config.store[j]["sq_code"]) {
						config.store[j].display = true;
					}
				}
			}
		}
		console.log(config);
		document.getElementById("store_pad").innerHTML = '';
		for(i=0; i<config.store.length; i++) {
			if(config.store[i]["display"]) {
				ckd = config.store[i]["checked"] ? "checked" : "";
				document.getElementById("store_pad").innerHTML += '' +
				'<label class="custom-control custom-checkbox ml-3">' +
					'<input type="checkbox" class="custom-control-input" id="' + config.store[i]["code"] + '" OnChange="changeStore()" '+ ckd + '>' +
					'<span class="custom-control-label">' +config.store[i]["name"] + '</span>' +
				'</label>';
			}
			else {
				config.store[i].checked = false;
			}
		}
	}
	
	function changeStore() {
		for(i=0; i<config.store.length; i++) {
			if(document.getElementById(config.store[i]["code"])) {
				config.store[i].checked = document.getElementById(config.store[i]["code"]).checked;
			}
		}
		mkAPI();
		console.log(config);
	}

	function setConfig() {
		config.counter_label[0] =  document.getElementById("label_entrance").checked ? 'y' : 'n';
		config.counter_label[1] =  document.getElementById("label_exit").checked ? 'y' : 'n';
		config.counter_label[2] =  document.getElementById("label_outside").checked ? 'y' : 'n';
		config.counter_label[3] =  document.getElementById("label_none").checked ? 'y' : 'n';
		
		config.group = 	document.getElementById("group_none").checked ? 'none' : 
						document.getElementById("group_square").checked ? 'square' :
						document.getElementById("group_store").checked ? 'store' :
						document.getElementById("group_camera").checked ? 'camera' :'';
						
		config.startDate = document.getElementById("refdate_from").value;
		config.endDate = document.getElementById("refdate").value;
		
		config.interval = 	document.getElementById("interval_tenmin").checked ? "tenmin" :
							document.getElementById("interval_hourly").checked ? "hourly" :
							document.getElementById("interval_daily").checked ? "daily" :
							document.getElementById("interval_weekly").checked ? "weekly" :
							document.getElementById("interval_monthly").checked ? "monthly" : "hourly";
							
							
		config.reportfmt = 	document.getElementById("output_table").checked ? "table" :
						document.getElementById("output_csv").checked ? "csv" :
						document.getElementById("output_json").checked ? "json" :
						document.getElementById("output_curve").checked ? "curve" : "table";
		
		config.order =	document.getElementById("order_asc").checked ? "asc" :
						document.getElementById("order_desc").checked ? "desc" : "asc";
		
		mkAPI();
		console.log(config);
	}
	
	
	function mkAPI() {
		var server_address = document.getElementById("server_address").value;
		var store ='';
		var label = '';
		var str = '' +
			'reportfmt=' + config.reportfmt +
			'&from=' + config.startDate + 
			'&to=' + config.endDate +
			'&interval=' + config.interval +
			'&order=' + config.order +
			'&group=' + config.group;
			
		if((config.counter_label[0]=='y') && (config.counter_label[1]=='y') && (config.counter_label[2]=='y') && (config.counter_label[3]=='y')) {
			label = 'all';
		}
		else {
			if(config.counter_label[0]=='y') {
				label =  'entrance';
			}
			if(config.counter_label[1]=='y') {
				if(label) {
					label +=',';
				}
				label += 'exit';
			}
			if(config.counter_label[2]=='y') {
				if(label) {
					label +=',';
				}
				label += 'outside';
			}
			if(config.counter_label[3]=='y') {
				if(label) {
					label +=',';
				}
				label += 'none';
			}
		}
		str += '&label=' + label;
		
		for (i=0; i<config.store.length; i++) {
			if(config.store[i]["checked"]) {
				if(store) {
					store += ',';
				}
				store += config.store[i]["code"];
			}
		}

		str += '&store='+store;
		str += '&api_key=' + document.getElementById("api_key").value;
		str = 'http://' + server_address + '/countreport.php?' + str;

		document.getElementById("query_api").value = str;
	}
	
	function QueryAPI() {
		var url = document.getElementById("query_api").value;
		window.open(url);
		console.log(config);
	}
}

else if(Get['fr'] == "sensors") {
	function deviceInfo(pk) {
		var info_id = document.getElementById("device_info");
		info_id.style.display="";
		var url = "./inc/query.php?fr=sensors&act=info&fm=json&pk=" + pk;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			document.getElementById("code").innerHTML = response["info"]["code"];
			document.getElementById("name").innerHTML = response["info"]["name"];
			document.getElementById("mac").innerHTML = response["info"]["mac"];
			document.getElementById("brand").innerHTML = response["info"]["brand"];
			document.getElementById("model").innerHTML = response["info"]["model"];
			document.getElementById("usn").innerHTML = response["info"]["usn"];
			document.getElementById("product_id").innerHTML = response["info"]["product_id"];
			document.getElementById("store_name").innerHTML = response["info"]["store_name"];
			document.getElementById("initial_access").innerHTML = response["info"]["initial_access"];
			document.getElementById("last_access").innerHTML = response["info"]["last_access"];
			document.getElementById("license").innerHTML = response["info"]["license"];
			for(i=0; i<4; i++) {
				document.getElementById("functions[" + i + "]").innerHTML = response["info"]["functions"][i];
				document.getElementById("features[" + i + "]").innerHTML = response["info"]["features"][i];
			}
			document.getElementById("comment").innerHTML = response["info"]["comment"];
			z = document.getElementById("zone_id");
			z.style.background = 'url("' + response["info"]["snapshot"] + '") no-repeat';
			z.style.backgroundSize = "800px 450px";
			draw_zone(z, response["info"]["zone"]);
		});
	}
	
	function draw_zone(id, zone) {
		console.log(zone);
		var context = id.getContext("2d");
		var width = 800;
		var height =  450;
		context.clearRect(0,0,width,height);
		var P = new Array();
		var x = new Array();
		var y = new Array();
		for (i=0; i<zone.length; i++) {
			P = zone[i]['points'].split(',');
			if(zone[i]['style'] == 'polygon'){
				P.push(P[0]);
			}
			for (j=0; j<P.length; j++) {
				p_xy = P[j].split(":");
				x[j] = Math.round((width*p_xy[0])/65535);
				y[j] = Math.round((height*p_xy[1])/65535);
			}
			context.beginPath(); 	
			context.moveTo(x[0], y[0]);
			for (j=1; j<P.length; j++) {
				context.lineTo(x[j],y[j]);
			}
			if(zone[i]['style'] == 'polygon'){
				context.lineWidth = 0;
				context.fillStyle = 'rgba(' + zone[i]['color'] + ',0.3)';
				context.closePath();
				if(zone[i]['type'] == 'nondetection') {
					context.fillStyle = "rgba(100,100,100,0.6)";
				}
				context.fill();
			}
			else {
				context.lineWidth = 3;
				context.strokeStyle = 'rgba(' + zone[i]['color'] + ',0.5)';
				context.stroke();
			}
			context.font = "12pt Calibri";
			context.fillStyle = 'rgba(' + zone[i]['color'] + ',0.8)';
			context.fillText(zone[i]['name'], x[0], y[0]-10);
		}
		
	}
	
	function doAnalysis() {
		var square_ref = document.getElementById("square").value;
		var store_ref = document.getElementById("store").value;
		var list_id = document.getElementById("device_list");
		var info_id = document.getElementById("device_info");
		info_id.style.display="none";		
//		info_id.innerHTML ='';
		
		var url = "./inc/query.php?fr=sensors&act=list&fm=json&sq=" + square_ref + "&st=" + store_ref;
		console.log(url);
		list_id.innerHTML ='';
		$.getJSON(url, function(response) {
			console.log(response);
			for(i=0; i< response["list"].length; i++) {
				list_id.innerHTML += ''+
					'<div class="col-12 col-md-6 col-lg-4">'+
						'<div class="card">'+
							'<div class="card-header">'+
								'<span class="float-right">'+response["list"][i]['regdate']+'</span>'+
								'<h3 class="card-title mb-0"><b>'+response["list"][i]['name']+'</b></h3>'+
							'</div>'+
							'<img class="card-img-top" src="' + response["list"][i]['snapshot']+'" alt="Unsplash" ></img>'+		
							'<div class="card-body">'+
								'<h5 class="mt-2">' + response["lang"]["square name"] + ': '+response["list"][i]['square_name']+'</h5>'+
								'<h5>' + response["lang"]["store name"] + ': '+ response["list"][i]['store_name']+'</h5>'+
								'<h5>' + response["lang"]["device info"] +': '+ response["list"][i]['device_info']+'</h5> '+
								'<p class="card-text">' + response["lang"]["memo"] + '</p>' +
								'<button class="btn btn-primary btn-sm" OnClick="deviceInfo(' + response["list"][i]["pk"] + ');">' + response["lang"]['detail'] +'</button >' +
							'</div>'+
						'</div>'+
					'</div>';
			}
			
			
		});
			
	}
	$(document).ready(function(){
		doAnalysis();
	});
	
	
}

else if(Get['fr'] == "sitemap") {
	// function getContents() {
	// 	var url = "./inc/query.php?fr=sitemap&fm=json";
	// 	console.log(url);
	// 	$.getJSON(url, function(response) {
	// 		console.log(response);
	// 		document.getElementById("table_body").innerHTML = response["tbody"];
	// 	});
	// }
	// getContents();
	var url = "./inc/query.php?fr=sitemap";
	console.log(url);
	var posting = $.post(url,{});
	posting.done(function(data) {
		// console.log(data);
		document.getElementById("table_body").innerHTML = data;
		
	});

}

else if(Get['fr'] == "version") {
	function getContents() {
		var url = "./inc/query.php?fr=version&fm=json";
		console.log(url);
		document.getElementById("changelog").innerHTML = '';
		$.getJSON(url, function(response) {
			console.log(response);
			for(i=0; i <response.length ; i++) {
				document.getElementById("changelog").innerHTML += ''+
					'<h4 class="d-inline-block"><span class="badge badge-primary">' + response[i]["title"] + '</span></h4>' +
					'<h5 class="d-inline-block ml-2">– ' + response[i]["date"] + '</h5>' + response[i]["body"];
			}
			
		});
	}

	getContents();
}

else if(Get['fr'] == "feedback") {
	$(function() {
		if (!window.Quill) {
			return $("#quill-editor,#quill-toolbar").remove();
		}
		var editor = new Quill("#quill-editor", {
			modules: {
				toolbar: "#quill-toolbar"
			},
			placeholder: "Type something",
			theme: "snow"
		});
	});
	
	function writeContents() {
		var body = document.getElementById("quill-editor").innerHTML;
		var title = document.getElementById("title").value;
		console.log(body);
		var url = "./inc/query.php?fr=feedback&act=write&fm=json";
		var posting = $.post(url,{body:body, title:title});
		posting.done(function(data) {
			console.log(data);
			
		});
	}
	function listContents() {
		var url = "./inc/query.php?fr=feedback&act=list&fm=json";
		console.log(url);
		document.getElementById("listContents").innerHTML = '';
		$.getJSON(url, function(response) {
			console.log(response);
			for(i=0; i <response.length ; i++) {
				document.getElementById("listContents").innerHTML += ''+
				'<h4 class="d-inline-block"><span class="badge badge-primary">' + response[i]["title"] + '</span></h4>' +
				'<h5 class="d-inline-block ml-2">– ' + response[i]["date"]  + '</h5><h5 class="d-inline-block ml-2">(' + response[i]["from"] + ')</h5>' + response[i]["body"];
			}
		});
	}
	
listContents();
	
}





	
$(document).ready(function(){
	listSquare();
  
});		

	