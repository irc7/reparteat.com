	function comprobar(){
		indice = document.getElementById("destino").selectedIndex;
		if( indice == -1) {
			alert("Debe seleccionar al menos una sección.");
		}else{
			var opt = document.mainform2.destino;
			var l = opt.length;
			for ( var i=0; i<l; i++ )
			{
			  if (indice != -1 )
			  {
				opt[i].selected = true;
			  }
			}
			showloading(1);
			mainform2.submit();
		}
	}