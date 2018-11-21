<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_cond_project_rules extends CI_Migration
{
	public function up(){
		
		// Modificará la columna `cond`:
		$fields = array(
			"cond" => array(
				"type" => "VARCHAR",
				"constraint" => 10,
				"null" => TRUE
			)
		);
		
		// Modificamos la columna `cond` si ésta existe en la tabla
		if($this->db->field_exists('cond', 'project_rules') == TRUE){
			$this->dbforge->modify_column('project_rules', $fields);
		}
		
	}
	
	public function down(){
		
		// Devolvemos la configuración de la columna 'cond'
		$fields = array(
			"cond" => array(
				"type" => "INT",
				"constraint" => 11,
				"null" => TRUE
			)
		);
		
		// Modificamos la columna `cond` si ésta existe en la tabla
		if($this->db->field_exists('cond', 'project_rules') == TRUE){
			$this->dbforge->modify_column('project_rules', $fields);
		}
		
	}
	
}
