// JavaScript Document
function protected_mail(email) {
	var pattern = "!ARROBA!";
	mailok = email.replace(pattern, "@");
	window.open("mailto:"+mailok);
}
function alertConfirm(msg, url) {
	if(confirm(msg)) {
		document.location = (url);
	}
}


function actual_date_start() {
	
	var now = new Date();
	
	var day = document.getElementById('Date_start_dd');
	if(now.getDate() <= 9) {
		var d = "0" + now.getDate();	
	} else {
		var d = now.getDate();
	}
	day.value = d;
	var month = document.getElementById('Date_start_mm');
	if((now.getMonth()+1) <= 9) {
		var m = "0" + (now.getMonth()+1);	
	} else {
		var m = now.getMonth()+1;
	}
	month.value = m;
	var year = document.getElementById('Date_start_yyyy');
	year.value = now.getFullYear();
	var hour = document.getElementById('Date_start_hh');
	if(now.getHours() <= 9) {
		var h = "0" + now.getHours();	
	} else {
		var h = now.getHours();
	}
	hour.value = h;
	var minutos = document.getElementById('Date_start_ii');
	if(now.getMinutes() <= 9) {
		var mi = "0" + now.getMinutes();	
	} else {
		var mi = now.getMinutes();
	}
	minutos.value = mi;
}