<?php

// Decodificar datos json
$data = json_decode($_GET['json']);

$account_id = $_GET['account_id'];

?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_import_edit'); ?> </h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_import_edit'); ?></a>
            </li>
            
            <li>
                <a href="<?php echo base_url() ?>import_lb"><?php echo $this->lang->line('heading_subtitle_import_edit'); ?></a>
            </li>
           
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_import_edit'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_import_edit'); ?><small></small></h5>
				</div>
				<div class="ibox-content">
					
					<div class="row">
					
						<form id="form_import" method="post" accept-charset="utf-8" class="form-horizontal">
							
							<!-- Transacción del monto en la moneda fiduciaria -->
							<div class="col-md-6 b-r">
								<h3 class="m-t-none m-b"><?php echo $this->lang->line('heading_info2_import_edit'); ?></h3>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_user_id_import'); ?> *</label>
									<div class="col-sm-9">
										<select class="form-control m-b" name="user_id[]" id="user_id">
											<option value="0">Seleccione</option>
											<?php foreach($usuarios as $usuario){?>
											<option value="<?php echo $usuario->id; ?>"><?php echo $usuario->name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_account_import'); ?> *</label>
									<div class="col-sm-9">
										<select class="form-control m-b" name="account_id[]" id="account_id">
											<option value="0">Seleccione</option>
											<?php foreach($accounts as $cuenta){?>
											<option value="<?php echo $cuenta->id; ?>"><?php echo $cuenta->alias." - ".$cuenta->number." - ".$cuenta->coin_avr; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_project_import'); ?> *</label>
									<div class="col-sm-9">
										<select class="form-control m-b" name="project_id[]" id="project_id">
											<option value="0">Seleccione</option>
											<?php foreach($projects as $project){?>
											<option value="<?php echo $project->id; ?>"><?php echo $project->name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_type_import'); ?> *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="type[]" maxlength="200" id="type" value="<?php echo "deposit"; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_description_import'); ?> *</label>
									<div class="col-sm-9">
										<?php 
											$description1 = "";
											if($data->is_buying == ""){
												$description1 = $data->partner." is buying";
											}else{
												$description1 = $data->partner." is selling";
											}
										?>
										<input type="text" class="form-control"  name="description[]" maxlength="250" id="description" value="<?php echo $description1; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_reference_import'); ?> *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control"  name="reference[]" maxlength="20" id="reference" value="<?php echo $data->reference; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_observation_import'); ?> *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control"  name="observation[]" maxlength="250" id="observation" value="<?php echo $data->partner; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_fiduciary_currency_import'); ?> *</label>
									<div class="col-sm-9">
										<?php
											// Si estamos vendiendo, entonces sumamos el monto fiduciario a la cuenta seleccionada, si no, lo restamos
											$fiduciary_currency = 0;
											if($data->is_buying == ""){
												$fiduciary_currency = $data->fiduciary_currency;
											}else{
												$fiduciary_currency = $data->fiduciary_currency*-1;
											}
										?>
										<input type="text" class="form-control"  name="fiduciary_currency" id="fiduciary_currency" value="<?php echo $fiduciary_currency; ?>">
									</div>
								</div>
								<!--<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_real'); ?></label>
									<div class="col-sm-10">
										<div class="i-checks">
											<label>
												<input type="checkbox" name="real" id="real">
											</label>
										</div>
									</div>
								</div>-->
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_rate_import'); ?> *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control"  name="rate[]" id="rate" value="<?php echo number_format((float)$data->fiduciary_currency/(float)$data->amount_btc, 2, '.', ''); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_status_import'); ?> *</label>
									<?php
									$status1; 
									if($data->status == "Bitcoins liberados."){ 
										$status1 = "approved"; 
									}else if($data->status == "Cancelado."){
										$status1 = "denied"; 
									}else{
										$status1 = "waiting"; 
									}
									?>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="status[]" id="status" value="<?php echo $status1; ?>">
									</div>
								</div>
								<br>
								<div class="form-group">
									<div class="col-sm-2 col-sm-offset-10">
										<button class="btn btn-white" id="volver" type="button">Volver</button>
									</div>
								</div>	
							</div>
							<!-- Cierre de la transacción del monto en la moneda fiduciaria -->
						
						
							<!-- Transacción del monto en BTC -->
							<div class="col-md-6">
								<h3 class="m-t-none m-b"><?php echo $this->lang->line('heading_info3_import_edit'); ?></h3>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_user_id_import'); ?> *</label>
									<div class="col-sm-9">
										<select class="form-control m-b" name="user_id[]" id="user_id2">
											<option value="0">Seleccione</option>
											<?php foreach($usuarios as $usuario){?>
											<option value="<?php echo $usuario->id; ?>"><?php echo $usuario->name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_account_import'); ?> *</label>
									<div class="col-sm-9">
										<select class="form-control m-b" name="account_id[]" id="account_id2">
											<option value="0">Seleccione</option>
											<?php foreach($accounts as $cuenta){?>
												<?php if($cuenta->id == $account_id){ ?>
													<option value="<?php echo $cuenta->id; ?>" selected="selected"><?php echo $cuenta->alias." - ".$cuenta->number." - ".$cuenta->coin_avr; ?></option>
												<?php }else{ ?>
													<option value="<?php echo $cuenta->id; ?>" ><?php echo $cuenta->alias." - ".$cuenta->number." - ".$cuenta->coin_avr; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_project_import'); ?> *</label>
									<div class="col-sm-9">
										<select class="form-control m-b" name="project_id[]" id="project_id2">
											<option value="0">Seleccione</option>
											<?php foreach($projects as $project){?>
											<option value="<?php echo $project->id; ?>"><?php echo $project->name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_type_import'); ?> *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control"  name="type[]" maxlength="200" id="type2" value="<?php echo "withdraw"; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_description_import'); ?> *</label>
									<div class="col-sm-9">
										<?php 
											$description2 = "";
											if($data->is_buying == ""){
												$description2 = $data->partner." is buying";
											}else{
												$description2 = $data->partner." is selling";
											}
										?>
										<input type="text" class="form-control"  name="description[]" maxlength="250" id="description2" value="<?php echo $description2; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_reference_import'); ?> *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control"  name="reference[]" maxlength="20" id="reference2" value="<?php echo $data->reference; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_observation_import'); ?> *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control"  name="observation[]" maxlength="250" id="observation2" value="<?php echo $data->partner; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_amount_import'); ?> *</label>
									<div class="col-sm-9">
										<?php
											// Si estamos vendiendo, entonces restamos el monto en btc a la cuenta de la api, si no, lo sumamos
											$amount_btc = 0;
											if($data->is_buying == ""){
												$amount_btc = $data->total_btc*-1;
											}else{
												$amount_btc = $data->total_btc;
											}
										?>
										<input type="text" class="form-control"  name="amount" id="amount" value="<?php echo $amount_btc; ?>">
									</div>
								</div>
								<!--<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_real'); ?></label>
									<div class="col-sm-10">
										<div class="i-checks">
											<label>
												<input type="checkbox" name="real" id="real">
											</label>
										</div>
									</div>
								</div>-->
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_rate_import'); ?> *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="rate[]" id="rate2" value="<?php echo number_format((float)$data->fiduciary_currency/(float)$data->amount_btc, 2, '.', ''); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" ><?php echo $this->lang->line('edit_status_import'); ?> *</label>
									<?php
									$status2; 
									if($data->status == "Bitcoins liberados."){ 
										$status2 = "approved"; 
									}else if($data->status == "Cancelado."){
										$status2 = "denied"; 
									}else{
										$status2 = "waiting"; 
									}
									?>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="status[]" id="status2" value="<?php echo $status2; ?>">
									</div>
								</div>
								<br>
								<div class="form-group">
									<div class="col-sm-2">
										<input type="hidden" name="d_create[]" id="d_create" value="<?php echo $data->d_create; ?>">
										<input type="hidden" name="d_create[]" id="d_create2" value="<?php echo $data->d_create; ?>">
										<button class="btn btn-primary" id="edit" type="submit">Guardar</button>
									</div>
								</div>
							</div>
							<!-- Cierre de la transacción del monto en BTC -->
							
						</form>
						
					</div>
					
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
        url = '<?php echo base_url() ?>import_lb/';
        window.location = url;
    });

    $("#edit").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#account_id').val() == "0") {
			swal("Disculpe,", "para continuar debe seleccionar la cuenta de la transacción fiduciaria");
			$('#account_id').parent('div').addClass('has-error');
			
        } else if ($('#project_id').val() == "0") {
			swal("Disculpe,", "para continuar debe seleccionar el proyecto de la transacción fiduciaria");
			$('#project_id').parent('div').addClass('has-error');
			
        } else if ($('#type').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el tipo de intercambio de la transacción fiduciaria");
			$('#type').parent('div').addClass('has-error');
			
        } else if ($('#description').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la descripción de la transacción fiduciaria");
			$('#description').parent('div').addClass('has-error');
			
        } else if ($('#reference').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la referencia de la transacción fiduciaria");
			$('#reference').parent('div').addClass('has-error');
			
        } else if ($('#description').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la descripción de la transacción fiduciaria");
			$('#description').parent('div').addClass('has-error');
			
        } else if ($('#observation').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la observación de la transacción fiduciaria");
			$('#observation').parent('div').addClass('has-error');
			
        } else if ($('#fiduciary_currency').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el monto de la transacción fiduciaria");
			$('#fiduciary_currency').parent('div').addClass('has-error');
			
        } else if ($('#rate').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la tasa de cambio de la transacción fiduciaria");
			$('#rate').parent('div').addClass('has-error');
			
        } else if ($('#status').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el status de la transacción fiduciaria");
			$('#status').parent('div').addClass('has-error');
			
        } else if ($('#account_id2').val() == "0") {
			swal("Disculpe,", "para continuar debe seleccionar la cuenta de la transacción en BTC");
			$('#account_id2').parent('div').addClass('has-error');
			
        } else if ($('#project_id2').val() == "0") {
			swal("Disculpe,", "para continuar debe seleccionar el proyecto de la transacción en BTC");
			$('#project_id2').parent('div').addClass('has-error');
			
        } else if ($('#type2').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el tipo de intercambio de la transacción en BTC");
			$('#type2').parent('div').addClass('has-error');
			
        } else if ($('#description2').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la descripción de la transacción en BTC");
			$('#description2').parent('div').addClass('has-error');
			
        } else if ($('#reference2').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la referencia de la transacción en BTC");
			$('#reference2').parent('div').addClass('has-error');
			
        } else if ($('#description2').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la descripción de la transacción en BTC");
			$('#description2').parent('div').addClass('has-error');
			
        } else if ($('#observation2').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la observación de la transacción en BTC");
			$('#observation2').parent('div').addClass('has-error');
			
        } else if ($('#amount').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el monto de la transacción en BTC");
			$('#amount').parent('div').addClass('has-error');
			
        } else if ($('#rate2').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar la tasa de cambio de la transacción en BTC");
			$('#rate2').parent('div').addClass('has-error');
			
        } else if ($('#status2').val().trim() === "") {
			swal("Disculpe,", "para continuar debe ingresar el status de la transacción en BTC");
			$('#status2').parent('div').addClass('has-error');
			
        } else {

            $.post('<?php echo base_url(); ?>CImport/import', $('#form_import').serialize(), function (response) {
				if (response[0] == '1') {
                    swal("Disculpe,", "esta transacción se encuentra registrada");
                }else{
					swal({ 
						title: "Actualizar",
						 text: "Guardado con exito",
						  type: "success" 
						},
					function(){
					  window.location.href = '<?php echo base_url(); ?>import_lb';
					});
				}
            });
            
        }
        
    });
    
});

</script>
