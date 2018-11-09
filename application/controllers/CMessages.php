<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CMessages extends CI_Controller {

	public function __construct() {
        parent::__construct();
       
		// Load database
        $this->load->model('MMessages');
		
    }
	
	// Método para guardar un nuevo registro desde la interfaz pública
    public function add_message() {

        $datos = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'subject' => $this->input->post('subject'),
            'message' => $this->input->post('message')
        );
        // Validación de campo recaptcha
        //~ if($this->input->post('g-recaptcha-response') != ''){
			echo "Pasó";
			// Registro del mensaje en base de datos
			//~ $result_id = $this->MMessages->insert($datos);
			//~ if($result_id != 'existe cliente'){
				echo "Pasó";
				// Enviamos un correo con el mensaje a una dirección ya predeterminada
				$this->MMessages->enviarMailMessage($datos);
				//~ return True;
			//~ }
		//~ }
    }
	
	
}
