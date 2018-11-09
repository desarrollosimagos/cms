<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_coin_rate extends CI_Migration
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
				"coin" => array(
					"type" => "VARCHAR",
					"constraint" => 10
				),
				"rate" => array(
					"type" => "FLOAT",
					"constraint" => 20.12
				),
				"d_create" => array(
					"type" => "DATE",
					"null" => TRUE
				),
				"d_update" => array(
					"type" => "TIMESTAMP",
					"null" => TRUE
				)
			)
		);
		
		$this->dbforge->add_key('id', TRUE);  // Establecemos el id como primary_key
		
		$this->dbforge->create_table('coin_rate', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'coin_rate'
		$this->dbforge->drop_table('coin_rate', TRUE);
		
	}
	
}
