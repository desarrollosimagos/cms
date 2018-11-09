<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CChangePasswd extends CI_Controller {

	public function __construct() {
        parent::__construct();
       
		// Load database
        $this->load->model('MChangePasswd');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Cambiar_Contraseña";  // Se añade el caracter "_" para suplantar los espacios y dar compatibilidad con la función de marcador de menú
		$data['ident_sub'] = "";
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'inversor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'asesor/';
		}else if($perfil_id == 5){
			$perfil_folder = 'gestor/';
		}else{
			redirect('login');
		}
		$this->load->view($perfil_folder.'change_passwd/change_passwd', $data);
		$this->load->view('footer');
	}
	
	// Método para actualizar de forma directa el status de un usuario
    public function update_passwd() {
		$id_user = $this->session->userdata['logged_in']['id'];
		$passwd_actual = 'pbkdf2_sha256$12000$'.hash( "sha256", $this->input->post('passwd_actual') );
		$new_passwd = $this->input->post('new_passwd');
		$confirm_new_passwd = $this->input->post('confirm_new_passwd');
		
		if($new_passwd == $confirm_new_passwd){
			
			$query = $this->MChangePasswd->verificarPasswd($id_user, $passwd_actual);
		
			if(count($query) > 0){
				
				$new_passwd = 'pbkdf2_sha256$12000$'.hash( "sha256", $this->input->post('new_passwd') );
				
				// Armamos la data a actualizar
				$data_usuario = array(
					'id' => $id_user,
					'password' => $new_passwd,
					'd_update' => date('Y-m-d H:i:s')
				);
				
				// Actualizamos el usuario con los datos armados
				$result = $this->MChangePasswd->update_passwd($data_usuario);
				
				echo '{"response":"ok"}';
				
			}else{
				
				echo '{"response":"error"}';
				
			}
				
		}else{
			
			echo '{"response":"error"}';
			
		}
		
	}
	
	
}
