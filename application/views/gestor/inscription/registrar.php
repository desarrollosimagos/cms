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
									<option value="<?php echo $usuario->id; ?>"><?php echo $usuario->username; ?></option>
									<?php }?>
								</select>
								<div class="alert alert-danger" id="category-message" style="display:none;">
									<?php echo $this->lang->line('registry_message_category_inscription'); ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_project_inscription'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="project_id" id="project_id">
									<option value="0" selected="">Seleccione</option>
									<?php foreach($proyectos as $proyecto){?>
										<?php if($proyecto->inscription_available == 'yes'){?>
											<?php if($project_id != '' && $project_id == $proyecto->id){?>
												<option value="<?php echo $proyecto->id; ?>" selected="selected"><?php echo $proyecto->name; ?></option>
											<?php }else{ ?>
												<option value="<?php echo $proyecto->id; ?>"><?php echo $proyecto->name; ?></option>
											<?php } ?>
										<?php } ?>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_category_inscription'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="category" id="category">
									<option value="0" selected="">Seleccione</option>
									
								</select>
								<!-- Spinner de carga de categorías -->
								<div class="sk-spinner sk-spinner-circle" id="load_categories" style="float: left !important;">
									<div class="sk-circle1 sk-circle"></div>
									<div class="sk-circle2 sk-circle"></div>
									<div class="sk-circle3 sk-circle"></div>
									<div class="sk-circle4 sk-circle"></div>
									<div class="sk-circle5 sk-circle"></div>
									<div class="sk-circle6 sk-circle"></div>
									<div class="sk-circle7 sk-circle"></div>
									<div class="sk-circle8 sk-circle"></div>
									<div class="sk-circle9 sk-circle"></div>
									<div class="sk-circle10 sk-circle"></div>
									<div class="sk-circle11 sk-circle"></div>
									<div class="sk-circle12 sk-circle"></div>
								</div>
								<!-- Cierre de spinner de carga de categorías -->
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<button class="btn btn-white" id="volver" type="button" style="float: left !important;">Volver</button>
								<button class="btn btn-primary" id="registrar" type="submit" style="float: left !important;">Guardar</button>
								<!-- Spinner de carga de registro de inscripción -->
								<div class="sk-spinner sk-spinner-circle" id="load_inscription" style="float: left !important;">
									<div class="sk-circle1 sk-circle"></div>
									<div class="sk-circle2 sk-circle"></div>
									<div class="sk-circle3 sk-circle"></div>
									<div class="sk-circle4 sk-circle"></div>
									<div class="sk-circle5 sk-circle"></div>
									<div class="sk-circle6 sk-circle"></div>
									<div class="sk-circle7 sk-circle"></div>
									<div class="sk-circle8 sk-circle"></div>
									<div class="sk-circle9 sk-circle"></div>
									<div class="sk-circle10 sk-circle"></div>
									<div class="sk-circle11 sk-circle"></div>
									<div class="sk-circle12 sk-circle"></div>
								</div>
								<!-- Cierre de spinner de carga de registro de inscripción -->
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
        url = '<?php echo base_url() ?>investments/';
        window.location = url;
    });
    
    // El elemento que se quiere activar (ícono de carga) si hay una petición ajax en proceso.
	var cargando_categorias = $("#load_categories");
	cargando_categorias.hide();
    
    // Proceso de carga de categorías en el combo al cargar la página de inscripción
    if($("#user_id").val() != "0" && $("#project_id").val() != "0"){
		
		// evento ajax start
		$(document).ajaxStart(function() {
			if($("#category").val() == "0"){
				cargando_categorias.show();
			}
		});

		// evento ajax stop
		$(document).ajaxStop(function() {
		cargando_categorias.hide();
		});
		
		$.post('<?php echo base_url(); ?>CInscription/load_categories', {'user_id':$("#user_id").val(), 'project_id':$("#project_id").val()}, function (response) {
				
			if (response['response'] == 'no_birthday') {
				
				// Hacemos visible el mensaje de advertencia si el usuario a inscribir no ha cargado su fecha de nacimiento
				$("#category-message").show();
				
			}else{
				
				// Ocultamos el mensaje de alerta
				$("#category-message").hide();
				
				// Primero borramos las opciones que tenga
				$('#category').find('option:gt(0)').remove().end().select2('val', '0');
				
				// Cargamos las categorías correspondientes en el combo
				$.each(response, function(i, item) {
					$("#category").append('<option value="'+item.result+'">'+item.result+'</option>');
				});
				
			}
			
		}, 'json');
		
	}
	
	// Proceso de carga de categorías en el combo al cambiar la selección del usuario o el proyecto
	$("#user_id, #project_id").change(function (e) {
		
		if($("#user_id").val() != "0" && $("#project_id").val() != "0"){
			
			// evento ajax start
			$(document).ajaxStart(function() {
				if($("#category").val() == "0"){
					cargando_categorias.show();
				}
			});

			// evento ajax stop
			$(document).ajaxStop(function() {
			cargando_categorias.hide();
			});
			
			$.post('<?php echo base_url(); ?>CInscription/load_categories', {'user_id':$("#user_id").val(), 'project_id':$("#project_id").val()}, function (response) {
				
				if (response['response'] == 'no_birthday') {
					
                    // Hacemos visible el mensaje de advertencia si el usuario a inscribir no ha cargado su fecha de nacimiento
                    $("#category-message").show();
					
                }else{
					
					// Ocultamos el mensaje de alerta
					$("#category-message").hide();
					
					// Primero borramos las opciones que tenga
					$('#category').find('option:gt(0)').remove().end().select2('val', '0');
					
					// Cargamos las categorías correspondientes en el combo
					$.each(response, function(i, item) {
						$("#category").append('<option value="'+item.result+'">'+item.result+'</option>');
					});
					
				}
				
            }, 'json');
			
		}
		
	});
	
	// El elemento que se quiere activar (ícono de carga) si hay una petición ajax en proceso.
	var cargando_inscripcion = $("#load_inscription");
	cargando_inscripcion.hide();
	
	// Proceso de registro de la inscripción
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
			
        } else if ($('#category').val() == "0") {
			
			swal("Disculpe,", "para continuar debe seleccionar la categoría");
			$('#category').focus();
			$('#category').parent('div').addClass('has-error');
			
        } else {
			
			// evento ajax start
			$(document).ajaxStart(function() {
			cargando_inscripcion.show();
			});

			// evento ajax stop
			$(document).ajaxStop(function() {
			cargando_inscripcion.hide();
			});

            $.post('<?php echo base_url(); ?>CInscription/add', $('#form_inscription').serialize(), function (response) {
				
				if (response['response'] == 'no_birthday') {
					
                    //~ swal("Disculpe", "Debe ingresar su fecha de nacimineto para poder inscribirse...");
                    swal({
						title: "Actualizar datos de perfil",
						text: "Debe ingresar su fecha de nacimineto para poder inscribirse, ¿desea hacerlo ahora?",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Actualizar perfil",
						cancelButtonText: "No",
						closeOnConfirm: false,
						closeOnCancel: true
					}, function(isConfirm){
						
						if (isConfirm) {
							
							window.location.href = '<?php echo base_url(); ?>profileuser';
							
						}
						
					});
					
                }else if (response['response'] == 'contract_exists') {
                    swal("Disculpe", "Usted ya se ha inscrito en este evento...");
                }else if (response['response'] == 'error2') {
                    swal("Disculpe", "Las reglas del contrato no han podido ser completamente registradas...");
                }else{
					
					swal({
						title: "Registro",
						text: "Guardado con exito, ¿desea hacer el pago ahora?",
						type: "success",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Pagar",
						cancelButtonText: "No",
						closeOnConfirm: false,
						closeOnCancel: true
					}, function(isConfirm){
						
						if (isConfirm) {
							
							window.location.href = '<?php echo base_url(); ?>payments';
							
						}else{
							
							window.location.href = '<?php echo base_url(); ?>investments';
							
						}
						
					});
					
				}
            }, 'json');
            
        }
    });
});

</script>
