$(document).ready(function(){
	
	// Capturamos la base_url
    var base_url = $("#base_url").val();
	
     //~ $('#tab_cuentas').DataTable({
        //~ "paging": true,
        //~ "lengthChange": true,
        //~ "autoWidth": false,
        //~ "searching": true,
        //~ "ordering": true,
        //~ "info": true,
        //~ "iDisplayLength": 50,
        //~ "iDisplayStart": 0,
        //~ "sPaginationType": "full_numbers",
        //~ "aLengthMenu": [10, 50, 100, 150],
        //~ "oLanguage": {"sUrl": "<?= assets_url() ?>js/es.txt"},
        //~ "aoColumns": [
            //~ {"sClass": "registro center", "sWidth": "5%"},
            //~ {"sClass": "registro center", "sWidth": "10%"},
            //~ {"sClass": "registro center", "sWidth": "10%"},
            //~ {"sClass": "registro center", "sWidth": "10%"},
            //~ {"sClass": "registro center", "sWidth": "10%"},
            //~ {"sClass": "registro center", "sWidth": "10%"},
            //~ {"sClass": "registro center", "sWidth": "10%"},
            //~ {"sClass": "registro center", "sWidth": "10%"},
            //~ {"sClass": "registro center", "sWidth": "10%"},
            //~ {"sClass": "none", "sWidth": "30%"},
            //~ {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            //~ {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        //~ ]
    //~ });
             
    // Validacion para borrar
    $("table#tab_cuentas").on('click', 'a.borrar', function (e) {
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
             
                $.post(base_url+'accounts/delete/' + id + '', function (response) {

                    if (response['response'] == "existe") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede eliminar, se encuentra asociado a una transacción",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    } else if (response['response'] == "existe2") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede eliminar, se encuentra asociado a un grupo de inversionistas.",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    } else if (response['response'] == "error") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede eliminar, ha ocurrido un falo en el sistema, por favor consulte con su administrador.",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    } else {
                         swal({ 
                           title: "Eliminar",
                            text: "Registro eliminado con exito.",
                             type: "success" 
                           },
                           function(){
                             window.location.href = base_url+'accounts';
                         });
                    }
                }, 'json');
            } 
        });
    });
    
    
    // El elemento que se quiere activar (ícono de carga) si hay una petición ajax en proceso.
	var cargando = $(".sk-spinner-circle");
	cargando.hide();
    
    // Funciones para el buscador
    $("#search").keyup( function (e) {
		
		// Realizamos la búsqueda al persionar la tecla 'Intro' o 'Enter'
		if(e.which == 13){
			
			var info = $(this).val();  // Valor del campo 'search'
			
			if(info != ''){
				
				// Mostramos en tiempo real una vista preliminar de lo que se busca
				$(".info").html('<strong>"'+info+'"</strong>');

				// evento ajax start
				$(document).ajaxStart(function() {
				cargando.show();
				});

				// evento ajax stop
				$(document).ajaxStop(function() {
				cargando.hide();
				});
				
				// Realizamos una consulta asincrona con ajax usando como argumento lo escrito en el buscador
				$.post(base_url+'accounts/search', { 'search': info }, function(data){
					
					if(data != ''){
						$(".results").html('');  // Vaciamos la tabla
						$(".results").html(data);  // Cargamos los resultados
					}else{
						$(".results").html('<strong>No se encontraron coincidencias</strong>');
					}
					
				});			
				
			}else{
				
				// Vaciamos la vista preliminar de la búsqueda
				$(".info").html('');

				// evento ajax start
				$(document).ajaxStart(function() {
				cargando.show();
				});

				// evento ajax stop
				$(document).ajaxStop(function() {
				cargando.hide();
				});
				
				// Realizamos una consulta asincrona con ajax sin datos de búsqueda para que retorne todos los registros correspondientes
				$.post(base_url+'accounts/search', { 'search': '' }, function(data){
					
					if(data != ''){
						$(".results").html('');  // Vaciamos la tabla
						$(".results").html(data);  // Cargamos los resultados
					}else{
						$(".results").html('<strong>No se encontraron coincidencias</strong>');
					}
					
				});
				
			}
		}
		
	});
    
    $("#go-search").click(function (e) {
		
		var info = $("#search").val();
		
		if(info != ''){
			
			// Mostramos en tiempo real una vista preliminar de lo que se busca
			$(".info").html('<strong>"'+info+'"</strong>');

			// evento ajax start
			$(document).ajaxStart(function() {
			cargando.show();
			});

			// evento ajax stop
			$(document).ajaxStop(function() {
			cargando.hide();
			});
			
			// Realizamos una consulta asincrona con ajax usando como argumento lo escrito en el buscador
			$.post(base_url+'accounts/search', { 'search': info }, function(data){
				
				if(data != ''){
					$(".results").html('');  // Vaciamos la tabla
					$(".results").html(data);  // Cargamos los resultados
				}else{
					$(".results").html('<strong>No se encontraron coincidencias</strong>');
				}
				
			});			
			
		}else{
			
			// Vaciamos la vista preliminar de la búsqueda
			$(".info").html('');

			// evento ajax start
			$(document).ajaxStart(function() {
			cargando.show();
			});

			// evento ajax stop
			$(document).ajaxStop(function() {
			cargando.hide();
			});
			
			// Realizamos una consulta asincrona con ajax sin datos de búsqueda para que retorne todos los registros correspondientes
			$.post(base_url+'accounts/search', { 'search': '' }, function(data){
				
				if(data != ''){
					$(".results").html('');  // Vaciamos la tabla
					$(".results").html(data);  // Cargamos los resultados
				}else{
					$(".results").html('<strong>No se encontraron coincidencias</strong>');
				}
				
			});
			
		}
		
	});
    
});
