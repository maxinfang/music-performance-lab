<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class System_Controller extends CI_Controller {
	
	public function __construct() {
		
		parent::__construct();

		//Establish connection to database
		$this->fm_db = db_init();

		if ($this->session->userdata('system') != 1 or $this->router->fetch_class() ==  'closed' or $this->router->fetch_class() !=  'login') {
						
			//*** Set System variables
			
			//Find the active setting records from database
			$find = $this->fm_db->newFindCommand('www_setting');
			$find->addFindCriterion('c_portal_status', '==1');
			$find->addSortRule('created', 1, FILEMAKER_SORT_DESCEND);
			$find->setRange(0, 1); //just want the latest record
			$result = $find->execute();
			if (FileMaker::isError($result)) {
				//setting not detected
				$this->session->set_userdata('system_open', 0);	
			} else {
							
				$records = $result->getRecords();
				$record = $records[0]; //work with the last record only
				
				//Set system settings into session
				$this->session->set_userdata('system_id', $record->getField('setting_id'));	
				$this->session->set_userdata('system_rid', $record->getRecordId());	
				$this->session->set_userdata('system_open', $record->getField('c_portal_status'));	
				$this->session->set_userdata('system_start', $record->getField('portal_start'));	
				$this->session->set_userdata('system_year', $record->getField('year'));
				$this->session->set_userdata('system_semester', $record->getField('semester'));
				$this->session->set_userdata('system_lesson_log_end', $record->getField('lesson_log_end'));
				$this->session->set_userdata('system_practical_exam_end', $record->getField('practical_exam_end'));
				$this->session->set_userdata('system_progress_report_end', $record->getField('progress_report_end'));
				$this->session->set_userdata('system_book_exam_start', $record->getField('exam_booking_start'));
				$this->session->set_userdata('system_book_exam_end', $record->getField('exam_booking_end'));
				$this->session->set_userdata('system_display_exam_results', $record->getField('display_exam_results'));
				
			}

			$this->session->set_userdata('system', 1);	
			
		}
		

		//Redirect if portal isn't open
		if ($this->session->userdata('system_open') != 1 && $this->router->fetch_class() !=  'closed') { //so it doesn't loop		
			header('Location: /music-performance-lab/closed');
			die();
		}

	}

} 


//Controller for Students
class Secure_Controller extends System_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		//Logged in
		if ($this->session->userdata('login_ok') == 1) {

		//Not Logged in - redirect to the main page
		} else {
			redirect('');
			die();
		}	
		
	}

} 

//Controller for Tutors
class Tutor_Controller extends System_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		//Not Logged in - redirect to the login page
		if ($this->session->userdata('login_ok') != 1 && $this->uri->segment(2) != 'login') {
			redirect('tutor/login');
			die();
		}
		
	}

} 

