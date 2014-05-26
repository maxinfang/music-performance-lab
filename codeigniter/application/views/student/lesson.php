<?php display_message($this->session->flashdata('message')); ?>

<p>Listed below are the lessons logged by you. If there are any inaccuracies, please contact the School as soon as possible.</p>

<?php if (count($form['row_array'])) { ?>

  <div class="table-two-column table-view">
    
    <table class="table-row" id="lesson-table">
    
      <tr>
      <th scope="col">Date</th>
      <th scope="col">Length of Lesson (in minutes)</th>
      <th scope="col">Tutor</th>
      </tr>
        
      <?php foreach  ($form['row_array'] as $x => $row) {	?>
          
          <tr>
          <td><?php echo $row['date']; ?></td>
          <td><?php echo $row['length']; ?></td>
          <td><?php echo $row['tutor_name']; ?></td>
          </tr>
          
      <?php	
          
          $total_minutes = $total_minutes + $row['length'];
      
      } //end for each ?>
        
    </table>
  
  </div>
  
<?php } else { ?>
	<?php display_errors('No lessons have been logged. Please contact the School.'); ?>
<?php } ?>

<h2>Payment details for <?php echo $study['title']; ?> years</h2>
<ul>
<li><strong>$<?php echo $study['payment']; ?></strong> (if registered for GST, <strong>$<?php echo $study['payment'] * 1.10; ?></strong>)</li>
<li><strong><?php echo $study['hours']; ?></strong> hours of tuition in total (e.g. <strong><?php echo $study['hours']; ?></strong> lessons at <strong>1 hour</strong> in length)</li>
</ul>

<script type="text/javascript" src="<?php echo base_url('assets/js/lesson-log.js');?>"></script> 
