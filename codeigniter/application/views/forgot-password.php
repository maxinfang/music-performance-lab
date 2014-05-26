

<?php display_message($message); ?>
<?php display_errors($errors); ?>

<div class="form-two-column">

	<?php echo form_open('/forgot-password/resend'); ?>
  
    <div class="field-row">
      <label for="username">Username or zID: </label>
      <input type="text" name="username" id="username" value="<?php $username; ?>" /> <em>Case Sensitive</em>
    </div>
    
    <div class="field-row">
      <div class="field-field-submit">
        <input type="submit" name="submit" value="Resend password" />
      </div>
    </div>
  
    <div class="field-row">
      <div class="field-field">
      	<br />
        <a href="/">Login</a>
      </div>
    </div>

  </form> 
  
</div>