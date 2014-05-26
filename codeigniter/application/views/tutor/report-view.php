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

<div class="table-two-column table-view">

	<p><strong>Technique in scales and exercises</strong></p>
	<p><?php echo nl2br($form['technique']['value']); ?></p>
	<hr />
	
  <p><strong>Preparation of repertoire for assessment</strong></p>
	<p><?php echo nl2br($form['preparation']['value']); ?></p>
	<hr />

	<p><strong>Sight reading</strong></p>
	<p><?php echo nl2br($form['sight_reading']['value']); ?></p>
	<hr />

	<p><strong>Attendance record and preparation for lessons</strong></p>
	<p><?php echo nl2br($form['attendance']['value']); ?></p>
	<hr />

	<p><strong>Artistic development in the repertoire</strong></p>
	<p><?php echo nl2br($form['artistic_development']['value']); ?></p>
	<hr />

	<p><strong>Fluency of technical work</strong></p>
	<p><?php echo nl2br($form['technical_fluency']['value']); ?></p>
	<hr />

	<p><strong>Fluency in the performance of repertoire pieces</strong></p>
	<p><?php echo nl2br($form['performance_fluency']['value']); ?></p>
	<hr />

	<p><strong>Attention to musical details and style</strong></p>
	<p><?php echo nl2br($form['attention']['value']); ?></p>
	<hr />

	<p><strong>Do you have any recommendations for the Course Co-ordinatorto follow up at the University?</strong></p>
	<p><?php echo nl2br($form['follow_up']['value']); ?></p>
	<hr />

	<p><strong>At this stage of the student's development you would assess the student as</strong></p>
  <p><?php switch ($form['assess']['value']) {
		
		case 'HD':
			echo 'High Distinction';
			break;
		case 'DN':
			echo 'Distinction';
			break;
		case 'CR':
			echo 'Credit';
			break;
		case 'PS':
			echo 'Pass';
			break;
		case 'FL':
			echo 'Fail';
			break;

	} ?>
	</select>
	</p>
	<hr />

	<p><strong>Please name the repertoire items the student has prepared in this period</strong></p>
	<p><?php echo nl2br($form['repertoire_items']['value']); ?></p>
	
</div>
