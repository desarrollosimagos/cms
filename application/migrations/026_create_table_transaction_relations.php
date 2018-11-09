<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_transaction_relations extends CI_Migration
{
	public function up(){
		
		// Creamos la estructura de la nueva tabla usando la clase dbforge de Codeigniter
		$this->dbforge->add_field(
			array(
				"id" => array(
					"type" => "INT",
					"constraint" => 11,
					"unsigned" => TRUE,
					"auto_increment" => TRUE,
					"null" => FALSE
				),
				"transaction_from_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"transaction_to_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"type" => array(
					"type" => "VARCHAR",
					"constraint" => 20,
					"null" => TRUE
				),
				"d_create" => array(
					"type" => "TIMESTAMP",
					"null" => TRUE
				),
				"d_update" => array(
					"type" => "TIMESTAMP",
					"null" => TRUE
				),
			)
		);
		
		$this->dbforge->add_key('id', TRUE);  // Establecemos el id como primary_key
		
		$this->dbforge->add_key('transaction_id');  // Establecemos la cuenta_id como key
		
		$this->dbforge->add_key('project_transaction_id');  // Establecemos el user_id como key
		
		$this->dbforge->create_table('transaction_relations', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'transaction_reference'
		$this->dbforge->drop_table('transaction_relations', TRUE);
		
	}
	
}
