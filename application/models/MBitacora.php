<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MBitacora extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the binnacle
    public function obtener() {
		
		$select = 'b.id, b.date, b.ip, b.user_id, b.detail, u.username, u.name, u.alias';
		
		$this->db->select($select);
		$this->db->from('binnacle b');
		$this->db->join('users u', 'u.id = b.user_id');
		// Si el usuario corresponde al de un administrador quitamos el filtro de usuario
        if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			$this->db->where('b.user_id =', $this->session->userdata('logged_in')['id']);
		}
		$this->db->order_by("b.id", "desc");
		$query = $this->db->get();
		//~ $query = $this->db->get('binnacle');

		if ($query->num_rows() > 0)
			return $query->result();
		else
			return $query->result();
            
    }

    // Public method to insert the data
    public function insert($datos) {
		
		$result = $this->db->insert("binnacle", $datos);
		$id = $this->db->insert_id();
		return $id;
        
    }

    // Public method to obtain the binnacle by id
    public function obtenerBitacora($id) {
		
        $this->db->where('id', $id);
        $query = $this->db->get('binnacle');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to obtain the binnacle by id
    public function obtenerBitacoraUser($user_id) {
		
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('binnacle');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to obtain the binnacle by id
    public function obtenerBitacoraIp($ip) {
		
        $this->db->where('ip', $ip);
        $query = $this->db->get('binnacle');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }    

    // Public method to update a record  
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('binnacle', $datos);
		return $result;
        
    }

    // Public method to delete a record
    public function delete($id) {
		 
		$result = $this->db->delete('binnacle', array('id' => $id));
		return $result;
       
    }
    

}
?>
