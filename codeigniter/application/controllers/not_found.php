<?php

class Not_found extends CI_Controller {
	
	public function index() {
	
		$this->data['title'] = 'Page Not Found';
		$this->load->view('templates/header', $this->data);
		$this->load->view('error-404', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}

}