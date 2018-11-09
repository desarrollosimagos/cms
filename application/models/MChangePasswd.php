<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MChangePasswd extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }
    
    // Public method to check a user password
    public function verificarPasswd($user_id, $password) {
		
        $this->db->where('id', $user_id);
        $this->db->where('password', $password);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to update a record 
    public function update_passwd($datos) {
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('users', $datos);
		return $result;
	} 

}
?>
