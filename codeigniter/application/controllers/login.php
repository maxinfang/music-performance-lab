<?php

class Login extends System_Controller {
	
	public function index() {

		//***** Load Helpers
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');					

		//***** Set Variables
		$username = trim($this->input->post('username'));
		$password = trim($this->input->post('password'));

		//***** Validate Login form
		if ($username == '') {
			$errors[] = 'zID is required';
			
		} elseif ($password == '') {
			$errors[] = 'zPass is required';

		//***** PROCESS FORM			
		} else {
			
						
			//*** Authenticate Against LDAP
			//So we can later check whether the user's zid and zpass is correct
			include_once('adUNSWLDAP.php');
			$adldap = new adLDAP();
	
			//*** Which user type is login?
			
			/* [1] Staff and Student: Logging in with their zID and zPass
			 * First check to see if it can authenticate against LDAP.
			 */
			if ($adldap -> authenticate($username,$password)) {
								
				//Check if there's a record for the student
				$errors = login_student('student', $username, $password);
				
			/* [2] Student: Staff is trying to log in as the student with the students portal password
			 * Did not authenticate against LDAP, so we'll see if there is a prefix in the username
			 */
			} elseif (substr($username, 0, 5) == 'unsw-') {
				
//				$errors = login_student('staff', $username, $password);

			/* [3] Tutor: Logging in with their portal login
			 * OR
			 * [4] Student: Staff is trying to log in as the student with the students portal password
			 * Did not authenticate against LDAP and does not have a prefix
			 * Its most likely a supervisor trying to login
			 */
/*			} else {			
			
				$errors = login_supervisor($username, $password);
*/				
			}
			
	
		} //End process form

		//***** View
		
		$this->data['title'] = 'Login';
		$this->data['errors'] = $errors;
		$this->data['form']['username'] = $username;
		$this->data['form']['password'] = $password;
		
		//Load the login form
		$this->load->view('templates/header', $this->data);
		$this->load->view('main', $this->data);
		$this->load->view('templates/footer', $this->data);

	}

}