<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CBitacora extends CI_Controller {

	public function __construct() {
        parent::__construct();
       
		// Load database
        $this->load->model('MBitacora');
        $this->load->model('MUser');
		
    }
	
	public function index()
	{
		$this->load->view('base');
		$data['listar'] = $this->MBitacora->obtener();
		$this->load->view('bitacora/lista', $data);
		$this->load->view('footer');
	}
	
	// Método para guardar un nuevo registro
    public function add($detail) {
		
		$ipvisitante = $_SERVER["REMOTE_ADDR"];
		
		$datos = array(
            'date' => date('Y-m-d H:i:s'),
			'ip' => $ipvisitante,
            'user_id' => $this->session->userdata('logged_in')['id'],
            'detail' => $detail,
        );
        
        $result = $this->MBitacora->insert($datos);
        
        if ($result) {

			echo '{"response":"ok"}';
       
        }else{
			
			echo '{"response":"error"}';
			
		}
    }
	
	// Método para actualizar
    public function update($detail) {
		
		$ipvisitante = $_SERVER["REMOTE_ADDR"];
		
		$datos = array(
            'id' => $this->input->post('id'),
            'date' => date('Y-m-d H:i:s'),
			'ip' => $ipvisitante,
            'user_id' => $this->session->userdata('logged_in')['id'],
            'detail' => $detail,
        );
		
        $result = $this->MBitacora->update($datos);
        
        if ($result) {
			
			echo '{"response":"ok"}';
			
        }else{
			
			echo '{"response":"error"}';
			
		}
    }
    
	// Método para eliminar
	function delete($id) {
			
		$result = $this->MBitacora->delete($id);
		
		if($result){
			
			echo '{"response":"ok"}';
			
		}else{
			
			echo '{"response":"error"}';
			
		}
        
    }
	
	public function ajax_bitacora()
    {
        $result = $this->MBitacora->obtener();
        echo json_encode($result);
    }
	
	
}
