var $cm = jQuery.noConflict();
$cm(document).ready(function(){
	$cm("#deleteImage").click(function(){
		$cm("#optImg").val(1);
		showloading(1);
		validate($cm("#mainform")); 
		return false;
	});
	$cm("#changeImage").click(function(){
		$cm("#optImg").val(2);
		$cm("#Image").attr("disabled", false);
		$cm(".box-edit-img").fadeIn();
	});		
});		