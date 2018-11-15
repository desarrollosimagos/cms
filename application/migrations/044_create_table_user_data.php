<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_user_data extends CI_Migration
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
				"user_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => FALSE
				),
				"dni" => array(
					"type" => "VARCHAR",
					"constraint" => 11,
					"null" => FALSE
				),
				"gender" => array(
					"type" => "VARCHAR",
					"constraint" => 20,
					"null" => FALSE
				),
				"birthday" => array(
					"type" => "DATE",
					"null" => FALSE
				),
				"phone" => array(
					"type" => "VARCHAR",
					"constraint" => 20,
					"null" => FALSE
				),
				"emergency_contact" => array(
					"type" => "VARCHAR",
					"constraint" => 50,
					"null" => FALSE
				),
				"emergency_phone" => array(
					"type" => "VARCHAR",
					"constraint" => 20,
					"null" => FALSE
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
		
		$this->dbforge->add_key('user_id');  // Establecemos el user_id como key
		
		$this->dbforge->create_table('user_data', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'user_data'
		$this->dbforge->drop_table('user_data', TRUE);
		
	}
	
}
