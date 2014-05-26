<p>Welcome to the Music Performance Lab system. Please use your email and the password to login:</p>
  
<?php display_message($message); ?>
<?php display_errors($errors); ?>

<div class="form-two-column">
	<?php echo form_open('/tutor_login'); ?>
  
    <div class="field-row">
      <label for="username">Email: </label>
      <input type="text" name="username" id="username" value="<?php echo $form['username']; ?>" />
    </div>
    
    <div class="field-row">
      <label for="password">Password:</label>
      <input type="password" name="password" value="" />
    </div>
        
    <div class="field-row">
      <div class="field-field-submit">
        <input type="submit" name="submit" value="Login" />
      </div>
    </div>
 
  
  </form> 
</div>
