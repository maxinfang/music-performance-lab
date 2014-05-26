<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>

<div class="table-two-column table-view">
        
  <div class="field-row">
    <div class="field-label">Name</div>      
    <div class="field-field"><?php echo $name; ?></div>
  </div>

  <div class="field-row">
    <div class="field-label">zID</div>      
    <div class="field-field"><?php echo $zid; ?></div>
  </div>

  <div class="field-row">
    <div class="field-label">Semester</div>      
    <div class="field-field"><?php echo $semester; ?></div>
  </div>

  <div class="field-row">
    <div class="field-label">Number of Lessons attended</div>      
    <div class="field-field"><?php echo $total_lesson; ?></div>
  </div>
  <br />

</div>

<div class="form-two-column">

<?php echo form_open_multipart('/tutor/report/' . $placement_rid . '/update'); ?>  

  <p>Please provide your comments for each. This information is extremely important in the process of marking students for the practical exams. It is imperative that you submit this report no later than [enter deadline].</p>

  <p>
    <label for="technique">Technique in scales and exercises</label>
    <textarea name="technique" id="technique" class="textbox-large"><?php echo $form['technique']['value']; ?></textarea>
  </p>

  <p>
    <label for="preparation">Preparation of repertoire for assessment</label>
    <textarea name="preparation" id="preparation" class="textbox-large"><?php echo $form['preparation']['value']; ?></textarea>
  </p>

  <p>
  <label for="sight_reading">Sight reading</label>
  <textarea name="sight_reading" id="sight_reading" class="textbox-large"><?php echo $form['sight_reading']['value']; ?></textarea>
  </p>

  <p>
  <label for="attendance">Attendance record and preparation for lessons</label>
  <textarea name="attendance" id="attendance" class="textbox-large"><?php echo $form['attendance']['value']; ?></textarea>
  </p>

  <p>
  <label for="artistic_development">Artistic development in the repertoire</label>
  <textarea name="artistic_development" id="artistic_development" class="textbox-large"><?php echo $form['artistic_development']['value']; ?></textarea>
  </p>

  <p>
  <label for="technical_fluency">Fluency of technical work</label>
  <textarea name="technical_fluency" id="technical_fluency" class="textbox-large"><?php echo $form['technical_fluency']['value']; ?></textarea>
  </p>

  <p>
  <label for="performance_fluency">Fluency in the performance of repertoire pieces</label>
  <textarea name="performance_fluency" id="performance_fluency" class="textbox-large"><?php echo $form['performance_fluency']['value']; ?></textarea>
  </p>

  <p>
  <label for="attention">Attention to musical details and style</label>
  <textarea name="attention" id="attention" class="textbox-large"><?php echo $form['attention']['value']; ?></textarea>
  </p>

  <p>
  <label for="follow_up">Do you have any recommendations for the Course Co-ordinatorto follow up at the University?</label>
  <textarea name="follow_up" id="follow_up" class="textbox-large"><?php echo $form['follow_up']['value']; ?></textarea>
  </p>

  <p>
  <label for="assess">At this stage of the student's development you would assess the student as</label>: <select name="assess" id="assess">
  <option value=""></option>
  <?php foreach ($valuelists['assess'] as $field_value => $field_option) { ?>
    <option value="<?php echo $field_value; ?>" <?php if ($form['assess']['value'] == $field_value) echo 'selected="selected"'; ?>><?php echo $field_option; ?></option>
  <?php } ?>
  </select>
  </p>

  <p>
  <label for="report">Please name the repertoire items the student has prepared in this period</label>
  <textarea name="repertoire_items" id="repertoire_items" class="textbox-large"><?php echo $form['repertoire_items']['value']; ?></textarea>
  </p>
  
  <div style="text-align:center"><input type="submit" name="save" value="Save Progress" /> <input type="submit" name="submit" value="Submit" /></div>

</div>
  
</form> 
  

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
