<p>Welcome to the Music Performance Lab system. This login is for tutors to view the list of students that they are tutoring for the current semester and to provide a progress report for each student.</p>  
<?php display_message($message); ?>
<?php display_errors($errors); ?>

<div class="form-two-column">
	<?php echo form_open('/tutor/login'); ?>
  
    <div class="field-row">
      <label for="username">E-mail: </label>
      <input type="text" name="username" id="username" value="<?php echo $form['username']; ?>" />
    </div>
    
    <div class="field-row">
      <label for="password">Password:</label>
      <input type="password" name="password" value="" /> <em>(provided in the e-mail)</em>
    </div>
        
    <div class="field-row">
      <div class="field-field-submit">
        <input type="submit" name="submit" value="Login" />
      </div>
    </div>

    <div class="field-row">
      <div class="field-field">
      	<br />
				<a href="#">Forgot your password?</a></div>
    </div>
  
  </form> 
</div>
