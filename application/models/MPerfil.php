<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MPerfil extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the profile
    public function obtener() {
        $query = $this->db->get('profile');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    //Public method to obtain the actions asociated
    public function obtener_acciones() {
        $query = $this->db->get('profile_actions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    //Public method to obtain the actions asociated by id_profile
    public function obtener_acciones_id($id_profile) {
		$this->db->where('profile_id =', $id_profile);
        $query = $this->db->get('profile_actions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    //Public method to obtain the actions asociated by profile_id and action_id
    public function obtener_accion_ids($id_profile, $id_action) {
		$this->db->where('profile_id =', $id_profile);
		$this->db->where('action_id =', $id_action);
        $query = $this->db->get('profile_actions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Public method to insert the data
    public function insert($datos) {
        $result = $this->db->where('name =', $datos['name']);
        $result = $this->db->get('profile');
        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
            $result = $this->db->insert("profile", $datos);
            $id = $this->db->insert_id();
            return $id;
        }
    }
    
    // Public method to insert the actions asociated
    public function insert_action($datos) {
		$result = $this->db->insert("profile_actions", $datos);
    }
    
    // Public method to insert the actions asociated
    public function update_action($datos) {
		$this->db->where('profile_id', $datos['profile_id']);
		$this->db->where('action_id', $datos['action_id']);
		$result = $this->db->update('profile_actions', $datos);
		return $result;
    }

    // Public method to obtain the profile by id
    public function obtenerPerfiles($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('profile');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Public method to update a record 
    public function update($datos) {
        $result = $this->db->where('name =', $datos['name']);
        $result = $this->db->where('id !=', $datos['id']);
        $result = $this->db->get('profile');

        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
            $result = $this->db->where('id', $datos['id']);
            $result = $this->db->update('profile', $datos);
            return $result;
        }
    }

    // Public method to delete a record 
    public function delete($id) {
        $result = $this->db->where('profile_id =', $id);
        $result = $this->db->get('users');

        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
			// Primero buscamos y eliminamos las acciones asociadas en la tabla 'profile_actions'
			$query_actions = $this->obtener_acciones_id($id);
			if(count($query_actions) > 0){
				foreach($query_actions as $action){
					$delete_action = $this->delete_action($action->id);
				}
			}
			// Eliminamos el perfil
            $result = $this->db->delete('profile', array('id' => $id));
            return $result;
        }
       
    }
    
    // Public method to delete the asociated actions
    public function delete_action($id) {
		$result = $this->db->delete('profile_actions', array('id' => $id));
    }
    
    // Public method to delete a specific association
    public function delete_profile_action($id_profile, $id_action) {
		$result = $this->db->delete('profile_actions', array('profile_id' => $id_profile, 'action_id' => $id_action));
    }
    

}
?>
