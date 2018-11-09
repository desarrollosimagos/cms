<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CCuentas extends CI_Controller {

	public function __construct() {
        parent::__construct();

		// Load database
        $this->load->model('MCuentas');
        $this->load->model('MCoins');
        $this->load->model('MTiposCuenta');
        $this->load->model('MBitacora');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Cuentas";
		
		$listar = array();
		
		$cuentas = $this->MCuentas->obtener();
		
		foreach($cuentas as $cuenta){
						
			// Proceso de búsqueda de grupos de inversores asociados a la cuenta
			$groups = $this->MCuentas->buscar_grupos($cuenta->id);
			$groups_names = "";
			foreach($groups as $group){
				$groups_names .= $group->name.",";
			}
			$groups_names = substr($groups_names, 0, -1);
			
			// Proceso de búsqueda de transacciones asociadas a la cuenta para calcular los montos totales y parciales
			// Suma general de la tabla 'transactions'
			$sum_transacctions = $this->MCuentas->sumar_transacciones($cuenta->id, 'transactions t');
			//~ $sum_transacctions_project = $this->MCuentas->sumar_transacciones($cuenta->id, 'project_transactions t');
			// Suma condicionada de la tabla 'transactions'
			$find_transactions = $this->MCuentas->buscar_transacciones($cuenta->id, 'transactions t');
			//~ $find_transactions_project = $this->MCuentas->buscar_transacciones($cuenta->id, 'project_transactions t');
			$capital_disponible_total = 0;
			$capital_disponible_parcial = 0;
			if(count($find_transactions) > 0){
				foreach($find_transactions as $t1){
					if($t1->status == 'approved'){
						// Si la moneda de la cuenta es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if($cuenta->coin_avr == 'VEF' && strtotime($t1->date) < strtotime("2018-10-20 00:00:00")){
							$capital_disponible_total += ($t1->amount/100000);
						}else{
							$capital_disponible_total += $t1->amount;
						}
					}
					$relations = $this->MCuentas->buscar_transaction_relation($t1->id);
					if($t1->type != "invest" && $t1->type != "sell" && $t1->status == 'approved'){
						if(count($relations) == 0){
							$capital_disponible_parcial += $t1->amount;
						}
						if(count($relations) > 0 && $relations[0]->type != "distribute"){
							$capital_disponible_parcial += $t1->amount;
						}
					}
				}
			}
			//~ if(count($find_transactions_project) > 0){
				//~ foreach($find_transactions_project as $t2){
					//~ if($t2->status == 'approved'){ $capital_disponible_total += $t2->monto; }
					//~ $relations = $this->MCuentas->buscar_project_transaction_relation($t2->id);
					//~ if(count($relations) == 0){
						//~ if($t2->type == "profit" || $t2->type == "expense"){
							//~ $capital_disponible_parcial += $t2->monto;
						//~ }
					//~ }
				//~ }
			//~ }
			
			$data_cuenta = array(
				'id' => $cuenta->id,
				'owner' => $cuenta->owner,
				'alias' => $cuenta->alias,
				'number' => $cuenta->number,
				'usuario' => $cuenta->usuario,
				'type' => $cuenta->type,
				'description' => $cuenta->description,
				'amount' => $cuenta->amount,
				'capital_disponible_total' => $capital_disponible_total,
				'capital_disponible_parcial' => $capital_disponible_parcial,
				'status' => $cuenta->status,
				'coin' => $cuenta->coin,
				'coin_avr' => $cuenta->coin_avr,
				'coin_symbol' => $cuenta->coin_symbol,
				'coin_decimals' => $cuenta->coin_decimals,
				'tipo_cuenta' => $cuenta->tipo_cuenta,
				'd_create' => $cuenta->d_create,
				'groups_names' => $groups_names
			);
			
			$listar[] = $data_cuenta;
			
		}
		
		// Conversión a objeto
		$listar = json_decode( json_encode( $listar ), false );
		
		$data['listar'] = $listar;
		
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
		$this->load->view($perfil_folder.'cuentas/lista', $data);
		$this->load->view('footer');
	}
	
	// Método para buscar cuentas por nombre o fecha
	public function seeker(){
		
		$buscar = $this->input->post('search');
		
		$listar = array();
		
		$cuentas = $this->MCuentas->obtener_filtrado($buscar);
		
		foreach($cuentas as $cuenta){
						
			// Proceso de búsqueda de grupos de inversores asociados a la cuenta
			$groups = $this->MCuentas->buscar_grupos($cuenta->id);
			$groups_names = "";
			foreach($groups as $group){
				$groups_names .= $group->name.",";
			}
			$groups_names = substr($groups_names, 0, -1);
			
			// Proceso de búsqueda de transacciones asociadas a la cuenta para calcular los montos totales y parciales
			// Suma general de la tabla 'transactions'
			$sum_transacctions = $this->MCuentas->sumar_transacciones($cuenta->id, 'transactions t');
			//~ $sum_transacctions_project = $this->MCuentas->sumar_transacciones($cuenta->id, 'project_transactions t');
			// Suma condicionada de la tabla 'transactions'
			$find_transactions = $this->MCuentas->buscar_transacciones($cuenta->id, 'transactions t');
			//~ $find_transactions_project = $this->MCuentas->buscar_transacciones($cuenta->id, 'project_transactions t');
			$capital_disponible_total = 0;
			$capital_disponible_parcial = 0;
			if(count($find_transactions) > 0){
				foreach($find_transactions as $t1){
					if($t1->status == 'approved'){
						// Si la moneda de la cuenta es el bolívar y la transacción es anterior al 20-08-2018, se hace una reconversión
						if($cuenta->coin_avr == 'VEF' && strtotime($t1->date) < strtotime("2018-10-20 00:00:00")){
							$capital_disponible_total += ($t1->amount/100000);
						}else{
							$capital_disponible_total += $t1->amount;
						}
					}
					$relations = $this->MCuentas->buscar_transaction_relation($t1->id);
					if($t1->type != "invest" && $t1->type != "sell" && $t1->status == 'approved'){
						if(count($relations) == 0){
							$capital_disponible_parcial += $t1->amount;
						}
						if(count($relations) > 0 && $relations[0]->type != "distribute"){
							$capital_disponible_parcial += $t1->amount;
						}
					}
				}
			}
			
			$data_cuenta = array(
				'id' => $cuenta->id,
				'owner' => $cuenta->owner,
				'alias' => $cuenta->alias,
				'number' => $cuenta->number,
				'usuario' => $cuenta->usuario,
				'type' => $cuenta->type,
				'description' => $cuenta->description,
				'amount' => $cuenta->amount,
				'capital_disponible_total' => $capital_disponible_total,
				'capital_disponible_parcial' => $capital_disponible_parcial,
				'status' => $cuenta->status,
				'coin' => $cuenta->coin,
				'coin_avr' => $cuenta->coin_avr,
				'coin_symbol' => $cuenta->coin_symbol,
				'coin_decimals' => $cuenta->coin_decimals,
				'tipo_cuenta' => $cuenta->tipo_cuenta,
				'd_create' => $cuenta->d_create,
				'groups_names' => $groups_names
			);
			
			$listar[] = $data_cuenta;
			
		}
		
		// Conversión a objeto
		$listar = json_decode( json_encode( $listar ), false );
		
		$data['listar'] = $listar;
		
		foreach($data['listar'] as $fondo){
		?>
		<tr class="scroll">
			<td class="project-status">
				<?php if($fondo->status == 1) { ?>
				<span class="label label-primary"><?php echo $this->lang->line('list_status1_accounts'); ?></span>
				<?php }else{ ?>
				<span class="label label-default"><?php echo $this->lang->line('list_status2_accounts'); ?></span>
				<?php } ?>
			</td>
			<td class="project-title">
				<a href="<?php echo base_url() ?>accounts/view/<?= $fondo->id; ?>"><?php echo $fondo->alias; ?></a>
				<br/>
				<small>Created <?php echo $fondo->d_create; ?></small>
				<br>
				<?php if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2) { ?>
				<small><?php echo $fondo->groups_names; ?></small>
				<?php } ?>
			</td>
			<td class="project-completion">
				<small>
					<!--Completion with:-->
					<?php echo $this->lang->line('list_capital_available_accounts'); ?>: <?php echo $fondo->capital_disponible_total; ?>
				</small>
			</td>
			<td class="project-title">
				<?php echo $fondo->coin; ?>
			</td>
			<td class="project-actions">
				<a href="<?php echo base_url() ?>accounts/view/<?= $fondo->id; ?>" title="<?php echo $this->lang->line('list_view_accounts'); ?>" class="btn btn-white btn-sm"><i class="fa fa-folder"></i> <?php echo $this->lang->line('list_view_accounts'); ?> </a>
				<a href="<?php echo base_url() ?>accounts/edit/<?= $fondo->id; ?>" title="<?php echo $this->lang->line('list_edit_accounts'); ?>" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('list_edit_accounts'); ?> </a>
				<a id='<?php echo $fondo->id; ?>' title='<?php echo $this->lang->line('list_delete_accounts'); ?>' class="btn btn-danger btn-sm borrar"><i class="fa fa-trash"></i> <?php echo $this->lang->line('list_delete_accounts'); ?> </a>
			</td>
		</tr>
		<?php
		}
	}
	
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Cuentas";
		$data['account_type'] = $this->MTiposCuenta->obtener();
		$data['monedas'] = $this->MCoins->obtener();
		
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
		$this->load->view($perfil_folder.'cuentas/registrar', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		
		$datos = array(
			'owner' => $this->input->post('owner'),
			'alias' => $this->input->post('alias'),
			'number' => $this->input->post('number'),
            'user_id' => $this->session->userdata('logged_in')['id'],
            'type' => $this->input->post('type'),
            'description' => $this->input->post('description'),
            'amount' => $this->input->post('amount'),
            'coin_id' => $this->input->post('coin_id'),
            'status' => $this->input->post('status'),
            'd_create' => date('Y-m-d H:i:s')
        );
        
        $result = $this->MCuentas->insert($datos);
        
        if ($result) {
			
			// Guardamos el registro en la bitácora
			
			$ipvisitante = $_SERVER["REMOTE_ADDR"];
			
			$detail[0] = array(
				'model' => 'accounts',
				'controller' => $this->router->class,
				'method' => $this->router->method,
				'data' => $datos,
			);
			
			//~ $detail = json_decode( json_encode( $detail ), true );
			$detail = json_encode( $detail );
			
			//~ print_r($detail);
			
			$bitacora = array(
				'date' => date('Y-m-d H:i:s'),
				'ip' => $ipvisitante,
				'user_id' => $this->session->userdata('logged_in')['id'],
				'detail' => $detail
			);
			
			//~ print_r($bitacora);
			
			$insert_bitacora = $this->MBitacora->insert($bitacora);

			echo '{"response":"ok"}';
       
        }else{
			
			echo '{"response":"error"}';
			
		}
    }
    
    // Método para ver detalles
    public function view() {
		
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Cuentas";
        $data['id'] = $this->uri->segment(3);
        $data['ver'] = $this->MCuentas->obtenerCuenta($data['id']);
        // Proceso de búsqueda de transacciones asociadas a la cuenta para calcular los montos totales y parciales
		// Suma general de las tablas 'transactions' y 'project_transactions'
		$find_transactions = $this->MCuentas->buscar_transacciones($data['id'], 'transactions t');
		//~ $find_transactions_project = $this->MCuentas->buscar_transacciones($data['id'], 'project_transactions t');
		$data['find_transactions'] = $find_transactions;
		//~ $data['find_transactions_project'] = $find_transactions_project;
		
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
        $this->load->view($perfil_folder.'cuentas/ver', $data);
		$this->load->view('footer');
    }
	
	// Método para editar
    public function edit() {
		
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Cuentas";
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->MCuentas->obtenerCuenta($data['id']);
        $data['account_type'] = $this->MTiposCuenta->obtener();
        $data['monedas'] = $this->MCoins->obtener();
        
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
        $this->load->view($perfil_folder.'cuentas/editar', $data);
		$this->load->view('footer');
    }
	
	// Método para actualizar
    public function update() {
		
		$datos = array(
			'id' => $this->input->post('id'),
			'owner' => $this->input->post('owner'),
			'alias' => $this->input->post('alias'),
			'number' => $this->input->post('number'),
			'user_id' => $this->session->userdata('logged_in')['id'],
            'type' => $this->input->post('type'),
            'description' => $this->input->post('description'),
            'amount' => $this->input->post('amount'),
            'coin_id' => $this->input->post('coin_id'),
            'status' => $this->input->post('status'),
            'd_update' => date('Y-m-d H:i:s')
		);
		
        $result = $this->MCuentas->update($datos);
        
        if ($result) {
			
			// Guardamos la actualización en la bitácora
			
			$ipvisitante = $_SERVER["REMOTE_ADDR"];
			
			$detail[0] = array(
				'model' => 'accounts',
				'controller' => $this->router->class,
				'method' => $this->router->method,
				'data' => $datos,
			);
			
			//~ $detail = json_decode( json_encode( $detail ), true );
			$detail = json_encode( $detail );
			
			//~ print_r($detail);
			
			$bitacora = array(
				'date' => date('Y-m-d H:i:s'),
				'ip' => $ipvisitante,
				'user_id' => $this->session->userdata('logged_in')['id'],
				'detail' => $detail
			);
			
			//~ print_r($bitacora);
			
			$insert_bitacora = $this->MBitacora->insert($bitacora);
			
			echo '{"response":"ok"}';
			
        }else{
			
			echo '{"response":"error"}';
			
		}
    }
    
	// Método para eliminar
	function delete($id) {
		
		// Primero verificamos si está asociada a alguna transacción
		$search_assoc = $this->MCuentas->obtenerCuentaFondos($id);
		
		// Luego verificamos si está asociada a algún grupo de inversionistas
		$search_assoc2 = $this->MCuentas->obtenerCuentaGrupos($id);
		
		if(count($search_assoc) > 0){
			
			echo '{"response":"existe"}';
			
		}else if(count($search_assoc2) > 0){
			
			echo '{"response":"existe2"}';
			
		}else{
			
			$result = $this->MCuentas->delete($id);
			
			if($result){
				
				// Guardamos la actualización en la bitácora
			
			$ipvisitante = $_SERVER["REMOTE_ADDR"];
			
			$detail[0] = array(
				'model' => 'accounts',
				'controller' => $this->router->class,
				'method' => $this->router->method,
				'data' => array("id" => $id, "user_id" => $this->session->userdata('logged_in')['id']),
			);
			
			//~ $detail = json_decode( json_encode( $detail ), true );
			$detail = json_encode( $detail );
			
			//~ print_r($detail);
			
			$bitacora = array(
				'date' => date('Y-m-d H:i:s'),
				'ip' => $ipvisitante,
				'user_id' => $this->session->userdata('logged_in')['id'],
				'detail' => $detail
			);
			
			//~ print_r($bitacora);
			
			$insert_bitacora = $this->MBitacora->insert($bitacora);
				
				echo '{"response":"ok"}';
				
			}else{
				
				echo '{"response":"error"}';
				
			}
			
		}
        
    }
	
	public function ajax_cuentas()
    {
        $result = $this->MCuentas->obtener();
        echo json_encode($result);
    }	
	
}
