<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_import'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_import'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_subtitle_import'); ?></strong>
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
			<div class="col-md-6">
				<div class="form-group">
					<form id="form_account" action="<?php echo base_url()."import_lb"; ?>" method="POST">
						<div class="col-sm-4">
							<a id="load">
								<button class="btn btn-outline btn-primary dim" type="button">
									<i class="fa fa-plus"></i> 
									<?php echo $this->lang->line('btn_load_import'); ?>
								</button>
							</a>
						</div>
						<label class="col-sm-2 control-label" ><?php echo $this->lang->line('list_select_account'); ?></label>
						<div class="col-sm-6">
							<select class="form-control m-b" name="account_id" id="account_id">
								<option value="0">Seleccione</option>
								<?php foreach($accounts as $cuenta){?>
									<?php if(isset($_POST['account_id']) && $_POST['account_id'] == $cuenta->id){ ?>
										<option value="<?php echo $cuenta->id; ?>" selected="selected"><?php echo $cuenta->alias." - ".$cuenta->number." - ".$cuenta->coin_avr; ?></option>
									<?php }else{ ?>
										<option value="<?php echo $cuenta->id; ?>"><?php echo $cuenta->alias." - ".$cuenta->number." - ".$cuenta->coin_avr; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
							<!-- Campo oculto para almacenar los datos json de la api asociada a la cuenta -->
							<input type="hidden" value="" name="json_api" id="json_api">
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-6">
				
			</div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo $this->lang->line('list_title_import'); ?></h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tab_import" class="table table-striped table-bordered dt-responsive table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $this->lang->line('list_create_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_type_trade_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_partner_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_advertiser_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_status_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_fiduciary_currency_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_amount_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_commission_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_total_btc_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_exchange_rate_import'); ?></th>
                                    <th><?php echo $this->lang->line('list_edit_import'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php // Si la data viene como array() es porque no tiene datos; si es un objeto, lo recorremos ?>
                                <?php if(!is_array($listar)){ ?>
									<?php foreach ($listar->data->contact_list as $j) { ?>
										<tr style="text-align: center">
											<td><?php echo $j->data->contact_id;?></td>
											<td><?php echo date_format(date_create($j->data->funded_at), 'Y-m-d H:i:s');?></td>
											<td id="<?php echo (string)$j->data->is_buying; ?>">
												<?php echo $j->data->advertisement->trade_type." ".$j->data->advertisement->payment_method;?>
											</td>
											<td id="<?php echo (string)$j->data->is_selling; ?>">
												<?php 
												if($j->data->is_selling == true){ 
													echo trim($j->data->buyer->username); 
												}else{ 
													echo trim($j->data->seller->username); 
												}?>
											</td>
											<td><?php echo $j->data->advertisement->advertiser->username; ?></td>
											<td>
												<?php
												if($j->data->canceled_at != null){
													echo "Cancelado.";
												}else if($j->data->released_at != null){
													echo "Bitcoins liberados.";
												}else if($j->data->disputed_at != null){
													echo "Disputado.";
												}
												?>
											</td>
											<td><?php echo $j->data->amount;?></td>
											<td><?php echo $j->data->amount_btc;?></td>
											<td><?php echo $j->data->fee_btc;?></td>
											<td>
											<?php
											// Si la transacción fue mediante aviso del usuario la cuenta de localbitcoins asociada
											if($myself->data->username == $j->data->advertisement->advertiser->username){
												// Si la transacción es una venta, sumamos la comisión al importe btc
												if($j->data->is_selling == true){
													echo $j->data->amount_btc + $j->data->fee_btc;
												}else{
													// Si la transacción es una compra, restamos la comisión al importe btc
													echo $j->data->amount_btc - $j->data->fee_btc;
												}
											}else{
												// Si la transacción no fue mediante aviso del usuario la cuenta de localbitcoins asociada
												echo $j->data->amount_btc;
											}
											?>
											</td>
											<td><?php echo ($j->data->amount/$j->data->amount_btc)."".$j->data->currency;?></td>
											<td style='text-align: center'>
												<a class="edit" title="<?php echo $this->lang->line('list_edit_import'); ?>"><i class="fa fa-edit fa-2x"></i></a>
											</td>
										</tr>
										<?php $i++ ?>
									<?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Page-Level Scripts -->
<script>
$(document).ready(function(){
	
     var table = $('#tab_import').DataTable({
        "paging": true,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 15],
        "oLanguage": {"sUrl": "<?= assets_url() ?>js/es.txt"},
        "aoColumns": [
            {"sClass": "registro center", "sWidth": "3%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
    
    // Ordenamos la tabla por el código de referencia de manera descendente
    table.order([1, 'desc']).draw();
    
    // Validación para cargar el listado de transacciones de la cuenta de localbitcoin
    $("#load").on('click', function (e) {
        e.preventDefault();
        var account_id = $("#account_id").val();

		if (account_id == "0") {
			
			swal({ 
				title: "Disculpe,",
				text: "Debe seleccionar una cuenta.",
				type: "warning" 
			},function(){
			
			});
			
		}else{
		 
			// Verificamos si la cuenta tiene una api de localbitcoins asociada
			$.post('<?php echo base_url(); ?>import_lb/check_api_account/'+account_id, function (response) {
				
				if (response.length == 0) {
				   
					swal({ 
						title: "Disculpe,",
						text: "La cuenta no está asociada a una api.",
						type: "warning"
					},function(){
						 
					});
					 
				}else{
					
					// Convertimos la data json proveniente del servidor a formato string y lo asignamos al campo oculto 
					$("#json_api").val(JSON.stringify(response));
										
					$("form").submit();
					
				}
				
			}, 'json');
		}
		
	});
	
	
	// Validación para enviar a la visual de edición con los datos de la transacción
	$("table#tab_import").on('click', 'a.edit', function (e) {
		
		var account_id = $("#account_id").val();  // Cuenta seleccionada
		
		var reference = $(this).parent().parent().find('td').eq(0).text();
		var d_create = $(this).parent().parent().find('td').eq(1).text();
		var type_trade = $(this).parent().parent().find('td').eq(2).text();
		type_trade = type_trade.split(' ');
		type_trade = type_trade[0];
		var is_buying = $(this).parent().parent().find('td').eq(2).attr('id');
		var partner = $(this).parent().parent().find('td').eq(3).text();
		var is_selling = $(this).parent().parent().find('td').eq(3).attr('id');
		var status = $(this).parent().parent().find('td').eq(5).text();
		var fiduciary_currency = $(this).parent().parent().find('td').eq(6).text();
		var amount_btc = $(this).parent().parent().find('td').eq(7).text();
		var commission = $(this).parent().parent().find('td').eq(8).text();
		var total_btc = $(this).parent().parent().find('td').eq(9).text();
		var exchange_rate = $(this).parent().parent().find('td').eq(10).text();
		
		var json = '{ "reference":"'+reference+'", "d_create":"'+d_create+'", "type_trade":"'+type_trade+'", "is_buying":"'+is_buying+'", "is_selling":"'+is_selling+'", "partner":"'+partner+'", "status":"'+status+'", "fiduciary_currency":"'+fiduciary_currency+'", "amount_btc":"'+amount_btc+'", "commission":"'+commission+'", "total_btc":"'+total_btc+'", "exchange_rate":"'+exchange_rate+'" }';
		
		window.location.href = '<?php echo base_url() ?>import_lb/edit?json='+json+'&account_id='+account_id;
		
	});
          
});
        
</script>
