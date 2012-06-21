jQuery(document).ready(function($) {
	// Delicious:
    $.each($("span.delicious_hash"), function () {
      var elem = $(this);
      $.ajax({ type: "GET",
          dataType: "jsonp",
          url: "http://feeds.delicious.com/v2/json/urlinfo/"+$(this).html(),
          success: function(data){
                 if (data.length > 0) {
                 	var posts = parseInt(data[0].total_posts);
                 	if (posts > 1000000) {
                 		var txt = parseInt(posts/1000000);
                 		elem.next().prepend(txt + 'M');
                 	} else if (posts > 1000) {
                 		var txt = parseInt(posts/1000);
                 		elem.next().prepend(txt + 'K');
                 	} else {
                 		elem.next().prepend(posts);
                 	}
                 } else {
                 	elem.next().prepend('0');
                 }
             }
        });
    });
});