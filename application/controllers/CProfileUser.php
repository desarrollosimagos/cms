<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CProfileUser extends CI_Controller {

	public function __construct() {
        parent::__construct();
       
		// Load database
        $this->load->model('MProfileUser');
        $this->load->model('MCoins');
        $this->load->model('MUser');
        $this->load->model('MWelcome');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Perfil_de_usuario";  // Se añade el caracter "_" para suplantar los espacios y dar compatibilidad con la función de marcador de menú
		$data['ident_sub'] = "";
		$data['editar'] = $this->MUser->obtenerUsers($this->session->userdata('logged_in')['id']);
		$data['editar_data'] = $this->MProfileUser->obtenerUserData($this->session->userdata('logged_in')['id']);
		// Reconstrucción de la data con valores vacíos si no hay datos complementarios asociados al usuario logueado
		if(count($data['editar_data']) == 0){
			// Armamos la data vacía en un arreglo bidimensional
			$data['editar_data'] = array(
				array(
					'dni' => '',
					'gender' => '0',
					'birthday' => '',
					'phone' => '',
					'emergency_contact' => '',
					'emergency_phone' => ''
				)
			);
			// Convertimos la data en un arreglo de objetos
			$data['editar_data'] = json_decode( json_encode( $data['editar_data'] ), false );
		}
		$data['monedas'] = $this->MCoins->obtener();
		$data['idiomas'] = $this->MWelcome->get_langs();
		
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
		$this->load->view($perfil_folder.'profileuser/profileuser', $data);
		$this->load->view('footer');
	}
	
	// Método para actualizar los datos del usuario
    public function update() {
		
		// Actualización de datos básicos
		$data = array(
			'id' => $this->input->post('id'),
			'username' => $this->input->post('username'),
			'name' => $this->input->post('name'),
			'alias' => $this->input->post('alias'),
			'lang_id' => $this->input->post('lang_id'),
			'd_update' => date('Y-m-d H:i:s'),

		);
		
        $result = $this->MUser->update($data);
		
		// Actualización de datos complementarios
		if($this->input->post('birthday') != ''){
			$birthday = explode("/", $this->input->post('birthday'));
			$birthday = $birthday[2] . "-" . $birthday[1] . "-" . $birthday[0];
		}else{
			$birthday = "";
		}
		$data2 = array(
			'user_id' => $this->input->post('id'),
			'dni' => $this->input->post('dni'),
			'gender' => $this->input->post('gender'),
			'birthday' => $birthday,
			'phone' => $this->input->post('phone'),
			'emergency_contact' => $this->input->post('emergency_contact'),
			'emergency_phone' => $this->input->post('emergency_phone'),
			'd_create' => date('Y-m-d H:i:s'),
			'd_update' => date('Y-m-d H:i:s')

		);
		
        $result2 = $this->MProfileUser->update($data2);
        
        //~ echo $result;
        
        //~ echo $result2;
        
        if ($result && $result2) {
			
			// Sección para el registro de la foto en la ruta establecida para tal fin (assets/img/userss)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			//~ // print_r($_FILES);
			$i = 0;
			
			$errors = 0;
				
			if($_FILES['image']['name'][0] != ""){
				
				// Obtenemos la extensión
				$ext = explode(".", $_FILES['image']['name'][0]);
				$ext = $ext[1];
				$image = "user_".$data['id'].".".$ext;
				
				if (!move_uploaded_file($_FILES['image']['tmp_name'][0], $ruta."/assets/img/users/user_".$data['id'].".".$ext)) {
					
					$errors += 1;
					
				}else{
					
					$data_user = array(
						'id' => $data['id'],
						'username' => $this->input->post('username'),
						'image' => $image,
					);
					$update_user = $this->MUser->update($data_user);
				
				}
				
				$i++;
			}
			
			if($errors > 0){
				
				echo '{"response":"error1"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
			
        }else{
			
			echo '{"response":"error"}';
			
		}
    }
	
	
}
