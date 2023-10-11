<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

	<!-- Sidebar - Brand -->
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo DOMAINZP; ?>">
		<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/logo_white.png">
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider my-0">

	
	<!-- Nav Item - Dashboard -->
	<li class="nav-item active">
		<a class="nav-link" href="<?php echo DOMAIN; ?>" title="Volver a RepartEat.com">
			<i class="fas fa-fw fa-arrow-alt-circle-left"></i>
			<span>Volver a RepartEat.com</span>
		</a>
	</li>
	<hr class="sidebar-divider my-0">
	<!-- Nav Item - Dashboard -->
	<li class="nav-item active">
		<a class="nav-link" href="<?php echo DOMAINZP; ?>" title="Inicio Area cliente">
			<i class="fas fa-fw fa-home"></i>
			<span>Inicio area cliente</span>
		</a>
	</li>
<?php 
	if($_SESSION[nameSessionZP]->IDTYPE == 2){ //Proveedor
		$userObj = new UserWeb();
		$totalSuppliersUser = $userObj->getUserWebSupplier($_SESSION[nameSessionZP]->ID);
		
		if($totalSuppliersUser > 0) {
?>
			<hr class="sidebar-divider">
			<div class="sidebar-heading">
				Restaurantes
			</div>
<?php			
			$suppliersUser = $userObj->getUserWebInfoSupplier($_SESSION[nameSessionZP]->ID);
			foreach($suppliersUser as $sup) {
?>
				<li class="nav-item">
					<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#supplier<?php echo $sup->ID; ?>" aria-expanded="true" aria-controls="supplier<?php echo $sup->ID; ?>">
						<i class="fas fa-fw fa-home"></i>
						<span><?php echo $sup->TITLE; ?></span>
					</a>
					<div id="supplier<?php echo $sup->ID; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
						<div class="bg-white py-2 collapse-inner rounded">
							<h6 class="collapse-header">Acciones:</h6>
							<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=supplier&sup=<?php echo $sup->ID; ?>&filter=pending">Pedidos pendientes</a>
							<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=supplier&sup=<?php echo $sup->ID; ?>">Todos los pedidos</a>
							<!--<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=statistics&mod=statistics&tpl=day&sup=<?php echo $sup->ID; ?>">Resumen de pedidos</a>-->
							<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=statistics&mod=statistics&tpl=day&sup=<?php echo $sup->ID; ?>&filter=sumary">Resumen de pedidos</a>
							<h6 class="collapse-header">Configuración:</h6>
							<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=supplier&mod=supplier&tpl=profile&sup=<?php echo $sup->ID; ?>">Perfil restaurante</a>
							<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=product&mod=product&tpl=list&sup=<?php echo $sup->ID; ?>">Productos</a>
						</div>
					</div>
				</li>
<?php 	
			}
		}
	}else if ($_SESSION[nameSessionZP]->IDTYPE == 5){//Responsable de zona
		$zoneObj = new Zone();
		$zoneUser = $zoneObj->zonesByUser($_SESSION[nameSessionZP]->ID);
		foreach($zoneUser as $zone) {
?>
			<li class="nav-item">
				<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#zone<?php echo $zone->ID; ?>" aria-expanded="true" aria-controls="zone<?php echo $zone->ID; ?>">
					<i class="fas fa-fw fa-map-pin"></i>
					<span><?php echo $zone->CP; ?> - <?php echo $zone->CITY; ?></span>
				</a>
				<div id="zone<?php echo $zone->ID; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
						<h6 class="collapse-header">Pedidos:</h6>
						<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=zone&z=<?php echo $zone->ID; ?>&filter=follow">Seguimiento</a>
						<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=zone&z=<?php echo $zone->ID; ?>&filter=all">Todos los pedidos</a>
						<h6 class="collapse-header">Estadísticas:</h6>
						<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=statistics&mod=statistics&tpl=day&z=<?php echo $zone->ID; ?>">Resumen diario</a>
						<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=report&mod=report&tpl=sumary&z=<?php echo $zone->ID; ?>">Formulario repartidores</a>
						<h6 class="collapse-header">Configuración:</h6>
						<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=zone&mod=zone&tpl=config&z=<?php echo $zone->ID; ?>">Límites de zona</a>
						<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=zone&mod=zone&tpl=supplier&z=<?php echo $zone->ID; ?>">Restaurantes</a>
					</div>
				</div>
			</li>
<?php 	
		}
	}else if ($_SESSION[nameSessionZP]->IDTYPE == 3){//Repartidor
?>
		<hr class="sidebar-divider">
		<div class="sidebar-heading">
			Pedidos
		</div>
		<li class="nav-item">
			<a class="nav-link" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=delivery&rep=<?php echo $_SESSION[nameSessionZP]->ID; ?>&filter=no-shipping">
				<i class="fas fa-fw fa-utensils"></i>
				<span>Pendientes del restaurante</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=delivery&filter=to-deliver">
				<i class="fas fa-fw fa-clock"></i>
				<span>Pedidos pendientes</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#mis-pedidos-rep" aria-expanded="true" aria-controls="mis-pedidos-rep">
				<i class="fas fa-fw fa-list"></i>
				<span>Mis pedidos</span>
			</a>
			<div id="mis-pedidos-rep" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<h6 class="collapse-header">Acciones:</h6>
					<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=delivery&filter=sumary">Resumen por dias</a>
					<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=delivery">Todos los pedidos</a>
					<h6 class="collapse-header"></h6>
					<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=report&mod=report&tpl=sumary">Formularios</a>
				</div>
			</div>
		</li>
<?php		
	}else if ($_SESSION[nameSessionZP]->IDTYPE == 4){//cliente
?>
		<hr class="sidebar-divider">
		<div class="sidebar-heading">
			Pedidos
		</div>
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#mis-pedidos-user" aria-expanded="true" aria-controls="mis-pedidos-user">
				<i class="fas fa-fw fa-list"></i>
				<span>Mis pedidos</span>
			</a>
			<div id="mis-pedidos-user" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<h6 class="collapse-header">Acciones:</h6>
					<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=order-follow">Seguimiento de pedidos</a>
					<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=user&filter=pending">Pedidos pendientes</a>
					<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=user&filter=finish">Pedidos finalizados</a>
					<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=order&mod=order&tpl=user">Todos mis pedidos</a>
				</div>
			</div>
		</li>
<?php
	} 
?>
	<hr class="sidebar-divider">
	<div class="sidebar-heading">
		Datos personales
	</div>
	<li class="nav-item">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#profile" aria-expanded="true" aria-controls="profile">
			<i class="fas fa-fw fa-cog"></i>
			<span>Mi perfil</span>
		</a>
		<div id="profile" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<h6 class="collapse-header">Acciones:</h6>
				<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=user&mod=user&tpl=profile">Mis datos</a>
				<?php if ($_SESSION[nameSessionZP]->IDTYPE == 4) { ?>
				<a class="collapse-item" href="<?php echo DOMAINZP; ?>?view=user&mod=user&tpl=address">Mis direcciones</a>
				<?php } ?>
			</div>
		</div>
	</li>
	
	<hr class="sidebar-divider">
	<div class="sidebar-heading">
		Atención al cliente
	</div>
	<li class="nav-item">
		<a class="nav-link" href="tel:+34681949316">
			<i class="fas fa-fw fa-phone"></i>
			<span>681 949 316</span>
		</a>
	</li>

	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block">
	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
<!-- End of Sidebar -->