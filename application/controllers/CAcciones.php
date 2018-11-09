<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CAcciones extends CI_Controller {

	public function __construct() {
        parent::__construct();

		// Load database
        $this->load->model('MAcciones');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Menús";
		$data['ident_sub'] = "Acciones";
		$data['listar'] = $this->MAcciones->obtener();
		
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
		$this->load->view($perfil_folder.'acciones/lista', $data);
		$this->load->view('footer');
	}
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Menús";
		$data['ident_sub'] = "Acciones";
		$data['controladores'] = $this->MAcciones->listar_controladores("application/controllers/", '');
		
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
		$this->load->view($perfil_folder.'acciones/registrar', $data);
		$this->load->view('footer');
	}
	
	  //Método para guardar un nuevo registro
    public function add() {
		
		$data = array(
			'name' => $this->input->post('name'),
			'class' => $this->input->post('class'),
			'route' => $this->input->post('route'),
			'assigned' => 0,
			'd_create' => date('Y-m-d')." ".date("H:i:s")
			//~ 'd_update' => date('Y-m-d')." ".date("H:i:s")
		);
		
        $result = $this->MAcciones->insert($data);
        
        if ($result) {

           /*$this->libreria->generateActivity('Nuevo Grupo de Usuario', $this->session->userdata('logged_in')['id']);*/
       
        }
    }
	 //Método para editar
    public function edit() {
		$this->load->view('base');
		$data['ident'] = "Menús";
		$data['ident_sub'] = "Acciones";
        $data['id'] = $this->uri->segment(3);
        $data['controladores'] = $this->MAcciones->listar_controladores("application/controllers/", $data['id']);
        $data['editar'] = $this->MAcciones->obtenerAccion($data['id']);
        
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
        $this->load->view($perfil_folder.'acciones/editar', $data);
        $this->load->view('footer');
    }
	
	//Método para actualizar
    public function update() {
		
		$data = array(
			'id' => $this->input->post('id'),
			'name' => $this->input->post('name'),
			'class' => $this->input->post('class'),
			'route' => $this->input->post('route'),
			'assigned' => 0,
			//~ 'd_create' => date('Y-m-d')." ".date("H:i:s"),
			'd_update' => date('Y-m-d')." ".date("H:i:s")
		);
		
        $result = $this->MAcciones->update($data);
        if ($result) {
        /*    $this->libreria->generateActivity('Actualizado Grupo de Usuario', $this->session->userdata['logged_in']['id']);*/
     
        }
    }
	//Método para eliminar
	function delete($id) {
		
        $result = $this->MAcciones->delete($id);
        if ($result) {
          /*  $this->libreria->generateActivity('Eliminado País', $this->session->userdata['logged_in']['id']);*/
        }
    }
	
	
}
