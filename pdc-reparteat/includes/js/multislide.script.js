var $pub = jQuery.noConflict();
$pub(document).ready(function(){
	$pub(".delete-img").click(function(){
		var id = $pub(this).attr("id").split("-");
		$pub("#wrap-"+id[1]).fadeOut();
		$pub(this).fadeOut();
		$pub("#action-"+id[1]).val('1');
	});	

	$pub('.sortable').sortable({
		update: function (event, ui) {
			$pub(this).children().each(function (index) {
				 if ($pub(this).attr('data-position') != (index+1)) {
					 $pub(this).attr('data-position', (index+1)).addClass('updated');
				 }
			});

			guardandoPosiciones();
		}
	 });

});		