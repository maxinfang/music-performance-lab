<?php echo $content; ?>



<?php /*

<p>The Practical Examinations for MUSC1704, MUSC1705, MUSC2402 and MUSC2502 will be held from 11 - 13 November in rooms G06, G07, G16, G17 and G18.</p>

<p>You will select a room and then a time and date that you wish to attend your examination.&nbsp; As these exams will take place during the formal University exam period please choose a practical exam time that does not clash with other exams.&nbsp; (If you find that you have a clash, please inform the School Office immediately, and we will amend your practical exam date).</p>

<p>The online booking system will be available from 21 – 30 October.&nbsp; During this time you will be able to log on and change your examination as often as you like.</p>


<p>After midnight on Wednesday 30 October you will be unable to make any further changes.

<p><strong style="color:#FF0000">NOTE:</strong> Each change you make will override your previous choice.</p>

*/ ?>

<a name="form"></a>

<?php display_message($this->session->flashdata('message')); ?>
<?php if (isset($errors)) { display_errors($errors); } ?>

<?php echo form_open_multipart('/student/book-exam-form/update/#form'); ?></p>

<div class="form-two-column">
                           
	<div class="field-row">
  	<label for="exam_drum_amp">Do you require drums or an amp?</label>
    <div class="field-field">
    	<select name="exam_drum_amp" id="exam_drum_amp">
      	<option value=""></option>
      	<option value="0" <?php if ($form['exam_drum_amp']['value'] == "0") echo 'selected="selected"'; ?>>No</option>
      	<option value="1" <?php if ($form['exam_drum_amp']['value'] == "1") echo 'selected="selected"'; ?>>Yes</option>
			</select>
		</div>                
	</div>
  
  <div class="field-row field-exam-room">
  	<label for="exam_room">Room</label>
    <div class="field-field">
      <p class="note field-drum-amp-no" style="color:red;">Priority for rooms G16, G17 and G18 will be given to students needing drums and amps. We recommend that if you do not need this equipment you choose one of the other rooms. If you do choose G16, G17 or G18 and the need arises, you may be contacted by the School to make an alternate selection.</p>      
        <select name="exam_room" id="exam_room">
        <option value=""></option>
        <?php foreach ($slot_options as $room_option => $room_info) {
					echo "<option value=\"$room_option\"";
					if ($form['exam_room']['value'] == $room_option) echo 'selected="selected"';
					echo ">$room_option</option>"; 
        }?>
        </select>
		</div>
	</div>      

  <div class="field-row field-exam-date">
  	<label for="exam_date">Date</label>
    <div class="field-field">
        <select name="exam_date" id="exam_date">
        <option value=""></option>
        <?php foreach ($slot_options[$form['exam_room']['value']]['date'] as $date_option => $date_info) {
					echo "<option value=\"$date_option\"";
					if ($form['exam_date']['value'] == $date_option) echo 'selected="selected"';
					echo ">$date_option</option>"; 
        }?>
        </select>
		</div>
	</div>      
	
  <div class="field-row field-exam-time">
    <label for="exam_time">Time:</label>
    <div class="field-field">
      <select name="exam_time" id="exam_time">
        <option value=""></option>
        <?php foreach ($slot_options[$form['exam_room']['value']]['date'][$form['exam_date']['value']] as $time_option) { 
              echo "<option value=\"$time_option\" ";
              if ($form['exam_time']['value'] == $time_option) echo 'selected="selected"';
              echo ">$time_option</option>\r\n";  
        }  ?>
      </select>
		</div>
  </div>            
    
  <div class="form-navigation">
    <p><input type="submit" name="submit" value="Save" class="form-button-save" />
    <?php if ($first_time == false) { ?>&nbsp; &nbsp;<a href="student/book-exam">Cancel</a><?php } ?>
    </p>
  </div>

</div>
</form>
<script type="text/javascript">
<!--
	var slot_options = <?php echo json_encode($slot_options); ?>;
-->
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/book-exam-form.js');?>"></script> 
