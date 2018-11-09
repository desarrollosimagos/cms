<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MCuentas extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    // Método público para obterner todas las cuentas
    public function listar() {
        $select = 'f_p.id, f_p.owner, f_p.alias, f_p.number, f_p.type, f_p.description, f_p.amount, f_p.status, f_p.d_create, ';
		$select .= 'u.username as usuario, c.description as coin, c.abbreviation as coin_avr, c.symbol as coin_symbol, ';
		$select .= 'c.decimals as coin_decimals, t_c.name as tipo_cuenta';
		
		$this->db->select($select);
		$this->db->from('accounts f_p');
		$this->db->join('account_type t_c', 't_c.id = f_p.type', 'right');
		$this->db->join('users u', 'u.id = f_p.user_id');
		$this->db->join('coins c', 'c.id = f_p.coin_id');
		$this->db->order_by("f_p.id", "desc");
		$query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    //Public method to obtain the accounts
    public function obtener() {
		
		$select = 'f_p.id, f_p.owner, f_p.alias, f_p.number, f_p.type, f_p.description, f_p.amount, f_p.status, f_p.d_create, ';
		$select .= 'u.username as usuario, c.description as coin, c.abbreviation as coin_avr, c.symbol as coin_symbol, ';
		$select .= 'c.decimals as coin_decimals, t_c.name as tipo_cuenta';
		
		$this->db->select($select);
		$this->db->distinct();
		//~ $this->db->from('accounts f_p');
		//~ $this->db->join('users u', 'u.id = f_p.user_id');
		//~ $this->db->join('coins c', 'c.id = f_p.coin_id');
		//~ $this->db->join('account_type t_c', 't_c.id = f_p.type');
		//~ // Si el usuario corresponde al de un administrador quitamos el filtro de usuario
        //~ if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			//~ $this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
		//~ }
		// Si el usuario logueado es de perfil administrador tomamos todas las cuentas de todos los grupos de inversores
		// Si el usuario logueado es de perfil plataforma tomamos todas las cuentas asociadas a su grupo de inversores
		// Si el usuario logueado es de perfil inversor tomamos todas las cuentas asignadas al usuario
		// Si el usuario logueado es de perfil gestor tomamos todas las cuentas asociadas a su grupo de inversores
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts f_p', 'f_p.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = f_p.type', 'right');
			$this->db->join('users u', 'u.id = f_p.user_id');
			$this->db->join('coins c', 'c.id = f_p.coin_id');
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
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
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
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

    //Public method to obtain the accounts
    public function obtener_filtrado($buscar){
		
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
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts f_p', 'f_p.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = f_p.type', 'right');
			$this->db->join('users u', 'u.id = f_p.user_id');
			$this->db->join('coins c', 'c.id = f_p.coin_id');
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
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
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
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
		// Filtro del buscador
		if($buscar != ''){
			$this->db->like('f_p.alias', $buscar);
			$this->db->or_like('f_p.d_create', $buscar);
		}
		$this->db->order_by("f_p.id", "desc");
		$query = $this->db->get();
		//~ $query = $this->db->get('accounts');
		
		//~ echo $this->db->last_query();
		//~ exit();

		if ($query->num_rows() > 0)
			return $query->result();
		else
			return $query->result();
            
    }

    // Public method to insert the data
    public function insert($datos) {
		
		$result = $this->db->insert("accounts", $datos);
		$id = $this->db->insert_id();
		return $id;
        
    }

    // Public method to obtain the accounts by id
    public function obtenerCuenta($id) {
		
		$select = 'a.id, a.owner, a.alias, a.number, a.type, a.description, a.amount, a.status, a.d_create, a.coin_id, ';
		$select .= 'u.username as usuario, c.description as coin, c.abbreviation as coin_avr, c.symbol as coin_symbol, ';
		$select .= 'c.decimals as coin_decimals, t_c.name as tipo_cuenta';
		
		$this->db->select($select);
		$this->db->from('accounts a');
		$this->db->join('users u', 'u.id = a.user_id');
		$this->db->join('coins c', 'c.id = a.coin_id');
		$this->db->join('account_type t_c', 't_c.id = a.type');
		
        $this->db->where('a.id', $id);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to obtain the accounts by id
    public function obtenerCuentaFondos($id) {
		
        $this->db->where('account_id', $id);
        $query = $this->db->get('transactions');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to obtain the accounts by id
    public function obtenerCuentaGrupos($id) {
		
        $this->db->where('account_id', $id);
        $query = $this->db->get('usergroups_accounts');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }
    
    // Public method to serach the investors associated
    public function buscar_grupos($account_id) {
        $this->db->select('i_g.name');
		$this->db->from('usergroups_accounts i_g_a');
		$this->db->join('usergroups i_g', 'i_g.id = i_g_a.group_id');
		$this->db->where('account_id', $account_id);
		$query = $this->db->get();
		
        return $query->result();
    }
    
    // Public method to serach the transactions associated
    public function sumar_transacciones($account_id, $tabla) {
        $this->db->select('SUM(amount) as ingresos');
		$this->db->from($tabla);
		$this->db->where('account_id', $account_id);
		$this->db->where('status', 'approved');
		$query = $this->db->get();
		
        return $query->result();
    }
    
    // Public method to serach the transactions associated
    public function buscar_transacciones($account_id, $tabla) {
		
		$this->db->select('t.id, t.date, t.project_id, p.name as name_project, t.user_id, t.type, t.amount, t.real, t.rate, t.description, t.status, u.profile_id, u.name as name_user, cn.abbreviation as coin_avr');
		$this->db->distinct();
		//~ $this->db->from($tabla);
		//~ $this->db->join('users u', 'u.id = t.user_id', 'left');
		//~ $this->db->join('accounts a', 'a.id = t.account_id');
		//~ $this->db->join('coins c', 'c.id = a.coin_id');
		//~ $this->db->join('projects p', 'p.id = t.project_id', 'left');
		//~ $this->db->where('t.account_id', $account_id);
		//~ // Si el usuario corresponde al de un gestor tomamos sólo las transacciones propias
		//~ if($this->session->userdata('logged_in')['profile_id'] == 5){
			//~ $this->db->where('t.user_create_id =', $this->session->userdata('logged_in')['id']);
		//~ }
		
		// Si el usuario logueado es de perfil administrador tomamos todas las transacciones.
		// Si el usuario logueado es de perfil plataforma tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil inversor tomamos todas las transacciones asociadas a él.
		// Si el usuario logueado es de perfil gestor tomamos todas las transacciones generadas por él.
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from($tabla);
			$this->db->join('users u', 'u.id = t.user_id', 'left');
			$this->db->join('accounts c', 'c.id = t.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('projects p', 'p.id = t.project_id', 'left');
			$this->db->where('t.account_id', $account_id);
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join($tabla, 't.account_id = c.id');
			$this->db->join('users u', 'u.id = t.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('projects p', 'p.id = t.project_id', 'left');
			$this->db->where('t.account_id', $account_id);
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from($tabla);
			$this->db->join('users u', 'u.id = t.user_id', 'left');
			$this->db->join('accounts c', 'c.id = t.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('projects p', 'p.id = t.project_id', 'left');
			$this->db->where('t.account_id', $account_id);
			$this->db->where('t.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from($tabla);
			$this->db->join('users u', 'u.id = t.user_id', 'left');
			$this->db->join('accounts c', 'c.id = t.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('projects p', 'p.id = t.project_id', 'left');
			$this->db->where('t.account_id', $account_id);
			$this->db->where('t.user_create_id =', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->from($tabla);
			$this->db->join('users u', 'u.id = t.user_id', 'left');
			$this->db->join('accounts c', 'c.id = t.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('projects p', 'p.id = t.project_id', 'left');
			$this->db->where('t.account_id', $account_id);
			$this->db->where('t.user_id =', $this->session->userdata('logged_in')['id']);
		}
		$this->db->order_by("t.date", "desc");
		$query = $this->db->get();
		
		//~ echo $this->db->last_query();
		//~ 
		//~ exit();
		
        return $query->result();
    }
    
    // Public method to serach the transactions associated
    public function buscar_transaction_relation($transaction_id) {
        $this->db->select('transaction_to_id, type');
		$this->db->from('transaction_relations');
		$this->db->where('transaction_to_id', $transaction_id);
		$query = $this->db->get();
		
        return $query->result();
    }
    
    //~ // Public method to serach the transactions associated
    //~ public function buscar_project_transaction_relation($project_transaction_id) {
        //~ $this->db->select('transactions_projects_id');
		//~ $this->db->from('project_transactions_relations');
		//~ $this->db->where('transactions_projects_id', $project_transaction_id);
		//~ $query = $this->db->get();
		//~ 
        //~ return $query->result();
    //~ }

    // Public method to update a record  
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('accounts', $datos);
		return $result;
        
    }


    // Public method to delete a record
     public function delete($id) {
		 
		$result = $this->db->delete('accounts', array('id' => $id));
		return $result;
       
    }
    

}
?>
