<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CServices extends CI_Controller {

	public function __construct() {
        parent::__construct();


       
		// Load database
        $this->load->model('MServices');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['listar'] = $this->MServices->obtener();
		$this->load->view('services/lista', $data);
		$this->load->view('footer');
	}
	
	public function register()
	{
		$this->load->view('base');
		$this->load->view('services/registrar');
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		$datos = array(
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'icon' => $_FILES['icon']['name'],
            'price' => $_POST['price'],
            'status' => $_POST['status'],
        );
        $result = $this->MServices->insert($datos);
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
	
	// Método para editar
    public function edit() {
		
		$this->load->view('base');
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->MServices->obtenerServices($data['id']);
        $this->load->view('services/editar', $data);
		$this->load->view('footer');
    }
	
	// Método para actualizar
    public function update() {
		if($_FILES['icon']['name'] != ''){
			$datos = array(
				'id' => $_POST['id'],
				'name' => $_POST['name'],
				'description' => $_POST['description'],
				'icon' => $_FILES['icon']['name'],
				'price' => $_POST['price'],
				'status' => $_POST['status'],
			);
		}else{
			$datos = array(
				'id' => $_POST['id'],
				'name' => $_POST['name'],
				'description' => $_POST['description'],
				'price' => $_POST['price'],
				'status' => $_POST['status'],
			);
		}
        $result = $this->MServices->update($datos);
        if ($result) {
			// Sección para el registro del archivo en la ruta establecida para tal fin (assets/public/img/demos/medical)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			if($_FILES['icon']['name'] != ''){
				if (move_uploaded_file($_FILES['icon']['tmp_name'], $ruta."/assets/public/img/demos/medical/".$_FILES['icon']['name'])) {
					echo "El fichero es válido y se subió con éxito.\n";
				} else {
					echo "¡Posible ataque de subida de ficheros!\n";
				}
			}     
        }
    }
    
	// Método para eliminar
	function delete($id) {
		
        $result = $this->MServices->delete($id);
        if ($result) {
          /*  $this->libreria->generateActivity('Eliminado País', $this->session->userdata['logged_in']['id']);*/
        }
    }
	
	public function ajax_service()
    {                                          #Campo         #Tabla                #ID
        $result = $this->MServices->obtener();
        echo json_encode($result);
    }
	
	
}
