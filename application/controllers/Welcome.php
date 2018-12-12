<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
        parent::__construct();
        
		// Load database
        $this->load->model('MUser');
        $this->load->model('MPerfil');
        $this->load->model('MAcciones');
        $this->load->model('MMenus');
        $this->load->model('MSubMenus');
        $this->load->model('MCoins');
        $this->load->model('MTiposCuenta');
        $this->load->model('MWelcome');
        $this->load->model('MProjects');
        $this->load->model('MInscription');
    }
	 
	public function index()
	{
		// Validamos la base de datos
		$exists = $this->exists_database();
		if($exists == "existe"){
			
			$this->migrations();  // Ejecutamos las migraciones
			
		}	
		// Cargamos la plantilla base
		$this->load->view('base');
		$this->load->view('publico/inicio');
	}

	public function detail_projects($id)
	{
		$this->load->view('base');
		//~ $id         = $this->input->get('id');
		$get_detail = $this->MWelcome->get_slider_detail($id);
		$fotos_asociadas = $this->MProjects->obtenerFotos($id);
		$get_id_lang = $this->MWelcome->get_lang_id();
		$detalles_asociados = $this->MProjects->obtenerDetalles($id, $get_id_lang);
		$documentos_asociados = $this->MProjects->obtenerDocumentos($id);
        $lecturas_asociadas = $this->MProjects->obtenerLecturas($id);
        
        // Consultamos las reglas del proyecto
		$project_rules = $this->MInscription->get_project_rules($id);
		
		// Determinamos si el proyecto está disponible para inscripción, verificando si la fecha actual encaja con la regla de 'inscription' del proyecto
		$inscription_available = 'no';
		
		// Determinamos la fecha de inscripción del proyecto
		$date_inscription = '';
		
		// Determinamos la fecha de inicio del proyecto
		$date_event = '';
		
		// Determinamos el costo del proyecto
		$cost = 0;
		
		foreach($project_rules as $rule){
			
			$cond = $rule->cond;  // Operador condicional de la regla
			$range = $rule->var2;  // Cadena de rangos de fecha de la regla
			$range = explode(";", $range);  // Separación de los rangos de fecha de la regla
			$range_from = $range[0];  // Rango desde
			$range_to = $range[1];  // Rango hasta
			
			// Tomamos la fecha actual
			$current_date = date('Y-m-d H:i:s');
			
			// Si el operador condicional es "between" y la regla es de inscripción
			if($cond == "between" && $rule->segment == "inscription"){
				
				$user_id = $this->session->userdata('logged_in')['id'];  // Id del usuario logueado
				
				// Si la fecha actual está dentro del rango de fechas de la regla de inscripción del proyecto, marcamos el proyecto como disponible
				$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
				if($check_in_range == true && $this->get_contract_user($user_id, $id) == 0){
					
					// Marcado del proyecto como disponible si el usuario no está inscrito
					$inscription_available = 'yes';
					
				}
				
				// Definimos la fecha de inscripción del proyecto
				$date_inscription = explode(" ", $range_from);
				$date_inscription = $date_inscription[0];
				
			}
			
			// Si el operador condicional es "between" y la regla es de fecha de inicio
			if($cond == "between" && $rule->segment == "date"){
				
				// Definimos la fecha de inscripción del proyecto
				$date_event = explode(" ", $range_from);
				$date_event = $date_event[0];
				
			}
			
			// Si el operador condicional es "between" y la regla es de costo
			if($cond == "between" && $rule->segment == "cost"){
				
				// Si la fecha actual está dentro del rango de fechas de la regla de costo del proyecto, tomamos ese precio como costo del proyecto
				$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
				if($check_in_range == true){
					
					// Definimos el costo del proyecto
					$cost = $rule->result;
					
				}
				
				
				
			}
			
		}
        
		$this->load->view('publico/detail_projects', compact('get_detail', 'fotos_asociadas', 'documentos_asociados', 'lecturas_asociadas', 'detalles_asociados', 'inscription_available', 'date_inscription', 'date_event', 'cost'));
		$this->load->view('footer');
	}
	
	public function admin()
	{
		$this->load->view('login_form');
	}
	
	public function start()
	{
		$this->load->view('base');
		$this->load->view('publico/start');
	}
	
	public function possibilities()
	{
		$this->load->view('base');
		$this->load->view('publico/possibilities');
	}
	
	public function investments()
	{
		$this->load->view('base');
		$data['ident'] = "Inversiones";
		$data['ident_sub'] = "Inversiones";
		
		$data['current_events'] = 0;
		$data['upcoming_events'] = 0;
		$data['past_events'] = 0;
		
		$listar = array();
		
		$proyectos = $this->MProjects->listar();
		
		foreach($proyectos as $proyecto){
			
			// Proceso de búsqueda de fotos asociadas al proyecto
			$num_fotos = $this->MProjects->buscar_photos($proyecto->id);
			$num_fotos = count($num_fotos);
			$fotos_asociadas = $this->MProjects->obtenerFotos($proyecto->id);
			
			// Proceso de búsqueda de notificaciones asociadas al proyecto
			$num_news = $this->MProjects->buscar_noticias($proyecto->id);
			$num_news = count($num_news);
			
			// Proceso de búsqueda de documentos asociados al proyecto
			$num_docs = $this->MProjects->buscar_documentos($proyecto->id);
			$num_docs = count($num_docs);
			
			// Proceso de búsqueda de lecturas recomendadas asociadas al proyecto
			$num_readings = $this->MProjects->buscar_lecturas($proyecto->id);
			$num_readings = count($num_readings);
			
			// Proceso de búsqueda de grupos de inversores asociados al proyecto
			$groups = $this->MProjects->buscar_grupos($proyecto->id);
			$groups_names = "";
			foreach($groups as $group){
				$groups_names .= $group->name.",";
			}
			$groups_names = substr($groups_names, 0, -1);
			
			// Proceso de búsqueda de transacciones asociados al proyecto para calcular el porcentaje recaudado
			$transacctions = $this->MProjects->buscar_transacciones($proyecto->id);
			if($proyecto->valor != null && $proyecto->valor > 0){
				$porcentaje = (float)$transacctions[0]->ingresos/(float)$proyecto->valor*100;
			}else{
				$porcentaje = "null";
			}
			
			// Consultamos las reglas del proyecto
			$project_rules = $this->MInscription->get_project_rules($proyecto->id);
			
			$data_proyecto = array(
				'id' => $proyecto->id,
				'name' => $proyecto->name,
				'description' => $proyecto->description,
				'type' => $proyecto->type,
				'valor' => $proyecto->valor,
				'coin' => $proyecto->coin_avr." (".$proyecto->coin.")",
				'status' => $proyecto->status,
				'fotos_asociadas' => $fotos_asociadas,
				'num_fotos' => $num_fotos,
				'num_news' => $num_news,
				'num_docs' => $num_docs,
				'num_readings' => $num_readings,
				'groups_names' => $groups_names,
				'percentage_collected' => $porcentaje,
				'inscription_available' => 'no',
				'availability' => 'current',
				'cost' => 0,
				'date_event' => '',
				'date_inscription' => ''
			);
			
			// Verificamos si la fecha actual encaja con las reglas del proyecto
			foreach($project_rules as $rule){
				
				$cond = $rule->cond;  // Operador condicional de la regla
				$range = $rule->var2;  // Cadena de rangos de fecha de la regla
				$range = explode(";", $range);  // Separación de los rangos de fecha de la regla
				$range_from = $range[0];  // Rango desde
				$range_to = $range[1];  // Rango hasta
				
				// Tomamos la fecha actual
				$current_date = date('Y-m-d H:i:s');
				
				// Si el operador condicional es "between" y la regla es de inscripción
				if($cond == "between" && $rule->segment == "inscription"){
					
					$user_id = $this->session->userdata('logged_in')['id'];  // Id del usuario logueado
					
					// Si la fecha actual está dentro del rango de fechas de la regla de inscripción del proyecto, marcamos el proyecto como disponible
					$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
					if($check_in_range == true && $this->get_contract_user($user_id, $proyecto->id) == 0){
						
						// Marcado del proyecto como disponible si el usuario no está inscrito
						$data_proyecto['inscription_available'] = 'yes';
						
					}
					
					// Definimos la fecha de inscripción del proyecto
					$date_inscription = explode(" ", $range_from);
					$data_proyecto['date_inscription'] = $date_inscription[0];
					
				}
				
				// Si el operador condicional es "between" y la regla es de costo
				if($cond == "between" && $rule->segment == "cost"){
					
					// Si la fecha actual está dentro del rango de fechas de la regla de costo del proyecto, tomamos ese precio como costo del proyecto
					$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
					if($check_in_range == true){
						
						// Marcado del proyecto como disponible
						$data_proyecto['cost'] += $rule->result;
						
					}
					
				}
				
				// Si el operador condicional es "between" y la regla tiene el segmento 'date'
				if($cond == "between" && $rule->segment == "date"){
					
					// Si la fecha actual no está dentro del rango de fechas de la regla de fecha del proyecto, marcamos el proyecto como pasado o próximo
					$check_in_range = $this->MInscription->check_in_range($current_date, $range_from, $range_to);
					if($check_in_range == false){
						
						// Si la fecha de inicio del evento es mayor que la fecha actual...
						if(strtotime($range_from) > strtotime($current_date)) {

							// Marcamos el evento como próximo ('upcoming')
							$data_proyecto['availability'] = 'upcoming';
							// Incrementamos el contador de próximos eventos
							$data['upcoming_events']++;

						}
						if(strtotime($range_to) < strtotime($current_date)){
							
							// Si la fecha de fin del evento es menor que la fecha actual...
							// Marcamos el evento como pasado ('past')
							$data_proyecto['availability'] = 'past';
							// Incrementamos el contador de eventos pasados
							$data['past_events']++;

						}
						
					}else{
						
						// Incrementamos el número eventos en curso
						$data['current_events']++;
					
					}
					
					// Definimos la fecha de inicio del proyecto
					$date_event = explode(" ", $range_from);
					$data_proyecto['date_event'] = $date_event[0];
					
				}
				
			}
			
			// Incluimos sólo los proyecto públicos y activos
			if($proyecto->public > 0 && $proyecto->status > 0){
				
				$listar[] = $data_proyecto;
				
			}
			
		}
		
		// Conversión a objeto
		$listar = json_decode( json_encode( $listar ), false );
		
		//~ print_r($listar);
		
		$data['listar'] = $listar;
		
		$this->load->view('publico/investments', $data);
		$this->load->view('footer');
		
	}
	
	// Método para comprobar el contrato de un usuario
    public function get_contract_user($user_id, $project_id) {
		
		// Consultamos si el usuario está inscrito en el evento indicado
		$query = $this->MInscription->get_contract_user($user_id, $project_id);

        return count($query);
            
    }
	
	public function contacts()
	{
		$this->load->view('base');
		$this->load->view('publico/contacts');
	}
	
	
    // Método para verificar si la base de datos existe
    public function exists_database(){
		
		$this->load->dbutil();
		
		// Obtenemos el nombre de la base de datos desde database.php con $this->db->database
		if ($this->dbutil->database_exists($this->db->database))
		{
			
			return "existe";
			
		}else{
			
			return "no existe";
			
		}
		
	}
	
	// Método que realiza las migraciones correspondientes
	public function migrations(){
		
		// Carga de la librería
		$this->load->library('migration');
		
		// Ejecutamos la migración
		if(!$this->migration->latest()){
			
			echo "error";
			show_error($this->migration->error_string());
			
		}else{
		
			//~ echo "success";
			
			// Precarga de datos necesarios
			
			// Verificamos si existe la tabla de usuarios 'users'
			$exists_users = $this->db->table_exists('users');
			
			if($exists_users){
				
				$usuario = $this->MUser->obtener();
				// Creamos el usuario admin si éste no existe
				if(count($usuario) == 0){
					
					$data_admin = array(
						'username' => 'admin@gmail.com',
						'name' => 'admin',
						'alias' => 'admin',
						'profile_id' => 1,
						'admin' => 1,
						'password' => 'pbkdf2_sha256$12000$a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',
						'status' => 1,
						'coin_id' => 1,
						'lang_id' => 1,
						'user_create_id' => 1,
						'd_create' => date('Y-m-d H:i:s')
						//~ 'd_update' => date('Y-m-d H:i:s')
					);
					
					$insert_admin = $this->MUser->insert($data_admin);
					
				}
				
			}
			
			// Verificamos si existe la tabla de perfiles 'profile'
			$exists_profile = $this->db->table_exists('profile');
			
			if($exists_profile){
			
				$perfil = $this->MPerfil->obtener();
				// Importamos los perfiles básicos si éstos no existen
				if(count($perfil) == 0){
				
					$this->import_profiles();
				
				}
			
			}
			
			// Verificamos si existe la tabla de acciones 'actions'
			$exists_actions = $this->db->table_exists('actions');
			
			if($exists_actions){
			
				$accion = $this->MAcciones->obtener();
				// Creamos la acción HOME si ésta no existe
				if(count($accion) == 0){
				
					// Importamos las acciones básicas
					$this->import_actions();
					
					// Buscamos los perfiles existentes y los asociamos a la acción 1 (HOME)
					$perfiles = $this->MPerfil->obtener();
					
					foreach($perfiles as $perfil){
						
						$data_assoc = array(
							'profile_id' => $perfil->id,
							'action_id' => 1,
							'parameter_permit' => '7777',
							'd_create' => date('Y-m-d H:i:s')
							//~ 'd_update' => date('Y-m-d H:i:s')
						);
						
						$insert_assoc = $this->MPerfil->insert_action($data_assoc);
						
					}
					
					// Asociamos las acciones diferentes a 1 (HOME) al usuario 1 (admin@gmail.com).
					// Primero verificamos si existe la tabla 'permissions'
					$exists_permissions = $this->db->table_exists('permissions');
					
					if($exists_permissions){
						
						// Listamos las acciones
						$acciones = $this->MAcciones->obtener();
						
						if(count($acciones) > 0){
							
							foreach($acciones as $accion){
								
								if($accion->id != 1){
									
									$data_assoc2 = array(
										'user_id' => 1,
										'action_id' => $accion->id,
										'parameter_permit' => '7777',
										'd_create' => date('Y-m-d H:i:s')
										//~ 'd_update' => date('Y-m-d H:i:s')
									);
									
									$insert_assoc2 = $this->MUser->insert_action($data_assoc2);
									
								}
								
							}
							
						}
						
					}
				
				}
			
			}
			
			// Verificamos si existe la tabla de menús 'menus'
			$exists_menus = $this->db->table_exists('menus');
			
			if($exists_menus){
			
				$menu = $this->MMenus->obtener();
				// Creamos los menús básicos si éstos no existen
				if(count($menu) == 0){
					
					// Importamos los menús básicos
					$this->import_menus();
				
				}
			
			}
			
			// Verificamos si existe la tabla de submenús 'submenus'
			$exists_submenus = $this->db->table_exists('submenus');
			
			if($exists_submenus){
			
				$submenu = $this->MSubMenus->obtener();
				// Creamos los submenús básicos si éstos no existen
				if(count($submenu) == 0){
					
					// Importamos los submenús básicos
					$this->import_submenus();
				
				}
			
			}
			
			// Verificamos si existe la tabla de íconos 'icons'
			$exists_icons = $this->db->table_exists('icons');
			
			if($exists_icons){
			
				$icono = $this->MMenus->search_icons();
				// Creamos los íconos básicos si éstos no existen
				if($icono == 0){
					
					// Importamos los íconos básicos
					$this->import_icons();
				
				}
			
			}
			
			// Verificamos si existe la tabla de monedas 'coins'
			$exists_coins = $this->db->table_exists('coins');
			
			if($exists_coins){
			
				$moneda = $this->MCoins->obtener();
				// Creamos las monedas básicas si éstas no existen
				if(count($moneda) == 0){
					
					// Importamos las monedas básicas
					$this->import_coins();
				
				}
			
			}
			
			// Verificamos si existe la tabla de tipos de cuenta 'tipos_cuenta'
			$exists_type = $this->db->table_exists('account_type');
			
			if($exists_type){
			
				$type = $this->MTiposCuenta->obtener();
				// Creamos los tipos de cuenta básicos si éstos no existen
				if(count($type) == 0){
					
					// Importamos los tipos de cuenta básicos
					$this->import_type_accounts();
				
				}
			
			}
			
			// Verificamos si existe la tabla de idiomas 'lang'
			$exists_lang = $this->db->table_exists('lang');
			
			if($exists_lang){
			
				$langs = $this->MWelcome->get_langs();
				// Creamos los idiomas básicos si éstos no existen
				if(count($langs) == 0){
					
					// Importamos los idiomas básicos
					$this->import_langs();
				
				}
			
			}
		
		}
		
	}
	
	// Método que importa las acciones básicas desde un csv
    public function import_actions() {
        
        $ruta = getcwd();  // Obtiene el directorio actual en donde se está trabajando
        
        $fp = fopen ($ruta."/application/migrations/actions.csv","r");
        
        while ($data = fgetcsv ($fp, 1000, ",")) {
			
			$data_accion2 = array(
				'name' => $data[1],
				'class' => $data[2],
				'route' => $data[3],
				'assigned' => $data[4],
				'd_create' => date('Y-m-d H:i:s')
				//~ 'd_update' => date('Y-m-d H:i:s')
			);
			
			$insert_accion = $this->MAcciones->insert($data_accion2);
			
		}
		
		fclose ($fp);
        
    }
    
    // Método que importa los menús básicos desde un csv
    public function import_menus() {
        
        $ruta = getcwd();  // Obtiene el directorio actual en donde se está trabajando
        
        $fp = fopen ($ruta."/application/migrations/menus.csv","r");
        
        while ($data = fgetcsv ($fp, 1000, ",")) {
			
			$data_menu = array(
				'name' => $data[1],
				'description' => $data[2],
				'logo' => $data[3],
				'route' => $data[4],
				'action_id' => $data[5],
				'order' => $data[6],
				'd_create' => date('Y-m-d H:i:s')
				//~ 'd_update' => date('Y-m-d H:i:s')
			);
			
			$insert_menu = $this->MMenus->insert($data_menu);
			
		}
		
		fclose ($fp);
        
    }
    
    // Método que importa los submenús básicos desde un csv
    public function import_submenus() {
        
        $ruta = getcwd();  // Obtiene el directorio actual en donde se está trabajando
        
        $fp = fopen ($ruta."/application/migrations/submenus.csv","r");
        
        while ($data = fgetcsv ($fp, 1000, ",")) {
			
			$data_submenu = array(
				'name' => $data[1],
				'description' => $data[2],
				'logo' => $data[3],
				'route' => $data[4],
				'menu_id' => $data[5],
				'action_id' => $data[6],
				'order' => $data[7],
				'd_create' => date('Y-m-d H:i:s')
				//~ 'd_update' => date('Y-m-d H:i:s')
			);
			
			$insert_submenu = $this->MSubMenus->insert($data_submenu);
			
		}
		
		fclose ($fp);
        
    }
    
    // Método que importa los iconos desde un csv
    public function import_icons() {
        
        $ruta = getcwd();  // Obtiene el directorio actual en donde se está trabajando
        
        $fp = fopen ($ruta."/application/migrations/icons.csv","r");
        
        while ($data = fgetcsv ($fp, 1000, ",")) {
			
			$data_icon = array(
				'class' => $data[1],
				'name' => $data[2],
				'category' => $data[3],
				'd_create' => date('Y-m-d H:i:s')
				//~ 'd_update' => date('Y-m-d H:i:s')
			);
			
			$insert_icon = $this->MMenus->insert_icons($data_icon);
			
		}
		
		fclose ($fp);
        
    }
    
    // Método que importa los perfiles iniciales desde un csv
    public function import_profiles() {
        
        $ruta = getcwd();  // Obtiene el directorio actual en donde se está trabajando
        
        $fp = fopen ($ruta."/application/migrations/profiles.csv","r");
        
        while ($data = fgetcsv ($fp, 1000, ",")) {
			
			$data_perfil = array(
				'name' => $data[1],
				'd_create' => date('Y-m-d H:i:s')
				//~ 'd_update' => date('Y-m-d H:i:s')
			);
			
			$insert_perfil = $this->MPerfil->insert($data_perfil);
			
		}
		
		fclose ($fp);
        
    }
    
    // Método que importa las monedas básicas desde un csv
    public function import_coins() {
        
        $ruta = getcwd();  // Obtiene el directorio actual en donde se está trabajando
        
        $fp = fopen ($ruta."/application/migrations/coins.csv","r");
        
        while ($data = fgetcsv ($fp, 1000, ",")) {
			
			$data_coin = array(
				'description' => $data[1],
				'abbreviation' => $data[2],
				'symbol' => $data[3],
				'status' => $data[4],
				'd_create' => date('Y-m-d H:i:s')
				//~ 'd_update' => date('Y-m-d H:i:s')
			);
			
			$insert_coin = $this->MCoins->insert($data_coin);
			
		}
		
		fclose ($fp);
        
    }
    
    // Método que importa los tipos de cuenta desde un csv
    public function import_type_accounts() {
        
        $ruta = getcwd();  // Obtiene el directorio actual en donde se está trabajando
        
        $fp = fopen ($ruta."/application/migrations/type_accounts.csv","r");
        
        while ($data = fgetcsv ($fp, 1000, ",")) {
			
			$data_type = array(
				'name' => $data[1],
				'd_create' => date('Y-m-d H:i:s')
				//~ 'd_update' => date('Y-m-d H:i:s')
			);
			
			$insert_type = $this->MTiposCuenta->insert($data_type);
			
		}
		
		fclose ($fp);
        
    }
    
    // Método que importa los idiomas desde un csv
    public function import_langs() {
        
        $ruta = getcwd();  // Obtiene el directorio actual en donde se está trabajando
        
        $fp = fopen ($ruta."/application/migrations/langs.csv","r");
        
        while ($data = fgetcsv ($fp, 1000, ",")) {
			
			$data_lang = array(
				'name' => $data[1],
				'route' => '',
				'status' => 1,
				'd_create' => date('Y-m-d H:i:s'),
				'd_update' => date('Y-m-d H:i:s')
			);
			
			$insert_lang = $this->MWelcome->insert_lang($data_lang);
			
		}
		
		fclose ($fp);
        
    }
	
}
