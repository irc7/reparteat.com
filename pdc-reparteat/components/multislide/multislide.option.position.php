<div class='cp_mnu_title title_header_mod'>Modificar Posicones</div>
<?php
	if (!isset($_GET['filterHook']) || intval($_GET['filterHook']) == 0) {
		$filterHook = 1;
	//	$filterHook = "";
}else {
	$filterHook = intval($_GET['filterHook']);
	//	$filterHookq .= " and HOOK = '".$filterHook."'";
}
	
	
	$urlCom = "index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&recordsperpage=".$recordsperpage."&filterHook=".$filterHook."&page=".$page;;
	$urlMod = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&recordsperpage=".$recordsperpage."&filterHook=".$filterHook."&page=".$page;;
	
	require_once("includes/classes/Zone/class.Zone.php");
	require_once("includes/classes/Image/class.Image.php");
	require_once("includes/classes/Multislide/class.Multislide.php");
	require_once("includes/classes/Multislide/class.MultislideHook.php");
	
	$zoneObj = new Image();
	$zoneObj = new Zone();
	$hookObj = new MultislideHook();
	$multislideObj = new Multislide();
	
	$hooks = array(); 
	$hooks = $hookObj->listHook(); 
	
	if($filterHook>0) {
		$list = $multislideObj->listFilter($filterHook, "asc");
	}else{
		$list = array();
	}
	
	
	?>
<div class='container container-admin darkshaded'>
	<div class='row'>
		<div class='col-xs-12'>
			<label class="label-field white" style="width:100%;">Arrastrar y soltar</label>
		</div>
		<?php /*
		<form name='dropdown' method='get' action='index.php'>
			<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
			<input type='hidden' name='com' value='<?php echo $com; ?>' />
			<input type='hidden' name='tpl' value='<?php echo $tpl; ?>' />
			<input type='hidden' name='opt' value='<?php echo $opt; ?>' />
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class='col-sm-5 col-xs-12'>
				<label class="label-field white" style="width:100%;">Zonas de visualización</label>
			</div>
			<div class='col-sm-2 col-xs-12'></div>
			<div class="separator1">&nbsp;</div>
			<div class='col-sm-5 col-xs-12'>
				<select name='filterHook' id='filterHook' class="form-control form-l">
					<option value="0"<?php if($filterHook == 0){echo " selected";} ?>>Todos las zonas de visualización</option>
					<?php foreach($hooks as $h){ ?>
						<option value="<?php echo $h->ID; ?>"<?php if($filterHook == $h->ID){echo " selected";} ?>><?php echo $h->TITLE; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class='col-sm-2 col-xs-12'>
				<button type="submit" class="btn tf-btn btn-default transition floatRight bgGreen white bold">Filtrar</button>
			</div>
			<div class="separator15">&nbsp;</div>
		</form>
		*/ ?>
	</div>
</div>
<?php if($filterHook>0) { ?>
	<div class="separator30">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row'>
				<div class='col-sm-4 cp_title top'>
					<div class='bold textLeft'>#</div>
				</div>
				<div class='col-sm-4 cp_title top'>
					<div class='bold textLeft'>Título</div>
				</div>
				<div class='col-sm-2 cp_title top'>
					<?php if($filterHook > 0) { ?>
						<div class='bold textCenter'>Posicion</div>
					<?php }else{ ?>
						<div class='bold textLeft'>&nbsp;</div>
					<?php } ?>
				</div>
				<div class='col-sm-2 cp_title top'>
					<div class='bold textLeft'>Estado</div>
				</div>
			</div>
		</div>
		<div class="separator30">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row'>
				<div class="sortable" id="drop-items">
	<?php	
				foreach($list as $row){
					$imgLogo = new Image();
					$imgLogo->path = "multislide";
					$imgLogo->pathoriginal = "original";
					$imgLogo->paththumb = "image";
					$urlImg = $imgLogo->dirView.$imgLogo->path."/".$imgLogo->paththumb."/1-".$row->IMAGE;
					$position = $row->POSITION; 
	?>		
					<div class='col-md-12 shaded item-list ordernar-item updated transition' data-index="<?php echo $row->ID; ?>" data-position="<?php echo $position; ?>">
						<div class='col-sm-4'>
							<div class='cp_number bold center m1' style='font-size:14px;'><?php echo $row->ID; ?></div>
								<a style='font-size:14px;' href='index.php?<?php echo $urlMod; ?>&id=<?php echo $row->ID; ?>'>
								<img src='<?php echo $urlImg; ?>' style='border:none;max-height:50px;'/>
							</a>
						</div>
					
						<div class='col-sm-4'>
							<div class='bold'>
								<a class="transition" style='font-size:14px;' href='index.php?mnu=<?php echo $mnu; ?>&com=<?php echo $com; ?>&tpl=edit&opt=<?php echo $opt; ?>&id=<?php echo $row->ID; ?>'>
									<?php echo $row->TITLE; ?>
								</a>
							</div>
						</div>
						<div class='col-sm-2 textCenter'>
							<p id="position-<?php echo $row->ID; ?>" class="position-item"><span><?php echo $position; ?></span></p>
						</div>
						<div class='col-sm-2 textCenter'>
							<i class="fa fa-arrow-circle-o-up green pointer iconBotton transition"></i>&nbsp;<i class="fa fa-arrow-circle-o-down green pointer iconBotton transition"></i>
						</div>
					</div>
		<?php 	} ?>
				</div>
			</div>
		</div>
		
<script type="text/javascript">
	 function guardandoPosiciones() {
		
		var $ord = jQuery.noConflict();
        var positions = [];
        $ord('.updated').each(function () {
			positions.push([$ord(this).attr('data-index'), $ord(this).attr('data-position')]);
			$ord(this).removeClass('updated');
        });
        $ord.ajax({
			url: 'modules/multislide/ajax_ordenar.php',
			method: 'POST',
			dataType: 'text',
			data: {
				update: 1,
				hook: <?php echo $filterHook; ?>,
				positions: positions
			}, success: function (response) {
				console.log(response);
				$ord('.ordernar-item').each(function () {
					var idItem = $ord(this).attr('data-index');
					var pos = $ord(this).attr('data-position');
					$ord('#position-'+idItem).html("<span>"+pos+"</span>");
				});
			}
        });
    }
</script>  
		
<?php } ?>