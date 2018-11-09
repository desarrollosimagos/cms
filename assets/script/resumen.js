$(document).ready(function(){
	
	$('.footable').footable();  // Aplicamos el plugin footable
	
	// Capturamos la base_url
    var base_url = $("#base_url").val();
    
    // Definición de columnas según el perfil del usuario logueado
    var aoColumns;
    
    if($("#profile_id").val().trim() == '1' || $("#profile_id").val().trim() == '2'){
		aoColumns = [
			{"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "none", "sWidth": "30%"},
			{"sClass": "none", "sWidth": "30%"},
			{"sClass": "registro center", "sWidth": "10%"}
		];
	}else if($("#profile_id").val().trim() == '3'){
		aoColumns = [
			{"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			//~ {"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "none", "sWidth": "30%"},
			{"sClass": "none", "sWidth": "30%"},
			{"sClass": "registro center", "sWidth": "10%"}
		];
	}else{
		aoColumns = [
			{"sClass": "registro center", "sWidth": "5%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			//~ {"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "registro center", "sWidth": "10%"},
			{"sClass": "none", "sWidth": "30%"},
			{"sClass": "none", "sWidth": "30%"},
			{"sClass": "registro center", "sWidth": "10%"}
		];
	}
    
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
			"url": base_url+"dashboard/transactions_json"
		},
		"columnDefs": [
			{
				//~ "target": [0, 3, 4],
				"orderable":false,
			}
		],
		//Nuevo
        "oLanguage": {"sUrl": base_url+"assets/js/es.txt"},
        "aoColumns": aoColumns
    });

	
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

        var id = id.split(';');
        id = id[0];

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
             
                $.post(base_url+'transactions/validar/', {'id': id, 'account_id': account_id, 'amount': amount, 'tipo': tipo, 'status': 'approved'}, function (response) {

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
                             window.location.href = base_url+'dashboard';
                         });
                    }
                }, 'json');
            }else{
				
				//~ alert('denied');
				
				$.post(base_url+'transactions/validar/', {'id': id, 'account_id': account_id, 'amount': amount, 'tipo': tipo, 'status': 'denied'}, function (response) {

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
                             window.location.href = base_url+'dashboard';
                         });
                    }
                    
                }, 'json');
				
			}
			
        });
        
    });
    
    // Proceso de carga de datos a los gráficos
    // Datos del gráfico 1
    var graph_disponible = $("#graph_disponible").val().trim();
    graph_disponible = graph_disponible.split(' ');
    graph_disponible = graph_disponible[0];
    
    var graph_ingreso_pendiente = $("#graph_ingreso_pendiente").val().trim();
    graph_ingreso_pendiente = graph_ingreso_pendiente.split(' ');
    graph_ingreso_pendiente = graph_ingreso_pendiente[0];
    
    var graph_egreso_pendiente = $("#graph_egreso_pendiente").val().trim();
    graph_egreso_pendiente = graph_egreso_pendiente.split(' ');
    graph_egreso_pendiente = graph_egreso_pendiente[0];
    
    var labelsA;
    
    if($("#lang_session").val().trim() == "spanish"){
		labelsA = ["Capital en cuenta","Depósito pendiente","Retiro Pendiente" ];
	}else{
		labelsA = ["Capital in account","Pending deposit","Withdrawal pending" ];
	}
    
    var doughnutData = {
        labels: labelsA,
        datasets: [{
            data: [graph_disponible,graph_ingreso_pendiente,graph_egreso_pendiente],
            backgroundColor: ["#a3e1d4","#dedede","#b5b8cf"]
        }]
    } ;

    var doughnutOptions = {
        responsive: true
    };

    var ctx4 = document.getElementById("finances").getContext("2d");
    new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions});
    
    // Datos del gráfico 2
    var graph_invertido = $("#graph_invertido").val().trim();
    graph_invertido = graph_invertido.split(' ');
    graph_invertido = graph_invertido[0];
    
    var graph_retornado = $("#graph_retornado").val().trim();
    graph_retornado = graph_retornado.split(' ');
    graph_retornado = graph_retornado[0];
    
    var labelsB;
    
    if($("#lang_session").val().trim() == "spanish"){
		labelsB = ["Capital invertido","Dividendo"];
	}else{
		labelsB = ["Invested capital","Dividend"];
	}
    
    var doughnutData = {
        labels: labelsB,
        datasets: [{
            data: [graph_invertido,graph_retornado],
            backgroundColor: ["#a3e1d4","#dedede"]
        }]
    } ;

    var doughnutOptions = {
        responsive: true
    };

    var ctx4 = document.getElementById("investments").getContext("2d");
    new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions});
    
    
    /*// Proceso de conversión de moneda (captura del equivalente a 1 dólar en las distintas monedas)
    $.post('https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664', function (coins) {
		
		var valor1btc, valor1vef;
		
		// Valor de 1 btc en dólares (uso de async: false para esperar a que cargue la data)
		$.ajax({
			type: "get",
			dataType: "json",
			url: 'https://api.coinmarketcap.com/v1/ticker/',
			async: false
		})
		.done(function(btc) {
			if(btc.error){
				console.log(btc.error);
			} else {
				valor1btc = btc[0]['price_usd'];
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
		if ($("#iso_currency_user").val() == 'BTC') {
			
			var currency_user = 1/parseFloat(valor1btc);  // Tipo de moneda del usuario logueado
				
		} else if($("#iso_currency_user").val() == 'VEF') {
				
			var currency_user = valor1vef;  // Tipo de moneda del usuario logueado
		
		} else {
		
			var currency_user = coins['rates'][$("#iso_currency_user").val()];  // Tipo de moneda del usuario logueado
		
		}
		
		var capital_pendiente = 0;
		var ingreso_pendiente = 0;
		var egreso_pendiente = 0;
		var capital_aprobado = 0;
		
		// Proceso de carga de capital aprobado
		$.post(base_url+'dashboard/fondos_json', function (fondos) {
			
			$.each(fondos, function (i) {
				
				// Conversión de cada monto a dólares
				var currency = fondos[i]['coin_avr'];  // Tipo de moneda de la transacción
				
				// Si el tipo de moneda de la transacción es Bitcoin (BTC) o Bolívares (VEF) hacemos la conversión usando una api más acorde
				if (currency == 'BTC') {
					
					var trans_usd = parseFloat(fondos[i]['amount'])*parseFloat(valor1btc);
					
				} else if(currency == 'VEF') {
						
					var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(valor1vef);
					
				} else {
					
					var trans_usd = parseFloat(fondos[i]['amount'])/parseFloat(coins['rates'][currency]);
					
				}
				// alert(trans_usd);
				// console.log("id: "+fondos[i]['id']+" - "+"tipo: "+fondos[i]['tipo']+" - "+"monto: "+fondos[i]['amount']+" - "+trans_usd+" - "+fondos[i]['coin_avr']+" - "+"status: "+fondos[i]['status']);
				
				// Sumamos o restamos dependiendo del tipo de transacción (ingreso/egreso)
				if(fondos[i]['status'] == 'waiting'){
					if(fondos[i]['tipo'] == 'deposit'){
						ingreso_pendiente += trans_usd;
					}else if(fondos[i]['tipo'] == 'withdraw'){
						egreso_pendiente += trans_usd;
					}
				}
				if(fondos[i]['status'] == 'approved'){
					capital_aprobado += trans_usd;
				}
			});
			
			var decimals;
			if($("#decimals_currency_user").val().trim() != ""){
				decimals = parseFloat($("#decimals_currency_user").val());
			}else{
				decimals = 2;
			}
			var symbol = $("#symbol_currency_user").val();
			
			$("#span_aprobado").text((capital_aprobado*currency_user).toFixed(decimals)+" "+symbol);
			
			$("#span_pendiente").text((capital_pendiente*currency_user).toFixed(decimals)+" "+symbol);
			
			$("#span_pendiente").css('display', 'none');
			
			$("#span_ingreso_pendiente").text((ingreso_pendiente*currency_user).toFixed(decimals)+" "+symbol);
			
			$("#span_egreso_pendiente").text((egreso_pendiente*currency_user).toFixed(decimals)+" "+symbol);
			
		}, 'json');
		
	}, 'json');*/
	
});
