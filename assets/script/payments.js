$(document).ready(function() {
	// Capturamos la base_url
    var base_url = $("#base_url").val();    
        
    $('input').on({
        keypress: function () {
            $(this).parent('div').removeClass('has-error');
        }
    });
    
    $('#date').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
        endDate: 'today'
    });
    
    // Funciones para calcular el monto total de la transacción según los contratos seleccionados individualmente
    // Función para sumar el monto del contrato marcado al total
    $("table#tab_contracts").on('ifChecked', 'input.checkbox', function (e) {
		
		// Valor actual del total
		var total = $("span#total").text().trim();
		
		if(total != ''){
			total = parseFloat(total)
		}else{
			total = 0;
		}
		
		var tr_padre = $(this).parent().parent().parent().parent().parent();  // Subimos tantos niveles hasta llegar al tr
		
		var monto_contrato = tr_padre.find('td').eq(4).text().trim();  // Capturamos el valor de la columna 'Monto'
		
		monto_contrato = monto_contrato.split(' ');  // Filtrado de moneda
		
		monto_contrato = monto_contrato[0];
		
		// Cálculo del nuevo total
		total += parseFloat(monto_contrato);
		
		// Imprimimos el nuevo total
		$("span#total").text(total);
		
		// Capturamos y almacenamos el id del contrato marcado
		var id_contract = tr_padre.find('td').eq(1).text().trim();
		capture_id_contract('Checked', id_contract);
		
	});
    // Función para restar el monto del contrato desmarcado al total
    $("table#tab_contracts").on('ifUnchecked', 'input.checkbox', function (e) {
		
		// Valor actual del total
		var total = $("span#total").text().trim();
		
		if(total != ''){
			total = parseFloat(total)
		}else{
			total = 0;
		}
		
		var tr_padre = $(this).parent().parent().parent().parent().parent();  // Subimos tantos niveles hasta llegar al tr
		
		var monto_contrato = tr_padre.find('td').eq(4).text().trim();  // Capturamos el valor de la columna 'Monto'
		
		monto_contrato = monto_contrato.split(' ');  // Filtrado de moneda
		
		monto_contrato = monto_contrato[0];
		
		// Cálculo del nuevo total
		if(total > 0){
			total -= parseFloat(monto_contrato);
		}
		
		// Imprimimos el nuevo total
		$("span#total").text(total);
		
		// Borramos el id del contrato desmarcado del campo oculto
		var id_contract = tr_padre.find('td').eq(1).text().trim();
		capture_id_contract('Unchecked', id_contract);
		
	});
	
    // Funciones para calcular el monto total de la transacción según los contratos seleccionados generalmente
    // Función para marcar todos los contratos y sumar todos los montos para generar un total general
    $("table#tab_contracts").on('ifChecked', 'input.general_checkbox', function (e) {
		
		// Valor actual del total
		let total = $("span#total").text().trim();
		
		if(total != ''){
			total = parseFloat(total)
		}else{
			total = 0;
		}
		
		// Recorremos la lista de contratos
		$("#tab_contracts tbody tr").each(function (index){
		
			let tr_padre = $(this);  // Fijamos posición en el tr de la iteración
			
			// En este punto no es necesario que volvamos a calcular el monto total ya que se detecta al cambiar cada checkbox individualmente
			
			// Capturamos y almacenamos el id del contrato marcado
			let id_contract = tr_padre.find('td').eq(1).text().trim();
			capture_id_contract('Checked', id_contract);
			
			// Marcamos el checkbox correspondiente
			tr_padre.find('td').eq(0).find('input.checkbox').iCheck('check');
			
		});
		
	});
    // Función para desmarcar todos los contratos y restar todos los montos para generar un total general (cero)
    $("table#tab_contracts").on('ifUnchecked', 'input.general_checkbox', function (e) {
		
		// Valor actual del total
		let total = $("span#total").text().trim();
		
		if(total != ''){
			total = parseFloat(total)
		}else{
			total = 0;
		}
		
		// Recorremos la lista de contratos
		$("#tab_contracts tbody tr").each(function (index){
		
			let tr_padre = $(this);  // Fijamos posición en el tr de la iteración
			
			// En este punto no es necesario que volvamos a calcular el monto total ya que se detecta al cambiar cada checkbox individualmente
			
			// Borramos el id del contrato desmarcado del campo oculto
			let id_contract = tr_padre.find('td').eq(1).text().trim();
			capture_id_contract('Unchecked', id_contract);
			
			// Desmarcamos el checkbox correspondiente
			tr_padre.find('td').eq(0).find('input.checkbox').iCheck('uncheck');
			
		});
		
	});
	
	// Capturar y almacenar los ids de los contratos
	function capture_id_contract(checked, id){
		
		var checked = checked;
		
		var id = String(id);  // Id del contrato marcado/desmarcado
		
		var contracts_ids = $("#contract_ids").val().trim();  // Capturamos los ids almacenados actualmente
		
		if(checked == 'Checked'){
		
			if(contracts_ids != ''){
				
				// Primero verificamos que no esté ya almacenado
				var array = contracts_ids.split(';');
				
				if($.inArray( id, array ) == -1){
					contracts_ids += ";" + id;  // Concatenamos el nuevo id marcado añadiéndole un separador antes
				}
				
			}else{
				
				contracts_ids += id;  // Concatenamos el nuevo id marcado
				
			}
			
		}else{
			
			if(contracts_ids != ''){
				
				// Primero verificamos que esté almacenado
				var array = contracts_ids.split(';');
				var index = array.indexOf(id);  // Buscamos la posición o índice del elemento que coincida con el id del contrato desmarcado
				
				// Eliminamos del arreglo al elemento que coincida con el índice del id del contrato desmarcado
				if(index > -1){
					array.splice(index, 1);
				}
				
				// Reconstruimos la cadena de ids de los contratos marcados
				contracts_ids = '';
				$.each(array, function( index, value ) {
					contracts_ids += value + ';';
				});
				// Si la cadena de ids no queda vacía borramos el último ';'
				if(contracts_ids != ''){
					contracts_ids = contracts_ids.slice(0,-1);
				}
			}
			
		}
		
		// Almacenamos los ids resultantes
		$("#contract_ids").val(contracts_ids);
		
	}
	
	// Mostramos la ventana modal para la preselección y precarga de datos
	$("#pay").click(function (e) {
		
		e.preventDefault();  // Para evitar que se envíe por defecto
		
		var total = $("span#total").text();
		
		// Carga del monto
		$("#amount").val(total.trim());
		
		// Carga de las observaciones
		get_observations();
		
		// Mostramos la modal sólo si hay contratos marcados
		if(count_checks() > 0){
			
			$("#modal_pago").modal('show');
			
		}else{
			
			swal({ 
				title: "Pago",
				text: "Debe seleccionar los contratos a pagar",
				type: "warning" 
			}, function(){
				
			});
			
		}
		
	});
	
	// El elemento que se quiere activar (ícono de carga) si hay una petición ajax en proceso.
	var cargando_recalculo = $("#load_recalculation");
	cargando_recalculo.hide();
	
	// Ejecutamos las funciones de recálculo de montos de contratos
	$("#recalculate").click(function (e) {
		
		e.preventDefault();  // Para evitar que se envíe por defecto
		
		var contract_ids = $("#contract_ids").val();
		
		// Mostramos la modal sólo si hay contratos marcados
		if(count_checks() > 0){
			
			// evento ajax start
			$(document).ajaxStart(function() {
				cargando_recalculo.show();
			});

			// evento ajax stop
			$(document).ajaxStop(function() {
				cargando_recalculo.hide();
			});
			
			$.post(base_url+'CPayments/update_cost', { 'contract_ids': contract_ids }, function (response) {

				if (response['response'] == 'error') {
					
                    swal("Disculpe,", "ocurrió un error durante el proceso, vuelva a intentarlo.");
                    
                }else{
					
					swal({
						title: "Actualizar",
						text: "Costos actualizados",
						type: "success" 
					},
					function(){
						window.location.href = base_url+'payments';
					});
					
				}

            }, 'json');
			
		}else{
			
			swal({ 
				title: "Pago",
				text: "Debe seleccionar los contratos a recalcular",
				type: "warning" 
			}, function(){
				
			});
			
		}
		
	});
	
	// Contamos los contratos marcados
	function count_checks(){
		
		var checks = 0;
		
		// Recorremos la lista de contratos
		$("#tab_contracts tbody tr").each(function (index){
			
			var i_check = $(this).find('td').eq(0).find('div.icheckbox_square-green').attr('class');
			
			// Verificamos si está marcado y capturamos los datos de ser positivo
			if(i_check.indexOf('checked') > -1){
				
				checks++;
				
			}
			
		});
		
		return checks;
		
	}
	
	// Capturar, concatenar y almacenar los nombres de los eventos y usuarios de los contratos marcados
	function get_observations(){
		
		var observaciones = "";
		
		// Recorremos la lista de contratos
		$("#tab_contracts tbody tr").each(function (index){
			
			var i_check = $(this).find('td').eq(0).find('div.icheckbox_square-green').attr('class');
			var evento = "";
			var usuario = "";
			var observacion = "";
			
			// Verificamos si está marcado y capturamos los datos de ser positivo
			if(i_check.indexOf('checked') > -1){
				
				evento = $(this).find('td').eq(2).text().trim();
				usuario = $(this).find('td').eq(3).text().trim();
				
				observacion = evento + ":" + usuario;
				
				observaciones += observacion + ",";
				
			}
			
		});
		
		// Quitamos el último caracter (,) si las observaciones no resultan vacías
		if(observaciones != ''){
			
			observaciones = observaciones.slice(0,-1);
			
		}
		
		// Almacenamos las observaciones resultantes
		$("#observation").val(observaciones);
		
	}
	
	// El elemento que se quiere activar (ícono de carga) si hay una petición ajax en proceso.
	var cargando_pago = $("#load_payment");
	cargando_pago.hide();
	
	// Ejecutar actualización de datos
    $("#pay_excute").click(function (e) {

        e.preventDefault();  // Para evitar que se envíe por defecto
        // Expresion regular para validar el correo
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        // Expresion regular para validar el dni
		var RegExPattern = /^(V|E){1}(-){1}([0-9]){8}$/;

        if ($('#reference').val().trim() === "") {
          
		   swal("Disculpe,", "para continuar debe ingresar la referencia del pago");
	       $('#reference').parent().addClass('has-error');
		   
        } else if ($('#date').val().trim() === "") {

		   swal("Disculpe,", "para continuar debe ingresar nombre");
	       $('#date').parent().addClass('has-error');
	       
        } else if ($('#account_id').val() == '0') {
			
		  swal("Disculpe,", "para continuar debe seleccionar la cuenta");
	       $('#account_id').parent().addClass('has-error');
		   
		} else {
            
            var formData = new FormData(document.getElementById("ejecutar_pago"));  // Forma de capturar todos los datos del formulario
			
			// evento ajax start
			$(document).ajaxStart(function() {
				cargando_pago.show();
			});

			// evento ajax stop
			$(document).ajaxStop(function() {
				cargando_pago.hide();
			});
			
			$.ajax({
				// method: "POST",
				type: "post",
				dataType: "json",
				url: base_url+'CPayments/add',
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
					
						swal("Disculpe,", "no se ha podido procesar el pago, por favor consulte con el adminsitrador del sistema.");
						
					}else if(response['response'] == 'outdated') {
					
						swal("Disculpe,", "debe actualizar el costo de los contratos.");
						
					}else{
						
						swal({ 
							title: "Pago",
							 text: "Guardado con exito",
							  type: "success" 
							},
						function(){
						  window.location.href = base_url+'payments';
						});
						
					}
				}				
			}).fail(function() {
				console.log("error ajax");
			});
			
        }

    });
    
});

function valida_cedula(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta números
    patron =/[0-9-V]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function valida_telefono(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta números
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

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

//~ // Función para convertir a la moneda del usuario logueado el monto total a pagar
//~ function convertir(monto){
	//~ 
	//~ // Variables globales para la posterior validación del capital aprobado al validar la transacciones
    //~ var capital_aprobado_global = 0;
    //~ var coins_global = 0;
    //~ 
    //~ // Proceso de conversión de moneda (captura del equivalente a 1 dólar en las distintas monedas)
    //~ $.post('https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664', function (coins) {
		//~ 
		//~ coins_global = coins  // Tasas de conversión global
		//~ 
		//~ var valor1btc, valor1anycoin, rate = $("#iso_currency_user").val(), rates = [], cryptos;
		//~ 
		//~ // Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
		//~ $.ajax({
			//~ type: "get",
			//~ dataType: "json",
			//~ url: 'https://api.coinmarketcap.com/v1/ticker/',
			//~ async: false
		//~ })
		//~ .done(function(coin) {
			//~ if(coin.error){
				//~ console.log(coin.error);
			//~ } else {
				//~ 
				//~ cryptos = coin;
				//~ 
				//~ $.each(coin, function (i) {
					//~ if (coin[i]['symbol'] == rate){
						//~ // Obtenemos el valor de la cryptomoneda del usuario en dólares
						//~ valor1anycoin = coin[i]['price_usd'];
					//~ }
					//~ rates.push(coin[i]['symbol']);  // Colectamos los símbolos de todas las cryptomonedas
				//~ });
			//~ }				
		//~ }).fail(function() {
			//~ console.log("error ajax");
		//~ });
		//~ 
		//~ // Valor de 1 dólar en bolívares (uso de async: false para esperar a que cargue la data)
		//~ $.ajax({
			//~ type: "get",
			//~ dataType: "json",
			//~ url: 'https://s3.amazonaws.com/dolartoday/data.json',
			//~ async: false
		//~ })
		//~ .done(function(vef) {
			//~ if(vef.error){
				//~ console.log(vef.error);
			//~ } else {
				//~ valor1vef = vef['USD']['transferencia'];
			//~ }				
		//~ }).fail(function() {
			//~ console.log("error ajax");
		//~ });
		//~ 
		//~ // Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando valores de una api más acorde
		//~ if ($.inArray( $("#iso_currency_user").val(), rates ) != -1) {
			//~ 
			//~ var currency_user = 1/parseFloat(valor1anycoin);  // Tipo de moneda del usuario logueado
				//~ 
		//~ } else if($("#iso_currency_user").val() == 'VEF') {
				//~ 
			//~ var currency_user = valor1vef;  // Tipo de moneda del usuario logueado
		//~ 
		//~ } else {
		//~ 
			//~ var currency_user = coins['rates'][$("#iso_currency_user").val()];  // Tipo de moneda del usuario logueado
		//~ 
		//~ }
		//~ 
		//~ var capital_pendiente = 0;
		//~ var capital_aprobado = 0;
		//~ 
		//~ // Proceso de cálculo de capital aprobado y pendiente
		//~ $.post('<?php echo base_url(); ?>dashboard/fondos_json', function (fondos) {
			//~ 
			//~ $.each(fondos, function (i) {
				//~ 
				//~ // Conversión de cada account a dólares
				//~ var currency = fondos[i]['coin_avr'];  // Tipo de moneda de la transacción
				//~ 
				//~ // Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				//~ if ($.inArray( currency, rates ) != -1) {
					//~ 
					//~ // Primero convertimos el valor de la cryptodivisa
					//~ var valor1anycoin = 0;
					//~ rate = currency;
					//~ 
					//~ $.each(cryptos, function (i) {
						//~ if (cryptos[i]['symbol'] == rate){
							//~ // Obtenemos el valor de la cryptomoneda del usuario en dólares
							//~ valor1anycoin = cryptos[i]['price_usd'];
						//~ }
					//~ });
					//~ 
					//~ var trans_usd = parseFloat(fondos[i]['amount'])*parseFloat(valor1anycoin);
					//~ 
				//~ } else if(currency == 'VEF') {
						//~ 
					//~ var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(valor1vef);
					//~ 
				//~ } else {
					//~ 
					//~ var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(coins['rates'][currency]);
					//~ 
				//~ }
				//~ 
				//~ // Sumamos o restamos dependiendo del tipo de transacción (ingreso/egreso)
				//~ if(fondos[i]['status'] == 'waiting'){
					//~ if(fondos[i]['type'] == 'deposit'){
						//~ capital_pendiente += trans_usd;
					//~ }else{
						//~ capital_pendiente += trans_usd;
					//~ }
				//~ }
				//~ if(fondos[i]['status'] == 'approved'){
					//~ if(fondos[i]['type'] == 'deposit'){
						//~ capital_aprobado += trans_usd;
						//~ capital_aprobado_global += trans_usd;
					//~ }else{
						//~ capital_aprobado += trans_usd;
						//~ capital_aprobado_global += trans_usd;
					//~ }
				//~ }
			//~ });
			//~ 
			//~ capital_aprobado = (capital_aprobado*currency_user).toFixed(2);
			//~ 
			//~ capital_pendiente = (capital_pendiente*currency_user).toFixed(2);
			//~ 
			//~ $("#span_capital_aprobado").text(capital_aprobado);
			//~ 
		//~ }, 'json');
		//~ 
	//~ }, 'json').fail(function() {
		//~ 
		//~ // Usamos la segunda cuenta si la primera falla
		//~ // Proceso de conversión de moneda (captura del equivalente a 1 dólar en las distintas monedas)
		//~ $.post('https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85', function (coins) {
			//~ 
			//~ coins_global = coins  // Tasas de conversión global
			//~ 
			//~ var valor1btc, valor1anycoin, rate = $("#iso_currency_user").val(), rates = [], cryptos;
			//~ 
			//~ // Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
			//~ $.ajax({
				//~ type: "get",
				//~ dataType: "json",
				//~ url: 'https://api.coinmarketcap.com/v1/ticker/',
				//~ async: false
			//~ })
			//~ .done(function(coin) {
				//~ if(coin.error){
					//~ console.log(coin.error);
				//~ } else {
					//~ 
					//~ cryptos = coin;
					//~ 
					//~ $.each(coin, function (i) {
						//~ if (coin[i]['symbol'] == rate){
							//~ // Obtenemos el valor de la cryptomoneda del usuario en dólares
							//~ valor1anycoin = coin[i]['price_usd'];
						//~ }
						//~ rates.push(coin[i]['symbol']);  // Colectamos los símbolos de todas las cryptomonedas
					//~ });
				//~ }				
			//~ }).fail(function() {
				//~ console.log("error ajax");
			//~ });
			//~ 
			//~ // Valor de 1 dólar en bolívares (uso de async: false para esperar a que cargue la data)
			//~ $.ajax({
				//~ type: "get",
				//~ dataType: "json",
				//~ url: 'https://s3.amazonaws.com/dolartoday/data.json',
				//~ async: false
			//~ })
			//~ .done(function(vef) {
				//~ if(vef.error){
					//~ console.log(vef.error);
				//~ } else {
					//~ valor1vef = vef['USD']['transferencia'];
				//~ }				
			//~ }).fail(function() {
				//~ console.log("error ajax");
			//~ });
			//~ 
			//~ // Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando valores de una api más acorde
			//~ if ($.inArray( $("#iso_currency_user").val(), rates ) != -1) {
				//~ 
				//~ var currency_user = 1/parseFloat(valor1anycoin);  // Tipo de moneda del usuario logueado
					//~ 
			//~ } else if($("#iso_currency_user").val() == 'VEF') {
					//~ 
				//~ var currency_user = valor1vef;  // Tipo de moneda del usuario logueado
			//~ 
			//~ } else {
			//~ 
				//~ var currency_user = coins['rates'][$("#iso_currency_user").val()];  // Tipo de moneda del usuario logueado
			//~ 
			//~ }
			//~ 
			//~ var capital_pendiente = 0;
			//~ var capital_aprobado = 0;
			//~ 
			//~ // Proceso de cálculo de capital aprobado y pendiente
			//~ $.post('<?php echo base_url(); ?>dashboard/fondos_json', function (fondos) {
				//~ 
				//~ $.each(fondos, function (i) {
					//~ 
					//~ // Conversión de cada account a dólares
					//~ var currency = fondos[i]['coin_avr'];  // Tipo de moneda de la transacción
					//~ 
					//~ // Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando una api más acorde
					//~ if ($.inArray( currency, rates ) != -1) {
						//~ 
						//~ // Primero convertimos el valor de la cryptodivisa
						//~ var valor1anycoin = 0;
						//~ rate = currency;
						//~ 
						//~ $.each(cryptos, function (i) {
							//~ if (cryptos[i]['symbol'] == rate){
								//~ // Obtenemos el valor de la cryptomoneda del usuario en dólares
								//~ valor1anycoin = cryptos[i]['price_usd'];
							//~ }
						//~ });
						//~ 
						//~ var trans_usd = parseFloat(fondos[i]['amount'])*parseFloat(valor1anycoin);
						//~ 
					//~ } else if(currency == 'VEF') {
							//~ 
						//~ var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(valor1vef);
						//~ 
					//~ } else {
						//~ 
						//~ var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(coins['rates'][currency]);
						//~ 
					//~ }
					//~ 
					//~ // Sumamos o restamos dependiendo del tipo de transacción (ingreso/egreso)
					//~ if(fondos[i]['status'] == 'waiting'){
						//~ if(fondos[i]['type'] == 'deposit'){
							//~ capital_pendiente += trans_usd;
						//~ }else{
							//~ capital_pendiente += trans_usd;
						//~ }
					//~ }
					//~ if(fondos[i]['status'] == 'approved'){
						//~ if(fondos[i]['type'] == 'deposit'){
							//~ capital_aprobado += trans_usd;
							//~ capital_aprobado_global += trans_usd;
						//~ }else{
							//~ capital_aprobado += trans_usd;
							//~ capital_aprobado_global += trans_usd;
						//~ }
					//~ }
				//~ });
				//~ 
				//~ capital_aprobado = (capital_aprobado*currency_user).toFixed(2);
				//~ 
				//~ capital_pendiente = (capital_pendiente*currency_user).toFixed(2);
				//~ 
				//~ $("#span_capital_aprobado").text(capital_aprobado);
				//~ 
			//~ }, 'json');
			//~ 
		//~ }, 'json');  // Cierre de la conversión del monto con la segunda cuenta de openexchangerates.org
		//~ 
	//~ });  // Cierre de la conversión del monto con la primera cuenta de openexchangerates.org
	//~ 
//~ }
