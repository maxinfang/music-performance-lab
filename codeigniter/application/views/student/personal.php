<p>You may return to update this information.</p>

<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>


<div class="form-two-column">
<?php echo form_open_multipart('/student/personal/update'); ?>  
  <div class="field-row">
  <div class="field-label">zID</div>
 	<div class="field-field"><?php echo $form['zid']['value']; ?></div>
	</div>

  <div class="field-row">
  <div class="field-label">First Name</div>
 	<div class="field-field"><?php echo $form['first_name']['value']; ?></div>
	</div>

  <div class="field-row">
  <div class="field-label">Last Name</div>
 	<div class="field-field"><?php echo $form['last_name']['value']; ?></div>
	</div>

  <div class="field-row">
  <div class="field-label">Degree</div>
  <div class="field-field"><?php echo $form['degree']['value']; ?></div>
  </div>

  <div class="field-row">
  	<?php if ($form['instrument']['edit'] == "0") { ?>
      <div class="field-label">Instrument</div>
      <div class="field-field"><?php echo $form['instrument']['value']; ?></div>
    <?php } else { ?> 
    
      <label for="instrument">Instrument<span class="field-required" title="Required Field">*</span></label>
      <select name="instrument" id="instrument">
      <option value=""></option>
      <?php foreach ($valuelists['instrument'] as $field_value => $field_option) { ?>
        <option value="<?php echo $field_value; ?>" <?php if ($form['instrument']['value'] == $field_value) echo 'selected="selected"'; ?>><?php echo $field_option; ?></option>
      <?php } ?>
      </select>

		<?php } ?>
  </div>

  <div class="field-row field-row-heading">
    <div class="field-label">
      <h3>Contact Information</h3></div>
  </div>
  
  <div class="field-row">
  <div class="field-label">E-mail Address</div>
 	<div class="field-field"><?php echo mailto($form['email']['value']); ?></div>
	</div>
    
  <div class="field-row">
  <label for="phone_home">Home Phone<span class="field-required" title="Required Field">*</span></label>
  <input type="text" name="phone_home" id="phone_home" value="<?php echo $form['phone_home']['value']; ?>" />
  </div>

  <div class="field-row">
  <label for="phone_mobile">Mobile Contact<span class="field-required" title="Required Field">*</span></label>
  <input type="text" name="phone_mobile" id="phone_mobile" value="<?php echo $form['phone_mobile']['value']; ?>" />
  </div>

  <div class="field-row field-row-heading">
    <div class="field-label">
      <h3>Address</h3>
    </div>
  </div>

  <div class="field-row">
    <div class="field-field"><em>Please provide your address in Australia, NSW.</em></div>
  </div>

  <div class="field-row">
    <label for="address_line_1">Address Line 1<span class="field-required" title="Required Field">*</span></label>
    <input type="text" name="address_line_1" id="address_line_1" size="40"  value="<?php echo $form['address_line_1']['value']; ?>" />
  </div>

  <div class="field-row">
    <label for="address_line_2">Address Line 2</label>
    <input type="text" name="address_line_2" id="address_line_2" size="40"  value="<?php echo $form['address_line_2']['value']; ?>" />
  </div>

  <div class="field-row">
    <label for="suburb">Suburb<span class="field-required" title="Required Field">*</span></label>
    <input type="text" name="suburb" id="suburb" value="<?php echo $form['suburb']['value']; ?>" />
  </div>

  <div class="field-row">
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
			if (value == 1) {
				$('#' + key).css('border', '1px solid red');
			}			
		});
	<?php } ?>

});
	
-->
</script>
