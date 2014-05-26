<?php 

class Upload extends Secure_Controller {
	
	function __construct() {
		
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->helper('file');		
		
		/* Include the upload library so we can use some of the functions.
		 * However, we wont be using the upload_do function from the library to do the upload, since we
		 * need to create sugarbeans and directories before uploading, so we will
		 * use our own code for uploads
		 *
		 * Set $config to be be used for the upload library. 
		 */
		$config['upload_path'] = TMP_DIR;
		$config['allowed_types'] = implode('|', unserialize(FILE_TYPE));
		$this->load->library('upload', $config);
	}
	
	function assessment($placement_id) {
				
		//***** Set Variables
		
		/* The path of where the form was submitted
		 * This way, we can redirect back if there are errors
		 */ 
		$return_path = $this->input->post('current_path');
		/* If somehow the return path was not posted, redirect to the user's default homepage */
		if ($return_path == '') {
			$return_path = user_default_homepage;
		}

		//Submission variables
		$file = $_FILES['file'];
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		$uploaded_by_user = $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name');
		if (strstr($this->session->userdata['user_type'], 'Student')) {
			$uploaded_by = 'Student';
		} else {
			$uploaded_by = 'Supervisor';
		}

		//***** Validate Form
		if (!$placement_id or $placement_id == '') {
			$errors[] = 'Unable to identify the record to upload this file to';
		}
			
		//Name
		if ($name == '') {
			$errors[] = 'Assignment Name is a required field';

		//File - Use our custom function from the upload library
		} elseif ($this->upload->validate_upload('file')) {
			
			/***** File is OK to Upload
			 ***** Proceed to create the sugarbeans and upload the file to the sugar directory
			 *****/
			 
			//Create New Assessment Record
			$current_date = date("Y-m-d", mktime());
			$parameters = array(
				'session'=>$this->session->userdata('login_id'),
				'module_name'=>'la_Assessments',
				'name_value_list'=>array(
					array('name'=>'document_name', 'value'=>$name),
					array('name'=>'description', 'value'=>$description),
					array('name'=>'uploaded_by', 'value'=>$uploaded_by),
					array('name'=>'uploaded_by_user_c', 'value'=>$uploaded_by_user),
					array('name'=>'active_date', 'value'=>$current_date),
					array('name'=>'filename', 'value'=>$file['name']),
					array('name'=>'team_id', 'value'=>$this->session->userdata('team_id')),				
				),
			);
	
			$result = rest_call('set_entry', $parameters);
			$assessment_id = $result->id;
					
//			$assessment_id = 'd2604739-06e6-9c96-387c-4ff3d1d83634';
			//Check Assessment creation was successful
			if ($assessment_id) {

				//Add Attachment for this Assessment
/*				$parameters = array(
					'session'=>$this->session->userdata('login_id'),
					'note'=>array(
						'id'=>$assessment_id,
						'filename_id'=>$assessment_id,
						'file'=>base64_encode(file_get_contents($file['tmp_name'])),
					),
				);
		
				$result = rest_call('set_note_attachment', $parameters);
				
				print_ob($result);
//				print_ob($result);
				
				//Check Attachment creation was successful
//				if ($result->id) {
*/					
					/* Set Relationship for New Assessment to Placement Record
					 * The following parameters were used in the portals developed
					 * by LogicAppeal
					 */
					$parameters = array(
						'session'=>$this->session->userdata('login_id'),
						'module_name'=>'la_Assessments',
						'module_id'=>$assessment_id,
						'link_field_name'=>'la_placeme555dcements_ida', 
						'related_ids'=>array($placement_id),
					);
	
					/* Unsure why the following parameters (below) were not used, but we'll stick
					 * with the ones that LogicAppeal used (above)
					
						$parameters = array(
							'session'=>$this->session->userdata('login_id'),
							'module_name'=>'la_Placements',
							'module_id'=>$placement_id,
							//'link_field_name'=>'la_placements_la_assessments',
							'related_ids'=>array($assessment_id),
						);
					
					*/
					
					$result = rest_call('set_relationship', $parameters);
					
					//Check that Assessment and Placement has been linked
					if ($result->created == 1) {
							
						/* Upload the tmp file manually because for some reason
						 * using the REST API for set_note_attachment (above) does not upload
						 * the file correctly
						 *
						 * The Filename must be the Assessment ID - In SugarCRM, it automatically
						 * looks for the file named as the Assessment ID
						 *
						 */
						$file_path = SUGAR_DIR . '/' . $assessment_id;
								
						//Move the uploaded file to the SugarCRM file directory
						$upload = move_uploaded_file($file['tmp_name'], $file_path);
				
						//Upload Successful
						if ($upload == TRUE) {
							$message = $file['name'] . ' was uploaded successfully';
						} else {
							$errors[] = 'Unable to successfully upload ' . $file['name'];
						}
					
					} else {
						$errors[] = 'Unable to add Assessment to Placement Record';
					} //End Relationship Check

//				} else {
//					$errors[] = 'Unable to create a record for the attachment';
//				} //End Note ID Check
				
			} else {
				$errors[] = 'Unable to create a record for this assessment';
			} //End Assessment ID Check

		} else {
				
			//Set Error from our custom function from the upload library
			foreach ($this->upload->error_msg as $error) {
				$errors[] = $error;
			}

		}
		
		//Flash Data
		$this->session->set_flashdata('form_message', $message);
		$this->session->set_flashdata('form_errors', $errors);
		if ($errors) {
			$this->session->set_flashdata('form_field_name', $name);
			$this->session->set_flashdata('form_field_description', $description);
		}
		
		//Redirect
		redirect($return_path);


	}
	

}

?>