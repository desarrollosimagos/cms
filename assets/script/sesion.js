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
		var confirm_title = "Confirmation successful!";
		var confirm_text = "You can log in";
		var alert_add_client1_title1 = "Sorry,";
		var alert_add_client1_title2 = "Registry";
		var alert_add_client1 = "to continue you must enter the username";
		var alert_add_client2 = "the user must be a valid email address";
		var alert_add_client3 = "to continue you must enter a password";
		var alert_add_client4 = "you must confirm the password";
		var alert_add_client5 = "passwords must be the same";
		var alert_add_client6 = "to continue you must enter the name";
		var alert_add_client7 = "to continue, you must enter the last name";
		var alert_add_client8 = "to continue you must indicate a primary phone";
		var alert_add_client9 = "to continue you must press the captcha button";
		var alert_add_client10 = "this name is registered";
		var alert_add_client11 = "Saved successfully. A confirmation email will be sent to you, please check your inbox.";
		var alert_login1 = "to continue you must enter the username";
		var alert_login2 = "to continue you must enter a password";
	}else if(language == "portuguese"){
		var confirm_title = "Confirmação de sucesso!";
		var confirm_text = "Você pode fazer logon";
		var alert_add_client1_title1 = "Desculpe,";
		var alert_add_client1_title2 = "Inscrição";
		var alert_add_client1 = "para continuar a introduzir o nome de utilizador";
		var alert_add_client2 = "o usuário deve ser um endereço de email válido";
		var alert_add_client3 = "para continuar você deve digitar uma senha";
		var alert_add_client4 = "é necessário confirmar a senha";
		var alert_add_client5 = "as senhas devem ser iguais";
		var alert_add_client6 = "para continuar a introduzir o nome";
		var alert_add_client7 = "para continuar a introduzir o nome";
		var alert_add_client8 = "para continuar a indicar um telefone principal";
		var alert_add_client9 = "para continuar a marcar o botão captcha";
		var alert_add_client10 = "esse nome é registrado";
		var alert_add_client11 = "Salvo com sucesso. Você será enviado um e-mail de confirmação, verifique sua caixa de entrada.";
		var alert_login1 = "para continuar a introduzir o nome de utilizador";
		var alert_login2 = "para continuar você deve digitar uma senha";
	}
    
    // Verificamos si se ha recibido alguna confirmación en la capa para tal fin (li_confirm)
    //~ alert($("li#li_confirm span div.li_confirm").text().trim());
    var confirm = $("li#li_confirm span div.li_confirm").text().trim();
    if(confirm == '1'){
		swal({
			title: confirm_title,
			text: confirm_text,
			type: "success"
		},
		function () {
			$("#modal_cliente").modal('show');
			// Definimos el título y la acción
			//~ $("span#titulo").text('Iniciar sesión');
			$("#titulo1").show();
			$("#titulo2").hide();
			$("#accion").val('Iniciar');
			// Mostramos y ocultamos los campos y textos necesarios
			$("label.username").css('display','block');
			$("#username").css('display','block');
			$("label.password").css('display','block');
			$("#password").css('display','block');
			$("label.confirm").css('display','none');
			$("#confirm").css('display','none');
			$("label.name").css('display','none');
			$("#name").css('display','none');
			$("label.lastname").css('display','none');
			$("#lastname").css('display','none');
			$("label.phone").css('display','none');
			$("#phone").css('display','none');
			$("label.cell_phone").css('display','none');
			$("#cell_phone").css('display','none');
			$("#question_account").css('display','block');
			$("#reg_client").css('display','block');
			$("#hidden_reg_client").css('display','none');
			// Mostramos y ocultamos los botones necesarios
			$("#add_client").css('display','none');
			$("#iniciar").css('display','block');
			// Ocultamos el div del recapcha
			$("#recapcha").css('display','none');
			$("#form_client").attr("action", "login_public");
		});
	}
    
	//abrir modal de registro/inicio
	$("#inicio,span a.solicitar").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_cliente").modal('show');  // Mostramos la modal
		// Definimos el título y la acción
		//~ $("span#titulo").text('Iniciar sesión');
		$("#titulo1").show();
		$("#titulo2").hide();
		$("#accion").val('Iniciar');
		// Mostramos y ocultamos los campos y textos necesarios
		$("label.username").css('display','block');
		$("#username").css('display','block');
		$("label.password").css('display','block');
		$("#password").css('display','block');
		$("label.confirm").css('display','none');
		$("#confirm").css('display','none');
		$("label.name").css('display','none');
		$("#name").css('display','none');
		$("label.lastname").css('display','none');
		$("#lastname").css('display','none');
		$("label.phone").css('display','none');
		$("#phone").css('display','none');
		$("label.cell_phone").css('display','none');
		$("#cell_phone").css('display','none');
		$("#question_account").css('display','block');
		$("#reg_client").css('display','block');
		$("#hidden_reg_client").css('display','none');
		// Mostramos y ocultamos los botones necesarios
		$("#add_client").css('display','none');
		$("#iniciar").css('display','block');
		// Ocultamos el div del recapcha
		$("#recapcha").css('display','none');
		$("#form_client").attr("action", "login_public");
	});
	
	//Preparar modal de registro de usuario cliente
	$("#reg_client").click(function (e) {
		// Definimos el título y la acción
		//~ $("span#titulo").text('Registrar cliente');
		$("#titulo1").hide();
		$("#titulo2").show();
		$("#accion").val('Registrar');
		// Mostramos y ocultamos los campos necesarios
		$("label.username").css('display','block');
		$("#username").css('display','block');
		$("label.password").css('display','block');
		$("#password").css('display','block');
		$("label.confirm").css('display','block');
		$("#confirm").css('display','block');
		$("label.name").css('display','block');
		$("#name").css('display','block');
		$("label.lastname").css('display','block');
		$("#lastname").css('display','block');
		$("label.phone").css('display','block');
		$("#phone").css('display','block');
		$("label.cell_phone").css('display','block');
		$("#cell_phone").css('display','block');
		$("#username").focus();
		$("#question_account").css('display','none');
		$("#reg_client").css('display','none');
		$("#hidden_reg_client").css('display','block');
		// Mostramos y ocultamos los botones necesarios
		$("#add_client").css('display','block');
		$("#iniciar").css('display','none');
		// Mostramos el div del recapcha
		$("#recapcha").css('display','block');
		$("#form_client").attr("action", "");
	});
	
	//Preparar modal de inicio de sesión
	$("#hidden_reg_client").click(function (e) {
		// Definimos el título y la acción
		//~ $("span#titulo").text('Iniciar sesión');
		$("#titulo1").show();
		$("#titulo2").hide();
		$("#accion").val('Registrar');
		// Mostramos y ocultamos los campos necesarios
		$("label.username").css('display','block');
		$("#username").css('display','block');
		$("label.password").css('display','block');
		$("#password").css('display','block');
		$("label.confirm").css('display','none');
		$("#confirm").css('display','none');
		$("label.name").css('display','none');
		$("#name").css('display','none');
		$("label.lastname").css('display','none');
		$("#lastname").css('display','none');
		$("label.phone").css('display','none');
		$("#phone").css('display','none');
		$("label.cell_phone").css('display','none');
		$("#cell_phone").css('display','none');
		$("#username").focus();
		$("#question_account").css('display','block');
		$("#reg_client").css('display','block');
		$("#hidden_reg_client").css('display','none');
		// Mostramos y ocultamos los botones necesarios
		$("#add_client").css('display','none');
		$("#iniciar").css('display','block');
		// Ocultamos el div del recapcha
		$("#recapcha").css('display','none');
		$("#form_client").attr("action", "login_public");
	});
	
	$('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });
    
    $("#phone,#cell_phone").numeric(); // Sólo permite valores numéricos
	
	//Registar usuario cliente nuevo
	$("#add_client").click(function (e) {
		//~ alert($('#g-recaptcha-response').val()); // El campo g-recaptcha-response es añadido automáticamente por el plugin de google
		e.preventDefault();  // Para evitar que se envíe por defecto
		// Expresion regular para validar el correo
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        if ($('#username').val().trim() == "") {
          
		   swal(alert_add_client1_title1, alert_add_client1);
	       $('#username').parent('div').addClass('has-error');
	       $('#username').focus();
		   
        } else if (!(regex.test($('#username').val().trim()))){
			
			swal(alert_add_client1_title1, alert_add_client2);
			$('#username').parent('div').addClass('has-error');
			
		}  else if ($('#password').val().trim() === "") {
          
		   swal(alert_add_client1_title1, alert_add_client3);
	       $('#password').parent('div').addClass('has-error');
		   
        } else if ($('#confirm').val().trim() === "") {
          
		   swal(alert_add_client1_title1, alert_add_client4);
	       $('#confirm').parent('div').addClass('has-error');
		   
        }else if ($('#confirm').val().trim() != $('#password').val().trim()) {
          
		   swal(alert_add_client1_title1, alert_add_client5);
	       $('#password').parent('div').addClass('has-error');
		   $('#confirm').parent('div').addClass('has-error');
		   
        } else if ($('#name').val().trim() === "") {
          
		   swal(alert_add_client1_title1, alert_add_client6);
	       $('#name').parent('div').addClass('has-error');
	       
        } else if ($('#lastname').val().trim() === "") {
          
		   swal(alert_add_client1_title1, alert_add_client7);
	       $('#lastname').parent('div').addClass('has-error');
		   
        } else if ($('#phone').val() == '') {
			
		  swal(alert_add_client1_title1, alert_add_client8);
	       $('#phone').parent('div').addClass('has-error');
		   
		} else if ($('#g-recaptcha-response').val() == '') {
			
		  swal(alert_add_client1_title1, alert_add_client9);
		   
		} else {
			$.post(base_url+'CClientPublic/add3', $('#form_client').serialize(), function (response) {
				//~ alert(response);
				if (response[0] == '1') {
					swal(alert_add_client1_title1, alert_add_client10);
				} else {
					swal({
						title: alert_add_client1_title2,
						text: alert_add_client11,
						type: "success"
					},
					function () {
						$("#modal_cliente").modal('hide');
						window.location.href = base_url+'public_perfil';
					});
				}
			});
		}
	});
	
	//Inicio de sesión de cliente registrado
	$("#iniciar").click(function (e) {
		//~ alert($('#g-recaptcha-response').val()); // El campo g-recaptcha-response es añadido automáticamente por el plugin de google
		e.preventDefault();  // Para evitar que se envíe por defecto
		// Expresion regular para validar el correo
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        if ($('#username').val().trim() == "") {
          
		   swal(alert_add_client1_title1, alert_login1);
	       $('#username').parent('div').addClass('has-error');
	       $('#username').focus();
		   
        }/* else if (!(regex.test($('#username').val().trim()))){
			
			swal(alert_add_client1_title1, "el usuario debe ser una dirección de correo electrónico válida");
			$('#username').parent('div').addClass('has-error');
			
		}*/  else if ($('#password').val().trim() === "") {
          
		   swal(alert_add_client1_title1, alert_login2);
	       $('#password').parent('div').addClass('has-error');
		   
        }/* else if ($('#g-recaptcha-response').val() == '') {
			
		  swal(alert_add_client1_title1, "para continuar debe marcar el boton de captcha");
		   
		}*/ else {
			//Fijamos la localización de solicitudes
			var lugar = String(window.location);
			if(lugar.indexOf("solicitud") > -1){
				$('#location').val('solicitud');
			}else{
				$('#location').val('');
			}
			// Enviamos el formulario
			$('#form_client').submit();
		}
	});
	
	//abrir modal de registro/inicio
	$("#cerrar").click(function (e) {
		window.location.href = base_url+'logout_public';
	});
});
        
   
