var $us = jQuery.noConflict();
$us(document).ready(function(){
	$us("#deleteImage").click(function(){
		$us("#optImg").val(1);
		showloading(1);
		validate($us("#mainform")); 
		return false;
	});
	
	$us("#changeImage").click(function(){
		$us("#optImg").val(2);
		$us("#Image").attr("disabled", false);
		$us(".box-edit-img").fadeIn();
	});		
	
	
});