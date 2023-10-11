var $date = jQuery.noConflict();
	$date(document).ready(function(){
		$date( "#date_day" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		$date( "#date_day_finish" ).datepicker({
			dateFormat: "dd-mm-yy",
			defaultDate: "+1w",
			changeMonth: true,
			areaOfMonths: 1
		});
		$date("#controlDateEnd").click(function(){
			if($date(this).attr("checked")) {
				$date("#date_day_finish").attr("disabled", false);
				$date("#Date_end_hh").attr("disabled", false);
				$date("#Date_end_ii").attr("disabled", false);
				$date("#boxDateEnd").fadeIn();
			}else{
				$date("#boxDateEnd").fadeOut();
				$date("#date_day_finish").attr("disabled", true);
				$date("#Date_end_hh").attr("disabled", true);
				$date("#Date_end_ii").attr("disabled", true);
			}
		});
	});

function SelectIdSection(){
	var indice = document.mainform.section.selectedIndex;
	var valor = document.mainform.section.options[indice].value;
	
	var sel = document.getElementById("section"); 
	
	//ponemos todas los div a none y posteriormente ponemos a block el div seleccionado
	for (var i = 0; i < sel.length; i++){
		var opt = sel[i];
		document.getElementById('seccion_'+opt.value).style.display = 'none';
		document.getElementById('origen'+opt.value).selectedIndex=-1; 
	}
	
	document.getElementById('seccion_'+valor).style.display = 'block';
}







function changeAction() {
	document.getElementById('mainform').action='modules/blog/create_record.php?add_p=ON';	
}

function optionAction(action, id) {
	document.getElementById('mainform').action='modules/blog/edit_record.php?record='+id+'&option='+action;
}

function optionActionBlock(action, id, block) {
	document.getElementById('mainform').action='modules/blog/edit_record.php?record='+id+'&option='+action+'&block='+block;
}
function optionActionFile(action, id, file) {
	document.getElementById('mainform').action='modules/blog/edit_record.php?record='+id+'&option='+action+'&file='+file;
}
function open_thumb_new() {
	document.getElementById('Thumb_image').style.display='block';
	document.getElementById('Thumb_image').disabled=false;
	document.getElementById('box_article').style.height='430px';
}
function close_thumb_new() {
	document.getElementById('Thumb_image').style.display='none';
	document.getElementById('Thumb_image').disabled=true;
	document.getElementById('box_article').style.height='410px';
}







//PARRAFOS
function selectImageParagraph(a) {
	document.getElementById('list_img'+a).style.opacity = '1';
	document.getElementById('list_video'+a).style.opacity = '0.5';
	document.getElementById('list_gallery'+a).style.opacity = '0.5';
	
	document.getElementById('box_img'+a).style.display = 'block';
	document.getElementById('box_video'+a).style.display = 'none';
	document.getElementById('box_gallery'+a).style.display = 'none';
	document.getElementById('o_p_'+a).value = 0;
	document.getElementById('mnu_img'+a+'_image').checked = true;
	document.getElementById('Image'+a).style.display = 'block';
	document.getElementById('Image'+a).disabled = false;
	document.getElementById('Video'+a+'_img').disabled = true;
	document.getElementById('Video'+a).disabled = true;
	document.getElementById('Youtube'+a).disabled = true;
	document.getElementById('Youtube'+a+'_img').disabled = true;
	//document.getElementById('image_start'+a).disabled=true;
	
	//document.getElementById('Block'+a+'_album').value = 0;
}

function selectVideoParagraph(a) {
	document.getElementById('list_img'+a).style.opacity = '0.5';
	document.getElementById('list_video'+a).style.opacity = '1';
	document.getElementById('list_gallery'+a).style.opacity = '0.5';
	
	//document.getElementById('mnu_video'+a+'_per').checked = true;
	document.getElementById('box_img'+a).style.display = 'none';
	document.getElementById('box_video'+a).style.display = 'block';
	document.getElementById('box_gallery'+a).style.display = 'none';	
	
	document.getElementById('o_p_'+a).value = 1;
	document.getElementById('Image'+a).disabled = true;
	document.getElementById('Video'+a+'_img').disabled = false;
	document.getElementById('Video'+a).disabled = false;
	document.getElementById('Youtube'+a).disabled = false;
	document.getElementById('Youtube'+a+'_img').disabled = true;
}
function selectGalleryParagraph(a) {
	document.getElementById('list_img'+a).style.opacity = '0.5';
	document.getElementById('list_video'+a).style.opacity = '0.5';
	document.getElementById('list_gallery'+a).style.opacity = '1';
	
	//document.getElementById('mnu_video'+a+'_per').checked = true;
	document.getElementById('box_img'+a).style.display = 'none';
	document.getElementById('box_video'+a).style.display = 'none';
	document.getElementById('box_gallery'+a).style.display = 'block';
	
	document.getElementById('o_p_'+a).value = 5;
	document.getElementById('Image'+a).disabled = true;
	document.getElementById('Video'+a+'_img').disabled = true;
	document.getElementById('Video'+a).disabled = true;
	document.getElementById('Youtube'+a).disabled = true;
	document.getElementById('Youtube'+a+'_img').disabled = true;
}

function closedVideoOptions(a) {
	var obj1 = document.getElementById('Video'+a);
	var obj2 = document.getElementById('Youtube'+a);
	
	obj1.disabled = true;
	obj2.disabled = true;
	document.getElementById('opt_video'+a).style.display = 'none';
	document.getElementById('opt_youtube'+a).style.display = 'none';
}
function openVideoOptions(a,t) {
	var obj1 = document.getElementById('Video'+a);
	var obj2 = document.getElementById('Youtube'+a);
	
	if(t=='video') {
		obj1.disabled = false;
		obj2.disabled = true;
		document.getElementById('opt_video'+a).style.display = 'block';
		document.getElementById('opt_youtube'+a).style.display = 'none';
		document.getElementById('o_p_'+a).value = 1;
		document.getElementById('box_opt_img_youtube'+a).style.display = 'none';
		document.getElementById('mnu_img_nothing'+a).checked = true;
	} else if(t=='youtube') {
		obj1.disabled = true;
		obj2.disabled = false;
		document.getElementById('opt_video'+a).style.display = 'none';
		document.getElementById('opt_youtube'+a).style.display = 'block';
		document.getElementById('o_p_'+a).value = 2;
		document.getElementById('box_opt_img_youtube'+a).style.display = 'block';
		document.getElementById('opt_img_youtube'+a).checked = true;
	} else {
		obj1.disabled = true;
		obj2.disabled = true;
		document.getElementById('opt_video'+a).style.display = 'none';
		document.getElementById('opt_youtube'+a).style.display = 'none';
		document.getElementById('o_p_'+a).value = 0;
	}
}
function active_mnu_img(a) {
	document.getElementById('select_new_thumb'+a).disabled = false;
	document.getElementById('mnu_img_delete'+a).disabled = false;
	document.getElementById('mnu_img_nothing'+a).disabled = false;
	document.getElementById('mnu_img_new'+a).disabled = false;
	document.getElementById('opt_img_youtube'+a).disabled = false;
}
function active_mnu_img_Youtube(a, type) {
	document.getElementById('select_new_thumb'+a).disabled = true;
	document.getElementById('mnu_img_delete'+a).disabled = true;
	document.getElementById('mnu_img_nothing'+a).disabled = true;
	if(type != "youtube") {
		document.getElementById('mnu_img_new'+a).disabled = true;
	} else {
		document.getElementById('mnu_img_new'+a).disabled = false;		
	}
	document.getElementById('opt_img_youtube'+a).disabled = false;
	document.getElementById('opt_img_youtube'+a).checked = true;
}
function close_mnu_img(a) {
	document.getElementById('select_new_thumb'+a).disabled = true;
	document.getElementById('mnu_img_delete'+a).disabled = false;
	document.getElementById('mnu_img_delete'+a).checked = true;
	document.getElementById('mnu_img_nothing'+a).disabled = true;
	document.getElementById('mnu_img_new'+a).disabled = true;
	document.getElementById('opt_img_youtube'+a).disabled = true;
}
function openBoxFile(a) {
	var obj = document.getElementById('box_file'+a);
	var obj2 = document.getElementById('add_file'+a);
	var objInput = document.getElementById('Block'+a+'_file');
	
	if(obj.style.display == 'none') {
		objInput.disabled = false;
		obj.style.display = 'block';
		
		obj2.style.display = 'none';
	} else {
		objInput.disabled = true;
		obj.style.display = 'none';
		obj2.style.display = 'block';
	}
}

function view_link_article(a) {
	var id_obj = "content_url"+a;
	var st = document.getElementById(id_obj).style.visibility;
	if(st == "hidden"){
		document.getElementById(id_obj).style.visibility='visible';
	}else{
		document.getElementById(id_obj).style.visibility='hidden';
	}
}

function open_closeSecurity(cont) {

	var obj = document.getElementById("list_security" + cont);
	if (obj.className == "view_security_off"){
		obj.className = "view_security_on";
	} else {
		obj.className = "view_security_off";
	}
	
}



function view_alert_link(cont) {
		var obj = document.getElementById("content_file"+cont);
		if (obj.className == "view_off"){
			obj.className = "view_on";
		} else {
			obj.className = "view_off";
		}
}
function close_alert_link(cont) {
	var obj = document.getElementById("content_file"+cont);
	obj.className = "view_off";
}

function openNexDoc(i) {
	if(document.getElementById('Url_file'+i).style.display=='none') {
		document.getElementById('Url_file'+i).style.display='block';
		
	} else {
		document.getElementById('Url_file'+i).style.display='none';
		
	}
}
function openNexImg(i) {
	if(document.getElementById('Url_image'+i).style.display=='none') {
		document.getElementById('Url_image'+i).style.display='block';
		
	} else {
		document.getElementById('Url_image'+i).style.display='none';
		
	}
}


function openNewImageSection() {
	var obj = document.getElementById('new_image_section');
	var obj2 = document.getElementById('delete_image_section');
	
	if(obj.style.display=='block') {
		obj.style.display='none';
		obj.disabled = true;
	} else {
		obj.style.display='block'	
		obj.disabled = false;
	}
	
	obj2.checked=false;
	
}

function openNewSizeImage(){
	var obj = document.getElementById('new_size_section');

	if(obj.style.display=='block') {
		obj.style.display='none';
		obj.disabled = true;
	} else {
		obj.style.display='block'	
		obj.disabled = false;
	}	
	
}

function deleteImageSection(){
	var obj = document.getElementById('Select_image_section');
	var obj3 = document.getElementById('new_image_section');

	obj.checked=false;
	obj3.style.display='none';

	var obj2 = document.getElementById('delete_image_section');

	if(obj2.checked==true){
		obj2.value=1;
	}else{
		obj2.value=0;
	}
}

/*función mostrar dimensiones al crear un post*/
function showDimension(){
	
	var e = document.getElementById("Section");
	var strUser = e.options[e.selectedIndex].value;		
	
	for (var i = 0; i < e.length; i++){
		var opt = e[i];
		
		if(opt.value != strUser){
			document.getElementById('box_infomartion_'+opt.value).style.display = 'none';
			document.getElementById('video_infomartion_'+opt.value).style.display = 'none';
		}else{
			document.getElementById('box_infomartion_'+strUser).style.display = 'block';
			document.getElementById('video_infomartion_'+opt.value).style.display = 'block';
		}
	}				
}

/*función mostrar dimensiones al editar un post*/
function showDimensionEditPost(aux){
	
	var e = document.getElementById("Section");
	var strUser = e.options[e.selectedIndex].value;			

	for(var j = 1; j <= aux; j++){
		for (var i = 0; i < e.length; i++){
			var opt = e[i];
			
			if(opt.value != strUser){
				document.getElementById('box_infomartion_'+opt.value).style.display = 'none';
				
				if(document.getElementById('new_dimensions_'+j)){	
					document.getElementById('new_infomartion_'+opt.value).style.display = 'none';
				}

				if(document.getElementById('video_dimensions_'+j)){
					document.getElementById('video_infomartion_'+opt.value).style.display = 'none';
				}
				
				/*editar video*/
				if(document.getElementById('edit_video_dimensions_'+j)){
					document.getElementById('edit_video_infomartion_'+opt.value).style.display = 'none';
				}
				
				/*editar imagen*/
				if(document.getElementById('box_dimensions'+j)){			
					document.getElementById('box_infomartion_tam_'+opt.value+"_"+j).style.display = 'none';
				}
				
			}else{
				document.getElementById('box_infomartion_'+strUser).style.display = 'block';
				
				if(document.getElementById('new_dimensions_'+j)){	
					document.getElementById('new_infomartion_'+opt.value).style.display = 'block';	
				}					

				if(document.getElementById('video_dimensions_'+j)){				
					document.getElementById('video_infomartion_'+opt.value).style.display = 'block';
				}
				
				/*editar vídeo*/
				if(document.getElementById('edit_video_dimensions_'+j)){				
					document.getElementById('edit_video_infomartion_'+opt.value).style.display = 'block';
				}
				
				/*editar imagen*/	
				if(document.getElementById('box_dimensions'+j)){					
					document.getElementById('box_infomartion_tam_'+opt.value+"_"+j).style.display = 'block';
				}
			}
		}	
	}
}