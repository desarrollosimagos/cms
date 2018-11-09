<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CPruebas extends CI_Controller {

	public function __construct() {
        parent::__construct();
       
		// Load database
        $this->load->model('MUpdateM3');
		
    }
	
	// Método para guardar un nuevo registro desde la interfaz pública
    public function enviar_mail() {		
		$para = 'jasolorzano18@hotmail.com' . ', '; // atención a la coma
		$para .= 'solorzano202009@gmail.com';
		$titulo = 'El título';
		$mensaje = 'Hola mundo';
		$cabeceras = 'From: webmaster@example.com' . "\r\n" .
		'Reply-To: webmaster@example.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

		if(mail($para, $titulo, $mensaje, $cabeceras)){
			echo "Enviado";
		}else{
			echo "No se pudo enviar";
		}
    }
	
	
}


?>
