<script type="text/javascript">
	tinyMCE.init({
		directionality: "ltr",
		editor_selector : "spl_editable",
		language : "es",
		mode : "specific_textareas",
		skin : "default",
		theme : "advanced",
		// Cleanup/Output
		inline_styles : true,
		gecko_spellcheck : true,
		cleanup : true,
		cleanup_on_startup : true,
		plugins : "table",
		theme_advanced_buttons3_add : "tablecontrols",
		table_styles : "Header 1=header1;Header 2=header2;Header 3=header3",
		table_cell_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Cell=tableCel1",
		table_row_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1",
		table_cell_limit : 100,
		table_row_limit : 5,
		table_col_limit : 5,
		entity_encoding : "raw",
		extended_valid_elements : "hr[id|title|alt|class|width|size|noshade|style],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],a[id|class|name|href|target|title|onclick|rel|style]",
		extended_valid_elements : "marquee[class|width|height|align|onmouseover|onmouseout|behavior|direction|scrollamount|scrolldelay|bgcolor]",
		extended_valid_elements : "embed[type|width|height|src|*]",
		force_br_newlines : false, force_p_newlines : true, forced_root_block : 'div',
		invalid_elements : "applet",
		// URL
		relative_urls : false,
		remove_script_host : false,
		document_base_url : "<?php echo DOMAIN; ?>",
		// Layout
		content_css : "<?php echo DOMAIN;?>pdc-reparteat/css/editor/editor.css",
		// Advanced theme
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,styleselect,formatselect,|,link, unlink,|,",
		theme_advanced_buttons1_add : "bullist,numlist,separator,outdent,indent,separator,undo,redo,|,table,removeformat,code",
	  //  theme_advanced_buttons3 : "hr,removeformat,visualaid,separator,sub,sup,separator,charmap",
		//Estilos: 
		theme_advanced_styles : "Naranja=orange;Gris Suave=grayLight;Gris Normal=grayNormal;Gris Oscuro=grayStrong;Negro:black;Blanco:white",
		//theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_source_editor_height : "260",
		theme_advanced_source_editor_width : "690",
		theme_advanced_statusbar_location : "bottom", 
		theme_advanced_path : true,
		keep_styles: false,
		invalid_styles: "font-size"
	});
</script>