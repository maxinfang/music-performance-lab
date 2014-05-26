<div id="form">
	<br />
	<?php if (isset($errors)) { display_errors($errors); } ?>
  
  <?php echo form_open_multipart('/student/placement-form/update/#form'); ?>  
          
    <div class="field-row">
      <div class="field-label">Year</div>      
      <div class="field-field"><?php echo $this->session->userdata('year'); ?></div>
    </div>

    <div class="field-row">
      <div class="field-label">Semester</div>      
      <div class="field-field"><?php echo $this->session->userdata('semester'); ?></div>
    </div>


		<div class="field-row field-row-special-cons-other">
      <label for="course_code">Course code</label>
      <select name="course_code" id="course_code">
				<option value=""></option>
			<?php echo $form['edu_special_cons_details_c']; ?>
      </select>
		</div>

		<div class="field-row">
      <label for="ensemble">Ensemble</label>
      <select name="ensemble" id="ensemble">
				<option value=""></option>
				<?php echo $form['ensemble']['value']; ?>
      </select>
		</div>
  
    <div style="text-align:center"><input type="submit" name="submit" value="Continue" /></div>
  
  </form> 
</div>
<script type="text/javascript">
<!--
$(function () {
	
		//*** Special Consideration
		
		//Initate
		special_consideration();
		
		//Bind
		$('#edu_special_cons_c').bind('change', special_consideration);
		
		function special_consideration () {
			
			var value = $('#edu_special_cons_c :selected').val();
			var div = $('.field-row-special-cons-other');
			
			//Show textbox
			if (value.length !== '' && value != '_empty_') {
				div.show();

			//Hide textbox
			} else {
				div.hide();
			}
			
		}	

});
-->
</script>
