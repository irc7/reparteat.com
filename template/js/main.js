function main() {

(function () {
   'use strict';

	$("#popup-btn").click(function(){
		$("#wrap-popup").fadeOut();
	});
	$("#btn-close-msg-alert").click(function(){
		$("#msg-alert").fadeOut();
	});
	
	
	
	$("#form-order").validate({
		rules: {
			address:{required:true},
			methodpay:{required:true}
		},
		messages: {
			address:{required:"<i class='fa fa-exclamation-triangle red'></i>&nbsp;Debe seleccionar una dirección de envío"},
			methodpay:{required:"<i class='fa fa-exclamation-triangle red'></i>&nbsp;Debe seleccionar un método de pago"}
		},
		errorPlacement: function(error, element) 
        {
            if (element.is(":radio")) {
                error.appendTo( element.parents('.step-order') );
            }else { // This is the default behavior 
                error.insertAfter(element);
            }
         }
     });
	
	 $("#btn-cart-open").click(function(){
		$("#header-sumary-cart").slideDown(500, function(){
			 $(this).removeClass("close-cart");
			 $(this).addClass("open-cart");

		 });
	 });
	 $("#btn-cart-close").click(function(){
		$("#header-sumary-cart").slideUp(500, function(){
			$(this).removeClass("close-cart");
			$(this).addClass("open-cart");
		});
	 });
	 
	$("input[name=address]").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[1];
		
		$(".address-order.bgGrayLight").removeClass("bgGrayLight");
		$("#address-order-"+id).addClass("bgGrayLight");
	}); 
	
	$("#points-deliver").click(function(){
		if($("#points-deliver").is(':checked')) {
			$("#title-points-deliver").removeClass("no-checked");
			$("#title-points-deliver").addClass("checked");
			$("#wrap-points-deliver").fadeIn();
			$("#wrap-points-deliver input[name='address']:first").prop('checked', true);
			var aux = $("#wrap-points-deliver input[name='address']:first").attr("id").split("-");
			var id = aux[1];
			
			$(".address-order.bgGrayLight").removeClass("bgGrayLight");
			$("#address-order-"+id).addClass("bgGrayLight");
		}else {
			$("#title-points-deliver").removeClass("checked");
			$("#title-points-deliver").addClass("no-checked");
			$("#wrap-points-deliver").fadeOut();
			
			$("#wrap-my-directions input[name='address']:first").prop('checked', true);
			var aux = $("#wrap-my-directions input[name='address']:first").attr("id").split("-");
			var id = aux[1];
			
			$(".address-order.bgGrayLight").removeClass("bgGrayLight");
			$("#address-order-"+id).addClass("bgGrayLight");
		}
	});
	$('#search-home').submit(function (event) {
		var valorZone = $('#search-zone').val();
		if (valorZone === '0') {
			event.preventDefault();
			$('#error-search-zone').fadeIn();
		}else {
			$('#error-search-zone').fadeOut();
		}
	});
}());


}
main();