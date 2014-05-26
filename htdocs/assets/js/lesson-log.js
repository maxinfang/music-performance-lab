$(function() {
		
	//***** Bind functions to elements
	$('.form-button-remove').bind('click', remove_row).bind('click', total_hours);
	$('#form-button-add').bind('click', add_row);

	//Validate fields
	$('.date-field').bind('change', val_field_date);
	$('.length-field').bind('change', val_field_length);

	//Icons
//	$("#form-button-add").button({ icons: { primary: "ui-icon-gear", secondary: "ui-icon-triangle-1-s" } });
		
	//***** FUNCTION : Remove Row
	function remove_row () {
		
		var row_number = $(this).attr('name').replace('remove_', '');
		var row_id = 'row_' + row_number;
		$('#' + row_id).remove();
		
		//Update total row
		var total_row = $('#total_row').val();
		$('#total_row').val(parseInt(total_row) - parseInt(1));

		
	}

	//***** FUNCTION : Remove Row
	function renumber_row () {
		
		var row_number = $(this).attr('name').replace('remove_', '');
		var row_id = 'row_' + row_number;
		$('#' + row_id).remove();
		
	}
	
	//***** FUNCTION : Add Row
	function add_row () {
		
		var is_date_valid  = val_field_date ('date_new');
		var is_length_valid  = val_field_length ('length_new');
		
		if (is_date_valid == true && is_length_valid == true) {
		

			//Get the latest remove number to use
///			var numbering = $('#numbering').val();
			var row_number = $('#next_row').val(); //parseInt(numbering) + parseInt(1);
			
			//field ids and names
			var row_id = 'row_' + row_number;
			var date_field = 'date_' + row_number;
			var length_field = 'length_' + row_number;
			var tutor_field = 'tutor_id_' + row_number;
			var lesson_id = 'lesson_' + row_number;
			var remove_button = 'remove_' + row_number;
			
			//field values
			var date_value = $('#date_new').val();
			var length_value = $('#length_new').val();
			var tutor_id_value = $('#tutor_id_new option:selected').attr('value');
			var tutor_name_value = $('#tutor_id_new option:selected').text();
						
			//create the rows and fields
			var date_cell = '<td><input type="hidden" value="new" name="' + lesson_id + '" /><input type="hidden" value="' + date_value + '" name="' + date_field + '" />' + date_value + '</td>';
			var length_cell = '<td><input type="hidden" value="' + length_value + '" name="' + length_field + '" class="length" />' + length_value + '</td>';
			var tutor_cell = '<td><input type="hidden" value="' + tutor_id_value + '" name="' + tutor_field + '" />' + tutor_name_value + '</td>';
			var remove_cell = '<td><input type="button" value="- Remove" class="form-button-remove" id="' + remove_button + '" name="' + remove_button + '" /></td>';
	
			//Create new row
			$("#row-new").before($('<tr id="' + row_id + '">')
				.append(date_cell)
				.append(length_cell)
				.append(tutor_cell)
				.append(remove_cell)
			);
						
			//Append actions to new elements
			$('#' + remove_button).bind('click', remove_row).bind('click', total_hours);
		
			//Clear data
			$('#date_new').val('');
			$('#length_new').val('');
			$('#tutor_id_new option:selected').removeAttr('selected');
		 
			//Add to row count
			var total_row = $('#total_row').val();
			$('#total_row').val(parseInt(total_row) + parseInt(1));
			$('#next_row').val(parseInt(row_number) + 1);
			
			//Calculate hours
			total_hours();
			
		}
		
		
		
	}
	
	
	//***** FUNCTION : Add Row
	function val_field_length (id) {
	
		var id;
		if ($.type(id) == 'object') {
			id = $(this).attr('id');
		}

		var length_value = $('#' + id).val();
		var valid = false;

		if (length_value.length === 0) {
			add_error(id, 'Please specific the amount of time in minutes');
		} else if ($.isNumeric(length_value) === false) {
			add_error(id, 'Only numeric values only');
		} else {
			remove_error(id);
			valid = true;
		}
		
		return valid;
		
	}

	//***** FUNCTION : Validate date fields
	function val_field_date (id) {

		var id;	
		
		if ($.type(id) == 'object') {
			id = $(this).attr('id');
		}

		var date_value = $('#' + id).val();
		var valid = false;
		
		if (date_value.length === 0) {			
			add_error(id, 'Please select a date');
		} else if (val_date(date_value) == false) { 
			add_error(id, 'Please provide a valid date');
		} else {
			remove_error(id);
			valid = true;
		}

		return valid;
		
	}
	
	
	//***** FUNCTION : Validate that its a date
	function val_date (value) {
		
		var date_ok = false;
		
		try {
			$.datepicker.parseDate("dd/mm/yy", value);
			date_ok = true;
		} catch (e) {}
		
		return date_ok;
		
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
	
	//***** FUNCTION : Calculate total minutes into hours
	function total_hours () {
	
		var all_length = $('.length');
		var each_length;
		var total_minutes = 0;
		var results = [];
		var result = 0;
		$.each(all_length, function() {
			each_length = parseInt($(this).val());
			total_minutes = total_minutes + each_length;
		});
				
		if (total_minutes > 0) {
			
			var total_hours = total_minutes / 60;
			var time = String(total_hours).split('.');
			
			//Hours
			if (time[0]) {			
			
				var hours = time[0];
				
				if (hours == 1) {
					results.push(hours + ' hour');
				} else {
					results.push(hours + ' hours');
				}
				
			}
			
			//Minutes
			if (time[1]) {
				
				var minutes = total_minutes - (parseInt(hours) * 60);
				if (hours == 1) {
					results.push(minutes + ' minute');
				} else {
					results.push(minutes + ' minutes');
				}
			}
			
			result = results.join(' ');

		}
		
		//Set result to element
		$('#total_hours').text(result);
		
	}
	

	
});
