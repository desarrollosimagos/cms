$(document).ready(function(){
	// Capturamos la base_url
    var base_url = $("#base_url").val();
	
	$('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });

    $('#volver').click(function () {
        url = base_url+'twitters/view?id='+$("#id_twitter").val()+'&screen_name='+$("#screen_name").val()+'&ruta='+$("#ruta_origen").val();
        window.location = url;
    });
    
    // Cargamos los datos de la persona usando la api de BVA
    $.get('http://consultaelectoral.bva.org.ve/cedula='+$("#cedula").val().trim()+'?format=json', function (response) {  // Se produce un error si usamos $.post en vez de $.get
		
		//~ alert(response['USD']['transferencia']);
		var cedula = response[0]['cedula'];
		var genero = response[0]['sexo'];
		var nombre = response[0]['p_nombre'] + ' ' + response[0]['s_nombre'];
		var apellido = response[0]['p_apellido'] + ' ' + response[0]['s_apellido'];
		var fecha_nac = response[0]['f_nac'];
		var centro_votacion = response[0]['c_votacion'];
		var direccion_centro_votacion = response[0]['c_direccion'];
		$("#genero").val(genero);
		$("#nombre").val(nombre);
		$("#apellido").val(apellido);
		$("#fecha_nac").val(fecha_nac);
		$("#centro_votacion").val(centro_votacion);
		$("#direccion_centro_votacion").val(direccion_centro_votacion);
		
	}, 'json');
	

    $("#registrar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#cedula').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar nombre");
			$('#name').parent('div').addClass('has-error');
			
        } else {

            $.post(base_url+'CPerfilSocial/add', $('#form_perfil_social').serialize(), function (response) {

				if (response == 'existe') {
                    swal("Disculpe,", "esta cédula se encuentra registrada");
                }else{
					swal({ 
						title: "Registro",
						text: "Guardado con exito",
						type: "success" 
					},
					function(){
						url = base_url+'twitters/view?id='+$("#id_twitter").val()+'&screen_name='+$("#screen_name").val()+'&ruta='+$("#ruta_origen").val();
						window.location = url;
					});

				}

            });
            
        }
    });
	
});
