String.prototype.trim = function() {
	return this.replace(/^\s*|\s*$/g, '');
};

String.prototype.checkEmail = function() {
	var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	if (this.match(filter)) {
		return true;
	} else {
		return false;
	}
};

String.prototype.checkDNI = function() {
	var n = this.substr(0, this.length-1);
	var l = this.substr(this.length-1,1);
	n = n % 23;
	var llist = 'TRWAGMYFPDXBNJZSQVHLCKET';
	var character = llist.substring(n, n + 1);
	if (character != l) {
		return false;
	} else {
		return true;
	}
}

String.prototype.isNumber = function() {
	return !isNaN(this) && this != "";
};


function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function checkFileZp(id){
	var res = false;
	var fileInput = document.getElementById(id);
	var filePath = fileInput.value;
    var allowedExtensions = /(.xls|.xlsx|.doc|.docx|.csv|.pdf)$/i;
	if(allowedExtensions.exec(filePath) && filePath.trim() != ""){
        res = true;
    }
    return res;
}
function checkImageZp(id){
	var res = false;
	var fileInput = document.getElementById(id);
	var filePath = fileInput.value;
    var allowedExtensions = /(.png|.jpg|.jpeg|.gif)$/i;
	if(allowedExtensions.exec(filePath) && filePath.trim() != ""){
        res = true;
    }
    return res;
	
}
$(document).ready(function(){
	$('#cv').bind('change', function() {
		if(window.File && window.FileReader && window.FileList && window.Blob){
			var size = this.files[0].size / 1024;
			size = Math.ceil(size / 1024);
		}else{
		// IE
			var Fs = new ActiveXObject("Scripting.FileSystemObject");
			var ruta = document.upload.file.value;
			var archivo = Fs.getFile(ruta);
			var size = archivo.size;
			size = this.files[0].size / 1024;
			size = Math.ceil(size / 1024);
		}
		extC = new Array("application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/msword", "application/pdf"); 
		var isValid = false; 
		for (var i = 0; i < extC.length; i++) { 
			if (extC[i] == this.files[0].type) { 
				isValid = true; 
				break; 
			} 
		} 
		if (!isValid) { 
			$("#error-cv").addClass("error");
			$("#error-cv").removeClass("oculto");
			$("#error-cv").addClass("visible");
			$("#error-cv").html("El archivo seleccionado no tiene un formato válido.");
			$("#cv").addClass("validation-form-error");
			this.focus();
			$("#btn-work").attr("disabled", true);
		}else{ 
			if(size <= 2) {
				$("#error-cv").addClass("error");
				$("#error-cv").removeClass("visible");
				$("#error-cv").addClass("oculto");
				$("#cv").removeClass("validation-form-error");
				$("#btn-work").attr("disabled", false);
			}else{
				$("#error-cv").addClass("error");
				$("#error-cv").removeClass("oculto");
				$("#error-cv").addClass("visible");
				$("#error-cv").html("El archivo seleccionado es demasiado grande");
				$("#cv").addClass("validation-form-error");
				this.focus();
				$("#btn-work").attr("disabled", true);
			}
		} 
	});
});