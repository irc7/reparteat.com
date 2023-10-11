var $cD = jQuery.noConflict(); //javascript para el tipo 9 Documentos
$cD(document).ready(function(){
//al abrir	
	var sec = $cD("#select-doc-level-1").val();
	$cD(".box-doc-level-2").css("display", "none");
	$cD("#descargasDoc"+sec).css("display", "block");
	var down = $cD("#select-doc-level-2-"+sec).val();
	$cD(".box-doc-level-3").css("display", "none");
	$cD("#box-doc-level-3-"+down).css("display", "block");
	
//acciones	
	//select secciones
	$cD("#select-doc-level-1").change(function(){
		var sec = $cD(this).val();
		
		$cD(".box-doc-level-2").css("display", "none");
		$cD("#descargasDoc"+sec).fadeIn();
		
		var down = $cD("#select-doc-level-2-"+sec).val();
		$cD(".box-doc-level-3").css("display", "none");
		$cD("#box-doc-level-3-"+down).fadeIn();
	});
	//select descargas
	$cD(".select-doc-level-2").change(function(){
		var down = $cD(this).val();
		
		$cD(".box-doc-level-3").css("display", "none");
		$cD("#box-doc-level-3-"+down).fadeIn();
	});
});



function view_submenu_level1(i, dom) {
	if(document.getElementById('submenu_'+i).style.display=='none') {
		document.getElementById('submenu_'+i).style.display='block';
	} else {
		document.getElementById('submenu_'+i).style.display='none';
	}
	var img = document.getElementById('leyend_menu_'+i); 

	if(img.src == dom + 'pdc-reparteat/images/leyen_menu_con.png') {
		img.src= dom + 'pdc-reparteat/images/leyen_menu_rest.png';
	} else {
		img.src= dom + 'pdc-reparteat/images/leyen_menu_con.png';
	}
}
function view_submenu_level2(p, i, dom) {
	if(document.getElementById('submenu_'+p+'_'+i).style.display=='none') {
		document.getElementById('submenu_'+p+'_'+i).style.display='block';
	} else {
		document.getElementById('submenu_'+p+'_'+i).style.display='none';
	}
	var img = document.getElementById('leyend_menu_'+p+'_'+i);

	if(img.src == dom + 'pdc-reparteat/images/leyen_menu_con.png') {
		img.src= dom + 'pdc-reparteat/images/leyen_menu_rest.png';
	} else {
		img.src= dom + 'pdc-reparteat/images/leyen_menu_con.png';
	}
}

function openSelectArticle() {
	var sel = document.getElementById('sectionArticle');
	var box = document.getElementById('content_list_articles');	
	
	var objs = box.getElementsByTagName('div');
	
	for(var i = 0;i < objs.length;i++) {
		objs[i].style.display='none';
	}
	document.getElementById('articles'+sel.value).style.display='block';		
}

function openSelectDescarga() {
	var sel = document.getElementById('sectionDescarga');
	var box = document.getElementById('content_list_descargas');	
	
	var objs = box.getElementsByTagName('div');
	
	for(var i = 0;i < objs.length;i++) {
		objs[i].style.display='none';
	}
	document.getElementById('descargas'+sel.value).style.display='block';		
}

function openSelectVideo() {
	var sel = document.getElementById('sectionVideo');
	var box = document.getElementById('content_list_videos');	
	
	var objs = box.getElementsByTagName('div');
	
	for(var i = 0;i < objs.length;i++) {
		objs[i].style.display='none';
	}
	document.getElementById('videos'+sel.value).style.display='block';		
}

function openSelectGallery() {
	var sel = document.getElementById('sectionGallery');
	var box = document.getElementById('content_list_galleries');	
	
	var objs = box.getElementsByTagName('div');
	
	for(var i = 0;i < objs.length;i++) {
		objs[i].style.display='none';
	}
	document.getElementById('galleries'+sel.value).style.display='block';		
}

function opt_select_type_item(){

    var x = document.getElementById("select_item_menu").value;
    
	/*artículos - sección*/
	var box1 = document.getElementById('select_article');
	var box2 = document.getElementById('box_section');
	
	/*enlace personalizado*/
	var box3 = document.getElementById('select_link');	

	/*descargas - sección*/	
	var box5 = document.getElementById('select_descarga');	
	var box4 = document.getElementById('box_descarga');		
	var box10 = document.getElementById('select_doc');		
	
	/*videos - sección*/	
	var box7 = document.getElementById('select_video');	
	var box6 = document.getElementById('box_video');	

	/*galerías - sección*/	
	var box9 = document.getElementById('select_gallery');	
	var box8 = document.getElementById('box_gallery');	
	
	if(x == 1){
		box1.style.display='none';
		box2.style.display='block';
		box3.style.display='none';
		box4.style.display='none';
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='none';	
		box8.style.display='none';
		box9.style.display='none';			
		box10.style.display='none';	
	}else if(x == 2){
		box1.style.display='block';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='none';
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='none';	
		box8.style.display='none';
		box9.style.display='none';	
		box10.style.display='none';	
		openSelectArticle();
	}else if(x == 3){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='block';
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='none';	
		box8.style.display='none';
		box9.style.display='none';			
		box10.style.display='none';	
	}else if(x == 4){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='none';
		box5.style.display='block';	
		box6.style.display='none';
		box7.style.display='none';	
		box8.style.display='none';
		box9.style.display='none';			
		box10.style.display='none';	
		openSelectDescarga();		
	}else if(x == 5){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='none';
		box5.style.display='none';	
		box6.style.display='block';
		box7.style.display='none';	
		box8.style.display='none';
		box9.style.display='none';			
		box10.style.display='none';	
	}else if(x == 6){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='none';
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='block';	
		box8.style.display='none';
		box9.style.display='none';			
		box10.style.display='none';	
		openSelectVideo();		
	}else if(x == 7){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='none';
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='none';	
		box8.style.display='block';
		box9.style.display='none';				
		box10.style.display='none';	
	}else if(x == 8){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='none';
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='none';	
		box8.style.display='none';
		box9.style.display='block';			
		box10.style.display='none';	
		openSelectGallery();					
	}else if(x == 9){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='none';
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='none';	
		box8.style.display='none';
		box9.style.display='none';			
		box10.style.display='block';	
	}else if(x == -1){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='block';
		box4.style.display='none';
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='none';			
		box10.style.display='none';	
	}else if(x == 0){
		box1.style.display='none';
		box2.style.display='none';
		box3.style.display='none';
		box4.style.display='none';	
		box5.style.display='none';	
		box6.style.display='none';
		box7.style.display='none';			
		box10.style.display='none';	
	}
}

function openNewImageItemMenu() {
	var img = document.getElementById('Select_image_item_menu');
	var img2 = document.getElementById('delete_image_item_menu');
	
	if(img.checked == true) {
		document.getElementById('new_image').style.display='block';
		img2.checked = false;
	} else {
		document.getElementById('new_image').style.display='none';	
	}
}

function deleteImageItemMenu(){
	var img = document.getElementById('delete_image_item_menu');
	var img2 = document.getElementById('Select_image_item_menu');
	
	if(img.checked == true) {
		document.getElementById('new_image').style.display='none';
		img2.checked = false;
	} 

}

function mostrar_ocultar(){
	var valor_select = document.getElementById("enlace_item").value;
	
	if((valor_select == 2) || (valor_select == 3)){
		document.getElementById('new_image').style.display='block';	
	}else{
		document.getElementById('new_image').style.display='none';	
	}			
}
