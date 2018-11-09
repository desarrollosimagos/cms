<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_projects extends CI_Migration
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
				"name" => array(
					"type" => "VARCHAR",
					"constraint" => 50,
					"null" => TRUE
				),
				"description" => array(
					"type" => "VARCHAR",
					"constraint" => 200,
					"null" => TRUE
				),
				"valor" => array(
					"type" => "FLOAT",
					"null" => TRUE
				),
				"type" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"public" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"status" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				//~ "user_id" => array(
					//~ "type" => "INT",
					//~ "constraint" => 11,
					//~ "null" => TRUE
				//~ ),
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
		
		$this->dbforge->add_key('coin_id');  // Establecemos el coin_id como key
		
		$this->dbforge->create_table('projects', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'projects'
		$this->dbforge->drop_table('projects', TRUE);
		
	}
	
}
