$(document).ready(function(){
	// Ruta base del proyecto
	base_url = $("#base_url").val();
	
    $('#tab_colas').DataTable({
        "paging": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 15],
        "oLanguage": {"sUrl": base_url+"assets/js/es.txt"},
        "aoColumns": [
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "none", "sWidth": "20%"},
            {"sClass": "none", "sWidth": "20%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
            
    // Función para cambiar el status de una cola según la opción seleccionada en su respectivo combo
	$("table#tab_colas").on('change', 'select.cambiar', function (e) {
		
		e.preventDefault();
		
		var id = this.getAttribute('id');
		id = id.split(";");
		id = id[0];  // Id de la cola
		var estatus_actual = this.getAttribute('id');
		estatus_actual = estatus_actual.split(";");
		estatus_actual = estatus_actual[1];  // Estatus actual de la cola
		var select_actual = $(this);  // Combo actualmente seleccionado
		var new_status = $(this).val();  // Estatus nuevo para la cola
		
		//~ alert("Id: "+id+" | Nuevo Estatus: "+new_status);
		
		swal({
            title: "Cambiar estatus",
            text: "¿Está seguro de cambiar el estatus de la cola?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Cambiar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
             
                $.post(base_url+'colas/update_status', {'id':id, 'new_status':new_status}, function (response) {

                    if (response['response'] == "detalles pendientes") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "La cola aún tiene detalles pendientes",
                             type: "warning" 
						   },
						   function(){
                             // Si hay detalles pendientes, fijamos el select al estatus inicial
							 select_actual.select2('val', estatus_actual);
                         });
                    }else{
                         swal({ 
                           title: "Cambio de estatus",
                            text: "Estatus actualizado con exito",
                             type: "success" 
                           },
                           function(){
                             window.location.href = base_url+'colas';
                         });
                    }
                }, 'json');
                
            }else{
				
				// Si cancelamos el cambio de estatus, fijamos el select al estatus inicial
				select_actual.select2('val', estatus_actual);
			
			} 
        });
		
	});       
	
});
