<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_profiles_edit'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_profiles_edit'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url() ?>profile"><?php echo $this->lang->line('heading_subtitle_profiles_edit'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_profiles_edit'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
        <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo $this->lang->line('heading_info_profiles_edit'); ?><small></small></h5>
					
				</div>
				<div class="ibox-content">
					<form id="form_perfil" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_name_profiles'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="Introduzca nombre" name="name" id="name" value="<?php echo $editar[0]->name ?>">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_actions_profiles'); ?></label>
							<div class="col-sm-10">
								<select id="actions_ids" class="form-control" multiple="multiple">
									<?php
									// Primero creamos un arreglo con la lista de ids de acciones proveniente del controlador
									$ids_actions = explode(",",$ids_actions);
									foreach ($acciones as $accion) {
										// Si el id de la acción está en el arreglo lo marcamos, si no, se imprime normalmente
										if(in_array($accion->id, $ids_actions)){
										?>
										<option selected="selected" value="<?php echo $accion->id; ?>"><?php echo $accion->name; ?></option>
										<?php
										}else{
										?>
										<option value="<?php echo $accion->id; ?>"><?php echo $accion->name; ?></option>
										<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ></label>
							<div class="col-sm-10">
								<!--Tab de servicios-->
								<div class="tabs-container">
									<ul class="nav nav-tabs">
										<li class="active"><a data-toggle="tab" href="#tab-1"><?php echo $this->lang->line('edit_tab_permissions_title_profiles'); ?></a></li>
										<!--<li class=""><a data-toggle="tab" href="#tab-2">Productos</a></li>-->
									</ul>
									<div class="tab-content">
										<div id="tab-1" class="tab-pane active">
											<div class="panel-body">
											  <!--<button  class="btn btn-w-m btn-primary" id="i_new_line"><i class="fa fa-plus"></i>&nbsp;Agregar Acción</button>-->
												 <div class="table-responsive">
													<table style="width: 100%" class="table dataTable table-striped table-bordered dt-responsive jambo_table bulk_action" id="tab_acciones">
														<thead>
														<tr>
															<th><?php echo $this->lang->line('edit_tab_permissions_item_profiles'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_action_profiles'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_create_profiles'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_edit_profiles'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_delete_profiles'); ?></th>
															<th><?php echo $this->lang->line('edit_tab_permissions_validate_profiles'); ?></th>
														</tr>
														</thead>
														<tbody>
															<?php 
															foreach ($profile_acciones as $profile_accion) {
																foreach ($acciones as $accion) { 
																	// Imprimimos sólo las acciones asociadas
																	if($accion->id == $profile_accion->action_id){ 
																		$parameter1 = $profile_accion->parameter_permit[0];
																		$parameter2 = $profile_accion->parameter_permit[1];
																		$parameter3 = $profile_accion->parameter_permit[2];
																		$parameter4 = $profile_accion->parameter_permit[3];
																		?>
																		<tr id="<?php echo $id;?>">
																			<td><?php echo $accion->id; ?></td>
																			<td><?php echo $accion->name; ?></td>
																			<?php if($parameter1 == '0'){?>
																				<td><input type="checkbox" id=""></td>
																			<?php }else{ ?>
																				<td><input type="checkbox" id="" checked="checked"></td>
																			<?php } ?>
																			<?php if($parameter2 == '0'){?>
																				<td><input type="checkbox" id=""></td>
																			<?php }else{ ?>
																				<td><input type="checkbox" id="" checked="checked"></td>
																			<?php } ?>
																			<?php if($parameter3 == '0'){?>
																				<td><input type="checkbox" id=""></td>
																			<?php }else{ ?>
																				<td><input type="checkbox" id="" checked="checked"></td>
																			<?php } ?>
																			<?php if($parameter4 == '0'){?>
																				<td><input type="checkbox" id=""></td>
																			<?php }else{ ?>
																				<td><input type="checkbox" id="" checked="checked"></td>
																			<?php } ?>
																		</tr>
																<?php }
																}
															} ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--Tab de servicios-->
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<input class="form-control"  type='hidden' id="id" name="id" value="<?php echo $id ?>"/>
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('edit_back_profiles'); ?></button>
								<button class="btn btn-primary" id="edit" type="submit"><?php echo $this->lang->line('edit_save_profiles'); ?></button>
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

	$('select').on({
		change: function () {
			$(this).parent('div').removeClass('has-error');
		}
	});
    $('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });
    
    $('#tab_acciones').DataTable({
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
			  {"sWidth": "1%"},
			  {"sWidth": "10%"},
			  {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
			  {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
			  {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
			  {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
		  ]
	});

    $('#volver').click(function () {
        url = '<?php echo base_url() ?>profile/';
        window.location = url;
    });
	
	// Función para la interacción del combo select2 y la lista datatable
	$("#actions_ids").on('change', function () {
		
		var ids_actions = $(this).val();
		var data_actions = $(this).select2('data');
		
		// Comparamos las acciones del select con las de la lista y agregamos las que falten
		$.each(data_actions, function (index, value){
			// alert(index + ": " + value.id);
			var contador = 0;  // Para verificar si la acción ya está en la tabla
			$("#tab_acciones tbody tr").each(function (index){
				var id_action = $(this).find('td').eq(0).text();
			
				if(value.id == id_action){
					contador += 1;
				}
			})
			// Si la acción no está en la tabla, la añadimos
			if(contador == 0){
				var table = $('#tab_acciones').DataTable();
				var id_new_action = value.id;
				var name_new_action = value.text;
				var permission_new_action = '<input type="checkbox" id="">';
				var i = table.row.add( [ id_new_action, name_new_action, permission_new_action, permission_new_action, permission_new_action, permission_new_action ] ).draw();
				table.rows(i).nodes().to$().attr("id", $("#id").val());
			}
		});
		
		// Comparamos las acciones de la lista con las del combo select y eliminamos las que sobren
		$("#tab_acciones tbody tr").each(function (index){
			var id_action = $(this).find('td').eq(0).text();
			var contador2 = 0  // Para verificar si la acción está en la tabla
			
			// Recorremos la lista de ids capturados del combo select2
			$.each(ids_actions, function (index, value){
				if(id_action == value) {
					contador2 += 1;
				}
			})
			// Si el contador es igual a cero, significa que la acción ha sido borrada del combo select, por tanto la quitamos también de la lista
			if(contador2 == 0) {
				// Borramos la línea correspondiente (línea actual)
				var table = $('#tab_acciones').DataTable();
				table.row($(this)).remove().draw();
			}
			
		});

    });
    

    $("#edit").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#name').val().trim() === "") {

			swal("Disculpe,", "para continuar debe ingresar nombre");
			$('#name').parent('div').addClass('has-error');
			
        } else if ($('#actions_ids').val() == "") {
          
			swal("Disculpe,", "para continuar debe seleccionar los permisos");
			$('#actions_ids').parent('div').addClass('has-error');
			
        } else {
			//~ alert(String($('#actions_ids').val()));
			
			// Construimos la data de permisología leyendo las filas de la tabla
			var campos= "";
			var data = [];
			$("#tab_acciones tbody tr").each(function () {
				var campo0, campo1, campo2, campo3, campo4, campo5, campo6;
				//~ campo0 = $(this).attr('id');  // Id del perfil
				campo1 = $(this).find('td').eq(0).text();
				campo2 = $(this).find('td').eq(1).text();
				if($(this).find('input').eq(0).is(':checked')){
					campo3 = '7';
				}else{
					campo3 = '0';
				}
				if($(this).find('input').eq(1).is(':checked')){
					campo4 = '7';
				}else{
					campo4 = '0';
				}
				if($(this).find('input').eq(2).is(':checked')){
					campo5 = '7';
				}else{
					campo5 = '0';
				}
				if($(this).find('input').eq(3).is(':checked')){
					campo6 = '7';
				}else{
					campo6 = '0';
				}
				
				campos = { "id" : campo1, "accion" : campo2, "crear" : campo3, "editar" : campo4, "eliminar" : campo5, "validar" : campo6 },
				data.push(campos);
			});
			
            $.post('<?php echo base_url(); ?>CPerfil/update', $('#form_perfil').serialize()+'&'+$.param({'actions_ids':$('#actions_ids').val(), 'data':data}), function (response) {
				//~ alert(response);
				if (response[0] == 'e') {
                    swal("Disculpe,", "este nombre de perfil se encuentra registrado");
                    $('#name').parent('div').addClass('has-error');
                }else{
					swal({
						title: "Actualizar",
						 text: "Registro actualizado con exito",
						  type: "success" 
						},
					function(){
						window.location.href = '<?php echo base_url(); ?>profile';
					});
				}
            });
        }
    });
});

</script>
