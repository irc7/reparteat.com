/*GALERIAS*/
function openNexImgMin() {
	var mini = document.getElementById('select_min');
	var img = document.getElementById('select_image');
	
	if(mini.checked == true) {
		img.checked = false;
		document.getElementById('Url').style.display='block';
	} else {
		document.getElementById('Url').style.display='none';	
	}
}

function openNexImg() {
	var mini = document.getElementById('select_min');
	var img = document.getElementById('select_image');
	
	if(img.checked == true) {
		mini.checked = false;
		document.getElementById('Url').style.display='block';
	} else {
		document.getElementById('Url').style.display='none';	
	}
}

var $b = jQuery.noConflict();			
function changeTypeUploadPhoto(type) {
	$b("#typeUpload").val(type);
	if(type === "single") {
		$b("#one_photo").show();
		$b("#multiple_photo").hide();
		$b('#menuSinglePhoto').css('opacity', '1');
		$b('#menuMultiplePhoto').css('opacity', '0.5');
	} else if(type === "multiple") {
		$b("#one_photo").hide();
		$b("#multiple_photo").show();
		$b('#menuSinglePhoto').css('opacity', '0.5');
		$b('#menuMultiplePhoto').css('opacity', '1');
	}
}