<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MImport extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the services
    public function obtener() {
        $query = $this->db->get('services');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Public method to insert the data
    public function insert($datos) {
        $result = $this->db->where('name =', $datos['name']);
        $result = $this->db->get('services');
        if ($result->num_rows() > 0) {
            echo '1';
        } else {
            $result = $this->db->insert("services", $datos);
            return $result;
        }
    }

    //Public method to obtain the accounts
    public function obtener_cuentas() {
		
		$select = 'f_p.id, f_p.owner, f_p.alias, f_p.number, f_p.type, f_p.description, f_p.amount, f_p.status, f_p.d_create, ';
		$select .= 'u.username as usuario, c.description as coin, c.abbreviation as coin_avr, c.symbol as coin_symbol, ';
		$select .= 'c.decimals as coin_decimals, t_c.name as tipo_cuenta';
		
		$this->db->select($select);
		$this->db->distinct();
		// Si el usuario logueado es de perfil administrador tomamos todas las cuentas de todos los grupos de inversores
		// Si el usuario logueado es de perfil plataforma tomamos todas las cuentas asociadas a su grupo de inversores
		// Si el usuario logueado es de perfil inversor tomamos todas las cuentas asignadas al usuario
		// Si el usuario logueado es de perfil gestor tomamos todas las cuentas asociadas a su grupo de inversores
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from('investorgroups ig');
			$this->db->join('investorgroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('investorgroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts f_p', 'f_p.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = f_p.type', 'right');
			$this->db->join('users u', 'u.id = f_p.user_id');
			$this->db->join('coins c', 'c.id = f_p.coin_id');
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('investorgroups ig');
			$this->db->join('investorgroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('investorgroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts f_p', 'f_p.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = f_p.type', 'right');
			$this->db->join('users u', 'u.id = f_p.user_id');
			$this->db->join('coins c', 'c.id = f_p.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from('accounts f_p');
			$this->db->join('users u', 'u.id = f_p.user_id');
			$this->db->join('coins c', 'c.id = f_p.coin_id');
			$this->db->join('account_type t_c', 't_c.id = f_p.type');
			$this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from('investorgroups ig');
			$this->db->join('investorgroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('investorgroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts f_p', 'f_p.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = f_p.type', 'right');
			$this->db->join('users u', 'u.id = f_p.user_id');
			$this->db->join('coins c', 'c.id = f_p.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->from('accounts f_p');
			$this->db->join('users u', 'u.id = f_p.user_id');
			$this->db->join('coins c', 'c.id = f_p.coin_id');
			$this->db->join('account_type t_c', 't_c.id = f_p.type');
			$this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
		}
		$this->db->order_by("f_p.id", "desc");
		$query = $this->db->get();
		//~ $query = $this->db->get('accounts');

		if ($query->num_rows() > 0)
			return $query->result();
		else
			return $query->result();
            
    }

    // Public method to update a record  
    public function update($datos) {
        $result = $this->db->where('name =', $datos['name']);
        $result = $this->db->where('id !=', $datos['id']);
        $result = $this->db->get('services');

        if ($result->num_rows() > 0) {
            echo '1';
        } else {
            $result = $this->db->where('id', $datos['id']);
            $result = $this->db->update('services', $datos);
            return $result;
        }
    }


    // Public method to delete a record
     public function check_api_account($account_id) {
		 
        $result = $this->db->where('account_id =', $account_id);
        $result = $this->db->get('account_api');
        
        if ($result->num_rows() > 0) {
			return $result->result();
		}else{
			return 'no existe';
		}
       
    }

	// Public method to get a record by reference
	public function get_by_reference($reference) {
		 
		$result = $this->db->where('reference =', $reference);
        $result = $this->db->get('transactions');
        
        if ($result->num_rows() > 0) {
			return 'existe';
		}else{
			return 'no existe';
		}
       
    }
    

}
?>
