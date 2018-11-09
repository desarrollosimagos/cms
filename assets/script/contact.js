$(document).ready(function() {
	// Capturamos la base_url
    var base_url = $("#base_url").val();
    var language = $("#language").val();
    
    // Soporte de multi-idioma para los mensajes a de alerta y confimación:
    if(language == "" || language == "spanish"){
		var confirm_title = "¡Confirmación exitosa!";
		var confirm_text = "Puede iniciar sesión";
		var alert_add_client1_title1 = "Disculpe,";
		var alert_add_client1_title2 = "Registro";
		var alert_add_client1 = "para continuar debe ingresar el nombre de usuario";
		var alert_add_client2 = "el usuario debe ser una dirección de correo electrónico válida";
		var alert_add_client3 = "para continuar debe ingresar una contraseña";
		var alert_add_client4 = "debe confirmar la contraseña";
		var alert_add_client5 = "las contraseñas deben ser iguales";
		var alert_add_client6 = "para continuar debe ingresar el nombre";
		var alert_add_client7 = "para continuar debe ingresar el apellido";
		var alert_add_client8 = "para continuar debe indicar un teléfono principal";
		var alert_add_client9 = "para continuar debe marcar el boton de captcha";
		var alert_add_client10 = "este nombre se encuentra registrado";
		var alert_add_client11 = "Guardado con exito. Le será enviado un correo de confirmación, por favor revise su bandeja de entrada.";
		var alert_login1 = "para continuar debe ingresar el nombre de usuario";
		var alert_login2 = "para continuar debe ingresar una contraseña";
	}else if(language == "english"){
		var confirm_title = "¡Confirmación exitosa!";
		var confirm_text = "¡Confirmación exitosa!";
		var alert_add_client1_title1 = "Disculpe,";
		var alert_add_client1_title2 = "Registro";
		var alert_add_client1 = "para continuar debe ingresar el nombre de usuario";
		var alert_add_client2 = "el usuario debe ser una dirección de correo electrónico válida";
		var alert_add_client3 = "para continuar debe ingresar una contraseña";
		var alert_add_client4 = "debe confirmar la contraseña";
		var alert_add_client5 = "las contraseñas deben ser iguales";
		var alert_add_client6 = "para continuar debe ingresar el nombre";
		var alert_add_client7 = "para continuar debe ingresar el apellido";
		var alert_add_client8 = "para continuar debe indicar un teléfono principal";
		var alert_add_client9 = "para continuar debe marcar el boton de captcha";
		var alert_add_client10 = "este nombre se encuentra registrado";
		var alert_add_client11 = "Guardado con exito. Le será enviado un correo de confirmación, por favor revise su bandeja de entrada.";
		var alert_login1 = "para continuar debe ingresar el nombre de usuario";
		var alert_login2 = "para continuar debe ingresar una contraseña";
	}else if(language == "portuguese"){
		var confirm_title = "¡Confirmación exitosa!";
		var confirm_text = "¡Confirmación exitosa!";
		var alert_add_client1_title1 = "Disculpe,";
		var alert_add_client1_title2 = "Registro";
		var alert_add_client1 = "para continuar debe ingresar el nombre de usuario";
		var alert_add_client2 = "el usuario debe ser una dirección de correo electrónico válida";
		var alert_add_client3 = "para continuar debe ingresar una contraseña";
		var alert_add_client4 = "debe confirmar la contraseña";
		var alert_add_client5 = "las contraseñas deben ser iguales";
		var alert_add_client6 = "para continuar debe ingresar el nombre";
		var alert_add_client7 = "para continuar debe ingresar el apellido";
		var alert_add_client8 = "para continuar debe indicar un teléfono principal";
		var alert_add_client9 = "para continuar debe marcar el boton de captcha";
		var alert_add_client10 = "este nombre se encuentra registrado";
		var alert_add_client11 = "Guardado con exito. Le será enviado un correo de confirmación, por favor revise su bandeja de entrada.";
		var alert_login1 = "para continuar debe ingresar el nombre de usuario";
		var alert_login2 = "para continuar debe ingresar una contraseña";
	}
    
	//Envío de mensaje
	$("#send_message").click(function (e) {
		//~ alert($('#g-recaptcha-response').val()); // El campo g-recaptcha-response es añadido automáticamente por el plugin de google
		e.preventDefault();  // Para evitar que se envíe por defecto
		// Expresion regular para validar el correo
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        if ($('#name').val().trim() == "") {
          
		   swal("Disculpe,", "para continuar debe ingresar su nombre");
	       $('#name').parent('div').addClass('has-error');
	       $('#name').focus();
		   
        }  else if ($('#email').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe ingresar una dirección de correo electrónico");
	       $('#email').parent('div').addClass('has-error');
	       $('#email').focus();
		   
        } else if (!(regex.test($('#email').val().trim()))){
			
			swal("Disculpe,", "debe indicar una dirección de correo electrónico válida");
			$('#email').parent('div').addClass('has-error');
			$('#email').focus();
			
		} else if ($('#subject').val().trim() === "") {
          
		   swal("Disculpe,", "debe colocar un asunto");
	       $('#subject').parent('div').addClass('has-error');
	       $('#subject').focus();
		   
        } else if ($('#message').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe escribir un mensaje");
	       $('#message').parent('div').addClass('has-error');
	       $('#message').focus();
	       
        } else {
			$.post(base_url+'send_message', $('#contactForm').serialize(), function (response) {
				//~ alert(response);
				if (response != 'PasóPasó') {
					swal("Disculpe,", "ha ocurrido algo inesperado, consulte con el administrador del sistema");
					$('#contactError').removeClass('hidden');
					$('#name').focus();
				} else {
					swal({
						title: "Mensaje",
						text: "Enviado con exito. Le agrademos por tomarse el tiempo para escribirnos.",
						type: "success"
					},
					function () {
						//~ $("#modal_cliente").modal('hide');
						//~ window.location.href = base_url+'public_perfil';
						$('#name').val('');
						$('#email').val('');
						$('#subject').val('');
						$('#message').val('');
						$('#contactSuccess').removeClass('hidden');
						$('#name').focus();
					});
				}
			});
		}
	});
});
