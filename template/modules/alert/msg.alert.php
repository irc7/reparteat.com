<?php if($view != "tpv") { ?>
<div id="msg-alert" class="msg-alert">
	<div class="container">
		<div class="row">
			<div class="wrap-msg-alert text-center">
				<button id="btn-close-msg-alert"><i class="fa fa-close grayStrong"></i></button>
				<div class="separator5"></div>
			<?php if($_SESSION[msgError]["result"] == 0) { ?>	
				<h5 class="textBox grayStrong text-center"><?php echo $_SESSION[msgError]["msg"]; ?></h5>
			<?php }else { ?>	
				<h5 class="textBox danger text-center"><?php echo $_SESSION[msgError]["msg"]; ?></h5>
			<?php } ?>	
			</div>
		</div>
	</div>
</div>
<?php 
}
	unset($_SESSION[msgError]);
?>