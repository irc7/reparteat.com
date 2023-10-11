

function goToSection(idS) {
	var pos = $('#'+idS).offset();
	$('html, body').animate({scrollTop: pos.top}, 3000);
}

function resizeWithProportion(elem, originW, originH) {
	var boxW = $(elem).width();
	var hBox = (boxW * originH) / originW;
	$(elem).height(hBox+"px");
	$(elem).css("min-height", hBox+"px");
}


function resizeSlider() {
	var wBody = parseInt($(window).width());
	
	if(wBody <= maxWidthMovil){
		if ((isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows())) {
			//movil
		}else{
			//pc pantalla muy pequeña
		}
	}else if (wBody > maxWidthMovil && wBody <= maxWidthTablet) {
		if ((isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows())) {
			//Tablet
		}else{
			//pc
		}
	}else{
		//pc
		
	}
}
function isset() {
	var a = arguments,
	l = a.length,
	i = 0,
	undef;
	if (l === 0) {
	throw new Error('Empty isset');
	}
	while (i !== l) {
	if (a[i] === undef || a[i] === null) {
	  return false;
	}
	i++;
	}
	return true;
}