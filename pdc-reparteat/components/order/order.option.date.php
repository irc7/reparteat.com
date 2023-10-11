<?php if (allowed ($mnu)){ 

	require_once("includes/classes/Image/class.Image.php");
	require_once("includes/classes/Order/class.Order.php");
	require_once("includes/classes/UserWeb/class.UserWeb.php");
	require_once("includes/classes/Supplier/class.Supplier.php");
	require_once("includes/classes/Product/class.Product.php");
	require_once("includes/classes/Supplier/class.Supplier.php");
	
	if (!isset($_GET['filter'])) {
		$filter = "";
	}else {
		$filter = trim($_GET['filter']);
	}
	$now = new DateTime();
	$filterstring = $now->format("d-m-Y");
	
?>	
	<div class='container container-admin darkshaded' style="padding-top:15px;">
		<form id="form-search" name='dropdown' method='get' action='index.php'>
			<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
			<input type='hidden' name='com' value='<?php echo $com; ?>' />
			<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
			<input type='hidden' name='opt' value='<?php echo $opt; ?>' />
			<input type='hidden' name='filter' value='<?php echo $filter; ?>' />
	<?php if($filter == "day"){ 
			if (isset($_GET['filterday'])) {
				$filterstring = $_GET['filterday'];
			}
	?>
			<div class="form-group">
				<label class="label-field white" for="filterday">Selecciona fecha:</label>
				<input class="form-control form-xs" value="<?php echo $filterstring; ?>" name="filterday" id="filterday" readonly="readonly" />
			</div>
	<?php } ?>
		</form>
	</div>
	<div class="separator20"></div>
	<div class='container container-admin'>
<?php 
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']);
		echo "<div class='container container-admin'><div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div></div>";
	}
	
	$supObj = new Supplier();
	$proObj = new Product();
	$orderObj = new Order();
	$userObj = new UserWeb();
	$now = new DateTime();
	if(isset($filterstring) && trim($filterstring) != "") {
		$orders = $orderObj->infoOrderByFilterDay($filterstring, $filter);
		//$methodPay = $orderObj->orderMethodPay($order->IDMETHODPAY);
		//$supplier = $supObj->infoSupplierById($order->IDSUPPLIER);
		//$products = $orderObj->listProductOrder($order->ID);
		if(count($orders) > 0) {
			$cont = 0;
			//pre($orders);
			for($i=0;$i<count($orders);$i++) {
				$product = array();
?>
				<div class="wrap-data-sup">	
					<div class='flex-title title'>Restaurante: <?php echo $orders[$i]["data"]->TITLE; ?> 
						<span class="floatRight title">Total pedidos: <?php echo count($orders[$i]["order"]); ?></span>
					</div>
					<div class="wrap-flex flex-header">
						<div class="item-flex title c-2">Referencia</div>
						<div class="item-flex title c-1">Subtotal</div>
						<div class="item-flex title c-3">Envío</div>
						<div class="item-flex title c-4">Total</div>
					</div>
<?php
					$tSubtotal = 0;
					$tShipping = 0;
					$tCost = 0;
					for($j=0;$j<count($orders[$i]["order"]);$j++) {
?>
						<div class="wrap-flex flex-list<?php if($j == count($orders[$i]["order"])-1){echo " last";} ?>">
							<div class="item-flex textBox c-2"><?php echo $orders[$i]["order"][$j]["data"]->REF; ?></div>
							<div class="item-flex textBox c-1"><?php echo $orders[$i]["order"][$j]["data"]->SUBTOTAL; ?></div>
							<div class="item-flex textBox c-3"><?php echo $orders[$i]["order"][$j]["data"]->SHIPPING; ?></div>
							<div class="item-flex textBox c-4"><?php echo $orders[$i]["order"][$j]["data"]->COST; ?></div>
						</div>
<?php
						$tSubtotal = $tSubtotal + $orders[$i]["order"][$j]["data"]->SUBTOTAL;
						$tShipping = $tShipping + $orders[$i]["order"][$j]["data"]->SHIPPING;
						$tCost = $tCost + $orders[$i]["order"][$j]["data"]->COST;
						for($z=0;$z<count($orders[$i]["order"][$j]["product"]);$z++) {
							$item = $orders[$i]["order"][$j]["product"][$z];
							$enc = false;
							for($x=0;$x<count($product);$x++) {
								if($product[$x]["id"] == $item["id"]) {
									$enc = true;
									break;
								}
							}
							if($enc) {
								$product[$x]["uds"] = $product[$x]["uds"] + $item["uds"];
								$product[$x]["cost"] = $product[$x]["cost"] + $item["cost"];
							}else {
								$product[] = $item;
							}
						}
					}
?>
					<div class="wrap-flex flex-footer">
						<div class="item-flex title c-2">TOTALES</div>
						<div class="item-flex title c-1"><?php echo $tSubtotal; ?> €</div>
						<div class="item-flex title c-3"><?php echo $tShipping; ?> €</div>
						<div class="item-flex title c-4"><?php echo $tCost; ?> €</div>
					</div>
					<div class="wrap-flex flex-header">
						<div class="item-flex title c-1">ID</div>
						<div class="item-flex title c-2">Nombre</div>
						<div class="item-flex title c-3">Uds</div>
						<div class="item-flex title c-4">Cost</div>
					</div>
<?php				
					$product = orderArrayByCamp($product, "uds");
					foreach($product as $p) { 
?>
						<div class="wrap-flex flex-list">
							<div class="item-flex textBox c-1" style="width:10%;"><?php echo $p["id"]; ?></div>
							<div class="item-flex textBox c-2" style="width:50%;"><?php echo $p["title"]; ?></div>
							<div class="item-flex textBox c-3" style="width:20%;"><?php echo $p["uds"]; ?></div>
							<div class="item-flex textBox c-4" style="width:20%;"><?php echo $p["cost"]; ?></div>
						</div>
<?php 				} ?>					
					
				</div>
				<div class="separator20"></div>
<?php			
			}
		}else {
?>
			<div class='container container-admin'>
				<div class="separator1 bgGrayStrong"></div>
				<div class="separator20"></div>
				<div class='cp_info'>
					<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
					<p class="textBox" style="font-size:15px;padding-top:5px;">No hay pedidos registrados para la fecha <?php echo $filterstring; ?></p>
				</div>
			</div>
<?php		
		}
	}
?>	
	</div>
<?php		
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}
?>	