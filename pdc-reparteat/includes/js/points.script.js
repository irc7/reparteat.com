var $point = jQuery.noConflict();
	$point(document).ready(function(){

	$point(".delete-img").click(function(){
		var id = $point(this).attr("id").split("-");
		$point("#wrap-"+id[1]).fadeOut();
		$point(this).fadeOut();
		$point("#action-"+id[1]).val('1');
	});	

	
});
