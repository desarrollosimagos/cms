$(document).ready(function(){
	// Ruta base del proyecto
	base_url = $("#base_url").val() 

    $('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });

    $('#volver').click(function () {
        url = base_url+'productos/';
        window.location = url;
    });
    
    // Configuraciones de la tabla de productos asociados
    $('#tab_productos').DataTable({
		"iDisplayLength": 10,
		"iDisplayStart": 0,
		"destroy": true,
		"paging": true,
        "lengthChange": false,
        "autoWidth": false,
		"searching": true,
		"pagingType": "full_numbers",
		"language": {"url": base_url+"assets/js/es.txt"},
		"aoColumns": [
			{"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "20%"},
			{"sClass": "registro center", "sWidth": "20%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "none", "sWidth": "20%"},
			{"sClass": "none", "sWidth": "20%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			//~ {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
			//~ {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
		]
	});
    
});
