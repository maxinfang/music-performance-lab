<p>Please complete a student progress report for each of your students via the &lsquo;edit&rsquo; action. You can save your progress throughout the semester and &lsquo;submit&rsquo; at the conclusion of the required hours. If you wish to make changes after you have selected &lsquo;submit&rsquo;, please contact <a href="mailto:sam@unsw.edu.au">sam@unsw.edu.au</a></p>
<?php if ($records) { ?>

  <table class="table-row">
  
    <tr>
    <th scope="col"></th>
    <th scope="col">First Name</th>
    <th scope="col">Last Name</th>
    <th scope="col">E-mail</th>
    <th scope="col">Semester</th>
    <th scope="col">Report Status</th>
    <th scope="col">Action</th>
    </tr>
      
    <?php foreach  ($records as $record) {
			
			$count ++;
			$report = get_progress_report_info ($record['placement_id'], $this->session->userdata('tutor_id'));
			
		?>
        
      <tr>
        <td>#<?php echo $count; ?></td>
        <td><?php echo $record['first_name']; ?></td>
        <td><?php echo $record['last_name']; ?></td>
        <td><?php echo mailto($record['email']); ?></td>
        <td><?php echo $record['semester']; ?></td>
        <td><?php echo ucwords($report['status']); ?>
        <?php //print_ob($report); ?>
        </td>
        <td>
					
          <?php
						switch($report['status']) {
							
							case 'awaiting':
							case 'draft':
								echo '<a href="tutor/report/' . $record['placement_rid'] . '">Edit</a>';
								break;
							case 'submitted':
								echo '<a href="tutor/report/' . $record['placement_rid'] . '">View</a>';
								break;
							case 'closed';
								echo '-';
								break;
							default:
								echo '';
								break;
					
						}
					?>         
          </a>
        </td>
      </tr>
        
    <?php	
    
    } //end for each ?>
      
  </table>

<?php

} else {

	display_errors('No list of students to display');
	
} ?>