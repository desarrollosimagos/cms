$(document).ready(function(){
	// Capturamos la base_url
    var base_url = $("#base_url").val();
	
	var tabPolitico = $('#tab_politico').DataTable({
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
			"url": base_url+"politico_json"
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
        "aoColumns": [
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sWidth": "9%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "sClass": "registro center"},
            {"sWidth": "3%", "sClass": "registro center"}
        ]
    });
    
	// Función para cambiar un twitter a otra bandeja según la opción seleccionada en su respectivo combo
	$("table#tab_politico").on('change', 'select.cambiar', function (e) {
		
		e.preventDefault();
		
		var id = this.getAttribute('id');
		id = id.split(";");
		id = id[0];  // Id de la cola
		var estatus_actual = this.getAttribute('id');
		estatus_actual = estatus_actual.split(";");
		estatus_actual = estatus_actual[1];  // Estatus actual de la cola
		var select_actual = $(this);  // Combo actualmente seleccionado
		var nueva_bandeja = $(this).val();  // Bandeja nueva para el tweet
		
		//~ alert("Id: "+id+" | Nueva bandeja: "+nueva_bandeja);
		
		swal({
            title: "Cambiar de bandeja",
            text: "¿Está seguro de asignar el tweet a otra bandeja?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Asignar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
             
                $("#modal_detalles").modal('show');
                $("#id_tweet").val(id);
                $("#nueva_bandeja").val(nueva_bandeja);
                
                $("#asignar").show();
                $("#observar").hide();
                
            }else{
				
				// Si cancelamos el cambio de estatus, fijamos el select al estatus inicial
				select_actual.val('0');
			
			} 
        });
		
	});
	
	// Función para cambiar un twitter a otra bandeja según la opción seleccionada en su respectivo combo
	$("table#tab_politico").on('click', 'a.observacion', function (e) {
		
		e.preventDefault();
		
		var id = this.getAttribute('id');
		id = id.split(";");
		id = id[0];  // Id de la cola
		var estatus_actual = this.getAttribute('id');
		estatus_actual = estatus_actual.split(";");
		estatus_actual = estatus_actual[1];  // Estatus actual de la cola
		var select_actual = $(this);  // Combo actualmente seleccionado
		var nueva_bandeja = "Observaciones";  // Bandeja nueva para el tweet
		
		//~ alert("Id: "+id+" | Nueva bandeja: "+nueva_bandeja);
		
		swal({
            title: "Cambiar de bandeja",
            text: "¿Está seguro de asignar el tweet a otra bandeja?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Asignar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
             
                $("#modal_detalles").modal('show');
                $("#id_tweet").val(id);
                $("#nueva_bandeja").val(nueva_bandeja);
                
                $("#asignar").hide();
                $("#observar").show();
                
            }else{
				
				// Si cancelamos el cambio de estatus, fijamos el select al estatus inicial
				select_actual.val('0');
			
			} 
        });
		
	});
	
	// Función para cambiar un twitter a otra bandeja según la opción seleccionada en su respectivo combo
	$("#asignar").on('click', function (e) {
		
		if($("#detalles").val().trim() == ''){
			
			swal("Disculpe,", "debe indicar los detalles de la asignación");
			$("#detalles").focus();
			
		}else{
			
			$.post(base_url+'politico/cambiar_bandeja', {'id':$("#id_tweet").val(), 'nueva_bandeja':$("#nueva_bandeja").val(), 'mensaje':$("#detalles").val()}, function (response) {

				if (response['response'] == "error") {
				   
					swal({
						title: "Disculpe,",
						text: "Ha ocurrido un error durante la asignación, por favor contacte con su administrador.",
						type: "warning" 
					},
					function(){
						// Si hay algún error, fijamos el select al estatus inicial
						select_actual.val('0');
					});
					
				}else{
					 swal({ 
					   title: "Asignación a nueva bandeja",
						text: "Asignación realizada con éxito",
						 type: "success" 
					   },
					   function(){
						 window.location.href = base_url+'bandeja_politico';
					 });
				}
			}, 'json');
		
		}
	
		
	});
	
	// Función para cambiar un twitter a la bandeja de observaciones
	$("#observar").on('click', function (e) {
		
		if($("#detalles").val().trim() == ''){
			
			swal("Disculpe,", "debe indicar los detalles de la asignación");
			$("#detalles").focus();
			
		}else{
			
			$.post(base_url+'politico/cambiar_bandeja', {'id':$("#id_tweet").val(), 'nueva_bandeja':$("#nueva_bandeja").val(), 'mensaje':$("#detalles").val()}, function (response) {

				if (response['response'] == "error") {
				   
					swal({
						title: "Disculpe,",
						text: "Ha ocurrido un error durante la asignación, por favor contacte con su administrador.",
						type: "warning" 
					},
					function(){
						// Si hay algún error, fijamos el select al estatus inicial
						select_actual.val('0');
					});
					
				}else{
					 swal({ 
					   title: "Asignación a nueva bandeja",
						text: "Asignación realizada con éxito",
						 type: "success" 
					   },
					   function(){
						 window.location.href = base_url+'bandeja_politico';
					 });
				}
			}, 'json');
		
		}
	
		
	});
	
	// Función para ver el time-line de un twitter tomando en cuenta el valor del id
	$("table#tab_politico").on('click', 'a.verId', function (e) {
		
		var valor = this.innerHTML;
		
		$("#id_str").val(valor);
		$("#screen_name").val('');
		
		window.location.href = base_url+'time_line/time_line?id_str='+$("#id_str").val()+'&ruta='+$("#ruta_origen").val();
		
	});
    
	// Función para ver los datos de un twitter tomando en cuenta el valor del screen_name
	$("table#tab_politico").on('click', 'a.verName', function (e) {
		
		var valor = this.innerHTML;
		
		$("#id_str").val('');
		$("#screen_name").val(valor);
		
		window.location.href = base_url+'twitters/view?screen_name='+$("#screen_name").val()+'&ruta='+$("#ruta_origen").val();
		
	});
	
	// Función para ver el time-line de un twitter tomando en cuenta el valor del id
	$("table#tab_politico").on('click', 'a.verText', function (e) {
		
		var valor = this.getAttribute('id');
		
		$("#id_str").val(valor);
		$("#screen_name").val('');
		
		window.location.href = base_url+'time_line/time_line?id_str='+$("#id_str").val()+'&ruta='+$("#ruta_origen").val();
		
	});
	
});
