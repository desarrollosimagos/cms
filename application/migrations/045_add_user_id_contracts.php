<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_user_id_contracts extends CI_Migration
{
	public function up(){
		
		// Colocará la nueva columna después de la columna `project_id`:
		$fields = array(
			"user_id" => array(
				"type" => "INT",
				"constraint" => 11,
				"null" => TRUE,
				"after" => "project_id"
			)
		);
		
		// Creamos el nuevo campo si éste no existe en la tabla
		if($this->db->field_exists('user_id', 'contracts') == FALSE){
			$this->dbforge->add_column('contracts', $fields);
		}
		
		$this->dbforge->add_key('user_id');  // Establecemos el user_id como key
		
	}
	
	public function down(){
		
		// Eliminamos la columna 'user_id'
		$this->dbforge->drop_column('contracts', 'user_id');
		
	}
	
}
