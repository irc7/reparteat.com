
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Resumen de pedidos</h1>
<p class="mb-4"></p>
<div class='container bgGrayStrong'>
	<div class="separator10"></div>
	<form id="form-filter-day" name='form-filter-day' method='get' action='index.php'>
		<input type='hidden' name='view' value='<?php echo $view; ?>' />
		<input type='hidden' name='mod' value='<?php echo $mod; ?>' />
		<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
		<input type='hidden' name='filter' value='sumary' />
		<div class="form-group">
			<div class="col-md-3 col-sm-12 col-xs-12">
				<label class="label-field white" for="filter">Selecciona fecha:</label>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12">
				<input type="date" class="form-control form-l" value="<?php echo $dateStartString; ?>" name="dateStart" id="dateStart" />
			</div>
			<div class="col-sm-1 col-xs-12">
				<label class="label-field white textCenter" for="filter">A</label>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12">
				<input type="date" class="form-control form-l" value="<?php echo $dateEndString; ?>" name="dateEnd" id="dateEnd" />
			</div>
			<div class="col-md-2 col-sm-3 col-xs-12">
				<button type="submit" class="btn btn-primary floatLeft transition">
					Consultar
				</button>
			</div>
			<div class="separator10"></div>
		</div>
	</form>
</div>
<div class="separator50"></div>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Del <?php echo $dateStart->format("d/m/Y")." a " . $dateEnd->format("d/m/Y"); ?></h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
	<?php 
		//pre($orders);
		if(count($orders) > 0) {
			$tSubtotal = 0;
			$tShipping = 0;
			$tCost = 0;
			
			foreach($orders as $item) { ?>
				<div class="wrap-sumary-delivery-order">
					<div class="col-md-2 col-sm-2 col-xs-12 reference">
						<?php echo $item["data"]->REF; ?>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 supplier">
						<?php echo $item["data"]->TITLE; ?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 subtotal textRight">
						<?php echo $item["data"]->SUBTOTAL; ?> €
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 shipping textRight">
						<?php echo $item["data"]->SHIPPING; ?> €
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 shipping textRight">
						<?php echo $item["data"]->COST; ?> €
					</div>
				</div>
				<div class="separator1 bgGrayLight"></div>
				<?php 
						$tSubtotal += $item["data"]->SUBTOTAL;
						$tShipping += $item["data"]->SHIPPING;
						$tCost += $item["data"]->COST;
			} ?>
			<div class="separator10"></div>
			<div class="separator1 bgGrayStrong"></div>
			<div class="separator10"></div>
			<div class="wrap-sumary-delivery-order">
				<div class="col-md-7 col-sm-7 col-xs-12 subtotal textRight">
					<?php echo $tSubtotal; ?> €
				</div>
				<div class="col-md-2 col-sm-2 col-xs-12 shipping textRight">
					<?php echo $tShipping; ?> €
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12 shipping textRight">
					<?php echo $tCost; ?> €
				</div>
			</div>
<?php
		}else {
?>
			<div class='container'>
				<div class='textCenter'>
					<i class="fa fa-info-circle green iconBig"></i>
					<div class="separator10"></div>
					<h5 class="textBox green">No hay pedidos registrados para el periodo de <?php echo $dateStart->format("d/m/Y")." a " . $dateEnd->format("d/m/Y"); ?></h5>
				</div>
			</div>
<?php	} ?>				
			
		</div>
	</div>
</div>


<div class="separator50"></div>

       

