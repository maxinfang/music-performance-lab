<p>Welcome to the Music Performance Lab system.</p>

<?php display_message($message); ?>
<?php display_errors($errors); ?>

<?php /*<p>This system is currently under maintenance. Please try again in the next hour.</p>


<?php if ($_GET['status'] == 'open') { //   */ ?>Please use your zID and zPass to login:</p>


<div class="form-two-column">
	<?php echo form_open('/login'); ?>
  
    <div class="field-row">
      <label for="username">zID: </label>
      <input type="text" name="username" id="username" value="<?php echo $form['username']; ?>" />
    </div>
    
    <div class="field-row">
      <label for="password">zPass:</label>
      <input type="password" name="password" value="" />
    </div>
        
    <div class="field-row">
      <div class="field-field-submit">
        <input type="submit" name="submit" value="Login" />
      </div>
    </div>

    
  </form> 
</div>

<?php // } ?>
