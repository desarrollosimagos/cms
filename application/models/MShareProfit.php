<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MShareProfit extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    //Public method to obtain the transactions
    public function obtener() {
        $query = $this->db->get('transactions');

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }    

}
?>
