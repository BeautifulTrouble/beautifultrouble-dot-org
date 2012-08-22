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

function su_toggle_blind(id) {
	if (document.getElementById(id)) {
		if (document.getElementById(id).style.display=='none')
			Effect.BlindDown(id);
		else
			Effect.BlindUp(id);
	}
	
	return false;
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

jQuery(document).ready(function() {
	jQuery('input, textarea, select', 'div.su-module').change(su_enable_unload_confirm);
	jQuery('form', 'div.su-module').submit(su_disable_unload_confirm);
});