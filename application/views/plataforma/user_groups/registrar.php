<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_investorgroups_registry'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_investorgroups_registry'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url() ?>user_groups"><?php echo $this->lang->line('heading_subtitle_investorgroups_registry'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_investorgroups_registry'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_investorgroups_registry'); ?><small></small></h5>
				</div>
				<div class="ibox-content">
					<form id="form_group" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_name_investorgroups'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="<?php echo $this->lang->line('registry_name_placeholder_investorgroups'); ?>" name="name" id="name">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_projects_investorgroups'); ?></label>
							<div class="col-sm-10">
								<select id="projects_ids" class="form-control" multiple="multiple">
									<?php
									foreach ($projects as $project) {
										?>
										<option value="<?php echo $project->id; ?>"><?php echo $project->name; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_investors_investorgroups'); ?></label>
							<div class="col-sm-10">
								<select id="users_ids" class="form-control" multiple="multiple">
									<?php
									foreach ($inversores as $inversor) {
										?>
										<option value="<?php echo $inversor->id; ?>"><?php echo $inversor->username; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_accounts_investorgroups'); ?></label>
							<div class="col-sm-10">
								<select id="accounts_ids" class="form-control" multiple="multiple">
									<?php
									foreach ($accounts as $account) {
										?>
										<option value="<?php echo $account->id; ?>"><?php echo $account->alias." - ".$account->number; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('registry_back_investorgroups'); ?></button>
								<button class="btn btn-primary" id="registrar" type="submit"><?php echo $this->lang->line('registry_save_investorgroups'); ?></button>
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

    $('#volver').click(function () {
        url = '<?php echo base_url() ?>user_groups/';
        window.location = url;
    });

    $("#registrar").click(function (e) {

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
			//~ alert(String($('#users_ids').val()));

            $.post('<?php echo base_url(); ?>CUserGroups/add', $('#form_group').serialize()+'&'+$.param({'users_ids':$('#users_ids').val(), 'accounts_ids':$('#accounts_ids').val(), 'projects_ids':$('#projects_ids').val()}), function (response) {
				//~ alert(response);
				if (response == 'existe') {
                    swal("Disculpe,", "este nombre de grupo se encuentra registrado");
                    $('#name').parent('div').addClass('has-error');
                }else{
					swal({ 
						title: "Registro",
						 text: "Guardado con exito",
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
