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
