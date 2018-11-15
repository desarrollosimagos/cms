<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MProfileUser extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }
    
    // Public method to obtain the users by id
    public function obtenerUserData($user_id) {
		
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_data');
        return $query->result();
        
    }

    // Public method to update a record 
    public function update($datos) {
		
        $result = $this->db->where('user_id =', $datos['user_id']);
        $result = $this->db->get('user_data');
		
		// Si ya existe data complementaria para el usuario logueado actualizamos, sino registramos los datos
        if ($result->num_rows() > 0) {
			
            $result = $this->db->where('user_id', $datos['user_id']);
            $result = $this->db->update('user_data', $datos);
            return $result;
            
        } else {
			
			$result = $this->db->insert("user_data", $datos);
            $id = $this->db->insert_id();
            return $id;
            
        }
        
    } 

}
?>
