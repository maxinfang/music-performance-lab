<?php display_message($this->session->flashdata('message')); ?>

<?php if ($records) { ?>

  <table class="table-row">
  
    <tr>
    <th scope="col"></th>
    <th scope="col">Select</th>
    <th scope="col"><a href="student/tutor-list/name<?php if ($sort['field'] == 'name' && $sort['order'] == 'asc') echo '/desc'; ?>">Name</a></th>
    <th scope="col"><a href="student/tutor-list/email<?php if ($sort['field'] == 'email' && $sort['order'] == 'asc') echo '/desc'; ?>">E-mail</a></th>
    <th scope="col" width="20%"><a href="student/tutor-list/instruments<?php if ($sort['field'] == 'instruments' && $sort['order'] == 'asc') echo '/desc'; ?>">Instruments</th>
    <th scope="col"><a href="student/tutor-list/suburb<?php if ($sort['field'] == 'suburb' && $sort['order'] == 'asc') echo '/desc'; ?>">Suburb</a></th>
    <th scope="col"><a href="student/tutor-list/postcode<?php if ($sort['field'] == 'postcode' && $sort['order'] == 'asc') echo '/desc'; ?>">Postcode</a></th>
    <?php /*<th scope="col"></th>*/ ?>
    </tr>
      
    <?php foreach  ($records as $record) {
			
			$count ++;
			$report = '';
			$report[] = 'Reporting outdated information for the following tutor:'; //reset
			$report[] = 'Name: ' . $record->getField('c_full_name');
      $report[] = 'Email ' . $record->getField('email');
				
		?>
        
        <tr>
        <td>#<?php echo $count; ?></td>
        <td>
        	<?php echo form_open_multipart('/student/tutor-form/update', array('class'=>'form-plain')); ?>  
					<input type="hidden" name="tutor_id" id="tutor_id" value="<?php echo $record->getField('tutor_id'); ?>" />
          <input type="submit" name="select" value="Select" />
					</form>
        </td>
        <td><a href="student/tutor-profile/<?php echo $record->getRecordId(); ?>"><?php echo $record->getField('c_full_name'); ?></a></td>
        <td><?php echo mailto($record->getField('email')); ?></td>
        <td>
					<?php
						$instruments = $record->getField('instruments');
						$instruments = str_replace("\n", ', ', $instruments);
						$instruments = str_replace('Other', 'Other (' . $record->getField('instruments_other') . ')', $instruments);
						echo $instruments;
					?>
        </td>
        <td><?php echo $record->getField('suburb'); ?></td>
        <td><?php echo $record->getField('postcode'); ?></td>
        <?php /*<td><a href="mailto:sam@unsw.edu.au?subject=Update to tutor information&body=<?php echo implode('%0A', $report); ?>">Report outdated information</a></td>*/ ?>
        </tr>
        
    <?php	
        
        $total_minutes = $total_minutes + $row['length'];
    
    } //end for each ?>
      
  </table>

<?php

} else {

	display_errors('Unable to display the complete list of tutors');
	
} ?>