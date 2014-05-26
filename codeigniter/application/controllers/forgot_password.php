<?php

class Forgot_password extends CI_Controller {
	
	function Index() {
		
		//***** Load Helper
		$this->load->helper('form');
		
		//***** View
		$this->data['title'] = 'Forgot Password';
		$this->data['errors'] = $this->session->flashdata('errors');
		$this->data['message'] = $this->session->flashdata('message');
		$this->load->view('templates/education/header', $this->data);
		$this->load->view('forgot-password', $this->data);
		$this->load->view('templates/education/footer', $this->data);
	
	}
	
	function Resend () {
		
		$this->load->helper('form');
		
		$username = $this->input->post('username');
	
		$zid_pattern = '/^z[0-9]{7}$/';
		
		//Username not entered
		if ($username == '') {
			$this->session->set_flashdata('errors', 'Please enter your username');
			redirect('/forgot-password');
			die();

		//Check to see if the user is a student
		} elseif (preg_match($zid_pattern, $username)) {
			$this->session->set_flashdata('errors', 'You need to use your zPass to log into this system. To reset your zPass, please visit <a href="https://idm.unsw.edu.au" target="_blank">UNSW Identity Manager</a>.');
		
		//It must be supervisor requesting password
		} else {
		
			//** Initialise connection to SugarCRM
			$sugar_session_id = rest_init();
			
			//** Find Corresponding Record in SugarCRM
			$parameters = array(
				'session'=>$sugar_session_id,
				'module_name'=>'la_Supervisors',
				'query'=>"la_supervisors_cstm.portal_name_c = '{$username}'",
				'order_by'=>'',
				'offset'=>0,
				'select_fields'=>array('portal_name_c', 'portal_password_c', 'email1', 'first_name', 'last_name'),
				'link_name_to_fields_array'=>'',
				'max_results'=>2, //Limit the return
				'deleted'=>0
			);
	
			$result = rest_call('get_entry_list', $parameters);
	
			//Found Supervisor Record
			if ($result->result_count == 1) {
				
				$password = $result->entry_list[0]->name_value_list->portal_password_c->value;
				$email = trim($result->entry_list[0]->name_value_list->email1->value);
				$first_name = $result->entry_list[0]->name_value_list->first_name->value;
				$last_name = $result->entry_list[0]->name_value_list->last_name->value;
				
				$message = "Dear {$first_name} {$last_name},\n\n" . 
									 "A request to was recieved to resend you your password for the UNSW Student Placement Management System at http://placements.arts.unsw.edu.au\n\n".
									 "Your password is: {$password}\n\n".

									 "Kind Regards,\r\n".
									 "UNSW Student Placement Management System";
									  
				//Email Password
				$this->load->library('email');
			
				$this->email->from('no-reply@unsw.edu.au', 'UNSW No Reply');
				$this->email->to($email); 
				
				$this->email->subject('FASS Student Placement Management System - Password Request');
				$this->email->message($message);	
				
//				$this->email->print_debugger();

				if ($this->email->send()) {
					
					$this->session->set_flashdata('email', $email);
					
					redirect('/forgot-password/resent');
					die();
					
				} else {
					
					$this->session->set_flashdata('errors', "Unable to email your password to <strong>{$email}</strong>");
					
				}
				
			} elseif ($result->result_count > 1) {
				$this->session->set_flashdata('errors', "Unable to identify username <strong>{$username}</strong>");
				
			} else {
				$this->session->set_flashdata('errors', "The username <strong>{$username}</strong> does not exist in our system");
			}
			
		}

		redirect('/forgot-password');
		die();
		
	}

	function Resent () {
		
		$this->session->keep_flashdata('email', $this->session->flashdata('email'));
		
		//***** View
		$this->data['title'] = 'Password Resent';
		$this->data['errors'] = $this->session->flashdata('errors');
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['email'] = $this->session->flashdata('email');
		$this->load->view('templates/education/header', $this->data);
		$this->load->view('password-sent', $this->data);
		$this->load->view('templates/education/footer', $this->data);
	
	}

	
}