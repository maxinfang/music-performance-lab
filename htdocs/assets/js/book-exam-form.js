$(function() {
		
	toggle_all_fields();
	
	//***** Bind functions to elements
	$('#exam_drum_amp').bind('change', val_drum_amp);
	$('#exam_room').bind('change', val_room);
	$('#exam_date').bind('change', val_date);

	//***** FUNCTION : Toggle related elemnts
	function toggle_all_fields () {
	
		var drum_amp_text = $('.field-drum-amp-no');
		var room = $('.field-exam-room');
		var date = $('.field-exam-date');
		var time = $('.field-exam-time');

		var drum_amp_value = $('#exam_drum_amp option:selected').val();
		var room_value = $('#exam_room option:selected').val();
		var date_value = $('#exam_date option:selected').val();
		var time_value = $('#exam_time option:selected').val();
			
		//room fields	
		if (drum_amp_value.length == 0) {
			room.hide();
		} else {
			if (drum_amp_value == 0) {
				drum_amp_text.show();
			} else {
				drum_amp_text.hide();
			}
			room.show();
		}
		
		//date		
		if (drum_amp_value.length == 0 || room_value.length == 0) {
			date.hide();
		} else {
			date.show();
		}

		//time	
		if (drum_amp_value.length == 0 || room_value.length == 0 || date_value.length == 0) {
			time.hide();
		} else {
			time.show();
		}

	}
	
	
	//***** FUNCTION : Add Row
	function val_drum_amp () {
		
		//value selec
		var id = 'exam_drum_amp';
		var value = $('#' + id + ' option:selected').val();
		
		//no value
		if (value.length == 0) {
 			add_error(id, 'Please indicate Yes or No on whether you require drums or an app');
		} else {
	
			//drum/amp required, show rooms availble with drum/amps only	
			if (value == 1) {
 				remove_error(id);
				//show all available rooms		
			} else if (value == 0) {
	 			remove_error(id);
			}
		}
	
		toggle_all_fields();
		update_room_options	();
	}

	//***** FUNCTION : Add Row
	function val_room () {
		
		//value selec
		var id = 'exam_room';
		var value = $('#' + id + ' option:selected').val();
		
		//no value
		if (value.length == 0) {
 			add_error(id, 'Please select a room');
		//value exist
		} else {
			//grab the available dates
			update_date_options();
 			remove_error(id);			
		}
		
		toggle_all_fields();
		
	}

	//***** FUNCTION : Add Row
	function val_date () {
		
		//value selec
		var id = 'exam_date';
		var value = $('#' + id + ' option:selected').val();
		
		//no value
		if (value.length == 0) {
 			add_error(id, 'Please select a date');
		//value exist
		} else {
			
			//grab the available dates
			update_time_options();
 			remove_error(id);
			
		}
		
		toggle_all_fields();

	}


	//***** FUNCTION : Update the dates option based on the selected room
	function update_room_options () {

		//value selec
		var value = $('#exam_drum_amp option:selected').val();
		var room_field = $('#exam_room');
		var room_selected = $('#exam_room option:selected').val();
		var room_options = slot_options;
		
 		/* no options available for this selection
		 * hide the list and replace with error
		 */
		if (room_options.length == 0) {
			room_field.after('<div id="room-error">No rooms are available. Please contact the School</div>');		
			room_field.hide();
			
		//options available - update the list
		} else {

			$("room-error").remove();
			room_field.show();
			
			//clear the current options
			$('#exam_room option').remove();
			room_field.append('<option value=""></option>');
			
			//add each time option to the list
			$.each(room_options, function(room_option, room_info) {		
				//only show if drum only OR drum not required
				if ((value == 1 && room_info['drum_amp'] == 1) || value == 0) room_field.append('<option value="' + room_option + '">' + room_option + '</option>');
			}); 
			
			//reset the selected room
			$('#exam_room').val(room_selected).attr('selected', 'selected');
		}
				
	}

	//***** FUNCTION : Update the dates option based on the selected room
	function update_date_options () {
		
		//value selec
		var value = $('#exam_room option:selected').val();
		var date_field = $('#exam_date');
		var date_selected = $('#exam_date option:selected').val();
		
		var date_options = slot_options[value]['date'];
		
 		/* no options available for this room selection
		 * hide the list and replace with error
		 */
		if (date_options.length == 0) {
			date_field.after('<div id="date-error">No dates are available for this room. Please select another room</div>');		
			date_field.hide();
			
		//options available - update the list
		} else {

			$("date-error").remove();
			date_field.show();
						//clear the current options
			$('#exam_date option').remove();
			date_field.append('<option value=""></option>');
			
			//add each time option to the list
			$.each(date_options, function(date_option, time_options) {		
				date_field.append('<option value="' + date_option + '">' + date_option + '</option>');
			}); 

			//reset the selected date
			$('#exam_date').val(date_selected).attr('selected', 'selected');

		}
	

	}

	//***** FUNCTION : Update the time option based on the selected date
	function update_time_options () {
		
		//value selec
		var room = $('#exam_room option:selected').val();
		var date = $('#exam_date option:selected').val();
		var time_field = $('#exam_time');
		var time_selected = $('#exam_time option:selected').val();
		
		var time_options = slot_options[room]['date'][date];
		
 		/* no options available for this room selection
		 * hide the list and replace with error
		 */
		if (time_options.length == 0) {
			time_field.after('<div id="time-error">No times are available for this date. Please select another room and/or date</div>');		
			time_field.hide();
			
		//options available - update the list
		} else {

			$("time-error").remove();
			time_field.show();
			//clear the current options
			time_field.find('option').remove();
			time_field.append('<option value=""></option>');
			
			//add each time option to the list
			$.each(time_options, function(index, value) {		
				time_field.append('<option value="' + value + '">' + value + '</option>');
			}); 

			//reset the selected date
			$('#exam_time').val(time_selected).attr('selected', 'selected');
						
		}
	}

	//***** FUNCTION : Add error after element
	function add_error (id, error) {
		var error_id = id + '-error';
		
		//remove exisiting error
		if ($('#' + error_id).length != 0) { remove_error (id); }
		//add new error
		$('#' + id).after('<div class="error error-field" id="' + error_id + '">' + error + '</div>');		
	}

	//***** FUNCTION : Remove errors of an element
	function remove_error (id) {
		$('#' + id + '-error').remove();		
	}
	
	
});
