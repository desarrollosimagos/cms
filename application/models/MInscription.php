<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MInscription extends CI_Model {
	
	public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }
    
    // Método público para obterner todos los proyectos
    public function listar_proyectos() {
        $this->db->select('pj.id, pj.name, pj.description, p_t.type as type, pj.valor, pj.public, pj.status, c.description as coin, c.abbreviation as coin_avr, c.symbol as coin_symbol');
		$this->db->from('projects pj', 'pj.id = ig_p.project_id');
		$this->db->join('project_types p_t', 'p_t.id = pj.type');
		$this->db->join('coins c', 'c.id = pj.coin_id');
		$this->db->order_by("pj.id", "desc");
		$query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Public method to obtain the users
    public function listar_usuarios() {
		
		$this->db->select('u.id, u.username, u.name, u.alias, u.profile_id, u.admin, u.status, u.image, c.description as coin, c.abbreviation as coin_avr, p.name as perfil, l.id as lang_id, l.name as lang_name');
		$this->db->from('users u');
		$this->db->join('profile p', 'p.id = u.profile_id');
		$this->db->join('coins c', 'c.id = u.coin_id');
		$this->db->join('lang l', 'l.id = u.lang_id');
		$this->db->where('u.profile_id', 4);
		// Si el usuario corresponde al de un COMPETIDOR añadimos el filtro de usuario
        if($this->session->userdata('logged_in')['profile_id'] == 4){
			$this->db->where('u.id', $this->session->userdata('logged_in')['id']);
		}
		$this->db->order_by("u.id", "desc");
        $query = $this->db->get();
        //~ $query = $this->db->get('users');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
            
    }

    // Public method to insert the data of a transaciton
    public function insert_transaction($datos) {
		
		$result = $this->db->where('project_id', $datos['project_id']);
		$result = $this->db->where('user_id', $datos['user_id']);
        $result = $this->db->get('transactions');
        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
            $result = $this->db->insert("transactions", $datos);
			$id = $this->db->insert_id();
			return $id;
        }
        
    }

    // Public method to insert the data of a contract
    public function insert_contract($datos) {
		
		$result = $this->db->where('project_id', $datos['project_id']);
		$result = $this->db->where('user_id', $datos['user_id']);
        $result = $this->db->get('contracts');
        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
            $result = $this->db->insert("contracts", $datos);
			$id = $this->db->insert_id();
			return $id;
        }
        
    }

    // Public method to insert the data of a rule of a contract
    public function insert_contract_rule($datos) {
		
		$result = $this->db->where('contracts_id', $datos['contracts_id']);
		$result = $this->db->where('segment', $datos['segment']);
        $result = $this->db->get('contract_rules');
        if ($result->num_rows() > 0) {
            return 'existe';
        } else {
            $result = $this->db->insert("contract_rules", $datos);
			$id = $this->db->insert_id();
			return $id;
        }
        
    }
    
    // Método público para obterner las reglas de un proyecto
    public function get_project_rules($project_id) {
		
		$result = $this->db->where('project_id', $project_id);
        $query = $this->db->get('project_rules');

        return $query->result();
            
    }
    
    // Método público para verificar si una fecha está dentro de un rango de fechas
    public function check_in_range($date, $range_from, $range_to) {
		
		$range_from = strtotime($range_from);
		$range_to = strtotime($range_to);
		$date = strtotime($date);

		if(($date >= $range_from) && ($date <= $range_to)) {

			return true;

		} else {

			return false;

		}
            
    }
    
    // Public method to serach the rules
    public function buscar_rules($variable1, $condicional, $variable2, $segmento) {
		$result = $this->db->where('var1', $variable1);
		$result = $this->db->where('cond', $condicional);
		$result = $this->db->where('var2', $variable2);
		$result = $this->db->where('segment', $segmento);
        $result = $this->db->get('project_rules');
        return $result->result();
    }
    
    //Public method to obtain the accounts
    public function buscar_cuentas($project_id) {
		
		$select = 'a.id, a.owner, a.alias, a.number, a.type, a.description, a.amount, a.status, a.d_create, ';
		$select .= 'c.description as coin, c.abbreviation as coin_avr, c.symbol as coin_symbol, ';
		$select .= 'c.decimals as coin_decimals, t_c.name as tipo_cuenta';
		
		$this->db->select($select);
		$this->db->from('accounts a');
		$this->db->join('transactions t', 't.account_id = a.id');
		$this->db->join('projects p', 'p.id = t.project_id');
		$this->db->join('coins c', 'c.id = a.coin_id');
		$this->db->join('account_type t_c', 't_c.id = a.type');
		// Si el usuario corresponde al de un GESTOR incluimos sólo las cuentas donde éste creo transacciones
        if($this->session->userdata('logged_in')['profile_id'] == 5){
			$this->db->where('t.user_create_id =', $this->session->userdata('logged_in')['id']);
		}
		$this->db->where('t.project_id =', $project_id);
		$this->db->group_by(array("a.id", "a.owner", "a.alias", "a.number", "a.type", "a.description", "a.amount", "a.status", "a.d_create", "coin", "coin_avr", "coin_symbol", "coin_decimals", "tipo_cuenta"));
		$this->db->order_by("a.id", "desc");
		$query = $this->db->get();
		//~ $query = $this->db->get('accounts');

		if ($query->num_rows() > 0)
			return $query->result();
		else
			return $query->result();
            
    }

    // Public method to update a record  
    public function update($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('projects', $datos);
		return $result;
        
    }


    // Public method to delete a record
     public function delete($id) {
		 
		$result = $this->db->delete('projects', array('id' => $id));
		return $result;
       
    }

}
?>
