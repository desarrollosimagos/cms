<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_usergroups_projects extends CI_Migration
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
				"group_id" => array(
					"type" => "INT",
					"constraint" => 11
				),
				"project_id" => array(
					"type" => "INT",
					"constraint" => 11
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
		
		$this->dbforge->add_key('group_id');  // Establecemos el group_id como key
		
		$this->dbforge->add_key('project_id');  // Establecemos el project_id como key
		
		$this->dbforge->create_table('usergroups_projects', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'usergroups_projects'
		$this->dbforge->drop_table('usergroups_projects', TRUE);
		
	}
	
}
