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

    $("#editar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#cedula').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar nombre");
			$('#name').parent('div').addClass('has-error');
			
        } else {

            $.post(base_url+'CPerfilSocial/update', $('#form_perfil_social').serialize(), function (response) {

				if (response == '1') {
                    swal("Disculpe,", "esta cédula se encuentra registrada");
                }else{
					swal({ 
						title: "Actualización",
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
