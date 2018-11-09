<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Home extends CI_Controller {

    public function __construct() {
        @parent::__construct();
    }
	
	// Show login page
    public function home() {
		// Borrar la cache de sesión manualmente
		//~ $this->session->sess_destroy();
		
		$this->load->view('base');
		//~ $this->basicauthpublic->logout();
    }

}
