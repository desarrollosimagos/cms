<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_actions_edit'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_actions_edit'); ?></a>
            </li>
            
            <li>
                <a href="<?php echo base_url() ?>actions"><?php echo $this->lang->line('heading_subtitle_actions_edit'); ?></a>
            </li>
           
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_actions_edit'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_actions_edit'); ?><small></small></h5>
					
				</div>
				<div class="ibox-content">
					<form id="form_acciones" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_name_actions'); ?> *</label>
							<div class="col-sm-10"><input type="text" class="form-control" style="text-transform:uppercase;" name="name" id="name" maxlength="150" value="<?php echo $editar[0]->name ?>"></div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_class_actions'); ?></label>
							<div class="col-sm-10">
								<select name="class" id="class" class="form-control">
									<option value="0">Seleccione</option>
									<?php
									foreach ($controladores as $controlador) {
										?>
										<option value="<?php echo $controlador ?>"><?php echo $controlador ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('edit_route_actions'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" maxlength="100" name="route" id="route" value="<?php echo $editar[0]->route ?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<input type='hidden' id="id_class" value="<?php echo $editar[0]->class;?>"/>
								<input class="form-control"  type='hidden' id="id" name="id" value="<?php echo $id ?>"/>
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('edit_back_actions'); ?></button>
								<button class="btn btn-primary" id="edit" type="submit"><?php echo $this->lang->line('edit_save_actions'); ?></button>
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
        url = '<?php echo base_url() ?>actions/';
        window.location = url;
    });
    
    $("#class").select2('val', $("#id_class").val());

    $("#edit").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#name').val().trim() === "") {
          
			swal("Disculpe,", "para continuar debe ingresar el nombre");
			$('#name').parent('div').addClass('has-error');
			
        } else if ($('#class').val().trim() === "0") {
			
			swal("Disculpe,", "para continuar debe ingresar la clase");
			$('#class').parent('div').addClass('has-error');
			$('#class').focus();
			
        } else if ($('#route').val().trim() === "") {
			
			swal("Disculpe,", "para continuar debe ingresar la ruta");
			$('#route').parent('div').addClass('has-error');
			$('#route').focus();
			
        } else {

            $.post('<?php echo base_url(); ?>CAcciones/update', $('#form_acciones').serialize(), function (response) {

				if (response[0] == '1') {
                    swal("Disculpe,", "este nombre se encuentra registrado");
                }else{
					swal({ 
						title: "Actualizar",
						 text: "Actualizado con exito",
						  type: "success" 
						},
					function(){
					  window.location.href = '<?php echo base_url(); ?>actions';
					});
				

				}

            });
        }

    });
});

</script>
