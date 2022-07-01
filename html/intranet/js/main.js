document.getElementById('title').innerHTML = "HELLO" ;
console.log(location.href);
page_t = location.href.split('/').pop().split('?');
page = page_t[0];
if(page_t[1]) {
	fr_t = page_t[1].split('&');
	for(i=0; i<fr_t.length; i++) {
		x = fr_t[i].split('=');
		if( x[0] =='fr') {
			 fr = x[1];
		}
		else if( x[0] =='db') {
			 db = x[1];
		}
	}
}
console.log(fr);
if (fr == 'order'){
	$('#order').addClass('active');
}
else if (fr == 'list_stock' || fr == 'list_warehouse' || fr == 'list_sample' || fr == 'list_serial'){
	$('#warehouse').children(".sidebar-submenu").slideDown(0); 
	$('#warehouse').addClass('active');
}
else if (fr == 'sales_doc' || fr == 'admin_doc' || fr == 'product_doc' || fr == 'write_qt' || fr == 'write_po' || fr == 'write_pi') {
	$('#document').children(".sidebar-submenu").slideDown(0); 
	$('#document').addClass('active');
}



if (fr == 'language'){
    console.log('lang');
}
