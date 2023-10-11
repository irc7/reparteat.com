function order() {

(function () {
   'use strict';

    /*====================================
    Añadir al carrito
	$(".btn-action-order").click(function(){
		var statusNext = $(this).attr("data-value");
		var id = $(this).attr("data-order");
		
		changeStatusOrder(statusNext, id);
	});
	
	
	function changeStatusOrder(statusNext, id){
		
		var params = {
				"statusNext" : statusNext,
				"idOrder" : id
		};
		var urlAdd = DomainWebZP+'template/modules/cart/add.php';
		$.ajax({
			data: params,
			url:urlAdd,
			type:'post',
			dataType: "json",
			beforeSend: function() {
				
			}
			
		}).done(function(msg){
			var answer = JSON.parse(JSON.stringify(msg));
			//console.log(answer);
			var totalOrder = 0;
			var totalProduct = 0;
			var htmlList = "";
			if(answer.data.length > 0) {
				htmlList += startHTMLCart();
				for(var i=0;i<answer.data.length;i+=1) {
					totalOrder = parseFloat(totalOrder) + parseFloat(answer.data[i].cost);
					totalProduct = parseInt(totalProduct) + parseInt(answer.data[i].ud);
					htmlList += '<div id="item-cart-'+i+'" class="cart-item">';
						htmlList += '<div class="col-xs-1 no-padding cart-item-ud">';
							htmlList += answer.data[i].ud
						htmlList += '</div>';
						htmlList += '<div class="col-xs-7 cart-item-name ">';
							htmlList += answer.data[i].title;
							if(answer.data[i].comp != "") {
								htmlList += ' <em>+ '+answer.data[i].comp+'</em>';
							}
						htmlList += '</div>';
						htmlList += '<div class="col-xs-2 no-padding cart-item-cost textCenter">';
							htmlList += answer.data[i].cost + ' €';
						htmlList += '</div>';
						htmlList += '<div class="col-xs-2 no-padding cart-item-delete textCenter">';
							htmlList += '<i id="cart-item-delete-'+answer.id+'-'+i+'" class="fa fa-trash grayStrong pointer transition cart-item-delete"></i>';
						htmlList += '</div>';
					htmlList += '</div>';
					htmlList += '<div class="separator10"></div>';
				}
				htmlList += closeHTMLCart(totalOrder, answer.shipping, answer.inTime, answer.id, answer.min);
			}
			$("#mnu-cart .loading-cart").fadeOut();
			$("#mnu-cart .sumary-cart-mnu span").html(totalProduct);
			$("#mnu-cart .sumary-cart-mnu").fadeIn();
			$("#wrap-cart").html(htmlList);
			$("#wrap-cart .loading-cart").fadeOut();
			$("#wrap-cart #box-list-cart").removeClass("oculto");
			$("#wrap-cart #box-list-cart").addClass("visible");
			
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
	function closeHTMLCart(subtotalOrder, shipping, inTime, idSup ,min) {
		
		var htmlList = '<div class="cart-order-total">';
			htmlList += '<div class="separator1 bgGrayStrong"></div>';
			htmlList += '<div class="separator15"></div>';
			htmlList += '<div class="col-xs-4 no-padding">';
				htmlList += '<h5 class="grayStrong textBox">Subtotal</h5>';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-6 textRight grayStrong textBox">';
		var totalOrderString = Math.round(subtotalOrder * 100) / 100; 
				htmlList += totalOrderString.toFixed(2)+' €';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-2 no-padding"></div>';
			htmlList += '<div class="separator5"></div>';
			htmlList += '<div class="col-xs-4 no-padding">';
				htmlList += '<h5 class="grayStrong textBox">Gastos de envio</h5>';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-6 textRight grayStrong textBox">';
		var shippingString = Math.round(shipping * 100) / 100; 
				htmlList += shippingString.toFixed(2) + ' €';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-2 no-padding"></div>';
			htmlList += '<div class="separator5"></div>';
			htmlList += '<div class="separator1 bgGrayStrong"></div>';
			htmlList += '<div class="separator5"></div>';
			htmlList += '<div class="col-xs-4 no-padding">';
				htmlList += '<h4 class="grayStrong textBoxBold">TOTAL</h4>';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-6 textRight grayStrong textBoxBold">';
		var totalOrder = parseFloat(subtotalOrder) + parseFloat(shipping);
		var  totalOrderString = Math.round(totalOrder * 100) / 100; 
				htmlList += totalOrderString.toFixed(2)+' €';
			htmlList += '</div>';
			htmlList += '<div class="col-xs-2 no-padding"></div>';
			htmlList += '</div>';
			htmlList += '</div>';//cierre de box-list-cart
			htmlList += '<div class="separator15"></div>';
			htmlList += '<div class="action-order textRight">';
				htmlList += '<button id="btn-action-order-'+idSup+'" type="button" class="btn btn-action-order transition bgGreen yellow';
				if(inTime == 1 && parseFloat(subtotalOrder) >= parseFloat(min)){
					htmlList += ' active';
				}
				htmlList += '">Tramitar pedido</button>';
			htmlList += '</div></div>';
		return htmlList;
	}
    ======================================*/
}());
}
order();