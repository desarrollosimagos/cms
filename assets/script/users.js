$(document).ready(function() {
	// Capturamos la base_url
    var base_url = $("#base_url").val();
    
    
    $('#tab_users').DataTable({
       "paging": true,
       "lengthChange": true,
       "autoWidth": false,
       "searching": true,
       "ordering": true,
       "info": true,
       "iDisplayLength": 50,
       "iDisplayStart": 0,
       "sPaginationType": "full_numbers",
       "aLengthMenu": [10, 50, 100, 150],
       "oLanguage": {"sUrl": base_url+"assets/js/es.txt"},
       "aoColumns": [
           {"sClass": "registro center", "sWidth": "5%"},
           {"sClass": "registro center", "sWidth": "10%"},
           {"sClass": "registro center", "sWidth": "10%"},
           {"sClass": "registro center", "sWidth": "10%"},
           {"sClass": "registro center", "sWidth": "10%"},
           {"sClass": "none", "sWidth": "8%"},
           {"sClass": "none", "sWidth": "8%"},
           {"sClass": "none", "sWidth": "8%"},
           {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
           {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
       ]       
    });   
                
	// Función para activar/desactivar un usuario
	$("table#tab_users").on('click', 'input.activar_desactivar', function (e) {
		e.preventDefault();
		var id = this.getAttribute('id');
		//alert(id)
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'activar';
        }else{
			accion = 'desactivar';
		}
		
		swal({
			title: accion.charAt(0).toUpperCase()+accion.substring(1)+" registro",
			text: "¿Desea "+accion+" el Usuario?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: accion.charAt(0).toUpperCase()+accion.substring(1),
			cancelButtonText: "Cancelar",
			closeOnConfirm: false,
			closeOnCancel: true
		  },
		  function(isConfirm){
			if (isConfirm) {

			  $("#motivo_anulacion").val('');
				$("#accion").val(accion);
				
				var mensaje = "";
				if (accion == 'desactivar'){
					mensaje = "desactivado";
				}else{
					mensaje = "activado";
				}
				
				$.post(base_url+'CUser/update_status/' + id, {'accion':accion}, function(response) {
					swal("El usuario fue "+mensaje+" exitosamente");
					location.reload();
				})
			} 
		  });
	   
	});
        
    $('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });

    $('#volver2').click(function () {
        url = base_url+'users/';
        window.location = url;
    });
    
    $('#volver').click(function () {
        url = base_url+'users/';
        window.location = url;
    });
    
    // Función para la pre-visualización de la imagen a cargar
	$(function() {
		$('#image').change(function(e) {
			addImage(e); 
		});

		function addImage(e){
			var file = e.target.files[0],
			imageType = /image.*/;

			if (!file.type.match(imageType))
			return;

			var reader = new FileReader();
			reader.onload = fileOnload;
			reader.readAsDataURL(file);
		}
	  
		function fileOnload(e) {
			var result=e.target.result;
			$('#imgSalida').attr("src",result);
		}
	});
	
	$('#status').change(function (){
		
		$('#status').parent('div').removeClass("has-error");
	
	});
	
	// Al cargar la página validamos las acciones que se deben mostrar
	var perfil = $("#profile").find('option').filter(':selected').text();
	if(perfil == 'ADMINISTRADOR'){
		$('#admin').val(1);
	}else{
		$('#admin').val(0);
	}
	
	var perfil_id = $("#profile").val();
	var usuario_id = $("#id").val();
	if(perfil_id != '0'){
		$.post(base_url+'CUser/search_actions', $.param({'profile_id':perfil_id}), function (response) {
			//~ alert(response);
			var selectedValues = new Array();  // Arreglo donde almacenaremos los ids de las acciones a marcar
			var option = "";
			$.each(response, function (i) {
				option += "<option value=" + response[i]['id'] + ">" + response[i]['name'] + "</option>";
				if(perfil == 'ADMINISTRADOR'){
					selectedValues[i] = response[i]['id'];  // Añadimos el id de la acción a marcar
				}
			});
			$('#actions_ids').append(option);
			$('#actions_ids').select2('val', selectedValues);  // Marcamos
		}, 'json');
		// Si estamos editando un usuario buscamos las acciones asociadas a él y las añadimos a la lista
		if(usuario_id != '' && perfil != 'ADMINISTRADOR'){
			$.post(base_url+'CUser/search_actions2', $.param({'user_id':usuario_id}), function (response) {
				var selectedValues = new Array();  // Arreglo donde almacenaremos los ids de las acciones a marcar
				var option = "";
				$.each(response, function (i) {
					// Primero removemos la opción igual a la que vamos a imprimir (evitará redundancia de datos)
					$("#actions_ids option[value='"+response[i]['id']+"']").remove();
					option = "<option value=" + response[i]['id'] + ">" + response[i]['name'] + "</option>";
					$('#actions_ids').append(option);
					selectedValues[i] = response[i]['id'];  // Añadimos el id de la acción a marcar
					$('#actions_ids').select2('val', selectedValues);  // Marcamos
				});
			}, 'json');
		}
	}
	
	// Al cambiar el perfil validamos las acciones que se deben mostrar
	$('#profile').change(function (){
		
		$('#profile').parent('div').removeClass("has-error");
		
		var perfil = $("#profile").find('option').filter(':selected').text();
		if(perfil == 'ADMINISTRADOR'){
			$('#admin').val(1);
		}else{
			$('#admin').val(0);
		}
		
		var perfil_id = $("#profile").val();
		var usuario_id = $("#id").val();
		//~ $('#actions_ids').find('option:gt(0)').remove().end().select2('val', '0');
		$('#actions_ids').find('option').remove().end();
		
		if(perfil_id != '0'){
			if($('#actions_ids').val() != undefined){
				$.post(base_url+'CUser/search_actions', $.param({'profile_id':perfil_id}), function (response) {
					// alert(response);
					var selectedValues = new Array();  // Arreglo donde almacenaremos los ids de las acciones a marcar
					var option = "";
					$.each(response, function (i) {
						option += "<option value=" + response[i]['id'] + ">" + response[i]['name'] + "</option>";
						if(perfil == 'ADMINISTRADOR'){
							selectedValues[i] = response[i]['id'];  // Añadimos el id de la acción a marcar
						}
					});
					$('#actions_ids').append(option);
					$('#actions_ids').select2('val', selectedValues);  // Marcamos
				}, 'json');
				// Si estamos editando un usuario buscamos las acciones asociadas a él y las añadimos a la lista
				if(usuario_id != '' && perfil != 'ADMINISTRADOR'){
					$.post(base_url+'CUser/search_actions2', $.param({'user_id':usuario_id}), function (response) {
						var selectedValues = new Array();  // Arreglo donde almacenaremos los ids de las acciones a marcar
						var option = "";
						$.each(response, function (i) {
							// Primero removemos la opción igual a la que vamos a imprimir (evitará redundancia de datos)
							$("#actions_ids option[value='"+response[i]['id']+"']").remove();
							option = "<option value=" + response[i]['id'] + ">" + response[i]['name'] + "</option>";
							$('#actions_ids').append(option);
							selectedValues[i] = response[i]['id'];  // Añadimos el id de la acción a marcar
							$('#actions_ids').select2('val', selectedValues);  // Marcamos
						});
					}, 'json');
				}
			}
		}
	
	});

    $("#registrar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto
        // Expresion regular para validar el correo
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        if ($('#name').val().trim() === "") {

          
		   swal("Disculpe,", "para continuar debe ingresar nombre");
	       $('#name').parent('div').addClass('has-error');
        } else if ($('#alias').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe ingresar el alias");
	       $('#alias').parent('div').addClass('has-error');
		   
        } else if ($('#username').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe ingresar el nombre de usuario");
	       $('#username').parent('div').addClass('has-error');
		   
        } else if (!(regex.test($('#username').val().trim()))){
			
			swal("Disculpe,", "el usuario debe ser una dirección de correo electrónico válida");
			$('#username').parent('div').addClass('has-error');
			
		}  else if ($('#password').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe ingresar el nombre de usuario");
	       $('#password').parent('div').addClass('has-error');
		   
        } else if ($('#passw1').val().trim() === "") {
          
		   swal("Disculpe,", "debe confirmar la contraseña");
	       $('#passw1').parent('div').addClass('has-error');
		   
        }else if ($('#passw1').val().trim() != $('#password').val().trim()) {
          
		   swal("Disculpe,", "las contraseñas deben ser iguales");
	       $('#password').parent('div').addClass('has-error');
		   $('#passw1').parent('div').addClass('has-error');
		   
        } else if ($('#profile').val() == '0') {
			
		  swal("Disculpe,", "para continuar debe seleccionar el perfil");
	       $('#profile').parent('div').addClass('has-error');
		   
		} else if ($('#coin_id').val() == '0') {
			
		  swal("Disculpe,", "para continuar debe seleccionar la moneda");
	       $('#coin_id').parent('div').addClass('has-error');
		   
		} else if ($('#lang_id').val() == '0') {
			
		  swal("Disculpe,", "para continuar debe seleccionar el idioma");
	       $('#lang_id').parent('div').addClass('has-error');
		   
		} else {
            
            var formData = new FormData(document.getElementById("form_users"));  // Forma de capturar todos los datos del formulario
			
			$.ajax({
				//~ method: "POST",
				type: "post",
				dataType: "json",
				url: base_url+'CUser/add',
				data: formData,
				cache: false,
				contentType: false,
				processData: false
			})
			.done(function(response) {
				if(response.error){
					console.log(response.error);
				} else {
					if (response['response'] == 'error') {
					
						swal("Disculpe,", "este usuario se encuentra registrado");
						
					}else if (response['response'] == 'error1') {
						
						swal("Disculpe,", "ha ocurrido un error al guardar las acciones");
						
					}else if (response['response'] == 'error2') {
						
						swal("Disculpe,", "ha ocurrido un error al guardar la foto");
						
					}else{
						
						swal({ 
							title: "Registro",
							 text: "Guardado con exito",
							  type: "success" 
							},
						function(){
						  window.location.href = base_url+'users';
						});
						
					}
					
				}				
			}).fail(function() {
				console.log("error ajax");
			});
			
        }

    });
    
    $("#actions_ids").ready(function() {
		// Función para la interacción del combo select2 y la lista datatable
		$("#actions_ids").on('change', function () {
			
			var ids_actions = $(this).val();
			var data_actions = $(this).select2('data');
			
			// Comparamos las acciones del select con las de la lista y agregamos las que falten
			$.each(data_actions, function (index, value){
				// alert(index + ": " + value.id);
				var contador = 0;  // Para verificar si la acción ya está en la tabla
				$("#tab_acciones tbody tr").each(function (index){
					var id_action = $(this).find('td').eq(0).text();
				
					if(value.id == id_action){
						contador += 1;
					}
				})
				//~ alert(contador+"-"+value.text);
				// Si la acción no está en la tabla, la añadimos
				if(contador == 0){
					var table = $('#tab_acciones').DataTable();
					var id_new_action = value.id;
					var name_new_action = value.text;
					var permission_new_action = '<input type="checkbox" id="">';
					var i = table.row.add( [ id_new_action, name_new_action, permission_new_action, permission_new_action, permission_new_action ] ).draw();
					table.rows(i).nodes().to$().attr("id", $("#id").val());
				}
			});
			
			// Comparamos las acciones de la lista con las del combo select y eliminamos las que sobren
			$("#tab_acciones tbody tr").each(function (index){
				var id_action = $(this).find('td').eq(0).text();
				var contador2 = 0  // Para verificar si la acción está en la tabla
				
				// Recorremos la lista de ids capturados del combo select2
				$.each(ids_actions, function (index, value){
					if(id_action == value) {
						contador2 += 1;
					}
				})
				// Si el contador es igual a cero, significa que la acción ha sido borrada del combo select, por tanto la quitamos también de la lista
				if(contador2 == 0) {
					// Borramos la línea correspondiente (línea actual)
					var table = $('#tab_acciones').DataTable();
					table.row($(this)).remove().draw();
				}
				
			});

		});
	});
    
});

// Validamos que los archivos sean de tipo .jpg, jpeg o png
function valida_tipo(input) {
	
	var max_size = '';
	var archivo = input.val();
	
	var ext = archivo.split(".");
	ext = ext[1];
	
	if (ext != 'jpg' && ext != 'jpeg' && ext != 'png'){
		swal("Disculpe,", "sólo se admiten archivos .jpg, .jpeg y png");
		input.val('');
		input.parent('div').addClass('has-error');
	}else{
		input.parent('div').removeClass('has-error');
	}
}
