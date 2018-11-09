<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_lang_id_users extends CI_Migration
{
	public function up(){
		
		// Colocará la nueva columna después de la columna `coin_id`:
		$fields = array(
			"lang_id" => array(
				"type" => "INT",
				"constraint" => 11,
				"null" => TRUE,
				"default" => 1,
				"after" => "coin_id"
			)
		);
		
		// Creamos el nuevo campo si éste no existe en la tabla
		if($this->db->field_exists('lang_id', 'users') == FALSE){
			$this->dbforge->add_column('users', $fields);
		}
		
		$this->dbforge->add_key('lang_id');  // Establecemos el lang_id como key
		
	}
	
	public function down(){
		
		// Eliminamos la columna 'lang_id'
		$this->dbforge->drop_column('lang_id', TRUE);
		
	}
	
}
