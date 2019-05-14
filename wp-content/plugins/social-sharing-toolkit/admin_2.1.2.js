jQuery(document).ready( function($) {
	$( "#mr_social_sharing_tabs" ).tabs();
	$( "#mr_social_sharing_networks" ).sortable();
	$( "#mr_social_sharing_shortcode_networks" ).sortable();
	$( "#mr_social_sharing_widget_networks" ).sortable();
	$( "#mr_social_sharing_follow_networks" ).sortable();
	$( "span.mr_social_sharing_custom" ).hide();
	$( "select.mr_social_sharing_type_select" ).each(function () {
		if ($(this).val() == 'icon_small' || $(this).val() == 'icon_small_text' || $(this).val() == 'icon_medium' || $(this).val() == 'icon_medium_text' || $(this).val() == 'icon_large') {
			$(this).parents("li").find("span.mr_social_sharing_custom").show();
		}
		$(this).change(function () {
			if ($(this).val() == 'icon_small' || $(this).val() == 'icon_small_text' || $(this).val() == 'icon_medium' || $(this).val() == 'icon_medium_text' || $(this).val() == 'icon_large') {
				$(this).parents("li").find("span.mr_social_sharing_custom").show();
			} else {
				$(this).parents("li").find("span.mr_social_sharing_custom").hide();
			}
		});
	});
});