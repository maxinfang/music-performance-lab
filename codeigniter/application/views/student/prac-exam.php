<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>

<div class="table-two-column table-view">

  <h3>Ensemble you are enrolled in this semester</h3>
  <p><?php echo $placement_record->getField('ensemble'); ?></p>

  <h3>Technical Work/Study/Vocalise</h3>

  <table class="table-row" id="lesson-table">
  
    <tr>
    <th scope="col">Title</th>
    <th scope="col">Composer</th>
    <th scope="col">Duration</th>
    </tr>
              
    <tr>
    <td><?php echo $placement_record->getField('tech_title'); ?></td>
    <td><?php echo $placement_record->getField('tech_composer'); ?></td>
    <td><?php echo $placement_record->getField('tech_duration'); ?></td>
    </tr>
                    
  </table>

  <h3>Other works for examination</h3>

  <table class="table-row" id="lesson-table">
  
    <tr>
    <th scope="col">Title</th>
    <th scope="col">Composer</th>
    <th scope="col">Duration</th>
    </tr>
      
    <?php foreach  ($ow_records as $ow_record) { ?>
        
        <tr>
        <td><?php echo $ow_record->getField('title'); ?></td>
        <td><?php echo $ow_record->getField('composer'); ?></td>
        <td><?php echo $ow_record->getField('duration'); ?></td>
        </tr>
        
    <?php	} //end for each ?>
            
  </table>

</div>
