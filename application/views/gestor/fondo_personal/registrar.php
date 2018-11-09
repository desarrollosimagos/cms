<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $this->lang->line('heading_title_transactions_registry'); ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>home"><?php echo $this->lang->line('heading_home_transactions_registry'); ?></a>
            </li>
            
            <li>
                <a href="<?php echo base_url() ?>transactions"><?php echo $this->lang->line('heading_subtitle_transactions_registry'); ?></a>
            </li>
            
            <li class="active">
                <strong>
				<?php
				if($this->uri->segment(3) == 1){
					echo $this->lang->line('heading_info_transactions_registry');
				}else if($this->uri->segment(3) == 2){
					echo $this->lang->line('heading_info2_transactions_registry');
				}else{
					echo $this->lang->line('heading_info3_transactions_registry');
				}	
				?>
				</strong>
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
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>
					<?php
					if($this->uri->segment(3) == 1){
						echo $this->lang->line('registry_title');
					}else if($this->uri->segment(3) == 2){
						echo $this->lang->line('registry_title2');
					}else{
						echo $this->lang->line('registry_title3');
					}	
					?>
					</h5>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label style="color:red;">
						(<?php echo $this->lang->line('registry_approved_capital'); ?>: <span id="span_capital_aprobado"></span>
						<?php echo $this->session->userdata('logged_in')['coin_symbol']; ?>)
						
					</label>
					
				</div>
				<div class="ibox-content">
					<form id="form_transactions" method="post" accept-charset="utf-8" class="form-horizontal">
						<div class="form-group">
							<!--<input type="hidden" class="form-control" name="type" id="type" value="<?php echo $this->uri->segment(3); ?>"/>-->
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_type'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="type" id="type">
									<option value="deposit"><?php echo $this->lang->line('transactions_type_deposit'); ?></option>
									<option value="expense"><?php echo $this->lang->line('transactions_type_expense'); ?></option>
									<!--<option value="internal">Interno</option>-->
									<option value="invest"><?php echo $this->lang->line('transactions_type_invest'); ?></option>
									<option value="profit"><?php echo $this->lang->line('transactions_type_profit'); ?></option>
									<option value="sell"><?php echo $this->lang->line('transactions_type_sell'); ?></option>
									<option value="withdraw"><?php echo $this->lang->line('transactions_type_withdraw'); ?></option>
								</select>
							</div>
						</div>
						<!-- Si el usuario es administrador, entonces puede elegir el usuario -->
						<?php if($this->session->userdata('logged_in')['profile_id'] == 5){ ?>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_user'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="user_id" id="user_id">
									<option value="0">Seleccione</option>
									<?php foreach($usuarios as $usuario){?>
									<option value="<?php echo $usuario->id; ?>"><?php echo $usuario->name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>
						<!-- Fin validación -->
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_project'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="project_id" id="project_id">
									<option value="0">Seleccione</option>
									<?php foreach($projects as $project){?>
									<option value="<?php echo $project->id; ?>"><?php echo $project->name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_account'); ?> *</label>
							<div class="col-sm-10">
								<select class="form-control m-b" name="account_id" id="account_id">
									<option value="0">Seleccione</option>
									<?php foreach($accounts as $cuenta){?>
									<option value="<?php echo $cuenta->id; ?>"><?php echo $cuenta->alias." - ".$cuenta->number." - ".$cuenta->coin_avr; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_date'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="date" maxlength="19" id="date" style="width:30%"/>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_description'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="description" maxlength="250" id="description"/>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_reference'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="reference" maxlength="100" id="reference"/>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label" ><?php echo $this->lang->line('registry_observations'); ?></label>
							<div class="col-sm-10">
								<textarea class="form-control" name="observation" maxlength="250" id="observation"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_amount'); ?> *</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="amount" id="amount" onkeypress="return valida_monto(event)">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_real'); ?></label>
							<div class="col-sm-10">
								<div class="i-checks">
									<label>
										<input type="checkbox" name="real" id="real">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_rate'); ?></label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="rate" id="rate">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo $this->lang->line('registry_document'); ?></label>
							<div class="col-sm-4">
								<input type="file" class="form-control" name="document[]" id="document" onChange="valida_tipo($(this))">
							</div>
							<div class="col-sm-6">
								<img id="imgSalida" style="height:150px;width:150px;" class="img-circle" src="<?php echo base_url(); ?>assets/img/users/usuario.jpg">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-2">
								<input id="usuario" type="hidden" value="<?php echo $this->session->userdata('logged_in')['id']; ?>"/>
								<button class="btn btn-white" id="volver" type="button"><?php echo $this->lang->line('registry_back'); ?></button>
								<button class="btn btn-primary" id="registrar" type="submit"><?php echo $this->lang->line('registry_save'); ?></button>
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
        url = '<?php echo base_url() ?>transactions/';
        window.location = url;
    });
	
	//~ $('#date').datepicker({
        //~ format: "dd/mm/yyyy",
        //~ language: "es",
        //~ autoclose: true,
        //~ endDate: 'today'
    //~ });
    
	$.datetimepicker.setLocale('es');
	$('#date').datetimepicker({
	  format:'d/m/Y H:i:s'
	});
    
    // Función para la pre-visualización de la imagen a cargar
	$(function() {
		$('#document').change(function(e) {
			addImage(e); 
		});

		function addImage(e){
			var file = e.target.files[0],
			imageType = /image.*/;

			if (!file.type.match(imageType))
			return;

			var reader = new FileReader();
			reader.onload = fileOnload;
			reader.readAsDataURL(file);
		}
	  
		function fileOnload(e) {
			var result=e.target.result;
			$('#imgSalida').attr("src",result);
		}
	});
    
    // Función para la precarga de la tasa de cambio a usar según la cuenta seleccionada
	$(function() {
		$('#account_id').change(function(e) {
			
			if($('#account_id').val() != '0'){
				
				var currency_account = $('select[name="account_id"] option:selected').text();  // Moneda de la cuenta seleccionada
				currency_account = currency_account.split("-");
				currency_account = currency_account[2].trim();
				
				// Proceso de conversión de moneda (captura del equivalente a 1 dólar en las distintas monedas)
				//~ $.post('https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664', function (coins) {
				$.ajax({
					type: "post",
					//~ dataType: "json",
					url: 'https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664',
					async: false
				}).done(function(coins) {
					if(coins.error){
						console.log(coins.error);
					} else {
					
						var valor1btc, valor1anycoin, rate = currency_account, rates = [], cryptos;
						
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
								alert(coin.error);
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
						if ($.inArray( currency_account, rates ) != -1) {
							
							var exchange_rate = valor1anycoin;  // Tasa de cambio
								
						} else if(currency_account == 'VEF') {
								
							var exchange_rate = valor1vef;  // Tasa de cambio
						
						} else {
						
							var exchange_rate = coins['rates'][currency_account];  // Tasa de cambio
						
						}
						
						// Asiganción de la tasa de cambio
						$("#rate").val(exchange_rate);
					
					}  // Cierre de la comprobación de error en el ajax
					
				}).fail(function() {
					
					// Usamos la segunda cuenta si la primera falla
					// Proceso de conversión de moneda (captura del equivalente a 1 dólar en las distintas monedas)
					$.ajax({
						type: "post",
						//~ dataType: "json",
						url: 'https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85',
						async: false
					}).done(function(coins) {
						if(coins.error){
							console.log(coins.error);
						} else {
						
							var valor1btc, valor1anycoin, rate = currency_account, rates = [], cryptos;
							
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
									alert(coin.error);
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
							if ($.inArray( currency_account, rates ) != -1) {
								
								var exchange_rate = valor1anycoin;  // Tasa de cambio
									
							} else if(currency_account == 'VEF') {
									
								var exchange_rate = valor1vef;  // Tasa de cambio
							
							} else {
							
								var exchange_rate = coins['rates'][currency_account];  // Tasa de cambio
							
							}
							
							// Asiganción de la tasa de cambio
							$("#rate").val(exchange_rate);
						
						}  // Cierre de la comprobación de error en el ajax
						
					});  // Cierre de la conversión del monto con la segunda cuenta de openexchangerates.org
					
				});  // Cierre de la conversión del monto con la primera cuenta de openexchangerates.org
				
			}  // Cierre de condicional de combo de cuentas
			
		});
	});
    
	var capital_pendiente = 0;
	var capital_aprobado = 0;
    // Proceso de conversión de moneda (captura del equivalente a 1 dólar en las distintas monedas)
    $.post('https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664', function (coins) {
		
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
					}else{
						capital_aprobado += trans_usd;
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
			
			var valor1btc, valor1anycoin, rate = $("#iso_currency_user").val().trim().trim(), rates = [], cryptos;
			
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
			if ($.inArray( $("#iso_currency_user").val().trim(), rates ) != -1) {
				
				var currency_user = 1/parseFloat(valor1anycoin);  // Tipo de moneda del usuario logueado
					
			} else if($("#iso_currency_user").val().trim() == 'VEF') {
					
				var currency_user = valor1vef;  // Tipo de moneda del usuario logueado
			
			} else {
			
				var currency_user = coins['rates'][$("#iso_currency_user").val().trim()];  // Tipo de moneda del usuario logueado
			
			}
			
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
						}else{
							capital_aprobado += trans_usd;
						}
					}
				});
				
				capital_aprobado = (capital_aprobado*currency_user).toFixed(2);
				
				capital_pendiente = (capital_pendiente*currency_user).toFixed(2);
				
				$("#span_capital_aprobado").text(capital_aprobado);
				
			}, 'json');
			
		}, 'json');  // Cierre de la conversión del monto con la segunda cuenta de openexchangerates.org
		
	});  // Cierre de la conversión del monto con la primera cuenta de openexchangerates.org
	
	
	// Proceso de validación del registro
    $("#registrar").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto

        /*if ($('#user_id').val() == "0") {
			
			swal("Disculpe,", "para continuar debe seleccionar el usuario");
			$('#user_id').focus();
			$('#user_id').parent('div').addClass('has-error');
			
        } else if ($('#project_id').val() == "0") {
			
			swal("Disculpe,", "para continuar debe seleccionar el proyecto");
			$('#project_id').focus();
			$('#project_id').parent('div').addClass('has-error');
			
        } else*/ if ($('#account_id').val() == "0") {
			
			swal("Disculpe,", "para continuar debe seleccionar la cuenta");
			$('#account_id').focus();
			$('#account_id').parent('div').addClass('has-error');
			
        } else if ($('#date').val().trim() === ""){
			
			swal("Disculpe,", "para continuar debe ingresar la fecha");
			$('#date').focus();
			$('#date').parent('div').addClass('has-error');
			
		} else if ($('#amount').val().trim() === ""){
			
			swal("Disculpe,", "para continuar debe ingresar el monto");
			$('#amount').focus();
			$('#amount').parent('div').addClass('has-error');
			
		} else {
			
			var monto_convertido;
	
			$.post('https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664', function (coins) {
		
				var currency_user = coins['rates'][$("#iso_currency_user").val()];  // Tipo de moneda del usuario logueado
				
				// Conversión de cada monto a dólares
				var currency = $("#account_id").find('option').filter(':selected').text();  // Tipo de moneda de la cuenta
				currency = currency.split(' - ');
				currency = currency[2];
				//~ alert(currency);
				var trans_usd = parseFloat($('#amount').val().trim())/coins['rates'][currency];
				//~ alert(trans_usd);
				
				monto_convertido = trans_usd;
					
				monto_convertido = (monto_convertido*currency_user).toFixed(2);
				
			}, 'json').done(function() {
				
				/*if(monto_convertido > capital_aprobado && $('#type').val().trim() == 'withdraw' && $("#usuario").val().trim() != 1){
					
					alert("El monto a retirar no puede ser superior al capital aprobado");
					
				}else{*/
					
					var formData = new FormData(document.getElementById("form_transactions"));  // Forma de capturar todos los datos del formulario
			
					$.ajax({
						//~ method: "POST",
						type: "post",
						dataType: "json",
						url: '<?php echo base_url(); ?>CFondoPersonal/add',
						data: formData,
						cache: false,
						contentType: false,
						processData: false
					})
					.done(function(response) {
						if(response.error){
							console.log(response.error);
						} else {
							if (response['response'] == 'error') {
							
								swal("Disculpe,", "El registro no pudo ser guardado, por favor consulte a su administrador...");
								
							}else if (response['response'] == 'error2') {
								
								swal("Disculpe,", "ha ocurrido un error al guardar el documento");
								
							}else{
								
								swal({ 
									title: "Registro",
									 text: "Guardado con exito",
									  type: "success" 
									},
								function(){
								  window.location.href = '<?php echo base_url(); ?>transactions';
								});
								
							}
							
						}				
					}).fail(function() {
						console.log("error ajax");
					});
					
				/*}*/
				
			}).fail(function() {
				
				// Usamos la segunda cuenta si la primera falla
				$.post('https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85', function (coins) {
		
					var currency_user = coins['rates'][$("#iso_currency_user").val().trim()];  // Tipo de moneda del usuario logueado
					
					// Conversión de cada monto a dólares
					var currency = $("#account_id").find('option').filter(':selected').text();  // Tipo de moneda de la cuenta
					currency = currency.split(' - ');
					currency = currency[2];
					var trans_usd = parseFloat($('#amount').val().trim())/coins['rates'][currency];
					
					monto_convertido = trans_usd;
						
					monto_convertido = (monto_convertido*currency_user).toFixed(2);
					
				}, 'json').done(function() {
					
					/*if(monto_convertido > capital_aprobado && $('#type').val().trim() == 'withdraw' && $("#usuario").val().trim() != 1){
						
						alert("El monto a retirar no puede ser superior al capital aprobado");
						
					}else{*/
						
						var formData = new FormData(document.getElementById("form_transactions"));  // Forma de capturar todos los datos del formulario
				
						$.ajax({
							//~ method: "POST",
							type: "post",
							dataType: "json",
							url: '<?php echo base_url(); ?>CFondoPersonal/add',
							data: formData,
							cache: false,
							contentType: false,
							processData: false
						})
						.done(function(response) {
							if(response.error){
								console.log(response.error);
							} else {
								if (response['response'] == 'error') {
								
									swal("Disculpe,", "El registro no pudo ser guardado, por favor consulte a su administrador...");
									
								}else if (response['response'] == 'error2') {
									
									swal("Disculpe,", "ha ocurrido un error al guardar el documento");
									
								}else{
									
									swal({ 
										title: "Registro",
										 text: "Guardado con exito",
										  type: "success" 
										},
									function(){
									  window.location.href = '<?php echo base_url(); ?>transactions';
									});
									
								}
								
							}				
						}).fail(function() {
							console.log("error ajax");
						});
						
				/*	}*/
					
				});  // Cierre de la conversión del monto con la segunda cuenta de openexchangerates.org
				
			});  // Cierre de la conversión del monto con la primera cuenta de openexchangerates.org
            
        }
    });
});

function valida_monto(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9-.-]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

// Validamos que los archivos sean de tipo .jpg, jpeg, png o pdf
function valida_tipo(input) {
	
	var max_size = '';
	var archivo = input.val();
	
	var ext = archivo.split(".");
	ext = ext[1];
	
	if (ext != 'jpg' && ext != 'jpeg' && ext != 'png' && ext != 'pdf'){
		swal("Disculpe,", "sólo se admiten archivos .jpg, .jpeg, .png y .pdf");
		input.val('');
		input.parent('div').addClass('has-error');
	}else{
		input.parent('div').removeClass('has-error');
	}
}

</script>
