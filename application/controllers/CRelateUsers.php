<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CRelateUsers extends CI_Controller {

	public function __construct() {
        parent::__construct();


       
		// Load database
        $this->load->model('MRelateUsers');
        $this->load->model('MUser');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Asociaciones";
		$data['listar'] = $this->MRelateUsers->obtener();
		$data['asociaciones'] = $this->MRelateUsers->associated_investors();
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'gestor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'inversor/';
		}else{
			redirect('login');
		}
		$this->load->view($perfil_folder.'relate_users/lista', $data);
		$this->load->view('footer');
	}
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Asociaciones";
		$data['asesores'] = $this->MRelateUsers->obtener_asesores();
		$data['inversores'] = $this->MRelateUsers->obtener_inversores();
		// Construimos las listas con los ids de los inversores y asesores ya asociados
		// Asesores asociados
		$associated_advisers = array();
		foreach($this->MRelateUsers->associated_investors() as $asesor){
			if(!in_array($asesor->userfrom_id, $associated_advisers)){
				$associated_advisers[] = $asesor->userfrom_id;
			}
		}
		// Inversores asociados
		$associated_investors = array();
		foreach($this->MRelateUsers->associated_investors() as $inversor){
			if(!in_array($inversor->userto_id, $associated_investors)){
				$associated_investors[] = $inversor->userto_id;
			}
		}
		//~ print_r($associated_advisers);
		//~ print_r($associated_investors);
		$data['asesores_asociados'] = $associated_advisers;
		$data['inversores_asociados'] = $associated_investors;
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'gestor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'inversor/';
		}else{
			redirect('login');
		}
		$this->load->view($perfil_folder.'relate_users/registrar', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		
		if($this->session->userdata['logged_in']['profile_id'] == 4){
			
			$asesor = $this->session->userdata['logged_in']['id'];
			
		}else{
			
			$asesor = $this->input->post('userfrom_id');
			
		}
        
        $exito = 0;
        
        // Asociamos los inversores seleccionadas del combo select
		foreach($this->input->post('inversores') as $inversor){
			
			$data_inversor = array(
				'userfrom_id' => $asesor,
				'userto_id' => $inversor,
				'd_create' => date('Y-m-d H:i:s')
			);
			
			if($this->MRelateUsers->insert($data_inversor)){
				$exito += 1;
			}
			
		}
        
        if ($exito > 0) {

			echo '{"response":"ok"}';
       
        }else{
			
			echo '{"response":"error"}';
			
		}
		
    }
	
	// Método para editar
    public function edit() {
		
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Asociaciones";
        $data['id'] = $this->uri->segment(3);
        $data['asesor'] = $this->MUser->obtenerUsers($data['id']);
		$data['inversores'] = $this->MRelateUsers->obtener_inversores();
		// Construimos la lista con los ids de los inversores ya asociados, excluyendo los pertenecientes al usuario actual
		$general_associations = array();  // Asociaciones generales
		$associated_investors = array();  // Asociaciones propias del asesor editado
		foreach($this->MRelateUsers->associated_investors() as $association){
			// Filtramos cuales asociaciones pertenecen al asesor editado y cuales no
			if($association->userfrom_id != $data['id']){
				// Limitamos que el id del inversor sea añadido al arreglo sólo una vez
				if(!in_array($association->userto_id, $general_associations)){
					$general_associations[] = $association->userto_id;
				}
			}else{
				$associated_investors[] = $association->userto_id;
			}
		}
		//~ print_r($associated_investors);
		$data['asociaciones_generales'] = $general_associations;
		$data['inversores_asociados'] = $associated_investors;
		
		// Filtro para cargar las vistas según el perfil del usuario logueado
		$perfil_id = $this->session->userdata('logged_in')['profile_id'];
		$perfil_folder = "";
		if($perfil_id == 1 || $perfil_id == 2){
			$perfil_folder = 'plataforma/';
		}else if($perfil_id == 3){
			$perfil_folder = 'gestor/';
		}else if($perfil_id == 4){
			$perfil_folder = 'inversor/';
		}else{
			redirect('login');
		}
        $this->load->view($perfil_folder.'relate_users/editar', $data);
		$this->load->view('footer');
    }
	
	// Método para actualizar
    public function update() {
		
		$errors = 0;
		
		// Proceso de registro de inversores asociados al asesor
		$ids_investors = array(); // Aquí almacenaremos los ids de los inversores a asociar
		// Asociamos los nuevos inversores seleccionados del combo select
		foreach($this->input->post('inversores') as $inversor_id){
			// Primero verificamos si ya está asociado cada inversor, si no lo está, lo insertamos
			$check_associated = $this->MRelateUsers->obtenerRelacionIds($this->input->post('id'), $inversor_id);
			//~ echo count($check_associated);
			if(count($check_associated) == 0){
				$data_relate_users = array(
					'userfrom_id'=>$this->input->post('id'),
					'userto_id'=>$inversor_id,
					'd_create' => date('Y-m-d H:i:s')
				);
				if(!$this->MRelateUsers->insert($data_relate_users)){
					$errors += 1;
				}
			}
			// Vamos colectando los ids recorridos
			$ids_investors[] = $inversor_id;
		}
		
		// Validamos que inversores han sido quitados del combo select para proceder a borrar las relaciones
		// Primero buscamos todos los inversores asociados al asesor
		$query_associated = $this->MRelateUsers->obtenerRelacion($this->input->post('id'));
		if(count($query_associated) > 0){
			// Verificamos cuáles de los inversores no están en la nueva lista
			foreach($query_associated as $association){
				if(!in_array($association->userto_id, $ids_investors)){
					// Eliminamos la asociacion de la tabla relate_users
					if($this->MRelateUsers->delete_adviser_investor($this->input->post('id'), $association->userto_id)){
						$errors += 1;
					}
				}
			}
		}
		
		if ($errors > 0) {
			
			echo '{"response":"error"}';
			
        }else{
			
			echo '{"response":"ok"}';
			
		}
		
    }
    
	// Método para eliminar
	function delete($id) {
		
        $result = $this->MRelateUsers->delete_investors($id);
        
        if ($result) {
			
			echo '{"response":"ok"}';
			
        }else{
			
			echo '{"response":"error"}';
			
		}
        
    }
	
	public function ajax_relate()
    {
        $result = $this->MRelateUsers->obtener();
        echo json_encode($result);
    }
	
	
}
