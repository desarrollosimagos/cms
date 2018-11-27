<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CInscription extends CI_Controller {
	
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
        $this->load->model('MInscription');
        $this->load->model('MFondoPersonal');
        $this->load->model('MCuentas');
        $this->load->model('MCoins');
        $this->load->model('MCoinRate');
        $this->load->model('MMails');
        
        // Load coin rate
        $this->load_rate();  // Load coin rate from api
        $this->coin_rate = $this->show_rate();  // Load coin rate from database
        $this->coin_openexchangerates = $this->load_openexchangerates();  // Load rates from openexchangerates api
        $this->coin_coinmarketcap = $this->load_rates_coinmarketcap();  // Load rates from coinmarketcap api
		
    }
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Eventos";
		$data['ident_sub'] = "Inscribir";
		$data['monedas'] = $this->MCoins->obtener();
		$data['usuarios'] = $this->MInscription->listar_usuarios();
		
		// Proceso de carga y marca de proyectos
		$listar = array();
		
		$proyectos = $this->MInscription->listar_proyectos();
		
		foreach($proyectos as $proyecto){
			
			// Consultamos las reglas del proyecto
			$project_rules = $this->MInscription->get_project_rules($proyecto->id);
		
			$data_proyecto = array(
				'id' => $proyecto->id,
				'name' => $proyecto->name,
				'description' => $proyecto->description,
				'type' => $proyecto->type,
				'valor' => $proyecto->valor,
				'public' => $proyecto->public,
				'coin' => $proyecto->coin_avr." (".$proyecto->coin.")",
				'status' => $proyecto->status,
				'inscription_available' => 'no'
			);
			
			// Verificamos si la fecha actual encaja con la regla de 'inscription' del proyecto
			foreach($project_rules as $rule){
				
				$cond = $rule->cond;  // Operador condicional de la regla
				$range = $rule->var2;  // Cadena de rangos de fecha de la regla
				$range = explode(";", $range);  // Separación de los rangos de fecha de la regla
				$range_from = $range[0];  // Rango desde
				$range_to = $range[1];  // Rango hasta
				
				// Tomamos la fecha actual
				$current_date = date('Y-m-d H:i:s');
				
				// Si el operador condicional es "between" y la regla es de inscripción
				if($cond == "between" && $rule->segment == "inscription"){
					
					// Si la fecha actual está dentro del rango de fechas de la regla de inscripción del proyecto, marcamos el proyecto como disponible
					$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
					if($check_in_range == true){
						
						// Marcado del proyecto como disponible
						$data_proyecto['inscription_available'] = 'yes';
						
					}
					
				}
				
			}
			
			// Incluimos sólo los proyectos activos
			if($proyecto->status > 0){
				
				$listar[] = $data_proyecto;
				
			}
		
		}
		
		$listar = json_decode( json_encode( $listar ), false );  // Conversión a objeto
		
		$data['proyectos'] = $listar;  // Lista de proyectos disponibles y no disponibles
		
		// Verificamos si hemos recibidos el id de algún proyecto para seleccionar
		if($this->input->get('project_id')){
			$data['project_id'] = $this->input->get('project_id');
		}else{
			$data['project_id'] = '';
		}
		
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
		$this->load->view($perfil_folder.'inscription/registrar', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		
		$project_id = $this->input->post('project_id');  // Id del proyecto
		
		$data_event = $this->MProjects->obtenerProyecto($project_id);  // Datos del evento
		
		$user_id = $this->input->post('user_id');  // Id del usuario
		
		$data_user = $this->MUser->obtenerUsers($user_id);  // Datos del usuario
		
		$category = $this->input->post('category');  // Id del usuario
		
		$current_date = date('Y-m-d H:i:s');  // Fecha actual
		
		// Primero verificamos si el usuario a colocado su fecha de nacimiento
		$birthday = $this->MUser->search_user_data($user_id);
		
		// Si el usuario ya ha cargado su fecha de nacimiento
		if(count($birthday) > 0 && $birthday[0]->birthday != '' && $birthday[0]->birthday != '0000-00-00 00:00:00'){
		
			// Consultamos las reglas del proyecto
			$project_rules = $this->MInscription->get_project_rules($project_id);
			
			// Verificamos el monto del evento consultando el rango de fechas de cada regla de costo
			// Verificamos la fecha de inicio del evento consultando el rango de fechas de cada regla de fecha
			// Verificamos la fecha de fin de inscripción del proyecto consultando el rango de fechas de cada regla de inscripción
			$project_cost = 0;
			$project_date = '';
			$project_pay_expiration = '';
			foreach($project_rules as $rule){
				
				$cond = $rule->cond;  // Operador condicional de la regla
				$range = $rule->var2;  // Cadena de rangos de fecha de la regla
				$range = explode(";", $range);  // Separación de los rangos de fecha de la regla
				$range_from = $range[0];  // Rango desde
				$range_to = $range[1];  // Rango hasta
				
				// Si es una regla de costo
				if($rule->segment == "cost" && $cond == "between"){
					// Si la fecha actual está dentro del rango de fechas de la regla tomamos ese costo como monto del proyecto
					$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
					if($check_in_range == true){
						$project_cost = $rule->result;
					}
				}
				
				// Si es una regla de costo
				if($rule->segment == "inscription" && $cond == "between"){
					// Si la fecha actual está dentro del rango de fechas de la regla tomamos ese costo como monto del proyecto
					$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
					if($check_in_range == true){
						$project_date = $range_to;
					}
				}
				
				// Si es una regla de costo
				if($rule->segment == "date" && $cond == "between"){
					// Si la fecha actual está dentro del rango de fechas de la regla tomamos ese costo como monto del proyecto
					$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
					if($check_in_range == true){
						$project_pay_expiration = $range_from;
					}
				}
				
			}
			
			// Sección para el registro del contrato
			$contract = array(
				'project_id' => $project_id,
				'user_id' => $user_id,
				'user_create_id' => $this->session->userdata('logged_in')['id'],
				'transaction_id' => 0,
				'type' => '',
				'created_on' => date('Y-m-d H:i:s'),
				'payback' => 0,
				'amount' => $project_cost
			);
			
			$contract_id = $this->MInscription->insert_contract($contract);  // Guardamos el contrato
			
			if ($contract_id != 'existe') {
				
				$exists = 0;
				
				// Verificamos las reglas que encajen con la fecha actual y la fecha de nacimiento del usuario
				foreach($project_rules as $rule){
					
					$cond = $rule->cond;  // Operador condicional de la regla
					$range = $rule->var2;  // Cadena de rangos de fecha de la regla
					$range = explode(";", $range);  // Separación de los rangos de fecha de la regla
					$range_from = $range[0];  // Rango desde
					$range_to = $range[1];  // Rango hasta
					
					// Si el operador condicional es "between" y la regla es de costo
					if($cond == "between" && $rule->segment == "cost"){
						// Si es una regla de costo tomamos la fecha actual
						$date = $current_date;
						
						// Si la fecha actual está dentro del rango de fechas de la regla del proyecto, 
						// registramos dicha regla como regla del contrato
						$check_in_range = $this->MInscription->check_in_range($date, $range_from, $range_to);
						if($check_in_range == true){
							
							// Sección para el registro de la regla del contrato
							$contract_rules = array(
								'var1' => $date,
								'cond' => $cond,
								'var2' => $rule->var2,
								'contracts_id' => $contract_id,
								'segment' => $rule->segment,
								'result' => $rule->result
							);
							
							$contract_rule_id = $this->MInscription->insert_contract_rule($contract_rules);  // Guardamos la regla del contrato
							
							if($contract_rule_id == 'existe'){
								$exists += 1;
							}
							
						}
					
					// Si el operador condicional es "between" y la regla es de categoría	
					}else if($cond == "between" && $rule->segment == "category"){
						// Si es una regla de categoría tomamos la fecha de nacimiento
						$date = $birthday[0]->birthday;
						
						// Si la fecha de nacimiento está dentro del rango de fechas de la regla del proyecto y 
						// coincide con la categoría seleccionada, registramos dicha regla como regla del contrato
						$check_in_range = $this->MInscription->check_in_range($date, $range_from, $range_to);
						if($check_in_range == true && $rule->result == $category){
							
							// Sección para el registro de la regla del contrato
							$contract_rules = array(
								'var1' => $date,
								'cond' => $cond,
								'var2' => $rule->var2,
								'contracts_id' => $contract_id,
								'segment' => $rule->segment,
								'result' => $rule->result
							);
							
							$contract_rule_id = $this->MInscription->insert_contract_rule($contract_rules);  // Guardamos la regla del contrato
							
							if($contract_rule_id == 'existe'){
								$exists += 1;
							}
							
						}
						
					}
					
				}
				
				if($exists > 3){
					
					echo '{"response":"error2"}';
					
				}else{
					
					// Armamos los datos de la Inscripción
					$datos_reg = array(
						'username' => $data_user[0]->username,
						'event_name' => $data_event[0]->name,
						'event_date' => $project_date,
						'pay_expiration' => $project_pay_expiration,
						'event_cost' => $project_cost
					);
					
					// Enviamos los datos actualizados al correo del usuario y lo redireccionamos al inicio de sesión
					$this->MMails->enviarMailInscriptionEvent($datos_reg);
					
					echo '{"response":"ok"}';
					
				}
		   
			}else{
				
				echo '{"response":"contract_exists"}';
				
			}
			
		}else{
			
			echo '{"response":"no_birthday"}';
			
		}
		
    }
	
	
	// Método para consultar la categorías disponibles por usuario y proyecto seleccionado
	public function load_categories(){
				
		// Obtenemos los datos del usuario
		$user_id = $this->input->post('user_id');  // Id del usuario
		$user_data = $this->MUser->obtenerUsers($user_id);
		
		// Obtenemos los datos del proyecto
		$project_id = $this->input->post('project_id');  // Id del proyecto
		$project_data = $this->MProjects->obtenerProyecto($user_id);
		
		// Consultamos las reglas del proyecto
		$project_rules = $this->MInscription->get_project_rules($project_id);
		
		// Preparamos el arreglo que almacenará las categorías disponibles para el usuario
		$categories = array();
		
		// Obtenemos la fecha de nacimiento del usuario
		$birthday = $this->MUser->search_user_data($user_id);
		
		// Si el usuario ya ha cargado su fecha de nacimiento
		if(count($birthday) > 0 && $birthday[0]->birthday != '' && $birthday[0]->birthday != '0000-00-00 00:00:00'){
		
			// Verificamos las reglas que encajen con la fecha de nacimiento del usuario
			foreach($project_rules as $rule){
				
				$cond = $rule->cond;  // Operador condicional de la regla
				$range = $rule->var2;  // Cadena de rangos de fecha de la regla
				$range = explode(";", $range);  // Separación de los rangos de fecha de la regla
				$range_from = $range[0];  // Rango desde
				$range_to = $range[1];  // Rango hasta
				
				// Tomamos la fecha de nacimiento
				$date = $birthday[0]->birthday;
				
				// Si el operador condicional es "between" y la regla es de categoría
				if($cond == "between" && $rule->segment == "category"){
					
					// Si la fecha de nacimiento está dentro del rango de fechas de la regla de categoría del proyecto, cargamos la categoría de dicha regla
					$check_in_range = $this->MInscription->check_in_range($date, $range_from, $range_to);
					if($check_in_range == true){
						
						// Sección para el registro de la regla del contrato
						$contract_rule = array(
							'id' => $rule->id,
							'var1' => $date,
							'cond' => $cond,
							'var2' => $rule->var2,
							'project_id' => $rule->project_id,
							'segment' => $rule->segment,
							'result' => $rule->result
						);
						
						$categories[] = $contract_rule;  // Guardamos la regla del contrato en el arreglo de categorías
						
					}
					
				}
				
			}
			
			// Convertimos el arreglo de categorías a formato json
			echo json_encode($categories);
		
		}else{
			
			echo '{"response":"no_birthday"}';
			
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
