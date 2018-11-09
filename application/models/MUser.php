<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MUser extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    // Método público para obterner todos los usuarios
    public function listar() {
        $query = $this->db->get('users');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Public method to obtain the users
    public function obtener() {
		
		$this->db->select('u.id, u.username, u.name, u.alias, u.profile_id, u.admin, u.status, u.image, c.description as coin, c.abbreviation as coin_avr, p.name as perfil, l.id as lang_id, l.name as lang_name');
		$this->db->from('users u');
		$this->db->join('profile p', 'p.id = u.profile_id');
		$this->db->join('coins c', 'c.id = u.coin_id');
		$this->db->join('lang l', 'l.id = u.lang_id');
		// Si el usuario corresponde al de un administrador quitamos el filtro de usuario
        if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			if(in_array($this->session->userdata('logged_in')['profile_id'], array(3, 4, 5))){
				$this->db->where('u.user_create_id =', $this->session->userdata('logged_in')['id']);
			}
		}
		$this->db->order_by("u.id", "desc");
        $query = $this->db->get();
        //~ $query = $this->db->get('users');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }
    
    // Public method to obtain the permissions asociated
    public function obtener_permisos() {
        $query = $this->db->get('permissions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Public method to obtain the permissions asociated by user_id
    public function obtener_permisos_id($id_user) {
		$this->db->where('user_id =', $id_user);
        $query = $this->db->get('permissions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Public method to obtain the permissions asociated by user_id and action_id
    public function obtener_permiso_ids($id_user, $id_action) {
		$this->db->where('user_id =', $id_user);
		$this->db->where('action_id =', $id_action);
        $query = $this->db->get('permissions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Public method to insert the data
    public function insert($datos) {
        $result = $this->db->where('username', $datos['username']);
        $result = $this->db->get('users');
        if ($result->num_rows() > 0) {
            return false;
        } else {
            $result = $this->db->insert("users", $datos);
            $id = $this->db->insert_id();
            return $id;
        }
    }
    
    // Public method to insert the action associated
    public function insert_action($datos) {
		$result = $this->db->insert("permissions", $datos);
		return $result;
    }
    
    // Public method to insert the action associated
    public function insert_token($datos) {
		$result = $this->db->insert("user_tokens", $datos);
		return $result;
    }
    
    // Public method to insert the actions asociated
    public function update_action($datos) {
		$this->db->where('user_id', $datos['user_id']);
		$this->db->where('action_id', $datos['action_id']);
		$result = $this->db->update('permissions', $datos);
		return $result;
    }

    // Public method to obtain the users by id
    public function obtenerUsers($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Public method to obtain the users by id
    public function obtenerUserName($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        
        return $query->result();
    }

    // Public method to obtain the users by id
    public function getToken($token) {
        $this->db->where('token', $token);
        $query = $this->db->get('user_tokens');
        
        return $query->result();
    }

    // Public method to update a record 
    public function update($datos) {
        $result = $this->db->where('username =', $datos['username']);
        $result = $this->db->where('id !=', $datos['id']);
        $result = $this->db->get('users');

        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
            $result = $this->db->where('id', $datos['id']);
            $result = $this->db->update('users', $datos);
            return $result;
        }
    }
    
    // Public method to update a record 
    public function update_status($datos) {
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('users', $datos);
		return $result;
	}
    
    // Public method to update a record 
    public function update_passwd($datos) {
		$result = $this->db->where('username', $datos['username']);
		$result = $this->db->update('users', $datos);
		return $result;
	}
    
    // Public method to update a record 
    public function update_session($datos) {
		$result = $this->db->where('user_id', $datos['user_id']);
		$result = $this->db->update('user_sessions', $datos);
		return $result;
	}

    // Public method to delete a record
     public function delete($id) {
        $result = $this->db->delete('users', array('id' => $id));
        return $result;
    }

    // Public method to delete the permissions asociated 
    public function delete_user_action($id_user, $id_action) {
		$result = $this->db->delete('permissions', array('user_id' => $id_user, 'action_id' => $id_action));
    }
    
    // Public method to obtain the actions asociated to profile_id
    public function search_profile_actions($id_profile)
    {
        $result = $this->db->where('profile_id', $id_profile);
        $result = $this->db->get('profile_actions');
        return $result->result();
    }
    
    // Public method to obtain the actions asociated to user_id
    public function search_permissions($id_user)
    {
        $result = $this->db->where('user_id', $id_user);
        $result = $this->db->get('permissions');
        return $result->result();
    }
    
    // Public method to obtain the actions not asociated to profile_id list
    public function search_actions($list_actions_ids)
    {
        $this->db->where_not_in('id',$list_actions_ids);
        $result = $this->db->get('actions');
        return $result->result();
    }
    
    // Public method to obtain the actions not asociated to user_id list
    public function search_actions2($list_actions_ids)
    {
        $this->db->where_in('id',$list_actions_ids);
        $result = $this->db->get('actions');
        return $result->result();
    }

}
?>
