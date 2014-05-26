<div id="form">
	<br />
	<?php if (isset($errors)) { display_errors($errors); } ?>
  
  <?php echo form_open_multipart('/student/course-form/update/#form'); ?>  
          
    <div class="field-row">
      <div class="field-label">Year</div>      
      <div class="field-field"><?php echo $this->session->userdata('system_year'); ?></div>
    </div>

    <div class="field-row">
      <div class="field-label">Semester</div>      
      <div class="field-field"><?php echo $this->session->userdata('system_semester'); ?></div>
    </div>

		<div class="field-row">
      <label for="course_code">Course code<span class="field-required" title="Required Field">*</span></label>
      <select name="course_code" id="course_code">
      <option value=""></option>
      <?php foreach ($valuelists['course_code'] as $field_value => $field_option) { ?>
        <option value="<?php echo $field_value; ?>" <?php if ($form['course_code']['value'] == $field_value) echo 'selected="selected"'; ?>><?php echo $field_option; ?></option>
      <?php } ?>
      </select>

		</div>

    <div style="text-align:center"><input type="submit" name="submit" value="Save & Continue" /></div>
  
  </form> 
</div>
<script type="text/javascript">
<!--

$(function() {
	
	<?php if (count($form['errors']) > 0) { ?>
	//* Get errors
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