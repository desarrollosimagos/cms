<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MCoins extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the coins
    public function obtener() {
		
        $query = $this->db->get('coins');
        //~ $query = $this->db->get('coins');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to insert the data
    public function insert($datos) {
		
		$result = $this->db->where('description =', $datos['description']);
		$result = $this->db->where('abbreviation =', $datos['abbreviation']);
        $result = $this->db->get('coins');
        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
            $result = $this->db->insert("coins", $datos);
            $id = $this->db->insert_id();
            return $id;
        }
        
    }

    // Public method to obtain the coins by id
    public function obtenerMoneda($id) {
		
        $this->db->where('id', $id);
        $query = $this->db->get('coins');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to update a record  
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('coins', $datos);
		return $result;
        
    }


    // Public method to delete a record
    public function delete($id) {
		$query = $this->db->where('coin_id =', $id);
        $query = $this->db->get('users');
        
		$query2 = $this->db->where('coin_id =', $id);
        $query2 = $this->db->get('cuentas');
        
        if ($query->num_rows() > 0 || $query2->num_rows() > 0) {
            echo 'existe';
        } else {
            $result = $this->db->delete('coins', array('id' => $id));
			return $result;
        }
       
    }
    

}
?>
