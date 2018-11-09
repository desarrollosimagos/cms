<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_user_create_id_users extends CI_Migration
{
	public function up(){
		
		// Colocará la nueva columna después de la columna `coin_id`:
		$fields = array(
			"user_create_id" => array(
				"type" => "INT",
				"constraint" => 11,
				"null" => TRUE,
				"default" => 1,
				"after" => "lang_id"
			)
		);
		
		// Creamos el nuevo campo si éste no existe en la tabla
		if($this->db->field_exists('user_create_id', 'users') == FALSE){
			$this->dbforge->add_column('users', $fields);
		}
		
		$this->dbforge->add_key('user_create_id');  // Establecemos el user_create_id como key
		
	}
	
	public function down(){
		
		// Eliminamos la columna 'user_create_id'
		$this->dbforge->drop_column('user_create_id', TRUE);
		
	}
	
}
