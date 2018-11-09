<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_projectsteam extends CI_Migration
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
					"constraint" => 100,
					"null" => FALSE
				),
				"description" => array(
					"type" => "TEXT",
					"null" => FALSE
				),
				"project_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => FALSE
				),
				"img" => array(
					"type" => "VARCHAR",
					"constraint" => 100,
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
		
		$this->dbforge->add_key('project_id');  // Establecemos el project_id como key
		
		$this->dbforge->create_table('projectsteam', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'projectsteam'
		$this->dbforge->drop_table('projectsteam', TRUE);
		
	}
	
}
