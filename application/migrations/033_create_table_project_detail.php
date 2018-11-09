<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_project_detail extends CI_Migration
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
				"lang_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"project_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"button" => array(
					"type" => "VARCHAR",
					"constraint" => 100,
					"null" => FALSE
				),
				"title" => array(
					"type" => "VARCHAR",
					"constraint" => 256,
					"null" => FALSE
				),
				"subtitle" => array(
					"type" => "VARCHAR",
					"constraint" => 256,
					"null" => FALSE
				),
				"content" => array(
					"type" => "TEXT",
					"null" => TRUE
				),
				"order" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"status" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				)
			)
		);
		
		$this->dbforge->add_key('id', TRUE);  // Establecemos el id como primary_key
		
		$this->dbforge->add_key('lang_id');  // Establecemos la lang_id como key
		
		$this->dbforge->add_key('project_id');  // Establecemos la project_id como key
		
		$this->dbforge->create_table('project_detail', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'project_detail'
		$this->dbforge->drop_table('project_detail', TRUE);
		
	}
	
}
