
//Export suscription

function comprobar(){
	indice = document.getElementById("destino").selectedIndex;
	if( indice == -1) {
		alert("Debe seleccionar al menos un grupo para exportar.");
	}else{
		var opt = document.mainform.destino;
		var l=opt.length;
		for ( var i=0; i<l; i++ )
		{
		  if (indice != -1 )
		  {
			opt[i].selected = true;
		  }
		}
		validate(this);
	}
}

//create & edit mailer

function openSMTP() {
	var obj = document.getElementById('boxSMPT');
	var box = document.getElementById('boxConfig');
	var opt = jQuery("#selectType").val();
	//if(obj.style.display == 'none') {
	if(opt == "smtp" || opt == "mandrill") {
		obj.style.display = 'block';
		box.style.height = '280px';
	} else {
		obj.style.display = 'none';
		box.style.height = '100px';
	}
}

function includeMailer() {
	resetFields();
	includeField('MailFrom','email');
	includeField('NameFrom','string');
	includeField('MailSends','number');
	includeField('MailTime','number');
	if(jQuery("#selectType").val() == "smtp" && jQuery("#selectType").val() == "mandrill") {
		includeField('User','string');
		includeField('Host','string');
		includeField('Pass','string');
	}	
}

//Create newsletter

var $a = jQuery.noConflict();
$a(document).ready(function(){
	$a( "#date_day" ).datepicker({
		dateFormat: "dd-mm-yy",
		defaultDate: "+1w",
		changeMonth: true,
		areaOfMonths: 1
	});
});
			
function changeTypeNewsletter(type) {
	$a("#typeNewsletter").val(type);
	if(type === "article") {
		$a("#selectArticles").show();
		$a("#selectFreeText").hide();
		$a('#menuArticle').css('opacity', '1');
		$a('#menuFree').css('opacity', '0.5');
	} else if(type === "free") {
		$a("#selectArticles").hide();
		$a("#selectFreeText").show();
		$a('#menuArticle').css('opacity', '0.5');
		$a('#menuFree').css('opacity', '1');
	}
}

function viewLayout(){
	var form = document.getElementById('mainform');
	form.action='modules/newsletter/layout_newsletter.php';	
	form.target = '_blank';
	showloading(0);
	form.submit();
}

function includeFieldAux() {
	resetFields();
	includeField('Subject','string');
	var form = document.getElementById('mainform');
	form.target = '_self';
	form.action='modules/newsletter/save_newsletter.php';	
	if(document.getElementById("typeNewsletter").value == "free") {
		includeField('freeCode','string');
	} else {
		
		
		
	}
	isChecked('groups');
}


function isChecked(name){
	var chks = document.getElementsByName(name+'[]');
	var enc = 0;
	for (var i = 0; i < chks.length ; i++){
		if ((chks[i].checked)){
			enc = 1;
			break;
		}
	}
	
	if(enc == 1) {
		includeField('Subject','string');
		showloading(1);
		validate('mainform');
	}else if (enc == 2){
		showloading(0);
		alert("Ha seleccionado un producto repetido, seleccione 4 diferentes para poder continuar.");
	} else {
		showloading(0);
		alert("Debe seleccionar al menos un grupo para el envío.");
	}
}

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
				
				










