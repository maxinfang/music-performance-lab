<?php

class Tutor extends Tutor_Controller {
	
	function __construct() {
		
		parent::__construct();
		
		//***** Navigation
		//Only see the navigation once they've gone through and required steps first
		if ($this->session->userdata('declaration_first_time') != 1 && $this->session->userdata('personal_info_first_time') != 1) {
			$this->data['navigation']['tutor'] = array('name'=>'Home', 'weight'=>1);
			$this->data['navigation']['tutor/progress-reports'] = array('name'=>'Progress Reports', 'weight'=>2);
			$this->data['navigation']['tutor/personal'] = array('name'=>'Personal Information', 'weight'=>3);	 
			$this->data['navigation']['tutor/profile'] = array('name'=>'Public Profile', 'weight'=>4);
		}
			
	} 

	// ------------------------------------------------------------------------

	public function _remap($method, $params = array()) {
		
		if ($this->session->userdata('login_ok') == 1 && $method != 'logout') { 
			/* User type
			 * Redirect if user do not have access to this section
			 */
			if ($this->session->userdata('user_type') != 'tutor') {
				redirect('' . user_default_homepage());
				die();
	
			/* Step 1: Declaration
			 * Redirect user to update declaration
			 */
			} elseif ($this->session->userdata('declaration_first_time') == 1) { 
				if ($method == 'declaration') {
					$this->$method($params);
				} else {
					redirect( user_default_homepage() . '/declaration/');
					die();
				}
	
			/* Personal Info
			 * Redirect user to update their personal information
			 * if they logged it prior to the system opening for this round of cohorts
			 */
			} elseif ($this->session->userdata('personal_info_first_time') == 1) { 
				if ($method == 'personal') {
					$this->$method($params);
				} else {
					redirect( user_default_homepage() . '/personal/');
					die();
				}
	
			//Display as requested
			} elseif (method_exists($this, $method)) {
					return call_user_func_array(array($this, $method), $params);
	
			//404 error
			} else {
				show_404();
			}

		//Display as requested
		} elseif (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $params);

		} else {
			show_404();
			
		}
	}
	
	// ------------------------------------------------------------------------

	function Index() {
		
		$this->data['database'] = $this->fm_db;

		//Get content
		$setting_record = $this->fm_db->getRecordById('www_setting', $this->session->userdata('system_rid'));
		$this->data['content'] = decode_html($setting_record->getField('web_content_tutor_home'));
	
		//Views
		$this->data['title'] = 'Welcome';
		$this->data['navigation']['tutor']['active'] = 1;
		$this->load->view('templates/tutor-header.php', $this->data);
		$this->load->view('tutor/index.php', $this->data);
		$this->load->view('templates/footer.php', $this->data);

	}

	// ------------------------------------------------------------------------
	function Progress_reports () {
		
		$this->data['database'] = $this->fm_db;
				
		//Get students thats currently enrolled and is being tutored by the tutor
		$find = $this->fm_db->newFindCommand('www_placement_search');
		$find->addFindCriterion('students::status', '==Currently enrolled');
		$find->addFindCriterion('year', '==' . $this->session->userdata('system_year'));
		$find->addFindCriterion('semester', '==' . $this->session->userdata('system_semester'));
		$find->addFindCriterion('placement_tutor::tutor_id', '==' . $this->session->userdata('tutor_id'));
		$find->addSortRule('students::first_name', 1, FILEMAKER_SORT_ASCEND);
		$find->addSortRule('students::last_name', 2, FILEMAKER_SORT_ASCEND);
		$result = $find->execute();
		
		if (FileMaker::isError($result)) {
		//trap error
		} else {
			$records = $result->getRecords();
			foreach ($records as $record) {
				
				//Put student info into array
				$row_info['placement_rid'] = $record->getRecordID();
				$row_info['placement_id'] = $record->getField('placement_id');
				$row_info['first_name'] = $record->getField('students::first_name');
				$row_info['last_name'] = $record->getField('students::last_name');
				$row_info['email'] = $record->getField('students::email');
				$row_info['semester'] = $record->getField('year') . ', S' . $record->getField('semester');
				
				$this->data['records'][] = $row_info;
			
			}
			
		}
		
		//Views
		$this->data['title'] = 'Progress Reports';
		$this->data['navigation']['tutor/progress-reports']['active'] = 1;
		$this->load->view('templates/tutor-header.php', $this->data);
		$this->load->view('tutor/progress-reports.php', $this->data);
		$this->load->view('templates/footer.php', $this->data);

	}


	// ------------------------------------------------------------------------

	function Login() {

		//Form submitted
		if ($this->input->post('submit')) {
			//***** Load Helpers
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');					
	
			//***** Set Variables
			$username = trim($this->input->post('username'));
			$password = trim($this->input->post('password'));
	
			//***** Validate Login form
			if ($username == '') {
				$errors[] = 'E-mail is required';
				
			} elseif ($password == '') {
				$errors[] = 'Password is required';
	
			//***** PROCESS FORM			
			} else {
							
				//*** Which user type is login?
				$errors = login_tutor($username, $password);
					
			} //End process form

		}
		
		//***** View

		$this->data['title'] = 'Tutor Login';
		$this->data['errors'] = $errors;
		$this->data['form']['username'] = $username;
		$this->data['form']['password'] = $password;
		
		//Load the login form
		$this->load->view('templates/tutor-header', $this->data);
		$this->load->view('tutor/login', $this->data);
		$this->load->view('templates/footer', $this->data);

	}


	// ------------------------------------------------------------------------

	function Logout() {


		//***** Log edit
		log_edits($this->session->userdata('tutor_id'), $this->session->userdata('tutor_id'), 'Logged out of tutor portal', 'Tutor Portal');
		
		//***** Destroy session
		$this->session->set_userdata('login_ok', '0');
		$this->session->sess_destroy();	
//		$this->session->set_flashdata('message', 'Logged out successfully');

		//***** Reset the messages for the main page to use
		redirect ('tutor');
		die();

	}
	
  // ------------------------------------------------------------------------
	function Declaration($params) {
 
		//Form submitted
		if ($this->uri->segment(3) == 'update' && $this->input->post('accept')) {

			//Log the edit so that won't need to come back to this page again
			log_edits($this->session->userdata('tutor_id'), $this->session->userdata('tutor_id') , 'Declaration complete', 'Tutor Portal');			
			
			//Update the session
			$this->session->set_userdata('declaration_first_time', 0);
	
			//Redirect to the next step which is personal information
			redirect( user_default_homepage() . '/personal/');
			die();
			
		}

    $this->data['title'] = 'Declaration';
		$this->load->view('templates/tutor-header', $this->data);
		$this->load->view("tutor/declaration", $this->data);
		$this->load->view('templates/footer', $this->data);

   }

  // ------------------------------------------------------------------------
	function Personal () {
 
		$fields = array(
			//Field => Submit to Database
			'first_name'				=>array('post'=>'0', 'setdb'=>'0', 'required'=>'0', 'label'=>'First Name'),
			'last_name'					=>array('post'=>'0', 'setdb'=>'0', 'required'=>'0', 'label'=>'Last Name'), 
			'title'							=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Title'),
			'public_list'				=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Add to public list?'),
			'qualifications'		=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Qualifications'),
			'instruments'				=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Instruments/voice'),
			'instruments_other'	=>array('post'=>'1', 'setdb'=>'1', 'required'=>'0', 'label'=>'Other instruments/voice'),
			'musical_styles'		=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Other instruments/voice'),
			'biography'					=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Biography'),
			'payment_method'		=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Payment Method'),
			'zid'								=>array('post'=>'1', 'setdb'=>'1', 'required'=>'0', 'label'=>'zID'),
			'vendor_id'					=>array('post'=>'1', 'setdb'=>'1', 'required'=>'0', 'label'=>'Vendor ID'),
			'email'							=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'E-mail address'),
			'phone_home'				=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Home phone'),
			'phone_mobile'			=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Mobile contact'),
			'address_line_1'		=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Address Line 1'),
			'address_line_2'		=>array('post'=>'1', 'setdb'=>'1', 'required'=>'0', 'label'=>'Address Line 2'),
			'suburb'						=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Suburb'),
			'postcode'					=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Postcode'),
		);
   
		//*** Get Valuelist from Database
		$valuelist_layout = $this->fm_db->getLayout('www_tutor');
		$this->data['valuelists']['title'] = $valuelist_layout->getValueListTwoFields('title');
		$this->data['valuelists']['instruments'] = $valuelist_layout->getValueListTwoFields('instrument');
		$this->data['valuelists']['payment_method'] = $valuelist_layout->getValueListTwoFields('tutors_payment_method');
		
		//***** Get Record
		$record = $this->fm_db->getRecordById('www_tutor', $this->session->userdata('tutor_rid'));
    if (!FileMaker::isError($record)) { 

			//*** Set Field into Variables for use in View
			foreach ($fields as $field_name=>$field_info) {
				$this->data['form'][$field_name]['value'] = $record->getField($field_name);
			}
			
			//*** Special fields
			$this->data['form']['instruments']['value'] = explode("\n", $record->getField('instruments'));

		}
		
		//***** Form has been submitted
		if ($this->uri->segment(3) == 'update' && isset($_POST['submit'])) {
						
			//*** Get Post Variables			
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['post'] == 1) {

					//Set into Array
					$field_value = $this->input->post($field_name);
					
					$post_field[$field_name] = (is_array($field_value)) ? $field_value : htmlentities(trim($_POST[$field_name]));
					
					//Set submitted values in form
					$this->data['form'][$field_name]['value'] = $post_field[$field_name];
					
				}
			}
			
			//*** Validate
			
			//Required Fields
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['required'] == 1 && $post_field[$field_name] == '') {
					$this->data['errors'][] = '<em>' . $field_info['label'] . '</em> is a required field. Please complete this field';
					$this->data['form']['errors'][$field_name] = 1;
				}
			}

			//Other instruments
			if (in_array('Other', $post_field['instruments']) && $post_field['instruments_other'] == '') {
				$this->data['errors'][] = 'You have selected <em>Other</em> for <em>' . $fields['instruments']['label'] . '</em>. Please specify what it is';
				$this->data['form']['errors']['instruments_other'] = 1;
			}

			//Biography
			$bio_string = str_replace("\n","\r\n", $post_field['biography']);
			if ($post_field['biography'] != '' && strlen($bio_string) > 1500) {
				$this->data['errors'][] = 'Your <em>' . $fields['biography']['label'] . '</em> must contain 1500 characters or less';
				$this->data['form']['errors']['biography'] = 1;
			}
			
			//*** Submit to Record
			if (!isset($this->data['errors'])) {
	
				//** Prepare standard fields
				foreach ($fields as $field_name => $field_info) {
					if ($field_info['setdb'] == 1) {
						$field_value = trim($this->data['form'][$field_name]['value']);
						$record->setField($field_name, $field_value);
						$logs[] = '[' . $field_name . '] ' . $field_value; //for tracking edits
					}
				}	
				
				//** Prepare special fields			
				
				//Instruments
				$record->setField('instruments', implode("\r\n", $this->data['form']['instruments']['value']));
				$logs[] = '[' . $field_name . '] ' . implode("; ", $this->data['form']['instruments']['value']); //for tracking edits
	
				//Other instruments - set as blank if not applicable
				if (!in_array('Other', $post_field['instruments'])) $record->setField('instruments_other', '');
										
				//** Commit record
				$result = $record->commit();
			
				//Save Failed
				if (FileMaker::isError($result)) {
					
					$this->data['errors'] = 'Unable to save successfully due to <em>' . $result->getMessage() . '</em>. Please contact us if the problem persists.';
				
				//Save OK
				} else {
						
					// Log this update into the edits log 
					$log_message = 'Updated personal information';
					if ($logs) $log_message = $log_message . "\r\n" . implode("\n", $logs);
					log_edits($this->session->userdata('tutor_id'), $this->session->userdata('tutor_id'), $log_message , 'Tutor Portal');

					// Set first login session to 0 so that the user isn't redirected here again
					if ($this->session->userdata('personal_info_first_time')) {
						$redirect_path = '/tutor/';
						$this->session->set_userdata('personal_info_first_time', 0);
					} else {
						$redirect_path = '/tutor/personal/';
					}
					
					//Redirect
					$this->session->set_flashdata('message', 'Successfully saved');
					redirect($redirect_path);
					die();
					
				}
				
			} //End submit data to database
			
		} //End Process Form
		
    $this->data['title'] = 'Personal Information';
		$this->data['navigation']['tutor/personal']['active'] = 1;
		$this->load->view('templates/tutor-header', $this->data);
		$this->load->view("tutor/personal", $this->data);
		$this->load->view('templates/footer', $this->data);

   }

  // ------------------------------------------------------------------------
	
	function Profile () {
    		
		//***** Get Record
		$record = $this->fm_db->getRecordById('www_tutor', $this->session->userdata('tutor_rid'));
    if (!FileMaker::isError($record)) { 
			$this->data['record' ] = $record;
		} else {
			$this->data['errors'] = 'Unable to display tutor information. Please the problem persists, please contact the School office.';
		}
			
    $this->data['title'] = 'Public Profile';
		$this->data['navigation']['tutor/profile']['active'] = 1;
		$this->load->view('templates/tutor-header', $this->data);
		$this->load->view("tutor/profile", $this->data);
		$this->load->view('templates/footer', $this->data);

	}

	// ------------------------------------------------------------------------

	function Report ($params) {
			
		//Get Placement
		$this->data['placement_rid'] = $this->uri->segment(3);
		$placement_record = $this->fm_db->getRecordById('www_placement_search', $this->data['placement_rid']);
		if (FileMaker::isError($placement_record)) {
			$access_error = 'Unable to find placement record';
		
		//Check access [1] tutor assigned to placement [2] placement year [3] placement semester match system settings
		} elseif (
				placement_tutor_match ($placement_record->getField('placement_id'), $this->session->userdata('tutor_id')) !== true or 
				$placement_record->getField('year') != $this->session->userdata('system_year') or
				$placement_record->getField('semester') != $this->session->userdata('system_semester')
			) {
				
			$access_error[] = 'You do not have access to this progress report';
		
		//Allow access to the page		
		} else {
			
			$progress_report_info = get_progress_report_info ($placement_record->getField('placement_id'), $this->session->userdata('tutor_id'));
			
			//Student Info
			$this->data['zid'] = $placement_record->getField('students::zid');
			$this->data['name'] = $placement_record->getField('students::first_name') . ' ' . $placement_record->getField('students::last_name');
			$this->data['semester'] = $placement_record->getField('year') . ', S' . $placement_record->getField('semester');
			$this->data['total_lesson'] = $placement_record->getField('c_total_lesson');
			
			//Form fields
			$fields = array(
				//Field => Submit to Database
				'technique'							=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Technique in scales and exercises'),
				'preparation'						=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Preparation of repertoire for assessment'), 
				'sight_reading'					=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Sight reading'),
				'attendance'						=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Attendance record and preparation for lessons'),
				'artistic_development'	=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Artistic development in the repertoire'),
				'technical_fluency'			=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Fluency of technical work'),
				'performance_fluency'		=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Fluency in the performance of repertoire pieces'),
				'attention'							=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Attention to musical details and style'),
				'follow_up'							=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Any recommendations for the Course Co-ordinator to follow up at the University'),
				'assess'								=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Assessment'),
				'repertoire_items'			=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Name the repertoire items the student has prepared'),
			);
			
			//*** Valuelists
			$this->data['valuelists']['assess'] = array(
				'HD'=>'High Distinction',
				'DN'=>'Distinction',
				'CR'=>'Credit',
				'PS'=>'Pass',
				'FL'=>'Fail',
			);

			//***** Get Record
			
			//*** No existing record, create a new one
			if ($progress_report_info['status'] == 'awaiting') {
				
				$record = $this->fm_db->createRecord('www_progress_report');
				$record->setField('tutor_id', $this->session->userdata('tutor_id'));
				$record->setField('placement_id', $placement_record->getField('placement_id'));			

			//*** Record existing, get the record
			} elseif ($progress_report_info['status'] == 'draft' or $progress_report_info['status'] == 'submitted') {
				
				$record = $this->fm_db->getRecordById('www_progress_report', $progress_report_info['rid']);
				if (FileMaker::isError($record)) { 
					$access_error = 'Unable to load this report';
				} else {
					//Set Field into Variables for use in View
					foreach ($fields as $field_name=>$field_info) {
						$this->data['form'][$field_name]['value'] = $record->getField($field_name);
					}
					
				}
			
			} elseif ($progress_report_info['status'] == 'closed') {
				
				$access_error[] = 'The deadline for progress report submissions have closed';

			}
		
			//***** Form has been submitted
			
			//Only allow to submit if the report is still open and if the correct buttons are clicked
			if (($progress_report_info['status'] == 'awaiting' or $progress_report_info['status'] == 'draft') && $this->uri->segment(4) == 'update' && (isset($_POST['submit']) or isset($_POST['save']))) {
							
				//*** Get Post Variables			
				foreach ($fields as $field_name => $field_info) {
					if ($field_info['post'] == 1) {
						
						//Set into Array
						$post_field[$field_name] = htmlentities(trim($_POST[$field_name]));
						
						//Set submitted values in form
						$this->data['form'][$field_name]['value'] = $post_field[$field_name];
						
					}
				}
				
				//*** Validate - only validate if the form is 'submitted', not saved
				
				if (isset($_POST['submit'])) {
					//Required Fields
					foreach ($fields as $field_name => $field_info) {
						if ($field_info['required'] == 1 && $post_field[$field_name] == '') {
							$this->data['errors'][] = '<em>' . $field_info['label'] . '</em> is a required field. Please complete this field';
							$this->data['form']['errors'][$field_name] = 1;
						}
					}
				}
				
				//*** Submit to Record
				if (!isset($this->data['errors'])) {
		
					//** Prepare standard fields
					foreach ($fields as $field_name => $field_info) {
						if ($field_info['setdb'] == 1) {
							$field_value = trim($this->data['form'][$field_name]['value']);
							$record->setField($field_name, $field_value);
							$logs[] = '[' . $field_name . '] ' . $field_value; //for tracking edits
						}
					}				
					
					//Flag as submitted
					if (isset($_POST['submit'])) {
						$record->setField('submitted', 1);
						$progress_report_info['status'] = 'submitted';
						$log_message = 'Submitted progress report';

					} else {
						$log_message = 'Update progress report';					
					}

					//* Commit record
					$result = $record->commit();
				
					//Save Failed
					if (FileMaker::isError($result)) {
						
						$this->data['errors'] = 'Unable to save successfully due to <em>' . $result->getMessage() . '</em>. Please contact us if the problem persists.';
					
					//Save OK
					} else {
						
						// Log this update into the edits log 
						if ($logs) $log_message = $log_message . "\r\n" . implode("\n", $logs);
						log_edits($record->getField('progress_id'), $this->session->userdata('tutor_id'), $log_message , 'Tutor Portal');
						
						//Redirect
						$this->session->set_flashdata('message', 'Successfully saved');
						redirect('/tutor/report/' . $this->data['placement_rid']); //redirect in order for flash data to appear
						
					}
					
				} //End submit data to database
				
			} //End Process Form

		} //end allow access
			
		$this->data['title'] = 'Student Progress Report';	
		$this->data['navigation']['tutor']['active'] = 1;
		$this->load->view('templates/tutor-header.php', $this->data);	
		if ($access_error) {
			$this->data['access_error'] = $access_error;
			$this->load->view("tutor/no-access.php", $this->data);
		} elseif ($progress_report_info['status'] == 'awaiting' or $progress_report_info['status'] == 'draft') {
			$this->load->view("tutor/report.php", $this->data);
		} elseif ($progress_report_info['status'] == 'submitted') {
			$this->load->view("tutor/report-view.php", $this->data);
		}
		$this->load->view('templates/footer.php', $this->data);

	}

	// ------------------------------------------------------------------------

	function Error ($params) {
		
		$this->data['title'] = 'Error Occurred';
		$this->data['error'] = $params[0];
		
		$this->load->view('templates/tutor-header', $this->data);
		$this->load->view('error', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}

}