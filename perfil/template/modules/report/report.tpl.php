
          <!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Resumen de pedidos</h1>
<p class="mb-4"></p>
<div class='bgGrayStrong'>
	<div class="separator10"></div>
	<form id="form-filter-day" name='dropdown' method='get' action='index.php'>
		<input type='hidden' name='view' value='<?php echo $view; ?>' />
		<input type='hidden' name='mod' value='<?php echo $mod; ?>' />
		<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
		<?php if($_SESSION[nameSessionZP]->IDTYPE == 5 || $_SESSION[nameSessionZP]->IDTYPE == 1) { ?>
			<input type='hidden' name='z' value='<?php echo $idZone; ?>' />
		<?php } ?>
		<div class="form-group">
			<div class="col-md-2 col-sm-4 col-xs-12">
				<label class="label-field white" for="filter">Selecciona fecha:</label>
			</div>
			<div class="col-md-10 col-sm-8 col-xs-12">
				<input type="date" class="form-control form-s" value="<?php echo $filterstring; ?>" name="filter" id="filter" />
			</div>
			<div class="separator10"></div>
		</div>
	</form>
</div>
<div class="separator50"></div>

<?php	
if(count($reports)>0) { 
	foreach($reports as $item) {	
?>
		<div class="card shadow mb-4 wrap-report">
			<div class="card-header py-3 bgGrayStrong">
				<h4><?php echo $item['name']; ?></h4>
				<h6 class="m-0 font-weight-bold text-primary">FORMULARIO DEL <?php echo $dateCheck->format("d/m/Y"); ?></h6>
			</div>
			<div class="card-body">
				<div class="row no-margin">
					<div class="report-rep">
						<form action="<?php echo DOMAINZP; ?>template/modules/report/save.php" method="post" id="form-reports-<?php echo $item['idRep']; ?>" class="form-reports"> 
							<input type="hidden" name="view" value="<?php echo $view; ?>" />
							<input type="hidden" name="mod" value="<?php echo $mod; ?>" />
							<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
							<?php if($_SESSION[nameSessionZP]->IDTYPE == 5 || $_SESSION[nameSessionZP]->IDTYPE == 1) { ?>
								<input type='hidden' name='zone' value='<?php echo $idZone; ?>' />
							<?php } ?>
							<input type="hidden" name="action" value="<?php if($item['id'] > 0){echo 'edit';}else{echo 'create';} ?>" />
							<input type="hidden" name="idReport" value="<?php echo $item['id']; ?>" />
							<input type="hidden" name="idRep" value="<?php echo $item['idRep']; ?>" />
							<input type="hidden" name="dateCreate" value="<?php echo $item['date']->format("Y-m-d h:i:s"); ?>" />
							<input type="hidden" name="name" id="name-<?php echo $item['idRep']; ?>" value="<?php echo $item['name']; ?>" />	
							<div class="col-sm-6 col-xs-12 report-horary">
								<div class="form-group bgGrayNormal">
									<label class="label-field white" for="day">Reparto mediodia:</label>
									<input type="checkbox" class="form-control form-s floatRight" name="day" id="day-<?php echo $item['idRep']; ?>"<?php if($item['day'] == 1){echo " checked";} ?> /> 
									<div class="separator"></div>
								</div>
								<div class="report-opt-horary">
									<div class="separator10"></div>
									<div class="col-xs-12 form-group">
										<label class="label-field grayStrong" for="orderDay">Pedidos mediodia:</label>
										<h5 class="grayStrong"><?php echo $item['orderDay']; ?></h5>
										<input type="hidden" name="orderDay" id="orderDay-<?php echo $item['idRep']; ?>" value="<?php echo $item['orderDay']; ?>" />
										<div class="separator"></div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xs-12 report-horary">
								<div class="form-group bgGrayNormal">
									<label class="label-field white" for="night">Reparto noche:</label>
									<input type="checkbox" class="form-control form-s floatRight" name="night" id="night-<?php echo $item['idRep']; ?>"<?php if($item['night'] == 1){echo " checked";} ?> /> 
									<div class="separator"></div>
								</div>
								<div class="report-opt-horary">
									<div class="separator10"></div>
									<div class="col-xs-12 form-group">
										<label class="label-field grayStrong" for="orderNight">Pedidos noche:</label>
										<h5 class="grayStrong"><?php echo $item['orderNight']; ?></h5>
										<input type="hidden" name="orderNight" id="orderNight-<?php echo $item['idRep']; ?>" value="<?php echo $item['orderNight']; ?>" />
										<div class="separator"></div>
									</div>
								</div>
							</div>
							<div class="separator10"></div>
							<div class="separator1 bgGrayNormal"></div>
							<div class="separator20"></div>
							<div class="form-group report-table">
								<div class="col-xs-9">
									<label class="label-field grayStrong textRight" for="payCash">Cobrado efectivo:</label>
								</div>
								<div class="col-xs-3">
									<h5 class="grayStrong textRight"><?php echo $item['payCash']; ?> &euro;</h5>
									<input type="hidden" name="payCash" id="payCash-<?php echo $item['idRep']; ?>" value="<?php echo $item['payCash']; ?>" />
								</div>
								<div class="separator5"></div>
								<div class="separator1 bgGrayLight"></div>
								<div class="separator10"></div>
							</div>
							<div class="form-group report-table">
								<div class="col-xs-9">
									<label class="label-field grayStrong textRight" for="payTPV">Cobrado TPV:</label>
								</div>
								<div class="col-xs-3">
									<h5 class="grayStrong textRight"><?php echo $item['payTPV']; ?> &euro;</h5>
									<input type="hidden" name="payTPV" id="payTPV-<?php echo $item['idRep']; ?>" value="<?php echo $item['payTPV']; ?>" />
								</div>
								<div class="separator5"></div>
								<div class="separator1 bgGrayLight"></div>
								<div class="separator10"></div>
							</div>
							<div class="form-group report-table">
								<div class="col-xs-9">
									<label class="label-field grayStrong textRight" for="salaryDay">Salario mediodia:</label>
								</div>
								<div class="col-xs-3">
								<?php if($_SESSION[nameSessionZP]->IDTYPE == 5 || $_SESSION[nameSessionZP]->IDTYPE == 1) { ?>
										<input type="number" class="form-control form-l floatRight textRight operator-report" name="salaryDay" id="salaryDay-<?php echo $item['idRep']; ?>" value="<?php echo $item['salaryDay']; ?>" step="0.01" />
								<?php }else{ ?>
										<h5 class="grayStrong textRight"><?php echo $item['salaryDay']; ?> &euro;</h5>
										<input type="hidden" name="salaryDay" id="salaryDay-<?php echo $item['idRep']; ?>" value="<?php echo $item['salaryDay']; ?>" />
								<?php } ?>
								</div>
								<div class="separator5"></div>
								<div class="separator1 bgGrayLight"></div>
								<div class="separator10"></div>
							</div>
							<div class="form-group report-table">
								<div class="col-xs-9">
									<label class="label-field grayStrong textRight" for="salaryNight">Salario noche:</label>
								</div>
								<div class="col-xs-3">
								<?php if($_SESSION[nameSessionZP]->IDTYPE == 5 || $_SESSION[nameSessionZP]->IDTYPE == 1) { ?>
									<input type="number" class="form-control form-l floatRight textRight operator-report" name="salaryNight" id="salaryNight-<?php echo $item['idRep']; ?>" value="<?php echo $item['salaryNight']; ?>" step="0.01" />
								<?php }else{ ?>
									<h5 class="grayStrong textRight"><?php echo $item['salaryNight']; ?> &euro;</h5>
									<input type="hidden" name="salaryNight" id="salaryNight-<?php echo $item['idRep']; ?>" value="<?php echo $item['salaryNight']; ?>" />
								<?php } ?>
								</div>
								<div class="separator5"></div>
								<div class="separator1 bgGrayLight"></div>
								<div class="separator10"></div>
							</div>
							<div class="form-group report-table">
								<div class="col-xs-9">
									<label class="label-field grayStrong textRight" for="orderNight">Gastos:</label>
								</div>
								<div class="col-xs-3">
								<?php if($_SESSION[nameSessionZP]->IDTYPE == 3) { ?>
									<input type="number" class="form-control form-l floatRight textRight operator-report" name="cost" id="cost-<?php echo $item['idRep']; ?>" value="<?php echo $item['cost']; ?>" step="0.01" />
								<?php }else{ ?>
									<h5 class="grayStrong textRight"><?php echo ($item['cost']*-1); ?> &euro;</h5>
									<input type="hidden" name="cost" id="cost-<?php echo $item['idRep']; ?>" value="<?php echo $item['cost']; ?>" />
								<?php } ?>
								</div>
								<div class="separator15"></div>
								<div class="separator1 bgGrayStrong"></div>
								<div class="separator10"></div>
							</div>
							<div class="separator5"></div>
							<div class="form-group report-table">
								<div class="col-xs-9">
									<label class="label-field grayStrong textRight" for="total">A entregar efectivo:</label>
								</div>
								<div class="col-xs-3">
									<h5 id="total-report-<?php echo $item['idRep']; ?>" class="grayStrong textRight"><?php echo $item['total']; ?> &euro;</h5>
									<input type="hidden" name="total" id="total-<?php echo $item['idRep']; ?>" value="<?php echo $item['total']; ?>" />
								</div>
								<div class="separator10"></div>
							</div>
							<div class="separator15"></div>
							<div class="form-group">
								<h5 class="label-field grayStrong">Observaciones:</h5>
								<?php if($_SESSION[nameSessionZP]->IDTYPE == 3) { ?>
									<textarea class="form-control form-xl report-text" name="text" id="text-<?php echo $item['idRep']; ?>"><?php echo $item['text']; ?></textarea>
								<?php }else{ ?>
									<textarea class="form-control form-xl report-text" name="text" id="text-<?php echo $item['idRep']; ?>" style="display:none;">
										<?php echo $item['text']; ?>
									</textarea>
									<div class="form-control form-xl report-text"> 
										<?php echo $item['text']; ?>
									</div>
								<?php } ?>
								<div class="separator10"></div>
								<div class="separator1 bgGrayLight"></div>
							</div>
							<div class="form-group">
								<?php if($item['type'] == "new") { ?>
									<button type="submit" class="btn btn-primary floatRight bgGreen white">Crear</button>
								<?php }else if($item['type'] == "bd") { ?>
									<button type="submit" class="btn btn-secondary floatRight bgOrange yellow">Guardar</button>
								<?php } ?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
						
<?php 	
	} 
}else {
?>
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="row no-margin">
				<div class='container'>
					<div class='textCenter'>
						<i class="fa fa-info-circle green iconBig"></i>
						<div class="separator10"></div>
						<h5 class="textBox green">No hay repartos registrados para la fecha <?php echo $dateCheck->format("d/m/Y"); ?></h5>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php	
} ?>				
<div class="separator50"></div>

       

