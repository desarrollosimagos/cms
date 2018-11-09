$(document).ready(function(){
	// Capturamos la base_url
    var base_url = $("#base_url").val();
	
	var tabTwitters = $('#tab_twitters').DataTable({
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
			"url": base_url+"twitters_json"
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
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "registro center", "sWidth": "20%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    
	// Función para ver los datos de un twitter tomando en cuenta el valor del id
	$("table#tab_twitters").on('click', 'a.verId', function (e) {
		
		var valor = this.innerHTML;
		
		$("#id").val(valor);
		$("#screen_name").val('');
		
		window.location.href = base_url+'twitters/view?id='+$("#id").val()+'&screen_name='+$("#screen_name").val()+'&ruta='+$("#ruta_origen").val();
		
	});
    
	// Función para ver los datos de un twitter tomando en cuenta el valor del screen_name
	$("table#tab_twitters").on('click', 'a.verName', function (e) {
		
		var valor = this.innerHTML;
		
		$("#id").val('');
		$("#screen_name").val(valor);
		
		window.location.href = base_url+'twitters/view?id='+$("#id").val()+'&screen_name='+$("#screen_name").val()+'&ruta='+$("#ruta_origen").val();
		
	});
	
});
