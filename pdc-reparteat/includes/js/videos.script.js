//CREATE IMAGE
	
	var $cvideo = jQuery.noConflict();			
	function changeTypeVideoCreate(type) {
		$cvideo("#typeVideo").val(type);
		$cvideo(".error").empty();
		resetFields();
		if(type === "video") {
			$cvideo("#selectVideo").show();
			$cvideo("#selectYoutube").hide();
			$cvideo('#menuVideo').css('opacity', '1');
			$cvideo('#menuYoutube').css('opacity', '0.5');
			$cvideo('#Video').attr('disabled', false);
			$cvideo('#codeYoutube').attr('disabled', true);
			$cvideo('#optionImgPersonal').hide();
			$cvideo('#optionImgYoutube').hide();
			$cvideo('#youtubeImage').attr("checked", false);
			$cvideo('#personalImage').attr("checked", true);
			$cvideo('#Image').attr("disabled", false);
			$cvideo('#Image').css("display", "block");
			includeField('Title','string');
			includeField('Video','file');

		} else if(type === "youtube") {
			$cvideo("#selectVideo").hide();
			$cvideo("#selectYoutube").show();
			$cvideo('#menuVideo').css('opacity', '0.5');
			$cvideo('#menuYoutube').css('opacity', '1');
			$cvideo('#Video').attr('disabled', true);
			$cvideo('#codeYoutube').attr('disabled', false);
			$cvideo('#optionImgPersonal').show();
			$cvideo('#optionImgYoutube').show();
			$cvideo('#youtubeImage').attr("checked", true);
			$cvideo('#personalImage').attr("checked", false);
			$cvideo('#Image').attr("disabled", true);
			$cvideo('#Image').css("display", "none");
			includeField('Title','string');
			includeField('codeYoutube','string');
		}
		
	}
//EDIT VIDEO	
	var $video = jQuery.noConflict();			
	function changeTypeVideo(type, act) {
		$video("#typeVideo").val(type);
		$cvideo(".error").empty();
		resetFields();
		if(type === "video") {
			$video("#selectVideo").show();
			$video("#selectYoutube").hide();
			$video('#menuVideo').css('opacity', '1');
			$video('#menuYoutube').css('opacity', '0.5');
			$video('#changeVideo').attr('disabled', false);
			$video('#Video').attr('disabled', true);
			$video('#codeYoutube').attr('disabled', true);
			$video('#changeImgYoutube').attr('disabled', true);
			$video('#changeImgPersonal').attr('disabled', true);
			$video('#ImageYoutube').attr('disabled', true);
			$video('#changeImg').attr('disabled', false);
			$video('#ImageVideo').attr('disabled', true);
			viewInfoImage("infoImageYoutube");
			viewInfoImage("infoImageVideo");
			includeField('Title','string');
		} else if(type === "youtube") {
			$video("#selectVideo").hide();
			$video("#selectYoutube").show();
			$video('#menuVideo').css('opacity', '0.5');
			$video('#menuYoutube').css('opacity', '1');
			$video('#changeVideo').attr('disabled', true);
			$video('#Video').attr('disabled', true);
			$video('#codeYoutube').attr('disabled', false);
			$video('#changeImgYoutube').attr('disabled', false);//menu imagen for active type file in type youtube
			if(act != "start") {
				$video('#changeImgYoutube').attr('checked', true);
			}
			$video('#changeImgPersonal').attr('disabled', false);
			$video('#ImageYoutube').attr('disabled', true);//type file for image on record type youtube
			$video('#changeImg').attr('disabled', true);//type checkbox for active type file
			$video('#ImageVideo').attr('disabled', true);//type file for image on record type video
			viewInfoImage("infoImageYoutube");
			viewInfoImage("infoImageVideo");
			includeField('Title','string');
			includeField('codeYoutube','string');
		}
	}
	var $cv = jQuery.noConflict();
	function openUploadFile(checkbox, elem){
		if($cv('#'+checkbox).attr('checked')) {
			$cv("#"+elem).fadeIn();
			$cv("#"+elem).attr('disabled', false);
		}else{
			$cv("#"+elem).css('display', 'none');
			$cv("#"+elem).attr('disabled', true);
		}
	}
	function openInfoUploadFile(checkbox, elem){
		if($cv('#'+checkbox).attr('checked')) {
			$cv("#"+elem).fadeIn();
		}else{
			$cv("#"+elem).css('display', 'none');
		}
	}
	var $iv = jQuery.noConflict();
	function ImageMenuYoutube(val){
		if(val == "youtube") {
			$iv("#ImageYoutube").css('display', 'none');
			$iv("#ImageYoutube").attr('disabled', true);
		}else if(val == "personal"){
			$iv("#ImageYoutube").css('display', 'block');
			$iv("#ImageYoutube").attr('disabled', false);
		}
	}
	
	var $sg = jQuery.noConflict();
	function viewInfoImage(info) {
		var g = $sg("#Gallery").val();
		for(var i=0;i<ids.length;i++) {
			if(g == ids[i]){
				var msg = sizes[i];
			}
		}
		$sg("#"+info).html(msg);
	}
	
	function view_link_video(a) {
		var id_obj = "content_url"+a;
		var st = document.getElementById(id_obj).style.visibility;
		if(st == "hidden"){
			document.getElementById(id_obj).style.visibility='visible';
		}else{
			document.getElementById(id_obj).style.visibility='hidden';
		}
	}		
	var $date = jQuery.noConflict();
	$date(document).ready(function(){
		$date( "#date_day" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		$date( "#date_day_finish" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		$date("#controlDateEnd").click(function(){
			if($date(this).attr("checked")) {
				$date("#date_day_finish").attr("disabled", false);
				$date("#Date_end_hh").attr("disabled", false);
				$date("#Date_end_ii").attr("disabled", false);
				$date("#boxDateEnd").fadeIn();
			}else{
				$date("#boxDateEnd").fadeOut();
				$date("#date_day_finish").attr("disabled", true);
				$date("#Date_end_hh").attr("disabled", true);
				$date("#Date_end_ii").attr("disabled", true);
			}
		});
	});