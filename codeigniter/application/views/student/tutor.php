<div class="table-two-column table-view">
    
  <div class="field-row">
    <div class="field-label">First Name</div>
    <div class="field-field"><?php echo $record->getField('first_name'); ?></div>
  </div>

  <div class="field-row">
    <div class="field-label">Last Name</div>
    <div class="field-field"><?php echo $record->getField('last_name'); ?></div>
  </div>
  
  <?php $this->load->view("common/tutor-profile", $this->data); ?>


  <?php 
  
    $report[] = 'Reporting outdated information for the following tutor:'; //reset
    $report[] = 'Name: ' . $record->getField('first_name') . ' ' . $record->getField('last_name') ;
    $report[] = 'Email ' . $record->getField('email');

  ?>

  <p style="text-align:right"><a href="mailto:sam@unsw.edu.au?subject=Update to tutor information&body=<?php echo implode('%0A', $report); ?>">Report outdated information</a></p>
      
</div>
