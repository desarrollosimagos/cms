$(document).ready(function(){
	
	//~ alert("Funcinando...");
    
    // Para marcar el menú correspondiente como activo
    if($("#ident").val().trim() != ""){
		var ident = $("#ident").val().trim();
		$("."+ident+".menu").addClass("active");  // Marca el menú con las clases identificadoras
	}
    
    // Para marcar el submenú correspondiente como activo
    if($("#ident_sub").val().trim() != ""){
		var ident_sub = $("#ident_sub").val().trim();
		$("."+ident_sub+".submenu").addClass("active");  // Marca el menú con las clases identificadoras
		$("."+ident_sub+".submenu").parent().removeClass("collapse");  // Remueve la clase 'collapse' del elemento ul padre para desplegar el submenú
	}
    
});
