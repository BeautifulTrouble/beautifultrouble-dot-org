
function su_toggle_select_children(select) {
	var i=0;
	for (i=0;i<select.options.length;i++) {
		var option = select.options[i];
		var c = ".su_" + select.name.replace("_su_", "") + "_" + option.value + "_subsection";
		if (option.index == select.selectedIndex) jQuery(c).show().removeClass("hidden"); else jQuery(c).hide();
	}
}

// bootstrap/prototype conflict
jQuery.noConflict();
if ( typeof Prototype !== 'undefined' && Prototype.BrowserFeatures.ElementExtensions ) {
    var disablePrototypeJS = function (method, pluginsToDisable) {
            var handler = function (event) {
                event.target[method] = undefined;
                setTimeout(function () {
                    delete event.target[method];
                }, 0);
            };
            pluginsToDisable.each(function (plugin) { 
                jQuery(window).on(method + '.bs.' + plugin, handler);
            });
        },
        pluginsToDisable = ['collapse', 'dropdown', 'modal', 'tooltip', 'popover', 'tab'];
    disablePrototypeJS('show', pluginsToDisable);
    disablePrototypeJS('hide', pluginsToDisable);
}
jQuery(document).ready(function ($) {
    $('.bs-example-tooltips').children().each(function () {
        $(this).tooltip();
    });
    $('.bs-example-popovers').children().each(function () {
            $(this).popover();
    });
});