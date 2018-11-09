$(document).ready(function() {
	// Capturamos la base_url
    var base_url = $("#base_url").val();
    
    $('#year').datepicker({

		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		endDate: 'today',
		autoclose: true,
		
	});
	
    $('#w4-fecha').datepicker({

		format: "dd/mm/yyyy",
		//~ endDate: 'today',
		startDate: 'today',  // Permite seleccionar fechas desde el día actual en adelante
		//~ minDate: "-1D",
		//~ maxDate: "-1D",
		language: "es",
		autoclose: true,
		
	});
    
    // Aplicación de restricción numérica a los campos que lo requieran
    $("#phone, #cell_phone, #zip, #phone_1, #cell_phone_1").numeric();
    
    // Abrir modal vehículo
	$(".add_vehiculo").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_vehiculo").modal('show');
		//~ $("span#titulo").text('Registrar');
		$("#titulo_v1").show();
		$("#titulo_v2").hide();
		$("#send_v1").show();
		$("#send_v2").hide();
		$("#accion2").val('Registrar');
		// Vaciamos los campos
		$("#trademark").val('');
		$("#model").val('');
		$("#color").val('');
		$("#year").val('');
		$("#license_plate").val('');

	});
	
    // Al cambiar la selección del vehículo
	$("#vehiculo").change(function () {
		if($(this).val() != '0'){
			$.get(base_url+'CClientPublic/ajax_car_id/' + $(this).val() + '', function (data) {
				var option = "";
				$.each(data, function (i) {
					$("#w4-marca").val(data[i]['trademark']);
					$("#w4-modelo").val(data[i]['model']);
					$("#w4-color").val(data[i]['color']);
					$("#w4-anyo").val(data[i]['year']);
				});
				
			}, 'json');
		}else{
			$("#w4-marca").val('');
			$("#w4-modelo").val('');
			$("#w4-color").val('');
			$("#w4-anyo").val('');
		}
	});
	
    // Al cambiar la selección de la franquicia
	$("#franquicia").change(function () {
		if($(this).val() != '0'){
			//~ $('#services_ids').find('option:gt(0)').remove().end().select2('val', '0');
			$('#services_ids').find('option').remove().end();
			$.get(base_url+'CClientPublic/ajax_services_franchise_id/' + $(this).val() + '', function (data) {
				var option = "";
				$.each(data, function (i) {
					option += "<option value=" + data[i]['service_id'] + ">" + data[i]['name'] + "</option>";
				});

				$('#services_ids').append(option);
				
			}, 'json');
		}
	});
	
    // Al cambiar la selección de los servicios
	$("#services_ids").on('change',function () {
		var sub_total = 0;
		var iva = 12;  // Valor estático temporalmente
		var impuesto = 0;
		var total = 0;
		if($(this).val() != ""){
			$.each($(this).select2('data'), function (index, value){
				
				//~ alert(""+index+" - "+value.id+"vdf");
				$.get(base_url+'CClientPublic/ajax_service_id/' + value.id + '', function (data) {
					$.each(data, function (i) {
						
						sub_total += parseFloat(data[i]['price']);
						impuesto = parseFloat(sub_total) * parseFloat(iva) / 100;
						total = parseFloat(sub_total) + parseFloat(impuesto);
						
						//~ alert("Sub-total: "+sub_total+" - Impuesto: "+impuesto+" - Total: "+total);
						
						$("#sub_total").val(parseFloat(sub_total));
						$("#impuesto").val(parseFloat(impuesto));						
						$("#total").val(parseFloat(total));
						
					});
					
				}, 'json');
			});
		}else{
			$("#sub_total").val(parseFloat(sub_total));
			$("#impuesto").val(parseFloat(impuesto));						
			$("#total").val(parseFloat(total));
		}
		
	});

	// Agregar vehículo nuevo
	$("#add_vehicle").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		if ($('#trademark').val().trim() == "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar la marca");
		   $('#model').removeClass('error');
		   $('#color').removeClass('error');
		   $('#year').removeClass('error');
		   $('#license_plate').removeClass('error');
		   $('#trademark').addClass('error');
		   $('#trademark').focus();
		   
		} else if ($('#model').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar el modelo");
		   $('#trademark').removeClass('error');
		   $('#color').removeClass('error');
		   $('#year').removeClass('error');
		   $('#license_plate').removeClass('error');
		   $('#model').addClass('error');
		   $('#model').focus();
		   
		} else if ($('#color').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar el color");
		   $('#trademark').removeClass('error');
		   $('#model').removeClass('error');
		   $('#year').removeClass('error');
		   $('#license_plate').removeClass('error');
		   $('#color').addClass('error');
		   $('#color').focus();
		   
		} else if ($('#year').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar el año");
		   $('#trademark').removeClass('error');
		   $('#color').removeClass('error');
		   $('#model').removeClass('error');
		   $('#license_plate').removeClass('error');
		   $('#year').addClass('error');
		   $('#year').focus();
		   
		} else if ($('#license_plate').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar la placa");
		   $('#trademark').removeClass('error');
		   $('#color').removeClass('error');
		   $('#year').removeClass('error');
		   $('#model').removeClass('error');
		   $('#license_plate').addClass('error');
		   $('#license_plate').focus();
		   
		}else{
			$.post(base_url+'CClientPublic/addCar', $('#form_vehiculo').serialize()+'&'+$.param({'customer_id2':$('#customer_id').val()}), function (response) {
				//~ alert(response);
				if (response == 'existe vehiculo') {
					swal("Disculpe,", "este vehículo se encuentra registrado");
				} else {
					swal({
						title: "Registro",
						text: "Guardado con exito.",
						type: "success"
					},
					function () {
						$("#modal_vehiculo").modal('hide');
						//~ window.location.href = base_url+'public_perfil';
						var cliente_id = $('#customer_id').val();
						$('#vehiculo').find('option:gt(0)').remove().end().select2('val', '0');
						$.get(base_url+'CClientPublic/ajax_car/' + cliente_id + '', function (data) {
							var option = "";
							$.each(data, function (i) {
								option += "<option value=" + data[i]['id'] + ">" + data[i]['license_plate'] + ' - ' + data[i]['trademark'] + "</option>";
							});

							$('#vehiculo').append(option);
							
						}, 'json');
					});
				}
			});
		}
	});
    
    // Abrir modal dirección
	$(".add_direccion").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto

		$("#modal_direccion").modal('show');
		//~ $("span#titulo").text('Registrar');
		$("#titulo_a1").show();
		$("#titulo_a2").hide();
		$("#send_a1").show();
		$("#send_a2").hide();
		$("#accion").val('Registrar');
		// Vaciamos los campos
		$("#city").val('');
		$("#zip").val('');
		$("#description").val('');
		$("#address_1").val('');
		$("#address_2").val('');
		$("#phone_1").val('');
		$("#cell_phone_1").val('');

	});
	
    // Al cambiar la selección de la dirección
	$("#address").change(function () {
		if($(this).val() != '0'){
			$.get(base_url+'CClientPublic/ajax_address_id/' + $(this).val() + '', function (data) {
				var option = "";
				$.each(data, function (i) {
					$("#w4-ciudad").val(data[i]['city']);
					$("#w4-telefono").val(data[i]['phone']);
					$("#w4-movil").val(data[i]['cell_phone']);
					$("#w4-descripcion").val(data[i]['description']);
					$("#w4-zip").val(data[i]['zip']);
				});
				
			}, 'json');
		}else{
			$("#w4-ciudad").val('');
			$("#w4-telefono").val('');
			$("#w4-movil").val('');
			$("#w4-descripcion").val('');
			$("#w4-zip").val('');
		}
	});

	// Agregar dirección nueva
	$("#add_address").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		
		if ($('#city').val().trim() == "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar el nombre de la ciudad");
		   $('#zip').removeClass('error');
		   $('#address_1').removeClass('error');
		   $('#phone_1').removeClass('error');
		   $('#city').addClass('error');
		   $('#city').focus();
		   
		} else if ($('#zip').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar el código postal");
		   $('#city').removeClass('error');
		   $('#address_1').removeClass('error');
		   $('#phone_1').removeClass('error');
		   $('#zip').addClass('error');
		   $('#zip').focus();
		   
		} else if ($('#address_1').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar la dirección");
		   $('#zip').removeClass('error');
		   $('#city').removeClass('error');
		   $('#phone_1').removeClass('error');
		   $('#address_1').addClass('error');
		   $('#address_1').focus();
		   
		} else if ($('#phone_1').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar el teléfono");
		   $('#zip').removeClass('error');
		   $('#address_1').removeClass('error');
		   $('#city').removeClass('error');
		   $('#phone_1').removeClass('error');
		   $('#phone_1').addClass('error');
		   
		}else{
			$.post(base_url+'CClientPublic/addAddressPublic', $('#form_direccion').serialize()+'&'+$.param({'customer_id':$('#customer_id').val()}), function (response) {
				//~ alert(response);
				if (response == 'existe direccion') {
					swal("Disculpe,", "esta dirección se encuentra registrada");
				} else {
					swal({
						title: "Registro",
						text: "Guardado con exito.",
						type: "success"
					},
					function () {
						$("#modal_direccion").modal('hide');
						//~ window.location.href = base_url+'public_perfil';
						var cliente_id = $('#customer_id').val();
						$('#address').find('option:gt(0)').remove().end().select2('val', '0');
						$.get(base_url+'CClientPublic/ajax_address/' + cliente_id + '', function (data) {
							var option = "";
							$.each(data, function (i) {
								option += "<option value=" + data[i]['id'] + ">" + data[i]['city'] + ' - ' + data[i]['address_1'] + "</option>";

							});

							$('#address').append(option);

						}, 'json');
					});
				}
			});
		}
	});
	
	// Al hacer click en el checkbox de terminos de uso asignamos 1 o 0 al campo oculto terms_accept
	$("#w4-terms").click(function (e) {
		if($(this).is(':checked')){
			$("#terms_accept").val('1');
		}else{
			$("#terms_accept").val('0');
		}

	});
	
	// Validamos los campos al enviar la solicitud
	$("#finalizar").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		if ($('#vehiculo').val() == "0") {
		  
		   swal("Disculpe,", "para continuar debe seleccionar el vehículo");
		   $('#franquicia').removeClass('error');
		   $('#services_ids').removeClass('error');
		   $('#address').removeClass('error');
		   $('#w4-fecha').removeClass('error');
		   $('#terms_accept').removeClass('error');
		   $('#vehiculo').addClass('error');
		   $('#vehiculo').focus();
		   
		} else if ($('#franquicia').val() == "0") {
		  
		   swal("Disculpe,", "para continuar debe seleccionar la franquicia");
		   $('#vehiculo').removeClass('error');
		   $('#services_ids').removeClass('error');
		   $('#address').removeClass('error');
		   $('#w4-fecha').removeClass('error');
		   $('#terms_accept').removeClass('error');
		   $('#franquicia').addClass('error');
		   $('#franquicia').focus();
		   
		} else if ($('#services_ids').val() == "") {
		  
		   swal("Disculpe,", "para continuar debe seleccionar el(los) servicio(s)");
		   $('#franquicia').removeClass('error');
		   $('#vehiculo').removeClass('error');
		   $('#address').removeClass('error');
		   $('#w4-fecha').removeClass('error');
		   $('#terms_accept').removeClass('error');
		   $('#services_ids').addClass('error');
		   $('#services_ids').focus();
		   
		} else if ($('#address').val() == "0") {
		  
		   swal("Disculpe,", "para continuar debe seleccionar la dirección");
		   $('#franquicia').removeClass('error');
		   $('#services_ids').removeClass('error');
		   $('#vehiculo').removeClass('error');
		   $('#w4-fecha').removeClass('error');
		   $('#terms_accept').removeClass('error');
		   $('#address').addClass('error');
		   $('#address').focus();
		   
		} else if ($('#w4-fecha').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar la fecha del servicio");
		   $('#franquicia').removeClass('error');
		   $('#services_ids').removeClass('error');
		   $('#address').removeClass('error');
		   $('#vehiculo').removeClass('error');
		   $('#terms_accept').removeClass('error');
		   $('#w4-fecha').addClass('error');
		   $('#w4-fecha').focus();
		   
		} else if ($('#terms_accept').val().trim() == "" || $('#terms_accept').val().trim() == "0") {
		  
		   swal("Disculpe,", "para continuar debe aceptar los terminos del servicio");
		   $('#franquicia').removeClass('error');
		   $('#services_ids').removeClass('error');
		   $('#address').removeClass('error');
		   $('#w4-fecha').removeClass('error');
		   $('#vehiculo').removeClass('error');
		   $('#terms_accept').addClass('error');
		   $('#terms_accept').focus();
		   
		}else{
			//~ alert("Registrando...");
			$.post(base_url+'COrderPublic/add_public', $('#form_solicitud').serialize()+'&'+$.param({'servicios':$('#services_ids').val()}), function (response) {
				//~ alert(response);
				if (response == 'existe') {
                    swal("Disculpe,", "este nombre de perfil se encuentra registrado");
                    $('#name').parent('div').addClass('has-error');
                }else{
					swal({ 
						title: "Solicitud",
						 text: "Realizada con exito",
						  type: "success" 
						},
					function(){
						window.location.href = base_url+'public_perfil';
					});
				}
            });
		}
	});

});
        
   

