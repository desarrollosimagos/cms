<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_transactions extends CI_Migration
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
				"date" => array(
					"type" => "DATE",
					"null" => TRUE
				),
				"user_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"user_create_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"project_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"account_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"type" => array(
					"type" => "VARCHAR",
					"constraint" => 25,
					"null" => TRUE
				),
				"description" => array(
					"type" => "VARCHAR",
					"constraint" => 250,
					"null" => TRUE
				),
				"reference" => array(
					"type" => "VARCHAR",
					"constraint" => 100,
					"null" => TRUE
				),
				"observation" => array(
					"type" => "TEXT",
					"null" => TRUE
				),
				"amount" => array(
					"type" => "FLOAT",
					"null" => TRUE
				),
				"real" => array(
					"type" => "INT",
					"null" => TRUE
				),
				"rate" => array(
					"type" => "FLOAT",
					"null" => TRUE
				),
				"document" => array(
					"type" => "VARCHAR",
					"constraint" => 100,
					"null" => TRUE
				),
				"status" => array(
					"type" => "VARCHAR",
					"constraint" => 25,
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
		
		$this->dbforge->add_key('user_id');  // Establecemos el user_id como key
		
		$this->dbforge->add_key('cuenta_id');  // Establecemos la cuenta_id como key
		
		$this->dbforge->create_table('transactions', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'transactions'
		$this->dbforge->drop_table('transactions', TRUE);
		
	}
	
}
