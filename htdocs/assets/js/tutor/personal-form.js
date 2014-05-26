$(function() {
		
	toggle_payment_method();
	toggle_instruments_other();
	biography_count();

	//***** Bind functions to elements
	$('input[name="payment_method"]').bind('change', toggle_payment_method);
	$('input[name="instruments[]"]').bind('change', toggle_instruments_other);
	$('#biography').bind('keyup', biography_count);

	//***** FUNCTION : Toggle related elemnts
	function toggle_payment_method () {
	
		var value = $('input[name="payment_method"]:checked').val();
		var related = $('.field-payment-method');
		var selected = $('.field-payment-method-' + value);
		
		//hide all
		related.hide();
		
		//show selected only
		selected.show();

	}
	
	//***** FUNCTION : Toggle related elemnts
	function toggle_instruments_other () {
	
		var related = $('.instruments-other');
		
		//Loop through each checkbox
		$('input[name="instruments[]"]').each(function () {
			if ($(this).val() == 'Other') {
				//Get id "Other" checkbox
				if (this.checked) {
					related.show();
				} else {
					related.hide();
				}
			} 
	  });
		
	}

	//***** FUNCTION : Toggle related elemnts
	function biography_count () {
	
		var val = $('#biography').val()
		var length = val.length;
		var text = $('#biography-word-count').html();	
		var remaining = 1500 - length;

		//Carriage returns
		var new_lines = val.match(/(\r\n|\n|\r)/g);
		var addition = 0;
		if (new_lines != null) addition = new_lines.length;

		remaining = remaining - addition;

		$('#biography-word-count').html(remaining);	
		
	}

});
