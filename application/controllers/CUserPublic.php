<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CUserPublic extends CI_Controller {

	public function __construct() {
        parent::__construct();

		// Load database
        $this->load->model('MUser');
		$this->load->model('MMails');
		
    }
	
	// Método para el registro de nuevos usuarios desde el login
	public function register()
	{
		
		$this->load->view('register');
		
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		
		$data = array();
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		
		if($this->form_validation->run()==FALSE){
			$data['error'] = "Datos inválidos.";
			$this->load->view('register', $data);
		}else{
			
			// Armamos el registro
			$data = array(
				'name' => $this->input->post('name'),
				'username' => $this->input->post('username'),
				'alias' => '',
				'profile_id' => 3,
				'coin_id' => 1,
				'lang_id' => 1,
				'user_create_id' => 0,
				'admin' => 0,
				'password' => 'pbkdf2_sha256$12000$' . hash("sha256", $this->input->post('password')),
				'status' => 0,
				'd_create' => date('Y-m-d H:i:s')
	
			);
			
			// Realizamos el registro
			if ($result = $this->MUser->insert($data)){
				// echo $result;
				
				// Registramos un token del nuevo usuario para la posterior confirmación
				$data_token = array(
					'user_id' => $result, 
					'token' => hash("sha256", $result),
					'status' => 1,
					'd_create' => date('Y-m-d H:i:s')
				);
				$reg_token = $this->MUser->insert_token($data_token);
				
				// Enviamos un correo de validación a la dirección correspondiente al usuario, pasando el id encriptado
				$this->MMails->enviarMail(hash("sha256", $result), $this->input->post('username'));
				
				// Cargamos los plugins necesarios para las alertas con estilos optimizados
				if($this->load->view('plugins')){
					echo "<script>
						setTimeout(newAlert, 3000);  // Retrasamos la ejecución de las alertas por tres segundos
						function newAlert(){
							swal({ 
								title: 'Registro exitoso',
								text: 'Usuario creado satisfactoriamente. Por favor revise su correo y confirme el registro.',
								type: 'success' 
							}, function(){
								window.location.href = '".base_url()."login';
							});
						}
					</script>";
				}
					   
			}else{
				
				$data['error'] = "El usuario ya existe.";
				$this->load->view('register', $data);
				
			}
			
		}
		
		
    }
    
    // Método para validar el correo de un cliente y proceder a activarlo
    public function validar_mail() {
		// Primero consultamos el id del usuario correspondiente al token
		$data_token = $this->MUser->getToken($this->input->get('hash'));
		$id_user = $data_token[0]->user_id;
		$datos_update = array('id'=>$id_user, 'status'=>1);
		// Activamos el usuario
        $result = $this->MUser->update_status($datos_update);
        
        if($result){
			// Obtenemos y armamos los datos del cliente
			$mail = $this->MUser->obtenerUsers($id_user);
			$datos_reg = array(
				'name'=>$mail[0]->name,
				'username'=>$mail[0]->username,
				'perfil'=>'INVERSOR'
			);
			// Enviamos los datos registrados al correo del usuario y lo redireccionamos al inicio de sesión
			$this->MMails->enviarMailConfirm($datos_reg);
			redirect('login?confirm=1');
		}
    }
    
	// Método para mostrar el formulario donde se indica el usuario a cambiar la contraseña
	public function new_password()
	{
		
		$this->load->view('new_password');
		
	}
	
	// Método para en enviar un email con el enlace al formulario de cambio de contraseña.
    public function send_mail_change() {
		
		$data = array();
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		
		if($this->form_validation->run()==FALSE){
			$data['error'] = "Datos inválidos.";
			$this->load->view('new_password', $data);
		}else{
			
			// Buscamos el usuario proveniente del formulario
			$result = $this->MUser->obtenerUserName($this->input->post('username'));
			
			// Realizamos el envío
			if (count($result) > 0){
				// echo $result;
				
				// Registramos un token del usuario al que se le cambiará la contraseña para la posterior confirmación
				$data_token = array(
					'user_id' => $result[0]->id, 
					'token' => hash("sha256", $result[0]->id),
					'status' => 1,
					'd_create' => date('Y-m-d H:i:s')
				);
				$reg_token = $this->MUser->insert_token($data_token);
				
				// Enviamos un correo de validación a la dirección correspondiente al usuario
				$this->MMails->enviarMailCambio(hash("sha256", $result[0]->id), $this->input->post('username'));
				
				// Cargamos los plugins necesarios para las alertas con estilos optimizados
				if($this->load->view('plugins')){
					echo "<script>
						setTimeout(newAlert, 3000);  // Retrasamos la ejecución de las alertas por tres segundos
						function newAlert(){
							swal({ 
								title: 'Correo enviado',
								text: 'Por favor revise su correo y confirme que desea cambiar su contraseña.',
								type: 'success' 
							}, function(){
								window.location.href = '".base_url()."login';
							});
						}
					</script>";
				}
					   
			}else{
				
				$data['error'] = "El usuario que ingresó no existe.";
				$this->load->view('new_password', $data);
				
			}
			
		}
		
		
    }
    
	// Método para mostrar el formulario donde se cambia la contraseña del usuario indicado
	public function change_password()
	{
		
		$this->load->view('change_password');
		
	}
	
	// Método para en enviar un email con el enlace al formulario de cambio de contraseña.
    public function update_password() {
		
		$data = array();
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		$this->form_validation->set_rules('password2', 'Password2', 'required|trim');
		
		if($this->form_validation->run()==FALSE){
			$data['error'] = "Datos inválidos.";
			$this->load->view('change_password', $data);
		}else{
			
			// Comparamos las contraseñas y realizamos la actualización si corresponde
			if ($this->input->post('password') == $this->input->post('password2')){
				// echo $result;
				
				// Primero consultamos el id del usuario correspondiente al token
				$data_token = $this->MUser->getToken($this->input->post('hash'));
				$id_user = $data_token[0]->user_id;
				// Luego consultamos el nombre del usuario correspondiente al token
				$data_user = $this->MUser->obtenerUsers($id_user);
				$username = $data_user[0]->username;
				
				$data = array(
					'username' => $username,
					'password' => 'pbkdf2_sha256$12000$'.hash( "sha256", $this->input->post('password') )
				);
				
				// Actualizamos la contraseña
				$update_password = $this->MUser->update_passwd($data);
				
				// Armamos los datos del usuario
				$datos_reg = array(
					'username'=>$username,
					'new_password'=>$this->input->post('password')
				);
				
				// Enviamos los datos actualizados al correo del usuario y lo redireccionamos al inicio de sesión
				$this->MMails->enviarMailUpdatePasswd($datos_reg);
				redirect('login?update_password=1');
					   
			}else{
				
				$data['error'] = "Las contraseñas no coinciden.";
				$data['token'] = $this->input->post('hash');  // Reenviamos el token del usuario
				$this->load->view('change_password', $data);
				
			}
			
		}
		
		
    }
	
}
