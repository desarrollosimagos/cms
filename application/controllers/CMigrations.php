<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CMigrations extends CI_Controller {

	public function __construct() {
        parent::__construct();
    }
	
	// Método para ejecutar las migraciones en la versión indicada presentes en el directorio application/migrations/ 
    public function index() {
		
		// Load library
        $this->load->library('migration');
		
		if(!$this->migration->latest()){
			
			echo "error";
			show_error($this->migration->error_string());
			
		}else{
		
			echo "success";
		
		}
		
    }
	
	
}
