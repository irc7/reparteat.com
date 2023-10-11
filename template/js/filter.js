function filter() {

(function () {
   'use strict';

	$("#filter-cat").change(function(){
		var filter = $(this).val();
		if(filter == "all") {
			$(".product-item").fadeOut();
			$(".product-item").fadeIn();
		}else {
			$(".product-item").fadeOut();
			$(".product-item."+filter).fadeIn();
		}
	});
	
	$("#btn-filter-cat").click(function(){
		var filter = [];
		$(".filter-cat-checkbox").each(function(){
			if($(this).is(':checked')) {
				filter.push($(this).val());
			}
		});
		if(filter.length > 0) { 
			$("#filter-cat-all").prop("checked", false);
			$(".product-item").fadeOut();
			$.each(filter, function(i, val) {
				$(".product-item."+val).fadeIn();
			});
		}else {
			$("#filter-cat-all").prop("checked", true);
			$(".product-item").fadeOut();
			$(".product-item").fadeIn();
		}
		$('html, body').animate({
			scrollTop: $("#start-list-product").offset().top-50
		}, 1000);
	});

	$(".filter-cat-button").click(function(){
		var act = $(this).attr("data");
		if(act == "desactive") {
			$(this).attr("data", "active");
		}else {
			$(this).attr("data", "desactive");
		}
		filterProductByCat();
		
		$('html, body').animate({
			scrollTop: $("#start-list-product").offset().top-50
		}, 1000);
	});
	function filterProductByCat() {
		var filter = [];
		$(".filter-cat-button").each(function(){
			var active = $(this).attr("data");
			if(active == "active") {
				filter.push($(this).attr("data-filter"));
			}
		});
		if(filter.length > 0) { 
			$(".product-item").fadeOut();
			$.each(filter, function(i, val) {
				$(".product-item."+val).fadeIn();
			});
		}else {
			$(".product-item").fadeOut();
			$(".product-item").fadeIn();
		}
	}
}());


}
filter();