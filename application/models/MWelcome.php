<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MWelcome extends CI_Model {


    public function __construct() {
       
        parent::__construct();
        $this->load->database();
    }

    public function get_slider_projects()
    {
        $this->db->select('a.id, a.name, a.description, b.photo as image');
        $this->db->from('projects a');
        $this->db->join('photos b', 'b.project_id = a.id');
        $result = $this->db->get();
        return $result->result();
    }

    public function get_slider_detail($id)
    {
        $this->db->where('a.id =', $id);
        $this->db->select('a.id, a.name, a.description, a.amount_min, a.date, b.photo as image');
        $this->db->from('projects a');
        $this->db->join('project_photos b', 'b.project_id = a.id', 'left');
        $result = $this->db->get();
        return $result->row();
    }
	
	// Obtiene la lista de idiomas disponibles
    public function get_langs()
    {
        $result = $this->db->get('lang');
        return $result->result();
    }
	
	// Obtiene el id del idioma cargado en sesión
    public function get_lang_id()
    {
		// Capturamos el idioma actual
		$siteLang = $this->session->userdata('site_lang');
        if($siteLang) {
			$lang = $siteLang;
		}else{
			$lang = 'english';
		}
        $this->db->where('name', $lang);
        $result = $this->db->get('lang');
        return $result->result()[0]->id;
    }
    
    // Public method to insert the data
    public function insert_lang($datos) {
		
		$result = $this->db->insert("lang", $datos);
		$id = $this->db->insert_id();
		return $id;
        
    }

}
?>
