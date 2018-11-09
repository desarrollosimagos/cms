<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_project_rules extends CI_Migration
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
				"var1" => array(
					"type" => "TEXT",
					"null" => TRUE
				),
				"cond" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"var2" => array(
					"type" => "TEXT",
					"null" => TRUE
				),
				"project_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"segment" => array(
					"type" => "VARCHAR",
					"constraint" => 150,
					"null" => FALSE
				),
				"result" => array(
					"type" => "TEXT",
					"null" => TRUE
				)
			)
		);
		
		$this->dbforge->add_key('id', TRUE);  // Establecemos el id como primary_key
		
		$this->dbforge->add_key('project_id');  // Establecemos la project_id como key
		
		$this->dbforge->create_table('project_rules', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'project_rules'
		$this->dbforge->drop_table('project_rules', TRUE);
		
	}
	
}
