/*global jQuery:false, alert */
(function($) { 
    "use strict";
jQuery(document).ready(function($) {
	$('input, textarea, select', 'div.sdf-admin').change(su_enable_unload_confirm);
	$('form', 'div.sdf-admin').submit(su_disable_unload_confirm);
	
	$('#wpbody').on('click', '.su_toggle_hide', function(e) {
		e.preventDefault();
		var selector = $(this).data('toggle'),
		to_toggle = $('#'+selector);
		to_toggle.slideToggle();
	});
	$('#wpbody').on('click', '.su_toggle_up', function(e) {
		e.preventDefault();
		var selector = $(this).data('toggle'),
		to_toggle = $('#'+selector);
		to_toggle.slideToggle();
	});
});
})(jQuery);

function su_reset_textbox(id, d, m, e) {
	if (confirm(m+"\n\n"+d)) {
		document.getElementById(id).value=d;
		e.className='hidden';
		su_enable_unload_confirm();
	}
}

function su_textbox_value_changed(e, d, l) {
	if (e.value==d)
		document.getElementById(l).className='hidden';
	else
		document.getElementById(l).className='';
}

function su_enable_unload_confirm() {
	window.onbeforeunload = su_confirm_unload_message;
}

function su_disable_unload_confirm() {
	window.onbeforeunload = null;
}

function su_confirm_unload_message() {
	return suModulesModulesL10n.unloadConfirmMessage;
}
