var $pz = jQuery.noConflict();
$pz(document).ready(function(){
	$pz("#change-pwd").click(function(){
		var d = $pz("#wrap-pwd").css("display");
		if(d == "none") {
			$pz("#wrap-pwd").fadeIn();
			$pz("#header-edit").height("130px");
			$pz("#wrap-pwd input").attr("disabled", false);
			$pz(this).val("Cerrar y cancelar");
		}else {
			$pz("#wrap-pwd").fadeOut();
			$pz("#header-edit").height("50px");
			$pz("#wrap-pwd input").attr("disabled", true);
			$pz(this).val("Cambiar contraseña");
		}
	});
	$pz("#deleteImage").click(function(){
		$pz("#optImg").val(1);
		sendKeywords();
		showloading(1);
		validate($pz("#mainform")); 
		return false;
	});
	$pz("#changeImage").click(function(){
		$pz("#optImg").val(2);
		$pz("#Image").attr("disabled", false);
		$pz(".box-edit-img").fadeIn();
	});	
	
	$pz("#changeParent").click(function(){
		$pz(this).fadeOut();
		$pz("#box-panel-parent").fadeIn();
	});	
	$pz("#changePass").click(function(){
		$pz(this).fadeOut();
		var $log = $pz("#Email").attr("disabled", false);
		var $log = $pz("#Pass").attr("disabled", false);
		var $log = $pz("#Pass").val("");
		var $log = $pz("#PassRepeat").attr("disabled", false);
		var $log = $pz("#PassRepeat").val("");
		$pz(".edit-password").fadeIn();
	});	
	$pz("#Type").change(function(){
		var type =$pz(this).val();
		if(type == 3 || type == 1){//repartidor
			$pz("#IDTelegram").attr("disabled",false);
			$pz("#box-idtelegram").fadeIn();
		}else {
			$pz("#IDTelegram").attr("disabled",true);
			$pz("#box-idtelegram").fadeOut();
		}
	});
});
	
	
