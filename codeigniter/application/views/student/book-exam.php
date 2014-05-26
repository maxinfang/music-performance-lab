<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>


<div class="table-two-column table-view">
   
  <div class="field-row">                       
		<div class="field-label">Do you require drums or an amp?</div>
    <div class="field-field"><?php if ($record->getField('exam_drum_amp') == "1") { echo 'Yes'; } else { echo "No";  } ?></div>                
	</div>
  
  <div class="field-row">
  	<div class="field-label">Room</div>
    <div class="field-field"><?php echo $record->getField('exam_room'); ?></div>                
	</div>      

  <div class="field-row">
  	<div class="field-label">Date</div>
    <div class="field-field"><?php echo $record->getField('exam_date'); ?></div>
 	</div>      
	
  <div class="field-row">
  	<div class="field-label">Time</div>
    <div class="field-field"><?php echo $record->getField('exam_time'); ?></div>
  </div>            
  
  <?php 
	//allow changes to booking
	if ($this->session->userdata('system_book_exam_open') == 1) { ?>
  <div class="field-row">
  	<div class="field-label">&nbsp;</div>
  	<div class="field-field"><a href="student/book-exam-form/">Change booking details</a></div>
  </div>
	<?php } ?>
</div>

