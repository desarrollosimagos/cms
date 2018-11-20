<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_inscription_registry'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_inscription_registry'); ?></a>
            </li>
            
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_inscription_registry'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_inscription_registry'); ?> <small></small></h5>
					
				</div>
				<div class="ibox-content">
					<form id="form_inscription" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_user_inscription'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="user_id" id="user_id">
									<option value="0" selected="">Seleccione</option>
									<?php foreach($usuarios as $usuario){?>
										<?php if($usuario->id == $this->session->userdata('logged_in')['id']){?>
											<option value="<?php echo $usuario->id; ?>" selected="selected"><?php echo $usuario->username; ?></option>
										<?php }else{ ?>
											<option value="<?php echo $usuario->id; ?>"><?php echo $usuario->username; ?></option>
										<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_project_inscription'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="project_id" id="project_id">
									<option value="0" selected="">Seleccione</option>
									<?php foreach($proyectos as $proyecto){?>
										<?php if($project_id != '' && $project_id == $proyecto->id){?>
											<option value="<?php echo $proyecto->id; ?>" selected="selected"><?php echo $proyecto->name; ?></option>
										<?php }else{ ?>
											<option value="<?php echo $proyecto->id; ?>"><?php echo $proyecto->name; ?></option>
										<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
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
        url = '<?php echo base_url() ?>services/';
        window.location = url;
    });

	// Validamos que el archivo sea de tipo .jpg, jpeg o png
	$("#icon").change(function (e) {
		e.preventDefault();  // Para evitar que se envíe por defecto
		
		var max_size = '';
		var archivo = $(this).val();
		
		var ext = archivo.split(".");
		ext = ext[1];
		
		if (ext != 'jpg' && ext != 'jpeg' && ext != 'png'){
			swal("Disculpe,", "sólo se admiten archivos .jpg, .jpeg y png");
			$("#icon").val('');
			$('#icon').parent('div').addClass('has-error');
		}
	});

    $("#registrar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#user_id').val() == "0") {
			
			swal("Disculpe,", "para continuar debe seleccionar el usuario");
			$('#user_id').focus();
			$('#user_id').parent('div').addClass('has-error');
			
        } else if ($('#project_id').val() == "0") {
			
			swal("Disculpe,", "para continuar debe seleccionar el proyecto");
			$('#project_id').focus();
			$('#project_id').parent('div').addClass('has-error');
			
        } else {

            $.post('<?php echo base_url(); ?>CInscription/add', $('#form_inscription').serialize(), function (response) {
				if (response['response'] == 'error') {
                    swal("Disculpe", "Usted ya se ha inscrito en este evento...");
                }else if (response['response'] == 'error2') {
                    swal("Disculpe", "Usted ya se ha inscrito en este evento...");
                }else{
					swal({ 
						title: "Registro",
						text: "Guardado con exito",
						type: "success" 
					}, function(){
					  window.location.href = '<?php echo base_url(); ?>home';
					});
				}
            }, 'json');
            
        }
    });
});

</script>
