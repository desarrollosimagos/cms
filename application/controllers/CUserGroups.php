<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CUserGroups extends CI_Controller {

	public function __construct() {
        parent::__construct();

		// Load database
        $this->load->model('MUserGroups');
        $this->load->model('MUser');
        $this->load->model('MCuentas');
        $this->load->model('MProjects');
        $this->load->model('MRelateUsers');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Grupos_de_Inversionistas";  // Se añade el caracter "_" para suplantar los espacios y dar compatibilidad con la función de marcador de menú
		$data['listar'] = $this->MUserGroups->obtener();
		$data['group_projects'] = $this->MUserGroups->obtener_proyectos();
		//~ $data['projects'] = $this->MProjects->obtener();
		$data['projects'] = $this->MProjects->listar();
		$data['group_users'] = $this->MUserGroups->obtener_inversores();
		//~ $data['users'] = $this->MRelateUsers->obtener_inversores();
		$data['users'] = $this->MUser->obtener();
		$data['group_accounts'] = $this->MUserGroups->obtener_cuentas();
		//~ $data['accounts'] = $this->MCuentas->obtener();
		$data['accounts'] = $this->MCuentas->listar();
		
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
		$this->load->view($perfil_folder.'user_groups/lista', $data);
		$this->load->view('footer');
	}
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Grupos_de_Inversionistas";  // Se añade el caracter "_" para suplantar los espacios y dar compatibilidad con la función de marcador de menú
		//~ $data['projects'] = $this->MProjects->obtener();
		$data['projects'] = $this->MProjects->listar();
		//~ $data['inversores'] = $this->MRelateUsers->obtener_inversores();
		$data['inversores'] = $this->MUser->obtener();
		//~ $data['accounts'] = $this->MCuentas->obtener();
		$data['accounts'] = $this->MCuentas->listar();
		
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
		$this->load->view($perfil_folder.'user_groups/registrar', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
        
        $data = array(
			'name' => $this->input->post('name'),
			'd_create' => date('Y-m-d H:i:s')
			//~ 'd_update' => date('Y-m-d H:i:s')
		);
        
        $result = $this->MUserGroups->insert($data);
        
        echo $result;  // No comentar, esta impresión es necesaria para que se ejecute el método insert()
        
        if ($result != 'existe') {
			// Proceso de registro de proyectos asociados al grupo
			// Asociamos los proyectos seleccionados del combo select
			foreach($this->input->post('projects_ids') as $project_id){
				$data_project = array(
					'group_id'=>$result, 
					'project_id'=>$project_id,
					'd_create' => date('Y-m-d H:i:s')
					//~ 'd_update' => date('Y-m-d H:i:s')
				);
				$this->MUserGroups->insert_project($data_project);
			}
			// Proceso de registro de usuarios asociados al grupo
			// Asociamos los usuarios seleccionados del combo select
			foreach($this->input->post('users_ids') as $user_id){
				$data_user = array(
					'group_id'=>$result, 
					'user_id'=>$user_id,
					'd_create' => date('Y-m-d H:i:s')
					//~ 'd_update' => date('Y-m-d H:i:s')
				);
				$this->MUserGroups->insert_user($data_user);
			}
			// Proceso de registro de cuentas asociadas al grupo
			// Asociamos las cuentas seleccionadas del combo select
			foreach($this->input->post('accounts_ids') as $account_id){
				$data_account = array(
					'group_id'=>$result, 
					'account_id'=>$account_id,
					'd_create' => date('Y-m-d H:i:s')
					//~ 'd_update' => date('Y-m-d H:i:s')
				);
				$this->MUserGroups->insert_account($data_account);
			}
        }else{
			return $result;
		}
    }
    
	// Método para editar
    public function edit() {
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Grupos_de_Inversionistas";  // Se añade el caracter "_" para suplantar los espacios y dar compatibilidad con la función de marcador de menú
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->MUserGroups->obtenerGrupos($data['id']);
        $data['group_projects'] = $this->MUserGroups->obtener_proyectos_id($data['id']);
        $data['group_users'] = $this->MUserGroups->obtener_usuarios_id($data['id']);
        $data['group_accountss'] = $this->MUserGroups->obtener_cuentas_id($data['id']);
        //~ $data['projects'] = $this->MProjects->obtener();
        $data['projects'] = $this->MProjects->listar();
        //~ $data['inversores'] = $this->MRelateUsers->obtener_inversores();
        $data['inversores'] = $this->MUser->obtener();
		//~ $data['accounts'] = $this->MCuentas->obtener();
		$data['accounts'] = $this->MCuentas->listar();
        // Lista de proyectos asociados al grupo
        $ids_projects = "";
        $query_projects = $this->MUserGroups->obtener_proyectos_id($data['id']);
        if(count($query_projects) > 0){
			foreach($query_projects as $project){
				$ids_projects .= $project->project_id.",";
			}
		}
		$ids_projects = substr($ids_projects,0,-1);
        $data['ids_projects'] = $ids_projects;
        // Lista de usuarios asociados al grupo
        $ids_users = "";
        $query_users = $this->MUserGroups->obtener_usuarios_id($data['id']);
        if(count($query_users) > 0){
			foreach($query_users as $user){
				$ids_users .= $user->user_id.",";
			}
		}
		$ids_users = substr($ids_users,0,-1);
        $data['ids_users'] = $ids_users;
        // Lista de cuentas asociadas al grupo
        $ids_accounts = "";
        $query_accounts = $this->MUserGroups->obtener_cuentas_id($data['id']);
        if(count($query_accounts) > 0){
			foreach($query_accounts as $account){
				$ids_accounts .= $account->account_id.",";
			}
		}
		$ids_accounts = substr($ids_accounts,0,-1);
        $data['ids_accounts'] = $ids_accounts;
        
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
        $this->load->view($perfil_folder.'user_groups/editar', $data);
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
		
        $result = $this->MUserGroups->update($data);
        
        echo $result;  // No comentar, esta impresión es necesaria para que se ejecute el método update()
        
        if ($result) {
			
			// Proceso de registro de proyectos asociados al grupo
			$ids_projects = array(); // Aquí almacenaremos los ids de los proyectos a asociar
			// Asociamos los nuevos proyectos seleccionados del combo select
			foreach($this->input->post('projects_ids') as $project_id){
				// Primero verificamos si ya está asociado cada proyecto, si no lo está, lo insertamos
				$check_associated = $this->MUserGroups->obtener_proyectos_ids($data['id'], $project_id);
				//~ echo count($check_associated);
				if(count($check_associated) == 0){
					$data_project = array(
						'group_id'=>$data['id'], 
						'project_id'=>$project_id,
						'd_create' => date('Y-m-d H:i:s')
						//~ 'd_update' => date('Y-m-d H:i:s')
					);
					$this->MUserGroups->insert_project($data_project);
				}
				// Vamos colectando los ids recorridos
				$ids_projects[] = $project_id;
			}
			
			// Validamos que proyectos han sido quitados del combo select para proceder a borrar las relaciones
			// Primero buscamos todos los proyectos asociados al grupo
			$query_associated = $this->MUserGroups->obtener_proyectos_id($data['id']);
			if(count($query_associated) > 0){
				// Verificamos cuales de los proyectos no están en la nueva lista
				foreach($query_associated as $association){
					if(!in_array($association->project_id, $ids_projects)){
						// Eliminamos la asociacion de la tabla investorgroups_projects
						$this->MUserGroups->delete_investorgroups_project($data['id'], $association->project_id);
					}
				}
			}
			
			// Proceso de registro de usuarios asociados al grupo
			$ids_users = array(); // Aquí almacenaremos los ids de los usuarios a asociar
			// Asociamos los nuevos usuarios seleccionados del combo select
			foreach($this->input->post('users_ids') as $user_id){
				// Primero verificamos si ya está asociado cada usuario, si no lo está, lo insertamos
				$check_associated = $this->MUserGroups->obtener_usuarios_ids($data['id'], $user_id);
				//~ echo count($check_associated);
				if(count($check_associated) == 0){
					$data_user = array(
						'group_id'=>$data['id'], 
						'user_id'=>$user_id,
						'd_create' => date('Y-m-d H:i:s')
						//~ 'd_update' => date('Y-m-d H:i:s')
					);
					$this->MUserGroups->insert_user($data_user);
				}
				// Vamos colectando los ids recorridos
				$ids_users[] = $user_id;
			}
			
			// Validamos que usuarios han sido quitados del combo select para proceder a borrar las relaciones
			// Primero buscamos todos los usuarios asociados al grupo
			$query_associated = $this->MUserGroups->obtener_usuarios_id($data['id']);
			if(count($query_associated) > 0){
				// Verificamos cuales de los usuarios no están en la nueva lista
				foreach($query_associated as $association){
					if(!in_array($association->user_id, $ids_users)){
						// Eliminamos la asociacion de la tabla investorgroups_users
						$this->MUserGroups->delete_investorgroups_user($data['id'], $association->user_id);
					}
				}
			}
			
			// Proceso de registro de cuentas asociadas al grupo
			$ids_accounts = array(); // Aquí almacenaremos los ids de las cuentas a asociar
			// Asociamos las nuevas cuentas seleccionadas del combo select
			foreach($this->input->post('accounts_ids') as $account_id){
				// Primero verificamos si ya está asociada cada cuenta, si no lo está, la insertamos
				$check_associated = $this->MUserGroups->obtener_cuentas_ids($data['id'], $account_id);
				//~ echo count($check_associated);
				if(count($check_associated) == 0){
					$data_account = array(
						'group_id'=>$data['id'], 
						'account_id'=>$account_id,
						'd_create' => date('Y-m-d H:i:s')
						//~ 'd_update' => date('Y-m-d H:i:s')
					);
					$this->MUserGroups->insert_account($data_account);
				}
				// Vamos colectando los ids recorridos
				$ids_accounts[] = $account_id;
			}
			
			// Validamos que cuentas han sido quitadas del combo select para proceder a borrar las relaciones
			// Primero buscamos todas las cuentas asociadas al grupo
			$query_associated = $this->MUserGroups->obtener_cuentas_id($data['id']);
			if(count($query_associated) > 0){
				// Verificamos cuales de las cuentas no están en la nueva lista
				foreach($query_associated as $association){
					if(!in_array($association->account_id, $ids_accounts)){
						// Eliminamos la asociacion de la tabla profile_actions
						$this->MUserGroups->delete_investorgroups_account($data['id'], $association->account_id);
					}
				}
			}
			
        }else{
			return $result;
		}
    }
    
	// Método para eliminar
	function delete($id) {
        $result = $this->MUserGroups->delete($id);
        if ($result) {
          /*  $this->libreria->generateActivity('Eliminado País', $this->session->userdata['logged_in']['id']);*/
        }
    }
	
	
}
