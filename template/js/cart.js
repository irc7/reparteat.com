function cart() {

(function () {
   'use strict';

	var saldo = parseFloat($("#valueSaldo").html());
    
	/*====================================
    Aplicar saldo en cuenta
    ======================================*/
	
	$('#Discount').click(function(){
		var pedido = Math.round(parseFloat($("#subTotalOrderSumary").html()) * 100) / 100; ;
		var envio = Math.round(parseFloat($("#envioOrderSumary").html()) * 100) / 100; ;
		var total = Math.round(parseFloat($("#totalOrderSumary").html()) * 100) / 100; ;
		var newsaldo = saldo;
		var discount = 0;
		if($(this).is(":checked")){
			if(saldo>=total){
				newsaldo = saldo - total;
				discount = total;
				total = 0;
			}else if(saldo < total) {
				total = total - newsaldo;
				discount = newsaldo;
				newsaldo = 0;
			}
			if(total == 0) {
				$("#methodpay-1").attr("checked", true);
				$("#methodpay-2").attr("checked", false);
				$("#methodpay-2").attr("disabled", true);
			}
		}else if($(this).is(":not(:checked)")){
		//realizar acciones	
			total = pedido+envio;
			newsaldo = saldo;
			$("#methodpay-2").attr("disabled", false);
		}
		
		$("#discount-sumary-view").html("-"+discount.toFixed(2)+" €");
		$("#totalOrderSumary").html(total.toFixed(2));
		$("#valueSaldo").html(newsaldo.toFixed(2));
		
	});
	/*====================================
    Abrir carrito en movil
    ======================================*/
	$('.open-wrap-cart-mobile').on('click', '.open-cart', function(){
		if($('#wrap-cart').css('display') == "none") {
			$('#wrap-cart').fadeIn();
			$(this).html('<span class="danger">Cerrar resumen de la compra</span> <i class="fa fa-close icon-view-cart danger">');
		}else {
			$(this).html('Ver resumen de la compra <i class="fa fa-shopping-basket icon-view-cart">');
			$('#wrap-cart').fadeOut();
		}
	});
	/*====================================
    Añadir al carrito
    ======================================*/
	$(".add-to-cart-list").click(function(){
		var aux = $(this).attr("id").split('-');
		var id = aux['3'];
		
		var idP = $('#idProduct-'+id).val();
		var idS = $('#idSupplier-'+id).val();
		var ud = $('#totalPro-'+id).val();
		
		var comps = new Array();
		var costComps = new Array();
		addToCart('add', idP, idS, comps, costComps, ud);
	});
	
	$(".add-to-cart-product").click(function(){
		var aux = $(this).attr("id").split('-');
		var id = aux['4'];
		
		var idP = $('#idProduct-'+id).val();
		var idS = $('#idSupplier-'+id).val();
		var ud = $('#totalPro-'+id).val();
		
		var comps = new Array();
		var costComps = new Array();
		
		$('.addComCart-'+id+':checked').each(function() {
			comps.push($(this).val());
			var idCom = $(this).val();
			costComps.push($("#CostCom-"+idCom).val());
		});
		console.log(costComps);
		addToCart('add',idP, idS, comps, costComps, ud);
	});
    /*====================================
    Editar carrito Header
    ======================================*/

	$('#header-sumary-cart').on('click', '.cart-item-delete', function(){
		if($(this).attr("id") != null) {
			
			var au = $(this).attr("id").split('-');
			
			var idS = au['3'];
			var idP = au['4'];
			var ud = 0;
			var comps = new Array();
			var costComps = new Array();
			
			addToCart('delete',idP, idS, comps, costComps, ud, "header");
		}
	});
	$('#header-sumary-cart').on('click', '.cart-item-udless', function(){
		if($(this).attr("id") != null) {
			
			var au = $(this).attr("id").split('-');
			
			var idS = au['3'];
			var idP = au['4'];
			var ud = parseInt($(this).attr("data-ud"));
			var comps = new Array();
			var costComps = new Array();
			
			addToCart('edit',idP, idS, comps, costComps, ud, "header");
		}
	});
	$('#header-sumary-cart').on('click', '.cart-item-udmore', function(){
		if($(this).attr("id") != null) {
			
			var au = $(this).attr("id").split('-');
			
			var idS = au['3'];
			var idP = au['4'];
			var ud = parseInt($(this).attr("data-ud"));
			var comps = new Array();
			var costComps = new Array();
			
			addToCart('edit',idP, idS, comps, costComps, ud, "header");
		}
	});
	/*====================================
    Editar carrito columna
    ======================================*/
	$('#wrap-cart').on('click', '.cart-item-delete', function(){
		if($(this).attr("id") != null) {
			
			var au = $(this).attr("id").split('-');
			
			var idS = au['3'];
			var idP = au['4'];
			var ud = 0;
			var comps = new Array();
			var costComps = new Array();
			
			addToCart('delete',idP, idS, comps, costComps, ud, "column");
		}
	});
	$('#wrap-cart').on('click', '.cart-item-udmore', function(){
		if($(this).attr("id") != null) {
			
			var au = $(this).attr("id").split('-');
			
			var idS = au['3'];
			var idP = au['4'];
			var ud = parseInt($(this).attr("data-ud"));
			var comps = new Array();
			var costComps = new Array();
			
			addToCart('edit',idP, idS, comps, costComps, ud, "column");
		}
	});
	$('#wrap-cart').on('click', '.cart-item-udless', function(){
		if($(this).attr("id") != null) {
			
			var au = $(this).attr("id").split('-');
			
			var idS = au['3'];
			var idP = au['4'];
			var ud = parseInt($(this).attr("data-ud"));
			var comps = new Array();
			var costComps = new Array();
			
			addToCart('edit',idP, idS, comps, costComps, ud, "column");
		}
	});
	
	$('#header-sumary-cart').on('click', '.btn-action-order.active', function(){
		var au = $(this).attr("id").split('-');
		var idS = au['3'];
		window.location.href=DomainWeb+"resumen-pedido/"+idS;
	});
	$('#wrap-cart').on('click', '.btn-action-order.active', function(){
		var au = $(this).attr("id").split('-');
		var idS = au['3'];
		window.location.href=DomainWeb+"resumen-pedido/"+idS;
	});
	function addToCart(action, idProduct, idSupplier, comps, costComps, ud, ubicationAction){
	/**carrito header */
		if(ubicationAction != "header") {
			$("#header-sumary-cart").removeClass("open");
			$("#header-sumary-cart").addClass("close");
			$("#header-sumary-cart").fadeOut();
		}
	/*icono header */
		$("#mnu-cart .sumary-cart-mnu").fadeOut();
		$("#mnu-cart .loading-cart").fadeIn();
	/*carrito columna*/	
		$("#wrap-cart #box-list-cart").removeClass("visible");
		$("#wrap-cart #box-list-cart").addClass("oculto");
		$("#wrap-cart .loading-cart").fadeIn();
		
		var compString = "";
		for(var i=0;i<comps.length;i+=1) {
			compString+=comps[i];
			if(i<comps.length-1) {
				compString+='-';
			}
		}
		var costCompString = "";
		for(var i=0;i<costComps.length;i+=1) {
			costCompString+=costComps[i];
			if(i<costComps.length-1) {
				costCompString+='-';
			}
		}

		var params = {
				"action" : action,
				"idProduct" : idProduct,
				"idSupplier" : idSupplier,
				"addCom" : compString,
				"costCom" : costCompString,
				"totalPro" : ud
		};
		if (action == "delete") {
			var urlAdd = DomainWeb+'template/modules/cart/delete.php';
		}else if (action == "edit") {
			var urlAdd = DomainWeb+'template/modules/cart/edit.php';
		}else{
			var urlAdd = DomainWeb+'template/modules/cart/add.php';
		}
		$.ajax({
			data: params,
			url:urlAdd,
			type:'post',
			dataType: "json",
			beforeSend: function() {
				
			}
			
		}).done(function(msg){
			var answer = JSON.parse(JSON.stringify(msg));
			
			var totalOrder = 0;
			var totalProduct = 0;
			var htmlList = "";
			var htmlListH = "";
			if(answer.data.length > 0) {
				htmlList += startHTMLCart();
				for(var i=0;i<answer.data.length;i+=1) {
					totalOrder = parseFloat(totalOrder) + parseFloat(answer.data[i].cost);
					totalProduct = parseInt(totalProduct) + parseInt(answer.data[i].ud);
					var udless = parseInt(answer.data[i].ud) - 1;
					var udmore = parseInt(answer.data[i].ud) + 1;
					htmlList += '<div id="item-cart-'+i+'" class="cart-item">';
						htmlList += '<div class="col-xs-3 no-padding cart-item-ud">';
							htmlList += '<i id="cart-item-udless-'+answer.id+'-'+i+'" data-ud="'+udless+'" class="fa fa-minus yellow pointer cart-item-udless"></i>';
							htmlList += '<span id="cart-item-ud-'+answer.id+'-'+i+'">'+answer.data[i].ud+'</span>';
							htmlList += '<i id="cart-item-udmore-'+answer.id+'-'+i+'" data-ud="'+udmore+'" class="fa fa-plus yellow pointer cart-item-udmore"></i>';
						htmlList += '</div>';
						htmlList += '<div class="col-xs-6 cart-item-name ">';
							htmlList += answer.data[i].title;
							if(answer.data[i].comp != "") {
								htmlList += ' <em>+ '+answer.data[i].comp+'</em>';
							}
						htmlList += '</div>';
						htmlList += '<div class="col-xs-2 no-padding cart-item-cost textCenter">';
							htmlList += answer.data[i].cost + ' €';
						htmlList += '</div>';
						htmlList += '<div class="col-xs-1 no-padding cart-item-delete textRight">';
							htmlList += '<i id="cart-item-delete-'+answer.id+'-'+i+'" class="fa fa-trash grayStrong pointer cart-item-delete"></i>';
						htmlList += '</div>';
					htmlList += '</div>';
					htmlList += '<div class="separator1"></div>';
				}
				htmlList += closeHTMLCart(totalOrder, answer.shipping, answer.inTime, answer.id, answer.min, answer.status);
				htmlListH = htmlList;
			} else {
				htmlListH = "<h5 class='textCenter textBox green'><i class='fa fa-info-circle iconBig'></i><div class='separator10'></div>No tiene ningún producto en este carrito</h5>";
			}
			$("#mnu-cart .loading-cart").fadeOut();
			$("#mnu-cart .sumary-cart-mnu span").html(totalProduct);
			$("#mnu-cart .sumary-cart-mnu").fadeIn();
			$("#wrap-header-cart").html(htmlListH);
			$("#wrap-cart").html(htmlList);
			$("#wrap-cart .loading-cart").fadeOut();
			
			$("#wrap-cart #box-list-cart").removeClass("oculto");
			$("#wrap-cart #box-list-cart").addClass("visible");
			var widthWindows = $(window).width();
			if(widthWindows < 768) {
				if(answer.data.length > 0) {
					$('.open-wrap-cart-mobile h4').html('Cerrar resumen de la compra <i class="fa fa-close icon-view-cart"></i>');
					$('#wrap-cart').fadeIn();
					$('.open-wrap-cart-mobile h4').addClass('open-cart');
				}else {
					$('#wrap-cart').fadeOut();
					$('.open-wrap-cart-mobile h4').html('Ningún producto en la cesta <i class="fa fa-shopping-basket icon-view-cart"></i>');
					$('.open-wrap-cart-mobile h4').removeClass('open-cart');
				}
			}
		}).fail(function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus + "//"+errorThrown);
		});
		
	}
	function startHTMLCart() {
		var htmlList = '<div class="list-cart">';
				htmlList += '<h3 class="arial green">Resumen del carrito</h3>';
				htmlList += '<div class="loading-cart">';
					htmlList += '<img class="img-responsive" src="'+DomainWeb+'template/images/loading.gif" />';
				htmlList += '</div>';
				htmlList += '<div id="box-list-cart">';
		return htmlList;
	}
	function closeHTMLCart(subtotalOrder, shipping, inTime, idSup, min, status) {
		
		var htmlList = '<div class="separator10"></div>';
			htmlList += '<div class="cart-order-total">';
			htmlList += '<div class="separator1 bgGrayStrong"></div>';
			htmlList += '<div class="separator15"></div>';
/*
			htmlList += '<div class="col-xs-5 no-padding">';
				htmlList += '<h5 class="grayStrong textBox">Subtotal</h5>';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-6 textRight grayStrong textBox"><h5 class="grayStrong textBox">';
		var totalOrderString = Math.round(subtotalOrder * 100) / 100; 
				htmlList += totalOrderString.toFixed(2)+' €';
			htmlList += '</h5></div>';
			htmlList += '<div class="col-xs-1 no-padding"></div>';
			htmlList += '<div class="separator1"></div>';
			htmlList += '<div class="col-xs-5 no-padding">';
				htmlList += '<h5 class="grayStrong textBox">Gastos de envio</h5>';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-6 textRight grayStrong textBox"><h5 class="grayStrong textBox">';
		var shippingString = Math.round(shipping * 100) / 100; 
				htmlList += shippingString.toFixed(2) + ' €';
			htmlList += '</h5></div>';
			htmlList += '<div class="col-xs-1 no-padding"></div>';
			htmlList += '<div class="separator5"></div>';
			htmlList += '<div class="separator1 bgGrayStrong"></div>';
			htmlList += '<div class="separator5"></div>';
*/
			htmlList += '<div class="col-xs-5 no-padding">';
				htmlList += '<h4 class="green textBoxBold total-cart-text">TOTAL</h4>';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-6 textRight green textBoxBold total-cart-num"><h4 class="green textBoxBold total-cart-text">';
		var totalOrder = parseFloat(subtotalOrder) + parseFloat(shipping);
		var  totalOrderString = Math.round(totalOrder * 100) / 100; 
				htmlList += totalOrderString.toFixed(2)+' €';
			htmlList += '</h4></div>';
			htmlList += '<div class="col-xs-1 no-padding"></div>';
			htmlList += '</div>';
			htmlList += '</div>';//cierre de box-list-cart
			if(parseFloat(subtotalOrder) < parseFloat(min)){
				htmlList += '<div class="separator15"></div>';
				htmlList += '<div class="alert-order-min textRight">';
				var restMin = parseFloat(min) - parseFloat(subtotalOrder);
				htmlList += '<i class="fa fa-exclamation-triangle"></i> Te quedan ' + restMin.toFixed(2) + '€ para completar el pedido mínimo';
				htmlList += '</div>';
			}
			htmlList += '<div class="separator15"></div>';
			htmlList += '<div class="action-order textRight">';
				htmlList += '<button id="btn-action-order-'+idSup+'" type="button" class="btn btn-action-order transition bgGreen yellow';
				if(inTime == 1 && parseFloat(subtotalOrder) >= parseFloat(min) && status == 1){
					htmlList += ' active';
				}
				htmlList += '">Tramitar pedido</button>';
			htmlList += '</div></div>';
		return htmlList;
	}
}());
}
cart();