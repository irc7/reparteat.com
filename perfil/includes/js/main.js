$(document).on('click', '[data-toggle="lightbox"]', function(event) {
	event.preventDefault();
	$(this).ekkoLightbox();
});


$(document).ready(function(){
	$("#changePass").click(function(){
		$(this).fadeOut();
		//var $log = $("#Email").attr("disabled", false);
		var $log = $("#Pass").attr("disabled", false);
		var $log = $("#Pass").val("");
		var $log = $("#PassRepeat").attr("disabled", false);
		var $log = $("#PassRepeat").val("");
		$(".edit-password").fadeIn();
	});	
	$(".add-user-group").click(function(){
		$(".form-check-input").attr("checked", false);
		$("#wrapform-add-users").fadeIn();
	});
	$("#close-add-users").click(function(){
		$("#wrapform-add-users").fadeOut();
	});
	
	$("#btnChangeDoc").click(function(){
		$("#Doc").attr("type", "file");
		$("#Doc").val("");
		$("#changeDocument").fadeIn();
	});
	$(".btnChangeDocNotes").click(function(){
		var idn = $(this).attr("id").split("-");
		$("#Doc-"+idn[1]).attr("type", "file");
		$("#Doc-"+idn[1]).val("");
		$("#changeDocument-"+idn[1]).fadeIn();
	});
	
	$(".open-alert").click(function(){
		var aux = $(this).attr("id").split("-");
		var idC = aux[2];
	
		var act = $(this).attr("data-action");
		//var d = $(this).attr("src");
		if(act == "open") {
			//d = d.replace("open","close");
			//$(this).attr("src", d);
			
			$("#detail-alert-"+idC).fadeIn();
			$("#detail-alert-"+idC).animate({
				height: "show"
			  }, 600);
			$(this).attr("data-action", "close");
		}else if(act == "close") {
			//d = d.replace("close","open");
			//$(this).attr("src", d);
			
			$("#detail-alert-"+idC).fadeOut();
			$("#detail-alert-"+idC).animate({
				height: "hide"
			  }, 600);
			 $(this).attr("data-action", "open");
		}
    });
	
});




function alertConfirm(msg, url) {
	if(confirm(msg)) {
		document.location = (url);
	}
}