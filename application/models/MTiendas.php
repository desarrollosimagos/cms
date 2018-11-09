<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MTiendas extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the tienda
    public function obtener() {
        $query = $this->db->get('tiendas');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    //Public method to obtain the tienda
    public function obtenerByUser() {
        //~ $query = $this->db->get('tiendas');
		$this->db->select('t.id, t.rif, t.name, t.status, t.address, t.referencia, t.bd_externa, t.cp_externo, t.phone');
		$this->db->from('users_tiendas u_t');
		$this->db->join('tiendas t', 't.id = u_t.tienda_id');
		$this->db->where('u_t.user_id =', $this->session->userdata['logged_in']['id']);
		$query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    //Public method to obtain the applications
    public function obtener_tiendasv() {
        $query = $this->db->get('tienda_virtual');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    //Public method to obtain the applications
    public function obtener_usuarios() {
        $query = $this->db->get('users');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método publico, para obtener los usuarios asociados a la tienda
    public function obtenerUsuarios($id) {
        $this->db->where('tienda_id', $id);
        $query = $this->db->get('users_tiendas');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método publico, para obtener los usuarios asociados a la tienda
    public function obtenerTiendasv($id) {
        $this->db->where('tienda_id', $id);
        $query = $this->db->get('tiendas_tiendasv');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Public method to insert the data
    public function insert($datos) {
        $result = $this->db->where('name =', $datos['name']);
        $result = $this->db->get('tiendas');
        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
            $result = $this->db->insert("tiendas", $datos);
            $id = $this->db->insert_id();
            return $id;
        }
    }
    
    // Método público, forma de insertar los datos de la asociación entre tiendas virtuales y tiendas
    public function insertTiendasv($datos) {
        $result = $this->db->where('tienda_id =', $datos['tienda_id']);
        $result = $this->db->where('tiendav_id =', $datos['tiendav_id']);
        $result = $this->db->get('tiendas_tiendasv');
        if ($result->num_rows() > 0) {
			$result = $this->db->where('tienda_id =', $datos['tienda_id']);
			$result = $this->db->where('tiendav_id =', $datos['tiendav_id']);
            $result = $this->db->update("tiendas_tiendasv", $datos);
            return $result;
        } else {
            $result = $this->db->insert("tiendas_tiendasv", $datos);
            return $result;
        }
    }
    
    // Método público, forma de insertar los datos de la asociación entre usuarios y tiendas
    public function insertUsuarios($datos) {
        $result = $this->db->where('tienda_id =', $datos['tienda_id']);
        $result = $this->db->where('user_id =', $datos['user_id']);
        $result = $this->db->get('users_tiendas');
        if ($result->num_rows() > 0) {
			$result = $this->db->where('tienda_id =', $datos['tienda_id']);
			$result = $this->db->where('user_id =', $datos['user_id']);
            $result = $this->db->update("users_tiendas", $datos);
            return $result;
        } else {
            $result = $this->db->insert("users_tiendas", $datos);
            return $result;
        }
    }

    // Public method to obtain the tienda by id
    public function obtenerTiendas($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tiendas');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Public method to obtain the productos by id
    public function obtenerTiendasUsuario($id) {
        $this->db->where('user_id', $id);
        $query = $this->db->get('users_tiendas');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Public method to obtain the productos by id
    public function obtenerTiendasUsuario2($id_user, $id_tienda) {
        $this->db->where('user_id', $id_user);
        $this->db->where('tienda_id', $id_tienda);
        $query = $this->db->get('users_tiendas');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Public method to update a record  
    public function update($datos) {
        $result = $this->db->where('name =', $datos['name']);
        $result = $this->db->where('id !=', $datos['id']);
        $result = $this->db->get('tiendas');

        if ($result->num_rows() > 0) {
            echo '1';
        } else {
            $result = $this->db->where('id', $datos['id']);
            $result = $this->db->update('tiendas', $datos);
            return $result;
        }
    }
    
    // Public method to update a record 
    public function update_status($datos) {
        $result = $this->db->where('user_id', $datos['user_id']);
        $result = $this->db->where('tienda_id', $datos['tienda_id']);
        $result = $this->db->update('users_tiendas', $datos);
        return $result;
    }

    // Public method to delete a record
     public function delete($id) {
        $result = $this->db->where('tienda_id =', $id);
        $result = $this->db->get('users_tiendas');

        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
            $result = $this->db->delete('tiendas', array('id' => $id));
            return $result;
        }
       
    }
    
    // Public method to delete a record
    public function delete_tienda_usuario($id) {
		$result = $this->db->delete('users_tiendas', array('id' => $id));
		return $result;
    }
    
    // Public method to delete a record
    public function delete_tienda_tiendav($id) {
		$result = $this->db->delete('tiendas_tiendasv', array('id' => $id));
		return $result;
    }
    

}
?>
