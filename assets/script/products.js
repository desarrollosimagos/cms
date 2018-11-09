$(document).ready(function(){
	// Capturamos la base_url
    var base_url = $("#base_url").val();
	
	var tabProducts = $('#tab_productos').DataTable({
        //~ "paging": true,
        //~ "lengthChange": false,
        "autoWidth": false,
        //~ "searching": true,
        //~ "ordering": true,
        //~ "info": true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
			"method":"POST",
			"url": base_url+"productos_json"
		},
		"columnDefs": [
			{
				//~ "target": [0, 3, 4],
				"orderable":false,
			}
		],
        //~ "iDisplayLength": 50,
        //~ "iDisplayStart": 0,
        //~ "sPaginationType": "full_numbers",
        //~ "aLengthMenu": [50, 100, 150],
        "oLanguage": {"sUrl": base_url+"assets/js/es.txt"},
        //~ "columns": [
			//~ {"data":"nombre"},
			//~ {"data":"referencia"},
			//~ {"data":"costo_dolar"},
			//~ {"data":"costo_bolivar"},
			//~ {"data":"name"},
			//~ {"data":"modificado"},
			//~ {"data":"descripcion"},
			//~ {"data":"c_compra"},
			//~ {"data":"c_vende"},
			//~ {"data":"c_fabrica"}
        //~ ],
        "aoColumns": [
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "20%"},
            {"sClass": "none", "sWidth": "20%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    
    // Activando/Desactivando filtro de registros sin imágenes
    $("#sin_imagenes").on('click', function (e) {
		var check = $(this);
		
		var accion = '';
		if (check.is(':checked')) {
            // "Activando filtro de registros sin imágenes"
            if($("#sin_tiendasv").is(':checked')){
				tabProducts.ajax.url( base_url+"productos_json4" ).load();
			}else{
				tabProducts.ajax.url( base_url+"productos_json2" ).load();
			}
        }else{
			// "Desactivando filtro de registros sin imágenes"
			if($("#sin_tiendasv").is(':checked')){
				tabProducts.ajax.url( base_url+"productos_json3" ).load();
			}else{
				tabProducts.ajax.url( base_url+"productos_json" ).load();
			}
		}
	});
    
    // Activando/Desactivando filtro de registros sin tiendas virtuales
    $("#sin_tiendasv").on('click', function (e) {
		var check = $(this);
		
		var accion = '';
		if (check.is(':checked')) {
            // "Activando filtro de registros sin tiendas virtuales"
            if($("#sin_imagenes").is(':checked')){
				tabProducts.ajax.url( base_url+"productos_json4" ).load();
			}else{
				tabProducts.ajax.url( base_url+"productos_json3" ).load();
			}
        }else{
			// "Desactivando filtro de registros sin tiendas virtuales"
			if($("#sin_imagenes").is(':checked')){
				tabProducts.ajax.url( base_url+"productos_json2" ).load();
			}else{
				tabProducts.ajax.url( base_url+"productos_json" ).load();
			}
		}
	});
    
    
    // Cargamos el precio actual del dólar en el campo oculto 'precio_dolar'
    $.get('https://s3.amazonaws.com/dolartoday/data.json', function (response) {  // Se produce un error si usamos $.post en vez de $.get
		//~ alert(response['USD']['transferencia']);
		var precio_dolar = response['USD']['transferencia'];
		$("#precio_dolar").val(precio_dolar);
		$("#label_precio_dolar").text("**Precio actual del dólar("+precio_dolar+")");
	}, 'json');
             
    // Validacion para borrar
    $("table#tab_productos").on('click', 'a.borrar', function (e) {
        e.preventDefault();
        var id = this.getAttribute('id');

        swal({
            title: "Borrar registro",
            text: "¿Está seguro de borrar el registro?",
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
             
                $.post(base_url+'productos/delete/' + id + '', function (response) {

                    if (response[0] == "e") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede eliminar se encuentra asociado a un usuario",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    }else{
                         swal({ 
                           title: "Eliminar",
                            text: "Registro eliminado con exito",
                             type: "success" 
                           },
                           function(){
                             window.location.href = base_url+'productos';
                         });
                    }
                });
            } 
        });
    });
    
    // Función para marcar/desmarcar los inputs y volver editable o no las celdas de Costo en Dólares y Costo en Bolívares
	$("table#tab_productos").on('change', 'input#check_all', function (e) {
		e.preventDefault();
		
		var check = $(this);
		
		//~ alert(check.prop('checked'));  // Saber si el campo está marcado o no
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'marcar';
            check.prop("checked", "checked");  // Marcamos nuevamente el checkbox
            // Recorremos la tabla marcando todos los checkbox y convirtiendo las columnas de costos en inputs editables
            $("#tab_productos tbody tr").each(function () {
				$(this).find('td').find('input').prop("checked", "checked");
				var id_dl = $(this).find('span').eq(0).attr('id');  // Id de la columna de dólares
				id_dl = id_dl.replace("checkbox", "input");
				var id_new_input_dl = "id='"+id_dl+"'"  // Asignamos un id al input compuesto del id del td padre pero reemplazando el prefijo 'checkbox' por 'input'
				var id_bs = $(this).find('span').eq(1).attr('id');  // Id de la columna de bolívares
				id_bs = id_bs.replace("checkbox", "input");
				var id_new_input_bs = "id='"+id_bs+"'"  // Asignamos un id al input compuesto del id del td padre pero reemplazando el prefijo 'checkbox' por 'input'
				// Campos de costos en dólares
				var valor_dl = $(this).find('span').eq(0).text();
				if(valor_dl == ""){
					valor_dl = $(this).find('span').eq(0).find('input').val();
					$(this).find('span').eq(0).html("<input "+id_new_input_dl+" type='text' value='"+valor_dl.trim()+"' size='8px' onChange='referenciar(\""+id_dl+"\")'>");
					$(this).find('span').eq(0).find('input').numeric();
				}else{
					$(this).find('span').eq(0).html("<input "+id_new_input_dl+" type='text' value='"+valor_dl.trim()+"' size='8px' onChange='referenciar(\""+id_dl+"\")'>");
					$(this).find('span').eq(0).find('input').numeric();
				}
				// Campos de costos en bolívares
				var valor_bs = $(this).find('span').eq(1).text();
				if(valor_bs == ""){
					valor_bs = $(this).find('span').eq(1).find('input').val()
					$(this).find('span').eq(1).html("<input "+id_new_input_bs+" type='text' value='"+valor_bs.trim()+"' size='8px'>");
					$(this).find('span').eq(1).find('input').numeric();
				}else{
					$(this).find('span').eq(1).html("<input "+id_new_input_bs+" type='text' value='"+valor_bs.trim()+"' size='8px'>");
					$(this).find('span').eq(1).find('input').numeric();
				}
			});
        }else{
			accion = 'desmarcar';
			//~ alert(accion);
			check.prop("checked", "");  // Desmarcamos nuevamente el checkbox
			// Recorremos la tabla desmarcando todos los checkbox y convirtiendo las columnas de costos en texto plano
            $("#tab_productos tbody tr").each(function () {
				$(this).find('td').find('input').prop("checked", "");
				// Campos de costos en dólares				
				var valor_dl = $(this).find('span').eq(0).find('input').val();
				//~ alert(valor_dl);
				$(this).find('span').eq(0).text(valor_dl);
				// Campos de costos en bolívares
				var valor_bs = $(this).find('span').eq(1).find('input').val();
				//~ alert(valor_bs);
				$(this).find('span').eq(1).text(valor_bs);
			});
		}
	});
	
	// Función para marcar/desmarcar un input seleccionado y volver editable o no las celdas de Costo en Dólares y Costo en Bolívares correspondiente
	$("table#tab_productos").on('change', 'input.check', function (e) {
		e.preventDefault();
        var id = this.getAttribute('id');
        var id_column = id+"_column";
        var column = $("#"+id_column);
        var id_column_dl = id+"_column_dl";
        var column_dl = $("#"+id_column_dl);
                
        //~ alert(id_column);
        
        // Ids para los inputs de edición de montos
        var id_dl = id_column_dl.replace("checkbox", "input");  // Id de la columna de dólares		
		var id_new_input_dl = "id='"+id_dl+"'"  // Asignamos un id al input compuesto del id del checkbox marcado pero reemplazando el prefijo 'checkbox' por 'input'
		var id_bs = id_column.replace("checkbox", "input");  // Id de la columna de bolívares		
		var id_new_input_bs = "id='"+id_bs+"'"  // Asignamos un id al input compuesto del id del checkbox marcado pero reemplazando el prefijo 'checkbox' por 'input'
		
        var check = $(this);
		
		//~ alert(check.prop('checked'));  // Saber si el campo está marcado o no
		
		var accion = '';
		if (check.is(':checked')) {
            accion = 'marcar';
            //~ alert(accion);
            check.prop("checked", "checked");  // Marcamos nuevamente el checkbox
            // Volvemos editable las columnas correspondientes
            // Columna de precio en dólares
			var valor_dl = column_dl.text();
            column_dl.html("<input "+id_new_input_dl+" type='text' value='"+valor_dl.trim()+"' size='8px' onChange='referenciar(\""+id_dl+"\")'>");
            column_dl.find('input').numeric();
			// Columna de precio en bolívares
			var valor_bs = column.text();
            column.html("<input "+id_new_input_bs+" type='text' value='"+valor_bs.trim()+"' size='8px'>");
            column.find('input').numeric();
        }else{
			accion = 'desmarcar';
			//~ alert(accion);
			check.prop("checked", "");  // Desmarcamos nuevamente el checkbox
			// Volvemos no editable las columnas correspondientes
			// Columna de precio en dólares
			var valor_dl = column_dl.find('input').val();
            column_dl.text(valor_dl);
            // Columna de precio en bolívares
			var valor_bs = column.find('input').val();
            column.text(valor_bs);
		}
	});
	
	// Proceso de referenciación de montos según el precio actual del dólar 
	$("#referenciar").on('click', function (e) {
		var num_checked = 0;  // Contador de checkbox marcados
		var precio_dolar = $("#precio_dolar").val();  // Capturamos el precio del dólar previamente cargado en el campo oculto 'precio_dolar'
		
		// Recorremos la tabla para verificar que campos están editables y proceder a referenciarles el monto
		$("#tab_productos tbody tr").each(function () {
			var checkbox;
			checkbox = $(this).find('td').eq(0).find('input');
			
			if (checkbox.is(':checked')) {
				num_checked += 1;
				// Captura del costo del material en dólares teniendo en cuenta si está en un input o es texto plano
				if($(this).find('span').eq(0).find('input').val().trim() == undefined){
					var costo_mat_dolar = $(this).find('span').eq(0).text();
				}else{
					var costo_mat_dolar = $(this).find('span').eq(0).find('input').val();
				}
				var valor_referencial = parseFloat(costo_mat_dolar) * precio_dolar;
				// Indicamos el monto referencial
				$(this).find('span').eq(1).find('input').val(valor_referencial.toFixed(2));
			}
		});
		
		//~ alert(num_checked);
		
		if (num_checked == 0) {
			swal("Disculpe,", "no ha marcado ningún elemento de la lista");			
        }
	});
	
	
	// Proceso de actualización de montos de los productos seleccionados
	$("#actualizar_montos").on('click', function (e) {
		var num_checked = 0;  // Contador de checkbox marcados
		
		// Recorremos la tabla para contar los registros con los campos de precios editables
		$("#tab_productos tbody tr").each(function () {
			var checkbox;
			checkbox = $(this).find('td').eq(0).find('input');
			
			if (checkbox.is(':checked')) {
				num_checked += 1;
			}
		});
		
		if (num_checked == 0) {
			swal("Disculpe,", "no ha marcado ningún elemento de la lista");
			$('#resultado').css({display:'none'});
			$('#agregar').prop('disabled',false);
			$('#referenciar').prop('disabled',false);		
		}else{
		
			swal({
				title: "Actualizar productos",
				text: "¿Está seguro de actualizar los montos de los productos?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Actualizar",
				cancelButtonText: "Cancelar",
				closeOnConfirm: false,
				closeOnCancel: true
			  },
			function(isConfirm){
				if (isConfirm) {
					
					var data = [];  // Arreglo para productos a actualizar
					// Recorremos la tabla para verificar qué campos están editables y proceder a incluirlos en el arreglo
					$("#tab_productos tbody tr").each(function () {
						var checkbox;
						checkbox = $(this).find('td').eq(0).find('input');
						
						if (checkbox.is(':checked')) {
							var id = $(this).find('a').eq(1).attr('id');
							//~ alert(id);
							var nombre = $(this).find('td').eq(1).text().trim();
							var referencia = $(this).find('td').eq(2).text().trim();
							var costo_dolar = $(this).find('span').eq(0).find('input').val().trim();
							var costo_bolivar = $(this).find('span').eq(1).find('input').val().trim();
							//~ var tienda_id = $(this).find('td').eq(6).attr('id');
							//~ tienda_id = tienda_id.split('_');
							//~ tienda_id = tienda_id[1];
							var c_compra = $(this).find('td').eq(8).text().trim();
							var c_vende = $(this).find('td').eq(9).text().trim();
							var c_fabrica = $(this).find('td').eq(10).text().trim();
							
							campos = { 
								'id' : id,
								'nombre' : nombre,
								'referencia' : referencia,
								'costo_dolar' : costo_dolar,
								'costo_bolivar' : costo_bolivar,
								'c_compra' : c_compra,
								'c_vende' : c_vende,
								'c_fabrica' : c_fabrica
							}
							data.push(campos);
						}
					});
					
					console.log(data);
					
					$.ajax({
						url : base_url+'CProductos/update_list',
						type : 'POST',
						async: false,  // Para que no proceda con las siguientes instrucciones hasta terminar la petición
						//~ dataType : 'json',
						data : {'productos' : data},
						beforeSend:function(objeto){
							$('#resultado').css({display:'block'});
							$('#agregar').prop('disabled',true);
							$('#referenciar').prop('disabled',true);
						},
						success : function(response) {
							
							$('#resultado').css({display:'none'});
							$('#agregar').prop('disabled',false);
							$('#referenciar').prop('disabled',false);
							swal({
								title: "Actualización",
								 text: "Actualizado con exito",
								  type: "success" 
								},
							function(){
								// Reiniciamos
								//~ window.location.href = base_url+'productos';
							});
															
						},
					});
					
				}
			});  // Cierre del confirm
			
		}  // Cierre del if que valida si hay checkbox marcados
		
	});
	
	// Referenciación y actualización general de precios
	$("#actualizar_montos_general").on('click', function (e) {
		var precio_dolar = $("#precio_dolar").val();  // Capturamos el precio del dólar previamente cargado en el campo oculto 'precio_dolar'
		
		$.ajax({
			url : base_url+'CProductos/update_list2',
			type : 'POST',
			async: false,  // Para que no proceda con las siguientes instrucciones hasta terminar la petición
			//~ dataType : 'json',
			data : {'precio_dolar' : precio_dolar},
			beforeSend:function(objeto){
				$('#resultado').css({display:'block'});
				$('#actualizar_montos').css({display:'none'});
			},
			success : function(response) {
				
				$('#resultado').css({display:'none'});
				$('#actualizar_montos').css({display:'block'});
				
				swal({
					title: "Actualización",
					 text: "Actualizado con exito",
					  type: "success" 
					},
				function(){
					// Reiniciamos
					window.location.href = base_url+'productos';
				});
												
			},
		});
	});
	
	// Mostrar la modal de selección de tienda virtual
    $("table#tab_productos").on('click', 'a.actualizar', function (e) {
        e.preventDefault();
        var id_producto = this.getAttribute('id');
        
        swal({
			title: "Sincronización",
			text: "¿Está seguro de continuar con la sincronización?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Sincronizar",
			cancelButtonText: "Cancelar",
			closeOnConfirm: true,
			closeOnCancel: true
		},
		function(isConfirm){
			
			if (isConfirm) {
				
				$("#modal_tiendav").modal('show');
				$("#producto_id").val(id_producto);
				
				var id_tiendav = $('#tiendav_id').val();
				
				// Pre-carga de la lista de tiendas virtuales asociadas al producto
				$('#tiendav_id').find('option:gt(0)').remove().end().select2('val', '0');
					
				$.get(base_url+'CProductos/ajax_tiendasv/' + id_producto + '', function (data) {
					
					var option = "";
					
					$.each(data, function (i) {

						option += "<option value=" + data[i]['id'] + ">" + data[i]['nombre'] + "</option>";
						
					});
					
					$('#tiendav_id').append(option);
					
				}, 'json');
				
			}
			
		});
        
    });
    
    // Ejecutar sincronización
	$("#sincronizar").on('click', function (e) {
		e.preventDefault();
		
        var id_tiendav = $("#tiendav_id").val();
        
        if($("#tiendav_id").val() == "0"){
			swal("Disculpe,", "para continuar debe seleccionar la tienda virtual");
			$('#tiendav_id').parent('div').addClass('has-error');
		}else{		
			window.location.href = base_url+'mercado/update_singles/?producto_id='+$("#producto_id").val()+'&tiendav_id='+id_tiendav;
		}
		
	});
	
});

// Función para referenciar del precio en bolívares al cambiar el precio en dólares de algún producto
function referenciar(id_input_dl){
	var precio_dolar = $("#precio_dolar").val();  // Capturamos el precio del dólar previamente cargado en el campo oculto 'precio_dolar'
	//~ var precio_dolar = 22938.23;  // Prueba con valor estático
	
	var input_dolar = $("#"+id_input_dl).val();  // Capturamos el precio en dólares del producto modificado
	// Preparamos el input del precio en bolívares
	var id_input_bolivar = id_input_dl.replace("_dl","");
	var input_bolivar = $("#"+id_input_bolivar);
	
	// Calculamos el nuevo precio y lo asinamos al input de monto en bolívares correspondiente al producto
	var valor_referencial = parseFloat(input_dolar) * precio_dolar;
	input_bolivar.val(valor_referencial.toFixed(2));
}
