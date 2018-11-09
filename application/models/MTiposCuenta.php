<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MTiposCuenta extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the account_type
    public function obtener() {
		
		$query = $this->db->get('account_type');

		if ($query->num_rows() > 0)
			return $query->result();
		else
			return $query->result();
            
    }

    // Public method to insert the data
    public function insert($datos) {
		
		$result = $this->db->insert("account_type", $datos);
		$id = $this->db->insert_id();
		return $id;
        
    }

    // Public method to obtain the account_type by id
    public function obtenerTipoCuenta($id) {
		
        $this->db->where('id', $id);
        $query = $this->db->get('account_type');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to update a record  
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('account_type', $datos);
		return $result;
        
    }


    // Public method to delete a record
     public function delete($id) {
		 
		$result = $this->db->delete('account_type', array('id' => $id));
		return $result;
       
    }
    

}
?>
