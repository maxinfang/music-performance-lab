<p>Please fill out for each lesson to verify your attendance. Students MUST finish all lessons by <strong><?php echo timestamp_mdy_dmy($this->session->userdata('system_lesson_log_end')); ?></strong>.</p>
<p>Please organise lessons with your tutor with this in mind at the beginning of the semester</p>

<h2>Payment details for <?php echo $study['title']; ?> years</h2>
<ul>
<li><strong>$<?php echo $study['payment']; ?></strong> (if registered for GST, <strong>$<?php echo $study['payment'] * 1.10; ?></strong>)</li>
<li><strong><?php echo $study['hours']; ?></strong> hours of tuition in total (e.g. <strong><?php echo $study['hours']; ?></strong> lessons at <strong>1 hour</strong> in length)</li>
</ul>

<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>

<?php echo form_open_multipart('/student/lesson-form/update'); ?>  
<table class="table-row" id="lesson-table">

	<tr>
  <th scope="col">Date</th>
  <th scope="col">Length of Lesson (in minutes)</th>
  <th scope="col">Tutor</th>
  <th scope="col">Action</th>
  </tr>
    
	<?php foreach  ($form['row_array'] as $x => $row) {
		
			$remove_button = 'remove_' . $x;	
	?>
			
			<tr id="row_<?php echo $x; ?>">
      <td>
      	<input type="hidden" value="<?php echo $row['lesson_id']; ?>" name="lesson_<?php echo $x; ?>" />
        <input type="hidden" value="<?php echo $row['date']; ?>" name="date_<?php echo $x; ?>" />
				<?php echo $row['date']; ?></td>
      <td>
				<input type="hidden" value="<?php echo $row['length']; ?>" name="length_<?php echo $x; ?>" class="length"/>
				<?php echo $row['length']; ?>
      </td>
    	<td><input type="hidden" value="<?php echo $row['tutor_id']; ?>" name="tutor_id_<?php echo $x; ?>" />
      	<?php echo $row['tutor_name']; ?>
      </td>
      <td><input type="button" value="- Remove" class="form-button-remove" id="<?php echo $remove_button; ?>" name="<?php echo $remove_button; ?>" /></td>
      </tr>
			
	<?php	
			
			$total_minutes = $total_minutes + $row['length'];
	
	} //end for each ?>
    
  <tr id="row-new">
  <td><input type="text" value="<?php echo $this->input->post('date_new'); ?>" name="date_new" id="date_new" class="date-field" /></td>
  <td><input type="text" size="5" value="<?php echo $this->input->post('length_new'); ?>" maxlength="3" name="length_new" id="length_new" class="length-field" /></td>
  <td><select name="tutor_id_new" id="tutor_id_new" />
		<?php foreach ($valuelists['tutors'] as $tutor_name=>$tutor_id) { ?>
      <option value="<?php echo $tutor_id; ?>" <?php if ($this->input->post['tutor_id_new'] == $tutor_id) echo 'selected="selected"'; ?>><?php echo $tutor_name; ?></option>
    <?php } ?>
  </td>
	<td><input type="button" value="+ Add" id="form-button-add" name="add_new"/></td>
  </tr>
    
</table>

<div id="lesson-length-total">
Total hours: <strong><span id="total_hours"><?php echo time_min_to_hr ($total_minutes); ?></span></strong>
</div>

<div class="form-navigation">
	<input type="hidden" name="current_rows" id="current_rows" value="<?php echo $form['current_rows']; ?>" />
	<input type="hidden" name="next_row" id="next_row" value="<?php echo $form['total_row']; ?>" />
	<input type="hidden" name="total_row" id="total_row" value="<?php echo $form['total_row']; ?>" />

  <p class="important">IMPORTANT</p>
	<p><strong>Save Progress</strong> will save your changes only and you will be able to return and make changes.<br />
  <strong>Submit</strong> will mark your lesson log as complete and you will <strong>NO</strong> longer have access to return and make changes.</p>

	<p><input type="submit" name="submit" value="Save Progress" class="form-button-save"/> - or - 
  <input type="submit" name="submit" value="Submit" class="form-button-submit" /></p>
  
</div>

</form>
<script type="text/javascript" src="<?php echo base_url('assets/js/lesson-log.js');?>"></script> 
