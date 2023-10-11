var $sup = jQuery.noConflict();
	$sup(document).ready(function(){
		$sup(".delete-img").click(function(){
			var id = $sup(this).attr("id").split("-");
			$sup("#wrap-"+id[1]).fadeOut();
			$sup(this).fadeOut();
			$sup("#action-"+id[1]).val('1');
		});		
		
		$sup("#addCategory").click(function(){
			$sup("#wrap-addCategory input[type=text]").attr("disabled", false);
			$sup("#wrap-addCategory textarea").attr("disabled", false);
			$sup("#wrap-addCategory input[type=submit]").attr("disabled", false);
			$sup("#wrap-addCategory").fadeIn();
		});		
		$sup(".open-editcat").click(function(){
			$sup(".form-category input[type=text]").attr("disabled", true);
			$sup(".form-category textarea").attr("disabled", true);
			$sup(".form-category input[type=submit]").attr("disabled", true);
			$sup(".form-category").fadeOut();
			
			var id = $sup(this).attr('id').split('-');
			$sup("#editcat-"+id[2]+" input[type=text]").attr("disabled", false);
			$sup("#editcat-"+id[2]+" textarea").attr("disabled", false);
			$sup("#editcat-"+id[2]+" input[type=submit]").attr("disabled", false);
			$sup("#editcat-"+id[2]).fadeIn();
		});		
		
		$sup("#add-time-control").click(function(){
			var act = 0;
			$sup(".box-time-control").each(function(){
				if($sup(this).attr("data") == "timeframe-on") {
					act = act+1;
				}
			});
			var on = act+1;
			$sup("#day-"+on).attr("disabled",false);
			$sup("#start-h-"+on).attr("disabled",false);
			$sup("#start-m-"+on).attr("disabled",false);
			$sup("#finish-h-"+on).attr("disabled",false);
			$sup("#finish-m-"+on).attr("disabled",false);
			$sup("#boxtime-control-"+on).attr("data","timeframe-on");
			$sup("#boxtime-control-"+on).fadeIn();
			
		});
		$sup(".deleteTimeFrame").click(function(){
			var aux = $sup(this).attr('id').split('-');
			var off = aux[3];
			$sup("#day-"+off).attr("disabled",true);
			$sup("#start-h-"+off).attr("disabled",true);
			$sup("#start-m-"+off).attr("disabled",true);
			$sup("#finish-h-"+off).attr("disabled",true);
			$sup("#finish-m-"+off).attr("disabled",true);
			$sup("#boxtime-control-"+off).attr("data","timeframe-off");
			$sup("#boxtime-control-"+off).fadeOut();
		});
		$sup(".deleteTimeFrameID").click(function(){
			var aux = $sup(this).attr('id').split('-');
			var off = aux[4];
			$sup("#day-id-"+off).attr("disabled",true);
			$sup("#start-h-id-"+off).attr("disabled",true);
			$sup("#start-m-id-"+off).attr("disabled",true);
			$sup("#finish-h-id-"+off).attr("disabled",true);
			$sup("#finish-m-id-"+off).attr("disabled",true);
			$sup("#boxtime-control-id-"+off).attr("data","timeframe-off");
			$sup("#boxtime-control-id-"+off).fadeOut();
		});
	});
