var $z = jQuery.noConflict();
	$z(document).ready(function(){
		$z( "#date_day" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		
		$z( "#date_day_finish" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		
		$z("#controlDateEnd").click(function(){
			if($z(this).attr("checked")) {
				$z("#date_day_finish").attr("disabled", false);
				$z("#Date_end_hh").attr("disabled", false);
				$z("#Date_end_ii").attr("disabled", false);
				$z("#boxDateEnd").fadeIn();
			}else{
				$z("#boxDateEnd").fadeOut();
				$z("#date_day_finish").attr("disabled", true);
				$z("#Date_end_hh").attr("disabled", true);
				$z("#Date_end_ii").attr("disabled", true);
			}
		});
		$z("#deleteFile").click(function(){
			$z("#optFile").val(1);
			showloading(1);
			validate($z("#mainform")); 
			return false;
		});	
		$z("#changeFile").click(function(){
			$z("#optFile").val(2);
			$z("#Url_file").attr("disabled", false);
			$z("#box-change-file").fadeIn();
		});		
	});
	
function optionAction(action) {
	document.getElementById('mainform').action='modules/agenda/edit_event.php?option='+action;
}