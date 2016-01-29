jQuery(document).ready(function($){

	$('a.import').click(function(a,b){
		// Find elements
		$button = $(this);
		call_url = $button.attr('href');
		$attachment = $button.parents('.attachment:first');

		// Hide import button, show loading animation
		$attachment
			.find('.post, .import').hide().end()
			.find('.ajax-loader').show();

		// Do Ajax Request
		$.ajax(call_url, {
			success: function(data, textStatus, jqXHR){
				// Hide Loading animation, show success message
				$attachment
					.find('.ajax-loader').hide().end()
					.find('.import-success').fadeIn();
			}
		});

		return false;
	});

});