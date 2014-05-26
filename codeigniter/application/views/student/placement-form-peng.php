<div id="form">
	<br />
	<?php if (isset($errors)) { display_errors($errors); } ?>
  
  <?php echo form_open_multipart('/soe-student/placement-form/update/#form'); ?>  
          
		<?php if ($form['strand_enrolled'] == 'PrimarySecondary') { ?>

      <div class="field-row">
        <label for="strand_enrolled">Enrolled into</label>
              
        <div class="field-field" id="strand_enrolled">K-6 or Secondary Learning Support</div>
        
      </div>
  
      <div class="field-row">
        <label for="edu_strand_preference_c">Placement Preference<span class="field-required" title="Required Field">*</span></label>
              
        <div class="field-field">
          <select id="edu_strand_preference_c" name="edu_strand_preference_c">
            <option value=""></option>
            <option value="K6" <?php if ($form['edu_strand_preference_c'] == 'K6') echo 'selected="selected"'; ?>>K-6 Classroom</option>
            <option value="SecondaryLearningSupport" <?php if ($form['edu_strand_preference_c'] == 'SecondaryLearningSupport') echo 'selected="selected"'; ?>>Secondary School Learning Support</option>
          </select>
          <p class="note">Please be aware there is no guarantee of receiving placement preference. Depends on availability.</p>
        </div>
    
      </div>

		<?php } else { 
			//Default strand preference to ASPIRE
		?>
      <div class="field-row">
        <label for="strand_enrolled">Enrolled into</label>
              
        <div class="field-field" id="strand_enrolled">
        	ASPIRE
         	<input type="hidden" name="edu_strand_preference_c" value="ASPIRE" />
				</div>
        
      </div>

    <?php } ?>
    
    <div class="field-row">
      <label for="edu_availability_c">Day or Days Available</label>
      
      <div class="field-field">
        <?php foreach ($valuelists['edu_availability_c'] as $field_value => $field_option) { $availability_count ++;?>
          <input type="checkbox" name="edu_availability_c[]" id="edu_availability_c<?php echo $availability_count; ?>" value="<?php echo $field_value; ?>" <?php if (in_array($field_value, $form['edu_availability_c'])) echo 'checked="checked"'; ?> />
          <label for="edu_availability_c<?php echo $availability_count; ?>"><?php echo $field_option->value; ?></label><br />
        <?php } ?>

      </div>
  
    </div>

    <div class="field-row">
      <label for="edu_teaching_interests_c">Teaching-related Interests and Skills<span class="field-required" title="Required Field">*</span><br />
			<span class="note">(Example: Arts, Sports, IT, Music)</span></label>
      <textarea name="edu_teaching_interests_c" class="textbox-medium" id="edu_teaching_interests_c"><?php echo $form['edu_teaching_interests_c']; ?></textarea>
    </div>
		
    <div class="field-row">
      <label for="edu_special_cons_c">Special Considerations</label>
      
      <div class="field-field">
        <select id="edu_special_cons_c" name="edu_special_cons_c">
        <?php foreach ($valuelists['edu_special_cons_c'] as $field_value => $field_option) { ?>
          <option value="<?php echo $field_value; ?>" <?php if ($form['edu_special_cons_c'] == $field_value) echo 'selected="selected"'; ?>><?php echo $field_option->value; ?></option>
        <?php } ?>
        </select>        
      </div>
  
    </div>

		<div class="field-row field-row-special-cons-other">
      <label for="edu_special_cons_details_c">Please provide details of your request for special considerations<br /><em>Maximum 150 characters</em></label>
      <textarea name="edu_special_cons_details_c" id="edu_special_cons_details_c" class="textbox-small"><?php echo $form['edu_special_cons_details_c']; ?></textarea>
		</div>
  
    <div style="text-align:center"><input type="submit" name="submit" value="Apply" /></div>
  
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
