<?php

if (isset($errors)) {
	
	display_errors($errors);

} else { ?>

	<p>This is your public profile that will only be visible to students who need to select a tutor for their studies.</p>
  
  <?php if ($record->getField('public_list') == 1) { ?>

    <p class="success">You have currently opted to be added to our public list, which means your details below are currently visible to our students. If you wish to change this setting and update your information, you can do so under <a href="/music-performance-lab/tutor/personal">Personal Information</a>.</p>
  
  <?php } else { ?>
	
   <p class="error">You have currently opted to NOT be added to our public list, which means your details below are NOT visible to our students. <a href="/music-performance-lab/tutor/personal">Change this setting and update your information</a>.</p>
	  
  <?php } ?>
  
	<div class="table-two-column table-view">
  
    <h2><?php echo $record->getField('first_name'); ?> <?php echo $record->getField('last_name'); ?></h2>
    
		<?php $this->load->view("common/tutor-profile", $this->data); ?>
    
  </div>
  
<?php } ?>