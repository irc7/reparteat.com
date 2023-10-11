<?php
//Menu Auxiliar	
	$qA = "select * from ".preBD."menu_item where LEVEL = 0 and PARENT = 0 and IDMENU = 1 order by POSITION asc";
	$rA = checkingQuery($connectBD,$qA);
	$totalItemA = mysqli_num_rows($rA);
	$contForm = 0;
?>

	<ul class="mnu-top nav navbar-nav navbar-right">
	<?php	
		while($itemA = mysqli_fetch_object($rA)){ ?>
	<?php
			//submnu	
				$q = "select * from ".preBD."menu_item where LEVEL = 1 and PARENT = ".$itemA->ID." and IDMENU = 2 order by POSITION desc";
				$r = checkingQuery($connectBD,$q);
				$t = mysqli_num_rows($r);
				$classList = '';
				if($t>0) {
					$classList = ' class="dropdown-submenu"';
				}
				echo "<li id='item-".$itemA->ID."'".$classList.">";
				
				$TitleItemA = stripslashes($itemA->TITLE);
				$classLink = "page-scroll titleBold white ";
				constructItemMenu($TitleItemA, $itemA->TYPE, $itemA->IDVIEW, $itemA->TARGET, $itemA->DISPLAY, $itemA->THUMBNAIL, $classLink, $view);
				if($t>0 || $itemA->ID == 2) {
	?>
					<ul class="dropdown-menu multi-level<?php if($itemA->ID == 2) {echo " list-mnu-newsletter";} ?>">
	<?php
						if($itemA->ID == 2) {
							echo "<li id='list-form-newsletter'>
									<div id='wrap-header-form'>
										<div class='wrap-content-mov'>
											<i id='close-form-newsletter' class='fa fa-times pointer grayStrong transition' aria-hidden='true'></i>";
											include("template/modules/newsletter/newsletter.form.php");
							echo "		</div>
									</div>
								</li>";
							$contForm++;
						}else {
							while($itemS = mysqli_fetch_object($r)){
								echo "<li id='item-".$itemS->ID."'>";
								$TitleItem = stripslashes($itemS->TITLE);
								$classLink = "page-scroll titleBold white ";
								constructItemMenu($TitleItem, $itemS->TYPE, $itemS->IDVIEW, $itemS->TARGET, $itemS->DISPLAY, $itemS->THUMBNAIL, $classLink, $view);
								echo "</li>";
							}	
						}
	?>
					</ul>
	<?php
				}
			echo "</li>";
		} 
		
	?>
	</ul>