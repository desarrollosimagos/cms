<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CShareProfit extends CI_Controller {

	public function __construct() {
        parent::__construct();


       
		// Load database
        $this->load->model('MShareProfit');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Inversiones";
		$data['ident_sub'] = "Repartir_Ganancias";
		$data['listar'] = $this->MShareProfit->obtener();
		
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
		}
		$this->load->view($perfil_folder.'share_profit/lista', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function share() {
		$datos = array(
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'icon' => $_FILES['icon']['name'],
            'price' => $_POST['price'],
            'status' => $_POST['status'],
        );
        $result = $this->MShareProfit->insert($datos);
        if ($result) {

			// Sección para el registro del archivo en la ruta establecida para tal fin (assets/public/img/demos/medical)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando

			if (move_uploaded_file($_FILES['icon']['tmp_name'], $ruta."/assets/public/img/demos/medical/".$_FILES['icon']['name'])) {
				echo "El fichero es válido y se subió con éxito.\n";
			} else {
				echo "¡Posible ataque de subida de ficheros!\n";
			}
       
        }
    }	
	
}
