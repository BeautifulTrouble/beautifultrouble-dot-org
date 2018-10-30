/*global jQuery:false, alert */
(function($) { 
    "use strict";
jQuery(document).ready(function($) {
	
	$('#sdf-promo-carousel').hide();
	$('#sdf_dashboard_widget .inside').hide();
	var sds_promo_blog_post = $('#sds_promo_blog_post').html();
	var banners_remote = ({
"banners": [
{"banner_img":"seoultimateplus_300x250_set13.jpg", "banner_link":"https://www.seoultimateplus.com/breaking-news"},
{"banner_img":"seoultimateplus_300x600_set13.jpg", "banner_link":"https://www.seoultimateplus.com/breaking-news"},
{"banner_img":"seoultimateplus_300x250_set14.jpg", "banner_link":"https://www.seoultimateplus.com/breaking-news"},
{"banner_img":"seoultimateplus_300x600_set14.jpg", "banner_link":"https://www.seoultimateplus.com/breaking-news"},
{"banner_img":"seoultimateplus_300x250_set3.jpg", "banner_link":"https://www.seoultimateplus.com/breaking-news"}
],
"slides": [
{"slide_cap":"<h3>Download Jeffrey’s Brain</h3><p>Get Equipped for Success - Tips from Our Founder. Download: 20 SEO Tips, SEO for Large Websites and the Organic SEO EBook today.</p>", "slide_link":"http://www.seodesignframework.com/ebooks/"},
{"slide_cap":"<h3>Silos Made Easy</h3><p>Deploy Perfect Website Silo Architecture Quickly and Easily with the SEO Design Framework.</p>", "slide_link":"http://www.seodesignframework.com/website-silo-architecture/"}
],
"dashboard_widget": [
{"title":"An Important Message about SEO Ultimate", "content":"<p>Make sure to <a rel=\"nofollow\" target=\"_blank\" title=\"An Important Message about SEO Ultimate\" href=\"https://www.seoultimateplus.com/breaking-news\">watch the video</a> about this update.</p><a rel=\"nofollow\" target=\"_blank\" title=\"An Important Message about SEO Ultimate\" href=\"https://www.seoultimateplus.com/breaking-news\"><img src=\"" + suModulesSdfAdsSdfAdsL10n.sdf_banners_url + "seo-ultimate-wordpress-dashboard.jpg\" alt=\"SEO Ultimate video training\" /></a>"}
]
})

		
	var promo_carousel = $('#sdf-promo-carousel');
	if (promo_carousel.length > 0) {
		var sdf_carousel = '';
		var shuffled_banners = shuffleArray(banners_remote.banners);
		var shuffled_slides = shuffleArray(banners_remote.slides);
		// check if it's cloud hosted banner
		var banner_img = shuffled_banners[0].banner_img
		if(banner_img.indexOf('https://') == -1) banner_img = suModulesSdfAdsSdfAdsL10n.sdf_banners_url + banner_img;
		sdf_carousel = sdf_carousel + "<a href=\"" + shuffled_banners[0].banner_link + "\" rel=\"nofollow\" target=\"_blank\"><img src=\"" + banner_img + "\" alt=\"Slide "+ i +"\"></a>";
		sdf_carousel = sdf_carousel + "<div id=\"sdfCarousel\" class=\"carousel slide\"><ol class=\"carousel-indicators\">";
				
		var active_indicator = '';
		for ( var i = 0; i < shuffled_slides.length; i++ ) {
			if (i == 0) active_indicator = ' class=\"active\"';
			else active_indicator = '';
			sdf_carousel = sdf_carousel + "<li data-target=\"#sdfCarousel\" data-slide-to=\""+ i +"\""+ active_indicator +"></li>";
		};
		sdf_carousel = sdf_carousel + "<li data-target=\"#sdfCarousel\" data-slide-to=\""+ i +"\"></li>";
		sdf_carousel = sdf_carousel + "</ol><div class=\"carousel-inner\">";
		
		
		for ( var i = 0; i < shuffled_slides.length; i++ ) {
			if (i == 0) active_indicator = ' active';
			else active_indicator = '';
			sdf_carousel = sdf_carousel + "<div class=\"item"+ active_indicator +"\"><div class=\"container\"><div class=\"carousel-caption\">"+ shuffled_slides[i].slide_cap + "<p><a class=\"btn btn-large btn-warning\" href=\""+ shuffled_slides[i].slide_link + "\" rel=\"nofollow\" target=\"_blank\">Read More</a></p></div></div></div>";
		};
		sdf_carousel = sdf_carousel + "<div class=\"item\"><div class=\"container\"><div class=\"carousel-caption\">"+ sds_promo_blog_post + "</div></div></div>";
		sdf_carousel = sdf_carousel + "</div><a class=\"left carousel-control\" href=\"#sdfCarousel\" data-slide=\"prev\"><span class=\"glyphicon glyphicon-chevron-left\"></span></a><a class=\"right carousel-control\" href=\"#sdfCarousel\" data-slide=\"next\"><span class=\"glyphicon glyphicon-chevron-right\"></span></a></div>";
		
		promo_carousel.html(sdf_carousel).delay(500).fadeIn(400).carousel({ interval:12000 });
	}	
	
	// dashboard widget
	$('#sdf_dashboard_widget h3.hndle span').html(banners_remote.dashboard_widget[0].title);
	$('#sdf_dashboard_widget .inside').html(banners_remote.dashboard_widget[0].content);
	setTimeout(function(){
		$('#sdf_dashboard_widget .inside').fadeIn(400);
	},800);

});
 
})(jQuery);

/**
 * Randomize array element order in-place.
 * Using Fisher-Yates shuffle algorithm.
 */
function shuffleArray(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
}