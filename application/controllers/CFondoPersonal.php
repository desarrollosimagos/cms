<?php
/**
 * CFondoPersonal class than extends of CI_Controller
 *
 * An class than search and proccess data of 'transactions'.
 * 
 * Se encarga de realizar las consultas CRUD, instanciando para ello al modelo 'MFondoPersonal' principalmente.
 * 
 * @author	@jsolorzano18 (twitter)
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class CFondoPersonal extends CI_Controller {

/**
 * Initialization class
 *
 * Loads the required models.
 *
 */
	public function __construct() {
        parent::__construct();
       
        $this->load->model('MFondoPersonal');
        $this->load->model('MCuentas');
        $this->load->model('MProjects');
        $this->load->model('MUser');
		
    }

/**
 * ------------------------------------------------------
 * Public method to obtain a transactions list
 * ------------------------------------------------------
 * 
 * Este método muestra un listado de transaccciones limitado o ilimitado
 * dependiendo del perfil del usuario logueado, usando las plantillas base.
 */	
	public function index()
	{
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Transacciones";
		$data['listar'] = $this->MFondoPersonal->obtener();
		
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
		$this->load->view($perfil_folder.'fondo_personal/lista', $data);
		$this->load->view('footer');
	}

/**
 * ------------------------------------------------------
 * Public method to show a form of registry of transactions
 * ------------------------------------------------------
 * 
 * Este método muestra un formulario personalizado para el 
 * registro de transacciones, usando las plantillas base.
 */
	public function register()
	{
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Transacciones";
		$data['accounts'] = $this->MFondoPersonal->obtener_cuentas_group();
		$data['usuarios'] = $this->MUser->obtener();
		$data['projects'] = $this->MFondoPersonal->obtener_proyectos_group();
		
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
		$this->load->view($perfil_folder.'fondo_personal/registrar', $data);
		$this->load->view('footer');
	}

/**
 * ------------------------------------------------------
 * Método público para guardar una transacción proveniente del
 * formulario de registro.
 * ------------------------------------------------------
 * 
 * Este método filtra y valida los datos tanto textuales como de ficheros
 * provenientes del formulario antes de proceder a registralos en base de datos.
 */
    public function add() {
		
		$fecha = $this->input->post('date');
		$fecha = explode(" ", $fecha);
		$fecha = explode("/", $fecha[0]);
		$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		
		$hora = $this->input->post('date');
		$hora = explode(" ", $hora);
		$hora = $hora[1];
		
		$fecha = $fecha." ".$hora;
		
		if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 5){
			$user_id = $this->input->post('user_id');
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$user_id = 0;
		}else{
			$user_id = $this->session->userdata('logged_in')['id'];
		}
		
		$amount = $this->input->post('amount');
		
		$real = $this->input->post('real');
		
		if((string)$real == 'on'){
			$real = 1;
		}else{
			$real = 0;
		}
		
		$datos = array(
            'user_id' => $user_id,
            'user_create_id' => $this->session->userdata('logged_in')['id'],
            'type' => $this->input->post('type'),
            'project_id' => $this->input->post('project_id'),
            'account_id' => $this->input->post('account_id'),
            'date' => $fecha,
            'description' => $this->input->post('description'),
            'reference' => $this->input->post('reference'),
            'observation' => $this->input->post('observation'),
            'real' => $real,
            'rate' => $this->input->post('rate'),
            'amount' => $amount,
            'status' => 'waiting',
            'd_create' => date('Y-m-d H:i:s')
        );
        
        $result = $this->MFondoPersonal->insert($datos);
        
        if ($result) {
			
			// Sección para el registro de la foto en la ruta establecida para tal fin (assets/docs_trans/)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			$i = 0;
			
			$errors2 = 0;
				
			if($_FILES['document']['name'][0] != ""){
				
				// Obtenemos la extensión
				$ext = explode(".", $_FILES['document']['name'][0]);
				$ext = $ext[1];
				$document = "docs_trans_".$result.".".$ext;
				
				if (!move_uploaded_file($_FILES['document']['tmp_name'][0], $ruta."/assets/docs_trans/docs_trans_".$result.".".$ext)) {
					
					$errors2 += 1;
					
				}else{
					
					$data_trans = array(
						'id' => $result,
						'document' => $document,
					);
					$update_user = $this->MFondoPersonal->update($data_trans);
				
				}
				
				$i++;
			}
			
			if($errors2 > 0){
				
				echo '{"response":"error2"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
       
        }else{
			
			echo '{"response":"error"}';
			
		}
    }

/**
 * ------------------------------------------------------
 * Public method to show a form of update of transactions
 * ------------------------------------------------------
 * 
 * Este método muestra un formulario personalizado para la 
 * actualización de transacciones, usando las plantillas base.
 */
    public function edit() {
		
		$this->load->view('base');
		$data['ident'] = "Cuentas";
		$data['ident_sub'] = "Transacciones";
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->MFondoPersonal->obtenerFondoPersonal($data['id']);
        $data['accounts'] = $this->MFondoPersonal->obtener_cuentas_group();
        $data['usuarios'] = $this->MUser->obtener();
        $data['projects'] = $this->MFondoPersonal->obtener_proyectos_group();
        
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
        $this->load->view($perfil_folder.'fondo_personal/editar', $data);
		$this->load->view('footer');
    }

/**
 * ------------------------------------------------------
 * Método público para guardar una transacción editada desde 
 * el formulario de actualización.
 * ------------------------------------------------------
 * 
 * Este método filtra y valida los datos tanto textuales como de ficheros
 * provenientes del formulario antes de proceder a registralos en base de datos.
 */
    public function update() {
		
		$fecha = $this->input->post('date');
		$fecha = explode(" ", $fecha);
		$fecha = explode("/", $fecha[0]);
		$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		
		$hora = $this->input->post('date');
		$hora = explode(" ", $hora);
		$hora = $hora[1];
		
		$fecha = $fecha." ".$hora;
		
		if($this->session->userdata('logged_in')['id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 5){
			$user_id = $this->input->post('user_id');
		}else if($this->session->userdata('logged_in')['id'] == 2){
			$user_id = 0;
		}else{
			$user_id = $this->session->userdata('logged_in')['id'];
		}
		
		$amount = $this->input->post('amount');
		
		$real = $this->input->post('real');
		
		if((string)$real == 'on'){
			$real = 1;
		}else{
			$real = 0;
		}
		
		$datos = array(
			'id' => $this->input->post('id'),
			'user_id' => $user_id,
            'type' => $this->input->post('type'),
            'project_id' => $this->input->post('project_id'),
            'account_id' => $this->input->post('account_id'),
            'date' => $fecha,
            'description' => $this->input->post('description'),
            'reference' => $this->input->post('reference'),
            'observation' => $this->input->post('observation'),
            'real' => $real,
            'rate' => $this->input->post('rate'),
            'amount' => $amount,
            'd_update' => date('Y-m-d H:i:s')
		);
		
        $result = $this->MFondoPersonal->update($datos);
        
        if ($result) {
			
			// Sección para el registro de la foto en la ruta establecida para tal fin (assets/docs_trans/)
			$ruta = getcwd();  // Obtiene el directorio actual en donde se esta trabajando
			
			$i = 0;
			
			$errors2 = 0;
				
			if($_FILES['document']['name'][0] != ""){
				
				// Obtenemos la extensión
				$ext = explode(".", $_FILES['document']['name'][0]);
				$ext = $ext[1];
				$document = "docs_trans_".$this->input->post('id').".".$ext;
				
				if (!move_uploaded_file($_FILES['document']['tmp_name'][0], $ruta."/assets/docs_trans/docs_trans_".$this->input->post('id').".".$ext)) {
					
					$errors2 += 1;
					
				}else{
					
					$data_trans = array(
						'id' => $this->input->post('id'),
						'document' => $document,
					);
					$update_user = $this->MFondoPersonal->update($data_trans);
				
				}
				
				$i++;
			}
			
			if($errors2 > 0){
				
				echo '{"response":"error2"}';
				
			}else{
				
				echo '{"response":"ok"}';
				
			}
			
        }else{
			
			echo '{"response":"error"}';
			
		}
    }
   
/**
 * ------------------------------------------------------
 * Public method to drop a selcted transaction
 * ------------------------------------------------------
 * 
 * Este método borra una transacción tomando en cuenta el id
 * seleccionado y no sin antes mostrar un mensaje de confirmación.
 */
	function delete($id) {
		
        //~ $result = $this->MFondoPersonal->delete($id);
        // Armamos los nuevos datos de la transacción
		$data_transaccion = array(
			'id' => $id,
			'status' => 'denied',
			'd_update' => date('Y-m-d H:i:s')
		);
			
		// Actualizamos la transacción
		$update_transaccion = $this->MFondoPersonal->update($data_transaccion);
        
        if ($update_transaccion) {
			
			echo '{"response":"ok"}';
			
        }else{
			
			echo '{"response":"error"}';
			
		}
        
    }

/**
 * ------------------------------------------------------
 * Método para validar las transacciones
 * ------------------------------------------------------
 * 
 * Este método permite cambiar el status de una transacción,
 * pudiendo colocarla como validada o negada. Si la transacción
 * es validada, se actualizan los fondos de la cuenta utilizada 
 * con el monto de la transacción.
 */ 
    public function validar_transaccion(){
		
		// Armamos los nuevos datos de la transacción
		$data_transaccion = array(
			'id' => $this->input->post('id'),
			'status' => $this->input->post('status'),
			'd_update' => date('Y-m-d H:i:s')
		);
			
		// Actualizamos la transacción
		$update_transaccion = $this->MFondoPersonal->update($data_transaccion);
		
		// Si estamos validando y no negando la transacción, actualizamos también la account de dicha transacción
		if($this->input->post('status') == 'approved'){
			
			// Obtenemos los datos de la account a actualizar
			$data_account = $this->MCuentas->obtenerCuenta($this->input->post('account_id'));
			
			// Sumamos el monto de la transacción a la cuenta
			$amount_account = $data_account[0]->amount + $this->input->post('amount');
			
			// Armamos los nuevos datos de la cuenta
			$data_account = array(
				'id' => $this->input->post('account_id'),
				'amount' => $amount_account,
				'd_update' => date('Y-m-d H:i:s')
			);
			
			// Actualizamos la cuenta
			$update_account = $this->MCuentas->update($data_account);
			
		}else{
			$update_account = true;
		}
		
		if($update_transaccion && $update_account){
			echo '{"response":"ok"}';
		}else{
			echo '{"response":"error"}';
		}
		
	}

/**
 * ------------------------------------------------------
 * Método que retorna un json con todas las transacciones
 * ------------------------------------------------------
 */	
	public function ajax_fondo_personal()
    {
        $result = $this->MFondoPersonal->obtener();
        echo json_encode($result);
    }
    
/**
 * ------------------------------------------------------
 * Método alternativo para cargar los datos de la tabla de 
 * transacciones usando ajax.
 * ------------------------------------------------------
 * 
 * Este método permite construir un listado de transacciones adaptado 
 * a la solicitud realizada con ajax desde la vista por el plugin datatable.
 */
    public function ajax_transactions()
	{
		
		$fetch_data = $this->MFondoPersonal->make_datatables();
		$data = array();
		$i = 1;
		foreach($fetch_data as $row){
			$sub_array = array();
			
			$usuario; $status; $real; $edit; $delete; $validate;
			// Validamos los datos corresponientes a las columnas que lo ameriten
			// Validación de nombre de usuario
			if($row->usuario == ''){ $usuario = "PLATAFORMA"; }else{ $usuario = $row->usuario; }
			// Validación de status
			if($row->status == 'approved'){ 
				$status = "<span style='color:#337AB7;'>".$this->lang->line('transactions_status_approved')."</span>"; 
			}else if($row->status == 'waiting'){ 
				$status = "<span style='color:#A5D353;'>".$this->lang->line('transactions_status_waiting')."</span>"; 
			}else{
				$status = "<span style='color:#D33333;'>".$this->lang->line('transactions_status_denied')."</span>";
			}
			// Validación de campo real
			if($row->real == 1){ $real = "Sí"; }else{ $real = "No"; }
			// Validación de botón de edición
			if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 5){
				$edit = "<a class='a-actions' href='".base_url()."transactions/edit/".$row->id."' title='".$this->lang->line('list_edit')."'><i class='fa fa-edit fa-2x a-actions'></i></a>";
			}else{
				$edit = "<a class='a-actions'><i class='fa fa-ban fa-2x a-actions' style='color:#D33333;'></i></a>";
			}
			// Validación de botón de borrado
			if($this->session->userdata('logged_in')['profile_id'] == 1){
				if($row->status != 'denied'){
					$delete = "<a class='borrar' id='".$row->id."' title='".$this->lang->line('list_delete')."'><i class='fa fa-toggle-off fa-2x a-actions'></i></a>";
				}else{
					$delete = "<a><i class='fa fa-ban fa-2x a-actions' style='color:#D33333;'></i></a>";
				}
			}else{
				$delete = "<a><i class='fa fa-ban fa-2x a-actions' style='color:#D33333;'></i></a>";
			}
			// Validación de botón de validar
			$class = "";
			$class_icon_validar = "";
			$disabled = "";
			$cursor_style = "";
			$color_style = "";
			$title = "";
			if($row->status == 'approved'){
				$class_icon_validar = "fa-check-circle";
				$disabled = "disabled='true'";
				$cursor_style = "cursor:default";
				$color_style = "";
				$title = "";
			}else if($row->status == 'waiting'){
				$class = "validar";
				$class_icon_validar = "fa-check-circle-o";
				$cursor_style = "cursor:pointer";
				$color_style = "";
				$title = "title='".$this->lang->line('list_validate')."'";
			}else{
				$class_icon_validar = "fa-check-circle";
				$disabled = "disabled='true'";
				$cursor_style = "cursor:default";
				$color_style = "color:grey";
				$title = "";
			}
			$validate = "<a class='".$class." a-actions' id='".$row->id.';'.$row->account_id.';'.$row->amount.';'.$row->type.';'.$row->coin_avr."' ".$disabled." style='".$cursor_style.";".$color_style."' ".$title.">";
				$validate .= "<i class='fa ".$class_icon_validar." fa-2x'></i>";
			$validate .= "</a>";
			// Mostramos los datos ya filtrados. Tomando en cuenta el perfil del usuario, se eliminan algunas columnas
			$sub_array[] = $row->id;
			if($this->session->userdata('logged_in')['profile_id'] != 3){
				$sub_array[] = $usuario;
			}
			$sub_array[] = $this->lang->line('transactions_type_'.$row->type);
			$sub_array[] = $row->description;
			$sub_array[] = number_format($row->amount, $row->coin_decimals, '.', '')."  ".$row->coin_symbol;
			$sub_array[] = $status;
			$sub_array[] = $row->alias." - ".$row->number;
			$sub_array[] = $row->reference;
			$sub_array[] = $row->observation;
			$sub_array[] = $real;
			$sub_array[] = $row->rate;
			$sub_array[] = "<a target='_blank' href='".base_url()."assets/docs_trans/".$row->document."'>".$row->document."</a>";
			$sub_array[] = $edit." ".$delete." ".$validate;
			
			$data[] = $sub_array;
			
			$i++;
		}
		
		$output = array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $this->MFondoPersonal->get_all_data(),
			"recordsFiltered" => $this->MFondoPersonal->get_filtered_data(),
			"data" => $data
		);
		
		echo json_encode($output);
	}
	
	
}
