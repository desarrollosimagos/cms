<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Tiendas</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home">Inicio</a>
            </li>
            
            <li>
                <a href="<?php echo base_url() ?>tiendas">Tiendas</a>
            </li>
            
            <li class="active">
                <strong>Registrar Tienda</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
        <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Registrar Tienda<small></small></h5>
					
				</div>
				<div class="ibox-content">
					<form id="form_tienda" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" >Rif *</label>
							<div class="col-sm-4">
								<input type="text" class="form-control"  maxlength="20" name="rif" id="rif">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" >Nombre</label>
							<div class="col-sm-8">
								<input type="text" class="form-control"  name="name" maxlength="100" id="name">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" >Dirección</label>
							<div class="col-sm-8">
								<input type="text" class="form-control"  name="address" maxlength="250" id="address">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" >Referencia Externa</label>
							<div class="col-sm-8">
								<input type="text" class="form-control"  name="referencia" maxlength="250" id="referencia">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" >BD Externa</label>
							<div class="col-sm-8">
								<input type="text" class="form-control"  name="bd_externa" maxlength="250" id="bd_externa">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" >Campo Externo</label>
							<div class="col-sm-8">
								<input type="text" class="form-control"  name="cp_externo" maxlength="250" id="cp_externo">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Teléfono</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" maxlength="20" name="phone" id="phone">
							</div>
						</div>
						<br>
						<!-- Tabla de tiendas virtuales -->
						<!--<div class="col-md-12">
							<hr>
						</div>
						<div class="ibox-title col-md-12">
							<h5>Asociar Tiendas virtuales<small></small></h5>
						</div>
						<div class="col-md-4 text-left">
							<label class="control-label" >Tienda Virtual</label>
							<select class="form-control" name="tiendav_id" id="tiendav_id">
								<option value="0" selected="">Seleccione</option>
								<?php foreach ($listar_tiendasv as $tiendav) { ?>
									<option value="<?php echo $tiendav->id ?>"><?php echo $tiendav->nombre." - ".$tiendav->url; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2">
							<label style="font-weight:bold"></label>
							<br>
							<button type="button" class="btn btn-w-m btn-primary" id="i_new_line2"><i class="fa fa-plus"></i>&nbsp;Agregar Tienda Virtual</button>
						</div>
						<div class="col-md-6">
							
						</div>
						<div class="table-responsive col-md-12">
							<table style="width: 100%" class="tab_tiendasv table dataTable table-striped table-bordered dt-responsive jambo_table bulk_action" id="tab_tiendasv">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>Url</th>
										<th>Eliminar</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						<br>-->
						<!-- Tabla de tiendas virtuales -->
						<br>
						<!-- Tabla de usuarios -->
						<div class="col-md-12">
							<hr>
						</div>
						<div class="ibox-title col-md-12">
							<h5>Asociar Usuarios <small></small></h5>
						</div>
						<div class="col-md-3">
							<label class="control-label" >Usuario</label>
							<input type="text" id="usuario" class="form-control">
						</div>
						<!--<div class="col-md-3">
							<label class="control-label" >Usuario</label>
							<select class="form-control" name="usuario_id" id="usuario_id">
								<option value="0" selected="">Seleccione</option>
								<?php foreach ($listar_usuarios as $usuario) { ?>
									<?php if ($usuario->id != $this->session->userdata('logged_in')['id']) { ?>
										<option value="<?php echo $usuario->id ?>"><?php echo $usuario->username; ?></option>
									<?php } ?>		
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2">
							<label class="control-label" >Tipo</label>
							<select class="form-control" name="tipo" id="tipo">
								<option value="0" selected="">Seleccione</option>
								<option value="1">Administrador</option>
								<option value="2">Empleado</option>
							</select>
						</div>-->
						<div class="col-md-2">
							<label style="font-weight:bold"></label>
							<br>
							<!--<button type="button" class="btn btn-w-m btn-primary" id="i_new_line"><i class="fa fa-plus"></i>&nbsp;Agregar Usuario</button>-->
							<button type="button" class="btn btn-w-m btn-primary" id="invitar"><i class="fa fa-send"></i>&nbsp;Enviar Invitación</button>
						</div>
						<div class="col-md-6">
							
						</div>
						<div class="table-responsive col-md-12">
							<table style="width: 100%" class="tab_usuarios table dataTable table-striped table-bordered dt-responsive jambo_table bulk_action" id="tab_usuarios">
								<thead>
									<tr>
										<th>Usuario</th>
										<th>Tipo</th>
										<th>Eliminar</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						<!-- Tabla de usuarios -->
						<br>
						<br>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<!--<input type="hidden" id="codigos_des1" name="codigos_des1" placeholder="Códigos">-->
								<button class="btn btn-white" id="volver" type="button">Volver</button>
								<button class="btn btn-primary" id="registrar" type="submit">Guardar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){

    $('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });

    $('#volver').click(function () {
        url = '<?php echo base_url() ?>tiendas/';
        window.location = url;
    });
    
    $("#phone").numeric(); //Valida solo permite valores numéricos

    $("#registrar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#rif').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el rif");
			$('#rif').parent('div').addClass('has-error');
			
        } else if ($('#name').val().trim() == "") {
			swal("Disculpe,", "para continuar debe ingresar el nombre");
			$('#name').parent('div').addClass('has-error');
			
        } else if ($('#address').val().trim() == "") {
			swal("Disculpe,", "para continuar debe ingresar la dirección");
			$('#address').parent('div').addClass('has-error');
			
        } else if ($('#phone').val().trim() == "") {
			swal("Disculpe,", "para continuar debe ingresar el teléfono");
			$('#phone').parent('div').addClass('has-error');
			
        } else {
            
            var formData = new FormData(document.getElementById("form_tienda"));  // Forma de capturar todos los datos del formulario
			
			$.ajax({
				//~ method: "POST",
				type: "post",
				dataType: "html",
				url: '<?php echo base_url(); ?>CTiendas/add',
				data: formData,
				cache: false,
				contentType: false,
				processData: false
			})
			.done(function(response) {
				if(response.error){
					console.log(response.error);
				} else {
					if (response == 'existe') {
						swal("Disculpe,", "esta tienda se encuentra registrada");
					}else{
						//~ // Asociamos las tiendas virtuales a la tienda
						//~ var data = [];
						//~ $("#tab_tiendasv tbody tr").each(function () {
							//~ var id_tiendav;
							//~ if ($(this).attr('id') != undefined){
								//~ id_tiendav = $(this).attr('id');  // id tienda
//~ 
								//~ campos = { "id_tiendav" : id_tiendav}
								//~ data.push(campos);
							//~ }
//~ 
						//~ });
						//~ 
						//~ // Registramos la asociación con las tiendas virtuales de la lista
						//~ $.post('<?php echo base_url(); ?>CTiendas/associate_tiendasv', {'id_tienda':response, 'tiendasv':data}, function (response2) {
							//~ 
						//~ });
						
						// Asociamos los usuarios a la tienda
						var data = [];  // Usuarios regiastrados en el sistema
						var data2 = [];  // Usuarios no regiastrados en el sistema
						$("#tab_usuarios tbody tr").each(function () {
							var id_usuario, tipo, usuario_text;
							if ($(this).attr('id') != undefined){
								id_usuario = $(this).attr('id');  // id usuario
								usuario_text = $(this).find('td').eq(0).text();  // name usuario
								tipo = 2;

								campos = { "id_usuario" : id_usuario, "username":usuario_text, "tipo" : tipo}
								data.push(campos);
							}else{
								// Si el correo a invitar no está registrado, éste recibe una invitación de registro en el sistema
								id_usuario = 0;  // Nuevo usuario
								usuario_text = $(this).find('td').eq(0).text();
								tipo = 2;

								campos = { "id_usuario" : id_usuario, "username":usuario_text, "tipo" : tipo}
								data2.push(campos);
							}

						});
						
						// Registramos la asociación con los usuarios de la lista que están registrados en el sistema
						$.post('<?php echo base_url(); ?>CTiendas/associate_users', {'id_tienda':response, 'usuarios':data}, function (response2) {
							
						});
						
						// Registramos la asociación con los usuarios de la lista que no están registrados en el sistema
						$.post('<?php echo base_url(); ?>CTiendas/associate_users', {'id_tienda':response, 'usuarios':data2}, function (response2) {
							
						});
						
						// Reenviamos a la lista de tiendas
						swal({
							title: "Registro",
							 text: "Guardado con exito",
							  type: "success" 
							},
						function(){
						  window.location.href = '<?php echo base_url(); ?>tiendas';
						});
						
					}
				}				
			}).fail(function() {
				console.log("error ajax");
			});
        }
    });
    
    //~ // Configuraciones de la lista de tiendas virtuales
    //~ $('#tab_tiendasv').DataTable({
		//~ "bLengthChange": false,
		//~ "iDisplayLength": 10,
		//~ "iDisplayStart": 0,
		//~ destroy: true,
		//~ paging: false,
		//~ searching: false,
		//~ "order": [[0, "asc"]],
		//~ "pagingType": "full_numbers",
		//~ "language": {"url": "<?= assets_url() ?>js/es.txt"},
		//~ "aoColumns": [
			//~ {"sWidth": "20%"},
			//~ {"sWidth": "20%"},
			//~ {"sWidth": "10%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
		//~ ]
	//~ });
	//~ 
	//~ // Función para agregar tiendas virtuales a la lista
    //~ $("#i_new_line2").click(function (e) {
//~ 
        //~ e.preventDefault();  // Para evitar que se envíe por defecto
//~ 
        //~ if ($('#tiendav_id').val().trim() == "0") {
			//~ swal("Disculpe,", "para continuar debe seleccionar una tienda virtual");
			//~ $('#tiendav_id').parent('div').addClass('has-error');
			//~ 
        //~ } /*else if ($('#tipo').val().trim() == "0") {
			//~ swal("Disculpe,", "para continuar debe seleccionar el tipo de usuario");
			//~ $('#tipo').parent('div').addClass('has-error');
			//~ 
        //~ }*/ else {
			//~ 
			//~ var table = $('#tab_tiendasv').DataTable();
			//~ var tiendav = $("#tiendav_id").find('option').filter(':selected').text();
			//~ var nom_tienda = tiendav.split(' - ');
			//~ nom_tienda = nom_tienda[0];
			//~ var url_tienda = tiendav.split(' - ');
			//~ url_tienda = url_tienda[1];
			//~ var tiendav_id = $("#tiendav_id").val();
			//~ var tipo = $("#tipo").find('option').filter(':selected').text();
            //~ var tipo_id = $("#tipo").val();
			//~ var botonQuitar = "<a  style='color: #1ab394' class='quitar'><i class='fa fa-trash fa-2x'></i></a>";
			//~ 
			//~ // Añadimos la tienda virtual a la tabla (primero verificamos si aún no está añadido)
			//~ var num_apariciones = 0;
			//~ $("#tab_tiendasv tbody tr").each(function () {
				//~ var id_tiendav;
				//~ id_tiendav = $(this).attr('id');  // id usuario + tipo
				//~ if (id_tiendav != undefined){
					//~ if(id_tiendav == tiendav_id){
						//~ num_apariciones += 1;
					//~ }
				//~ }
			//~ });
			//~ 
			//~ if (num_apariciones == 1) {
				//~ swal("Disculpe,", "la tienda virtual ya se encuentra en la lista");
			//~ } else {
				//~ var i = table.row.add([nom_tienda, url_tienda, botonQuitar]).draw();
				//~ table.rows(i).nodes().to$().attr("id", tiendav_id);
			//~ }
		//~ }
	//~ });
	//~ 
	//~ //Método para eliminar un registro de la tabla
	//~ $("table#tab_tiendasv").on('click', 'a.quitar', function () {
//~ 
		//~ var aPos = $("table#tab_tiendasv").dataTable().fnGetPosition(this.parentNode.parentNode);
		//~ $("table#tab_tiendasv").dataTable().fnDeleteRow(aPos);
//~ 
	//~ });
	
    
    // Configuraciones de la lista de usuarios
    $('#tab_usuarios').DataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"iDisplayStart": 0,
		destroy: true,
		paging: false,
		searching: false,
		"order": [[0, "asc"]],
		"pagingType": "full_numbers",
		"language": {"url": "<?= assets_url() ?>js/es.txt"},
		"aoColumns": [
			{"sWidth": "20%"},
			{"sWidth": "20%"},
			{"sWidth": "10%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
		]
	});
    
    //~ // Función para agregar usuarios a la lista
    //~ $("#i_new_line").click(function (e) {
//~ 
        //~ e.preventDefault();  // Para evitar que se envíe por defecto
//~ 
        //~ if ($('#usuario_id').val().trim() == "0") {
			//~ swal("Disculpe,", "para continuar debe seleccionar un usuario");
			//~ $('#usuario_id').parent('div').addClass('has-error');
			//~ 
        //~ } else {
			//~ 
			//~ var table = $('#tab_usuarios').DataTable();
			//~ var usuario = $("#usuario_id").find('option').filter(':selected').text();
			//~ var usuario_id = $("#usuario_id").val();
			//~ var botonQuitar = "<a  style='color: #1ab394' class='quitar'><i class='fa fa-trash fa-2x'></i></a>";
			//~ 
			//~ // Añadimos el usuario a la tabla (primero verificamos si aún no está añadido)
			//~ var num_apariciones = 0;
			//~ var num_apariciones2 = 0;
			//~ var num_apariciones3 = 0;
			//~ $("#tab_usuarios tbody tr").each(function () {
				//~ var id_usuario, id_tipo, usuario_tipo;
				//~ usuario_tipo = $(this).attr('id');  // id usuario + tipo
				//~ if (usuario_tipo != undefined){
					//~ id_usuario = usuario_tipo.split(";");  // id usuario
					//~ id_usuario = id_usuario[0];
					//~ if(id_usuario == usuario_id){
						//~ num_apariciones += 1;
					//~ }
					//~ if(usuario_tipo == usuario_id+";2"){
						//~ num_apariciones2 += 1;
					//~ }
				//~ }
			//~ });
			//~ 
			//~ if(num_apariciones == 1){
				//~ swal("Disculpe,", "el usuario ya se encuentra en la lista");
			//~ }else{
				//~ var i = table.row.add([usuario, 'Empleado', botonQuitar]).draw();
				//~ table.rows(i).nodes().to$().attr("id", usuario_id+";2");
			//~ }
		//~ }
	//~ });
	
	//Método para eliminar un registro de la tabla
	$("table#tab_usuarios").on('click', 'a.quitar', function () {

		var aPos = $("table#tab_usuarios").dataTable().fnGetPosition(this.parentNode.parentNode);
		$("table#tab_usuarios").dataTable().fnDeleteRow(aPos);

	});
	
	// Función para invitar usuarios a la tienda
	$("#invitar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto
        // Expresion regular para validar el correo
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		
		if ($('#usuario').val().trim() === "") {
			
		   swal("Disculpe,", "para continuar debe ingresar un nombre de usuario");
	       $('#usuario').parent('div').addClass('has-error');
	       $('#usuario').focus();
	       
        }else if (!(regex.test($('#usuario').val().trim()))) {  
			        
		   swal("Disculpe,", "el usuario debe ser una dirección de correo electrónico válida");
	       $('#usuario').parent('div').addClass('has-error');
	       $('#usuario').focus();
	       
        }else{

			// Buscamos si existe el usuario
			$.post('<?php echo base_url(); ?>CUser/search_username', {'usuario':$('#usuario').val().trim()}, function (response) {
				//~ alert(response.length);
				if(response.length == 1){
					// Listamos la invitación
					var table = $('#tab_usuarios').DataTable();
					var usuario_id;
					$.each(response, function (i) {
						usuario_id = response[i]['id'];
					});
					var usuario = $("#usuario").val().trim();
					var botonQuitar = "<a  style='color: #1ab394' class='quitar'><i class='fa fa-trash fa-2x'></i></a>";
					
					// Añadimos el usuario a la tabla (primero verificamos si aún no está añadido)
					var num_apariciones = 0;
					$("#tab_usuarios tbody tr").each(function () {
						var usuario_text = $(this).find('td').eq(0).text().trim();
						if(usuario == usuario_text){
							num_apariciones += 1;
						}
					});
					
					if (num_apariciones == 1) {
						swal("Disculpe,", "el usuario ya se encuentra en la lista");
					} else {
						var i = table.row.add([usuario, 'Empleado', botonQuitar]).draw();
						table.rows(i).nodes().to$().attr("id", usuario_id);
						$("#usuario").val("");
					}
				}else{
					swal({
						title: "Invitar usuario",
						text: "El usuario no está registrado ¿Está seguro de realizar la invitación?",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Invitar",
						cancelButtonText: "Cancelar",
						closeOnConfirm: true,
						closeOnCancel: true
					},
					function(isConfirm){
						if (isConfirm) {
							// Listamos la invitación
							var table = $('#tab_usuarios').DataTable();
							var usuario_id;
							$.each(response, function (i) {
								usuario_id = response[i]['id'];
							});
							var usuario = $("#usuario").val().trim();
							var botonQuitar = "<a  style='color: #1ab394' class='quitar'><i class='fa fa-trash fa-2x'></i></a>";
							
							// Añadimos el usuario a la tabla (primero verificamos si aún no está añadido)
							var num_apariciones = 0;
							$("#tab_usuarios tbody tr").each(function () {
								var usuario_text = $(this).find('td').eq(0).text();
								if(usuario == usuario_text){
									num_apariciones += 1;
								}
							});
							
							if (num_apariciones == 1) {
								//~ swal("Disculpe,", "el usuario ya se encuentra en la lista");
								alert("Disculpe, el usuario ya se encuentra en la lista");
							} else {
								var i = table.row.add([usuario, 'Empleado', botonQuitar]).draw();
								$("#usuario").val("");
								//~ table.rows(i).nodes().to$().attr("id", usuario_id);
							}
						} 
					});
				}
			}, 'json');		
		}
		
	});
	
});

</script>
