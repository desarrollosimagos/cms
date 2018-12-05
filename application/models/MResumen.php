<?php
/**
 * MResumen class than extends of CI_Model
 *
 * An class than search data in the table 'transactions' and their associated tables.
 * 
 * Se encarga de realizar las consultas CRUD y las envía al controlador 'CResumen' principalmente.
 * 
 * @author	@jsolorzano18 (twitter)
 */
defined('BASEPATH') OR exit('No direct script access allowed');


class MResumen extends CI_Model {
/**
 * Tabla principal de consulta
 *
 * @var	array
 *
 */
	var $table = "transactions f_p";
	
/**
 * Campos a seleccionar
 *
 * @var	array
 *
 */
	var $select_column = array(
		"f_p.id", 
		"f_p.date", 
		"f_p.account_id", 
		"f_p.type", 
		"f_p.description", 
		"f_p.reference", 
		"f_p.observation", 
		"f_p.amount", 
		"f_p.status", 
		"u.username as usuario", 
		"u.name as user_name", 
		"c.owner",  
		"c.alias",  
		"c.number", 
		"cn.description as coin", 
		"cn.abbreviation as coin_avr", 
		"cn.symbol as coin_symbol", 
		"cn.decimals as coin_decimals"
	);

/**
 * Campos permitidos para ordenamiento
 *
 * @var	array
 *
 */
	var $order_column = array(
		"f_p.account_id", 
		"f_p.type",
		"f_p.description", 
		"f_p.reference", 
		"f_p.observation",
		"f_p.amount", 
		"f_p.status",
		"u.name",
	);

/**
 * Initialization class
 *
 * Loads the database connection.
 *
 */
    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the transactions
    public function obtener() {
		
		// Almacenamos los ids de los inversores asociados al asesor más su id propio en un array
		$ids = array($this->session->userdata('logged_in')['id']);
		$this->db->where('userfrom_id', $this->session->userdata('logged_in')['id']);
        $query_asesor_inversores = $this->db->get('user_relations');
        if ($query_asesor_inversores->num_rows() > 0) {
            foreach($query_asesor_inversores->result() as $relacion){
				$ids[] = $relacion->userto_id;
			}
		}
		
		$this->db->select('f_p.id, f_p.date, f_p.account_id, f_p.type, f_p.description, f_p.reference, f_p.observation, f_p.amount, f_p.status, u.username as usuario, u.name as user_name, c.owner, c.alias, c.number, cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, cn.decimals as coin_decimals');
		$this->db->distinct();
		// Si el usuario logueado es de perfil administrador tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil plataforma tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil inversor tomamos todas las transacciones asociadas a él.
		// Si el usuario logueado es de perfil gestor tomamos todas las transacciones generadas por él.
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join('transactions f_p', 'f_p.account_id = c.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join('transactions f_p', 'f_p.account_id = acc.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from('transactions f_p');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 4){
			$this->db->from('transactions f_p');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where_in('f_p.user_id', $ids);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from('transactions f_p');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_create_id', $this->session->userdata('logged_in')['id']);
		}
		$this->db->order_by("f_p.date", "desc");
        $query = $this->db->get();
        //~ $query = $this->db->get('transactions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to obtain the transactions by id
    public function capitalPendiente() {
		if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			$this->db->select_sum('amount');
			$this->db->where('status', 'waiting');
			$this->db->where('user_id', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->select_sum('amount');
			$this->db->where('status', 'waiting');			
		}
        $query = $this->db->get('transactions');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to obtain the transactions by id
    public function capitalDisponible() {
		if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			$this->db->select_sum('amount');
			$this->db->where('status', 'approved');
			$this->db->where('user_id', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->select_sum('amount');
			$this->db->where('status', 'approved');			
		}
        $query = $this->db->get('transactions');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to obtain the transactions by id
    public function capitalAprobado() {
		
		// Datos de moneda del usuario
		$iso_moneda_usu = $this->session->userdata('logged_in')['coin_iso'];
		
		$capitalAprobado = 0;
		
		$this->db->select('f_p.id, f_p.account_id, f_p.tipo, f_p.amount, f_p.status, cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol');
		$this->db->from('transactions f_p');
		$this->db->join('accounts c', 'c.id = f_p.account_id');
		$this->db->join('coins cn', 'cn.id = c.coin_id');
		if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			$this->db->where('f_p.status', 'approved');
			$this->db->where('f_p.user_id', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->where('f_p.status', 'approved');
		}
        $query = $this->db->get();
        
        foreach($query->result() as $result){
			if($result->tipo == 'deposit'){
				$capitalAprobado += $result->amount;
			}else{
				$capitalAprobado -= $result->amount;
			}
		}
		
        return $capitalAprobado;
            
    }

    // Public method to obtain the transactions by id
    public function fondos_json() {
		
		// Almacenamos los ids de los inversores asociados al asesor más su id propio en un array
		$ids = array($this->session->userdata('logged_in')['id']);
		$this->db->where('userfrom_id', $this->session->userdata('logged_in')['id']);
        $query_asesor_inversores = $this->db->get('user_relations');
        if ($query_asesor_inversores->num_rows() > 0) {
            foreach($query_asesor_inversores->result() as $relacion){
				$ids[] = $relacion->userto_id;
			}
		}
		
		$capitalAprobado = 0;
		
		$select = 'u.name, u.alias, u.username, f_p.id, f_p.account_id, f_p.project_id, f_p.user_id, f_p.user_create_id, f_p.type, f_p.amount, f_p.real, f_p.rate, f_p.status, f_p.date, ';
		$select .= 'cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, cn.decimals as coin_decimals, pf.id as perfil_id, pf.name as perfil_name, pj.name as project_name, p_t.type as project_type, count(ctr.transaction_id) as contracts';
		
		$this->db->select($select);
		//~ $this->db->from('transactions f_p');
		//~ $this->db->join('accounts c', 'c.id = f_p.account_id');
		//~ $this->db->join('coins cn', 'cn.id = c.coin_id');
		//~ $this->db->join('users u', 'u.id = f_p.user_id', 'left');
		//~ $this->db->join('profile pf', 'pf.id = u.profile_id', 'left');
		//~ $this->db->join('projects pj', 'pj.id = f_p.project_id', 'left');
		//~ $this->db->join('project_types p_t', 'p_t.id = pj.type', 'left');
		//~ // $this->db->where_in('cn.abbreviation', array('USD','BTC','VEF'));
		
		// Si el usuario logueado es de perfil administrador tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil plataforma tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil inversor tomamos todas las transacciones asociadas a él.
		// Si el usuario logueado es de perfil gestor tomamos todas las transacciones generadas por él.
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join('transactions f_p', 'f_p.account_id = c.id');
			$this->db->join('contracts ctr', 'ctr.transaction_id = f_p.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('profile pf', 'pf.id = u.profile_id', 'left');
			$this->db->join('projects pj', 'pj.id = f_p.project_id', 'left');
			$this->db->join('project_types p_t', 'p_t.id = pj.type', 'left');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join('transactions f_p', 'f_p.account_id = c.id');
			$this->db->join('contracts ctr', 'ctr.transaction_id = f_p.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('profile pf', 'pf.id = u.profile_id', 'left');
			$this->db->join('projects pj', 'pj.id = f_p.project_id', 'left');
			$this->db->join('project_types p_t', 'p_t.id = pj.type', 'left');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from('transactions f_p');
			$this->db->join('contracts ctr', 'ctr.transaction_id = f_p.id');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('profile pf', 'pf.id = u.profile_id', 'left');
			$this->db->join('projects pj', 'pj.id = f_p.project_id', 'left');
			$this->db->join('project_types p_t', 'p_t.id = pj.type', 'left');
			$this->db->where_in('f_p.user_id', $ids);
		}else if($this->session->userdata('logged_in')['profile_id'] == 4){
			$this->db->from('transactions f_p');
			$this->db->join('contracts ctr', 'ctr.transaction_id = f_p.id');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('profile pf', 'pf.id = u.profile_id', 'left');
			$this->db->join('projects pj', 'pj.id = f_p.project_id', 'left');
			$this->db->join('project_types p_t', 'p_t.id = pj.type', 'left');
			$this->db->where('f_p.user_id', $this->session->userdata('logged_in')['id']);
		}
		
        $query = $this->db->get();
        
        //~ echo $this->db->last_query();
		//~ exit();
        
        return $query->result();
            
    }
	
    // Public method to obtain the transactions by id
    public function fondos_json_users() {
		
		// Almacenamos los ids de los inversores asociados al asesor más su id propio en un array
		$ids = array($this->session->userdata('logged_in')['id']);
		$this->db->where('userfrom_id', $this->session->userdata('logged_in')['id']);
        $query_asesor_inversores = $this->db->get('user_relations');
        if ($query_asesor_inversores->num_rows() > 0) {
            foreach($query_asesor_inversores->result() as $relacion){
				$ids[] = $relacion->userto_id;
			}
		}
		
		// Consulta a la tabla 'transactions'
		$select = 'u.name, u.alias, u.username, f_p.project_id, f_p.id, f_p.user_id, f_p.account_id, f_p.type, f_p.amount, f_p.status, f_p.date, ';
		$select .= 'cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol';
		
		$this->db->select($select);
		$this->db->from('transactions f_p');
		$this->db->join('accounts c', 'c.id = f_p.account_id');
		$this->db->join('coins cn', 'cn.id = c.coin_id');
		$this->db->join('users u', 'u.id = f_p.user_id', 'left');
		if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			$this->db->where_in('f_p.user_id', $ids);
		}
        $query = $this->db->get();
        
        return $query->result();
            
    }
	
    // Public method to obtain the transactions by project
    public function fondos_json_projects() {
		
		$select = 'u.name, u.alias, u.username, f_p.id, f_p.project_id, f_p.user_id, f_p.account_id, f_p.type, f_p.amount, f_p.status, f_p.date, ';
		$select .= 'cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, p.name, p.description, p_t.type as project_type';
		
		$this->db->select($select);
		$this->db->from('transactions f_p');
		$this->db->join('accounts c', 'c.id = f_p.account_id');
		$this->db->join('coins cn', 'cn.id = c.coin_id');
		$this->db->join('users u', 'u.id = f_p.user_id', 'left');
		$this->db->join('projects p', 'p.id = f_p.project_id', 'left');
		$this->db->join('project_types p_t', 'p_t.id = p.type', 'left');
		if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			$this->db->where('f_p.user_id', $this->session->userdata('logged_in')['id']);
		}
        $query = $this->db->get();
        
        return $query->result();
            
    }
	
    // Public method to obtain the transactions by project and order it by coin
    public function fondos_json_projects_coin($project_id) {
		
		$select = 'u.name, u.alias, u.username, f_p.id, f_p.project_id, f_p.user_id, f_p.account_id, f_p.type, SUM(f_p.amount) as amount, f_p.status, f_p.date, ';
		$select .= 'cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, p.name as project_name, p.description, p_t.type as project_type';
		
		$this->db->select($select);
		$this->db->from('transactions f_p');
		$this->db->join('accounts c', 'c.id = f_p.account_id');
		$this->db->join('coins cn', 'cn.id = c.coin_id');
		$this->db->join('users u', 'u.id = f_p.user_id', 'left');
		$this->db->join('projects p', 'p.id = f_p.project_id', 'left');
		$this->db->join('project_types p_t', 'p_t.id = p.type', 'left');
		$this->db->where('f_p.project_id', $project_id);
		if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			if($this->session->userdata('logged_in')['profile_id'] == 5){
				$this->db->where('f_p.user_create_id', $this->session->userdata('logged_in')['id']);
			}else{
				$this->db->where('f_p.user_id', $this->session->userdata('logged_in')['id']);
			}
		}
		$this->db->group_by('cn.description');
        $query = $this->db->get();
        
        return $query->result();
            
    }
	
    // Public method to obtain the transactions by project
    public function fondos_deposito() {
		
		$this->db->select('SUM(amount) as amount');
		$this->db->from('transactions f_p');
		$this->db->where('f_p.type', 'deposit');
		$this->db->where('f_p.project_id !=', 0);
		$this->db->where('f_p.status', 'approved');
        $query = $this->db->get();
        
        return $query->result();
            
    }

    // Public method to obtain the transactions by id
    public function obtenerFondoPersonal($id) {
		
        $this->db->where('id', $id);
        $query = $this->db->get('transactions');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to update a record  
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('transactions', $datos);
		return $result;
        
    }


    // Public method to delete a record
     public function delete($id) {
		 
		$result = $this->db->delete('transactions', array('id' => $id));
		return $result;
       
    }
    
    /**
 * ------------------------------------------------------
 * MÉTODOS PARA RETORNO DE DATOS REQUERIDOS POR DATATABLE
 * ------------------------------------------------------
 */
 
/*
 * ------------------------------------------------------
 *  Método público para construir la consulta solicitada mediante ajax
 * ------------------------------------------------------
 * 
 * Se utilizan los atributos de la clase referentes a la tabla y los campos.
 *
 * Nota: Luego de la validación del perfil se valida si se
 * envió una búsqueda y si ésta no está vacía, de ser así,
 * se realiza un filtro para traducir las búsquedas en español
 * referentes a los campos 'type' y 'status'. Por último se aplica el 
 * ordenamiento solicitado.
 *
 */
    public function make_query() {
		
		// Almacenamos los ids de los inversores asociados al asesor más su id propio en un array
		$ids = array($this->session->userdata('logged_in')['id']);
		$this->db->where('userfrom_id', $this->session->userdata('logged_in')['id']);
        $query_asesor_inversores = $this->db->get('user_relations');
        if ($query_asesor_inversores->num_rows() > 0) {
            foreach($query_asesor_inversores->result() as $relacion){
				$ids[] = $relacion->userto_id;
			}
		}
		
        $this->db->select($this->select_column);
        $this->db->distinct();
        // Si el usuario logueado es de perfil administrador tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil plataforma tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil inversor tomamos todas las transacciones asociadas a él.
		// Si el usuario logueado es de perfil gestor tomamos todas las transacciones generadas por él.
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join($this->table, 'f_p.account_id = c.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join($this->table, 'f_p.account_id = acc.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 4){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where_in('f_p.user_id', $ids);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_create_id', $this->session->userdata('logged_in')['id']);
		}
		if(isset($_POST["search"]["value"]) && $_POST["search"]["value"] != ""){
			// Filtro para traducir el valor de las búsquedas de estatus en español que coincidan con el equivalente en inglés
			if($_POST["search"]["value"] == "Validado" || $_POST["search"]["value"] == "validado"){
				$_POST["search"]["value"] = "approved";
			}else if($_POST["search"]["value"] == "En espera" || $_POST["search"]["value"] == "en espera"){
				$_POST["search"]["value"] = "waiting";
			}
			// Filtro para traducir el valor de las búsquedas de tipo en español que coincidan con el equivalente en inglés
			if($_POST["search"]["value"] == "Depósito" || $_POST["search"]["value"] == "depósito"){
				$_POST["search"]["value"] = "deposit";
			}else if($_POST["search"]["value"] == "Retiro" || $_POST["search"]["value"] == "retiro"){
				$_POST["search"]["value"] = "withdraw";
			}else if($_POST["search"]["value"] == "Inversión" || $_POST["search"]["value"] == "inversión"){
				$_POST["search"]["value"] = "invest";
			}else if($_POST["search"]["value"] == "Ganancia" || $_POST["search"]["value"] == "ganancia"){
				$_POST["search"]["value"] = "profit";
			}else if($_POST["search"]["value"] == "Gasto" || $_POST["search"]["value"] == "gasto"){
				$_POST["search"]["value"] = "expense";
			}else if($_POST["search"]["value"] == "Venta" || $_POST["search"]["value"] == "venta"){
				$_POST["search"]["value"] = "sell";
			}
			$condicionales_like = "(u.name LIKE '%".$_POST["search"]["value"]."%' OR ";
			$condicionales_like .= "f_p.type LIKE '%".$_POST["search"]["value"]."%' OR ";
			$condicionales_like .= "f_p.amount LIKE '%".$_POST["search"]["value"]."%' OR ";
			$condicionales_like .= "f_p.status LIKE '%".$_POST["search"]["value"]."%' OR ";
			$condicionales_like .= "f_p.description LIKE '%".$_POST["search"]["value"]."%' OR ";
			$condicionales_like .= "f_p.reference LIKE '%".$_POST["search"]["value"]."%' OR ";
			$condicionales_like .= "f_p.observation LIKE '%".$_POST["search"]["value"]."%')";
			$this->db->where($condicionales_like);
		}
		if(isset($_POST["order"])){
			$this->db->order_by($this->order_column[$_POST["order"]["0"]["column"]], $_POST["order"]["0"]["dir"]);
		}else{
			$this->db->order_by("f_p.date", "DESC");
		}
    }

/**
 * ------------------------------------------------------
 * Método público para ejecutar la consulta construida arriba
 * y aplicar los límites solicitos.
 * ------------------------------------------------------
 */
    public function make_datatables(){
		$this->make_query();
		if($_POST["length"] != -1){
			$this->db->limit($_POST["length"], $_POST["start"]);
		}
		$query = $this->db->get();
		return $query->result();		
	}

/**
 * ------------------------------------------------------
 * Método público para obtener el número de registros 
 * resultantes de make_query().
 * ------------------------------------------------------
 */
	public function get_filtered_data(){
		$this->make_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

/**
 * ------------------------------------------------------
 * Método público para obtener el número total de registros 
 * de transacciones del usuario.
 * ------------------------------------------------------
 */
	public function get_all_data(){
		
		// Almacenamos los ids de los inversores asociados al asesor más su id propio en un array
		$ids = array($this->session->userdata('logged_in')['id']);
		$this->db->where('userfrom_id', $this->session->userdata('logged_in')['id']);
        $query_asesor_inversores = $this->db->get('user_relations');
        if ($query_asesor_inversores->num_rows() > 0) {
            foreach($query_asesor_inversores->result() as $relacion){
				$ids[] = $relacion->userto_id;
			}
		}
		
		$this->db->select($this->select_column);
		// Si el usuario logueado es de perfil administrador tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil plataforma tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil inversor tomamos todas las transacciones asociadas a él.
		// Si el usuario logueado es de perfil gestor tomamos todas las transacciones generadas por él.
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join($this->table, 'f_p.account_id = c.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join($this->table, 'f_p.account_id = acc.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 4){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where_in('f_p.user_id', $ids);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_create_id', $this->session->userdata('logged_in')['id']);
		}
		return $this->db->count_all_results();
	}
    

}
?>
