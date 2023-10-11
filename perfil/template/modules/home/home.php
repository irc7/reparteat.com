<div class="row">

<?php if($_SESSION[nameSessionZP]->IDTYPE == 4){ ?>
	<div class="col-xl-3 col-md-6 mb-4">
		<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=order-follow" alt="Seguimiento de pedidos" title="Seguimiento de pedidos">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
				<div class="col mr-2">
					<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Seguimiento de pedidos</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
							IR
					</div>
				</div>
				<div class="col-auto">
					<i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
				</div>
				</div>
			</div>
		</div>
		</a>
	</div>
	<div class="col-xl-3 col-md-6 mb-4">
		<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=pending" alt="Pedidos pendientes" title="Pedidos pendientes">
		<div class="card border-left-warning shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
				<div class="col mr-2">
					<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pedidos pendientes</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
							IR
					</div>
				</div>
				<div class="col-auto">
					<i class="fas fa-fire-alt fa-2x text-gray-300"></i>
				</div>
				</div>
			</div>
		</div>
		</a>
	</div>

	<div class="col-xl-3 col-md-6 mb-4">
		<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=user" alt="Todos los pedidos" title="Todos los pedidos">
		<div class="card border-left-success shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
				<div class="col mr-2">
					<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Todos los pedidos</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
							IR
					</div>
				</div>
				<div class="col-auto">
					<i class="fas fa-list fa-2x text-gray-300"></i>
				</div>
				</div>
			</div>
		</div>
		</a>
	</div>
<?php }else if($_SESSION[nameSessionZP]->IDTYPE == 3){ ?>
	<div class="col-xl-3 col-md-6 mb-4">
		<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=delivery&rep=<?php echo $_SESSION[nameSessionZP]->ID; ?>&filter=no-shipping" alt="Resumen pedidos pendientes" title="Resumen pedidos pendientes">
		<div class="card border-left-danger shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
				<div class="col mr-2">
					<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pendientes del restaurante</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
							IR
					</div>
				</div>
				<div class="col-auto">
					<i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
				</div>
				</div>
			</div>
		</div>
		</a>
	</div>
<?php /*
	<div class="col-xl-3 col-md-6 mb-4">
		<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=delivery&filter=to-pick-up" alt="Pedidos para recoger" title="Pedidos para recoger">
		<div class="card border-left-warning shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
				<div class="col mr-2">
					<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pedidos para recoger</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
							IR
					</div>
				</div>
				<div class="col-auto">
					<i class="fas fa-boxes fa-2x text-gray-300"></i>
				</div>
				</div>
			</div>
		</div>
		</a>
	</div>
*/ ?>
	<div class="col-xl-3 col-md-6 mb-4">
		<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=delivery&filter=to-deliver" alt="Pedidos para entregar" title="Pedidos para entregar">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
				<div class="col mr-2">
					<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pedidos para entregar</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
							IR
					</div>
				</div>
				<div class="col-auto">
					<i class="fas fa-flag fa-2x text-gray-300"></i>
				</div>
				</div>
			</div>
		</div>
		</a>
	</div>
	<div class="col-xl-3 col-md-6 mb-4">
		<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=delivery" alt="Todos los pedidos" title="Todos los pedidos">
		<div class="card border-left-success shadow h-100 py-2">
			<div class="card-body">
				<div class="row no-gutters align-items-center">
				<div class="col mr-2">
					<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Todos los pedidos</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
							IR
					</div>
				</div>
				<div class="col-auto">
					<i class="fas fa-list fa-2x text-gray-300"></i>
				</div>
				</div>
			</div>
		</div>
		</a>
	</div>
<?php }else if($_SESSION[nameSessionZP]->IDTYPE == 2){ 
		$userObj = new UserWeb();
		$totalSuppliersUser = $userObj->getUserWebSupplier($_SESSION[nameSessionZP]->ID);
		if($totalSuppliersUser > 0) {
			foreach($suppliersUser as $sup) { 
?>
				<div class="col-xl-3 col-md-6 mb-4">
					<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=supplier&sup=<?php echo $sup->ID; ?>&filter=pending" alt="Pedidos pendientes" title="Pedidos pendientes">
					<div class="card border-left-warning shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pedidos pendientes</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									<?php echo $sup->TITLE; ?>
								</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-fire-alt fa-2x text-gray-300"></i>
							</div>
							</div>
						</div>
					</div>
					</a>
				</div>
				<div class="col-xl-3 col-md-6 mb-4">
					<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=supplier&sup=<?php echo $sup->ID; ?>" alt="Todos los pedidos" title="Todos los pedidos">
					<div class="card border-left-success shadow h-100 py-2">
						<div class="card-body">
							<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Todos los pedidos</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">
									<?php echo $sup->TITLE; ?>
								</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-list fa-2x text-gray-300"></i>
							</div>
							</div>
						</div>
					</div>
					</a>
				</div>
			<?php 		}
		}
	} ?>
</div>

           