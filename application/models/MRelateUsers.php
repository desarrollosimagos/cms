<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MRelateUsers extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the user_relations
    public function obtener() {
        
        $this->db->select('r_u.userfrom_id, u.username');
		$this->db->from('user_relations r_u');
		$this->db->join('users u', 'u.id=r_u.userfrom_id');
		// Si el usuario corresponde al de un administrador quitamos el filtro de usuario
        if($this->session->userdata('logged_in')['profile_id'] != 1){
			$this->db->where('r_u.userfrom_id =', $this->session->userdata('logged_in')['id']);
		}
		$this->db->group_by('r_u.userfrom_id, u.username');
        $query = $this->db->get();
        //~ $query = $this->db->get('user_relations');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    //Public method to obtain the user_relations alternative
    public function associated_investors() {
        
        $this->db->select('r_u.userfrom_id, r_u.userto_id, u.username');
		$this->db->from('user_relations r_u');
		$this->db->join('users u', 'u.id=r_u.userto_id');
        $query = $this->db->get();
        //~ $query = $this->db->get('user_relations');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    //Public method to obtain the consultant users
    public function obtener_asesores() {
		
		$this->db->where('profile_id', 4);
        $query = $this->db->get('users');
        //~ $query = $this->db->get('user_relations');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    //Public method to obtain the investor users
    public function obtener_inversores() {
		
		$this->db->where('profile_id', 3);
        $query = $this->db->get('users');
        //~ $query = $this->db->get('user_relations');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to insert the data
    public function insert($datos) {
		
		$result = $this->db->insert("user_relations", $datos);
		$id = $this->db->insert_id();
		return $id;
        
    }

    // Public method to obtain the user_relations by userfrom_id
    public function obtenerRelacion($id) {
		
        $this->db->where('userfrom_id', $id);
        $query = $this->db->get('user_relations');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }
    
    //Public method to obtain the user_relations by userfrom_id and userto_id
    public function obtenerRelacionIds($id_asesor, $id_inversor) {
		$this->db->where('userfrom_id =', $id_asesor);
		$this->db->where('userto_id =', $id_inversor);
        $query = $this->db->get('user_relations');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Public method to update a record  
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('user_relations', $datos);
		return $result;
        
    }
    
	// Public method to delete the asociated investors
    public function delete_investors($id) {
		$result = $this->db->delete('user_relations', array('userfrom_id' => $id));
		return $result;
    }
    
    // Public method to delete the specific association 
    public function delete_adviser_investor($id_adviser, $id_investor) {
		$result = $this->db->delete('user_relations', array('userfrom_id' => $id_adviser, 'userto_id' => $id_investor));
    }    

}
?>
