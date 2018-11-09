<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CTiendas extends CI_Controller {

	public function __construct() {
        parent::__construct();
       
		// Load database
        $this->load->model('MTiendas');
        $this->load->model('MMails');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['listar'] = $this->MTiendas->obtenerByUser();
		$data['listar_usuarios'] = $this->MTiendas->obtener_usuarios();
		$this->load->view('tiendas/lista', $data);
		$this->load->view('footer');
	}
	
	public function register()
	{
		$data['listar_tiendasv'] = $this->MTiendas->obtener_tiendasv();
		$data['listar_usuarios'] = $this->MTiendas->obtener_usuarios();
		$this->load->view('base');
		$this->load->view('tiendas/registrar', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add() {
		$datos = array(
            'rif' => $_POST['rif'],
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'referencia' => $_POST['referencia'],
            'bd_externa' => $_POST['bd_externa'],
            'cp_externo' => $_POST['cp_externo'],
            'phone' => $_POST['phone'],
            'status' => 1
        );
        
        $result = $this->MTiendas->insert($datos);
        
        echo $result;  // No comentar, esta impresión es necesaria para que se ejecute el método insert()
    }
    
    // Método para asociar tiendas virtuales a una tienda
    public function associate_tiendasv() {
		$id_tienda = $this->input->post('id_tienda');
		$tiendasv = $this->input->post('tiendasv');
				
		// Si el arreglo trae registros se procede a hacer los registros correspondientes
		if(count($tiendasv) > 0){
			foreach ($tiendasv as $tiendav) {
				$datos2 = array(
					'tiendav_id' => $tiendav['id_tiendav'],
					'tienda_id' => $id_tienda,
					'd_create' => date('Y-m-d')." ".date("H:i:s")
				);

				$insert = $this->MTiendas->insertTiendasv($datos2);
			}
		}
	}
    
    // Método para desasociar tiendas virtuales de una tienda
    public function unassociate_tiendasv() {
		$tienda_id = $this->input->post('id_tienda');
		$tiendasv_ids = $this->input->post('codigos_des2');
		$tiendasv_ids = explode(',',$tiendasv_ids);
        foreach ($tiendasv_ids as $tiendavt_id) {
			// Borramos la asociación tienda-tiendav
			$delete = $this->MTiendas->delete_tienda_tiendav($tiendavt_id);
		}
	}
    
    // Método para asociar usuarios a una tienda
    public function associate_users() {
		$id_tienda = $this->input->post('id_tienda');
		$usuarios = $this->input->post('usuarios');
		
		// Datos de la tienda para el envío del correo de invitación
		$data_tienda = $this->MTiendas->obtenerTiendas($id_tienda);
		
		// Consultamos si hay registros de asociación de usuarios con la tienda actual
		$num_assoc = $this->MTiendas->obtenerUsuarios($id_tienda);
		// Si no hay registros de asociación a la tienda, entonces registramos la asociación con el usuario actual como administrador
		if(count($num_assoc) == 0){
			$datos1 = array(
				'user_id' => $this->session->userdata('logged_in')['id'],
				'tienda_id' => $id_tienda,
				'tipo' => 1,
				'status' => 1,
				'd_create' => date('Y-m-d')." ".date("H:i:s")
			);

			$insert = $this->MTiendas->insertUsuarios($datos1);
		}
		
		// Si el arreglo trae registros se procede a hacer los registros correspondientes
		if(count($usuarios) > 0){
			foreach ($usuarios as $usuario) {
				// Si es un usuario registrado en sistema se procede a asociarlo a la tienda 
				if($usuario['id_usuario'] != 0){
					// Primero verificamos si el usuario está activo en la tienda
					$data_usuario = $this->MTiendas->obtenerTiendasUsuario2($usuario['id_usuario'], $id_tienda);
					// Si el usuario no está asociado se lo asocia con status 0 y se le envía la invitación
					if(count($data_usuario) == 0){
						$datos2 = array(
							'user_id' => $usuario['id_usuario'],
							'tienda_id' => $id_tienda,
							'tipo' => $usuario['tipo'],
							'status' => 0,
							'd_create' => date('Y-m-d')." ".date("H:i:s")
						);

						$insert = $this->MTiendas->insertUsuarios($datos2);
						
						$this->MMails->enviarMail($usuario['id_usuario'], $usuario['username'], $id_tienda, $data_tienda[0]->name);  // Envío de la invitación por correo
					}else{
						// Si es usuario está asociado pero en status 0 se le reenvia la invitación
						if($data_usuario[0]->status == 0){
							$this->MMails->enviarMail($usuario['id_usuario'], $usuario['username'], $id_tienda, $data_tienda[0]->name);  // Envío de la invitación por correo
						}
					}					
				}else{
					$this->MMails->enviarMail($usuario['id_usuario'], $usuario['username'], $id_tienda, $data_tienda[0]->name);  // Envío de la invitación por correo
				}
			}
		}
	}
    
    // Método para desasociar usuarios de una tienda
    public function unassociate_users() {
		$tienda_id = $this->input->post('id_tienda');
		$usuarios_ids = $this->input->post('codigos_des1');
		$usuarios_ids = explode(',',$usuarios_ids);
        foreach ($usuarios_ids as $usert_id) {
			// Borramos la asociación tienda-usuario
			$delete = $this->MTiendas->delete_tienda_usuario($usert_id);
		}
	}
	
	// Método para editar
    public function edit() {
		
		$this->load->view('base');
        $data['id'] = $this->uri->segment(3);
        $data['editar'] = $this->MTiendas->obtenerTiendas($data['id']);
        $data['listar_tiendasv'] = $this->MTiendas->obtener_tiendasv();
        $data['listar_usuarios'] = $this->MTiendas->obtener_usuarios();
        //~ $data['tiendasv_asociadas'] = $this->MTiendas->obtenerTiendasv($data['id']);
        $data['usuarios_asociados'] = $this->MTiendas->obtenerUsuarios($data['id']);
        $this->load->view('tiendas/editar', $data);
		$this->load->view('footer');
    }
	
	// Método para actualizar
    public function update() {
		$datos = array(
			'id' => $_POST['id'],
            'rif' => $_POST['rif'],
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'referencia' => $_POST['referencia'],
            'bd_externa' => $_POST['bd_externa'],
            'cp_externo' => $_POST['cp_externo'],
            'phone' => $_POST['phone'],
            'status' => 1,
            'd_update' => date('Y-m-d')." ".date("H:i:s")
        );
        
        $result = $this->MTiendas->update($datos);
    }
    
	// Método para eliminar
	function delete($id) {
		
        $result = $this->MTiendas->delete($id);
        if ($result) {
          /*  $this->libreria->generateActivity('Eliminado País', $this->session->userdata['logged_in']['id']);*/
        }
    }
	
	// Método para enviar invitación por correo
	public function invitar()
    {
		$id_user = $this->input->post('id');
		$username = $this->input->post('usuario');
        $this->MMails->enviarMail($id_user, $username);
    }
    
    // Método para validar el correo de un usuario y proceder a activarlo
    public function validar_mail() {
		$id_user = $this->input->get('id');
		$id_tienda = $this->input->get('id_t');
		if($id_user != 0){
			// Datos de la tienda para el envío del correo de confirmación
			$data_tienda = $this->MTiendas->obtenerTiendas($id_tienda);
			
			$datos_update = array('user_id'=>$id_user, 'tienda_id'=>$id_tienda, 'status'=>1);
			// Activamos el usuario
			$result = $this->MTiendas->update_status($datos_update);
			
			if($result){
				// Armamos los datos del usuario
				$datos_reg = array(
					'username'=>$this->session->userdata('logged_in')['username'],
					'tiendaname'=>$data_tienda[0]->name
				);
				// Enviamos los datos registrados al correo del cliente y lo redireccionamos al inicio de sesión
				$this->MMails->enviarMailConfirm($datos_reg);
				redirect(base_url());
			}
		}else{
			redirect(base_url());
		}
    }
	
	public function ajax_service()
    {                                          #Campo         #Tabla                #ID
        $result = $this->MTiendas->obtener();
        echo json_encode($result);
    }
	
	
}
