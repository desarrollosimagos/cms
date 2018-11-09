<style>
.fa-2x {
    font-size: 1.2em !important;
}

.a-actions {
	padding-top: 8px !important;
	padding-right: 8px !important;
	padding-bottom: 8px !important;
	padding-left: 8px !important;
}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_transactions'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_transactions'); ?></a>
            </li>
            <li class="active">
                <strong><?php echo $this->lang->line('heading_subtitle_transactions'); ?></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Campos ocultos que almacenan el tipo de moneda de la cuenta del usuario logueado -->
<input type="hidden" id="iso_currency_user" value="<?php echo $this->session->userdata('logged_in')['coin_iso']; ?>">
<input type="hidden" id="symbol_currency_user" value="<?php echo $this->session->userdata('logged_in')['coin_symbol']; ?>">

<!-- Campos ocultos que almacenan los nombres del menú y el submenú de la vista actual -->
<input type="hidden" id="ident" value="<?php echo $ident; ?>">
<input type="hidden" id="ident_sub" value="<?php echo $ident_sub; ?>">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php echo base_url() ?>transactions/register">
				<button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-plus"></i> <?php echo $this->lang->line('btn_registry'); ?></button>
            </a>
            <!--<a href="<?php echo base_url() ?>transactions/register/withdraw">
				<button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-minus"></i> Retirar</button>
            </a>-->
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php echo $this->lang->line('list_title'); ?></h5>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label style="color:red;">
						(<?php echo $this->lang->line('list_approved_capital'); ?>: <span id="span_capital_aprobado"></span>
						<?php echo $this->session->userdata('logged_in')['coin_symbol']; ?>)
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="tab_transactions" class="table table-striped table-bordered dt-responsive table-hover dataTables-example" >
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><?php echo $this->lang->line('list_user'); ?></th>
                                    <th><?php echo $this->lang->line('list_type'); ?></th>
                                    <th><?php echo $this->lang->line('list_description'); ?></th>
                                    <th><?php echo $this->lang->line('list_amount'); ?></th>
                                    <th><?php echo $this->lang->line('list_status'); ?></th>
                                    <th><?php echo $this->lang->line('list_account'); ?></th>
                                    <th><?php echo $this->lang->line('list_reference'); ?></th>
                                    <th><?php echo $this->lang->line('list_observations'); ?></th>
                                    <th><?php echo $this->lang->line('list_real'); ?></th>
                                    <th><?php echo $this->lang->line('list_rate'); ?></th>
                                    <th><?php echo $this->lang->line('list_document'); ?></th>
                                    <!--<th><?php echo $this->lang->line('list_edit'); ?></th>
                                    <th><?php echo $this->lang->line('list_delete'); ?></th>
                                    <th><?php echo $this->lang->line('list_validate'); ?></th>-->
                                    <th><?php echo $this->lang->line('list_actions'); ?></th>
                                </tr>
                            </thead>
                            <!--<tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($listar as $fondo) { ?>
                                    <tr style="text-align: center">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if($fondo->usuario == ''){
												echo "PLATAFORMA";
											}else{
												echo $fondo->usuario;
											}
                                            ?>
                                        </td>
                                        <td>
                                            <?php
											echo $this->lang->line('transactions_type_'.$fondo->type);
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo number_format($fondo->amount, $fondo->coin_decimals, '.', '')."  ".$fondo->coin_symbol; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($fondo->status == 'approved'){
												echo "<span style='color:#337AB7;'>".$this->lang->line('transactions_status_approved')."</span>";
											}else if($fondo->status == 'waiting'){
												echo "<span style='color:#A5D353;'>".$this->lang->line('transactions_status_waiting')."</span>";
											}else{
												echo "<span style='color:#D33333;'>".$this->lang->line('transactions_status_denied')."</span>";
											}
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->alias." - ".$fondo->number; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->description; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->reference; ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->observation; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($fondo->real == 1){
												echo "Sí";
											}else{
												echo "No";
											}
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $fondo->rate; ?>
                                        </td>
                                        <td>
											<a target="_blank" href="<?php echo base_url(); ?>assets/docs_trans/<?php echo $fondo->document; ?>"><?php echo $fondo->document; ?></a>
                                        </td>
                                        <td style='text-align: center'>
											<?php if($this->session->userdata('logged_in')['profile_id'] == 1){ ?>
												<a href="<?php echo base_url() ?>transactions/edit/<?= $fondo->id; ?>" title="<?php echo $this->lang->line('list_edit'); ?>"><i class="fa fa-edit fa-2x"></i></a>
                                            <?php }else{ ?>
												<a ><i class="fa fa-ban fa-2x" style='color:#D33333;'></i></a>
                                            <?php } ?>
                                        </td>
                                        <td style='text-align: center'>
											<?php if($this->session->userdata('logged_in')['profile_id'] == 1){ ?>
                                            <a class='borrar' id='<?php echo $fondo->id; ?>' title='<?php echo $this->lang->line('list_delete'); ?>'><i class="fa fa-trash-o fa-2x"></i></a>
                                            <?php }else{ ?>
												<a ><i class="fa fa-ban fa-2x" style='color:#D33333;'></i></a>
                                            <?php } ?>
                                        </td>
                                        <td style='text-align: center'>
											<?php
											$class = "";
											$class_icon_validar = "";
											$disabled = "";
											$cursor_style = "";
											$color_style = "";
											$title = "";
											if($fondo->status == 'approved'){
												$class_icon_validar = "fa-check-circle";
												$disabled = "disabled='true'";
												$cursor_style = "cursor:default";
												$color_style = "";
												$title = "";
											}else if($fondo->status == 'waiting'){
												$class = "validar";
												$class_icon_validar = "fa-check-circle-o";
												$cursor_style = "cursor:pointer";
												$color_style = "";
												$title = "title='".$this->lang->line('list_validate')."'";
											}else{
												$class_icon_validar = "fa-check-circle";
												$disabled = "disabled='true'";
												$cursor_style = "cursor:default";
												$color_style = "color:grey";
												$title = "";
											}
											?>
                                            <a class='<?php echo $class; ?>' id='<?php echo $fondo->id.';'.$fondo->account_id.';'.$fondo->amount.';'.$fondo->type; ?>' <?php echo $disabled; ?> style='<?php echo $cursor_style; ?>;<?php echo $color_style; ?>' <?php echo $title; ?>>
												<i class="fa <?php echo $class_icon_validar; ?> fa-2x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $i++ ?>
                                <?php } ?>
                            </tbody>-->
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
     $('#tab_transactions').DataTable({
        "paging": true,
        "lengthChange": true,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "iDisplayLength": 50,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [10, 50, 100, 150],
        // Nuevo
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
			"method":"POST",
			"url": "<?= base_url() ?>transactions_json"
		},
		"columnDefs": [
			{
				//~ "target": [0, 3, 4],
				"orderable":false,
			}
		],
		//Nuevo
        "oLanguage": {"sUrl": "<?= assets_url() ?>js/es.txt"},
        "aoColumns": [
            {"sClass": "registro center", "sWidth": "5%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "registro center", "sWidth": "10%"},
            {"sClass": "none", "sWidth": "30%"},
            {"sClass": "none", "sWidth": "30%"},
            {"sClass": "none", "sWidth": "30%"},
            {"sClass": "none", "sWidth": "30%"},
            {"sClass": "none", "sWidth": "30%"},
            {"sClass": "none", "sWidth": "30%"},
            //~ {"sWidth": "1%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            //~ {"sWidth": "1%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "10%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });
             
    // Validacion para borrar
    $("table#tab_transactions").on('click', 'a.borrar', function (e) {
        e.preventDefault();
        var id = this.getAttribute('id');

        swal({
            title: "Denegar registro",
            text: "¿Está seguro de denegar el registro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Denegar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
          },
        function(isConfirm){
            if (isConfirm) {
             
                $.post('<?php echo base_url(); ?>transactions/delete/' + id + '', function (response) {

                    if (response['response'] == "error") {
                       
                         swal({ 
                           title: "Disculpe,",
                            text: "No se puede denegar, contacte con su administrador",
                             type: "warning" 
                           },
                           function(){
                             
                         });
                    }else{
                         swal({ 
                           title: "Denegar",
                            text: "Registro denegado con exito",
                             type: "success" 
                           },
                           function(){
                             window.location.href = '<?php echo base_url(); ?>transactions';
                         });
                    }
                    
                }, 'json');
                
            } 
            
        });
    });
    
    // Variables globales para la posterior validación del capital aprobado al validar la transacciones
    var capital_aprobado_global = 0;
    var coins_global = 0;
    
    // Proceso de conversión de moneda (captura del equivalente a 1 dólar en las distintas monedas)
    $.post('https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664', function (coins) {
		
		coins_global = coins  // Tasas de conversión global
		
		var valor1btc, valor1anycoin, rate = $("#iso_currency_user").val(), rates = [], cryptos;
		
		// Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
		$.ajax({
			type: "get",
			dataType: "json",
			url: 'https://api.coinmarketcap.com/v1/ticker/',
			async: false
		})
		.done(function(coin) {
			if(coin.error){
				console.log(coin.error);
			} else {
				
				cryptos = coin;
				
				$.each(coin, function (i) {
					if (coin[i]['symbol'] == rate){
						// Obtenemos el valor de la cryptomoneda del usuario en dólares
						valor1anycoin = coin[i]['price_usd'];
					}
					rates.push(coin[i]['symbol']);  // Colectamos los símbolos de todas las cryptomonedas
				});
			}				
		}).fail(function() {
			console.log("error ajax");
		});
		
		// Valor de 1 dólar en bolívares (uso de async: false para esperar a que cargue la data)
		$.ajax({
			type: "get",
			dataType: "json",
			url: 'https://s3.amazonaws.com/dolartoday/data.json',
			async: false
		})
		.done(function(vef) {
			if(vef.error){
				console.log(vef.error);
			} else {
				valor1vef = vef['USD']['transferencia'];
			}				
		}).fail(function() {
			console.log("error ajax");
		});
		
		// Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando valores de una api más acorde
		if ($.inArray( $("#iso_currency_user").val(), rates ) != -1) {
			
			var currency_user = 1/parseFloat(valor1anycoin);  // Tipo de moneda del usuario logueado
				
		} else if($("#iso_currency_user").val() == 'VEF') {
				
			var currency_user = valor1vef;  // Tipo de moneda del usuario logueado
		
		} else {
		
			var currency_user = coins['rates'][$("#iso_currency_user").val()];  // Tipo de moneda del usuario logueado
		
		}
		
		var capital_pendiente = 0;
		var capital_aprobado = 0;
		
		// Proceso de cálculo de capital aprobado y pendiente
		$.post('<?php echo base_url(); ?>dashboard/fondos_json', function (fondos) {
			
			$.each(fondos, function (i) {
				
				// Conversión de cada account a dólares
				var currency = fondos[i]['coin_avr'];  // Tipo de moneda de la transacción
				
				// Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if ($.inArray( currency, rates ) != -1) {
					
					// Primero convertimos el valor de la cryptodivisa
					var valor1anycoin = 0;
					rate = currency;
					
					$.each(cryptos, function (i) {
						if (cryptos[i]['symbol'] == rate){
							// Obtenemos el valor de la cryptomoneda del usuario en dólares
							valor1anycoin = cryptos[i]['price_usd'];
						}
					});
					
					var trans_usd = parseFloat(fondos[i]['amount'])*parseFloat(valor1anycoin);
					
				} else if(currency == 'VEF') {
						
					var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(valor1vef);
					
				} else {
					
					var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(coins['rates'][currency]);
					
				}
				
				// Sumamos o restamos dependiendo del tipo de transacción (ingreso/egreso)
				if(fondos[i]['status'] == 'waiting'){
					if(fondos[i]['type'] == 'deposit'){
						capital_pendiente += trans_usd;
					}else{
						capital_pendiente += trans_usd;
					}
				}
				if(fondos[i]['status'] == 'approved'){
					if(fondos[i]['type'] == 'deposit'){
						capital_aprobado += trans_usd;
						capital_aprobado_global += trans_usd;
					}else{
						capital_aprobado += trans_usd;
						capital_aprobado_global += trans_usd;
					}
				}
			});
			
			capital_aprobado = (capital_aprobado*currency_user).toFixed(2);
			
			capital_pendiente = (capital_pendiente*currency_user).toFixed(2);
			
			$("#span_capital_aprobado").text(capital_aprobado);
			
		}, 'json');
		
	}, 'json').fail(function() {
		
		// Usamos la segunda cuenta si la primera falla
		// Proceso de conversión de moneda (captura del equivalente a 1 dólar en las distintas monedas)
		$.post('https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85', function (coins) {
			
			coins_global = coins  // Tasas de conversión global
			
			var valor1btc, valor1anycoin, rate = $("#iso_currency_user").val(), rates = [], cryptos;
			
			// Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
			$.ajax({
				type: "get",
				dataType: "json",
				url: 'https://api.coinmarketcap.com/v1/ticker/',
				async: false
			})
			.done(function(coin) {
				if(coin.error){
					console.log(coin.error);
				} else {
					
					cryptos = coin;
					
					$.each(coin, function (i) {
						if (coin[i]['symbol'] == rate){
							// Obtenemos el valor de la cryptomoneda del usuario en dólares
							valor1anycoin = coin[i]['price_usd'];
						}
						rates.push(coin[i]['symbol']);  // Colectamos los símbolos de todas las cryptomonedas
					});
				}				
			}).fail(function() {
				console.log("error ajax");
			});
			
			// Valor de 1 dólar en bolívares (uso de async: false para esperar a que cargue la data)
			$.ajax({
				type: "get",
				dataType: "json",
				url: 'https://s3.amazonaws.com/dolartoday/data.json',
				async: false
			})
			.done(function(vef) {
				if(vef.error){
					console.log(vef.error);
				} else {
					valor1vef = vef['USD']['transferencia'];
				}				
			}).fail(function() {
				console.log("error ajax");
			});
			
			// Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando valores de una api más acorde
			if ($.inArray( $("#iso_currency_user").val(), rates ) != -1) {
				
				var currency_user = 1/parseFloat(valor1anycoin);  // Tipo de moneda del usuario logueado
					
			} else if($("#iso_currency_user").val() == 'VEF') {
					
				var currency_user = valor1vef;  // Tipo de moneda del usuario logueado
			
			} else {
			
				var currency_user = coins['rates'][$("#iso_currency_user").val()];  // Tipo de moneda del usuario logueado
			
			}
			
			var capital_pendiente = 0;
			var capital_aprobado = 0;
			
			// Proceso de cálculo de capital aprobado y pendiente
			$.post('<?php echo base_url(); ?>dashboard/fondos_json', function (fondos) {
				
				$.each(fondos, function (i) {
					
					// Conversión de cada account a dólares
					var currency = fondos[i]['coin_avr'];  // Tipo de moneda de la transacción
					
					// Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					if ($.inArray( currency, rates ) != -1) {
						
						// Primero convertimos el valor de la cryptodivisa
						var valor1anycoin = 0;
						rate = currency;
						
						$.each(cryptos, function (i) {
							if (cryptos[i]['symbol'] == rate){
								// Obtenemos el valor de la cryptomoneda del usuario en dólares
								valor1anycoin = cryptos[i]['price_usd'];
							}
						});
						
						var trans_usd = parseFloat(fondos[i]['amount'])*parseFloat(valor1anycoin);
						
					} else if(currency == 'VEF') {
							
						var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(valor1vef);
						
					} else {
						
						var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(coins['rates'][currency]);
						
					}
					
					// Sumamos o restamos dependiendo del tipo de transacción (ingreso/egreso)
					if(fondos[i]['status'] == 'waiting'){
						if(fondos[i]['type'] == 'deposit'){
							capital_pendiente += trans_usd;
						}else{
							capital_pendiente += trans_usd;
						}
					}
					if(fondos[i]['status'] == 'approved'){
						if(fondos[i]['type'] == 'deposit'){
							capital_aprobado += trans_usd;
							capital_aprobado_global += trans_usd;
						}else{
							capital_aprobado += trans_usd;
							capital_aprobado_global += trans_usd;
						}
					}
				});
				
				capital_aprobado = (capital_aprobado*currency_user).toFixed(2);
				
				capital_pendiente = (capital_pendiente*currency_user).toFixed(2);
				
				$("#span_capital_aprobado").text(capital_aprobado);
				
			}, 'json');
			
		}, 'json');  // Cierre de la conversión del monto con la segunda cuenta de openexchangerates.org
		
	});  // Cierre de la conversión del monto con la primera cuenta de openexchangerates.org
    
    
    // Función para validar transacción
    $("table#tab_transactions").on('click', 'a.validar', function (e) {
        e.preventDefault();
        var id = this.getAttribute('id');
        
        var account_id = id.split(';');
        account_id = account_id[1];

        var amount = id.split(';');
        amount = amount[2];

        var tipo = id.split(';');
        tipo = tipo[3];
        
        var coin_avr = id.split(';');
        coin_avr = coin_avr[4];

        var id = id.split(';');
        id = id[0];
        
        var currency_user = coins_global['rates'][$("#iso_currency_user").val()];  // Tipo de moneda del usuario logueado
				
		// Conversión del monto a dólares
		var currency = coin_avr;  // Tipo de moneda de la transacción
		//~ alert(currency);
		var trans_usd = parseFloat(amount)/coins_global['rates'][currency];
		//~ alert(trans_usd);
		
		monto_convertido = trans_usd;
			
		monto_convertido = (monto_convertido*currency_user).toFixed(2);
		
		//~ alert('approved');
		
		//~ alert(monto_convertido);
		//~ alert(capital_aprobado_global);
		
		if(monto_convertido > capital_aprobado_global && tipo == 'withdraw'){
			
			alert("El monto a retirar no puede ser superior al capital aprobado");
			
		}else{

			swal({
				title: "Validar transacción",
				text: "¿Está seguro de valdiar la transacción?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Validar",
				cancelButtonText: "Denegar",
				closeOnConfirm: false,
				closeOnCancel: true
			  },
			function(isConfirm){
				if (isConfirm) {
				 
					$.post('<?php echo base_url(); ?>transactions/validar/', {'id': id, 'account_id': account_id, 'amount': amount, 'tipo': tipo, 'coin_avr': coin_avr, 'status': 'approved'}, function (response) {

						if (response['response'] == 'error') {
						   
							 swal({ 
							   title: "Disculpe,",
								text: "No se pudo validar la transacción, por favor consulte con su administrador",
								 type: "warning" 
							   },
							   function(){
								 
							 });
						}else{
							 swal({ 
							   title: "Validado",
								text: "Transacción validada con exito",
								 type: "success" 
							   },
							   function(){
								 window.location.href = '<?php echo base_url(); ?>transactions';
							 });
						}
						
					}, 'json');
					
				}else{
					
					$.post('<?php echo base_url(); ?>transactions/validar/', {'id': id, 'account_id': account_id, 'amount': amount, 'tipo': tipo, 'coin_avr': coin_avr, 'status': 'denied'}, function (response) {

						if (response['response'] == 'error') {
						   
							 swal({ 
							   title: "Disculpe,",
								text: "No se pudo negar la transacción, por favor consulte con su administrador",
								 type: "warning" 
							   },
							   function(){
								 
							 });
						}else{
							 swal({ 
							   title: "Negada",
								text: "Transacción negada con exito",
								 type: "success" 
							   },
							   function(){
								 window.location.href = '<?php echo base_url(); ?>transactions';
							 });
						}
						
					}, 'json');
					
				}
			});
			
		}
		
    });
    
});
        
</script>
