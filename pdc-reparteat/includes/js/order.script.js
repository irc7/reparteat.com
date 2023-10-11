	var $ord = jQuery.noConflict();
	$ord(document).ready(function(){
		$ord("#btn-change-status").click(function(){
			if($ord("#status").val()>0) {
				if(confirm("Va a cambiar el estado del pedido, sin generar alerta alguna a los usuarios implicados.")) {
					$ord("#changeStatus").submit();
				}
			}else {
				alert("Debe seleccionar un estado");
			}
		});		
		$ord( "#filterday" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		$ord( "#filterday" ).change(function(){
			$ord( "#form-search" ).submit();
			
		});

		$ord( ".btn-add-product-order").click(function(){
			var aux = $ord( this).attr("id").split("-");
			var id = aux[4];
			$ord( "#btn-add-product").attr("disabled",false);
			$ord( "#msg-add-product-order-"+id).fadeIn();
		});
		$ord( "#btn-close-add-order").click(function(){
			$ord( "#btn-add-product").attr("disabled",true);
			$ord( ".comp-input-product").attr("disabled", true);
			$ord( ".msg-alert-order").fadeOut();
		});
		$ord( ".select-add-product").change(function(){
			$ord( ".comp-input-product").attr("disabled", true);
			$ord( ".comp-aux-product").css("display", "none");
			var idProduct = $ord( "#idProduct option:selected").val();
			$ord( "#comp-aux-"+idProduct).fadeIn();
			$ord( ".comp-input-"+idProduct).attr("disabled", false);
		});
		
		$ord( ".btn-delete-product").click(function(){
			var aux = $ord( this).attr("id").split("-");
			var id = aux[3];
			$ord( "#btn-delete-product-order"+id).attr("disabled",false);
			$ord( "#msg-delete-product-"+id).fadeIn();
		});
		$ord( ".btn-close-delete-product").click(function(){
			var aux = $ord( this).attr("id").split("-");
			var id = aux[3];
			$ord( "#btn-delete-product-order-"+id).attr("disabled",true);
			$ord( "#msg-delete-product-"+id).fadeOut();
		});


	});
