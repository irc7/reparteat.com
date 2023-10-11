<div class='separator50 bgGreen'>&nbsp;</div>
<?php 
		include("includes/tpv-redsys/apiRedsys.php");
		$q = "select * from ".preBD."tpv_configuration where ID > 0 and ID <=8";
		$r = checkingQuery($connectBD, $q);
		
		$config = array();
		while($row = mysqli_fetch_object($r)) {
			$config[$row->ID] = $row->CODE;
		}
		


		$miObj = new RedsysAPI;
		$ordObj = new Order();
		$ref = intval($_GET["ref"]);
		
		$order = $ordObj->infoOrderByRef($ref);
	if($order) {
		
		$supObj = new Supplier();
		$supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
	
		$proObj = new Product();
		
		$products = $ordObj->listProductOrder($order->ID);


		// Valores de entrada
		$fuc = $config[1];
		$terminal=$config[4];
		$moneda=$config[3];
		$trans=$config[5];
		$url=DOMAIN."template/modules/tpv/tpv.return.php";
		$urlOKKO = DOMAIN . "pedido-realizado/" . $ref;
		
		$reference=$order->REF;
		$amount=number_format($order->COST, 2, '', '');
		$description = "Pedido " . $order->REF . " en " . $supplierCart->TITLE;
		
		// Se Rellenan los campos
		$miObj->setParameter("DS_MERCHANT_AMOUNT",strval($amount));
		$miObj->setParameter("DS_MERCHANT_ORDER",strval($reference));
		$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
		$miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
		$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
		$miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
		$miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
		$miObj->setParameter("DS_MERCHANT_URLOK",$urlOKKO);		
		$miObj->setParameter("DS_MERCHANT_URLKO",$urlOKKO);
		$miObj->setParameter("DS_MERCHANT_PRODUCTDESCRIPTION",substr($description,0,124));//max 125 caracteres

		//Datos de configuración
		$version="HMAC_SHA256_V1";
		$kc = $config[2];//Clave recuperada de CANALES
		// Se generan los parámetros de la petición
		$request = "";
		$params = $miObj->createMerchantParameters();
		$signature = $miObj->createMerchantSignature($kc);
		
		if($order->STATUS == 1) {
		
	?>
			<!-- Start article section -->
			<div class="container-fluid">
				<section id="ree-order">
					<div class="container">
						<div class="row">

							<div id="wrap-redirect-tpv" class="bgWhite">
								<div class="redirect-tpv bgWhite">
									<h3 class="arial">Redirigiendo a TPV Virtual BBVA</h3>
									<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/loading.gif">
									<form name="frm" id="form-tpv" action="<?php echo $config[8]; ?>" method="POST">
										<input type="hidden" name="Ds_SignatureVersion" value="<?php echo $version; ?>"/>
										<input type="hidden" name="Ds_MerchantParameters" value="<?php echo $params; ?>"/>
										<input type="hidden" name="Ds_Signature" value="<?php echo $signature; ?>"/>
										<input type="submit" class="btn btn-primary arial bgGreen yellow" value="Pinche aquí si la pantalla no se redirige automaticamente">
									</form>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<script type="text/javascript">
				var f = document.getElementById("form-tpv");	
				setTimeout(f.submit(), 2000);
			</script>
	<?php }else{ 
		//el pedido ya se ha procesado
	?>

	<?php } ?>
<?php }else{ 
	//no viene referencia o no existe la referencia
?>

<?php } ?>