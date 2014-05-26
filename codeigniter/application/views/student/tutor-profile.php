<?php

if (isset($errors)) {
	
	display_errors($errors);

} else { ?>

	<?php echo form_open_multipart('/student/tutor-form/update', array('class'=>'form-plain')); ?>  
	<div class="table-two-column table-view">
      
		<?php $this->load->view("common/tutor-profile", $this->data); ?>


		<?php 
		
			$report[] = 'Reporting outdated information for the following tutor:'; //reset
			$report[] = 'Name: ' . $record->getField('first_name') . ' ' . $record->getField('last_name') ;
      $report[] = 'Email ' . $record->getField('email');

		?>

		<p style="text-align:right"><a href="mailto:sam@unsw.edu.au?subject=Update to tutor information&body=<?php echo implode('%0A', $report); ?>">Report outdated information</a></p>
		<div class="field-submit">
      <input type="hidden" name="tutor_id" id="tutor_id" value="<?php echo $record->getField('tutor_id'); ?>" />
      <input type="submit" name="select" value="Select this tutor" /> &nbsp;<a href="student/tutor-list">Back to list of tutors</a>
    </div>
        
  </div>
  </form>

  
<?php } ?>