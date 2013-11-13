

(function($) {

	$("#rfb_img_size").change(function() {

		if($(this).val() == 'dont_show') {
			$("#rfb_img_options").hide();
		} else {
			$("#rfb_img_options").show();
		}

	});

})(jQuery);