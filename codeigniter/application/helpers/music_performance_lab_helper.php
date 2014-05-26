<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------


/**
 * Logout
 *
 * Login to SugarCRM to open a session and retrieve the session id.
 * The session id can then be used for subsequent calls to Sugar.
 *
 */
if ( ! function_exists('logout'))
{
	function logout ()
	{

		$CI =& get_instance(); //To fetch the object
		
		//***** Load Helpers
		$CI->load->library('form_validation');

		//***** Log edit
		log_edits($CI->session->userdata('student_id'), $CI->session->userdata('user_zid'), 'Logged out of portal', 'Student Portal');
		
		//***** Destroy session
		$CI->session->set_userdata('login_ok', '0');
		$CI->session->sess_destroy();	
		$CI->session->set_flashdata('message', 'Logged out successfully');

		//***** Reset the messages for the main page to use
		redirect ('');
		die();

	}
}

// ------------------------------------------------------------------------


/**
 * Initiate connection to database
 *
 * Login to SugarCRM to open a session and retrieve the session id.
 * The session id can then be used for subsequent calls to Sugar.
 *
 */
if ( ! function_exists('db_init'))
{
	function db_init ()
	{

		require_once('/data/www/groups/trc/other/connections/sam_music_performance_lab.php');
		$sam_mpl_db = new FileMaker($db_name, $db_host, $db_user, $db_pass); 
		
		return $sam_mpl_db;

	}
}


// ------------------------------------------------------------------------

/**
 * Print arrays in a readable format
 *
 * For development purposes
 *
 * @ob - object or array
 *
 */

if ( ! function_exists('print_ob'))
{
	function print_ob ($ob)
	{
		print '<pre>';
		var_dump($ob);
		print '</pre>';
	}
}
// ------------------------------------------------------------------------

/**
 * Print sessions into readable format
 *
 * For development purposes
 *
 */

if ( ! function_exists('print_session'))
{
	function print_session ()
	{
		
		$CI =& get_instance();	
		print_ob($CI->session->all_userdata());
		
	}
}




// ------------------------------------------------------------------------

/**
 * Format Errors
 *
 * @errors - Array or String
 *
 */
if ( ! function_exists('format_errors'))
{
	function format_errors($errors)
	{
		
		if (is_array($errors)) {
			
			$result = '<div class="error status"><p><strong>ERROR:</strong> The following error(s) have occurred. You must resolve them before you can proceed:</p><ul>';
			
			foreach ($errors as $error) {
				$result .= '<li>' . $error . '</li>';
			}
			
			$result .= '</ul></div>';
			
			return $result;
			
		} elseif (isset($errors) && is_string($errors)) {

			$result = '<div class="error status"><strong>ERROR:</strong> ' . $errors . '</div>';
			return $result;

		} else {
			return FALSE;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Display Formatted Errors
 *
 * @errors - Array or String
 *
 */
if ( ! function_exists('display_errors'))
{
	function display_errors($errors)
	{
		print format_errors($errors);
	}
}

// ------------------------------------------------------------------------

/**
 * Format Message
 *
 * @message - Array or String
 *
 */
if ( ! function_exists('format_message'))
{
	function format_message($messages)
	{
		
		if (is_array($messages)) {
			
			$result = '<div class="message status"><ul>';
			
			foreach ($messages as $message) {
				$result .= '<li>' . $message . '</li>';
			}
			
			$result .= '</ul></div>';
			
			return $result;
			
		} elseif (isset($messages) && is_string($messages)) {

			$result = '<div class="message status">' . $messages . '</div>';
			return $result;

		} else {
			return FALSE;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Display Formatted Messages
 *
 * @message - Array or String
 *
 */
if ( ! function_exists('display_message'))
{
	function display_message($message)
	{
		print format_message($message);
	}
}

// ------------------------------------------------------------------------

/**
 * User's default homepagae
 *
 * Based on the user type
 *
 */

if ( ! function_exists('user_default_homepage'))
{
	function user_default_homepage()
	{
		
		$CI =& get_instance(); //To fetch the object
		
		$user_type = $CI->session->userdata('user_type');
		
		switch($user_type) {
	
			case 'student':
				$homepage = '/student';
				break;		
			case 'examiner':
				$homepage = '/examiner';
				break;
			case 'tutor':
				$homepage = '/tutor';
				break;
			default:
				$homepage = '/'; //login page
				break;
		}
		
		return $homepage;
		
	}
}

// ------------------------------------------------------------------------

/**
 * User's type
 *
 * Based on the user type
 *
 */

if ( ! function_exists('get_user_type_label'))
{
	function get_user_type_label($string)
	{
		
		switch($string) {
	
			case 'student':
				$label = 'Student';
				break;
				
			case 'examiner':
				$label = 'Examiner';
				break;

			case 'tutor':
				$label = 'Tutor';
				break;

			default:
				$label = $string;
				break;
				
		}
		
		return $label;
		
	}
}

// ------------------------------------------------------------------------

/**
 * Implode an Array and exclude empty values
 *
 * Return the array in a string. Will not include any arrays that are empty
 *
 * @glue = string
 * @array = object/array
 *
 */
if ( ! function_exists('no_empty_implode'))
{
	function no_empty_implode($glue, $array)
	{
		
		$count = 0;
		$array_count = count($array);
		$string = FALSE;	
		$new_array = array();
		// Loop through each array
		foreach ($array as $row) {
			//Include into string if the row is not empty
			if (trim($row)) {
				$new_array[] = trim($row);
			}
		}
		
		if (count($new_array) > 0) {
			$string = implode($glue, $new_array);
		}
		
		return $string;
	}
}

// ------------------------------------------------------------------------

/**
 * Sort Navigation based on weight
 *
 * @array = multidimensional associative array
 *
 */
if ( ! function_exists('sort_navigation'))
{
	function sort_navigation($array)
	{
		
		// Obtain a list of columns
		foreach ($array as $key => $value) {
			$order[$key]  = $value['weight'];
		}
		
		// Sort the navigation with weight ascending
		// Add $this->data['navigation'] as the last parameter, to sort by the common key
		array_multisort($order, SORT_ASC, $array);

		return $array;
		
	}
}

// ------------------------------------------------------------------------

/**
 * Convert Field into Array
 *
 * The Contact 1, Contact 2 and Emergency Contact details are each grouped into its own field. We need to separate the values in the
 * field so that we can display the value into separate fields for the personal information form.
 *
 * @value = The raw value from the rest_call
 *
 */
if ( ! function_exists('contact_split_array'))
{
	function contact_split_array($value)
	{
		
		//Split each line
		$array = explode("\r\n", $value);
		$new_array = array();
		
		//Loop through each and get the value without the label
		foreach ($array as $row) {
			$separator = strpos($row, ':');
			$label = trim(substr($row, 0, $separator));
			$value = trim(substr($row, $separator + 1, strlen($row)));		
			$new_array[] = $value;
		}

		return $new_array;
		
	}
}

// ------------------------------------------------------------------------

/**
 * Student Login
 *
 * Search sugarcrm for student record and set the sessions and redirects
 *
 * @type = 'student' or 'staff'
 * @username = string
 * @password = string
 *
 */
if ( ! function_exists('login_student'))
{
	function login_student($type, $username, $password)
	{
		
//		if ($username == 'z3130938') $username = 'z5020958';
		
		$CI =& get_instance(); //To fetch the object
			
		//Find Corresponding record in database
		$find = $CI->fm_db->newFindCommand('www_students_search');
		$find->addFindCriterion('zid', $username);
		$result = $find->execute();
		
		//Find error
		if (FileMaker::isError($result)) {
			$errors[] = 'You do not have an account with this system';
		
		//Found Student Record	
		} elseif ($result->getFoundSetCount() == 1) {
						
			//*** Shortcut to results
			$records = $result->getRecords();
			$record = $records[0];
			
			//*** Check that Student is currently enrolled
			if ($record->getField('status') != 'Currently enrolled') {
				$errors[] = 'Access to your account in this system is currently not enabled.';
								
			//*** Access OK
			} else {

				//*** Set User's details into Session				
				
				//Set User Details
				$session_data = array(
					'user_zid'=>$record->getField('zid'),
					'user_first_name'=>$record->getField('first_name'),
					'user_last_name'=>$record->getField('last_name'),
					'user_type'=>'student',
					'student_id'=>$record->getField('student_id'),
					'student_rid'=>$record->getRecordId(),
				);
				
				$CI->session->set_userdata($session_data);
				
				/* LAST LOGIN: See when the student last updated their personal info page
				 * if they haven't updated it since the system opened, set variable so they are redirected to
				 * the personal info page
				 */
				
				$find = $CI->fm_db->newFindCommand('www_edit');
				$find->addFindCriterion('related_id', "==".$CI->session->userdata('student_id'));
				$find->addFindCriterion('method', "==Student Portal");
				$find->addFindCriterion('description', '=="Updated personal information"*');
				$find->addFindCriterion('created_by', "==" . $CI->session->userdata('user_zid'));
				$find->addSortRule('created', 1, FILEMAKER_SORT_DESCEND);
				$find->setRange(0, 1); //just want the latest record
				$result = $find->execute();
				if (FileMaker::isError($result)) {
					//no previous login found
					$CI->session->set_userdata('user_first_time', 1);
				} else {
					//set previous login timestamp
					$login_records = $result->getRecords();
					$login_record = $login_records[0];
					$last_login = strtotime($login_record->getField('created'));
					$system_start = strtotime($CI->session->userdata('system_start'));
					if ( $last_login < $system_start) {
						$CI->session->set_userdata('user_first_time', 0);
					}
					
				}

				/* PLACEMENT: Check to see if the student has already entered a placement before 
				 * If so, we need to get the current placement
				 */
				//Find student's CURRENT placement in DB and set placement id if found
				$find = $CI->fm_db->newFindCommand('Placements');
				$find->addFindCriterion('student_id', "==".$CI->session->userdata('student_id'));
				$find->addFindCriterion('year', "==".$CI->session->userdata('system_year'));
				$find->addFindCriterion('semester', "==".$CI->session->userdata('system_semester'));
				$find->addSortRule('placement_id', 1, FILEMAKER_SORT_DESCEND);
				$find->setRange(0, 1); //just want the latest record
				$result = $find->execute();
				
				//Find error
				if (FileMaker::isError($result)) {		
					//No placements found				
					$CI->session->set_userdata('placement', '0');
					$CI->session->set_userdata('tutor', '0');
					
				//Placement Found
				} else {
					$placement_records = $result->getRecords();
					$placement_record = $placement_records[0]; //get the latest placement record

					//Set Placement details	
					$CI->session->set_userdata('placement_id', $placement_record->getField('placement_id'));
					$CI->session->set_userdata('placement_rid', $placement_record->getRecordId());
					$CI->session->set_userdata('placement', '1');
					$CI->session->set_userdata('lesson_log_submitted', $placement_record->getField('lesson_log_submitted'));
					$CI->session->set_userdata('prac_exam_submitted', $placement_record->getField('prac_exam_submitted'));
					$CI->session->set_userdata('lesson_log_unlock', $placement_record->getField('lesson_log_unlock'));
					$CI->session->set_userdata('prac_exam_unlock', $placement_record->getField('prac_exam_unlock'));
					
					//TUTOR: See if there is a tutor associated or if their request for new tutor has been declined
					$CI->session->set_userdata('tutor_approval', $placement_record->getField('new_tutor_approval'));
					$CI->session->set_userdata('tutor_new_request', $placement_record->getField('new_tutor_request'));
					
					$find = $CI->fm_db->newFindCommand('www_placement_tutor');
					$find->addFindCriterion('placement_id', "==".$CI->session->userdata('placement_id'));
					$find->addSortRule('primary', 1, FILEMAKER_SORT_DESCEND);
					$find->addSortRule('created', 2, FILEMAKER_SORT_DESCEND);
					$find->setRange(0, 1); //just want the latest record
					$result = $find->execute();
					if (FileMaker::isError($result)) {
						$CI->session->set_userdata('tutor_selected', 0);
					} else {
						$tutor_records = $result->getRecords();
						$tutor_record = $tutor_records[0];
						$CI->session->set_userdata('tutor_selected', 1);
						$CI->session->set_userdata('tutor_id', $tutor_record->getField('tutor_id'));
					}
									
				}
				
				/* LESSON LOGS: Check to see if student should be able to edit their lesson logs. Only open if
				 * [1] Before deadline
				 * [2] Haven't submitted it yet
				 * [3] Unlocked
				 */
				if (valid_deadline($CI->session->userdata('system_lesson_log_end')) == true && $CI->session->userdata('lesson_log_submitted') !=  1) {
					$CI->session->set_userdata('lesson_log_open', 1);
				} elseif ($CI->session->userdata('lesson_log_unlock') ==  1) {
					$CI->session->set_userdata('lesson_log_open', 1);
				} else {
					$CI->session->set_userdata('lesson_log_open', 0);
				}
				
				/* PRAC EXAM FORM: Check to see if student should be able to edit their prac exam details. Only open if
				 * [1] Before deadline
				 * [2] Haven't submitted it yet
				 * [3] Unlocked
				 */
				if (valid_deadline($CI->session->userdata('system_practical_exam_end')) == true && $CI->session->userdata('prac_exam_submitted') !=  1) {
					$CI->session->set_userdata('prac_exam_open', 1);
				} elseif ($CI->session->userdata('prac_exam_unlock') ==  1) {
					$CI->session->set_userdata('prac_exam_open', 1);
				} else {
					$CI->session->set_userdata('prac_exam_open', 0);
				}

				/* PRAC EXAM BOOKING: Check to see if student should be able to book for an exam slot
				 */
				 if (valid_deadline($CI->session->userdata('system_book_exam_end'), $CI->session->userdata('system_book_exam_start')) == true) {
					$CI->session->set_userdata('system_book_exam_open', 1);
				 } else {
					$CI->session->set_userdata('system_book_exam_open', 0);
				 }
			
				// LOG EDITS: student has logged into the portal
				log_edits($CI->session->userdata('student_id'), $CI->session->userdata('user_zid'), 'Logged into student portal', 'Student Portal');

				//Set authenticated session
				$CI->session->set_userdata('login_ok', 1);

				//Set default error incase the redirection below doesn't work
				$errors[] = 'Logged in successfully. Unable to redirect correctly.';
					
				//Get homepage for this user base on their user type
				$default_homepage = user_default_homepage();
				
				//Redirect
				if ($default_homepage == '/' or $default_homepage == '') {
					$errors[] = 'Unrecognised user type';
				} else {
					
//					print_ob($CI->session->all_userdata());
//					die ('login ok');
					redirect($default_homepage, "location");

				}
									
			} //Access OK
		
		//More than one record
		} else {
			$errors[] = 'Unable to identify your account details.';
		}
		
		return $errors;

	}
}


/**
 * Tutor Login
 *
 * Search databse for student record and set the sessions and redirects
 *
 * @username = string
 * @password = string
 *
 */
if ( ! function_exists('login_tutor'))
{
	function login_tutor($username, $password)
	{
	
		$CI =& get_instance(); //To fetch the object
		
		//***** Find Tutor
		$find = $CI->fm_db->newFindCommand('www_tutor_list');
		$find->addFindCriterion('email', '==' . $username);
		$find->addFindCriterion('password', '==' . $password);
		$result = $find->execute();
		
		//*** Get Record Failed
		if (FileMaker::isError($result)) {
			//Unable to find tutors
			$errors[] = 'Login failed. Your username and/or password combination may be incorrect.';
			
		//*** Too many records
		} elseif ($result->getFoundSetCount() > 1) {
			$errors[] = 'Unable to identify your account details.';
		
		//*** Found ok
		} else {

			$records = $result->getRecords();
			$record = $records[0];
					
			//*** Check that tutor has access to login
			
			//Active tutor?
			if ($record->getField('active') != '1') {
				$errors[] = 'Access for this account is currently disabled.';
				
			//User has access
			} else {
				
				//*** Set User's details into Session				
				//Set User Details
				$session_data = array(
					'user_email'=>$record->getField('email'),
					'user_name'=>$record->getField('c_full_name'),
					'user_first_name'=>$record->getField('first_name'),
					'user_last_name'=>$record->getField('last_name'),
					'user_type'=>'tutor',
					'tutor_id'=>$record->getField('tutor_id'),
					'tutor_rid'=>$record->getRecordId(),
				);
				
				$CI->session->set_userdata($session_data);
				
				// Check if tutor needs to see declaration page
				$CI->session->set_userdata('declaration_first_time', check_last_log($CI->session->userdata('tutor_id'), $CI->session->userdata('tutor_id'), 'Declaration complete', 'Tutor Portal'));

				// Check if tutor needs to update their personal details
				$CI->session->set_userdata('personal_info_first_time', check_last_log($CI->session->userdata('tutor_id'), $CI->session->userdata('tutor_id'), 'Updated personal information', 'Tutor Portal'));

				// LOG EDITS: tutor has logged into the portal
				log_edits($CI->session->userdata('tutor_id'), 'Tutor', 'Logged into tutor portal', 'Tutor Portal');

				//Set authenticated session
				$CI->session->set_userdata('login_ok', 1);

				//Set default error incase the redirection below doesn't work
				$errors[] = 'Logged in successfully. Unable to redirect correctly.';
					
				//Get homepage for this user base on their user type
				$default_homepage = user_default_homepage();
					
				//Redirect
				if ($default_homepage == '/' or $default_homepage == '') {
					$errors[] = 'Unrecognised user type';
				} else {
					redirect($default_homepage, "location");

				}

				
			} //End active tutor
			
			
		} //End tutor found

	//	print_ob ($CI->session->all_userdata());
		
		return $errors;

	}
}

// ------------------------------------------------------------------------

/**
 * Log edits
 *
 * Into database to track record changes
 * 
 */

if ( ! function_exists('log_edits'))
{
	function log_edits ($related_id, $created_by, $description, $method) {				
	
		$CI =& get_instance();
		$new_edit = $CI->fm_db->newAddCommand('www_edit');
		$new_edit->setField('related_id', $related_id);
		$new_edit->setField('created_by', $created_by);
		$new_edit->setField('description', $description);
		$new_edit->setField('method', $method);
		$new_result = $new_edit->execute();
		
		if (FileMaker::isError($new_result)) {
			return 'Unable able to log edits. ' . $new_result->getMessage();
		} else {
			return true;
		}

	}
}

// ------------------------------------------------------------------------

/** 
 * Check against FileMaker on when the specified log last occurred for the user.
 * Useful for identified what users have or haven't done
 *
 * returns true or false
 * $related_id = the user's id in FM (e.g. student_id, tutor_id)
 *
 */
if ( ! function_exists('check_last_log'))
{

	function check_last_log ($related_id, $created_by, $description, $method) {

		$CI =& get_instance();

		$first_time = true; //default to be their first 
		
		$find = $CI->fm_db->newFindCommand('www_edit');
		$find->addFindCriterion('related_id', '==' . $related_id);
		$find->addFindCriterion('method', '==' . $method);
		$find->addFindCriterion('description', '==' .  $description . '*');
		$find->addFindCriterion('created_by', '==' . $created_by);
		$find->addSortRule('created', 1, FILEMAKER_SORT_DESCEND);
		$find->setRange(0, 1); //just want the latest record
		$result = $find->execute();
		//Previous record found
		if (!FileMaker::isError($result)) {
			//set previous login timestamp
			$login_records = $result->getRecords();
			$login_record = $login_records[0];			
			$last_login = strtotime($login_record->getField('created'));
			$system_start = strtotime($CI->session->userdata('system_start'));
			//check to see if log happened after system start
			if ( $last_login > $system_start) {
				//so its not their first time
				$first_time = false;
			}
		}
		
		return $first_time;

	}
	
}

// ------------------------------------------------------------------------

/** 
 * Check whether whether there is a placement record with the specified placement id and tutor id
 * Useful for to whether a tutor should have access to a placement
 * returns true or false
 * true = tutor should have access to placement
 * false = tutor should NOT have access to placement because tutor is not assigned to the placement
 */
if ( ! function_exists('placement_tutor_match'))
{

	function placement_tutor_match ($placement_id, $tutor_id) {

		$CI =& get_instance();
		$result = false;
		//Is there a placement record with the specified placement ID and tutor ID
		$find = $CI->fm_db->newFindCommand('www_placement_search');
		$find->addFindCriterion('placement_id', '==' . $placement_id);
		$find->addFindCriterion('placement_tutor::tutor_id', '==' . $tutor_id);
		$result = $find->execute();
		if (!FileMaker::isError($result)) $result = true;

		return $result;
		
	}
	
}

// ------------------------------------------------------------------------

/** 
 * Check whether whether there is a placement record with the specified placement id and tutor id
 * Useful for to whether a tutor should have access to a placement
 * returns true or false
 * true = tutor should have access to placement
 * false = tutor should NOT have access to placement because tutor is not assigned to the placement
 */
if ( ! function_exists('get_progress_report_info'))
{

	function get_progress_report_info ($placement_id, $tutor_id) {
		
		$CI =& get_instance();
		
		//Is there already a progress report for this placement by this tutor?	
		$find = $CI->fm_db->newFindCommand('www_progress_report_search');
		$find->addFindCriterion('placement_id', '==' . $placement_id);
		$find->addFindCriterion('tutor_id', '==' . $tutor_id);
		$result = $find->execute();
		if (FileMaker::isError($result)) {		
			$info = array(
				'status'		=>'awaiting',
				'rid'				=>0,
				'id'				=>0,
				'submitted' =>0,
				'unlock' 		=>0,
			);
		} else {
			$progress_reports = $result->getRecords();
			//just use the first record
			$info = array(
				'rid'				=>$progress_reports[0]->getRecordId(),
				'id'				=>$progress_reports[0]->getField('progress_id'),
				'unlock'		=>$progress_reports[0]->getField('unlock')
			);
			
			//submitted?
			if ($progress_reports[0]->getField('submitted') == 1) {
				$info['status'] = 'submitted';
				$info['submitted'] = 1;
			} else {
				$info['status'] = 'draft';
				$info['submitted'] = 0;
			}

		}

		//Override report as closed if the deadline has passed and it hasn't been unlocked
		if (valid_deadline($CI->session->userdata('system_progress_report_end')) != true && $info['unlock'] != 1) $info['status'] = 'closed';
		
		return $info;
		
	}
	
}

// ------------------------------------------------------------------------

/**
 * FileMaker Error
 *
 * Show filemaker error quickly
 * 
 */

if ( ! function_exists('fm_error'))
{
	function fm_error (&$var) {				
		if (FileMaker::isError($var)) {
			echo "FM error is: " . $var->getMessage();
		} else {
			echo "No FM error";
		}
	}
}


// ------------------------------------------------------------------------

/**
 * Redirections for tutor form
 *
 */

if ( ! function_exists('redirect_to_tutor'))
{
	function redirect_to_tutor () {
	
		$CI =& get_instance();
		
		//tutor already selected
		if ($CI->session->userdata('tutor_selected') != '1') {
			return true;
			
		//new tutor requested but not approved
		} elseif ($CI->session->userdata('tutor_new_request') == '1' && $CI->session->userdata('tutor_approval') == '0') {
			return true;
		
		//need to be redirected to the tutor form
		} else {
			return false;
		}

	}
}


/**
 * Determine the status of the tutor selection
 *
 */

if ( ! function_exists('tutor_selection_status'))
{
	function tutor_selection_status () {
	
		$CI =& get_instance();
		
		//tutor already selected
		if ($CI->session->userdata('tutor_selected') == '1') {
			$status = 'selected';
			
		//new tutor requested but not approved
		} elseif ($CI->session->userdata('tutor_new_request') == '1') {
			
			if ($CI->session->userdata('tutor_approval') === '0') {
				$status = 'awaiting'; //user required to make a selection
			} else {
				$status = 'pending';
			}
		
		/* by default - need to be redirected to the tutor form
		 * where user is required to make a selection
		 */
		} else {
			$status = 'awaiting';
		}
 		
		return $status;
		
	}
}

// ------------------------------------------------------------------------

/**
 * Redirections for course information
 *
 */

if ( ! function_exists('redirect_to_course_info'))
{
	function redirect_to_course_info () {
	
		
		$CI =& get_instance();
		
		if ($CI->session->userdata('placement') != '1') {
			return true;
		} else {
			return false;
		}

	}
}

// ------------------------------------------------------------------------

/**
 * Sort Lessons
 *
 * Based on date. So that the order of the lessons appear by date when useres are
 * updating their lesson log
 *
 * @lessons = array
 *
 */

if ( ! function_exists('sort_lessons'))
{
	function sort_lessons($lessons)
	{
		
		if (is_array($lessons)) {
						
			foreach ($lessons as $lesson) {
				//Put each field into the array
				foreach ($lesson as $key=>$value) {
					$sort_array[$key][] = $value;
				}					
			}

			array_multisort($sort_array['date'],SORT_ASC,$lessons); 

			return $lessons;

		} else {

			return '0';
			
		}
		
	}
}

// ------------------------------------------------------------------------

/**
 * Convert date format from mdy to dmy
 *
 * use to change mm/dd/yyyy date format to dd/mm/yyyy
 * generally used to echo date fields from FileMaker
 * i.e. date_mdy_dmy("12/31/2010"); becomes "31/12/2010")
 *
 */

if ( ! function_exists('date_mdy_dmy'))
{
	function date_mdy_dmy($field) {
		$field = date("d/m/Y",strtotime($field));
		return $field;	
	}
}

// ------------------------------------------------------------------------

/**
 * use to change dd/mm/yyyy date format to mm/dd/yyyy
 * generally used to set date fields in FileMaker (fileMaker
 * only accepts mm/dd/yyyy formats for date fields)
 * i.e. dmy_to_mdy("28/3/2008"); becomes "3/28/2008"
 *
 */
if ( ! function_exists('date_dmy_mdy'))
{ 
	function date_dmy_mdy ($field) {
		$dateEach = explode("/", $field);
		$field = strftime("%m/%d/%Y", mktime(0,0,0,$dateEach[1],$dateEach[0],$dateEach[2]));
		return $field;	
	}
}
// ------------------------------------------------------------------------
/**
 * use to change mm/dd/yyyy date format in a "timestamp string" to dd/mm/yyyy
 * generally used to echo date fields from FileMaker
 * i.e. date_mdy_dmy("12/31/2010"); becomes "31/12/2010")
 */
if ( ! function_exists('timestamp_mdy_dmy'))
{
	function timestamp_mdy_dmy($field) {
		$fieldArray = explode(" ", $field);
		$date = date("d/m/Y",strtotime($fieldArray[0]));
		return $date." ".$fieldArray[1];	
	}
}

// ------------------------------------------------------------------------
/**
 * use to change dd/mm/yyyy date format in a "timestamp string" to mm/dd/yyyy
 * generally used to set date fields in FileMaker (fileMaker
 * only accepts mm/dd/yyyy formats for date fields)
 * i.e. dmy_to_mdy("28/3/2008"); becomes "3/28/2008"
 *
 */
 
if ( ! function_exists('timestamp_dmy_mdy'))
{
	function timestamp_dmy_mdy ($field) {
		$fieldArray = explode(" ", $field);
		$dateEach = explode("/", $fieldArray[0]);
		$date = strftime("%m/%d/%Y", mktime(0,0,0,$dateEach[1],$dateEach[0],$dateEach[2]));
		return $date." ".$fieldArray[1];
	}
}

// ------------------------------------------------------------------------

/** 
 * use for breaking a string thats a timestamp (contains Date and Time) into just date or
 * time. For example, Creation and Modification fields in FileMaker may be a timestamp.
 *
 * $option must be either "date" or "time".
 *
 *i.e. timestamp_split("14/5/2008 1:55:28 PM", "date"); returns "14/5/2008"
 *i.e. timestamp_split("14/5/2008 1:55:28 PM", "time"); returns "1:55:28 PM"
 */
if ( ! function_exists('timestamp_split'))
{

	function timestamp_split ($field, $option) {
	
		$timestamp = explode(" ", $field);
		
		switch($option) {
			case "date": $field = $timestamp[0]; break;//= substr($field, 0, 10); break;
			case "time"; $field = $timestamp[1]; break; //= substr($field, 11, strlen($field)); break;	
		}
	
		return $field;		
	}
}


// ------------------------------------------------------------------------

/** 
 * use for breaking a string thats a timestamp (contains Date and Time) into just date or
 * time. For example, Creation and Modification fields in FileMaker may be a timestamp.
 *
 * $option must be either "date" or "time".
 *
 *i.e. timestamp_split("14/5/2008 1:55:28 PM", "date"); returns "14/5/2008"
 *i.e. timestamp_split("14/5/2008 1:55:28 PM", "time"); returns "1:55:28 PM"
 */
if ( ! function_exists('time_min_to_hr'))
{

	function time_min_to_hr ($total_minutes) {
		
		if ($total_minutes > 0) {
			$time = explode('.', $total_minutes / 60);
			$hours = $time[0];
			$minutes = $total_minutes - ($hours * 60);
			
			if ($hours) {
				if ($hours == 1) {
					$results[] = $hours . ' hour';
				} else {
					$results[] = $hours . ' hours';
				}
			}
			
			if ($minutes) {
				if ($hours == 1) {
					$results[] = $minutes . ' minute';
				} else {
					$results[] = $minutes . ' minutes';
				}
			}
			
			return implode(' ', $results);
			
		} else {
			return '0';
		}
	}
}

// ------------------------------------------------------------------------

/** 
 * use for breaking a string thats a timestamp (contains Date and Time) into just date or
 * time. For example, Creation and Modification fields in FileMaker may be a timestamp.
 *
 * $option must be either "date" or "time".
 *
 *i.e. timestamp_split("14/5/2008 1:55:28 PM", "date"); returns "14/5/2008"
 *i.e. timestamp_split("14/5/2008 1:55:28 PM", "time"); returns "1:55:28 PM"
 */

if ( ! function_exists('valid_deadline'))
{

	function valid_deadline ($raw_end_date, $raw_start_date = NULL) {
		
		$current_date = mktime(date('H'),date('i'),date('s'), date('m'), date('d'), date('Y'));
		$end_date = strtotime($raw_end_date);
		$valid = false;
		
		//Use start and end time
		if ($raw_start_date) {
			$start_date = strtotime($raw_start_date);
			if ($start_date < $current_date && $current_date < $end_date) $valid = true;
			
		//Use end time only
		} else {

			if ($current_date < $end_date) $valid = true;
			
		}

		return $valid;

	}
}

// ------------------------------------------------------------------------

/** 
 * Get study level information
 *
 */

if ( ! function_exists('get_study_level_info'))
{

	function get_study_level_info ($course_code) {
		
		$course_code = str_replace('MUSC', '', $course_code);
		$study['level'] = substr($course_code, 0, 1);
		
		//Determine total hours
		switch ($study['level']) {
			case 1:
				$study['title'] = '1st';
				$study['hours'] = 10;
				$study['minutes'] = 600;
				$study['payment'] = 600;
				break;
			case 2:
				$study['title'] = '2nd';
				$study['hours'] = 10;
				$study['minutes'] = 600;
				$study['payment'] = 600;
				break;
			case 3:
				$study['title'] = '3rd';
				$study['hours'] = 12;
				$study['minutes'] = 720;
				$study['payment'] = 720;
				break;
			default:
				$study = 'Unable to determine your study level. Please contact the School as soon as possible.';
				break;
		}
		
		return $study;

	}
}

// ------------------------------------------------------------------------

/** 
 * Get study level information
 *
 */

if ( ! function_exists('send_email'))
{

	function send_email ($mail_info) {

		require_once("/data/www/groups/trc/other/scripts/phpmailer/class.phpmailer.php");

		$result = true;
	
		// Load Class
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		$mail->IsSMTP(); // telling the class to use SMTP
		
		try {
			$mail->Host = "localhost"; // SMTP server
			$mail->SMTPDebug  = 1; // enables SMTP debug information (for testing)
			$mail->AddAddress($mail_info['to']);
			$mail->SetFrom($mail_info['from']);
			$mail->Subject = $mail_info['subject'];
			$mail->AltBody = $mail_info['body_plain'];
			$mail->MsgHTML($mail_info['body_html']);
			
			// Attempt to Send with PHPMailer class via SMTP
			$mail->Send();
			
		//** ERRORS
		} catch (phpmailerException $e) {
			$result = $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
			$result = $e->getMessage(); //Boring error messages from anything else!
		}
		
		return $result;

	}
	
}

// ------------------------------------------------------------------------

/** 
 * Convert plain text to html
 *
 */

if ( ! function_exists('decode_html'))
{

	function decode_html ($content) {

		$content = '<p>' . str_replace("\n\n", '</p><p>', $content) . '</p>';
		$content = str_replace("\n", '<br />', $content);
		$content = html_entity_decode($content);
		
		return $content;

	}
	
}



// ------------------------------------------------------------------------

/* End of file MY_sugarcrm_helper.php */
/* Location: ./application/helpers/MY_sguarcrm_helper.php */




?>