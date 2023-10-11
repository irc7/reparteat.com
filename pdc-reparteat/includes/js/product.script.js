var $prod = jQuery.noConflict();
	$prod(document).ready(function(){
		$prod( "#date_day" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		
		$prod(".deleteImage").click(function(){
			var id = $prod(this).attr("id").split("-");
			$prod("#wrap-image-"+id[2]).fadeOut();
			$prod(this).fadeOut();
			$prod("#separator-img-"+id[2]).fadeOut();
			$prod("#act-img-"+id[2]).val('1');
		});	
		
		//Categoria
		$prod("#addCategory").click(function(){
			$prod("#wrap-addCategory input[type=text]").attr("disabled", false);
			$prod("#wrap-addCategory textarea").attr("disabled", false);
			$prod("#wrap-addCategory input[type=submit]").attr("disabled", false);
			$prod("#wrap-addCategory").fadeIn();
		});		
		$prod(".open-editcat").click(function(){
			$prod(".form-category input[type=text]").attr("disabled", true);
			$prod(".form-category textarea").attr("disabled", true);
			$prod(".form-category input[type=submit]").attr("disabled", true);
			$prod(".form-category").fadeOut();
			
			var id = $prod(this).attr('id').split('-');
			$prod("#editcat-"+id[2]+" input[type=text]").attr("disabled", false);
			$prod("#editcat-"+id[2]+" textarea").attr("disabled", false);
			$prod("#editcat-"+id[2]+" input[type=submit]").attr("disabled", false);
			$prod("#editcat-"+id[2]).fadeIn();
		});		
		
		//Ingredientes
		$prod("#addComponent").click(function(){
			$prod("#wrap-addComponent input").attr("disabled", false);
			$prod("#wrap-addComponent select").attr("disabled", false);
			$prod("#wrap-addComponent").fadeIn();
		});		
		$prod(".open-editcomponent").click(function(){
			$prod(".form-addComponent input").attr("disabled", true);
			$prod(".form-addComponent select").attr("disabled", true);
			$prod(".form-addComponent").fadeOut();
			
			var id = $prod(this).attr('id').split('-');
			$prod("#editcomponent-"+id[2]+" input").attr("disabled", false);
			$prod("#editcomponent-"+id[2]+" select").attr("disabled", false);
			$prod("#editcomponent-"+id[2]).fadeIn();
		});		
			
		//Icon
		$prod("#addIcon").click(function(){
			$prod("#wrap-addIcon input").attr("disabled", false);
			$prod("#wrap-addIcon").fadeIn();
		});		
		$prod(".open-editicon").click(function(){
			$prod(".form-addIcon input").attr("disabled", true);
			$prod(".form-addIcon").fadeOut();
			
			var id = $prod(this).attr('id').split('-');
			$prod("#editicon-"+id[2]+" input").attr("disabled", false);
			$prod("#editicon-"+id[2]).fadeIn();
		});		
		
		//Añadir ingregientes al producto
		$prod("#add-product-com").click(function(){
			var act = 0;
			$prod(".box-product-coms").each(function(){
				if($prod(this).attr("data") == "com-on") {
					act = act+1;
				}
			});
			var on = act+1;
			$prod("#IdCom-"+on).attr("disabled",false);
			$prod("#TypeCom-"+on).attr("disabled",false);
			$prod("#CostCom-"+on).attr("disabled",false);
			$prod("#boxproduct-coms-"+on).attr("data","com-on");
			$prod("#boxproduct-coms-"+on).fadeIn();	
		});
		
		$prod(".deleteCom").click(function(){
			var aux = $prod(this).attr('id').split('-');
			var off = aux[3];
			$prod("#IdCom-"+off).attr("disabled",true);
			$prod("#TypeCom-"+off).attr("disabled",true);
			$prod("#CostCom-"+off).attr("disabled",true);
			$prod("#boxproduct-coms-"+off).attr("data","com-off");
			$prod("#boxproduct-coms-"+off).fadeOut();
		});
		$prod(".deleteComID").click(function(){
			var aux = $prod(this).attr('id').split('-');
			var off = aux[4];
			$prod("#IdCom-id-"+off).attr("disabled",true);
			$prod("#TypeCom-id-"+off).attr("disabled",true);
			$prod("#CostCom-id-"+off).attr("disabled",true);
			$prod("#boxproduct-coms-id-"+off).attr("data","com-off");
			$prod("#boxproduct-coms-id-"+off).fadeOut();
		});
		$prod(".com-type").change(function(){
			var aux = $prod(this).attr("id").split("-");
			var id = aux[1];
			var type = $prod("#TypeCom-"+id+" option:selected").val();
			if(type == "optional") {
				$prod("#TextCostCom-"+id).css("display","none");
				$prod("#CostCom-"+id).fadeIn();
			}else if(type == "basic") {
				$prod("#CostCom-"+id).css("display","none");
				$prod("#TextCostCom-"+id).fadeIn();
			}
		});		
		$prod(".com-name").change(function(){
			var aux = $prod(this).attr("id").split("-");
			var id = aux[1];
			var cost = $prod("#IdCom-"+id+" option:selected").attr("data");
			$prod("#CostCom-"+id).attr("value", cost);
			
		});	
		$prod(".com-type-id").change(function(){
			var aux = $prod(this).attr("id").split("-");
			var id = aux[2];
			var type = $prod("#TypeCom-id-"+id+" option:selected").val();
			if(type == "optional") {
				$prod("#TextCostCom-id-"+id).css("display","none");
				$prod("#CostCom-id-"+id).fadeIn();
			}else if(type == "basic") {
				$prod("#CostCom-id-"+id).css("display","none");
				$prod("#TextCostCom-id-"+id).fadeIn();
			}
		});		
		$prod(".com-name-id").change(function(){
			var aux = $prod(this).attr("id").split("-");
			var id = aux[2];
			var cost = $prod("#IdCom-id-"+id+" option:selected").attr("data");
			$prod("#CostCom-id-"+id).attr("value", cost);
			
		});	
	});
