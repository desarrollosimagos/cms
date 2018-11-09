<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CUser extends CI_Controller {

	public function __construct() {
        parent::__construct();

		// Load database
        $this->load->model('MUser');
		$this->load->model('MPerfil');
        $this->load->model('MAcciones');
        $this->load->model('MCoins');
        $this->load->model('MWelcome');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Usuarios";
		$data['listar'] = $this->MUser->obtener();
		$data['acciones'] = $this->MAcciones->obtener();
		$data['permisos'] = $this->MUser->obtener_permisos();
		
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
		$this->load->view($perfil_folder.'user/lista', $data);
		$this->load->view('footer');
	}
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Usuarios";
		$data['list_perfil'] = $this->MPerfil->obtener();
		$data['acciones'] = $this->MAcciones->obtener_without_home();
		$data['monedas'] = $this->MCoins->obtener();
		$data['idiomas'] = $this->MWelcome->get_langs();
		//~ $data['user_tiendas'] = $this->MUser->obtenerUsersTiendas();
		
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
		$this->load->view($perfil_folder.'user/registrar',$data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		
		//~ print_r($this->input->post('actions_ids'));
		//~ exit();
		
		$data = array(
			'username' => $this->input->post('username'),
			'name' => $this->input->post('name'),
			'alias' => $this->input->post('alias'),
			'profile_id' => $this->input->post('profile_id'),
			'coin_id' => $this->input->post('coin_id'),
			'lang_id' => $this->input->post('lang_id'),
			'user_create_id' => $this->session->userdata('logged_in')['id'],
			'admin' => $this->input->post('admin'),
			'password' => 'pbkdf2_sha256$12000$' . hash("sha256", $this->input->post('password')),
			'status' => $this->input->post('status'),
			'd_create' => date('Y-m-d H:i:s'),
			//~ 'd_update' => date('Y-m-d H:i:s'),

		);
		
        //~ $result = $this->MUser->insert($data);
        
        //~ echo $result;  // No comentar, esta impresión es necesaria para que se ejecute el método insert()
        
        if ($result = $this->MUser->insert($data)){
			//~ echo $result;
			// Nos aseguramos de que la acción correspondiente a 'CUENTAS' sea asociada al usuario registrado
			$accion = $this->MAcciones->obtenerAccionByName('CUENTAS');
			$id_accion = $accion[0]->id;
			$perfil_action = $this->MPerfil->obtener_accion_ids($this->input->post('profile_id'), $id_accion);
			//~ // Si el id de la acción 'CUENTAS' no está presente en el arreglo de actions_ids y si tampoco está ya asociado
			//~ // al perfil del usuario registrado asociamos manualmente la acción, esto asegurará la asociación con dicha acción.
			//~ if(count($this->input->post('actions_ids')) > 0){
				//~ if(!in_array($id_accion, $this->input->post('actions_ids')) && count($perfil_action) <= 0){
					//~ $data = array('user_id'=>$result, 'action_id'=>$id_accion, 'parameter_permit'=>'7770');
					//~ $this->MUser->insert_action($data);
				//~ }
			//~ }else{
				//~ if(count($perfil_action) <= 0){
					//~ $data = array('user_id'=>$result, 'action_id'=>$id_accion, 'parameter_permit'=>'7770');
					//~ $this->MUser->insert_action($data);
				//~ }
			//~ }
			
			$errors = 0;
			
			// Si hay acciones asociadas al usuario, registramos la relación en la tabla 'permissions'
			if($this->input->post('actions_ids') != ""){
				// Inserción de las relaciones usuario-tienda				
				foreach($this->input->post('actions_ids') as $action_id){
					if($this->input->post('profile_id') == 1){
						$data = array('user_id'=>$result, 'action_id'=>$action_id, 'parameter_permit'=>'7777');
					}else{
						$data = array('user_id'=>$result, 'action_id'=>$action_id, 'parameter_permit'=>'7700');
					}
					if(!$this->MUser->insert_action($data)){
						$errors += 1;
					}
				}
			}
			
			// Sección para el registro de la foto en la ruta establecida para tal fin (assets/img/userss)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			//~ // print_r($_FILES);
			$i = 0;
			
			$errors2 = 0;
				
			if($_FILES['image']['name'][0] != ""){
				
				// Obtenemos la extensión
				$ext = explode(".", $_FILES['image']['name'][0]);
				$ext = $ext[1];
				$image = "user_".$result.".".$ext;
				
				if (!move_uploaded_file($_FILES['image']['tmp_name'][0], $ruta."/assets/img/users/user_".$result.".".$ext)) {
					
					$errors2 += 1;
					
				}else{
					//~ echo $result;
					$data_user = array(
						'id' => $result,
						'username' => $this->input->post('username'),
						'image' => $image,
					);
					$update_user = $this->MUser->update($data_user);
				
				}
				
				$i++;
			}
			
			if($errors > 0){
				
				echo '{"response":"error1"}';
				
			}else if($errors2 > 0){
				
				echo '{"response":"error2"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
			       
        }else{
			
			echo '{"response":"error"}';
			
		}
    }
	
	// Método para editar
    public function edit() {
		$this->load->view('base');
		$data['ident'] = "Usuarios";
		$data['ident_sub'] = "Usuarios";
        $data['id'] = $this->uri->segment(3);
		$data['list_perfil'] = $this->MPerfil->obtener();
		//~ $data['user_tiendas'] = $this->MUser->obtenerUsersTiendas();
        $data['editar'] = $this->MUser->obtenerUsers($data['id']);
		$data['permissions'] = $this->MUser->obtener_permisos_id($data['id']);
		$data['acciones'] = $this->MAcciones->obtener_without_home();
		$data['monedas'] = $this->MCoins->obtener();
		$data['idiomas'] = $this->MWelcome->get_langs();
		// Lista de ids de acciones asociadas al usuario
        $ids_actions = "";
        $query_actions = $this->MUser->obtener_permisos_id($data['id']);
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
        $this->load->view($perfil_folder.'user/editar', $data);
		$this->load->view('footer');
    }
	
	// Método para actualizar
    public function update() {
		
		$data = array(
			'id' => $this->input->post('id'),
			'username' => $this->input->post('username'),
			'name' => $this->input->post('name'),
			'alias' => $this->input->post('alias'),
			'profile_id' => $this->input->post('profile_id'),
			'coin_id' => $this->input->post('coin_id'),
			'lang_id' => $this->input->post('lang_id'),
			'admin' => $this->input->post('admin'),
			'status' => $this->input->post('status'),
			'd_update' => date('Y-m-d H:i:s'),

		);
		
        $result = $this->MUser->update($data);
        
        //~ echo $result;
        
        if ($result) {
			
			$errors = 0;
			
			// Si hay nuevas acciones asociadas al usuario, los registramos en la tabla 'permissions'
			if($this->input->post('actions_ids') != ""){
				// Proceso de registro de acciones asociadas al perfil
				$ids_actions = array(); // Aquí almacenaremos los ids de las acciones a asociar
				// Asociamos las nuevas acciones seleccionadas del combo select
				foreach($this->input->post('actions_ids') as $action_id){
					// Primero verificamos si ya está asociado cada acción, si no lo está, lo insertamos
					$check_associated = $this->MUser->obtener_permiso_ids($data['id'], $action_id);
					//~ echo count($check_associated);
					if(count($check_associated) == 0){
						if($this->input->post('profile_id') == 1){
							$data_action = array('user_id'=>$data['id'], 'action_id'=>$action_id, 'parameter_permit'=>'7777');
						}else{
							$data_action = array('user_id'=>$data['id'], 'action_id'=>$action_id, 'parameter_permit'=>'7700');
						}
						if(!$this->MUser->insert_action($data_action)){
							$errors += 1;
						}
					}
					// Vamos colectando los ids recorridos
					$ids_actions[] = $action_id;
				}
				
				// Validamos qué acciones han sido quitadas del combo select para proceder a borrar las relaciones
				// Primero buscamos todas las acciones asociadas al perfil
				$query_associated = $this->MUser->obtener_permisos_id($data['id']);
				if(count($query_associated) > 0){
					// Verificamos cuales de las acciones no están en la nueva lista
					foreach($query_associated as $association){
						// Primero verificamos los datos correspondientes a la acción para omitir el proceso si es de la clase Home
						$query_action = $this->MAcciones->obtenerAccion($association->action_id);
						if($query_action[0]->class != 'Home'){
							if(!in_array($association->action_id, $ids_actions)){
								// Eliminamos la asociacion de la tabla profile_actions
								if(!$this->MUser->delete_user_action($data['id'], $association->action_id)){
									$errors += 1;
								}
							}
						}
					}
				}
				
				// Actualizamos la permisología
				
				// Convertimos a formato json y posteriormente a arreglo con el segundo argumento (true)
				$data_permisos = json_decode($this->input->post('data'), true);  
				
				//~ print_r($data_permisos);
				
				foreach ($data_permisos as $campo){
					// Concatenamos los permisos como una cadena
					$parameter = $campo['crear'].$campo['editar'].$campo['eliminar'].$campo['validar'];
					
					// Nuevos datos de la acción asociada
					$data_ps = array(
						'user_id' => $data['id'],
						'action_id' => $campo['id'],
						'parameter_permit' => $parameter,
					);
					
					// Actualizamos los permisos para la acción asociada
					if(!$result = $this->MUser->update_action($data_ps)){
						$errors += 1;
					}
				}
				
			}else{
				// Eliminamos las asociaciones de la tabla permissions correspondientes al usuario seleccionado
				// Primero buscamos todas las acciones asociados al usuario
				$query_associated = $this->MUser->obtener_permisos_id($this->input->post('id'));
				if(count($query_associated) > 0){
					// Eliminamos las asociaciones encontradas
					foreach($query_associated as $association){
						$this->MUser->delete_user_action($this->input->post('id'), $association->action_id);
					}
				}
			}
			
			// Sección para el registro de la foto en la ruta establecida para tal fin (assets/img/userss)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			//~ // print_r($_FILES);
			$i = 0;
			
			$errors2 = 0;
				
			if($_FILES['image']['name'][0] != ""){
				
				// Obtenemos la extensión
				$ext = explode(".", $_FILES['image']['name'][0]);
				$ext = $ext[1];
				$image = "user_".$data['id'].".".$ext;
				
				if (!move_uploaded_file($_FILES['image']['tmp_name'][0], $ruta."/assets/img/users/user_".$data['id'].".".$ext)) {
					
					$errors2 += 1;
					
				}else{
					
					$data_user = array(
						'id' => $data['id'],
						'username' => $this->input->post('username'),
						'image' => $image,
					);
					$update_user = $this->MUser->update($data_user);
				
				}
				
				$i++;
			}
			
			if($errors > 0){
				
				echo '{"response":"error1"}';
				
			}else if($errors2 > 0){
				
				echo '{"response":"error2"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
			
        }else{
			
			echo '{"response":"error"}';
			
		}
    }
    
    // Método para actualizar de forma directa el status de un usuario
    public function update_status($id) {
		$accion = $this->input->post('accion');
		$estatus = 1;
		
		if ($accion == 'desactivar'){
			$estatus = 0;
		}
		
		// Armamos la data a actualizar
        $data_usuario = array(
			'id' => $id,
			'status' => $estatus,
			'd_update' => date('Y-m-d H:i:s'),
        );
        
        // Actualizamos el usuario con los datos armados
		$result = $this->MUser->update_status($data_usuario);
	}
    
	// Método para eliminar
	function delete($id) {
        $result = $this->MUser->delete($id);
    }
    
    // Método público para cargar un json con las acciones no asignadas al perfil seleccionado
    public function search_actions()
    {
		$id_profile = $this->input->post('profile_id');  // id del perfil
		
        $result = $this->MUser->search_profile_actions($id_profile);  // Consultamos los ids de las acciones asociadas al perfil
        // Armamos una lista de ids de las acciones asociadas al perfil
        $list_actions_ids = array();
        foreach($result as $relation){
			$list_actions_ids[] = $relation->action_id;
		}
		
		if(count($list_actions_ids) > 0){
			// Si hay acciones asociadas al perfil
			$result2 = $this->MUser->search_actions($list_actions_ids);  // Buscamos las acciones que no están asociadas al perfil
		}else{
			// Si no hay acciones asociadas al perfil
			$result2 = $this->MAcciones->obtener_without_home();  // Buscamos todas las acciones
		}
		
        echo json_encode($result2);
    }
	
	// Método público para cargar un json con las acciones no asignadas al usuario seleccionado
    public function search_actions2()
    {
		$id_usuario = $this->input->post('user_id');  // id del usuario
		
        $result = $this->MUser->search_permissions($id_usuario);  // Consultamos los ids de las acciones asociadas al usuario
        // Armamos una lista de ids de las acciones asociadas al usuario
        $list_actions_ids = array();
        foreach($result as $relation){
			$list_actions_ids[] = $relation->action_id;
		}
		
		if(count($list_actions_ids) > 0){
			// Si hay acciones asociadas al usuario
			$result2 = $this->MUser->search_actions2($list_actions_ids);  // Buscamos las acciones que están asociadas al usuario
		}else{
			// Si no hay acciones asociadas al usuario
			$result2 = $list_actions_ids;
		}
		
        echo json_encode($result2);
    }
    
    public function search_username(){
		$usuario = $this->input->post('usuario');  // nombre del usuario
		
		$result = $this->MUser->obtenerUserName($usuario);
		
		echo json_encode($result);
	}
	
	// Método de verificación de tiempo de sesión
	public function transcurrido()
	{
		
		// Si estamos logueados verificamos si el tiempo de la sesión ya ha expirado
		if(isset($this->session->userdata['logged_in'])){
			
			$user_id = $this->session->userdata['logged_in']['id'];
		
			// Control de tiempo de sesión			
			$fechaGuardada = $this->input->post("time_session");  // Variable de sesión con la hora de inicio
			$tiempo_limite = $this->config->item('sess_time_to_update');  // Variable global de configuración para actualizar id de sesión
			$fecha_actual = date('Y-m-d H:i:s');
			$tiempo_transcurrido = (strtotime($fecha_actual)-strtotime($fechaGuardada));  // Tiempo transcurrido
			// FORMA ANTERIOR E IMPRECISA
			//~ $ahora = time();  // Hora actual
			//~ $tiempo_transcurrido = ($ahora-$fechaGuardada);  // Tiempo transcurrido

			// Comparamos el tiempo transcurrido  
			if($tiempo_transcurrido >= $tiempo_limite){
				
				// Si el tiempo de la sesión ha alcanzado o excedido el límite configurado actualizamos su estatus en 'users_session'
				$fecha_actual = date('Y-m-d H:i:s');
				
				$usuario = array(
					'user_id' => $this->session->userdata['logged_in']['id'],
					'status' => 0,
					'd_update' => $fecha_actual
				);
				
				$update_session = $this->MUser->update_session($usuario);
				
				if($update_session){
					
					// Destruimos la sesión y devolvemos a la página de inicio
					//~ $this->session->sess_destroy();
					echo '{"update":"ok"}';
				
				}else{
				
					echo '{"update":"error"}';
					
				}
				
			}else{
			
				echo '{"update":"noupdate"}';
			
			}
			
		}
		
	}
	
}
