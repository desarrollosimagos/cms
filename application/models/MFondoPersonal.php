<?php
/**
 * MFondoPersonal class than extends of CI_Model
 *
 * An class than search data in the table 'transactions' and their associated tables.
 * 
 * Se encarga de realizar las consultas CRUD y las envía al controlador 'CFondoPersonal' principalmente.
 * 
 * @author	@jsolorzano18 (twitter)
 */
defined('BASEPATH') OR exit('No direct script access allowed');


class MFondoPersonal extends CI_Model {
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
		"f_p.account_id", 
		"f_p.type", 
		"f_p.description", 
		"f_p.reference", 
		"f_p.observation",
		"f_p.real", 
		"f_p.rate", 
		"f_p.document", 
		"f_p.amount", 
		"f_p.status", 
		"u.name as usuario", 
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
		"f_p.real", 
		"f_p.rate", 
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

/**
 * ------------------------------------------------------
 * Public method to obtain a transactions list
 * ------------------------------------------------------
 */
    public function obtener() {
		
		$this->db->select('f_p.id, f_p.account_id, f_p.type, f_p.description, f_p.reference, f_p.observation, f_p.real, f_p.rate, f_p.document, f_p.amount, f_p.status, u.name as usuario, c.alias, c.number, cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, cn.decimals as coin_decimals');
		$this->db->distinct();
		// Si el usuario logueado es de perfil administrador tomamos todas las transacciones.
		// Si el usuario logueado es de perfil plataforma tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil inversor tomamos todas las transacciones asociadas a él.
		// Si el usuario logueado es de perfil gestor tomamos todas las transacciones generadas por él.
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from('transactions f_p');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join('transactions f_p', 'f_p.account_id = c.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from('transactions f_p');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from('transactions f_p');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_create_id =', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->from('transactions f_p');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
		}
		$this->db->order_by("f_p.id", "desc");
        $query = $this->db->get();
        //~ $query = $this->db->get('transactions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

/**
 * ------------------------------------------------------
 * Public method to obtain the associated accounts
 * ------------------------------------------------------
 */
    public function obtener_cuentas_group() {
		
		$this->db->select('c.id, c.alias, c.number, cn.abbreviation as coin_avr');
		if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('users u');
			$this->db->join('usergroups_users i_g_u', 'i_g_u.user_id=u.id');
			$this->db->join('usergroups i_g', 'i_g.id=i_g_u.group_id');
			$this->db->join('usergroups_accounts i_g_a', 'i_g_a.group_id=i_g.id');
			$this->db->join('accounts c', 'c.id=i_g_a.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('i_g_u.user_id =', $this->session->userdata('logged_in')['id']);
			$this->db->group_by(array("c.id", "c.alias", "c.number", "cn.abbreviation"));
			$this->db->order_by("c.alias", "desc");
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from('accounts c');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('c.user_id =', $this->session->userdata('logged_in')['id']);
			$this->db->group_by(array("c.id", "c.alias", "c.number", "cn.abbreviation"));
			$this->db->order_by("c.alias", "desc");
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from('users u');
			$this->db->join('usergroups_users i_g_u', 'i_g_u.user_id=u.id');
			$this->db->join('usergroups i_g', 'i_g.id=i_g_u.group_id');
			$this->db->join('usergroups_accounts i_g_a', 'i_g_a.group_id=i_g.id');
			$this->db->join('accounts c', 'c.id=i_g_a.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('i_g_u.user_id =', $this->session->userdata('logged_in')['id']);
			$this->db->group_by(array("c.id", "c.alias", "c.number", "cn.abbreviation"));
			$this->db->order_by("c.alias", "desc");
		}else{
			$this->db->from('accounts c');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('c.user_id =', $this->session->userdata('logged_in')['id']);
			$this->db->group_by(array("c.id", "c.alias", "c.number", "cn.abbreviation"));
			$this->db->order_by("c.alias", "desc");
		}
			
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

/**
 * ------------------------------------------------------
 * Public method to obtain the associated projects
 * ------------------------------------------------------
 */
    public function obtener_proyectos_group() {
		
		$this->db->select('pj.id, pj.name, pj.description, p_t.type as type, pj.valor, pj.amount_r, pj.amount_min, pj.amount_max, pj.date, pj.date_r, pj.date_v, pj.status, c.description as coin, c.abbreviation as coin_avr, c.symbol as coin_symbol');
		// Si el usuario logueado es de perfil administrador o plataforma tomamos sólo los proyectos de su grupo de inversores
		if($this->session->userdata('logged_in')['profile_id'] == 1 || $this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_projects ig_p', 'ig_p.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('projects pj', 'pj.id = ig_p.project_id');
			$this->db->join('project_types p_t', 'p_t.id = pj.type');
			$this->db->join('coins c', 'c.id = pj.coin_id');
			$this->db->where('ig_u.user_id', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from('projects pj');
			$this->db->join('project_types p_t', 'p_t.id = pj.type');
			$this->db->join('transactions f_p', 'f_p.project_id = pj.id');
			$this->db->join('coins c', 'c.id = pj.coin_id');
			$this->db->where('f_p.user_id', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_projects ig_p', 'ig_p.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('projects pj', 'pj.id = ig_p.project_id');
			$this->db->join('project_types p_t', 'p_t.id = pj.type');
			$this->db->join('coins c', 'c.id = pj.coin_id');
			$this->db->where('ig_u.user_id', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->from('projects pj');
			$this->db->join('project_types p_t', 'p_t.id = pj.type');
			$this->db->join('coins c', 'c.id = pj.coin_id');
		}
		$this->db->order_by("pj.id", "desc");
		$query = $this->db->get();

		if ($query->num_rows() > 0)
			return $query->result();
		else
			return $query->result();
            
    }

/**
 * ------------------------------------------------------
 * Public method to insert the data
 * ------------------------------------------------------
 */
    public function insert($datos) {
		
		$result = $this->db->insert("transactions", $datos);
		$id = $this->db->insert_id();
		return $id;
        
    }

/**
 * ------------------------------------------------------
 * Public method to obtain the transactions by id
 * ------------------------------------------------------
 */
    public function obtenerFondoPersonal($id) {
		
		$this->db->select('f_p.id, f_p.date, f_p.user_id, f_p.project_id, f_p.account_id, f_p.type, f_p.description, f_p.reference, f_p.observation, f_p.real, f_p.rate, f_p.document, f_p.amount, f_p.status, u.name as usuario, c.alias, c.number, cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, cn.decimals as coin_decimals');
		$this->db->from('transactions f_p');
		$this->db->join('users u', 'u.id = f_p.user_id', 'left');
		$this->db->join('accounts c', 'c.id = f_p.account_id');
		$this->db->join('coins cn', 'cn.id = c.coin_id');
		$this->db->where('f_p.id =', $id);
        $query = $this->db->get();
        //~ $query = $this->db->get('transactions');
        
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

/**
 * ------------------------------------------------------
 * Public method to update a record
 * ------------------------------------------------------
 */
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('transactions', $datos);
		return $result;
        
    }

/**
 * ------------------------------------------------------
 * Public method to delete a record
 * ------------------------------------------------------
 */
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
        $this->db->select($this->select_column);
        $this->db->distinct();
		// Si el usuario logueado es de perfil administrador tomamos todas las transacciones.
		// Si el usuario logueado es de perfil plataforma tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil inversor tomamos todas las transacciones asociadas a él.
		// Si el usuario logueado es de perfil gestor tomamos todas las transacciones generadas por él.
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join($this->table, 'f_p.account_id = c.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_create_id =', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
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
		$this->db->order_by("f_p.id", "DESC");
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
		$this->db->select($this->select_column);
		// Si el usuario logueado es de perfil administrador tomamos todas las transacciones.
		// Si el usuario logueado es de perfil plataforma tomamos todas las transacciones asociadas a su grupo de inversores.
		// Si el usuario logueado es de perfil inversor tomamos todas las transacciones asociadas a él.
		// Si el usuario logueado es de perfil gestor tomamos todas las transacciones generadas por él.
		if($this->session->userdata('logged_in')['profile_id'] == 1){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
		}else if($this->session->userdata('logged_in')['profile_id'] == 2){
			$this->db->from('usergroups ig');
			$this->db->join('usergroups_accounts ig_a', 'ig_a.group_id = ig.id');
			$this->db->join('usergroups_users ig_u', 'ig_u.group_id = ig.id');
			$this->db->join('accounts c', 'c.id = ig_a.account_id', 'right');
			$this->db->join('account_type t_c', 't_c.id = c.type', 'right');
			$this->db->join($this->table, 'f_p.account_id = c.id');
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('ig_u.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 3){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
		}else if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_create_id =', $this->session->userdata('logged_in')['id']);
		}else{
			$this->db->from($this->table);
			$this->db->join('users u', 'u.id = f_p.user_id', 'left');
			$this->db->join('accounts c', 'c.id = f_p.account_id');
			$this->db->join('coins cn', 'cn.id = c.coin_id');
			$this->db->where('f_p.user_id =', $this->session->userdata('logged_in')['id']);
		}
		return $this->db->count_all_results();
	}
    

}
?>
