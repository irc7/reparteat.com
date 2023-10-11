function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
String.prototype.trim = function() {
	return this.replace(/^\s*|\s*$/g, "");
};

String.prototype.checkEmail = function() {
	var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	return filter.test(this);
};

function checkPwd() {
	var cp = document.getElementById('CPwd');
	var p = document.getElementById('Pwd');
	if(cp.value != p.value || p.value.trim() == "") {
		var e = 0;	
	}else{
		var e = 1;
	}
	return e;
};

function ValidaURL(url) {
	var regex=/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,3}|info|mobi|aero|asia|name)(:\d{2,5})?(\/)?((\/).+)?$/i;
	return regex.test(url);
}

var fields = new Array();
var datatype = new Array();

function resetFields() {
	fields.length = 0;
}


function includeField(id, type) {
	var o = document.getElementById(id);
	fields.push(o);
	datatype.push(type);
};

function validate(form) {
	var error = false;
	for (i = 0; i < fields.length; i++) {
		var field = fields[i];
		var type = datatype[i];
		
		var info = document.getElementById("info-" + field.id);
		
		switch (type) {
			case "string": //que una cadena no este vacia
				if (field.value.trim() == "") {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />" + field.title + " es obligatorio";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;
			case "checkbox": //que un input tipo checkbox este seleccionado
				if (!field.checked) {
					error = true;
					//alert("Debe aceptar la política de privacidad y las condiciones generales");
					field.focus();
					info.className = "error";
					break;
				} else {
					info.innerHTML = "";
					info.className = "noerror";
					break;
				}
				break;
				
			case "pwd": //dos campos contraseña de mas de 4 caracteres 
				var check = checkPwd();
				if(field.value.trim() == "" && field.value.length >= 4 && check == 1) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />" + field.title + " es obligatoria.";
					field.focus();
					info.className = "error";
				}else if (field.value.trim() != "" && field.value.length < 4 && check == 1) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />" + field.title + " debe tener m&iacute;nimo 4 carazteres.";
					field.focus();
					info.className = "error";
				}else if(field.value.trim() != "" && field.value.length >= 4 && check == 0) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />Los campos no coinciden.";
					field.focus();
					info.className = "error";
				}else {
					info.innerHTML = "";
					info.className = "noerror";
				}
			break;	
			case "email": //email válido
				if (!field.value.checkEmail()) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />" + field.title + " debe ser un e-mail v&aacute;lido<br/>";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;			
			case "number": //que lo que vienes sea un numero
				if (!isNumber(field.value)) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />" + field.title + " debe ser un n&uacute;mero";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;
				
			case "file": //algun archivo seleccionado
				if (field.value == "") {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />Debe seleccionar una imagen JPG";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;
			case "filePDF": //archivo PDF
				if (field.value == "") {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />Debe seleccionar un archivo PDF";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;
			case "selectMultiple": //select multiple no vacio
				if (field.selectedIndex==-1) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />Debe seleccionar un producto como mínimo";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;
			case "numberValue": //numero mayor que 0
				if (!isNumber(field.value) || field.value <= 0) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />" + field.title + " debe ser un n&uacute;mero correcto y mayor que 0";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;
			case "numberSelect": //select cuyo valor seleccionado sea mayor que cero
				if (!isNumber(field.value) || field.value <= 0) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' /> Debe elegir un producto para la caja " + field.title;
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;
			case "nif_cif_nie":
				if (!valida_nif_cif_nie(field.id)) {
					error = true;
					info.innerHTML = field.title + " no es un número o código válido.";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;
			case "url": //url válida
				if (!ValidaURL(field.value)) {
					error = true;
					info.innerHTML = "<img class='cp_msgicon' src='images/alert.png' alt='¡ATENCIÓN!' />" + field.title + " debe ser una url v&aacute;lida";
					field.focus();
					info.className = "error";
				} else {
					info.innerHTML = "";
					info.className = "noerror";
				}
				break;			
		}
	}
	if (error == false) {
		//alert("entra");		
		mainform.submit();
	} else {
		showloading(0);
	}
}

//VALIDAR NUMERO DE CUENTA

function validate_suscription() {
	var formSend = document.getElementById("mainform"); 
	var Iban = document.getElementById("Iban");
	var Bank = document.getElementById("Bank");
	var Office = document.getElementById("Office");
	var DC = document.getElementById("DC");
	var Account = document.getElementById("Account");

	var User = document.getElementById("UserWeb");
	var Commission = document.getElementById("Commission");
	
	var errorGlobal = 1;
	
	if(Iban.value != "" || Bank.value != "" || Office.value != "" || DC.value != "" || Account.value != "") {
		errorGlobal = ValidarIBAN(Iban.value, Bank.value,Office.value,DC.value,Account.value);
	}
	
	if (User.value == 0) {
		alert("Debe seleccionar un usuario.");
		errorGlobal = 0;
	}
	if (Commission.value == 0) {
		alert("Debe seleccionar el tipo de comisión a percebir..");
		errorGlobal = 0;
	}
	
	if (errorGlobal == 1) {
		formSend.submit();
	}else{
		showloading(0);
	}	
}

function tildes_unicode(str){
	str = str.replace('á','\u00e1');
	str = str.replace('é','\u00e9');
	str = str.replace('í','\u00ed');
	str = str.replace('ó','\u00f3');
	str = str.replace('ú','\u00fa');

	str = str.replace('Á','\u00c1');
	str = str.replace('É','\u00c9');
	str = str.replace('Í','\u00cd');
	str = str.replace('Ó','\u00d3');
	str = str.replace('Ú','\u00da');

	str = str.replace('ñ','\u00f1');
	str = str.replace('Ñ','\u00d1');
	return str;
}


//VALIDAR COMPRA CON SUSCRIPCION
function DigitoControl(cadena){
	var cifras = new Array(1,2,4,8,5,10,9,7,3,6);
	var chequeo = 0;
	for (var i=0; i < cifras.length; i++){
		chequeo += parseInt(cadena.charAt(i)) * cifras[i];
	}
	chequeo = 11 - (chequeo % 11);
	if (chequeo == 11) {chequeo = 0;}
	if (chequeo == 10) {chequeo = 1;}
	return chequeo;
}

// Funcion que comprueba que "valor" es un numero entero
function EsNumeroEntero(valor){
	var cadena = valor.toString();
	var longitud = cadena.length;
	if (longitud == 0){return false;}
	var ascii = null;
	for (var i=0; i<longitud; i++) {
		ascii = cadena.charCodeAt(i);
		if (ascii < 48 || ascii > 57){return false;}
	}
	return true;
}

// Funcion que valida el codigo de cuenta cliente
function ValidarIBAN(iban,entidad,oficina,dc,nc) {
	var error = false;
	var errorDC = false;
	var errorIBAN = false;
	
	// Comprobamos que solo hemos introducido numeros
	if (!EsNumeroEntero(entidad)){
		error = true;
		alert(tildes_unicode("Debe introducir el número de entidad bancaria"));
	}
	if (!EsNumeroEntero(oficina)){
		error = true;
		alert(tildes_unicode("Debe introducir el número de oficina"));
	}
	if (!EsNumeroEntero(dc)){
		error = true;
		alert(tildes_unicode("Debe introducir los dos dígitos de control"));
	}
	if (!EsNumeroEntero(nc)){
		error = true;
		alert(tildes_unicode("Debe introducir el número de cuenta"));
	}
// Comprobamos el primer digito de control
	var primer_control="00"+entidad+oficina;
	var primer_digito=DigitoControl(primer_control);
	if (primer_digito != dc.charAt(0)){
		alert(tildes_unicode("El código de cuenta cliente proporcionado no es válido"));
		errorDC = true;
	}
// Comprobamos el segundo digito de control
	var segundo_control=nc;
	var segundo_digito=DigitoControl(segundo_control);
	if (segundo_digito != dc.charAt(1)){
		alert(tildes_unicode("El código de cuenta cliente proporcionado no es válido"));
		errorDC = true;
	}
// Comprobamos el dígito de control IBAN	
	var iban_control = String(iban)+String(entidad)+String(oficina)+String(dc)+String(nc);
	var rest = fisIBAN(iban_control);
	if(rest != 1) {
		errorIBAN = true;
		alert(tildes_unicode("El dígito de control del IBAN es incorrecto"));
	}
	
	if(!errorDC && !error && !errorIBAN) {
		return 1;
	} else {
		return 0;
	}
}

function fisIBAN(IBAN) {
//Limpiamos el numero de IBAN
	IBAN = IBAN.toUpperCase();  //Todo a Mayus
	IBAN = trim(IBAN); //Quitamos blancos de principio y final.
	IBAN  = IBAN.replace(/\s/g, "");  //Quitamos blancos del medio.
	var letra1,letra2,num1,num2;
	var isbanaux;
	var numeroSustitucion;
	if (IBAN.length != 24) {                
		return false;
	}
// Cogemos las primeras dos letras y las pasamos a numeros
	letra1 = IBAN.substring(0, 1);
	letra2 = IBAN.substring(1, 2);
	num1 = getnumIBAN(letra1);
	num2 = getnumIBAN(letra2);
//Substituimos las letras por numeros.
	isbanaux = String(num1) + String(num2) + IBAN.substring(2, IBAN.length);
	
// Movemos los 6 primeros caracteres al final de la cadena.            
	isbanaux = isbanaux.substring(6,isbanaux.length) + isbanaux.substring(0,6);
	
	var iban1 = isbanaux.substring(0,8);
	var rest1 = iban1 % 97;
	var iban2 = String(rest1) + isbanaux.substring(8,15);
	var rest2 = iban2 % 97;
	var iban3 = String(rest2) + isbanaux.substring(15,21);
	var rest3 = iban3 % 97;
	var iban4 = String(rest3) + isbanaux.substring(21,isbanaux.length);
//Calculamos el resto          
	resto = iban4 % 97;
	//alert(resto);
	if (resto != 1) {
		return false;
	}else{
		return true;
	}
}
 
function trim(myString){
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
}

function getnumIBAN(letra){
	ls_letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';           
	return ls_letras.search(letra) + 10;
}
				
//FIN VALIDACION DEL NUMERO DE CUENTA


//VALIDAR UN DNI

// Recibe el 'id' del elemento HTML para proceder a la validación, si es correcta devuelve 'true' y sino saca un alert y devuelve 'false'
//Requiere del framework jQuery
function valida_nif_cif_nie(a) {
    var resul = true;
    var temp = jQuery('#'+a).val().toUpperCase();
    var cadenadni = "TRWAGMYFPDXBNJZSQVHLCKE";
    if (temp !== '') {
        //algoritmo para comprobacion de codigos tipo CIF
        suma = parseInt(temp[2]) + parseInt(temp[4]) + parseInt(temp[6]);
        for (i = 1; i < 8; i += 2) {
            temp1 = 2 * parseInt(temp[i]);
            temp1 += '';
            temp1 = temp1.substring(0,1);
            temp2 = 2 * parseInt(temp[i]);
            temp2 += '';
            temp2 = temp2.substring(1,2);
            if (temp2 == '') {
                temp2 = '0';
            }
            suma += (parseInt(temp1) + parseInt(temp2));
        }
        suma += '';
        n = 10 - parseInt(suma.substring(suma.length-1, suma.length));
        //si no tiene un formato valido devuelve error
        if ((!/^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$/.test(temp) && !/^[T]{1}[A-Z0-9]{8}$/.test(temp)) && !/^[0-9]{8}[A-Z]{1}$/.test(temp)) {
            if ((temp.length == 9) && (/^[0-9]{9}$/.test(temp))) {
                var posicion = temp.substring(8,0) % 23;
                var letra = cadenadni.charAt(posicion);
                var letradni = temp.charAt(8);
//                alert("La letra del NIF no es correcta. " + letradni + " es diferente a " + letra);
                jQuery('#'+a).val(temp.substr(0,8) + letra);
                resul = false;
            } else if (temp.length == 8) {
                if (/^[0-9]{1}/.test(temp)) {
                    var posicion = temp.substring(8,0) % 23;
                    var letra = cadenadni.charAt(posicion);
                    var tipo = 'NIF';
                } else if (/^[KLM]{1}/.test(temp)) {
                    var letra = String.fromCharCode(64 + n);
                    var tipo = 'NIF';
                } else if (/^[ABCDEFGHJNPQRSUVW]{1}/.test(temp)) {
                    var letra = String.fromCharCode(64 + n);
                    var tipo = 'CIF';
                } else if (/^[T]{1}/.test(temp)) {
                    var letra = String.fromCharCode(64 + n);
                    var tipo = 'NIE';
                } else if (/^[XYZ]{1}/.test(temp)) {
                    var pos = str_replace(['X', 'Y', 'Z'], ['0','1','2'], temp).substring(0, 8) % 23;
                    var letra = cadenadni.substring(pos, pos + 1);
                    var tipo = 'NIE';
                }
                if (letra !== '') {
//                    alert("Añadido la letra del " + tipo + ": " + letra);
                    jQuery('#'+a).val(temp + letra);
                } else {
//                    alert ("El CIF/NIF/NIE tiene que tener 9 caracteres");
                    jQuery('#'+a).val(temp);
                }
                resul = false;
            } else if (temp.length < 8) {
//                alert ("El CIF/NIF/NIE tiene que tener 9 caracteres");
                jQuery('#'+a).val(temp);
                resul = false;
            } else {
//                alert ("CIF/NIF/NIE incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
        //comprobacion de NIFs estandar
        else if (/^[0-9]{8}[A-Z]{1}$/.test(temp)) {
            var posicion = temp.substring(8,0) % 23;
            var letra = cadenadni.charAt(posicion);
            var letradni = temp.charAt(8);
            if (letra == letradni) {
                return 1;
            } else if (letra != letradni) {
//                alert("La letra del NIF no es correcta. " + letradni + " es diferente a " + letra);
                jQuery('#'+a).val(temp.substr(0,8) + letra);
                resul = false;
            } else {
//                alert ("NIF incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
        //comprobacion de NIFs especiales (se calculan como CIFs)
        else if (/^[KLM]{1}/.test(temp)) {
            if (temp[8] == String.fromCharCode(64 + n)) {
                return 1;
            } else if (temp[8] != String.fromCharCode(64 + n)) {
//                alert("La letra del NIF no es correcta. " + temp[8] + " es diferente a " + String.fromCharCode(64 + n));
                jQuery('#'+a).val(temp.substr(0,8) + String.fromCharCode(64 + n));
                resul = false;
            } else {
//                alert ("NIF incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
        //comprobacion de CIFs
        else if (/^[ABCDEFGHJNPQRSUVW]{1}/.test(temp)) {
            var temp_n = n + '';
            if (temp[8] == String.fromCharCode(64 + n) || temp[8] == parseInt(temp_n.substring(temp_n.length-1, temp_n.length))) {
                return 2;
            } else if (temp[8] != String.fromCharCode(64 + n)) {
//                alert("La letra del CIF no es correcta. " + temp[8] + " es diferente a " + String.fromCharCode(64 + n));
                jQuery('#'+a).val(temp.substr(0,8) + String.fromCharCode(64 + n));
                resul = false;
            } else if (temp[8] != parseInt(temp_n.substring(temp_n.length-1, temp_n.length))) {
//                alert("La letra del CIF no es correcta. " + temp[8] + " es diferente a " + parseInt(temp_n.substring(temp_n.length-1, temp_n.length)));
                jQuery('#'+a).val(temp.substr(0,8) + parseInt(temp_n.substring(temp_n.length-1, temp_n.length)));
                resul = false;
            } else {
//                alert ("CIF incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
        //comprobacion de NIEs
        //T
        else if (/^[T]{1}/.test(temp)) {
            if (temp[8] == /^[T]{1}[A-Z0-9]{8}$/.test(temp)) {
                return 3;
            } else if (temp[8] != /^[T]{1}[A-Z0-9]{8}$/.test(temp)) {
                var letra = String.fromCharCode(64 + n);
                var letranie = temp.charAt(8);
                if (letra != letranie) {
//                    alert("La letra del NIE no es correcta. " + letranie + " es diferente a " + letra);
                    jQuery('#'+a).val(temp.substr(0,8) + letra);
                    resul = false;
                } else {
//                    alert ("NIE incorrecto");
                    jQuery('#'+a).val(temp);
                    resul = false;
                }
            }
        }
        //XYZ
        else if (/^[XYZ]{1}/.test(temp)) {
            var pos = str_replace(['X', 'Y', 'Z'], ['0','1','2'], temp).substring(0, 8) % 23;
            var letra = cadenadni.substring(pos, pos + 1);
            var letranie = temp.charAt(8);
            if (letranie == letra) {
                return 3;
            } else if (letranie != letra) {
//                alert("La letra del NIE no es correcta. " + letranie + " es diferente a " + letra);
                jQuery('#'+a).val(temp.substr(0,8) + letra);
                resul = false;
            } else {
//                alert ("NIE incorrecto");
                jQuery('#'+a).val(temp);
                resul = false;
            }
        }
    }
    if (!resul) {      
        jQuery('#'+a).focus();
        return resul;
    }
}






/*recuperar contraseña*/
var fields2 = new Array();
var datatype2 = new Array();

function includeField2(id2, type2) {
	var o = document.getElementById(id2);
	fields2.push(o);
	datatype2.push(type2);
};

function insertFieldEditAddress() {
	includeField2('accept','checkbox');
	includeField2('home','string');
	includeField2('city','string');
	includeField2('province','string');
	includeField2('cp','string');
}

function validate_editAddress(form2) {
	var error = new Array();
	var errorGlobal = false;
	
	var formSend = document.getElementById("editAddressWeb"); 
	insertFieldEditAddress();
	
	for (i = 0; i < fields2.length; i++) {
		var field2 = fields2[i];
		var type2 = datatype2[i];
		
		var info2 = document.getElementById("info-" + field2.id);
		switch (type2) {
			case "string":
				if (field2.value.trim() == "") {
					error[i] = true;
					info2.innerHTML = field2.title + " es obligatorio";
					field2.focus();
					info2.className = "error";
				} else {
					error[i] = false;
					info2.innerHTML = "";
					info2.className = "noerror";
				}
			break;
			case "checkbox":
				if (!field2.checked) {
					error[i] = true;
					info2.innerHTML = "Debe aceptar la pol&iacute;tica de privacidad y las condiciones generales";
					field2.focus();
					info2.className = "error";
				} else {
					error[i] = false;
					info2.innerHTML = "";
					info2.className = "noerror";
				}
			break;
		}
		if (error[i] == true) {
			errorGlobal = true;	
		}
	}
	if (errorGlobal == false) {
		formSend.submit();
	} 
};

function insertFieldReLog() {
	includeField2('reMail','email');;
}

function validate_editReLog(form2) {
	var error = new Array();
	var errorGlobal = false;
	var formSend = document.getElementById("editReLog"); 
	
	insertFieldReLog();
	
	for (i = 0; i < fields2.length; i++) {
		var field2 = fields2[i];
		var type2 = datatype2[i];
		
		var info2 = document.getElementById("info-" + field2.id);
		switch (type2) {
			case "email":
				if (!field2.value.checkEmail()) {
					error[i] = true;
					info2.innerHTML = field2.title + " debe ser un e-mail v&aacute;lido";
					field2.focus();
					info2.className = "error";
				} else {
					error[i] = false;
					info2.innerHTML = "";
					info2.className = "noerror";
				}
				break;
		}
		if (error[i] == true) {
			errorGlobal = true;	
		}
	}
	if (errorGlobal == false) {
		formSend.submit();
	} 
};

function insertFieldReCode() {
	includeField2('reCode','string');
	includeField2('reMail','email');
}

//Codigo mail
function validate_editReCode(form2) {
	var error = new Array();
	var errorGlobal = false;
	var formSend = document.getElementById("editReCode"); 
	
	insertFieldReCode();
	
	for (i = 0; i < fields2.length; i++) {
		var field2 = fields2[i];
		var type2 = datatype2[i];
		
		var info2 = document.getElementById("info-" + field2.id);
		switch (type2) {
			case "string":
				if (field2.value.trim() == "") {
					error[i] = true;
					info2.innerHTML = field2.title + " es obligatorio";
					field2.focus();
					info2.className = "error";
				} else {
					error[i] = false;
					info2.innerHTML = "";
					info2.className = "noerror";
				}
			break;
			case "email":
				if (!field2.value.checkEmail()) {
					error[i] = true;
					info2.innerHTML = field2.title + " debe ser un e-mail v&aacute;lido";
					field2.focus();
					info2.className = "error";
				} else {
					error[i] = false;
					info2.innerHTML = "";
					info2.className = "noerror";
				}
				break;
		}
		if (error[i] == true) {
			errorGlobal = true;	
		}
	}
	if (errorGlobal == false) {
		formSend.submit();
	} 
};

//CAMBIAR CONTRASE?A

function insertFieldNewPass() {
	includeField2('passwordEdit','password');
}

function validate_newPass(form2) {
	var error = new Array();
	var errorGlobal = false;
	var formSend = document.getElementById("editPass"); 
	
	insertFieldNewPass();
	
	for (i = 0; i < fields2.length; i++) {
		var field2 = fields2[i];
		var type2 = datatype2[i];
		
		var info2 = document.getElementById("info-" + field2.id);
		switch (type2) {
			case "password":
				var pass2 = document.getElementById('passwordCopyEdit');
				if (field2.value.trim() != pass2.value.trim()) {
					error[i] = true;
					info2.innerHTML = "Las contrase&ntilde;as no coinciden";
					field2.focus();
					info2.className = "error";
				}else if((field2.value.trim() == pass2.value.trim()) && (field2.value.trim() == "" || pass2.value.trim() == "")){
					error[i] = true;
					info2.innerHTML = "La contrase&ntilde;a es obligatoria";
					field2.focus();
					info2.className = "error";
				} else {
					error[i] = false;
					info2.innerHTML = "";
					info2.className = "noerror";
				}
				break;
		}
		if (error[i] == true) {
			errorGlobal = true;	
		}
	}
	if (errorGlobal == false) {
		formSend.submit();
	} 
};