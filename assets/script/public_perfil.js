$(document).ready(function () {
	// Capturamos la base_url
    var base_url = $("#base_url").val();
			
	$('#year').datepicker({

		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		endDate: 'today',
		autoclose: true,
		
	});
	
	//Propiedades para la lista de direcciones
	$('#tab_direccion').DataTable({
		"paging": true,
		"lengthChange": false,
		"autoWidth": false,
		"searching": true,
		"info": true,
		"order": [[0, "asc"]],
		"iDisplayLength": 5,
		"iDisplayStart": 0,
		"sPaginationType": "full_numbers",
		"aLengthMenu": [5, 10, 15],
		"oLanguage": {"sUrl": base_url+"assets/js/es.txt"},
		"aoColumns": [
			{"sWidth": "15%"},
			{"sWidth": "15%"},
			{"sWidth": "20%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
			{"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
		]
	});
	
	//Propiedades para la lista de vehículos
	$('#tab_vehiculo').DataTable({
		"paging": true,
		"lengthChange": false,
		"autoWidth": false,
		"searching": true,
		"info": true,
		"order": [[0, "asc"]],
		"iDisplayLength": 5,
		"iDisplayStart": 0,
		"sPaginationType": "full_numbers",
		"aLengthMenu": [5, 10, 15],
		"oLanguage": {"sUrl": base_url+"assets/js/es.txt"},
		"aoColumns": [
			{"sWidth": "15%"},
			{"sWidth": "15%"},
			{"sWidth": "15%"},
			{"sWidth": "15%"},
			{"sWidth": "15%"},
			{"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
			{"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
		]
	});
	
	//Propiedades para la lista de pedidos (ordenes)
	$('#tab_order').DataTable({
		"paging": true,
		"lengthChange": false,
		"autoWidth": false,
		"searching": true,
		"info": true,
		dom: '<"html5buttons"B>lTfgitp',

		buttons: [
			{extend: 'copy'},
			{extend: 'csv'},
			{extend: 'excel', title: 'ExampleFile'},
			{extend: 'pdf', title: 'ExampleFile'},

			{extend: 'print',
				customize: function (win) {
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');

					$(win.document.body).find('table')
							.addClass('compact')
							.css('font-size', 'inherit');
				}
			}
		],
		"iDisplayLength": 5,
		"iDisplayStart": 0,
		"sPaginationType": "full_numbers",
		"aLengthMenu": [5, 10, 15],
		"oLanguage": {"sUrl": base_url+"assets/js/es.txt"},
		"aoColumns": [
			{"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "8%"},
			{"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "none", "sWidth": "8%"},
			{"sClass": "registro center", "sWidth": "5%"},
		]
	});
	
	// Acciones sobre direcciones y vehículos
	
	$("#phone, #cell_phone, #zip, #phone_1, #cell_phone_1").numeric(); // Sólo permite valores numéricos
	
	$("#i_new_line").click(function (e) {
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
	
	$("#i_new_line2").click(function (e) {
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
	
	$(".editar_direccion").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_direccion").modal('show');
		//~ $("span#titulo").text('Editar');
		$("#titulo_a1").hide();
		$("#titulo_a2").show();
		$("#send_a1").hide();
		$("#send_a2").show();
		$("#accion").val('Editar');
		// Construimos el id del campo oculto que contiene los datos de la línea  a partir de la clase y el id del link clickeado
		var id_values_line = "#"+$(this).attr('class')+"_"+$(this).attr('id')
		// Ahora separamos los valores y los ubicamos en los campos correspondientes
		var values_line = $(id_values_line).val();
		values_line = values_line.split(';');
		$("#id_direccion").val(values_line[0]);
		$("#city").val(values_line[1]);
		$("#zip").val(values_line[2]);
		$("#description").val(values_line[3]);
		$("#address_1").val(values_line[4]);
		$("#address_2").val(values_line[5]);
		$("#phone_1").val(values_line[6]);
		$("#cell_phone_1").val(values_line[7]);				
	});
	
	$(".editar_vehiculo").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_vehiculo").modal('show');
		//~ $("span#titulo").text('Editar');
		$("#titulo_v1").hide();
		$("#titulo_v2").show();
		$("#send_v1").hide();
		$("#send_v2").show();
		$("#accion2").val('Editar');
		// Construimos el id del campo oculto que contiene los datos de la línea  a partir de la clase y el id del link clickeado
		var id_values_line = "#"+$(this).attr('class')+"_"+$(this).attr('id')
		// Ahora separamos los valores y los ubicamos en los campos correspondientes
		var values_line = $(id_values_line).val();
		values_line = values_line.split(';');
		$("#id_vehiculo").val(values_line[0]);
		$("#trademark").val(values_line[1]);
		$("#model").val(values_line[2]);
		$("#color").val(values_line[3]);
		$("#year").val(values_line[4]);
		$("#license_plate").val(values_line[5]);
	});
	
	$(".editar_telefonos").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		$("#modal_telefonos").modal('show');
		//~ $("span#titulo").text('Editar');
		$("#accion3").val('Editar');
		$("#phone_2").val($("#span_phone").text());
		$("#cell_phone_2").val($("#span_cellphone").text());
	});
	
	//Registar dirección nueva
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
		   
		   
		} else {
			if($("#accion").val() == 'Registrar'){
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
							window.location.href = base_url+'public_perfil';
						});
					}
				});
			}else if($("#accion").val() == 'Editar'){
				$.post(base_url+'CClientPublic/updateAddressPublic', $('#form_direccion').serialize()+'&'+$.param({'customer_id':$('#customer_id').val()}), function (response) {
					//~ alert(response);
					if (response == 'existe direccion') {
						swal("Disculpe,", "esta dirección se encuentra registrada");
					} else {
						swal({
							title: "Edición",
							text: "Actualizado con exito.",
							type: "success"
						},
						function () {
							$("#modal_direccion").modal('hide');
							window.location.href = base_url+'public_perfil';
						});
					}
				});
			}
		}
	});
	
	//Registar vehículo nuevo
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
		   
		} else {
			if($("#accion2").val() == 'Registrar'){
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
							window.location.href = base_url+'public_perfil';
						});
					}
				});
			}else if($("#accion2").val() == 'Editar'){
				$.post(base_url+'CClientPublic/updateCarPublic', $('#form_vehiculo').serialize()+'&'+$.param({'customer_id2':$('#customer_id').val()}), function (response) {
					//~ alert(response);
					if (response == 'existe vehiculo') {
						swal("Disculpe,", "este vehículo se encuentra registrado");
					} else {
						swal({
							title: "Edición",
							text: "Actualizado con exito.",
							type: "success"
						},
						function () {
							$("#modal_vehiculo").modal('hide');
							window.location.href = base_url+'public_perfil';
						});
					}
				});
			}
		}
	});
	
	//Editar teléfonos
	$("#edit_phones").click(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto

		if ($('#phone_2').val().trim() == "" && $('#cell_phone_2').val().trim() === "") {
		  
		   swal("Disculpe,", "para continuar debe ingresar un teléfono");
		   $('#phone').addClass('error');
		   $('#cell_phone').addClass('error');
		   
		} else {
			$.post(base_url+'CClientPublic/update_phones', $('#form_phones').serialize()+'&'+$.param({'customer_id3':$('#customer_id').val()}), function (response) {
				//~ alert(response);
				swal({
					title: "Edición",
					text: "Actualizado con exito.",
					type: "success"
				},
				function () {
					// Recarga de datos con ajax
					$.post(base_url+'CClientPublic/ajax_client2/'+$('#customer_id').val(), function (response2) {
						//~ alert(response2);
						$.each(response2, function (i) {
							$('#span_phone').text(response2[i]['phone']);
							$('#span_cellphone').text(response2[i]['cell_phone']);
						});
					}, 'json');
					$("#modal_telefonos").modal('hide');
					//~ window.location.href = base_url+'public_perfil';
				});
			});
		}
	});
	
	$("table#tab_direccion").on('click', 'a.quitar_direccion', function (e) {
		e.preventDefault();
				
		var id = this.getAttribute('id');  // Capturamos el id del link cliqueado

		swal({
			title: "Borrar registro",
			text: "¿Está seguro de borrar la dirección?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Eliminar",
			cancelButtonText: "Cancelar",
			closeOnConfirm: false,
			closeOnCancel: true
		},
		function(isConfirm){
			if (isConfirm) {

				$.post(base_url+'CClientPublic/deleteAddressPublic/' + id + '', function (response) {
					if (response == 'existe address') {
						swal("Disculpe,", "esta dirección está asociada a algúna orden");
					} else {
						swal({ 
						title: "Eliminar",
						 text: "Dirección eliminada con exito",
						  type: "success" 
						},
						function(){
							window.location.href = base_url+'public_perfil';
							//~ // Capturamos el tr padre (subimos dos nivel por encima hasta llegar al tr)
							//~ var parent = $(this).parent().parent().index();
							//~ // Eliminamos la línea con datatable sin recargar la página
							//~ var table = $('#tab_direccion').DataTable();
							//~ table.row(parent).remove().draw();
						});
					}
				});
			} 
		});
	});
	
	$("table#tab_vehiculo").on('click', 'a.quitar_vehiculo', function (e) {
		e.preventDefault();
		var id = this.getAttribute('id');

		swal({
			title: "Borrar registro",
			text: "¿Está seguro de borrar el vehículo?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Eliminar",
			cancelButtonText: "Cancelar",
			closeOnConfirm: false,
			closeOnCancel: true
		},
		function(isConfirm){
			if (isConfirm) {

				$.post(base_url+'CClientPublic/deleteCarPublic/' + id + '', function (response) {
					if (response == 'existe vehicle') {
						swal("Disculpe,", "este vehículo está asociado a algúna orden");
					} else {
						swal({ 
						title: "Eliminar",
						 text: "Vehículo eliminado con exito",
						  type: "success" 
						},
						function(){
							window.location.href = base_url+'public_perfil';
							//~ // Capturamos el tr padre (subimos dos nivel por encima hasta llegar al tr)
							//~ var parent = $(this).parent().parent().index();
							//~ // Eliminamos la línea con datatable sin recargar la página
							//~ var table = $('#tab_vehiculo').DataTable();
							//~ table.row(parent).remove().draw();
						});
					}
				});
			} 
		});
	});
	
})
