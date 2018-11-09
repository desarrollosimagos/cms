<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_account_api extends CI_Migration
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
				"account_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"url" => array(
					"type" => "VARCHAR",
					"constraint" => 100,
					"null" => FALSE
				),
				"hmac_key" => array(
					"type" => "VARCHAR",
					"constraint" => 256,
					"null" => FALSE
				),
				"hmac_secret" => array(
					"type" => "VARCHAR",
					"constraint" => 256,
					"null" => FALSE
				),
				"status" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"d_create" => array(
					"type" => "TIMESTAMP",
					"null" => TRUE
				),
				"d_update" => array(
					"type" => "TIMESTAMP",
					"null" => TRUE
				)
			)
		);
		
		$this->dbforge->add_key('id', TRUE);  // Establecemos el id como primary_key
		
		$this->dbforge->add_key('account_id');  // Establecemos la account_id como key
		
		$this->dbforge->create_table('account_api', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'account_api'
		$this->dbforge->drop_table('account_api', TRUE);
		
	}
	
}
