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
					<form id="form_services" method="post" accept-charset="utf-8" class="form-horizontal">
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

        if ($('#name').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar nombre");
			$('#name').parent('div').addClass('has-error');
			
        } else if ($('#description').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la descripción");
			$('#description').parent('div').addClass('has-error');
			
        } else if ($('#icon').val().trim() == '1' || $('#icon').val().trim() == '') {
			swal("Disculpe,", "para continuar debe seleccionar una imagen");
			$('#icon').parent('div').addClass('has-error');
			
        } else if ($('#price').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el precio");
			$('#price').parent('div').addClass('has-error');
			
        }  else {

            //~ $.post('<?php echo base_url(); ?>CServices/add', $('#form_services').serialize(), function (response) {
				//~ if (response[0] == '1') {
                    //~ swal("Disculpe,", "este nombre se encuentra registrado");
                //~ }else{
					//~ swal({ 
						//~ title: "Registro",
						 //~ text: "Guardado con exito",
						  //~ type: "success" 
						//~ },
					//~ function(){
					  //~ window.location.href = '<?php echo base_url(); ?>services';
					//~ });
				//~ }
            //~ });
            
            var formData = new FormData(document.getElementById("form_services"));  // Forma de capturar todos los datos del formulario
			
			$.ajax({
				//~ method: "POST",
				type: "post",
				dataType: "html",
				url: '<?php echo base_url(); ?>CServices/add',
				data: formData,
				cache: false,
				contentType: false,
				processData: false
			})
			.done(function(data) {
				if(data.error){
					console.log(data.error);
				} else {
					if (data[0] == '1') {
						swal("Disculpe,", "este servicio se encuentra registrado");
					}else{
						swal({ 
							title: "Registro",
							 text: "Guardado con exito",
							  type: "success" 
							},
						function(){
						  window.location.href = '<?php echo base_url(); ?>services';
						});
					}
				}				
			}).fail(function() {
				console.log("error ajax");
			});
        }
    });
});

</script>
