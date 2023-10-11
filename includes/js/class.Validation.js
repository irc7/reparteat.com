var Validation = function(options) {
	
	this.form = options.form;
	if (options.send) {
		this.send = options.send;
	} else {
		this.send = true;
	}
	if (options.callback) {
		this.callback = options.callback;
	} else {
		this.callback = function(){};
	}
	this.fields = options.fields;
	this.ok = true;
	
	this.isOK = function() {
		return this.ok;
	};
	
	var _self = this;
	this.form.onsubmit = function() {
		_self.ok = true;
		for (var i = 0; i < _self.fields.length; i++) {
			
			var field = document.getElementById(_self.fields[i].id);
			var type = _self.fields[i].type;
			if(type != "boolean") {
				var value = field.value.trim();
				var min = _self.fields[i].min;
				var max = _self.fields[i].max;
			}
			var errorObj = document.getElementById("error-" + field.id);
			
			switch (type) {
				case "string":
					if (value == "" || value.length < min || value.length > max) {
						_self.ok = false;
						errorObj.className = "error visible";
						errorObj.innerHTML = "El campo <strong>" + field.title + "</strong> es obligatorio y debe tener entre " + min + " y " + max + " caracteres.";
						field.classList.add("validation-form-error");
						field.focus();
					} else {
						errorObj.className = "oculto";
						field.classList.remove("validation-form-error");
					}
					break;
				case "selectString":
					if (value == "" || value.length < min || value.length > max) {
						_self.ok = false;
						errorObj.className = "error visible";
						errorObj.innerHTML = "Debe seleccionar un <strong>" + field.title + "</strong>.";
						field.classList.add("validation-form-error");
						field.focus();
					} else {
						errorObj.className = "oculto";
						field.classList.remove("validation-form-error");
					}
					break;
				case "email":
					if (!value.checkEmail() || value.length < min || value.length > max) {
						_self.ok = false;
						errorObj.className = "error visible";
						errorObj.innerHTML = "El campo <strong>" + field.title + "</strong> es obligatorio y debe ser un e-Mail válido.";
						field.classList.add("validation-form-error");
						field.focus();
					} else {
						errorObj.className = "oculto";
						field.classList.remove("validation-form-error");
					}
					break;
				case "dni":
					if (!value.checkDNI() || value.length != max) {
						_self.ok = false;
						errorObj.className = "error visible";
						errorObj.innerHTML = "El campo <strong>" + field.title + "</strong> es obligatorio y debe ser un D.N.I. válido.";
						field.classList.add("validation-form-error");
						field.focus();
					} else {
						errorObj.className = "oculto";
						field.classList.remove("validation-form-error");
					}
					break;
				case "number":
					if (!value.isNumber() || value < min || value > max) {
						_self.ok = false;
						errorObj.className = "error visible";
						errorObj.innerHTML = "El campo <strong>" + field.title + "</strong> es obligatorio y debe ser un número entre " + min + " y " + max + ".";
						field.classList.add("validation-form-error");
						field.focus();
					} else {
						errorObj.className = "error oculto";
						field.classList.remove("validation-form-error");
					}
					break;
				case "selectNumber":
					if (!value.isNumber() || value == 0) {
						_self.ok = false;
						errorObj.className = "error visible";
						errorObj.innerHTML = "Debe seleccionar un <strong>" + field.title + "</strong>.";
						field.classList.add("validation-form-error");
						field.focus();
					} else {
						errorObj.className = "error oculto";
						field.classList.remove("validation-form-error");
					}
					break;
				case "boolean":
					if (!field.checked) {
						_self.ok = false;
						errorObj.className = "error visible";
						errorObj.innerHTML = "Debe aceptar la <strong>" + field.title + "</strong>.";
						field.classList.add("validation-form-error");
						field.focus();
					} else {
						errorObj.className = "error oculto";
						field.classList.remove("validation-form-error");
					}
					break;
				case "password":
					if (value == "" || value.length < min) {
						_self.ok = false;
						errorObj.className = "error visible";
						errorObj.innerHTML = "El campo <strong>" + field.title + "</strong> es obligatorio y debe tener más de " + min + " caracteres.";
						field.classList.add("validation-form-error");
						field.focus();
					} else {
						var nameRepeat = field.name + "Repeat";
						var fieldRepeat = document.getElementById(nameRepeat);
						var valRepeat = fieldRepeat.value.trim();
						if(value != valRepeat) {
							_self.ok = false;
							errorObj.className = "error visible";
							errorObj.innerHTML = "Las contraseñas no coinciden.";
							field.classList.add("validation-form-error");
							field.focus();
						}else {
							errorObj.className = "oculto";
							field.classList.remove("validation-form-error");
						}
					}
					break;
			}
			
		}
		
		if (_self.ok) {
			if (_self.send) {
				_self.form.submit();
			} else {
				_self.callback();
			}
		}
		return false;
	};
	
};
