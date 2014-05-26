<?php echo  $content; ?>

<?php /*if ($this->session->userdata('system_display_exam_results') == 1) { ?>

  <h2>Grade</h2>
  
  <?php
  
	$placement_record = $database->getRecordById('www_placement_prac_exam', $this->session->userdata('placement_rid'));
	if (FileMaker::isError($placement_record)) {
		display_errors('Unable to identify your placement details');
	} else {
		
		echo 'Your grade is: ' . $placement_record->getField('c_total_score');
		
	}
	
	?>
	
	<?php //display_message($this->session->flashdata('message')); ?>
  <?php //if (isset($errors)) { display_errors($errors); } ?>

<?php  }*/ ?>
