<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_accounts_registry'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_accounts_registry'); ?></a>
            </li>
            
            <li>
                <a href="<?php echo base_url() ?>accounts"><?php echo $this->lang->line('heading_subtitle_accounts_registry'); ?></a>
            </li>
            
            <li class="active">
                <strong><?php echo $this->lang->line('heading_info_accounts_registry'); ?></strong>
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
					<h5><?php echo $this->lang->line('heading_info_accounts_registry'); ?><small></small></h5>
					
				</div>
				<div class="ibox-content">
					<form id="form_cuentas" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_person_accounts'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="owner" id="owner">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_name_accounts'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="alias" id="alias">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_number_accounts'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="number" id="number">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_type_accounts'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="type" id="type">
									<option value="0" selected="">Seleccione</option>
									<?php foreach($account_type as $tipo){?>
									<option value="<?php echo $tipo->id; ?>"><?php echo $tipo->name; ?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_description_accounts'); ?></label>
							<div class="col-sm-10">
								<textarea class="form-control" name="description" maxlength="250" id="description"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_amount_accounts'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="amount" id="amount" value="0" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_coin_accounts'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="coin_id" id="coin_id">
									<option value="0" selected="">Seleccione</option>
									<?php foreach($monedas as $moneda){?>
									<option value="<?php echo $moneda->id; ?>"><?php echo $moneda->abbreviation." (".$moneda->description.")"; ?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_status_accounts'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="status" id="status">
									<option value="1" selected=""><?php echo $this->lang->line('registry_status1_accounts'); ?></option>
									<option value="0"><?php echo $this->lang->line('registry_status2_accounts'); ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('registry_back_accounts'); ?></button>
								<button class="btn btn-primary" id="registrar" type="submit"><?php echo $this->lang->line('registry_save_accounts'); ?></button>
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
        url = '<?php echo base_url() ?>accounts/';
        window.location = url;
    });
    
    $("#number").numeric(); // Sólo permite valores numéricos

    $("#registrar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        if ($('#owner').val().trim() === "") {
			
			swal("Disculpe,", "para continuar debe ingresar el nombre de la persona");
			$('#owner').parent('div').addClass('has-error');
			
        } else if ($('#alias').val().trim() === "") {
			
			swal("Disculpe,", "para continuar debe ingresar el nombre de la cuenta");
			$('#alias').parent('div').addClass('has-error');
			
        } else if($('#type').val() == "0"){
			
			swal("Disculpe,", "para continuar debe seleccionar el tipo de cuenta");
			$('#tipo').parent('div').addClass('has-error');
			
		} else if($('#coin_id').val() == "0"){
			
			swal("Disculpe,", "para continuar debe seleccionar el tipo de moneda");
			$('#coin_id').parent('div').addClass('has-error');
			
		} else {

            $.post('<?php echo base_url(); ?>CCuentas/add', $('#form_cuentas').serialize(), function (response) {
				if (response['response'] == 'error') {
                    swal("Disculpe,", "El registro no pudo ser guardado, por favor consulte a su administrador...");
                }else{
					swal({ 
						title: "Registro",
						 text: "Guardado con exito",
						  type: "success" 
						},
					function(){
					  window.location.href = '<?php echo base_url(); ?>accounts';
					});
				}
            }, 'json');
            
        }
    });
});

</script>
