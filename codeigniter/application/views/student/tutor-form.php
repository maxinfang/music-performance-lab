<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>


<div class="form-two-column">
<?php echo form_open_multipart('/student/tutor-form/update'); ?>  

  <div class="field-row">
	    <div class="field-label"><label for="tutor_id">Please select a tutor from the list</label></div>
      <div class="field-field">
        <select name="tutor_id" id="tutor_id">
          <option value=""></option>
          <?php foreach ($tutors as $tutor_rid => $tutor_info) { ?>
            <option value="<?php echo $tutor_info['id']; ?>" <?php if ($form['tutor_id']['value'] == $tutor_info['id']) echo 'selected="selected"'; ?>><?php echo strtoupper($tutor_info['last_name']) . ', ' . $tutor_info['first_name']; ?></option>
          <?php } ?>
        </select> &nbsp; <a href="student/tutor-list">See complete list of tutor information</a>
      </div>
  </div>

  <div class="field-row">
    <label for="new_tutor_request">Request a new tutor who is not listed</label>
    <input type="checkbox" name="new_tutor_request" id="new_tutor_request" value="1" <?php if ($form['new_tutor_request']['value'] == "1") echo 'checked="checked"'; ?> />
  </div>

  <div class="field-row field-row-heading field-new-tutor">
    <div class="field-label">
      <h3>New Tutor Information</h3></div>
  </div>
      

  <div class="field-row field-new-tutor">
  <label for="first_name">First Name<span class="field-required" title="Required Field">*</span></label>
  <input type="text" name="first_name" id="first_name" value="<?php echo $form['first_name']['value']; ?>" />
  </div>

  <div class="field-row field-new-tutor">
  <label for="last_name">Last Name<span class="field-required" title="Required Field">*</span></label>
  <input type="text" name="last_name" id="last_name" value="<?php echo $form['last_name']['value']; ?>" />
  </div>
  
  <div class="field-row field-new-tutor">
	  <div class="field-label">Instruments<span class="field-required" title="Required Field">*</span></div>
    <div class="field-field">
    	<ul class="field-list">
			<?php foreach ($valuelists['instruments'] as $field_value => $field_option) { $instrument_count ++; ?>
        <li><input name="instruments[]" id="instrument_<?php echo $instrument_count; ?>" type="checkbox" value="<?php echo $field_value; ?>" <?php if (in_array($field_value, $form['instruments']['value'])) echo 'checked="checked"'; ?> /> <label for="instrument_<?php echo $instrument_count; ?>"><?php echo $field_option; ?></label></li>
      <?php } ?>
      </ul>
    </div>
  </div>

  <div class="field-row field-new-tutor">
  <label for="email">E-mail Address<span class="field-required" title="Required Field">*</span></label>
  <input type="text" name="email" id="email" value="<?php echo $form['email']['value']; ?>" />
  </div>

  <div class="field-row field-new-tutor">
  <label for="phone_home">Home Phone<span class="field-required" title="Required Field">*</span></label>
  <input type="text" name="phone_home" id="phone_home" value="<?php echo $form['phone_home']['value']; ?>" />
  </div>

  <div class="field-row field-new-tutor">
  <label for="phone_mobile">Mobile Contact<span class="field-required" title="Required Field">*</span></label>
  <input type="text" name="phone_mobile" id="phone_mobile" value="<?php echo $form['phone_mobile']['value']; ?>" />
  </div>

  <div class="field-row field-new-tutor">
    <label for="address_line_1">Address Line 1<span class="field-required" title="Required Field">*</span></label>
    <input type="text" name="address_line_1" id="address_line_1" size="40"  value="<?php echo $form['address_line_1']['value']; ?>" />
  </div>

  <div class="field-row field-new-tutor">
    <label for="address_line_2">Address Line 2</label>
    <input type="text" name="address_line_2" id="address_line_2" size="40"  value="<?php echo $form['address_line_2']['value']; ?>" />
  </div>

  <div class="field-row field-new-tutor">
    <label for="suburb">Suburb<span class="field-required" title="Required Field">*</span></label>
    <input type="text" name="suburb" id="suburb" value="<?php echo $form['suburb']['value']; ?>" />
  </div>

  <div class="field-row field-new-tutor">
    <label for="postcode">Postcode<span class="field-required" title="Required Field">*</span></label>
    <input type="text" name="postcode" id="postcode" value="<?php echo $form['postcode']['value']; ?>" />
  </div>

  <div class="field-row">
  	<div class="field-field-submit">
    	<input type="submit" name="submit" value="Save" />
    </div>
  </div>
  
</form> 
</div>

<script type="text/javascript">
<!--

$(function() {
	
	//* Get errors
	<?php if (count($form['errors']) > 0) { ?>
		var errors = <?php echo json_encode($form['errors']); ?>;
		
		//Style each field
		$.each(errors, function (key, value) {
			if (value == '1') {
				$('#' + key).css('border', '1px solid red');
			}			
		});
	<?php } ?>

	//* Bind function to elements
	$('#new_tutor_request').bind('change', new_tutor);
	
	//* Initiate functions to run once on load
	new_tutor();
	
	//* Request new tutor
	function new_tutor() {
		
		if ($('#new_tutor_request').is(':checked')) {
			$('.field-new-tutor').show();
			$('#tutor_id').attr('disabled', 'disabled');
		} else {
			$('.field-new-tutor').hide();
			$('#tutor_id').removeAttr('disabled');
		}

	}
});
	
-->
</script>
