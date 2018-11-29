<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CPayments extends CI_Controller {
	
	private $coin_rate;  // Para almacenar la tasa de cambio del dólar a bolívares
	
	private $coin_openexchangerates;  // Para almacenar la tasa de cambio del dólar a las distintas divisas
	
	private $coin_coinmarketcap;  // Para almacenar la tasa de cambio del dólar a las distintas divisas
	
	// Mensaje de resultado de api de dolartoday
	private $coin_rate_message = array(
		'type' => '',
		'message' => ''
	);
	
	// Mensaje de resultado de api de openexchangerates
	private $openexchangerates_message = array(
		'type' => '',
		'message' => ''
	);
	
	// Mensaje de resultado de api de coinmarketcap
	private $coinmarketcap_message = array(
		'type' => '',
		'message' => ''
	);

	public function __construct() {
        parent::__construct();

		// Load database
		$this->load->model('MUser');
        $this->load->model('MProjects');
        $this->load->model('MCuentas');
        $this->load->model('MCoins');
        $this->load->model('MCoinRate');
        $this->load->model('MMails');
        $this->load->model('MInscription');
        $this->load->model('MFondoPersonal');
        $this->load->model('MPayments');
        
        // Load coin rate
        $this->load_rate();  // Load coin rate from api
        $this->coin_rate = $this->show_rate();  // Load coin rate from database
        $this->coin_openexchangerates = $this->load_openexchangerates();  // Load rates from openexchangerates api
        $this->coin_coinmarketcap = $this->load_rates_coinmarketcap();  // Load rates from coinmarketcap api
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Eventos";
		$data['ident_sub'] = "Pagos";
		$data['contratos'] = $this->MPayments->obtenerContratos($this->session->userdata('logged_in')['id']);
		$data['transacciones'] = $this->MPayments->obtenerTransacciones($this->session->userdata('logged_in')['id']);
		$data['accounts'] = $this->MFondoPersonal->obtener_cuentas_group();
		
		// Mensaje de la api de dolartoday
		$data['coin_rate_message'] = $this->coin_rate_message;
		
		// Mensaje de la api de openexchangerates
		$data['openexchangerates_message'] = $this->openexchangerates_message;
		
		// Mensaje de la api de coinmarketcap
		$data['coinmarketcap_message'] = $this->coinmarketcap_message;
		
		// Obtenemos el valor en dólares de las distintas divisas
		$exchangeRates = $this->coin_openexchangerates;
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'gestor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'inversor/';
		}else{
			redirect('login');
		}
		$this->load->view($perfil_folder.'payments/payments', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		
		$fecha = $this->input->post('date');
		$fecha = explode(" ", $fecha);
		$fecha = explode("/", $fecha[0]);
		$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		
		$fecha = $fecha;
		
		$user_id = 0;
		
		$amount = $this->input->post('amount');
		
		$real = 1;
		
		$datos = array(
            'user_id' => $user_id,
            'user_create_id' => $this->session->userdata('logged_in')['id'],
            'type' => $this->input->post('type'),
            'project_id' => 0,
            'account_id' => $this->input->post('account_id'),
            'date' => $fecha,
            'description' => '',
            'reference' => $this->input->post('reference'),
            'observation' => $this->input->post('observation'),
            'real' => $real,
            'rate' => 1,
            'amount' => $amount,
            'status' => 'waiting',
            'd_create' => date('Y-m-d H:i:s')
        );
        
        $result = $this->MPayments->insert($datos);
        
        if ($result) {
			
			// Actualizamos los contratos actualizándoles el id de la transacción a la que quedarán asociados
			$contract_ids = explode(";", $this->input->post('contract_ids'));
			foreach($contract_ids as $contract_id){
				
				// Armamos la data del contrato
				$data_contract = array( 
					'id'=>$contract_id, 
					'transaction_id'=>$result
				);
				
				// Actualizamos el contrato con el id de la transacción asociada
				$update = $this->MPayments->update_contract($data_contract);
				
			}
			
			echo '{"response":"ok"}';
       
        }else{
			
			echo '{"response":"error"}';
			
		}
		
    }
    
    // Método para actualizar el costo de los contratos tomando en cuenta las reglas de cada uno de los seleccionados
	public function update_cost(){
		
		$errors = 0;
		
		// Transformamos la cadena de ids en un arreglo iterable
		$contract_ids = explode(";", $this->input->post('contract_ids'));
		foreach($contract_ids as $contract_id){
		
			// Obtenemos los datos del contrato
			$contract_data = $this->MPayments->obtenerContrato($contract_id);
		
			// Obtenemos los datos del proyecto
			$project_data = $this->MProjects->obtenerProyecto($contract_data[0]->project_id);
			
			// Consultamos las reglas del proyecto
			$project_rules = $this->MInscription->get_project_rules($project_data[0]->id);
			
			// Verificamos las reglas de costo que encajen con la fecha actual
			foreach($project_rules as $rule){
				
				$cond = $rule->cond;  // Operador condicional de la regla
				$range = $rule->var2;  // Cadena de rangos de fecha de la regla
				$range = explode(";", $range);  // Separación de los rangos de fecha de la regla
				$range_from = $range[0];  // Rango desde
				$range_to = $range[1];  // Rango hasta
				
				// Tomamos la fecha actual
				$current_date = date('Y-m-d H:i:s');
				
				// Si el operador condicional es "between" y la regla es de costo
				if($cond == "between" && $rule->segment == "cost"){
					
					// Si la fecha actual está dentro del rango de fechas de la regla de costo del proyecto, actualizamos el costo de dicha regla al contrato
					$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
					if($check_in_range == true){
						
						// Armamos la data del contrato
						$data_contract = array( 
							'id'=>$contract_id, 
							'amount'=>$rule->result
						);
						
						// Actualizamos el contrato con el costo correspondiente
						if(!$update = $this->MPayments->update_contract($data_contract)){
							
							$errors++;
							
						}
						
					}
					
				}
				
			}
			
		}
		
		// Muestra de resultados
		if($errors > 0){
			
			echo '{"response":"error"}';
			
		}else{
			
			echo '{"response":"ok"}';
			
		}
		
	}
    
    // Método para actualizar el precio del dólar tomando como referencia la api de dolartoday
    public function load_rate(){
		
		$coin = 'USD';  // Moneda a convertir
		
		// Valor de 1 dólar en bolívares
		// Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		$ct = @file_get_contents("https://s3.amazonaws.com/dolartoday/data.json");
		
		if($ct){
			
			// Valor de 1 dólar en bolívares
			$get3 = file_get_contents("https://s3.amazonaws.com/dolartoday/data.json");
			$exchangeRates3 = json_decode($get3, true);
			$valor1vef = $exchangeRates3[$coin]['transferencia'];
			
			// Verificamos el valor del dólar
			if($valor1vef != 0 && $valor1vef != null && $valor1vef != ''){
			
				$data_reg = array(
					'coin' => $coin,
					'rate' => $valor1vef,
					'd_create' => date('Y-m-d')
				);
				
				$reg = $this->MCoinRate->insert($data_reg);
				
			}else{
			
				// Cargamos un mensaje de error
				$this->coin_rate_message['type'] = 'error';
				$this->coin_rate_message['message'] = '1';
			
			}
			
		} else {
			
			$this->coin_rate_message['type'] = 'error';
			$this->coin_rate_message['message'] = '2';
		
		}
		
	}
	
	
	// Método para retornar el precio del dólar más actualizado en la base de datos
    public function show_rate(){
		
		// Consultamos los registros de las tasas
		$tasas = $this->MCoinRate->obtener();
		
		$valor_actual = 1;
		
		if(count($tasas) > 0){
		
			foreach($tasas as $tasa){
					
				$valor_actual = $tasa->rate;
				
			}
		
		}
			
		return number_format($valor_actual, 2, '.', '');
		
	}
	
    
    // Método para obtener el valor del dólar en las distintas divisas tomando como referencia la api de openexchangerates
    public function load_openexchangerates(){
		
		$exchangeRates = array();
		
		// Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		$ct = @file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
		
		if($ct){
			
			$get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=65148900f9c2443ab8918accd8c51664");
			//~ // Se decodifica la respuesta JSON
			$exchangeRates = json_decode($get, true);
			
			$this->openexchangerates_message['type'] = 'message1';
			$this->openexchangerates_message['message'] = '1';
			
		} else {
			
			// Si ha fallado la carga de la api con la key primaria intentamos con la key secundaria
			$ct2 = @file_get_contents("https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85");
			
			if($ct2){
				
				$get = file_get_contents("https://openexchangerates.org/api/latest.json?app_id=1d8edbe4f5d54857b1686c15befc4a85");
				//~ // Se decodifica la respuesta JSON
				$exchangeRates = json_decode($get, true);
				
				$this->openexchangerates_message['type'] = 'message2';
				$this->openexchangerates_message['message'] = '2';
				
			}else{
				
				$this->openexchangerates_message['type'] = 'error';
				$this->openexchangerates_message['message'] = '3';
				
			}
		}
		
		return $exchangeRates;
		
	}
	
    
    // Colectando los symbolos de todas las cryptomonedas soportadas por la plataforma de coinmarketcap
    public function load_rates_coinmarketcap(){
		
		$exchangeRates2 = array();
		
		// Con el uso de @ evitamos la impresión forzosa de errores que hace file_get_contents()
		$ct = @file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
		
		if($ct){
			
			$get = file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
			//~ // Se decodifica la respuesta JSON
			$exchangeRates2 = json_decode($get, true);
			
			$this->coinmarketcap_message['type'] = 'message1';
			$this->coinmarketcap_message['message'] = '1';
			
		} else {
			
			$this->coinmarketcap_message['type'] = 'error';
			$this->coinmarketcap_message['message'] = '2';
			
		}
		
		return $exchangeRates2;
		
	}
	
	
}
