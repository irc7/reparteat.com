<?php
	if (!isset($_GET['filterstatus'])) {
		$filterstatus = 0;
		$filterstatusq = "";
	}else {
		if(intval($_GET['filterstatus']) > 0) {
			$filterstatus = $_GET['filterstatus'];
			$filterstatusq = " and STATUS = " .$filterstatus;
		}else{
			$filterstatus = 0;
			$filterstatusq = "";
		}
	}
	if (!isset($_GET['recordsperpage'])) {
		$recordsperpage = 50;
	}else {
		$recordsperpage = $_GET['recordsperpage'];
	}
	if (!isset($_GET['page'])) {
		$page = 1;
		$firstrecord = 0;
	}else {
		$page = $_GET['page'];
		$firstrecord = ($page-1) * $recordsperpage;
	}
	
	if (!isset($_GET['search']) || $_GET['search'] == "") {
		$search = NULL;
		$searchq = "";
	} else {
		$search = $_GET['search'];
		$searchq = " AND REF LIKE '%".$search."%'";
	}
	
	if (!isset($_GET['user'])) {
		$record = NULL;
		$recordq = "";
	}else {
		$record = intval($_GET['id']);
		$recordq = " AND ".preBD."supplier.ID='".$record."'";
		$searchq = "";
	}
	require_once("includes/classes/Order/class.Order.php");
	require_once("includes/classes/Supplier/class.Supplier.php");
	require_once("includes/classes/Zone/class.Zone.php");
	require_once("includes/classes/UserWeb/class.UserWeb.php");
	
	
	$supObj = new Supplier();
	$zoneObj = new Zone();
	$userObj = new UserWeb();
	$orderObj = new Order();
	
	$urlMod = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&filterstatus=".$filterstatus."&recordsperpage=".$recordsperpage."&search=".$search."&page=".$page;
	
	$order = 'DATE_CREATE';
	if(isset($_GET["filter"]) && trim($_GET["filter"]) == "follow") {
		$listOrder = $orderObj->followOrder();
	}else {
		$listOrder = $orderObj->listOrder($filterstatus, $order, $firstrecord, $recordsperpage);
	}
	
	$q = "select count(*) as total from ".preBD."order where true".$filterstatusq.$searchq;
	$r = checkingQuery($connectBD, $q);
	$row = mysqli_fetch_object($r);
	$totalrecods = $row->total;
	
	$totalpages = ceil($totalrecods / $recordsperpage);
?>
	
<?php 
if(count($listOrder) > 0) {
	if(isset($_GET["filter"]) && trim($_GET["filter"]) == "follow") {
		//pre($listOrder);
?>
	<div class='container container-admin'>
		<div class='row'>	
<?php	foreach($listOrder as $row){ ?>			
			<div class='box-follow-order col-xs-12 bgGrayLight'>
				<div class='col-xs-8'>
					<h4><?php echo $row->pedido; ?></h4>
				</div>
				<div class='col-xs-4'>
					<h5 class="text-center bgGrayStrong white" style="padding:10px;"><?php echo $row->estado; ?></h5>
				</div>
				<div class='col-md-12 col-sm-12 col-xs-12'>
					<?php 
						$date = new DateTime($row->fecha_pedido);
						echo $date->format("d-m-Y H:i:s");
					?>
				</div>
				<div class='col-md-6 col-sm-6 col-xs-12'>
					<ul id="follow-order">
						<li><strong>Establecimiento:</strong> <?php echo $row->establecimiento; ?></li>
						<li><strong>Repartidor:</strong> <?php echo $row->repartidor; ?></li>
						<li><strong>Dirección entrega:</strong> <?php echo $row->dir_entrega; ?></li>
					</ul>
				</div>
				<div class='col-md-6 col-sm-6 col-xs-12'>
					<ul id="follow-order">

						<li><strong>Queda cocina:</strong> -<?php echo conversorSegundosHoras($row->queda_cocina*(-1)); ?></li>
						<li><strong>Lleva terminado:</strong> <?php echo conversorSegundosHoras($row->lleva_terminado); ?></li>
						<li><strong>Queda estimación:</strong> <?php echo conversorSegundosHoras($row->queda_estimacion*(-1)); ?></li>
						<li><strong>Comienzo cocina:</strong>
						<?php 
							$date = new DateTime($row->comienzo_cocina);
							echo $date->format("d-m-Y H:i:s");
						?>
						</li>
						<li><strong>Terminado cocina:</strong>
						<?php 
							$date = new DateTime($row->terminado_cocina);
							echo $date->format("d-m-Y H:i:s");
						?>
						</li>
						<li><strong>Entrega:</strong>
						<?php 
							$date = new DateTime($row->estimacion_entrega);
							echo $date->format("d-m-Y H:i:s");
						?>
						</li>
					</ul>
				</div>
			</div>
<?php 	} ?>			
		</div>
	</div>
<?php		
	}else{
?>
	<div class='container container-admin darkshaded'>
		<div class='row'>
			<form name='dropdown' method='get' action='index.php'>
				<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
				<input type='hidden' name='com' value='<?php echo $com; ?>' />
				<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
				<input type='hidden' name='opt' value='<?php echo $opt; ?>' />
				<div class='col-sm-4 col-xs-12 top header-list'>
					<span class=' white'>Mostrar&nbsp;&nbsp;</span>
					<select name='recordsperpage' id='recordsperpage' width='20' onchange='dropdown.submit();'>
						<option value='5'<?php if ($recordsperpage == 5) {echo " selected";} ?>>5</option>
						<option value='10'<?php if ($recordsperpage == 10) {echo " selected";} ?>>10</option>
						<option value='25'<?php if ($recordsperpage == 25) {echo " selected";} ?>>25</option>
						<option value='50'<?php if ($recordsperpage == 50) {echo " selected";} ?>>50</option>
					</select>
					<span class='white'>&nbsp;de <?php echo $totalrecods; ?></span>
				</div>
				<div class='col-sm-4 col-xs-12 top header-list'>
					<input type='text' name='search' id='search' size='20' maxlength='150' value='<?php echo $search; ?>' placeholder="Referencia" />
					<input type='submit' value='Buscar' />
				</div>
				<?php $listStatus = $orderObj->listStatusOrder(); ?>
				<div class='col-sm-4 col-xs-12 top header-list'>
					<span class=' white'>Estados&nbsp;&nbsp;</span>
					<select name='filterstatus' id='filterstatus' style='max-width:200px' onchange='dropdown.submit();'>
						<option value='0'<?php if ($filterstatus == 0) {echo " selected";} ?>>Todos los estados</option>
						<?php foreach($listStatus as $statusBD){ ?>
							<option value='<?php echo $statusBD->ID; ?>'<?php if ($filterstatus == $statusBD->ID) {echo " selected";} ?>><?php echo $statusBD->TITLE; ?></option>
						<?php } ?>
					</select>
				</div>
			</form>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
			<div class='col-sm-2 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>Referencia</div>
			</div>
			<div class='col-sm-4 cp_title top'>
				<div class='bold textLeft'>Cliente</div>
				<div class='bold textLeft'>Proveedor</div>
				<div class='bold textLeft'>Repartidor</div>
			</div>
			<div class='col-sm-3 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>Fecha y Hora</div>
			</div>
			<div class='col-sm-3 cp_title top'>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textLeft'>&nbsp;</div>
				<div class='bold textCenter'>Estado</div>
			</div>
		</div>
	</div>
	<div class="separator30">&nbsp;</div>
	<div class='container container-admin'>
		<div class='row'>
<?php	
			foreach($listOrder as $row){
				$user = $userObj->infoUserWebById($row->IDUSER);
				$repartidor = $userObj->infoUserWebById($row->IDREPARTIDOR);
				$supplier = $supObj->infoSupplierById($row->IDSUPPLIER);
				$timeOrder = $orderObj->infoOrderStatusTime($row->ID);
				
?>
				<div class='col-xs-12 shaded item-list'>
					<div class='col-xs-2'>
						<a class="transition" style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=view&ref=<?php echo $row->REF; ?>'>
							<div class='cp_number bold center m1' style='font-size:14px;'>
								<?php echo $row->REF; ?>
							</div>
						</a>
					</div>
					<div class='col-xs-4'>
						<a class="transition" style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=view&ref=<?php echo $row->REF; ?>'>
							<div class='bold'>
								<h5 style="margin-top:0px;"><?php echo $user->NAME." ".$user->SURNAME; ?></h5>
								<div class="separator5"></div>
								<em><?php echo $supplier->TITLE; ?></em>
								<div class="separator5"></div>
								<em><?php echo $repartidor->NAME." ".$repartidor->SURNAME; ?></em>
							</div>
						</a>
					</div>
					<div class='col-xs-3'>
						<div class='grayNormal'>
							<?php
								$dateCreate = new DateTime($row->DATE_CREATE);
								$dateEnd = new DateTime($timeOrder[0]->DATE_CHANGE);
							?>
							<strong>Inicio:</strong><?php echo $dateCreate->format('d-m-Y H:i:s'); ?>
							<div class="separator5"></div>
							<strong>Último estado:</strong><?php echo $dateEnd->format('d-m-Y H:i:s'); ?>
						</div>
					</div>
					<div class='col-xs-2 textCenter <?php echo $timeOrder[0]->COLOR; ?>'>
						<?php echo $timeOrder[0]->TITLE; ?>
					</div>
					<div class='col-xs-1 textCenter'>
						<?php if($row->FROM_APP == 1) { ?>
							<i class="fa fa-mobile yellow iconBotton" title="Pedido realizado desde la APP" style="cursor:help;"></i>
						
						<?php }else { ?>
							<i class="fa fa-globe green pointer iconBotton" title="Pedido realizado desde la WEB" style="cursor:help;"></i>
							
						<?php } ?>
					</div>
				</div>
				<div class="separator">&nbsp;</div>
	<?php 	} ?>
		</div>
	</div>
<?php
	}
	$previouspage = $page - 1;
	$nextpage = $page + 1;
	$url_pag = "index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&recordsperpage=".$recordsperpage."&search=".$search;
	if ($totalpages > 1) {
?>
		<div class='cp_box dotted cp_height45'>
		<?php if ($page > 1) { ?>
			<div class='cp_table' style='margin-right:3px;'>
				<a href='<?php echo $url_pag."&page=".$previouspage; ?>'>
					<<
				</a>
			</div>
		<?php }
		if ($page > 9) {
	?>
			<div class='cp_table cp_pages center shaded'>
				<a href='<?php echo $url_pag."&page=1"; ?>'>1</a>
			</div>
			<div class='cp_table' style='margin-left:3px;'>...</div>
		<?php }
		for ($i=1; $i < $totalpages + 1; $i++) {
			if ($i > ($page - 9) && $i < ($page + 9)) {
		?>
				<div style='margin-right:3px;' class='cp_table cp_pages center<?php if ($page == $i) {echo" darkshaded";}	else {echo" shaded";}?>'>
					<a href='<?php echo $url_pag."&page=".$i; ?>'<?php if ($page == $i) {echo" style='color: white;'";}?>>	
						<?php echo $i; ?>
					</a>
				</div>
	<?php 	}
		}
	}
	if ($page < ($totalpages - 9)) {
	?>
			<div class='cp_table' style='margin-left:3px;'>...</div>
				<div class='cp_table cp_pages center shaded' style='margin-right:3px;'>
					<a href='<?php echo $url_pag."&page=".$totalpages; ?>'>
						<?php echo $totalpages; ?>
					</a>
				</div>
	<?php 
		}
	if ($page < $totalpages) {
	?>
		<div class='cp_table' style='margin-left:3px;'>
			<a href='<?php echo $url_pag."&page=".$nextpage; ?>'>
				>>
			</a>
		</div>
<?php } ?>
</div>
<?php 
}else{
?>
	<div class='cp_box dotted cp_height45'>
		<h4>No hay pedidos para mostrar</h4>
	</div>
<?php } ?>
