<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CPerfil extends CI_Controller {

	public function __construct() {
        parent::__construct();

		// Load database
        $this->load->model('MPerfil');
        $this->load->model('MAcciones');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Perfiles";
		$data['listar'] = $this->MPerfil->obtener();
		$data['profile_acciones'] = $this->MPerfil->obtener_acciones();
		$data['acciones'] = $this->MAcciones->obtener();
		
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
		$this->load->view($perfil_folder.'perfiles/lista', $data);
		$this->load->view('footer');
	}
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Perfiles";
		$data['acciones'] = $this->MAcciones->obtener_without_home();
		
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
		$this->load->view($perfil_folder.'perfiles/registrar', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
        
        $data = array(
			'name' => $this->input->post('name'),
			'd_create' => date('Y-m-d H:i:s')
			//~ 'd_update' => date('Y-m-d H:i:s')
		);
        
        $result = $this->MPerfil->insert($data);
        
        echo $result;  // No comentar, esta impresión es necesaria para que se ejecute el método insert()
        
        if ($result != 'existe') {
			// Proceso de registro de acciones asociadas al perfil
			// Primero asociamos la acción por defecto (HOME)
			$action_class = $this->MAcciones->obtenerAccionByClass('Home');
			$data_action = array(
				'profile_id'=>$result, 
				'action_id'=>$action_class[0]->id, 
				'parameter_permit'=>'7777',
				'd_create' => date('Y-m-d H:i:s')
				//~ 'd_update' => date('Y-m-d H:i:s')
			);
			$this->MPerfil->insert_action($data_action);
			// Asociamos las acciones seleccionadas del combo select
			foreach($this->input->post('actions_ids') as $action_id){
				$data_action = array(
					'profile_id'=>$result, 
					'action_id'=>$action_id, 
					'parameter_permit'=>'7777',
					'd_create' => date('Y-m-d H:i:s')
					//~ 'd_update' => date('Y-m-d H:i:s')
				);
				$this->MPerfil->insert_action($data_action);
			}
        }else{
			return $result;
		}
    }
    
	// Método para editar
    public function edit() {
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Perfiles";
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->MPerfil->obtenerPerfiles($data['id']);
        $data['profile_acciones'] = $this->MPerfil->obtener_acciones_id($data['id']);
        $data['acciones'] = $this->MAcciones->obtener_without_home();
        // Lista de acciones asociadas al perfil
        $ids_actions = "";
        $query_actions = $this->MPerfil->obtener_acciones_id($data['id']);
        if(count($query_actions) > 0){
			foreach($query_actions as $action){
				$ids_actions .= $action->action_id.",";
			}
		}
		$ids_actions = substr($ids_actions,0,-1);
        $data['ids_actions'] = $ids_actions;
        
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
        $this->load->view($perfil_folder.'perfiles/editar', $data);
		$this->load->view('footer');
    }
	
	// Método para actualizar
    public function update() {
		
		$data = array(
			'id'=>$this->input->post('id'),
			'name'=>$this->input->post('name'),
			//~ 'd_create' => date('Y-m-d H:i:s'),
			'd_update' => date('Y-m-d H:i:s')
		);
		
        $result = $this->MPerfil->update($data);
        
        echo $result;  // No comentar, esta impresión es necesaria para que se ejecute el método update()
        
        if ($result) {
			// Proceso de registro de acciones asociadas al perfil
			$ids_actions = array(); // Aquí almacenaremos los ids de las acciones a asociar
			// Asociamos las nuevas acciones seleccionadas del combo select
			foreach($this->input->post('actions_ids') as $action_id){
				// Primero verificamos si ya está asociado cada acción, si no lo está, lo insertamos
				$check_associated = $this->MPerfil->obtener_accion_ids($data['id'], $action_id);
				//~ echo count($check_associated);
				if(count($check_associated) == 0){
					$data_action = array(
						'profile_id'=>$data['id'], 
						'action_id'=>$action_id, 
						'parameter_permit'=>'7777',
						'd_create' => date('Y-m-d H:i:s')
						//~ 'd_update' => date('Y-m-d H:i:s')
					);
					$this->MPerfil->insert_action($data_action);
				}
				// Vamos colectando los ids recorridos
				$ids_actions[] = $action_id;
			}
			
			// Validamos que acciones han sido quitadas del combo select para proceder a borrar las relaciones
			// Primero buscamos todas las acciones asociadas al perfil
			$query_associated = $this->MPerfil->obtener_acciones_id($data['id']);
			if(count($query_associated) > 0){
				// Verificamos cuales de las acciones no están en la nueva lista
				foreach($query_associated as $association){
					// Primero verificamos los datos correspondientes a la acción para omitir el proceso si es de la clase Home
					$query_action = $this->MAcciones->obtenerAccion($association->action_id);
					if($query_action[0]->class != 'Home'){
						if(!in_array($association->action_id, $ids_actions)){
							// Eliminamos la asociacion de la tabla profile_actions
							$this->MPerfil->delete_profile_action($data['id'], $association->action_id);
						}
					}
				}
			}
			
			// Actualizamos la permisología
			$data_permisos = $this->input->post('data');
			
			foreach ($data_permisos as $campo){
				// Concatenamos los permisos como una cadena
				$parameter = $campo['crear'].$campo['editar'].$campo['eliminar'].$campo['validar'];
				
				// Nuevos datos de la acción asociada
				$data_ps = array(
					'profile_id' => $data['id'],
					'action_id' => $campo['id'],
					'parameter_permit' => $parameter,
					//~ 'd_create' => date('Y-m-d H:i:s'),
					'd_update' => date('Y-m-d H:i:s')
				);
				
				// Actualizamos los permisos para la acción asociada
				$result = $this->MPerfil->update_action($data_ps);
			}
			
        }else{
			return $result;
		}
    }
    
	// Método para eliminar
	function delete($id) {
		
        $result = $this->MPerfil->delete($id);
        
        echo $result;
    }
	
	
}
