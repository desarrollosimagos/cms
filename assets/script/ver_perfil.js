$(document).ready(function(){
	// Capturamos la base_url
    var base_url = $("#base_url").val();
	
	$('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });

    $('#volver').click(function () {
        url = base_url+'twitters/view?id='+$("#id_twitter").val()+'&screen_name='+$("#screen_name").val()+'&ruta='+$("#ruta_origen").val();
        window.location = url;
    });
	
});
