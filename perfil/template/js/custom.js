(function($) {
  "use strict"; // Start of use strict
	$("#btn-open-logout").click(function(){
		$("#confirmLogout").fadeIn();
	});
	$("#btn-close-logout").click(function(){
		$("#confirmLogout").fadeOut();
	});
	
	$("#btn-close-msg-alert").click(function(){
		$("#msg-alert").fadeOut();
	});
	
	$(".btn-close-alert-order").click(function(){
		$(".msg-alert-order").fadeOut();
	});
	$(".btn-action-order-cangerep").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[4];
		$("#msg-alert-order-cangerep-"+id).fadeIn();
	});
	
	
	$(".btn-action-open").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[3];
		$("#msg-alert-action-"+id).fadeIn();
	});
	
	$(".btn-close-alert-action").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[4];
		$("#msg-alert-action-"+id).fadeOut();
	});
	
	
	$(".btn-action-order-accept").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[4];
		$("#msg-alert-order-accept-"+id).fadeIn();
	});
	$(".btn-action-order-return").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[4];
		$("#msg-alert-order-return-"+id).fadeIn();
	});
	
	$("#btn-action-order-cancel").click(function(){
		$("#msg-alert-order-cancel").fadeIn();
	});
	$(".btn-action-rep-accept").click(function(){
		var id = $(this).attr("data-order");
		$("#msg-alert-rep-accept-"+id).fadeIn();
	});
	
	$(".btn-add-product-order").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[4];
		$("#btn-add-product").attr("disabled",false);
		$("#msg-add-product-order-"+id).fadeIn();
	});
	$("#btn-close-add-order").click(function(){
		$("#btn-add-product").attr("disabled",true);
		$(".comp-input-product").attr("disabled", true);
		$(".msg-alert-order").fadeOut();
	});
	$(".select-add-product").change(function(){
		$(".comp-input-product").attr("disabled", true);
		$(".comp-aux-product").css("display", "none");
		var idProduct = $("#idProduct option:selected").val();
		$("#comp-aux-"+idProduct).fadeIn();
		$(".comp-input-"+idProduct).attr("disabled", false);
	});
	
	$(".btn-delete-product-order").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[4];
		$("#btn-delete-product-"+id).attr("disabled",false);
		$("#msg-delete-product-order-"+id).fadeIn();
	});
	$(".btn-close-delete-product-order").click(function(){
		var aux = $(this).attr("id").split("-");
		var id = aux[4];
		$("#btn-delete-product-"+id).attr("disabled",true);
		$("#msg-delete-product-order-"+id).fadeOut();
	});

	$(".delete-img").click(function(){
		var id = $(this).attr("id").split("-");
		$("#wrap-"+id[1]).fadeOut();
		$(this).fadeOut();
		$("#action-"+id[1]).val('1');
	});	
	$("#changePass").click(function(){
		$(this).fadeOut();
		var $log = $("#Email").attr("disabled", false);
		var $log = $("#Pass").attr("disabled", false);
		var $log = $("#Pass").val("");
		var $log = $("#PassRepeat").attr("disabled", false);
		var $log = $("#PassRepeat").val("");
		$(".edit-password").fadeIn();
	});	
	$("#addAddress").click(function(){
		$(this).fadeOut();
		var $log = $("#Street-0").attr("disabled", false);
		var $log = $("#Zone-0").attr("disabled", false);
		$("#user-address-0").fadeIn();
	});	
	
	$("#add-time-control").click(function(){
			var act = 0;
			$(".box-time-control").each(function(){
				if($(this).attr("data") == "timeframe-on") {
					act = act+1;
				}
			});
			var on = act+1;
			$("#day-"+on).attr("disabled",false);
			$("#start-h-"+on).attr("disabled",false);
			$("#start-m-"+on).attr("disabled",false);
			$("#finish-h-"+on).attr("disabled",false);
			$("#finish-m-"+on).attr("disabled",false);
			$("#boxtime-control-"+on).attr("data","timeframe-on");
			$("#boxtime-control-"+on).fadeIn();
			
		});
		$(".deleteTimeFrame").click(function(){
			var aux = $(this).attr('id').split('-');
			var off = aux[3];
			$("#day-"+off).attr("disabled",true);
			$("#start-h-"+off).attr("disabled",true);
			$("#start-m-"+off).attr("disabled",true);
			$("#finish-h-"+off).attr("disabled",true);
			$("#finish-m-"+off).attr("disabled",true);
			$("#boxtime-control-"+off).attr("data","timeframe-off");
			$("#boxtime-control-"+off).fadeOut();
		});
		$(".deleteTimeFrameID").click(function(){
			var aux = $(this).attr('id').split('-');
			var off = aux[4];
			$("#day-id-"+off).attr("disabled",true);
			$("#start-h-id-"+off).attr("disabled",true);
			$("#start-m-id-"+off).attr("disabled",true);
			$("#finish-h-id-"+off).attr("disabled",true);
			$("#finish-m-id-"+off).attr("disabled",true);
			$("#boxtime-control-id-"+off).attr("data","timeframe-off");
			$("#boxtime-control-id-"+off).fadeOut();
		});
		
		
  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });
//Product
  //Añadir ingregientes al producto
  $("#add-product-com").click(function(){
	var act = 0;
	$(".box-product-coms").each(function(){
		if($(this).attr("data") == "com-on") {
			act = act+1;
		}
	});
	var on = act+1;
	$("#IdCom-"+on).attr("disabled",false);
	$("#TypeCom-"+on).attr("disabled",false);
	$("#CostCom-"+on).attr("disabled",false);
	$("#boxproduct-coms-"+on).attr("data","com-on");
	$("#boxproduct-coms-"+on).fadeIn();
	
});

$(".deleteCom").click(function(){
	var aux = $(this).attr('id').split('-');
	var off = aux[3];
	$("#IdCom-"+off).attr("disabled",true);
	$("#TypeCom-"+off).attr("disabled",true);
	$("#CostCom-"+off).attr("disabled",true);
	$("#boxproduct-coms-"+off).attr("data","com-off");
	$("#boxproduct-coms-"+off).fadeOut();
});
$(".deleteComID").click(function(){
	var aux = $(this).attr('id').split('-');
	var off = aux[4];
	$("#IdCom-id-"+off).attr("disabled",true);
	$("#TypeCom-id-"+off).attr("disabled",true);
	$("#CostCom-id-"+off).attr("disabled",true);
	$("#boxproduct-coms-id-"+off).attr("data","com-off");
	$("#boxproduct-coms-id-"+off).fadeOut();
});
$(".com-type").change(function(){
	var aux = $(this).attr("id").split("-");
	var id = aux[1];
	var type = $("#TypeCom-"+id+" option:selected").val();
	if(type == "optional") {
		$("#TextCostCom-"+id).css("display","none");
		$("#CostCom-"+id).fadeIn();
	}else if(type == "basic") {
		$("#CostCom-"+id).css("display","none");
		$("#TextCostCom-"+id).fadeIn();
	}
});		
$(".com-name").change(function(){
	var aux = $(this).attr("id").split("-");
	var id = aux[1];
	var cost = $("#IdCom-"+id+" option:selected").attr("data");
	$("#CostCom-"+id).attr("value", cost);
	
});	
$(".com-type-id").change(function(){
	var aux = $(this).attr("id").split("-");
	var id = aux[2];
	var type = $("#TypeCom-id-"+id+" option:selected").val();
	if(type == "optional") {
		$("#TextCostCom-id-"+id).css("display","none");
		$("#CostCom-id-"+id).fadeIn();
	}else if(type == "basic") {
		$("#CostCom-id-"+id).css("display","none");
		$("#TextCostCom-id-"+id).fadeIn();
	}
});		
$(".com-name-id").change(function(){
	var aux = $(this).attr("id").split("-");
	var id = aux[2];
	var cost = $("#IdCom-id-"+id+" option:selected").attr("data");
	$("#CostCom-id-"+id).attr("value", cost);
	
});	
$(".deleteImage").click(function(){
	var id = $(this).attr("id").split("-");
	$("#wrap-image-"+id[2]).fadeOut();
	$(this).fadeOut();
	$("#separator-img-"+id[2]).fadeOut();
	$("#act-img-"+id[2]).val('1');
});	

$("#filter").change(function(){
	$("#form-filter-day").submit();
});
$(".operator-report").change(function(){
	var aux = $(this).attr("id").split("-");
	var id = aux[1];
	var payCash = parseFloat($("#payCash-"+id).val());
	var payTPV = parseFloat($("#payTPV-"+id).val());
	var salaryDay = parseFloat($("#salaryDay-"+id).val());
	var salaryNight = parseFloat($("#salaryNight-"+id).val());
	var cost = parseFloat($("#cost-"+id).val());
	
	var totalString = payCash - salaryDay - salaryNight - cost;
	var total = Math.round(totalString * 100) / 100; 
	
	$("#total-report-"+id).css('display','none');
	$("#total-report-"+id).html(total.toFixed(2)+" &euro;");
	$("#total-report-"+id).fadeIn();
	$("#total-"+id).val(total.toFixed(2));

});

	$('.sortable').sortable({
	   update: function (event, ui) {
		   $(this).children().each(function (index) {
				if ($(this).attr('data-position') != (index+1)) {
					$(this).attr('data-position', (index+1)).addClass('updated');
				}
		   });

		   guardandoPosiciones();
	   }
	});

})(jQuery); // End of use strict
