<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_birthday_user_data extends CI_Migration
{
	public function up(){
		
		// Modificará la columna `birthday`:
		$fields = array(
			"birthday" => array(
				"type" => "TIMESTAMP",
				"null" => FALSE
			)
		);
		
		// Modificamos la columna `birthday` si ésta existe en la tabla
		if($this->db->field_exists('birthday', 'user_data') == TRUE){
			$this->dbforge->modify_column('user_data', $fields);
		}
		
	}
	
	public function down(){
		
		// Devolvemos la configuración de la columna 'birthday'
		$fields = array(
			"birthday" => array(
				"type" => "DATE",
				"null" => FALSE
			)
		);
		
		// Modificamos la columna `birthday` si ésta existe en la tabla
		if($this->db->field_exists('birthday', 'user_data') == TRUE){
			$this->dbforge->modify_column('user_data', $fields);
		}
		
	}
	
}
