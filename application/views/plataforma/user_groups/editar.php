<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_investorgroups_edit'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_investorgroups_edit'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url() ?>user_groups"><?php echo $this->lang->line('heading_subtitle_investorgroups_edit'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_investorgroups_edit'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_investorgroups_edit'); ?> <small></small></h5>
					
				</div>
				<div class="ibox-content">
					<form id="form_group" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_name_investorgroups'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="<?php echo $this->lang->line('edit_name_placeholder_investorgroups'); ?>" name="name" id="name" value="<?php echo $editar[0]->name ?>">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_projects_investorgroups'); ?></label>
							<div class="col-sm-10">
								<select id="projects_ids" class="form-control" multiple="multiple">
									<?php
									// Primero creamos un arreglo con la lista de ids de proyectos proveniente del controlador
									$ids_projects = explode(",",$ids_projects);
									foreach ($projects as $project) {
										// Si el id del proyecto está en el arreglo lo marcamos, si no, se imprime normalmente
										if(in_array($project->id, $ids_projects)){
										?>
										<option selected="selected" value="<?php echo $project->id; ?>"><?php echo $project->name; ?></option>
										<?php
										}else{
										?>
										<option value="<?php echo $project->id; ?>"><?php echo $project->name; ?></option>
										<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_investors_investorgroups'); ?></label>
							<div class="col-sm-10">
								<select id="users_ids" class="form-control" multiple="multiple">
									<?php
									// Primero creamos un arreglo con la lista de ids de usuarios proveniente del controlador
									$ids_users = explode(",",$ids_users);
									foreach ($inversores as $inversor) {
										// Si el id del usuario está en el arreglo lo marcamos, si no, se imprime normalmente
										if(in_array($inversor->id, $ids_users)){
										?>
										<option selected="selected" value="<?php echo $inversor->id; ?>"><?php echo $inversor->username; ?></option>
										<?php
										}else{
										?>
										<option value="<?php echo $inversor->id; ?>"><?php echo $inversor->username; ?></option>
										<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_accounts_investorgroups'); ?></label>
							<div class="col-sm-10">
								<select id="accounts_ids" class="form-control" multiple="multiple">
									<?php
									// Primero creamos un arreglo con la lista de ids de cuentas proveniente del controlador
									$ids_accounts = explode(",",$ids_accounts);
									foreach ($accounts as $account) {
										// Si el id de la cuenta está en el arreglo lo marcamos, si no, se imprime normalmente
										if(in_array($account->id, $ids_accounts)){
										?>
										<option selected="selected" value="<?php echo $account->id; ?>"><?php echo $account->alias." - ".$account->number; ?></option>
										<?php
										}else{
										?>
										<option value="<?php echo $account->id; ?>"><?php echo $account->alias." - ".$account->number; ?></option>
										<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<input class="form-control"  type='hidden' id="id" name="id" value="<?php echo $id ?>"/>
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('edit_back_investorgroups'); ?></button>
								<button class="btn btn-primary" id="edit" type="submit"><?php echo $this->lang->line('edit_save_investorgroups'); ?></button>
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
        url = '<?php echo base_url() ?>user_groups/';
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
			
        } else if ($('#projects_ids').val() == "") {
          
			swal("Disculpe,", "para continuar debe seleccionar los proyectos");
			$('#projects_ids').parent('div').addClass('has-error');
			
        } else if ($('#users_ids').val() == "") {
          
			swal("Disculpe,", "para continuar debe seleccionar los usuarios");
			$('#users_ids').parent('div').addClass('has-error');
			
        } else if ($('#accounts_ids').val() == "") {
          
			swal("Disculpe,", "para continuar debe seleccionar las cuentas");
			$('#accounts_ids').parent('div').addClass('has-error');
			
        } else {
			//~ alert(String($('#accounts_ids').val()));
			
            $.post('<?php echo base_url(); ?>CUserGroups/update', $('#form_group').serialize()+'&'+$.param({'users_ids':$('#users_ids').val(), 'accounts_ids':$('#accounts_ids').val(), 'projects_ids':$('#projects_ids').val()}), function (response) {
				//~ alert(response);
				if (response[0] == 'e') {
                    swal("Disculpe,", "este nombre de grupo se encuentra registrado");
                    $('#name').parent('div').addClass('has-error');
                }else{
					swal({
						title: "Actualizar",
						 text: "Registro actualizado con exito",
						  type: "success" 
						},
					function(){
						window.location.href = '<?php echo base_url(); ?>user_groups';
					});
				}
            });
        }
    });
});

</script>
