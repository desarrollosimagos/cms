$(document).ready(function() {
	// Capturamos la base_url
    var base_url = $("#base_url").val();    
        
    $('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });
    
    $('#date').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
        endDate: 'today'
    });
    
    // Funciones para calcular el monto total de la transacción según los contratos seleccionados
    // Función para sumar el monto del contrato marcado al total
    $("table#tab_contracts").on('ifChecked', 'input.checkbox', function (e) {
		
		// Valor actual del total
		var total = $("span#total").text().trim();
		
		if(total != ''){
			total = parseFloat(total)
		}else{
			total = 0;
		}
		
		var tr_padre = $(this).parent().parent().parent().parent().parent();  // Subimos tantos niveles hasta llegar al tr
		
		var monto_contrato = tr_padre.find('td').eq(4).text().trim();  // Capturamos el valor de la columna 'Monto'
		
		monto_contrato = monto_contrato.split(' ');  // Filtrado de moneda
		
		monto_contrato = monto_contrato[0];
		
		// Cálculo del nuevo total
		total += parseFloat(monto_contrato);
		
		// Imprimimos el nuevo total
		$("span#total").text(total);
		
	});
    // Función para restar el monto del contrato desmarcado al total
    $("table#tab_contracts").on('ifUnchecked', 'input.checkbox', function (e) {
		
		// Valor actual del total
		var total = $("span#total").text().trim();
		
		if(total != ''){
			total = parseFloat(total)
		}else{
			total = 0;
		}
		
		var tr_padre = $(this).parent().parent().parent().parent().parent();  // Subimos tantos niveles hasta llegar al tr
		
		var monto_contrato = tr_padre.find('td').eq(4).text().trim();  // Capturamos el valor de la columna 'Monto'
		
		monto_contrato = monto_contrato.split(' ');  // Filtrado de moneda
		
		monto_contrato = monto_contrato[0];
		
		// Cálculo del nuevo total
		if(total > 0){
			total -= parseFloat(monto_contrato);
		}
		
		// Imprimimos el nuevo total
		$("span#total").text(total);
		
	});
	
	// Mostramos la ventana modal para la preselección y precarga de datos
	$("#pay").click(function (e) {
		
		e.preventDefault();  // Para evitar que se envíe por defecto
		
		var total = $("span#total").text();
		
		$("#amount").val(total.trim());
		
		$("#modal_pago").modal('show');
		
	});
	
	// Ejecutar actualización de datos
    $("#pay_excute").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto
        // Expresion regular para validar el correo
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        // Expresion regular para validar el dni
		var RegExPattern = /^(V|E){1}(-){1}([0-9]){8}$/;

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
		   
		} else if (!($('#dni').val().match(RegExPattern)) && ($('#dni').val() != '')) {
			
		  swal("Disculpe,", "La cédula de identidad debe tener el formato V-00000000 sin puntos.");
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
						  window.location.href = base_url+'investments';
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
