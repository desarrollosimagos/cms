<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_binnacle extends CI_Migration
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
				"date" => array(
					"type" => "TIMESTAMP",
					"null" => TRUE
				),
				"ip" => array(
					"type" => "VARCHAR",
					"constraint" => 15,
					"null" => FALSE
				),
				"user_id" => array(
					"type" => "INT",
					"constraint" => 11,
					"null" => TRUE
				),
				"detail" => array(
					"type" => "TEXT",
					"null" => TRUE
				)
			)
		);
		
		$this->dbforge->add_key('id', TRUE);  // Establecemos el id como primary_key
		
		$this->dbforge->add_key('user_id');  // Establecemos la user_id como key
		
		$this->dbforge->create_table('binnacle', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'binnacle'
		$this->dbforge->drop_table('binnacle', TRUE);
		
	}
	
}
