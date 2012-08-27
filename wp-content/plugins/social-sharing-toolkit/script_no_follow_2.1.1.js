jQuery(document).ready(function($) {
	// Link popup handler:
    $.each($("a.mr_social_sharing_popup_link"), function() {
    	var elem = $(this);
    	elem.click( function (event) {
			event.preventDefault();
      		var popup = window.open(elem.attr('href'),'mr_social_sharing','height=400,width=740');
			if (popup) {
				popup.focus();
			}
    	});
    });
    // Remove pesky <p> tags:
    $(".mr_social_sharing_wrapper p").each(function(){
    	$(this).remove();
	});
    // Don't share links
	$(".mr_social_sharing_wrapper a").attr("rel", "nofollow");
});