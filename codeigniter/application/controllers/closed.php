<?php

class Closed extends System_Controller {

	function Index() {
		
		$this->data['title'] = 'System closed';			
		$this->load->view('templates/header.php', $this->data);
		$this->load->view('closed.php', $this->data);
		$this->load->view('templates/footer.php', $this->data);

		/* Redirect to main page if the system is open
		 * incase someone booked marked this closing page
		 */
		if ($this->session->userdata('system_open') == 1) {
			redirect('');
			die();
		}

	}
	
}