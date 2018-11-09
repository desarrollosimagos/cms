<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_projects_registry'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_projects_registry'); ?></a>
            </li>
            
            <li>
                <a href="<?php echo base_url() ?>projects"><?php echo $this->lang->line('heading_subtitle_projects_registry'); ?></a>
            </li>
            
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_projects_registry'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_projects_registry'); ?><small></small></h5>
					
				</div>
				<div class="ibox-content">
					<form id="form_proyectos" method="post" accept-charset="utf-8" class="form-horizontal">
						<ul class="nav nav-tabs">
						  <li class="active"><a data-toggle="tab" href="#home"><?php echo $this->lang->line('tab_data_projects_registry'); ?></a></li>
						  <li><a data-toggle="tab" href="#menu1"><?php echo $this->lang->line('tab_docs_projects_registry'); ?></a></li>
						  <li><a data-toggle="tab" href="#menu2"><?php echo $this->lang->line('tab_readings_projects_registry'); ?></a></li>
						  <li><a data-toggle="tab" href="#menu3"><?php echo $this->lang->line('tab_images_projects_registry'); ?></a></li>
						</ul>
						
						<!-- Tab content -->
						<div class="tab-content">
							
							<!-- Datos -->
							<div id="home" class="tab-pane fade in active">
								<br>
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_name_projects'); ?> *</label>
										<div class="col-sm-10">
											<input type="text" class="form-control"  maxlength="100" name="name" id="name">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_description_projects'); ?></label>
										<div class="col-sm-10">
											<!--<input type="text" class="form-control" name="description" maxlength="150" id="description">-->
											<textarea cols="46" name="description" id="description" cols="52"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_amount_rise_projects'); ?></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="amount_r" id="amount_r">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_amount_min_projects'); ?></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="amount_min" id="amount_min">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_amount_max_projects'); ?></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="amount_max" id="amount_max">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_currency_projects'); ?> *</label>
										<div class="col-sm-6">
											<select class="form-control m-b" name="coin_id" id="coin_id">
												<option value="0" selected="">Seleccione</option>
												<?php foreach($monedas as $moneda){?>
												<option value="<?php echo $moneda->id; ?>"><?php echo $moneda->abbreviation." (".$moneda->description.")"; ?></option>
												<?php }?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_type_projects'); ?> *</label>
										<div class="col-sm-6">
											<select class="form-control m-b" name="type" id="type">
												<option value="0">Seleccione</option>
												<?php foreach($project_types as $type){?>
												<option value="<?php echo $type->id; ?>"><?php echo $type->type; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_value_projects'); ?> *</label>
										<div class="col-sm-6">
											<input type="text" class="form-control"  name="valor" id="valor">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_date_projects'); ?> *</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" maxlength="10" name="date" id="date">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_return_date_projects'); ?></label>
										<div class="col-sm-6">
											<input type="text" class="form-control" maxlength="10" name="date_r" id="date_r">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_validity_date_projects'); ?></label>
										<div class="col-sm-6">
											<input type="text" class="form-control" maxlength="10" name="date_v" id="date_v">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_public_projects'); ?></label>
										<div class="col-sm-6">
											<input type="checkbox" name="public" id="public">
										</div>
									</div>
								</div>
							</div>
							
							<!-- Documentos -->
							<div id="menu1" class="tab-pane fade">
								<br>
								<div class="col-lg-6">
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 1</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 2</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 3</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 4</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 5</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 6</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 7</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 8</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 9</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_document_projects'); ?> 10</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="documento[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
								</div>
							</div>
							
							<!-- Lecturas Recomendadas -->
							<div id="menu2" class="tab-pane fade">
								<br>
								<div class="col-lg-6">
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 1</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 2</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 3</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 4</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 5</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 6</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 7</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 8</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 9</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reading_projects'); ?> 10</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="lectura[]" onChange="valida_tipo_document($(this))">
										</div>
									</div>
								</div>
							</div>
							
							<!-- Fotos -->
							<div id="menu3" class="tab-pane fade">
								<br>
								<div class="col-lg-6">
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 1</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 2</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 3</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 4</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 5</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 6</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 7</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 8</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 9</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_image_projects'); ?> 10</label>
										<div class="col-sm-10">
											<input type="file" class="form-control" name="imagen[]" onChange="valida_tipo($(this))">
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<!-- Cierre Tab content -->
						
						<!-- Enviar-->
						<div class="form-group">
							<div class="col-sm-12">
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('registry_back_projects'); ?></button>
								<button class="btn btn-primary" id="registrar" type="submit"><?php echo $this->lang->line('registry_save_projects'); ?></button>
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
    
    $('#date').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
    })
    
    $('#date_r').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
    })
    
    $('#date_v').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
    })

    $('#volver').click(function () {
        url = '<?php echo base_url() ?>projects/';
        window.location = url;
    });
    
    $("#valor").numeric(); //Valida solo permite valores numéricos
    $("#amount_r").numeric(); //Valida solo permite valores numéricos
    $("#amount_min").numeric(); //Valida solo permite valores numéricos
    $("#amount_max").numeric(); //Valida solo permite valores numéricos

    $("#registrar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#name').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el nombre del proyecto");
			$('#name').parent('div').addClass('has-error');
			
        } else if ($('#type').val() == "0") {
			swal("Disculpe,", "para continuar debe seleccionar el tipo de proyecto");
			$('#type').parent('div').addClass('has-error');
			
        } else if ($('#valor').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el valor del proyecto");
			$('#valor').parent('div').addClass('has-error');
			
        } else if ($('#coin_id').val() == '0') {
			
		  swal("Disculpe,", "para continuar debe seleccionar la moneda");
	       $('#coin_id').parent('div').addClass('has-error');
		   
		} else if ($('#date').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la fecha del proyecto");
			$('#date').parent('div').addClass('has-error');
			
        } else {
            
            // Formateamos los precios para usar coma en vez de punto
            //~ $("#valor").val(String($("#valor").val()).replace('.',','));
            //~ 
            //~ alert($("#valor").val());
            
            var formData = new FormData(document.getElementById("form_proyectos"));  // Forma de capturar todos los datos del formulario
			
			$.ajax({
				//~ method: "POST",
				type: "post",
				dataType: "json",
				url: '<?php echo base_url(); ?>CProjects/add',
				data: formData,
				cache: false,
				contentType: false,
				processData: false
			})
			.done(function(response) {
				if(response.error){
					console.log(response.error);
				} else {
					if (response['response'] == 'error1') {
					
						swal("Disculpe,", "este proyecto se encuentra registrado");
						
					}else if (response['response'] == 'error2') {
						
						swal("Disculpe,", "ha ocurrido un error al guardar las fotos");
						
					}else if (response['response'] == 'error3') {
						
						swal("Disculpe,", "ha ocurrido un error al guardar los documentos");
						
					}else if (response['response'] == 'error4') {
						
						swal("Disculpe,", "ha ocurrido un error al guardar las lecturas recomendadas");
						
					}else{
						
						swal({ 
							title: "Registro",
							 text: "Guardado con exito",
							  type: "success" 
							},
						function(){
						  window.location.href = '<?php echo base_url(); ?>projects';
						});
						
					}
				}				
			}).fail(function() {
				console.log("error ajax");
			});
        }
    });
    
});

// Validamos que los archivos sean de tipo .jpg, jpeg o png
function valida_tipo(input) {
	
	var max_size = '';
	var archivo = input.val();
	
	var ext = archivo.split(".");
	ext = ext[1];
	
	if (ext != 'jpg' && ext != 'jpeg' && ext != 'png'){
		swal("Disculpe,", "sólo se admiten archivos .jpg, .jpeg y png");
		input.val('');
		input.parent('div').addClass('has-error');
	}else{
		input.parent('div').removeClass('has-error');
	}
}

// Validamos que los archivos sean de tipo .pdf y no excedan los 2 MB de tamaño
function valida_tipo_document(input) {
	
	var max_size = '';
	var archivo = input.val();
	
	var ext = archivo.split(".");
	ext = ext[1];
	
	//~ alert(input.attr("id"));
	//~ alert(input[0].files[0].size);
	
	if(input[0].files[0].size > 10000000){
		swal("Disculpe,", "los archivos no deben exceder los 2 MB");
		input.val('');
		input.parent('div').addClass('has-error');
	}else if (ext != 'pdf'){
		swal("Disculpe,", "sólo se admiten archivos .pdf");
		input.val('');
		input.parent('div').addClass('has-error');
	}else{
		input.parent('div').removeClass('has-error');
	}
}

</script>
