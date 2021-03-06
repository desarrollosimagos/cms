<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_projects_fields extends CI_Migration
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
				"field_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => FALSE
				),
				"project_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => FALSE
				),
				"required" => array(
					"type" => "INT",
					"constraint" => 11,
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
		
		$this->dbforge->add_key('id_field');  // Establecemos el id_field como key
		
		$this->dbforge->add_key('project_id');  // Establecemos el project_id como key
		
		$this->dbforge->create_table('projects_fields', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'projects_fields'
		$this->dbforge->drop_table('projects_fields', TRUE);
		
	}
	
}
