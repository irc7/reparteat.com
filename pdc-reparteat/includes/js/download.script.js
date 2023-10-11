function view_link_download(a) {
	var id_obj = "content_url"+a;
	var st = document.getElementById(id_obj).style.visibility;
	if(st == "hidden"){
		document.getElementById(id_obj).style.visibility='visible';
	}else{
		document.getElementById(id_obj).style.visibility='hidden';
	}
}
	var $add2 = jQuery.noConflict();
	$add2(document).ready(function(){
		$add2( "#date_day" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		$add2( "#date_day_finish" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		$add2("#controlDateEnd").click(function(){
			if($add2(this).attr("checked")) {
				$add2("#date_day_finish").attr("disabled", false);
				$add2("#Date_end_hh").attr("disabled", false);
				$add2("#Date_end_ii").attr("disabled", false);
				$add2("#boxDateEnd").fadeIn();
			}else{
				$add2("#boxDateEnd").fadeOut();
				$add2("#date_day_finish").attr("disabled", true);
				$add2("#Date_end_hh").attr("disabled", true);
				$add2("#Date_end_ii").attr("disabled", true);
			}
		});
	});
	