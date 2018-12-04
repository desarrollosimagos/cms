<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MPayments extends CI_Model {

    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the contracts
    public function listar() {
		
        $query = $this->db->get('contracts');

        return $query->result();
            
    }

    //Public method to obtain the transactions
    public function obtenerContratos($user_id) {
		
		$select = 'c.id, c.project_id, p.name, c.user_id, u.username, c.transaction_id, c.type, ';
		$select .= 'c.created_on, c.finished_on, c.payback, c.amount, ';
		$select .= 'cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, cn.decimals as coin_decimals';
		$this->db->select($select);
		$this->db->distinct();
		$this->db->from('contracts c');
		$this->db->join('projects p', 'p.id = c.project_id', 'left');
		$this->db->join('coins cn', 'cn.id = p.coin_id');
		$this->db->join('users u', 'u.id = c.user_id');
		// Si el usuario corresponde al de un perfil diferente a administrador y a plataforma
        if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			$this->db->where('c.user_id', $user_id);
			$this->db->or_where('c.user_create_id', $user_id);
		}
        $query = $this->db->get();

        return $query->result();
            
    }

    //Public method to obtain the transactions
    public function getContractsTransaction($transaction_id) {
		
		$select = 'c.id, c.project_id, p.name, c.user_id, u.username, c.transaction_id, c.type, ';
		$select .= 'c.created_on, c.finished_on, c.payback, c.amount, ';
		$select .= 'cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, cn.decimals as coin_decimals';
		$this->db->select($select);
		$this->db->distinct();
		$this->db->from('contracts c');
		$this->db->join('projects p', 'p.id = c.project_id', 'left');
		$this->db->join('coins cn', 'cn.id = p.coin_id');
		$this->db->join('users u', 'u.id = c.user_id', 'left');
		$this->db->where('c.transaction_id', $transaction_id);
        $query = $this->db->get();
        
        //~ echo $this->db->last_query();
        //~ 
        //~ exit();

        return $query->result();
            
    }

    //Public method to obtain the transactions
    public function obtenerTransacciones($user_id) {
		
		$select = 'f_p.id, f_p.date, f_p.account_id, f_p.type, f_p.description, f_p.reference, f_p.observation, ';
		$select .= 'f_p.real, f_p.rate, f_p.document, f_p.amount, f_p.status, u.name as usuario, c.alias, c.number, ';
		$select .= 'cn.description as coin, cn.abbreviation as coin_avr, cn.symbol as coin_symbol, cn.decimals as coin_decimals';
		$this->db->select($select);
		$this->db->distinct();
		$this->db->from('transactions f_p');
		$this->db->join('users u', 'u.id = f_p.user_id', 'left');
		$this->db->join('accounts c', 'c.id = f_p.account_id');
		$this->db->join('coins cn', 'cn.id = c.coin_id');
		// Si el usuario corresponde al de un perfil diferente a administrador y a plataforma
        if($this->session->userdata('logged_in')['profile_id'] != 1 && $this->session->userdata('logged_in')['profile_id'] != 2){
			$this->db->where('f_p.user_create_id', $user_id);
		}
        $query = $this->db->get();

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

    // Public method to obtain the projects by id
    public function obtenerContrato($id) {
		
		
		$this->db->where('id', $id);
		$query = $this->db->get('contracts');
		
        return $query->result();
            
    }

    // Public method to update a record  
    public function update_contract($datos) {
		
		$result = $this->db->where('id', $datos['id']);
		$result = $this->db->update('contracts', $datos);
		return $result;
        
    }


    // Public method to delete a record
     public function delete($id) {
        $result = $this->db->where('service_id =', $id);
        $result = $this->db->get('franchises_transactions');

        if ($result->num_rows() > 0) {
            echo 'existe';
        } else {
            $result = $this->db->delete('transactions', array('id' => $id));
            return $result;
        }
       
    }
    

}
?>
