<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_contracts extends CI_Migration
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
				"project_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"transaction_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"type" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"created_on" => array(
					"type" => "TIMESTAMP",
					"null" => TRUE
				),
				"finished_on" => array(
					"type" => "TIMESTAMP",
					"null" => TRUE
				),
				"payback" => array(
					"type" => "INT",
					"null" => TRUE
				),
				"amount" => array(
					"type" => "FLOAT",
					"null" => TRUE
				),
			)
		);
		
		$this->dbforge->add_key('id', TRUE);  // Establecemos el id como primary_key
		
		$this->dbforge->add_key('project_id');  // Establecemos la project_id como key
		
		$this->dbforge->add_key('transaction_id');  // Establecemos el transaction_id como key
		
		$this->dbforge->create_table('contracts', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'contracts'
		$this->dbforge->drop_table('contracts', TRUE);
		
	}
	
}
