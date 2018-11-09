<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");

class CDolarToday extends CI_Controller {
	
	public $url;  // URL de dolartoday

    public function __construct() {
        parent::__construct();
        
        /* 
         * Definición de la url de dolartoday
         * 
         * */
        $this->url = "https://s3.amazonaws.com/dolartoday/data.json";
    }

    // Método para cargar un json con los valores del día de dolartoday
    public function index() {
		
		// Valor de 1 dólar en bolívares
		$get3 = file_get_contents($this->url);
		
		echo($get3);
		
    }

}
