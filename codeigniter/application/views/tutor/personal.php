<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>


<div class="table-two-column table-view">
<?php echo form_open_multipart('/tutor/personal/update'); ?>  

  <div class="field-row">
  <div class="field-label">First Name</div>
 	<div class="field-field"><?php echo $form['first_name']['value']; ?></div>
	</div>

  <div class="field-row">
  <div class="field-label">Last Name</div>
 	<div class="field-field"><?php echo $form['last_name']['value']; ?></div>
	</div>

  <div class="field-row">
    <label for="title">Title<span class="field-required" title="Required Field">*</span></label>
    <select name="title" id="title">
    <option value=""></option>
    <?php foreach ($valuelists['title'] as $field_value => $field_option) { ?>
      <option value="<?php echo $field_value; ?>" <?php if ($form['title']['value'] == $field_value) echo 'selected="selected"'; ?>><?php echo $field_option; ?></option>
    <?php } ?>
    </select>
  </div>

  <div class="field-row">
    <div class="field-label">Add to public list?<span class="field-required" title="Required Field">*</span></div>
    <div class="field-field">
    	<p>By adding yourself to the public list, you will be visible to students selecting a tutor and your information including your biography will be displayed on the website.</p>
      <input type="radio" name="public_list" id="public_list1" value="1" <?php if ($form['public_list']['value'] == "1") echo 'checked="checked"'; ?> />
			<label for="public_list1">Yes</label>
      <input type="radio" name="public_list" id="public_list0" value="0" <?php if ($form['public_list']['value'] == "0") echo 'checked="checked"'; ?> />
			<label for="public_list0">No</label>
		</div>
  </div>

  <div class="field-row">
    <label for="qualifications">Qualifications<span class="field-required" title="Required Field">*</span></label>
    <input type="text" name="qualifications" id="qualifications" size="40"  value="<?php echo $form['qualifications']['value']; ?>" />
  </div>

  <div class="field-row">
    <div class="field-label">Instruments<span class="field-required" title="Required Field">*</span></div>
    <div class="field-field">
    	<ul class="field-options-list field-list">
			<?php foreach ($valuelists['instruments'] as $field_value => $field_option) { $instruments_count ++; ?>
        <li>
        	<input type="checkbox" name="instruments[]" id="instruments<?php echo $instruments_count; ?>" value="<?php echo $field_value; ?>" <?php if (in_array($field_option, $form['instruments']['value'])) echo 'checked="checked"'; ?> />
					<label for="instruments<?php echo $instruments_count; ?>"><?php echo $field_option; ?></label>
          
          <?php if ($field_option == 'Other') { ?>
            <label for="instruments_other" class="instruments-other">- please specify</label>
            <input type="text" name="instruments_other" id="instruments_other" class="instruments-other" value="<?php echo $form['instruments_other']['value']; ?>" maxlength="50"/>
          <?php } ?>
        </li>
      <?php } ?>
      </ul>
		</div>
  </div>

  <div class="field-row">
    <label for="musical_styles">Musical styles<span class="field-required" title="Required Field">*</span></label>
    <input type="text" name="musical_styles" id="musical_styles" size="40"  value="<?php echo $form['musical_styles']['value']; ?>" />
  </div>

  <div class="field-row">
    <label for="biography">Biography<span class="field-required" title="Required Field">*</span></label>
    <div class="field-field">
    	<span id="biography-word-count">1500</span> characters remaining
    	<textarea name="biography" id="biography" class="textbox-large" maxlength="1500" rows="60"><?php echo $form['biography']['value']; ?></textarea>
    </div>
  </div>

  <div class="field-row field-row-heading">
    <div class="field-label">
      <h3>Payment</h3></div>
  </div>
  
  <div class="field-row">
    <div class="field-label">Payment Method<span class="field-required" title="Required Field">*</span></div>
    <div class="field-field">
			<?php foreach ($valuelists['payment_method'] as $field_value => $field_option) { $payment_count ++; ?>
        <input type="radio" name="payment_method" id="payment_method<?php echo $payment_count; ?>" value="<?php echo $field_value; ?>" <?php if ($form['payment_method']['value'] == $field_value) echo 'checked="checked"'; ?> />
        <label for="payment_method<?php echo $payment_count; ?>"><?php echo $field_option; ?></label>
      <?php } ?>
    </div>
  </div>

  <div class="field-row field-payment-method field-payment-method-HR">
  <label for="zid">zID</label>
  <input type="text" name="zid" id="zid" value="<?php echo $form['zid']['value']; ?>" />
  Should start with a z followed by 7 digits (i.e. z1234567)
  </div>

  <div class="field-row field-payment-method field-payment-method-Invoice">
  <label for="vendor_id">Vendor ID</label>
  <input type="text" name="vendor_id" id="vendor_id" value="<?php echo $form['vendor_id']['value']; ?>" />
  </div>

  <div class="field-row field-row-heading">
    <div class="field-label">
      <h3>Contact information</h3></div>
  </div>
  
  <div class="field-row">
  <label for="email">E-mail Address<span class="field-required" title="Required Field">*</span></label>
  <input type="text" name="email" id="email" value="<?php echo $form['email']['value']; ?>" />
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
      <h3>Studio address</h3>
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
  <div class="field-submit">
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
<script type="text/javascript" src="<?php echo base_url('assets/js/tutor/personal-form.js');?>"></script> 
