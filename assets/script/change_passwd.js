$(document).ready(function() {
	// Capturamos la base_url
    var base_url = $("#base_url").val();
    
    $('#volver').click(function () {
        url = base_url;
        window.location = url;
    });
    
    // Al cambiar las nuevas contraseñas
    $("#new_passwd, #confirm_new_passwd").change(function (e) {
		
		if ($('#new_passwd').val().trim() !== "" && $('#confirm_new_passwd').val().trim() !== "") {
		
			if ($('#new_passwd').val().trim() !== $('#confirm_new_passwd').val().trim()) {
				
				swal("Disculpe,", "las contraseñas no coinciden, vuelva a intentarlo");
				$('#new_passwd').parent('div').addClass('has-error');
				$('#confirm_new_passwd').parent('div').addClass('has-error');
				$('#new_passwd').focus();
				
			}
			
		}
		
	});
		

    $("#cambiar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#passwd_actual').val().trim() === "") {

		   swal("Disculpe,", "para continuar debe ingresar su contraseña actual");
	       $('#passwd_actual').parent('div').addClass('has-error');
	       $('#passwd_actual').focus();
	       
        } else if ($('#new_passwd').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe ingresar su nueva contraseña");
	       $('#new_passwd').parent('div').addClass('has-error');
	       $('#new_passwd').focus();
		   
        } else if ($('#confirm_new_passwd').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe repetir su nueva contraseña");
	       $('#confirm_new_passwd').parent('div').addClass('has-error');
	       $('#confirm_new_passwd').focus();
		   
        } else {

            $.post(base_url+'users/update_passwd', $('#change_passwd').serialize(), function (response) {

				if (response['response'] == 'error') {
					
                    swal("Disculpe,", "las contraseñas no coinciden, vuelva a intentarlo");
                    
                }else{
					
					swal({
						title: "Actualizar",
						text: "Guardado con exito",
						type: "success" 
					},
					function(){
						window.location.href = base_url;
					});
					
				}

            }, 'json');
        }

    });
    
});
