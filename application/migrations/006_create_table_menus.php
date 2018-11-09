<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_menus extends CI_Migration
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
					"constraint" => 50
				),
				"description" => array(
					"type" => "TEXT",
					"null" => TRUE
				),
				"logo" => array(
					"type" => "VARCHAR",
					"constraint" => 100,
					"null" => TRUE
				),
				"route" => array(
					"type" => "VARCHAR",
					"constraint" => 50,
					"null" => TRUE
				),
				"action_id" => array(
					"type" => "INT",
					"constraint" => 11
				),
				"order" => array(
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
		
		$this->dbforge->add_key('action_id');  // Establecemos el action_id como key
		
		$this->dbforge->create_table('menus', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'menus'
		$this->dbforge->drop_table('menus', TRUE);
		
	}
	
}
