<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MCoinRate extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the coin rates
    public function obtener() {
		
		$this->db->order_by("id", "desc");
		$this->db->limit(1);
        $query = $this->db->get('coin_rate');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to insert the data
    public function insert($datos) {
		
		$result = $this->db->insert("coin_rate", $datos);
		$id = $this->db->insert_id();
		return $id;
        
    }

    // Public method to obtain the coin_rate by id
    public function obtenerTasa($id) {
		
        $this->db->where('id', $id);
        $query = $this->db->get('coin_rate');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to update a record  
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('coin_rate', $datos);
		return $result;
        
    }    

}
?>
