$(document).ready(function() {
	// Capturamos la base_url
    var base_url = $("#base_url").val();
    
        
    $('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });
    
    $('#volver').click(function () {
        url = base_url+'home/';
        window.location = url;
    });
    
    $('#birthday').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
        endDate: 'today'
    });

	$("#gender").select2('val', $("#id_gender").val());
	$("#lang_id").select2('val', $("#id_lang").val());
    
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
	
	// Ejecutar actualización de datos
    $("#update").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto
        // Expresion regular para validar el correo
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        if ($('#username').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe ingresar el nombre de usuario");
	       $('#username').parent('div').addClass('has-error');
		   
        } else if (!(regex.test($('#username').val().trim()))){
			
			swal("Disculpe,", "el usuario debe ser una dirección de correo electrónico válida");
			$('#username').parent('div').addClass('has-error');
			
		} else if ($('#name').val().trim() === "") {

		   swal("Disculpe,", "para continuar debe ingresar nombre");
	       $('#name').parent('div').addClass('has-error');
	       
        } else if ($('#alias').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe ingresar el alias");
	       $('#alias').parent('div').addClass('has-error');
		   
        } else if ($('#lang_id').val() == '0') {
			
		  swal("Disculpe,", "para continuar debe seleccionar el idioma");
	       $('#lang_id').parent('div').addClass('has-error');
		   
		} else {
            
            var formData = new FormData(document.getElementById("profileuser"));  // Forma de capturar todos los datos del formulario
			
			$.ajax({
				// method: "POST",
				type: "post",
				dataType: "json",
				url: base_url+'CProfileUser/update',
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
						
						swal("Disculpe,", "ha ocurrido un error al guardar la foto");
						
					}else{
						
						swal({ 
							title: "Registro",
							 text: "Guardado con exito",
							  type: "success" 
							},
						function(){
						  window.location.href = base_url+'profileuser';
						});
						
					}
				}				
			}).fail(function() {
				console.log("error ajax");
			});
			
        }

    });
    
});

function valida_cedula(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta números
    patron =/[0-9-V]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function valida_telefono(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta números
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

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
