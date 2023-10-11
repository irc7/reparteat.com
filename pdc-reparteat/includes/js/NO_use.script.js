function open_iconfile_new() {
		document.getElementById('Url_image').style.marginTop='10px';
}
function open_iconyoutube_new() {
		document.getElementById('Url_image').style.marginTop='-17px';
		document.getElementById('Url_image').style.marginLeft='35px';
}
function close_iconfile_new() {
		document.getElementById('Url_image').style.marginTop='-1000px';	
}
function open_file_new() {
	document.getElementById('Url_file').style.visibility='visible';
}
function close_file_new() {
	document.getElementById('Url_file').style.visibility='hidden';
}

function actual_date_general() {
	
	var now = new Date();
	
	var day = document.getElementById('Date_dd');
	if(now.getDate() <= 9) {
		var d = "0" + now.getDate();	
	} else {
		var d = now.getDate();
	}
	day.value = d;
	var month = document.getElementById('Date_mm');
	if((now.getMonth()+1) <= 9) {
		var m = "0" + (now.getMonth()+1);	
	} else {
		var m = now.getMonth()+1;
	}
	month.value = m;
	var year = document.getElementById('Date_yyyy');
	year.value = now.getFullYear();
	var hour = document.getElementById('Date_hh');
	if(now.getHours() <= 9) {
		var h = "0" + now.getHours();	
	} else {
		var h = now.getHours();
	}
	hour.value = h;
	var minutos = document.getElementById('Date_ii');
	if(now.getMinutes() <= 9) {
		var mi = "0" + now.getMinutes();	
	} else {
		var mi = now.getMinutes();
	}
	minutos.value = mi;
}

function openOptionSlide() {
	var obj_file = document.getElementById('Option_file');
	var obj_url = document.getElementById('Option_url');
	var i; 
   	for (i=0;i<document.mainform.Class_link.length;i++){ 
      	 if (document.mainform.Class_link[i].checked)
		 	 break; 
   	} 
	
	
	if(document.mainform.Class_link[i].value == 'file') {
		obj_file.style.display = 'block';
		obj_url.style.display = 'none';
	} else if (document.mainform.Class_link[i].value == 'url') {
		obj_file.style.display = 'none';
		obj_url.style.display = 'block';
	} else {
		obj_file.style.display = 'none';
		obj_url.style.display = 'none';
	}
}


function openLink(){
	var obj = document.getElementById('box_image_link');
	var box = document.getElementById('box_block');
	
	if(obj.style.display == "none"){
		obj.style.display = "block";
		box.style.height = "580px";
	} else {
		obj.style.display = "none";
		box.style.height = "540px";
	}
}
function sendImage(a) {
	var file = document.getElementById('Block'+a+'_image');
	var check = document.getElementById('send_image'+a);
	
	if(check.checked){
		file.disabled = false;
		file.style.display = "block";
	} else {
		file.disabled = true;
		file.style.display = "none";
	}	
}

function sendFile(a) {
	var file = document.getElementById('Block'+a+'_file');
	var check = document.getElementById('send_file'+a);
	
	if(check.checked){
		file.disabled = false;
		file.style.display = "block";
	} else {
		file.disabled = true;
		file.style.display = "none";
	}	
}



function openProvince() {
	var sel = document.getElementById('Country');
	var box = document.getElementById('box_provinces');	
	var box2 = document.getElementById('box_provinces2');	
	
	if(sel.value == 'ESP') {
		box.style.display='block';
		box2.style.display='none';
	} else {
		box.style.display='none';
		box2.style.display='block';
	}
	
}

function openProvinceMult(a) {
	var sel = document.getElementById('Country'+a);
	var box = document.getElementById('box_provinces'+a);	
	var box2 = document.getElementById('box_provinces2'+a);	
	
	if(sel.value == 'ESP') {
		box.style.display='block';
		box2.style.display='none';
	} else {
		box.style.display='none';
		box2.style.display='block';
	}
	
}

