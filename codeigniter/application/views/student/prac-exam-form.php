<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>

<?php echo form_open_multipart('/student/prac-exam-form/update'); ?>  

<div class="form-two-column">


  <div class="field-row field-row-left">
    <label for="ensemble">Ensemble you are enrolled in this semester<span class="field-required" title="Required Field">*</span></label>
    <select name="ensemble" id="ensemble">
    <option value=""></option>
    <?php foreach ($valuelists['ensemble'] as $field_value => $field_option) { ?>
      <option value="<?php echo $field_value; ?>" <?php if ($form['ensemble']['value'] == $field_value) echo 'selected="selected"'; ?>><?php echo $field_option; ?></option>
    <?php } ?>
    </select>
  </div>

  <h2>Technical Work/Study/Vocalise</h2>

  <table class="table-row">
  
    <tr>
    <th scope="col">Title</th>
    <th scope="col">Composer</th>
    <th scope="col">Duration (in minutes)</th>
    </tr>
      
    <tr>
    <td><input type="text" value="<?php echo $form['tech_title']['value']; ?>" name="tech_title" id="tech_title" class="compulsory-field" /></td>
    <td><input type="text" value="<?php echo $form['tech_composer']['value']; ?>" name="tech_composer" id="tech_composer" class="compulsory-field" /></td>
    <td><input type="text" value="<?php echo $form['tech_duration']['value']; ?>" name="tech_duration" id="tech_duration" class="compulsory-field duration" maxlength="2" /></td>
    </tr>
      
  </table>

  <h2>Other works for examination</h2>

  <table class="table-row" id="lesson-table">
  
    <tr>
    <th scope="col">Title</th>
    <th scope="col">Composer</th>
    <th scope="col">Duration (in minutes)</th>
    <th scope="col">Action</th>
    </tr>
      
    <?php foreach  ($form['row_array'] as $x => $row) {
      
        $remove_button = 'remove_' . $x;	
    ?>
        
        <tr id="row_<?php echo $x; ?>">
        <td>
          <input type="hidden" value="<?php echo $row['ow_id']; ?>" name="ow_id_<?php echo $x; ?>" />
          <input type="hidden" value="<?php echo $row['title']; ?>" name="title_<?php echo $x; ?>" />
          <?php echo $row['title']; ?></td>
        <td>
          <input type="hidden" value="<?php echo $row['composer']; ?>" name="composer_<?php echo $x; ?>" />
          <?php echo $row['composer']; ?>
        </td>
        <td>
          <input type="hidden" value="<?php echo $row['duration']; ?>" class="duration" name="duration_<?php echo $x; ?>" />
          <?php echo $row['duration']; ?>
        </td>
        <td><input type="button" value="- Remove" class="form-button-remove" id="<?php echo $remove_button; ?>" name="<?php echo $remove_button; ?>" /></td>
        </tr>
        
    <?php	
		
				$total_minutes = $total_minutes + $row['duration'];

			} //end for each ?>
      
    <tr id="row-new">
    <td><input type="text" value="<?php echo $this->input->post('title_new'); ?>" name="title_new" id="title_new" class="compulsory-field" /></td>
    <td><input type="text" value="<?php echo $this->input->post('composer_new'); ?>" name="composer_new" id="composer_new" class="compulsory-field" /></td>
    <td><input type="text" value="<?php echo $this->input->post('duration_new'); ?>" name="duration_new" id="duration_new" class="compulsory-field" maxlength="2" /></td>
    <td><input type="button" value="+ Add" id="form-button-add" name="add_new"/></td>
    </tr>
      
  </table>

  <div id="lesson-length-total">
	<p>The total duration of all your works must be between 15 to 25 minutes </p>
  Total minutes: <strong><span id="total_minutes"><?php echo $total_minutes; ?></span></strong>
  </div>

  <h2>Declaration</h2>
  
  <input type="checkbox" name="declaration" id="declaration" value="1" <?php if ($form['declaration']['value'] == '1') echo 'checked="checked"'; ?> /> <label for="declaration"> I have not previously performed any of these works for assessment at UNSW</label>

</div>

<div class="form-navigation">
	<input type="hidden" name="current_rows" id="current_rows" value="<?php echo $form['current_rows']; ?>" />
	<input type="hidden" name="next_row" id="next_row" value="<?php echo $form['total_row']; ?>" />
	<input type="hidden" name="total_row" id="total_row" value="<?php echo $form['total_row']; ?>" />

	<hr />
  <p class="important">IMPORTANT</p>
	<p><strong>Save Progress</strong> will save your changes only and you will be able to return and make changes.<br />
  <strong>Submit</strong> will mark your lesson log as complete and you will <strong>NO</strong> longer have access to return and make changes.</p>

	<p><input type="submit" name="submit" value="Save Progress" class="form-button-save"/> - or - 
  <input type="submit" name="submit" value="Submit" class="form-button-submit" /></p>
  
</div>

</form>
<script type="text/javascript">
<!--

$(function() {
	
	//* Get errors
	<?php if (count($form['errors']) > 0) { ?>
		var errors = <?php echo json_encode($form['errors']); ?>;
		
		//Style each field
		$.each(errors, function (key, value) {
			if (value == 1) {
				$('#' + key).css('border', '1px solid red');
			}			
		});
	<?php } ?>

});
	
-->
</script>

<script type="text/javascript" src="<?php echo base_url('assets/js/prac-exam-form.js');?>"></script> 
