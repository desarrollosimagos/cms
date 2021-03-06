<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_field extends CI_Migration
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
				"type" => array(
					"type" => "VARCHAR",
					"constraint" => 20,
					"null" => FALSE
				),
				"format" => array(
					"type" => "VARCHAR",
					"constraint" => 20,
					"null" => FALSE
				),
				"description" => array(
					"type" => "TEXT",
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
		
		$this->dbforge->create_table('field', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'field'
		$this->dbforge->drop_table('field', TRUE);
		
	}
	
}
