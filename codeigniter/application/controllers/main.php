<?php

class Main extends System_Controller {
	
	public function index() {	

		//*** Redirect to user's homepage if already logged in
		if ($this->session->userdata('login_ok') == 1) {
			redirect('/' . user_default_homepage());
			exit;
		}
	
		$this->load->helper('form');

		//Set messages from flash data that other pages may have passed
		$this->data['errors'] = $this->session->flashdata('errors');
		$this->data['message'] = $this->session->flashdata('message');
		
		if ($this->session->userdata('login_ok') != 1) {
			$this->session->sess_destroy();
			unset($this->session->userdata);
		}
		
		//Load the login form
		$this->data['title'] = 'Login';
		$this->load->view('templates/header', $this->data);
		$this->load->view('main', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}
	
}