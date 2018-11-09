<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_table_user_relations extends CI_Migration
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
				"userfrom_id" => array(
					"type" => "INT",
					"constraint" => 11
				),
				"userto_id" => array(
					"type" => "INT",
					"constraint" => 11
				),
				"type" => array(
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
		
		$this->dbforge->add_key('userfrom_id');  // Establecemos el userfrom_id como key
		
		$this->dbforge->add_key('userto_id');  // Establecemos el userto_id como key
		
		$this->dbforge->create_table('user_relations', TRUE);
		
	}
	
	public function down(){
		
		// Eliminamos la tabla 'user_relations'
		$this->dbforge->drop_table('user_relations', TRUE);
		
	}
	
}
