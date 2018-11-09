<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_profiles_registry'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_profiles_registry'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url() ?>profile"><?php echo $this->lang->line('heading_subtitle_profiles_registry'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_profiles_registry'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_profiles_registry'); ?><small></small></h5>
				</div>
				<div class="ibox-content">
					<form id="form_perfil" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_name_profiles'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control"  placeholder="Introduzca nombre" name="name" id="name">
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_actions_profiles'); ?></label>
							<div class="col-sm-10">
								<select id="actions_ids" class="form-control" multiple="multiple">
									<?php
									foreach ($acciones as $accion) {
										?>
										<option value="<?php echo $accion->id; ?>"><?php echo $accion->name; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('registry_back_profiles'); ?></button>
								<button class="btn btn-primary" id="registrar" type="submit"><?php echo $this->lang->line('registry_save_profiles'); ?></button>
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
        url = '<?php echo base_url() ?>profile/';
        window.location = url;
    });

    $("#registrar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#name').val().trim() === "") {
          
			swal("Disculpe,", "para continuar debe ingresar nombre");
			$('#name').parent('div').addClass('has-error');
			
        } else if ($('#actions_ids').val() == "") {
          
			swal("Disculpe,", "para continuar debe seleccionar los permisos");
			$('#actions_ids').parent('div').addClass('has-error');
			
        } else {
			//~ alert(String($('#actions_ids').val()));

            $.post('<?php echo base_url(); ?>CPerfil/add', $('#form_perfil').serialize()+'&'+$.param({'actions_ids':$('#actions_ids').val()}), function (response) {
				//~ alert(response);
				if (response == 'existe') {
                    swal("Disculpe,", "este nombre de perfil se encuentra registrado");
                    $('#name').parent('div').addClass('has-error');
                }else{
					swal({ 
						title: "Registro",
						 text: "Guardado con exito",
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
