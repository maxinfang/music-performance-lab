<?php

class Student extends Secure_Controller {
//class Student extends CI_Controller {
	
	function __construct() {
		
		parent::__construct();
		
		//***** Navigation
		//Only see the navigation once they've gone through and completed the all the forms
		if (tutor_selection_status() == 'selected') {
			$this->data['navigation']['student'] = array('name'=>'Home', 'weight'=>1);
			$this->data['navigation']['student/personal'] = array('name'=>'Personal Information', 'weight'=>2);
			$this->data['navigation']['student/tutor'] = array('name'=>'Tutor', 'weight'=>3);
			$this->data['navigation']['student/lesson'] = array('name'=>'Lesson Log', 'weight'=>4);
			$this->data['navigation']['student/prac-exam'] = array('name'=>'Practical Examination', 'weight'=>5);
		}
//		if ($this->session->userdata('system_book_exam_open') == 1) $this->data['navigation']['student/book-exam'] = array('name'=>'Book Exam', 'weight'=>6);
		//Show booking exam navigation when booking starts - still show even if deadline ends
		if (valid_deadline($this->session->userdata('system_book_exam_start')) == false) $this->data['navigation']['student/book-exam'] = array('name'=>'Book Exam', 'weight'=>6);
		
	}

	// ------------------------------------------------------------------------

	public function _remap($method, $params = array()) {
		
		/* User type
		 * Redirect if user do not have access to this section
		 */
		if ($this->session->userdata('user_type') != 'student') {
			redirect('' . user_default_homepage());
			die();

		/* Personal Info
		 * Redirect user to update their personal information
		 * if they logged it prior to the system opening for this round of cohorts
		 */
		} elseif ($this->session->userdata('user_first_time') == 1) { 
			if ($method == 'personal') {
				$this->$method($params);
			} else {
				redirect( user_default_homepage() . '/personal/');
				die();
			}

		/* Study Info
		 * Redirect user to enter their study information
		 */
 		} elseif (redirect_to_course_info() == true) {
			if ($method == 'course_form') {
				$this->$method($params);
			} else {
				redirect( user_default_homepage() . '/course_form/');
				die();
			}

		/* Tutor selection
		 * Redirect user to select a tutor if student don't have a tutor assigned
		 */
		} elseif (tutor_selection_status() == 'awaiting') {
			if ($method == 'tutor_form' or $method == 'tutor_list' or $method == 'tutor_profile') {
				$this->$method($params);
			} else {
				redirect(user_default_homepage() . '/tutor-form/');
				die();
			}

		/* Tutor selection pending
		 * Redirect user to pending message
		 */
		} elseif (tutor_selection_status() == 'pending') {
			if ($method == 'tutor_pending') {
				$this->$method($params);
			} else {
				redirect(user_default_homepage() . '/tutor-pending/');
				die();
			}

		//Display as requested
		} elseif (method_exists($this, $method)) {
				return call_user_func_array(array($this, $method), $params);

		//404 error
		} else {
			show_404();
		}
		
	}

	// ------------------------------------------------------------------------

	function Index() {
		
		$this->data['database'] = $this->fm_db;
		
		//Get content
		$setting_record = $this->fm_db->getRecordById('www_setting', $this->session->userdata('system_rid'));
		$this->data['content'] = decode_html($setting_record->getField('web_content_student_home'));
		
		//Views
		$this->data['title'] = 'Home';		
		$this->data['navigation']['student']['active'] = 1;
		$this->load->view('templates/header.php', $this->data);
		$this->load->view('student/index.php', $this->data);
		$this->load->view('templates/footer.php', $this->data);

	}

	// ------------------------------------------------------------------------

	function Personal ($params) {
		
		//***** Set Variables
				
		/* DB Fields
		 * List the fields that we want from the database and indicate which ones
		 * we want to submit to the database
		 */
		$fields = array(
			//Field => Submit to Database
			'zid'=>array('post'=>'0', 'setdb'=>'0', 'required'=>'0', 'label'=>'zID'),
			'first_name'=>array('post'=>'0', 'setdb'=>'0', 'required'=>'0', 'label'=>'First Name'),
			'last_name'=>array('post'=>'0', 'setdb'=>'0', 'required'=>'0', 'label'=>'Last Name'),
			'degree'=>array('post'=>'0', 'setdb'=>'0', 'required'=>'0', 'label'=>'Degree'),
			'email'=>array('post'=>'0', 'setdb'=>'0', 'required'=>'0', 'label'=>'E-mail address'),
			'phone_home'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Home phone'),
			'phone_mobile'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Mobile contact'),
			'address_line_1'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Address Line 1'),
			'address_line_2'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'0', 'label'=>'Address Line 2'),
			'suburb'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Suburb'),
			'postcode'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Postcode'),
	
			/* Grab the post value from the post, but don't submit to the db
			 * because the value should only be submitted once - if the record doesn't have a value already
			 */
			'instrument'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Instrument'),
			 
		);
		
		// Get Valuelist from Database
		$valuelist_layout = $this->fm_db->getLayout('Students');
		$this->data['valuelists']['instrument'] = $valuelist_layout->getValueListTwoFields('instrument');
					
		//***** Get Record
		$record = $this->fm_db->getRecordById('Students', $this->session->userdata('student_rid'));
		
		//***** Find Record
		if (!FileMaker::isError($record)) {
						
			//*** Set Field into Variables for use in View
			foreach ($fields as $field_name=>$field_info) {
				$this->data['form'][$field_name]['value'] = $record->getField($field_name);
			}
	
			//*** Set editable fields
			//If instrument already has value then don't allow editing
			if ($this->data['form']['instrument']['value']) {
				$fields['instrument'] = array('post'=>'0', 'setdb'=>'0', 'required'=>'0', 'label'=>'Instrument');
				$this->data['form']['instrument']['edit'] = 0;
			}
			
		}
		
		//***** Form has been submitted
		if ($this->uri->segment(3) == 'update' && isset($_POST['submit'])) {
						
			//*** Get Post Variables			
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['post'] == 1) {
					
					//Set into Array
					$post_field[$field_name] = htmlentities(trim($_POST[$field_name]));
					
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
				//N/A
										
				//* Commit record
				$result = $record->commit();
			
				//Save Failed
				if (FileMaker::isError($result)) {
					
					$this->data['errors'] = 'Unable to save successfully due to <em>' . $result->getMessage() . '</em>. Please contact us if the problem persists.';
				
				//Save OK
				} else {
						
					// Log this update into the edits log 
					$log_message = 'Updated personal information';
					if ($logs) $log_message = $log_message . "\r\n" . implode("\n", $logs);
					log_edits($this->session->userdata('student_id'), $this->session->userdata('user_zid'), $log_message , 'Student Portal');

					// Set first login session to 0 so that the user isn't redirected here again
					if ($this->session->userdata('user_first_time')) $this->session->set_userdata('user_first_time', 0);
				
					//Redirect
					$this->session->set_flashdata('message', 'Successfully saved');
					redirect('/student/personal/');
					die();
					
				}
				
			} //End submit data to database
			
		} //End Process Form
		

		$this->data['title'] = 'Personal Information';		
		$this->data['navigation']['student/personal']['active'] = 1;
		$this->load->view('templates/header.php', $this->data);
		$this->load->view('student/personal.php', $this->data);
		$this->load->view('templates/footer.php', $this->data);

	}

	// ------------------------------------------------------------------------

	function Course_form ($arg) {
		
		//***** Check Access
		if (redirect_to_course_info == false) {
			redirect('student');
			die();
		}

		//***** Set Variables
		/* DB Fields
		 * List the fields that we want from Sugar and indicate which ones
		 * we want to submit to Sugar
		 */
		$fields = array(	
			'course_code'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Course Code'),
		);
		
		//*** Get Valuelist from Sugar
		// Get Valuelist from Database
		$valuelist_layout = $this->fm_db->getLayout('www_placement_course');
		$this->data['valuelists']['course_code'] = $valuelist_layout->getValueListTwoFields('course_code');
			
		//***** Form Submitted
		if ($this->uri->segment(3) == 'update' && isset($_POST['submit'])) {

			//*** Get Post Variables			
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['post'] == 1) {
					//Set into Array
					$post_field[$field_name] = $this->input->post($field_name);
					//Set value in form
					$this->data['form'][$field_name]['value'] = $post_field[$field_name];
				}
			}
			
			//*** Validate
			//Required Fields
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['required'] == 1 && ($post_field[$field_name] == '' or $post_field[$field_name] == '_empty_')) {
					$this->data['errors'][] = '<em>' . $field_info['label'] . '</em> is a required field. Please complete this field';
					$this->data['form']['errors'][$field_name] = 1;
				}
			}
			
			//*** Submit to Record
			if (!isset($this->data['errors'])) {
				
				//** Create new record
				$new_record = $this->fm_db->newAddCommand('www_placement_course');
				$new_record->setField('student_id', $this->session->userdata('student_id')); //Set the relationship - Link placement to the student record
				$new_record->setField('year', $this->session->userdata('system_year'));
				$new_record->setField('semester', $this->session->userdata('system_semester'));
				$logs[] = '[year] ' . $this->session->userdata('system_year'); //for tracking edits
				$logs[] = '[semester] ' . $this->session->userdata('system_semester'); //for tracking edits
		
				//** Set Fields to Record
				foreach ($fields as $field_name => $field_info) {
					if ($field_info['setdb'] == 1) {
						$field_value = trim($this->data['form'][$field_name]['value']);
						$new_record->setField($field_name, $field_value);
						$logs[] = '[' . $field_name . '] ' . $field_value; //for tracking edits
					}
				}
				
				$new_result = $new_record->execute();
				
				//Error creating new record
				if (FileMaker::isError($new_result)) {
					
					$errors[] = $new_result->getMessage();
					$this->data['errors'] = 'Unable to save successfully. Please contact us if the problem persists.';
					
				} else {
					
					$records = $new_result->getRecords();
					$record = $records[0];				
					
					//Set placement details into session so that user won't return to this page
					$this->session->set_userdata('placement_id', $record->getField('placement_id'));
					$this->session->set_userdata('placement_rid', $record->getRecordId());
					$this->session->set_userdata('placement', '1');
					
					// Log this update into the edits log 
					$log_message = 'Created placement record with course information';
					array_unshift($logs, '[placement_rid] ' . $record->getRecordId()); //log placement rid
					if ($logs) $log_message = $log_message . "\r\n" . implode("\n", $logs);

					// Log this update into the edits log 
					log_edits($this->session->userdata('student_id'), $this->session->userdata('user_zid'), $log_message, 'Student Portal');
				
					//Redirect
					redirect('/student/tutor-form/');
					die();
	
				} //End submit changes
				
			} //End No Errors
				
		} //End Process Form

		$this->data['title'] = 'Course Information';		
		$this->load->view('templates/header.php', $this->data);
		$this->load->view("student/course-form.php", $this->data);
		$this->load->view('templates/footer.php', $this->data);
			
	}

	// ------------------------------------------------------------------------

	function Tutor_form ($arg) {		

		//***** Check Access
		if (tutor_selection_status() != 'awaiting') {
			redirect('student');
			die();
		}

		//***** Set Variables
				
		/* DB Fields
		 * List the fields that we want from the database and indicate which ones
		 * we want to submit to the database
		 */
		$fields = array(
			//Field => Submit to Database

			'tutor_id'=>array('post'=>'1', 'setdb'=>'0', 'required'=>'0', 'label'=>'New tutor request'), //don't set to database - will do manually
			'new_tutor_request'=>array('post'=>'1', 'setdb'=>'0', 'required'=>'0', 'label'=>'New tutor request'), //don't set to database
			'first_name'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'First Name'),
			'last_name'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Last Name'),
			'instruments'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Instruments'),
			'email'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'E-mail address'),
			'phone_home'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Home phone'),
			'phone_mobile'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Mobile contact'),
			'address_line_1'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Address Line 1'),
			'address_line_2'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'0', 'label'=>'Address Line 2'),
			'suburb'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Suburb'),
			'postcode'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Postcode'),
					 
		);
		
		// Get Valuelist from Database
		$valuelist_layout = $this->fm_db->getLayout('www_tutor_list');
		$this->data['valuelists']['instruments'] = $valuelist_layout->getValueListTwoFields('instrument');

		//***** Get Tutor information
		$find = $this->fm_db->newFindCommand('www_tutor_list');
		$find->addFindCriterion('active', '==1');
		$find->addFindCriterion('public_list', '==1');
		$find->addSortRule('last_name', 1, FILEMAKER_SORT_ASCEND);
		$find->addSortRule('first_name', 2, FILEMAKER_SORT_ASCEND);
		$result = $find->execute();
		
		//*** Get Record Failed
		if (FileMaker::isError($result)) {
			//Unable to find tutors			
		//*** Get Record OK
		} else {

			$tutor_records = $result->getRecords();
			
			//*** Set tutor information into array
			foreach ($tutor_records as $tutor_record) {
				
				$tutor_rid = $tutor_record->getRecordId();
				$tutors[$tutor_rid]['id'] = $tutor_record->getField('tutor_id');
				$tutors[$tutor_rid]['first_name'] = $tutor_record->getField('first_name');
				$tutors[$tutor_rid]['last_name'] = $tutor_record->getField('last_name');
				$tutors[$tutor_rid]['suburb'] = $tutor_record->getField('suburb');
				$tutors[$tutor_rid]['email'] = $tutor_record->getField('email');
				$tutors[$tutor_rid]['postcode'] = $tutor_record->getField('postcode');
				
			}
			
			$this->data['tutors'] = $tutors;
	
		}
		
		//***** Form Submitted
		
		//Grab from complete tutor list
		if ($this->uri->segment(3) == 'update' && isset($_POST['select'])) {
			$this->data['form']['tutor_id']['value'] = $this->input->post('tutor_id');
			
		//Grab from the form
		} elseif ($this->uri->segment(3) == 'update' && isset($_POST['submit'])) {

			//*** Get Post Variables			
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['post'] == 1) {
					//Set into Array					
					$post_field[$field_name] = $this->input->post($field_name);
					//Set value in form
					$this->data['form'][$field_name]['value'] = $post_field[$field_name];
				}
			}
						
			//*** Validate
			
			//Required Fields for new tutor request
			if ($post_field['new_tutor_request'] == "1") {
				foreach ($fields as $field_name => $field_info) {
					if ($field_info['required'] == 1 && ($post_field[$field_name] == '' or $post_field[$field_name] == '_empty_')) {
						$this->data['errors'][] = '<em>' . $field_info['label'] . '</em> is a required field. Please complete this field';
						$this->data['form']['errors'][$field_name] = 1;
					}
				}

			//Required field for non-new tutor request
			} elseif ($post_field['tutor_id'] == '') {
				$this->data['errors'][] = 'Please select a tutor from the drop down list or check request new tutor';				
				$this->data['form']['errors']['tutor_id'] = 1;
			}

			//*** No errors
			if (!isset($this->data['errors'])) {
				
				//** Grab placement record
				$record = $this->fm_db->getRecordById('www_placement_tutor_selection', $this->session->userdata('placement_rid'));
			
				//Error creating new record
				if (FileMaker::isError($record)) {
					$this->data['errors'] = 'Unable to identify placement';				
				//Found record
				} else {
					
					//submit new tutor info
					if ($post_field['new_tutor_request'] == "1") {
						
						//** Set Fields to Record
						foreach ($fields as $field_name => $field_info) {
							if ($field_info['setdb'] == 1) {
								$tutor_field_value = trim($post_field[$field_name]);
								if (is_array($post_field[$field_name])) $tutor_field_value = implode(', ', $post_field[$field_name]);
								$new_tutor_info[] = $field_info['label'] . ': ' . $tutor_field_value;
							} 
						}

						$record->setField('new_tutor_request', $post_field['new_tutor_request']);
						$record->setField('new_tutor_approval', '');
						$record->setField('new_tutor_info', implode("\n", $new_tutor_info));
						$logs[] = '[new_tutor_request] ' . $post_field['new_tutor_request'];
						$logs[] = '[new_tutor_info] ' . implode("\n", $new_tutor_info);
						
					//OR select existing tutor
					} else {
						$record->setField('placement_tutor::tutor_id.0', $post_field['tutor_id']);
						$record->setField('placement_tutor::primary.0', 1);
						$logs[] = '[tutor_id] ' . $post_field['tutor_id'];
						$logs[] = '[tutor_primary] 1';

					}
		
					$result = $record->commit();
					if (FileMaker::isError($result)) {
						$this->data['errors'] = 'Unable to save successfully';
		
					} else {
												
						
						if ($post_field['new_tutor_request'] == "1") {
												
							$redirect = 'student/tutor-pending';
							$log_message = 'Requested a new tutor';						
							$this->session->set_userdata('tutor_approval', '');
							$this->session->set_userdata('tutor_selected', '0');
							$this->session->set_userdata('tutor_new_request', '1');

							//Notify school of request
							$mail_body[] = "A student have submitted a new tutor information via the Music Performance Lab system. Please review and action.";
							$mail_body[] = "Student zID: " . $this->session->userdata('user_zid');
							$mail_body[] = "First Name: " . $this->session->userdata('user_first_name');
							$mail_body[] = "Last Name: " . $this->session->userdata('user_last_name');
							$mail_body[] = "-----";
							$mail_body[] = "New tutor information";
							$mail_body[] = "-----";
							foreach ($new_tutor_info as $new_tutor_line) {
								$mail_body[] = $new_tutor_line;
							}
							$mail_body[] = "CRICOS Provider: 00098G";
							$mail_body[] = "(This is an automatically generated e-mail via the UNSW School of the Arts &amp; Media - Music Lab Performance System)";
						
							$mail_info = array(
								'to'					=> 'sam@unsw.edu.au',
								'from'				=> 'no-reply@unsw.edu.au',
								'subject'			=> 'New tutor request for Music Performance Lab',
								'body_plain'	=> implode("\n", $mail_body),
								'body_html'		=> '<body><p>' . implode("</p><p>", $mail_body) . '</p></body>',
							);

							$mail_result = send_email($mail_info);

						} else {
							
							$redirect = 'student';
							$log_message = 'Tutor selected';
							$this->session->set_userdata('tutor_selected', '1');
							$this->session->set_userdata('tutor_new_request', '0');
							$this->session->set_userdata('tutor_id', $post_field['tutor_id']);
						
						}
						
						if ($logs) $log_message = $log_message . "\r\n" . implode("\n", $logs);

						// Log this update into the edits log 
						log_edits($this->session->userdata('placement_id'), $this->session->userdata('user_zid'), $log_message, 'Student Portal');
						
						//Redirect
						redirect($redirect);
						die();
						
					} //End commit changes
					
				} //End found placement record
				
			} //End No Errors
				
		} //End Process Form

		$this->data['title'] = 'Select a Tutor';		
		$this->load->view('templates/header.php', $this->data);
		$this->load->view("student/tutor-form.php", $this->data);
		$this->load->view('templates/footer.php', $this->data);
			
	}
	
	// ------------------------------------------------------------------------

	function Tutor_list ($arg) {		
					
		
		//***** Check Access
		if (tutor_selection_status() != 'awaiting') {
			redirect('student');
			die();
		}

		//***** Sort settings
		$this->data['sort']['field'] = $this->uri->segment(3);
		$this->data['sort']['order'] = $this->uri->segment(4);

		//Sort by
		switch ($this->uri->segment(3)) {
			
			case 'name':
				$sort_field = 'c_full_name';
				break;
			case 'email':
				$sort_field = 'email';
				break;
			case 'instruments':
				$sort_field = 'instruments';
				break;
			case 'suburb':
				$sort_field = 'suburb';
				break;
			case 'postcode':
				$sort_field = 'postcode';
				break;
			default	:
				$this->data['sort']['field'] = 'name';
				$sort_field = 'c_full_name';		
				break;
		}
		
		//Sort order
		switch ($this->uri->segment(4)) {
			case 'asc';
				$sort_order = FILEMAKER_SORT_ASCEND;
				break;
			case 'desc';
				$sort_order = FILEMAKER_SORT_DESCEND;
				break;
			default;
				$this->data['sort']['order'] = 'asc';
				$sort_order = FILEMAKER_SORT_ASCEND;
				break;
		}
		
		
		//***** Get Tutor information
		$find = $this->fm_db->newFindCommand('www_tutor_list');
		$find->addFindCriterion('active', '==1');
		$find->addFindCriterion('public_list', '==1');

		//***** Set sort order
		$find->addSortRule($sort_field, 1, $sort_order);
//		$find->addSortRule('first_name', 2, FILEMAKER_SORT_ASCEND);
		$result = $find->execute();
		//*** Get Record Failed
		if (!FileMaker::isError($result)) {
			$this->data['records'] = $result->getRecords();
		}
		
		$this->data['title'] = 'List of tutors';		
		$this->load->view('templates/header.php', $this->data);
		$this->load->view("student/tutor-list.php", $this->data);
		$this->load->view('templates/footer.php', $this->data);
			
	}

	// ------------------------------------------------------------------------

	function Tutor_profile ($arg) {		
					
		
		//***** Check Access
		if (tutor_selection_status() != 'awaiting') {
			redirect('student');
			die();
		}

		$page_title = 'Error';
		
		//Record ID
		$record_id = $this->uri->segment(3);
		if ($record_id) {

			//***** Get Record
			$record = $this->fm_db->getRecordById('www_tutor', $record_id);
			
			//Not found
			if (FileMaker::isError($record)) { 
				$this->data['errors'] = 'Unable to identify the selected tutor\'s information. If the problem persists, please contact the School office.';
			//Found
			} else {	
				//Is public and active?
				if ($record->getField('public_list') == 1 && $record->getField('active') == 1) {
					$this->data['record' ] = $record;
				//Access denied
				} else {
					$this->data['errors'] = 'Unable to display the selected tutor\'s information. If the problem persists, please contact the School office.';
				}
				$page_title = $record->getField('first_name') . ' ' . $record->getField('last_name');
			}

		} else {
			$this->data['errors'] = 'Unable to determine tutor. If the problem persists, please contact the School office.';
		}
			
		$this->data['title'] = $page_title;		
		$this->load->view('templates/header.php', $this->data);
		$this->load->view("student/tutor-profile.php", $this->data);
		$this->load->view('templates/footer.php', $this->data);
			
	}

	// ------------------------------------------------------------------------

	function Tutor_pending () {
		
		$this->data['title'] = 'Pending request for new tutor';

		$this->data['navigation']['tutor']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('student/tutor-pending', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}

	// ------------------------------------------------------------------------

	function Tutor () {
		
		//**** Get tutor records
		$find = $this->fm_db->newFindCommand('Tutors');
		$find->addFindCriterion('tutor_id', "==" . $this->session->userdata('tutor_id'));
		$find->setRange(0,1); //just the latest one
		$result = $find->execute();
		if (FileMaker::isError($result)) {
			$errors[] = 'Unable to find the tutors selected';
		} else {
			$records = $result->getRecords();
			$record = $records[0];
			$this->data['record'] = $record;
		}
	
		$this->data['title'] = 'Tutor Information';
		$this->data['navigation']['student/tutor']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('student/tutor', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}

	// ------------------------------------------------------------------------

	function Lesson () {
			
		//***** Check Access
		//if they haven't completed the lesson log form then rediect them to the form
		if ($this->session->userdata('lesson_log_open') == 1) {
			redirect('student/lesson-form');
			die();
		}

		//***** Get Student's study level
		$placement_record = $this->fm_db->getRecordById('www_placement_lesson', $this->session->userdata('placement_rid'));
		$this->data['study'] = get_study_level_info($placement_record->getField('course_code'));

		//***** Get lesson information based on placement information
		$find = $this->fm_db->newFindCommand('www_lesson');
		$find->addFindCriterion('placement_id', "==" . $this->session->userdata('placement_id'));
		$find->addSortRule('date', 1, FILEMAKER_SORT_ASCEND);
		$find->addSortRule('created', 2, FILEMAKER_SORT_ASCEND);
		$result = $find->execute();
		if (!FileMaker::isError($result)) {
			$x = 0;
			foreach ($result->getRecords() as $record) {
				//Set into Array
				$row_array[$x]['lesson_id'] = $record->getRecordId();
				$row_array[$x]['date'] = date_mdy_dmy($record->getField('date'));
				$row_array[$x]['length'] = $record->getField('length');
				$row_array[$x]['tutor_id'] = $record->getField('tutor_id');
				$row_array[$x]['tutor_name'] = $record->getField('tutor_lesson::c_full_name');
				$x ++;				
			} //end each row
		}
	
		//***** Page view
		//pass data
		$this->data['form']['row_array'] = $row_array;
				
		$this->data['title'] = 'Lesson Log';
		$this->data['navigation']['student/lesson']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('student/lesson', $this->data); // show the view only if deadline has passed
		$this->load->view('templates/footer', $this->data);
		
		
	}

	// ------------------------------------------------------------------------

	function Lesson_form () {
		
		//***** Check Access
		//if they don't have access to edit prac form then rediect them to view it only
		if ($this->session->userdata('lesson_log_open') != 1) {
			redirect('student/lesson');
			die();
		}

		//***** Get Student's study level
		$placement_record = $this->fm_db->getRecordById('www_placement_lesson', $this->session->userdata('placement_rid'));
		$course_code = $placement_record->getField('course_code');
		$this->data['study'] = get_study_level_info($placement_record->getField('course_code'));
				
		//***** Get Lesson information based on placement ID
		$find = $this->fm_db->newFindCommand('www_lesson');
		$find->addFindCriterion('placement_id', "==" . $this->session->userdata('placement_id'));
		$find->addSortRule('date', 1, FILEMAKER_SORT_ASCEND);
		$find->addSortRule('created', 2, FILEMAKER_SORT_ASCEND);
		$result = $find->execute();
		if (FileMaker::isError($result)) {
			$errors[] = 'No lessons were previously logged';
			$total_row = 0;

		//***** Form not submitted - Grab data from database
		} else {

			$total_row = $result->getFoundSetCount();
			$records = $result->getRecords();
			$x = 0;
			
		}
	
		//***** Get related Tutor List
		/* unable to get this to work:
				$valuelist_layout = $this->fm_db->getLayout('www_placement_lesson');
				$this->data['valuelists']['tutors'] = $valuelist_layout->getValueListTwoFields('placement_tutors', $this->session->userdata('placement_rid'));
			 so am using a search query instead
		*/
		$find = $this->fm_db->newFindCommand('www_placement_tutor');
		$find->addFindCriterion('placement_id', "==" . $this->session->userdata('placement_id'));
		$find->addSortRule('primary', 1, FILEMAKER_SORT_ASCEND);
		$find->addSortRule('tutors::c_full_name', 2, FILEMAKER_SORT_ASCEND);
		$result = $find->execute();
		if (FileMaker::isError($result)) {
			$errors[] = 'Unable to find assigned tutors';
		//***** Form not submitted - Grab data from database
		} else {
			foreach ($result->getRecords() as $tutor_record) {
				$this->data['valuelists']['tutors'][$tutor_record->getField('tutors::c_full_name')] = $tutor_record->getField('tutor_id');
			}
		}
		
		//For submitting the record later
		$tutor_array_by_id = array_flip($this->data['valuelists']['tutors']);
			
		//***** Form Submitted
		if ($this->uri->segment(3) == 'update' && isset($_POST['submit'])) {
			
			//*** Get Post Variables
			$total_row = $this->input->post('next_row');
			$current_rows = $this->input->post('current_rows');
			$total_minutes = 0;
			for ($x = 0; $x < $total_row; $x ++) {
				
				//Set into Array
				if ($this->input->post('lesson_'. $x)) {
					$row_array[$x]['lesson_id'] = $this->input->post('lesson_'. $x);
					$row_array[$x]['date'] = $this->input->post('date_'. $x); //convert the date format
					$row_array[$x]['length'] = $this->input->post('length_'. $x);
					$row_array[$x]['tutor_id'] = $this->input->post('tutor_id_' . $x);
					$row_array[$x]['tutor_name'] = $tutor_array_by_id[$row_array[$x]['tutor_id']];
					
					$keep_records[] = $row_array[$x]['lesson_id'];
					$total_minutes = $total_minutes + $row_array[$x]['length'];
				}
				
			} //end each row
			
			//*** Validate
			//must have at least one lesson added
			if (count($row_array) == 0) {
				$this->data['errors'][] = "Please enter at least one lesson";				
			}

			//value on date field
			if ($this->input->post('date_new')) {
				$this->data['errors'][] = "A date has been selected. Please either click on the add button to add it to the list or delete it before saving";				
			}

			//value on length field
			if ($this->input->post('length_new')) {
				$this->data['errors'][] = "You have entered the length of the lesson. Please either click on the add button to add it to the list or delete it before saving";				
			}

			//Number of hours do not exceed limit
			if ($_POST['submit'] == 'Submit' && $total_minutes != $this->data['study']['minutes']) {
				$this->data['errors'][] = 'The total hours of the lessons entered exceeds or does not meet the requirement of <strong>' . $this->data['study']['hours'] . '</strong> hours (<strong>' . $this->data['study']['minutes'] . '</strong> minutes)';
			}

			//*** No errors
			if (!isset($this->data['errors'])) {
				
				//Delete old lessons
				if ($records) {
					foreach ($records as $record) {
						if (!in_array($record->getRecordId(), $keep_records)) {
							$logs[] = '[deleted-lesson::lesson_rid] '. $record->getRecordId();
							$logs[] = '[deleted-lesson::tutor_id] '. $record->getField('tutor_id');
							$logs[] = '[deleted-lesson::date] '. date_mdy_dmy($record->getField('date'));
							$logs[] = '[deleted-lesson::length] '. $record->getField('length');	
							$record->delete();							
						}
					}
				}
				
				
				//Add new ones
				foreach ($row_array as $row_number=>$row_info) {
					if ($row_info['lesson_id'] == 'new') {

						//** Create new record
						$new_record = $this->fm_db->newAddCommand('www_lesson');
						$new_record->setField('placement_id', $this->session->userdata('placement_id')); //Set the relationship - Link placement to the student record
						$new_record->setField('tutor_id', $row_info['tutor_id']); //convert the date format back to FileMaker's format of ddmmyy
						$new_record->setField('date', date_dmy_mdy($row_info['date'])); //convert the date format back to FileMaker's format of ddmmyy
						$new_record->setField('length', $row_info['length']);						
						$new_result = $new_record->execute();
						
						//Error creating new record
						if (FileMaker::isError($new_result)) {
							$errors[] = 'Unable to save your changes';
						} else {
							//Set the new id into the array - so it doesn't get created again if user submits the form again
							$new_records = $new_result->getRecords();
							$new_record = $new_records[0];
							$row_array[$row_number]['lesson_id'] = $new_record->getRecordId();
							$logs[] = '[add-lesson::lesson_rid] ' . $new_record->getRecordId();
							$logs[] = '[add-lesson::tutor_id] ' . $row_info['tutor_id'];
							$logs[] = '[add-lesson::date] ' . date_dmy_mdy($row_info['date']);
							$logs[] = '[add-lesson::length] ' . $row_info['length'];								

							
						}
		
					}
				}
				
				$row_array = sort_lessons($row_array);

				//Set as submitted
				if ($_POST['submit'] == 'Submit') {
					$placement_record->setField('lesson_log_submitted', 1);
					$placement_record->setField('lesson_log_unlock', 0);
					$result = $placement_record->commit();
					
					$logs[] = '[lesson_log_submitted] 1';
					$logs[] = '[lesson_log_unlock] 0';
					
					//Set session so user won't come back to this page
					$this->session->set_userdata('lesson_log_unlock', 0);
					$this->session->set_userdata('lesson_log_submitted', 1);
					$this->session->set_userdata('lesson_log_open', 0);
					$redirect = '/lesson/';	
				} else {
					$redirect = '/lesson-form/';
				}

				// Log this update into the edits log 
				$log_message = 'Log lesson';
				if ($logs) $log_message = $log_message . "\r\n" . implode("\n", $logs);
				log_edits($this->session->userdata('placement_id'), $this->session->userdata('user_zid'), $log_message, 'Student Portal');

				$this->session->set_flashdata('message', 'Save successful. Lessons have been reordered by the lesson date.');
				redirect( user_default_homepage() . $redirect);
				die();
				
			} //End No Errors


		//Grab values from Database
		} elseif ($records) {
			
			foreach ($records as $record) {	
			
				//Set into Array
				$row_array[$x]['lesson_id'] = $record->getRecordId();
				$row_array[$x]['date'] = date_mdy_dmy($record->getField('date'));
				$row_array[$x]['length'] = $record->getField('length');
				$row_array[$x]['tutor_id'] = $record->getField('tutor_id');
				$row_array[$x]['tutor_name'] = $record->getField('tutor_lesson::c_full_name');
				$x ++;
				
				$current_rows_array[] = $record->getRecordId();
				
			} //end each row
			
			$current_rows = implode(';', $current_rows_array);
			
		}			
				
		//***** Page view
		//pass data
		$this->data['form']['current_rows'] = $current_rows;
		$this->data['form']['total_row'] = $total_row;
		$this->data['form']['row_array'] = $row_array;
		
		$this->data['title'] = 'Lesson Log';
		$this->data['navigation']['student/lesson']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('student/lesson-form', $this->data); // show the view only if deadline has passed
		$this->load->view('templates/footer', $this->data);
		
		
	}

	// ------------------------------------------------------------------------

	function Prac_exam_form() {
		
		//***** Check Access
		//if they don't have access to edit prac form then rediect them to view it only
		if ($this->session->userdata('prac_exam_open') != 1) {
			redirect('student/prac-exam');
			die();
		}

		/* DB Fields
		 * List the fields that we want from the database and indicate which ones
		 * we want to submit to the database
		 */
		$fields = array(
			//Field => Submit to Database
			'ensemble'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Ensemble'),
			'tech_title'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Title for Technical Work'), //don't set to database - will do manually
			'tech_composer'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Composer for Technical Work'), //don't set to database
			'tech_duration'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Duration for Technical Work'), //don't set to database
			'declaration'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Declaration'),							 
		);
		
		//*** Get Valuelist from Sugar
		// Get Valuelist from Database
		$valuelist_layout = $this->fm_db->getLayout('www_placement_prac_exam');
		$this->data['valuelists']['ensemble'] = $valuelist_layout->getValueListTwoFields('ensemble');
	
			/* Select fields from DB
		 * Clean the field array so that it can be used for the REST Call
		 */
		foreach ($fields as $field_name => $field_info) {
			$select_fields[] = $field_name;
		}

		//***** Placement Record
		$placement_record = $this->fm_db->getRecordById('Placements', $this->session->userdata('placement_rid'));
		if (FileMaker::isError($placement_record)) {
			$errors[] = 'Unable to determine placement record';
		} else {
			//*** Set Field into Variables for use in View
			foreach ($select_fields as $field_name) {
				$this->data['form'][$field_name]['value'] = $placement_record->getField($field_name);
			}
		}

		//***** Get Other Works Information information based on placement information
		$find = $this->fm_db->newFindCommand('www_other_work');
		$find->addFindCriterion('placement_id', "==" . $this->session->userdata('placement_id'));
		$result = $find->execute();
		if (FileMaker::isError($result)) {
			$errors[] = 'No other works were previously logged';
			$total_row = 0;

		//***** Form not submitted - Grab data from database
		} else {

			$total_row = $result->getFoundSetCount();
			$records = $result->getRecords();
			$x = 0;
			
		}
	
		//***** Form Submitted
		if ($this->uri->segment(3) == 'update' && isset($_POST['submit'])) {
			
			//*** Get Post Variables 

			//For placement fields
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['post'] == 1) {
					//Set into Array
					$post_field[$field_name] = $this->input->post($field_name);
					//Set value in form
					$this->data['form'][$field_name]['value'] = $post_field[$field_name];
				}
			}
			
			//Total minutes
			$total_row = $this->input->post('next_row');
			$current_rows = $this->input->post('current_rows');
			$total_minutes = 0;
			$total_minutes = $this->input->post('tech_duration');
			for ($x = 0; $x < $total_row; $x ++) {
				//Set into Array
				if ($this->input->post('ow_id_'. $x)) {
					$row_array[$x]['ow_id'] = $this->input->post('ow_id_'. $x);
					$row_array[$x]['title'] = $this->input->post('title_'. $x); //convert the date format
					$row_array[$x]['composer'] = $this->input->post('composer_'. $x);
					$row_array[$x]['duration'] = $this->input->post('duration_'. $x);
					$keep_records[] = $row_array[$x]['ow_id'];
					$total_minutes = $total_minutes + $row_array[$x]['duration'];
				}
			} //end each row
			
			
			//*** Validate
	
			//ensemble must be selected to save		
			if ($this->input->post('ensemble') == '') {
				$this->data['errors'][] = '<em>' . $fields['ensemble']['label'] . '</em> is a required field. Please complete this field';
				$this->data['form']['errors']['ensemble'] = 1;
			}

			//value on length field
			if (!is_numeric($this->input->post('tech_duration'))) {
				$this->data['errors'][] = "The value entered in <em>" . $fields['tech_duration']['label'] ."</em> must be numbers only";				
				$this->data['form']['errors']['composer_new'] = 1;
			}
						//value on date field
			if ($this->input->post('title_new')) {
				$this->data['errors'][] = "You have entered a new title for other works for examination. Please either click on the add button to add it to the list or delete it before saving";				
				$this->data['form']['errors']['title_new'] = 1;
			}

			//value on length field
			if ($this->input->post('composer_new')) {
				$this->data['errors'][] = "You have entered a new composer for other works for examination. Please either click on the add button to add it to the list or delete it before saving";				
				$this->data['form']['errors']['composer_new'] = 1;
			}

			//value on length field
			if ($this->input->post('duration_new')) {
				$this->data['errors'][] = "You have entered a new duration for other works for examination. Please either click on the add button to add it to the list or delete it before saving";				
				$this->data['form']['errors']['duration_new'] = 1;
			}

			//*** Validate on Submit only
			if ($_POST['submit'] == 'Submit') { 
				
				foreach ($fields as $field_name => $field_info) {
					if ($field_info['required'] == 1 && $post_field[$field_name] == '') {
						$this->data['errors'][] = '<em>' . $field_info['label'] . '</em> is a required field. Please complete this field';
						$this->data['form']['errors'][$field_name] = 1;
					}
				}
	
				//At least one other works is provided
				if (count($row_array) < 1) {
					$this->data['errors'][] = 'Please provide a minimum of 1 other work for examination';
				}
				
				//Number of hours do not exceed limit
				if ($total_minutes > 25 or $total_minutes < 15) {
					$this->data['errors'][] = 'The total duration of all your works must be between 15 to 25 minutes. You have currently entered a total of <strong>' . $total_minutes . '</strong> minutes.</strong>';
				}
			
			}
			
			//*** No errors
			if (!isset($this->data['errors'])) {
				
				//** Prepare special fields
				//Delete old other works
				if ($records) {
					foreach ($records as $record) {
						if (!in_array($record->getRecordId(), $keep_records)) {
							$logs[] = '[deleted-other-works::other_work_rid] '. $record->getRecordId();
							$logs[] = '[deleted-other-works::title] '. $record->getField('title');
							$logs[] = '[deleted-other-works::composer] '. $record->getField('composer');	
							$logs[] = '[deleted-other-works::duration] '. $record->getField('duration');
							$record->delete();
						}
					}
				}
				
				//Add new ones
				foreach ($row_array as $row_number=>$row_info) {
					if ($row_info['ow_id'] == 'new') {

						//** Create new record
						$new_record = $this->fm_db->newAddCommand('www_other_work');
						$new_record->setField('placement_id', $this->session->userdata('placement_id')); //Set the relationship - Link placement to the student record
						$new_record->setField('title', $row_info['title']); //convert the date format back to FileMaker's format of ddmmyy
						$new_record->setField('composer', $row_info['composer']);						
						$new_record->setField('duration', $row_info['duration']);
						$new_result = $new_record->execute();
						
						//Error creating new record
						if (FileMaker::isError($new_result)) {
							$errors[] = 'Unable to save your changes';
						} else {
							//Set the new id into the array - so it doesn't get created again if user submits the form again
							$new_records = $new_result->getRecords();
							$new_record = $new_records[0];
							$row_array[$row_number]['ow_id'] = $new_record->getRecordId();
							$logs[] = '[add-other-works::other_work_rid] '. $new_record->getRecordId();
							$logs[] = '[add-other-works::title] '. $row_info['title'];
							$logs[] = '[add-other-works::composer] '. $row_info['composer'];								
							$logs[] = '[add-other-works::duration] '. $row_info['duration'];
						}
					}
				}
				
				
				//** Prepare standard fields
				foreach ($fields as $field_name => $field_info) {
					if ($field_info['setdb'] == 1) {
						$field_value = $this->data['form'][$field_name]['value'];
						$placement_record->setField($field_name, $field_value);
						$logs[] = "[$field_name] " . $field_value;				
					}
				}				

				//Set as submitted
				if ($_POST['submit'] == 'Submit') {						
					$placement_record->setField('prac_exam_submitted', 1);
					$placement_record->setField('prac_exam_unlock', 0);
					$logs[] = '[prac_exam_submitted] 1';
					$logs[] = '[prac_exam_unlock] 0';
					
					//Set session so the user won't be redirected back to this page
					$this->session->set_userdata('prac_exam_open', 0);
					$this->session->set_userdata('prac_exam_unlock', 0);
					$this->session->set_userdata('prac_exam_submitted', 1);
					$redirect = '/prac-exam/';	
				} else {
					$redirect = '/prac-exam-form/';
				}

				$placement_result = $placement_record->commit();
				
				
				// Log this update into the edits log 
				$log_message = 'Practical examination form';
				if ($logs) $log_message = $log_message . "\r\n" . implode("\n", $logs);
				log_edits($this->session->userdata('placement_id'), $this->session->userdata('user_zid'), $log_message, 'Student Portal');

				//Redirect
				$this->session->set_flashdata('message', 'Save successful');
				redirect( user_default_homepage() . $redirect);
				die();

			} //End No Errors
		
		}	elseif ($records) {
			
			//*** Set Field into Variables for use in View
			foreach ($records as $record) {	
			
				//Set into Array
				$row_array[$x]['ow_id'] = $record->getRecordId();
				$row_array[$x]['title'] = $record->getField('title');
				$row_array[$x]['composer'] = $record->getField('composer');
				$row_array[$x]['duration'] = $record->getField('duration');
				$x ++;
				
				$current_rows_array[] = $record->getRecordId();
				
			} //end each row
			
			$current_rows = implode(';', $current_rows_array);

		}
				
		//***** Page view
		//pass data
		$this->data['form']['current_rows'] = $current_rows;
		$this->data['form']['total_row'] = $total_row;
		$this->data['form']['row_array'] = $row_array;
	
		$this->data['title'] = 'Practical Examination Form';
		$this->data['navigation']['student/prac-exam']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('student/prac-exam-form', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}

	// ------------------------------------------------------------------------

	function Prac_exam() {
		
		//***** Check Access
		//if they still have access to edit prac form then rediect them to it
		if ($this->session->userdata('prac_exam_open') == 1) {
			redirect('student/prac-exam-form');
			die();
		}

		//***** Placement Record
		$placement_record = $this->fm_db->getRecordById('Placements', $this->session->userdata('placement_rid'));
		if (FileMaker::isError($placement_record)) {
			$errors[] = 'Unable to determine placement record';
		} else {
			$this->data['placement_record'] = $placement_record;
		}

		//***** Get Other Works Information information based on placement information
		$find = $this->fm_db->newFindCommand('www_other_work');
		$find->addFindCriterion('placement_id', "==" . $this->session->userdata('placement_id'));
		$result = $find->execute();
		if (FileMaker::isError($result)) {
			$total_row = 0;
		} else {
			$total_row = $result->getFoundSetCount();
			$records = $result->getRecords();
			$x = 0;
			$this->data['ow_records'] = $records;
		}
					
		//***** Page view
		$this->data['form']['current_rows'] = $current_rows;
		$this->data['form']['total_row'] = $total_row;
		$this->data['form']['row_array'] = $row_array;
	
		$this->data['title'] = 'Practical Examination';
		$this->data['navigation']['student/prac-exam']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('student/prac-exam', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}

	// ------------------------------------------------------------------------


	function Book_exam_form () {
		
		//***** Check Access
		//if they don't have access to edit prac form then rediect them to view it only
		if ($this->session->userdata('system_book_exam_open') != 1) {
			redirect('student/book-exam');
			die();
		}
	
		//***** Set variables
		$this->data['first_time'] = true;
		/* DB Fields
		 * List the fields that we want from the database and indicate which ones
		 * we want to submit to the database
		 */
		$fields = array(
			//Field => Submit to Database
			'exam_drum_amp'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Drum or amp requirement'), //don't set to database - will do manually
			'exam_room'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Room'), //don't set to database - will do manually
			'exam_date'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Date'), //don't set to database
			'exam_time'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Time'),
		);

		//Get content for page
		$setting_record = $this->fm_db->getRecordById('www_setting', $this->session->userdata('system_rid'));
		$this->data['content'] = decode_html($setting_record->getField('web_content_student_book_exam'));
				
		//***** Placement Record
		$placement_record = $this->fm_db->getRecordById('www_placement_exam_slot', $this->session->userdata('placement_rid'));
		if (!FileMaker::isError($placement_record)) {
			//*** Set Field into Variables for use in View
			foreach ($fields as $field_name=>$field_info) {
				$field_value = $placement_record->getField($field_name);
				if ($field_name == 'exam_date') $field_value = date_mdy_dmy($field_value);
				if ($field_name == 'exam_room' && $field_value != '') $this->data['first_time'] = false; //so that we can show a 'cancel' option for the form
				$this->data['form'][$field_name]['value'] = $field_value;
			}
		}
				
		//***** Find all records in the current cohort with slot already selected
		$find_existing = $this->fm_db->newFindCommand('www_placement_exam_slot');
		$find_existing->addFindCriterion('year', "==" . $this->session->userdata('system_year'));
		$find_existing->addFindCriterion('semester', "==" . $this->session->userdata('system_semester'));
		$find_existing->addFindCriterion('exam_room', "*");
		$find_existing->addFindCriterion('exam_date', "*");
		$find_existing->addFindCriterion('exam_time', "*");
		$find_existing_result = $find_existing->execute();
		if (!FileMaker::isError($find_existing_result)) {
			foreach ($find_existing_result->getRecords() as $taken_slot_record) {
				//Set slots as taken if the record is not the same as this user
				if ($taken_slot_record->getField('placement_id') != $this->session->userdata('placement_id')) {
					$slot_string = $taken_slot_record->getField("exam_room"). ';' . date_mdy_dmy($taken_slot_record->getField("exam_date")) . ';' . $taken_slot_record->getField("exam_time");
					$slots_taken[$slot_string] = 1;
				}
			}
		}
	
		//***** Get all time slots
		$find = $this->fm_db->newFindCommand('www_system_exam_slot');
		$find->addFindCriterion('setting_id', $this->session->userdata('system_id'));
		$find->addFindCriterion('date', '*');
		$find->addFindCriterion('room', '*');
		$find->addFindCriterion('time', '*');
		$find->addSortRule('date', 1, FILEMAKER_SORT_ASCEND);
		$find->addSortRule('time', 1, FILEMAKER_SORT_ASCEND);
		$result = $find->execute();
		if (FileMaker::isError($result)) {
			$errors[] = 'The available time slots for booking an examine has not been released yet. Please contact the School';
		} else {
			
			//Put the slots into array
			foreach ($result->getRecords() as $slot_record) {
				
				$slot_room = $slot_record->getField('room');
				$slot_date = date_mdy_dmy($slot_record->getField('date'));
				$slot_time = $slot_record->getField('time');
				
				//Check that its not already taken
				$slot_string = $slot_room . ';' . $slot_date . ';' . $slot_time;
				if ($slots_taken[$slot_string] != 1) {			
					$slot_options[$slot_room]['date'][$slot_date][] = $slot_time; 
				}
				
			}			
			
		}

		//***** Get Room Details - which ones have amp availabe
		$find = $this->fm_db->newFindCommand('www_system_exam_room');
		$find->addFindCriterion('setting_id', $this->session->userdata('system_id'));
		$find->addFindCriterion('room', '*');
		$result = $find->execute();
		if (!FileMaker::isError($result)) {			
			//Put the slots into array
			foreach ($result->getRecords() as $room_record) {
				$room_name = $room_record->getField('room');
				if ($slot_options[$room_name]) $slot_options[$room_name]['drum_amp'] = $room_record->getField('available_drum_amp');				
			}			
		}

		//***** Form Submitted
		if ($this->uri->segment(3) == 'update' && isset($_POST['submit'])) {
			
			//*** Get Post Variables 
			//For placement fields
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['post'] == 1) {
					//Set into Array
					$post_field[$field_name] = $this->input->post($field_name);
					//Set value in form
					$this->data['form'][$field_name]['value'] = $post_field[$field_name];
				}
			}
			
			//*** Validate
			foreach ($fields as $field_name => $field_info) {
				if ($field_info['required'] == 1 && $post_field[$field_name] == '') {
					$this->data['errors'][] = '<em>' . $field_info['label'] . '</em> is a required field. Please complete this field';
					$this->data['form']['errors'][$field_name] = 1;
				}
			}

			//checking the room is available
			$slot_string = $post_field['exam_room'] . ';' . $post_field['exam_date'] . ';' . $post_field['exam_time'];
			if ($slots_taken[$slot_string] == 1) {
				$this->data['errors'][] = 'The time slot <strong>' . $post_field['exam_time'] . '</strong> in room <strong>' . $post_field['exam_room'] . '</strong> on the date of <strong>' . $post_field['exam_date'] . '</strong> has been now be taken. Please change your selection.';				
			}

			//*** No errors
			if (!isset($this->data['errors'])) {
								
				//** Prepare standard fields
				foreach ($fields as $field_name => $field_info) {
					if ($field_info['setdb'] == 1) {
						
						if ($field_name == 'exam_date') {
							$field_value = date_dmy_mdy($this->data['form'][$field_name]['value']);
						} else {
							$field_value = $this->data['form'][$field_name]['value'];
						}
						
						$placement_record->setField($field_name, $field_value);
						$logs[] = "[$field_name] " . $field_value;
						
					}
				}				

				$placement_result = $placement_record->commit();

				// Log this update into the edits log 
				$log_message = 'Book exam slot';
				if ($logs) $log_message = $log_message . "\r\n" . implode("\n", $logs);
				log_edits($this->session->userdata('placement_id'), $this->session->userdata('user_zid'), $log_message, 'Student Portal');

				$this->session->set_flashdata('message', 'Save successful');
				redirect( user_default_homepage() . '/book-exam');
				die();

			} //End No Errors
		
		}	elseif ($records) {
			
			//*** Set Field into Variables for use in View
			foreach ($records as $record) {	
			
				//Set into Array
				$row_array[$x]['ow_id'] = $record->getRecordId();
				$row_array[$x]['title'] = $record->getField('title');
				$row_array[$x]['composer'] = $record->getField('composer');
				$x ++;
				
				$current_rows_array[] = $record->getRecordId();
				
			} //end each row
			
			$current_rows = implode(';', $current_rows_array);

		}
		
		$this->data['slot_options'] = $slot_options;
		$this->data['title'] = 'Book Practical Examinations';

		$this->data['navigation']['student/book-exam']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('student/book-exam-form', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}
	// ------------------------------------------------------------------------
	
	function Book_exam () {
	
		
		//***** Check Access
		if (valid_deadline($this->session->userdata('system_book_exam_start')) == true) {
			redirect('student/');
			die();
		}

		//***** Set Variables
		/* DB Fields
		 * List the fields that we want from the database and indicate which ones
		 * we want to submit to the database
		 */
		$fields = array(
			//Field => Submit to Database
			'exam_drum_amp'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Drum or amp requirement'), //don't set to database - will do manually
			'exam_room'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Room'), //don't set to database - will do manually
			'exam_date'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Date'), //don't set to database
			'exam_time'=>array('post'=>'1', 'setdb'=>'1', 'required'=>'1', 'label'=>'Time'),
		);
		
		//***** Get placement record
		$placement_record = $this->fm_db->getRecordById('www_placement_exam_slot', $this->session->userdata('placement_rid'));
		if (FileMaker::isError($find_existing_result)) {
			$errors[] = 'Unable to identify placement record';
		} else {
			$this->data['record'] = $placement_record;			

		}

		//***** Redirect to booking form is incomplete
		//if the system is still open and the user have not already selected a room then redirect to the booking form
		if ($this->session->userdata('system_book_exam_open') == 1 && trim($placement_record->getField('exam_room')) == '') {
			redirect('student/book-exam-form');
			die();
		}

		//***** View					
		$this->data['title'] = 'Practical Examination Detail';

		$this->data['navigation']['student/book-exam']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('student/book-exam', $this->data);
		$this->load->view('templates/footer', $this->data);		
	}

	// ------------------------------------------------------------------------

	function Error ($params) {
		
		$this->data['title'] = 'Error Occurred';
		$this->data['error'] = $params[0];
		
		$this->data['navigation']['student/error']['active'] = 1;
		$this->load->view('templates/header', $this->data);
		$this->load->view('error-404', $this->data);
		$this->load->view('templates/footer', $this->data);
		
	}

}