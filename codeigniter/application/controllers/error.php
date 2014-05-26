<?php

class Error extends CI_Controller {
	
	public function index() {
			
		$this->data['title'] = 'Error Occurred';
		
		$error = str_replace('%20', ' ' , $this->uri->segment(2));
		$this->data['error'] = $error;
		
		
		$this->load->view('templates/header', $this->data);
		$this->load->view('error', $this->data);
		$this->load->view('templates/footer', $this->data);
	
	}

	// ------------------------------------------------------------------------

	public function _remap($method, $params = array()) {
		
		$method = 'index';
		return call_user_func_array(array($this, $method), $params);

	}
}