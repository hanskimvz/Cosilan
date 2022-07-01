
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



page_t = location.href.split('/').pop().split('?');
page = page_t[0];
var Get = new Array();
if(page_t[1]) {
	fr_t = page_t[1].split('&');
	for(i=0; i<fr_t.length; i++) {
		x = fr_t[i].split('=');
		Get[x[0]] = x[1];
	}
}
console.log(Get);
var a = '';
var b = '';	


if(Get['fr'] == "database") {
	b = document.getElementById(Get['fr']);
	a = document.getElementById(Get['db']);
}
else {
	a = document.getElementById(Get['fr']);
}
if (a) {
	a.classList.add("active");
}
if (b) {
	b.classList.add("show");
}
function draw_zone(id, zone) {
	// console.log(zone);
	var context = id.getContext("2d");
	var width = 800;
	var height =  450;
	var i = 0;
	var j = 0;
	context.clearRect(0,0,width,height);
	var P = new Array();
	var x = new Array();
	var y = new Array();

	for (i = 0; i<zone.length; i++) {
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

function viewSnapshot(e){
	// console.log(e.src);
	var id = document.getElementById('snapShot');
	id.innerHTML = '<img src="' + e.src + '" height="620" />';
}

if(Get['fr'] == "account"){
	document.addEventListener("DOMContentLoaded", function(event) {
		$('input[name="date_in"]').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			"locale": date_picker_option_locale[_selected_language],
		});
		$('input[name="date_out"]').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			"locale": date_picker_option_locale[_selected_language],
		});
	});

}

else if(Get['fr'] == "device_tree"){
	function scroll_info() {
		var y_p = window.top.scrollY;
		// console.log(y_p);
		y_p -= 100;
		if(y_p<0) {
			y_p = 0;
		}
		document.getElementById('info_frame').style.top=  y_p + "px";
	}

	function viewSquareInfo(pk) {
		scroll_info();
		document.getElementById('delete_pad').style.display='none';
		document.getElementById('rs').innerHTML = '';
		var url = "./admin.php?href=admin&fr=device_tree&mode=view&info=square";
		console.log(url);
		var posting = $.post(url,{});
		posting.done(function(data) {
			document.getElementById("info_page").innerHTML = data;
		});	
		var url = "./inc/query.php?href=admin&fr=square&mode=view&pk=" + pk;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			arr = Object.keys(response).map((key) => [key, response[key]]);
            for (i=0; i< arr.length; i++) {
                document.getElementById(arr[i][0]).value = arr[i][1];
            }
		});
	}

	function viewStoreInfo(pk,sqpk=0) {
		console.log(sqpk);
		scroll_info();
		document.getElementById('delete_pad').style.display='none';
		document.getElementById('rs').innerHTML = '';
		var url = "./admin.php?href=admin&fr=device_tree&mode=view&info=store";
		console.log(url);
		var posting = $.post(url,{});
		posting.done(function(data) {
			document.getElementById("info_page").innerHTML = data;
		});	

		var url = "./inc/query.php?href=admin&fr=store&mode=view&pk=" + pk;
		if(sqpk){
			url += "&sqpk=" + sqpk;
		}
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			arr = Object.keys(response).map((key) => [key, response[key]]);
            for (i=0; i< arr.length; i++) {
                document.getElementById(arr[i][0]).value = arr[i][1];
            }
		});		
	}
	function viewCameraInfo(pk) {
		scroll_info();
		document.getElementById('delete_pad').style.display='none';
		document.getElementById('rs').innerHTML = '';
		var url = "./admin.php?href=admin&fr=device_tree&mode=view&info=camera&pk=" + pk;
		console.log(url);
		var posting = $.post(url,{});
		posting.done(function(data) {
			document.getElementById("info_page").innerHTML = data;
		});	
		
		var url = "./inc/query.php?href=admin&fr=camera&mode=view&pk=" + pk;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			var arr = Object.keys(response).map((key) => [key, response[key]]);
			
			for (var i=0; i< arr.length; i++) {
				// console.log(arr[i][0] + ':' + arr[i][1]);
				if(arr[i][0] == 'snapshot') {
				}
				else if(arr[i][0] == 'zone') {
					z = document.getElementById("zone_config");
					z.style.background = 'url("' +  response["snapshot"] + '") no-repeat';
					z.style.backgroundSize = "800px 450px";	
					z.style.border = "1px";	
					draw_zone(z, response["zone"]);
				}
				else if(arr[i][0] == 'regdate') {
					document.getElementById("regdate").innerHTML = arr[i][1];
				}	
				else if( arr[i][0] == 'countrpt') {
					if (arr[i][1] == 'y') {
						$('#countrpt_k').addClass('fa-check-square').removeClass('fa-square');
						document.getElementById('countrpt_k').style.color='#25d';
						document.getElementById("counter_label").style.display='';
						$("input:checkbox[id='enable_countingline']").removeAttr('disabled');
					}
					else {
						$("input:checkbox[id='enable_countingline']").attr("disabled","disabled");
					}
				}
				else if( arr[i][0] == 'face_det') {
					if (arr[i][1] == 'y') {
						$('#face_det_k').addClass('fa-check-square').removeClass('fa-square');
						document.getElementById('face_det_k').style.color='#25d';
						$("input:checkbox[id='enable_face_det']").removeAttr('disabled');
					}
					else {
						$("input:checkbox[id='enable_face_det']").attr("disabled","disabled");
					}
				}
				else if( arr[i][0] == 'heatmap') {
					if (arr[i][1] == 'y') {
						$('#heatmap_k').addClass('fa-check-square').removeClass('fa-square');
						document.getElementById('heatmap_k').style.color='#25d';
						$("input:checkbox[id='enable_heatmap']").removeAttr('disabled');
					}
					else {
						$("input:checkbox[id='enable_heatmap']").attr("disabled","disabled");
					}
				}
				else if( arr[i][0] == 'macsniff') {
					if (arr[i][1] == 'y') {
						$('#macsniff_k').addClass('fa-check-square').removeClass('fa-square');
						document.getElementById('macsniff_k').style.color='#25d';
						$("input:checkbox[id='enable_macsniff']").removeAttr('disabled');
					}
					else {
						$("input:checkbox[id='enable_macsniff']").attr("disabled","disabled");
					}
				}			
				else if(arr[i][0]=='enable_countingline' || arr[i][0]=='enable_heatmap' || arr[i][0]=='enable_face_det' || arr[i][0]=='enable_macsniff' || arr[i][0]=='flag' ) {
					if (arr[i][1] == 'y') {
						$("input:checkbox[id='"+arr[i][0]+"']").attr("checked","checked");
					}
				}																	
				else if(arr[i][0] == 'enable_snapshot') {
				}
				else {
					// console.log(arr[i][0] + ':' + arr[i][1]);
					if(arr[i][1] == 'entrance' || arr[i][1] == 'exit' || arr[i][1] == 'outside' || arr[i][1] == 'none'){
						if(document.getElementById(arr[i][0])) {
							document.getElementById(arr[i][0]).value = arr[i][1];
						}
						continue;
					}
					document.getElementById(arr[i][0]).value = arr[i][1];
				}
            }
		});			

	}
	
	function modifySquare() {
		var pk = document.getElementById("pk").value;
		if (!document.getElementById("name").value) {
			document.getElementById("name").style.borderColor = "#FF0000";
			return;
		}
		var url = "./inc/query.php?href=admin&fr=square&mode=modify&pk=" + pk;
		console.log(url);
		var posting = $.post(url,{
			pk: pk, 
			code: document.getElementById("code").value, 
			name: document.getElementById("name").value, 
			addr_state: document.getElementById("addr_state").value, 
			addr_city: document.getElementById("addr_city").value, 
			addr_b: document.getElementById("addr_b").value, 
			comment:document.getElementById("comment").value
		});
		posting.done(function(data) {
			console.log(data);
			document.getElementById('rs').innerHTML = data;
			if(data.indexOf("OK") >=0) {
				location.reload();
			}
		});	
	}

	function modifyStore() {
		var pk = document.getElementById("pk").value;
		if (!document.getElementById("name").value) {
			document.getElementById("name").style.borderColor = "#FF0000";
			return;
		}		
		var url = "./inc/query.php?href=admin&fr=store&mode=modify&pk=" + pk;
		console.log(url);
		var posting = $.post(url,{
			pk:pk, 
			code: document.getElementById("code").value, 
			name: document.getElementById("name").value, 
			phone: document.getElementById("phone").value, 
			fax: document.getElementById("fax").value, 
			contact_person: document.getElementById("contact_person").value, 
			contact_tel: document.getElementById("contact_tel").value, 
			addr_state: document.getElementById("addr_state").value, 
			addr_city: document.getElementById("addr_city").value, 
			addr_b: document.getElementById("addr_b").value, 
			open_hour: document.getElementById("open_hour").value, 
			close_hour: document.getElementById("close_hour").value, 
			sniffing_mac: document.getElementById("sniffing_mac").value, 
			area: document.getElementById("area").value, 
			square_code: document.getElementById("square_code").value, 
			comment: document.getElementById("comment").value
		});
		posting.done(function(data) {
			console.log(data);
			document.getElementById('rs').innerHTML = data;
			if(data.indexOf("OK") >=0) {
				location.reload();
			}
		});	
	}

	function modifyDeviceInfo() {
		var pk = document.getElementById("pk").value;
		if (!document.getElementById("name").value) {
			document.getElementById("name").style.borderColor = "#FF0000";
			return;
		}		
		document.getElementById('delete_pad').style.display='none';

		var ct_list = (document.getElementById("ct_list").value).split(',');
		var ct_labels = new Array();
		var ct_names = new Array();
		for(i=0; i<ct_list.length; i++) {
			// console.log(ct_list[i]);
			if(ct_list[i]){
				ct_names.push(ct_list[i]);
				ct_labels.push(document.getElementById(ct_list[i]).value);
			}
		}
		var url = "./inc/query.php?href=admin&fr=camera&mode=modify&pk=" + pk;
		console.log(url);
		var posting = $.post(url,{
			pk: pk,
			code: document.getElementById("code").value, 
			name: document.getElementById("name").value, 
			mac: document.getElementById("mac").value, 
			usn: document.getElementById("usn").value, 
			model: document.getElementById("model").value, 
			brand: document.getElementById("brand").value, 
			product_id: document.getElementById("product_id").value, 
			store_code: document.getElementById("store_code").value, 
			enable_countingline: document.getElementById("enable_countingline").checked, 
			enable_heatmap: document.getElementById("enable_heatmap").checked, 
			enable_face_det: document.getElementById("enable_face_det").checked, 
			enable_macsniff: document.getElementById("enable_macsniff").checked, 
			flag: document.getElementById("flag").checked, 
			comment: document.getElementById("comment").value,
			ct_labels: ct_labels,
			ct_names: ct_names
		});
		posting.done(function(data) {
			console.log(data);
			document.getElementById('rs').innerHTML = data;
			if(data.indexOf("FAIL") < 0) {
				location.reload();
			}			
		});
	}	
	
	function deleteInfo() {
		document.getElementById('rs').innerHTML = '';
		var pk = document.getElementById("pk").value;
		var fr = document.getElementById("fr").value;
		var passwd = document.getElementById("admin_password").value;
		var url = "./inc/query.php?href=admin&fr="+ fr +"&mode=delete&pk=" + pk;
		console.log(url);	
		var posting = $.post(url,{passwd:passwd});
		posting.done(function(data) {
			console.log(data);
			if(data.indexOf("delete OK") >=0) {
				location.reload();
			}
			else {
				document.getElementById('rs').innerHTML = data;
			}
		});	
	}
	
	function floatingCamera(st_code) {
		scroll_info();
		document.getElementById('delete_pad').style.display='none';
		var url = "./inc/query.php?href=admin&fr=floating_camera&mode=list&st_code=" + st_code;
		console.log(url);
		var posting = $.post(url,{});
		posting.done(function(data) {
//			console.log(data);
			document.getElementById("info_page").innerHTML = data;
		});	
	}

	function addDeviceToStore(st_code, dev_info) {
		document.getElementById('delete_pad').style.display='none';
		var url = "./inc/query.php?href=admin&fr=floating_camera&mode=addToStore&st_code=" + st_code + "&" + dev_info;
		console.log(url);
		var posting = $.post(url,{});
		posting.done(function(data) {
			console.log(data);
			if(data.indexOf("OK") >=0) {
				location.reload();
			}
			// document.getElementById("info_page").innerHTML = data;
		});	
	}

	// function viewDeviceParam(dev_info) {
	// 	scroll_info();
	// 	document.getElementById('delete_pad').style.display='none';		
	// 	var url = "./inc/query.php?href=admin&fr=floating_camera&mode=viewParam&" + dev_info;
	// 	console.log(url);
	// 	var posting = $.post(url,{});
	// 	posting.done(function(data) {
	// 		// console.log(data);
	// 		document.getElementById("info_page").innerHTML = data;
	// 	});
	// 	var url = "./inc/query.php?href=admin&fr=floating_camera&mode=viewParam&fmt=json&" + dev_info;
	// 	console.log(url);
	// 	$.getJSON(url, function(response) {
	// 		// console.log(response);
	// 		z = document.getElementById("zone_config");
	// 		z.style.background = 'url("' + response["info"]["snapshot"] + '") no-repeat';
	// 		z.style.backgroundSize = "800px 450px";
	// 		draw_zone(z, response["info"]["zone"]);			
	// 	});		
		
	// }
	
	
	function viewDeviceParamDetail(dev_info) {
		scroll_info();
		document.getElementById('delete_pad').style.display='none';		
		document.getElementById('rs').innerHTML = '';
		var url = "./admin.php?href=admin&fr=view_param&" + dev_info;
		window.open(url, 'info');

// 		var url = "./inc/query.php?href=admin&fr=floating_camera&mode=viewParam&" + dev_info;
// 		console.log(url);
// 		var posting = $.post(url,{});
// 		posting.done(function(data) {
// //			console.log(data);
// 			document.getElementById("info_page").innerHTML = data;
// 		});
// 		var url = "./inc/query.php?href=admin&fr=floating_camera&mode=viewParam&fmt=json&" + dev_info;
// 		console.log(url);
// 		$.getJSON(url, function(response) {
// //			console.log(response);
// 			z = document.getElementById("zone_config");
// 			z.style.background = 'url("' + response["info"]["snapshot"] + '") no-repeat';
// 			z.style.backgroundSize = "800px 450px";
// 			draw_zone(z, response["info"]["zone"]);			
// 		});		
	}	
	
	function showCounterLabel() {
		if(document.getElementById("enable_countingline").checked == true) {
			document.getElementById("counter_label").style.display ="";
		}
		else {
			document.getElementById("counter_label").style.display ="none";
		}
	}


	
	
}

// else if(Get['fr'] == "list_device"){
// 	page_no = document.getElementById("page_no");
// 	var url = "./inc/query.php?href=admin&fr=list_device&mode=list&page_no=" + page_no;
// 	console.log(url);
// 	var posting = $.post(url,);
// 	posting.done(function(data) {
// 		console.log(data);
// 		if(data.indexOf("OK") >=0) {
// 			console.log("OOOOK");
// 			location.reload();
// 		}
// 	});	
// }


else if(Get['fr'] == "message_setup"){
	var options = {
		modules: {
			toolbar: "#quill-toolbar"
		},
		placeholder: "Type something",
		readonly:false,
		theme: "snow",
	};

	var editor = new Quill("#quill-editor", options);

	function writeContents() {
		var url = "./inc/query.php?href=admin&fr=message&act=write";
		var posting = $.post(url,{
			pk: document.getElementById("pk").value, 
			body: document.getElementById("quill-editor").innerHTML, 
			category: document.getElementById("category").value, 
			title: document.getElementById("title").value, 
			from_p: document.getElementById("from_p").value, 
			to_p: document.getElementById("quill-editor").innerHTML
		});
		posting.done(function(data) {
			console.log(data);
		});
	}

	function listContents() {
		var url = "./inc/query.php?href=admin&fr=message&act=list&fm=json";
		console.log(url);
		document.getElementById("table_body").innerHTML = '';
		$.getJSON(url, function(response) {
			console.log(response);
			for(i=0; i <response["list"].length ; i++) {
				document.getElementById("table_body").innerHTML += '<tr>'+
					'<td>' + response["list"][i]["category"] + '</td>' + 
					'<td OnClick="getInfo('+response["list"][i]["pk"]+')">' + response["list"][i]["title"] + '</td>' +
					'<td>' + response["list"][i]["from"] + '</td>' +
					'<td>' + response["list"][i]["fo"] + '</td>' + 
					'<td>' + response["list"][i]["body"] + '</td>' + 
					'<td>' + response["list"][i]["date"] + '</td>' + 
				'</tr>';
			}
		});
	}
	
	function getInfo(pk) {
		var url = "./inc/query.php?href=admin&fr=message&act=view&fm=json&pk=" + pk;
		console.log(url);
		$.getJSON(url, function(response) {
			console.log(response);
			document.getElementById("pk").value = response['pk'];
			document.getElementById("category").selectedIndex =	response['category'] == "info" ? 0:
																response['category'] == "version" ? 1:
																response['category'] == "feedback" ? 2:
																response['category'] == "message" ? 3: "";
			document.getElementById("title").value = response['title'];
			document.getElementById("pk").value = response['pk'];
			document.getElementById("quill-editor").innerHTML =  '<div class="ql-editor" data-gramm="false" contenteditable="true">' + response['body'] +'</div>';
			document.getElementById("from_p").value = response['from'];
			document.getElementById("to_p").value = response['to'];
		

		});
		
	}
	
	listContents();	
}

else if(Get['fr'] == "web_update"){
	function update_web_software() {
		var url = "./update.php?href=admin&fr=web_update&mode=update";
		var posting = $.post(url,{});
		posting.done(function(data) {
				console.log(data);
		});
	}
}

