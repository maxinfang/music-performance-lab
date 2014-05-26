$(function() {
		
	total_minutes();
	
	//***** Bind functions to elements
	$('#tech_duration').bind('change', total_minutes);
	$('.form-button-remove').bind('click', remove_row).bind('click', total_minutes);
	$('#form-button-add').bind('click', add_row);
		
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
	
		var is_title_valid  = val_field_compulsory ('title_new');
		var is_composer_valid  = val_field_compulsory ('composer_new');
		var is_duration_valid  = val_field_compulsory ('duration_new');

		if (is_title_valid == true &&
				is_composer_valid == true &&
				is_duration_valid == true && 
				val_field_numeric('duration_new') == true) {
			
			//Get the latest remove number to use
			var row_number = $('#next_row').val();
			
			//field ids and names
			var row_id = 'row_' + row_number;
			var title_field = 'title_' + row_number;
			var composer_field = 'composer_' + row_number;
			var duration_field = 'duration_' + row_number;
			var ow_id = 'ow_id_' + row_number;
			var remove_button = 'remove_' + row_number;
			
			//field values
			var title_value = $('#title_new').val();
			var composer_value = $('#composer_new').val();
			var duration_value = $('#duration_new').val();
						
			//create the rows and fields
			var title_cell = '<td><input type="hidden" value="new" name="' + ow_id + '" /><input type="hidden" value="' + title_value + '" name="' + title_field + '" />' + title_value + '</td>';
			var composer_cell = '<td><input type="hidden" value="' + composer_value + '" name="' + composer_field + '" />' + composer_value + '</td>';
			var duration_cell = '<td><input type="hidden" value="' + duration_value + '" name="' + duration_field + '" class="duration" />' + duration_value + '</td>';
			var remove_cell = '<td><input type="button" value="- Remove" class="form-button-remove" id="' + remove_button + '" name="' + remove_button + '" /></td>';
	
			//Create new row
			$("#row-new").before($('<tr id="' + row_id + '">')
				.append(title_cell)
				.append(composer_cell)
				.append(duration_cell)
				.append(remove_cell)
			);
						
			//Append actions to new elements
			$('#' + remove_button).bind('click', remove_row).bind('click', total_minutes);
		
			//Clear data
			$('#title_new').val('');
			$('#composer_new').val('');
			$('#duration_new').val('');
		 
			//Add to row count
			var total_row = $('#total_row').val();
			$('#total_row').val(parseInt(total_row) + parseInt(1));
			$('#next_row').val(parseInt(row_number) + 1);
			
			//Calculate hours
			total_minutes();

		}
		
	}
	
	
	//***** FUNCTION : Validation compulsory fields
	function val_field_numeric (id) {
	
		var valid = false;
		
		//Not empty
		if (val_field_compulsory(id) == true) {
			var value = $('#' + id).val();
			//Is numeric
			if ($.isNumeric(value) == true) {
				valid = true;
				remove_error(id);
			} else {
				add_error(id, 'Must contain numbers only');
			}
		}
		
		return valid;
		
	}	

	//***** FUNCTION : Validation compulsory fields
	function val_field_compulsory (id) {
	
		var id;
		if ($.type(id) == 'object') {
			id = $(this).attr('id');
		}

		var length_value = $('#' + id).val();
		var valid = false;

		if (length_value.length === 0) {
			add_error(id, 'Please specify');
		} else {
			remove_error(id);
			valid = true;
		}
		
		return valid;
		
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

	//***** FUNCTION : Calculate total minutes
	function total_minutes () {
	
		var all_length = $('.duration');
		var each_length;
		var total_minutes = 0;
		var results = [];
		var result = 0;
		$.each(all_length, function() {
			each_length = parseInt($(this).val());
			total_minutes = total_minutes + each_length;
		});
				
		//Set result to element
		$('#total_minutes').text(total_minutes);
		
		if (total_minutes <= 25 && total_minutes >= 15) {
			$('#total_minutes').css('color', 'green');
		} else {
			$('#total_minutes').css('color', 'red');
		}
	
	}
	
});
