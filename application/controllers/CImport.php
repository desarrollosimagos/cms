<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CImport extends CI_Controller {

	public function __construct() {
        parent::__construct();
        
        // Load database
        $this->load->model('MImport');
        $this->load->model('MUser');
        $this->load->model('MFondoPersonal');
        $this->load->model('MCuentas');
		
    }
	
	public function index()
	{
		$hmac_key = '';
			
		$hmac_secret = '';
		
		$api_url = '';
		
		if(isset($_POST['account_id'])){
			
			// Preparamos los datos HMAC para conectar con la api de localbitcoin
			$object = json_decode($_POST['json_api'], false);
			
			$hmac_key = $object[0]->hmac_key;
			
			$hmac_secret = $object[0]->hmac_secret;
			
			$api_url = $object[0]->url;
			
			// Cargamos los datos básicos del usuario de la cuenta de localbitcoins
			$get_myself = $this->get_myself($hmac_key, $hmac_secret);
			$get_myself_decode = json_decode($get_myself);
			$data['myself'] = $get_myself_decode;
			
			//~ // Ejecuta el script test_lbcapi.py para importar las transacciones 
			//~ // de una cuenta determinada de localbitcoins
			//~ exec('python assets/script/test_lbcapi.py '.$hmac_key.' '.$hmac_secret.' '.$api_url, $output);
			//~ 
			//~ $json_decode = json_decode($output[0]); // Datos sin filtrar desde la cuenta de localbitcoins
			
			// Nueva forma usando URL Client (curl)
			$get_trades_api = $this->get_trades_api($hmac_key, $hmac_secret, $api_url);
			
			$json_decode = json_decode($get_trades_api); // Datos sin filtrar desde la cuenta de localbitcoins
			
			//~ $data['listar'] = $json_decode;  // Antes, sin filtro
			
			// Filtramos las transacciones y tomamos en cuenta sólo aquellas que no estén ya registradas en base de datos
			$new_data = array('data' => array('contact_list' => array()));  // Variable para la nueva data filtrada con el formato requerido
			
			foreach($json_decode->data->contact_list as $reg){
				
				$find_reg = $this->MImport->get_by_reference($reg->data->contact_id);
				
				if($find_reg == 'no existe'){
					$new_data['data']['contact_list'][] = $reg;
				}
				
			}
			
			$new_data = json_decode(json_encode($new_data));
			
			$data['listar'] = $new_data;
			
		}else{
			
			$data['listar'] = array();
			
		}
		
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Importar_Transacciones";
		
		$data['accounts'] = $this->MImport->obtener_cuentas();
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 5){
			$perfil_folder = 'gestor/';
		}else{
			redirect('login');
		}
		$this->load->view($perfil_folder.'imports/lista', $data);
		$this->load->view('footer');
	}
	
	// Método para chequear si una cuenta tiene una api asociada
	public function check_api_account($account_id) {
		
		$data = $this->MImport->check_api_account($account_id);
		
		if($data == 'no existe'){
			
			$data = "";
			
		}
		
		echo json_encode($data);
		
	}
	
	// Método para editar
    public function edit() {
		
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Importar_Transacciones";
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->input->post();
        $data['accounts'] = $this->MFondoPersonal->obtener_cuentas_group();
		$data['usuarios'] = $this->MUser->obtener();
		$data['projects'] = $this->MFondoPersonal->obtener_proyectos_group();
        
        // Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 5){
			$perfil_folder = 'gestor/';
		}else{
			redirect('login');
		}
		$this->load->view($perfil_folder.'imports/editar', $data);
		$this->load->view('footer');
    }
	
	
/**
 * ------------------------------------------------------
 * Método público para guardar una transacción proveniente del
 * formulario de registro.
 * ------------------------------------------------------
 * 
 * Este método filtra y valida los datos tanto textuales como de ficheros
 * provenientes del formulario antes de proceder a registralos en base de datos.
 */
    public function import() {
		
		//~ print_r($this->input->post());
		//~ exit();
		
		$datos = array(
            'user_id' => $this->input->post('user_id')[0],
            'user_create_id' => $this->session->userdata('logged_in')['id'],
            'type' => $this->input->post('type')[0],
            'project_id' => $this->input->post('project_id')[0],
            'account_id' => $this->input->post('account_id')[0],
            'date' => $this->input->post('d_create')[0],
            'description' => $this->input->post('description')[0],
            'reference' => $this->input->post('reference')[0],
            'observation' => $this->input->post('observation')[0],
            'real' => 1,
            'rate' => $this->input->post('rate')[0],
            'amount' => $this->input->post('fiduciary_currency'),
            'status' => $this->input->post('status')[0],
            'd_create' => date('Y-m-d H:i:s')
        );
		
		$datos2 = array(
            'user_id' => $this->input->post('user_id')[1],
            'user_create_id' => $this->session->userdata('logged_in')['id'],
            'type' => $this->input->post('type')[1],
            'project_id' => $this->input->post('project_id')[1],
            'account_id' => $this->input->post('account_id')[1],
            'date' => $this->input->post('d_create')[1],
            'description' => $this->input->post('description')[1],
            'reference' => $this->input->post('reference')[1],
            'observation' => $this->input->post('observation')[1],
            'real' => 1,
            'rate' => $this->input->post('rate')[1],
            'amount' => $this->input->post('amount'),
            'status' => $this->input->post('status')[1],
            'd_create' => date('Y-m-d H:i:s')
        );
        
        // Validación para dar prioridad de registro a la transacción negativa
        if($datos['amount'] < 0){
			
			$result = $this->MFondoPersonal->insert($datos);  // $datos es la transacción negativa
			
			$result2 = $this->MFondoPersonal->insert($datos2);
			
		}else{
			
			$result = $this->MFondoPersonal->insert($datos2);  // $datos2 es la transacción negativa
			
			$result2 = $this->MFondoPersonal->insert($datos);
		}
        
        if ($result && $result2) {
			
			// Actualizamos la cuenta de la transacción fiduciaria
			if($this->input->post('status')[0] == 'approved'){
				
				// Obtenemos los datos de la account a actualizar
				$data_account = $this->MCuentas->obtenerCuenta($this->input->post('account_id')[0]);
				
				// Sumamos el monto de la transacción a la cuenta
				$amount_account = $data_account[0]->amount + $this->input->post('fiduciary_currency');
				
				// Armamos los nuevos datos de la cuenta
				$data_account = array(
					'id' => $this->input->post('account_id')[0],
					'amount' => $amount_account,
					'd_update' => date('Y-m-d H:i:s')
				);
				
				// Actualizamos la cuenta
				$update_account = $this->MCuentas->update($data_account);
				
			}else{
				$update_account = false;
			}
			
			// Actualizamos la cuenta de la transacción en btc
			if($this->input->post('status')[1] == 'approved'){
				
				// Obtenemos los datos de la account a actualizar
				$data_account = $this->MCuentas->obtenerCuenta($this->input->post('account_id')[1]);
				
				// Sumamos el monto de la transacción a la cuenta
				$amount_account = $data_account[0]->amount + $this->input->post('amount');
				
				// Armamos los nuevos datos de la cuenta
				$data_account = array(
					'id' => $this->input->post('account_id')[1],
					'amount' => $amount_account,
					'd_update' => date('Y-m-d H:i:s')
				);
				
				// Actualizamos la cuenta
				$update_account2 = $this->MCuentas->update($data_account);
				
			}else{
				$update_account2 = false;
			}
			
			if($update_account != false && $update_account2 != false){
				
				echo '{"response":"ok"}';
				
			}else{
				
				echo '{"response":"error"}';
				
			}
       
        }else{
			
			echo '{"response":"error"}';
			
		}
		
    }
    
    
/**
 * ------------------------------------------------------
 * Método público para cargar los datos básicos de la cuenta
 * de localbitcoin asociada a una cuenta seccionada
 * ------------------------------------------------------
 * 
 * Usa la biblioteca URL Client (curl) de php para cargar los datos 
 * básicos de la cuenta de localbitcoin asociada a una cuenta seccionada.
 */
	public function get_myself($hmac_key, $hmac_secret, $api_endpoint = '/api/myself/') {

		$search = array('.');

		$replace = array('');

		$mt = microtime(true);

		$mt = str_replace($search, $replace, $mt);

		$nonce = $mt;

		$url = 'https://localbitcoins.com'.$api_endpoint;

		$get_or_post_params_urlencoded = '';

		$message = $nonce . $hmac_key . $api_endpoint . $get_or_post_params_urlencoded;

		$message_bytes = utf8_encode($message);

		$signature = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

		$ch = curl_init('https://localbitcoins.com'.$api_endpoint);

		$options = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTPHEADER => array(
				'Apiauth-key:'.$hmac_key,
				'Apiauth-Nonce:'.$nonce,
				'Apiauth-Signature:'.$signature 
			)
		);

		curl_setopt_array($ch, $options);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}
    
    
/**
 * ------------------------------------------------------
 * Método público para listar las transacciones de localbitcoin
 * asociadas a una cuenta seccionada
 * ------------------------------------------------------
 * 
 * Usa la biblioteca URL Client (curl) de php para importar las 
 * transacciones de una cuenta determinada de localbitcoins.
 */
	public function get_trades_api($hmac_key, $hmac_secret, $api_endpoint) {

		$search = array('.');

		$replace = array('');

		$mt = microtime(true);

		$mt = str_replace($search, $replace, $mt);

		$nonce = $mt;

		$url = 'https://localbitcoins.com'.$api_endpoint;

		$get_or_post_params_urlencoded = '';

		$message = $nonce . $hmac_key . $api_endpoint . $get_or_post_params_urlencoded;

		$message_bytes = utf8_encode($message);

		$signature = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

		$ch = curl_init('https://localbitcoins.com'.$api_endpoint);

		$options = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTPHEADER => array(
				'Apiauth-key:'.$hmac_key,
				'Apiauth-Nonce:'.$nonce,
				'Apiauth-Signature:'.$signature 
			)
		);

		curl_setopt_array($ch, $options);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}
	
}
 
