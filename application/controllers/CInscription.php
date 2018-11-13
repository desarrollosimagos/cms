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
        $this->load->model('MCuentas');
        $this->load->model('MCoins');
        $this->load->model('MCoinRate');
        
        // Load coin rate
        $this->load_rate();  // Load coin rate from api
        $this->coin_rate = $this->show_rate();  // Load coin rate from database
        $this->coin_openexchangerates = $this->load_openexchangerates();  // Load rates from openexchangerates api
        $this->coin_coinmarketcap = $this->load_rates_coinmarketcap();  // Load rates from coinmarketcap api
		
    }
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Inscribir";
		$data['ident_sub'] = "Inscribir";
		$data['monedas'] = $this->MCoins->obtener();
		$data['usuarios'] = $this->MUser->obtener();
		$data['proyectos'] = $this->MProjects->listar();
		$data['project_types'] = $this->MProjects->obtenerTipos();
		
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
		
		$publico = false;
		if($this->input->post('public') == "on"){
			$publico = true;
		}
		
		$datos = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'type' => $this->input->post('type'),
            'valor' => $this->input->post('valor'),
            'public' => $publico,
            'coin_id' => $this->input->post('coin_id'),
            'status' => 1,
            //~ 'user_id' => $this->session->userdata('logged_in')['id'],
            'd_create' => date('Y-m-d H:i:s')
        );
        
        $result = $this->MProjects->insert($datos);
        
        //~ echo $result;
        
        // Si el proyecto fue registrado satisfactoriamente registramos las photos
        if ($result != 'existe') {
			
			// Sección para el registro de las fotos en la ruta establecida para tal fin (assets/img/projects)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			//~ // print_r($_FILES);
			$i = 0;
			
			$errors = 0;
			
			foreach($_FILES['imagen']['name'] as $imagen){
				
				if($imagen != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$imagen);
					$ext = $ext[1];
					$datos2 = array(
						'project_id' => $result,
						'photo' => "photo".($i+1)."_".$result.".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_photo = $this->MProjects->insert_photo($datos2);
					
					if (!move_uploaded_file($_FILES['imagen']['tmp_name'][$i], $ruta."/assets/img/projects/photo".($i+1)."_".$result.".".$ext)) {
						
						$errors += 1;
						
					}
					
				}
				$i++;  // Incrementamos
			}
			
			// Sección para el registro de los documentos en la ruta establecida para tal fin (assets/documents)
			$j = 0;
			
			$errors2 = 0;
			
			foreach($_FILES['documento']['name'] as $documento){
				
				if($documento != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$documento);
					$ext = $ext[1];
					$datos3 = array(
						'project_id' => $result,
						'description' => "document".($j+1)."_".$result.".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_documento = $this->MProjects->insert_document($datos3);
					
					if (!move_uploaded_file($_FILES['documento']['tmp_name'][$j], $ruta."/assets/documents/document".($j+1)."_".$result.".".$ext)) {
						
						$errors2 += 1;
						
					}
					
				}
				$j++;  // Incrementamos
			}
			
			// Sección para el registro de las lecturas recomendadas en la ruta establecida para tal fin (assets/readings)
			$k = 0;
			
			$errors3 = 0;
			
			foreach($_FILES['lectura']['name'] as $lectura){
				
				if($lectura != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$lectura);
					$ext = $ext[1];
					$datos4 = array(
						'project_id' => $result,
						'description' => "reading".($k+1)."_".$result.".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_lectura = $this->MProjects->insert_reading($datos4);
					
					if (!move_uploaded_file($_FILES['lectura']['tmp_name'][$k], $ruta."/assets/readings/reading".($k+1)."_".$result.".".$ext)) {
						
						$errors3 += 1;
						
					}
					
				}
				$k++;  // Incrementamos
			}
			
			if($errors > 0){
				
				echo '{"response":"error2"}';
				
			}else if($errors2 > 0){
				
				echo '{"response":"error3"}';
				
			}else if($errors3 > 0){
				
				echo '{"response":"error4"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
       
        }else{
			
			echo '{"response":"error1"}';
			
		}
    }
	
	
	// Método para editar
    public function edit() {
		
		$this->load->view('base');
		$data['ident'] = "Inscribir";
		$data['ident_sub'] = "Inscribir";
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->MProjects->obtenerProyecto($data['id']);
        $data['monedas'] = $this->MCoins->obtener();
        $data['fotos_asociadas'] = $this->MProjects->obtenerFotos($data['id']);
        $data['documentos_asociados'] = $this->MProjects->obtenerDocumentos($data['id']);
        $data['lecturas_asociadas'] = $this->MProjects->obtenerLecturas($data['id']);
        $data['project_types'] = $this->MProjects->obtenerTipos();
        
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
        $this->load->view($perfil_folder.'inscription/editar', $data);
		$this->load->view('footer');
    }
	
	// Método para actualizar
    public function update() {
		
		$publico = false;
		if($this->input->post('public') == "on"){
			$publico = true;
		}
		
		$datos = array(
			'id' => $this->input->post('id'),
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'type' => $this->input->post('type'),
            'valor' => $this->input->post('valor'),
            'public' => $publico,
            'coin_id' => $this->input->post('coin_id'),
            'd_update' => date('Y-m-d H:i:s')
		);
		
        $result = $this->MProjects->update($datos);
        
        if ($result) {
			
			// Sección para el registro del archivo en la ruta establecida para tal fin (assets/img/productos)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			//~ print_r($_FILES);
			$i = 0;  // Indice de la imágen
			
			$errors = 0;
			
			foreach($_FILES['imagen']['name'] as $imagen){
				
				if($imagen != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$imagen);
					$ext = $ext[1];
					$datos2 = array(
						'project_id' => $_POST['id'],
						'photo' => "photo".($i+1)."_".$_POST['id'].".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					//~ echo "photo".($i+1)."_".$_POST['id'].".".$ext;
					$insertar_photo = $this->MProjects->insert_photo($datos2);
					
					if (!move_uploaded_file($_FILES['imagen']['tmp_name'][$i], $ruta."/assets/img/projects/photo".($i+1)."_".$_POST['id'].".".$ext)) {
						
						$errors += 1;
						
					}
					
				}
				$i++;  // Incrementamos
			}
			
			// Sección para el registro de los documentos en la ruta establecida para tal fin (assets/documents)
			$j = 0;
			
			$errors2 = 0;
			
			foreach($_FILES['documento']['name'] as $documento){
				
				if($documento != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$documento);
					$ext = $ext[1];
					$datos3 = array(
						'project_id' => $_POST['id'],
						'description' => "document".($j+1)."_".$_POST['id'].".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_documento = $this->MProjects->insert_document($datos3);
					
					if (!move_uploaded_file($_FILES['documento']['tmp_name'][$j], $ruta."/assets/documents/document".($j+1)."_".$_POST['id'].".".$ext)) {
						
						$errors2 += 1;
						
					}
					
				}
				$j++;  // Incrementamos
				
			}
			
			// Sección para el registro de las lecturas recomendadas en la ruta establecida para tal fin (assets/readings)
			$k = 0;
			
			$errors3 = 0;
			
			foreach($_FILES['lectura']['name'] as $lectura){
				
				if($lectura != ""){
					
					// Obtenemos la extensión
					$ext = explode(".",$lectura);
					$ext = $ext[1];
					$datos4 = array(
						'project_id' => $_POST['id'],
						'description' => "reading".($k+1)."_".$_POST['id'].".".$ext,
						'd_create' => date('Y-m-d')
					);
					
					$insertar_lectura = $this->MProjects->insert_reading($datos4);
					
					if (!move_uploaded_file($_FILES['lectura']['tmp_name'][$k], $ruta."/assets/readings/reading".($k+1)."_".$_POST['id'].".".$ext)) {
						
						$errors3 += 1;
						
					}
					
				}
				$k++;  // Incrementamos
				
			}
			
			if($errors > 0){
				
				echo '{"response":"error2"}';
				
			}else if($errors2 > 0){
				
				echo '{"response":"error3"}';
				
			}else if($errors3 > 0){
				
				echo '{"response":"error4"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
			
        }else{
			
			echo '{"response":"error1"}';
			
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
