<?php

class Download extends Secure_Controller {
	
	function assessments ($id) {
		
		//Get filename		
		$parameters = array(
						'session'=>$this->session->userdata('login_id'),
						'module_name'=>'la_Assessments',
						'id'=>$id,
						'select_fields'=>array(
							'id',
							'filename',
							'date_entered',
							'date_modified',
							'description',
							'deleted',
							'document_name',
							'file_ext',
							'file_mime_type',
							'active_date',
//							'exp_date',
							'category_id',
							'subcategory_id',
//							'status_id',
							'uploaded_by',
//							'trans_interp',
							'finished',
//							'hours_worked',
//							'draft_submission',
//							'approved_student',
//							'approved_tutor'
						),	
						'link_name_to_fields_array'=>'',
				);

		$result = rest_call('get_entry', $parameters);
		
		//Get the Assessment from Sugar and save to tmp folder
		$assessment = get_assessment($result->entry_list[0]->id, $result->entry_list[0]->name_value_list->filename->value);
		
		$data['title'] = 'Downloading File';
		$data['status'] = $assessment;
		$data['file'] = TMP_DIR . '/assessment/' . $id . '/' . $result->entry_list[0]->name_value_list->filename->value;

		if ($data['status'] == TRUE) {
			$this->load->view('file', $data);    
		} else {
			$this->load->view('templates/education/header', $data);
			$this->load->view('file', $data);
			$this->load->view('templates/education/footer', $data);
		}

	}

	function documents ($id) {
		
		$get_file = FALSE;
		
		//Get document from Sugar		
		$parameters = array(
				'session'=>$this->session->userdata('login_id'),
				'module_name'=>'Documents',
				'id'=>$id,
				'select_fields'=>array(
					'id',
					'document_name',
					'filename',
					'document_revision_id',
					'team_id',
				),
				'link_name_to_fields_array'=>'',
		);

		$result = rest_call('get_entry', $parameters);
			
		/* Check if entry was successfully retrieved
		 * If it was successfully retrieved, name_value_list will be an object
		 * Otherwise it will be an array
		 */
		if (is_object($result->entry_list[0]->name_value_list)) {
			
			$document = $result->entry_list[0]->name_value_list;
			
			//Check that the current user has access to this document - same team
			if ($document->team_id->value == $this->session->userdata('team_id')) {
				
				$get_file = get_document($result->entry_list[0]->id, $document->document_revision_id->value);
				
			//User does not have access to this document
			} else {
				$error = 'You do not have access to this document.';	
			}
		
		//Sugarbean not found
		} else {
			$error = 'Unable to retrieve this document';
		}

		//File OK
		if (is_string($get_file)) {
			
			$data['status'] = TRUE;
//			$data['file'] = TMP_DIR . '/document/' . $id . '/Testing upload assessment document available to all students.docx';// . $result->entry_list[0]->name_value_list->filename->value;
			$data['file'] = TMP_DIR . '/document/' . $id . '/' . $get_file;
			$this->load->view('file', $data); 
		
		//File ERROR
		} else {
			
			$data['status'] = FALSE;
			$data['title'] = 'File Download';
			$data['error'] = $error;
			$this->load->view('templates/education/header', $data);
			$this->load->view('file', $data);
			$this->load->view('templates/education/footer', $data);
			
		}

	}
	
}


?>