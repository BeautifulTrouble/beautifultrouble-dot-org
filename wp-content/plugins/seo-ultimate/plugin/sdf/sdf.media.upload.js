/*global jQuery:false */
(function($) { 
    "use strict";
	jQuery(document).ready(function($) {
// sdf media upload
	var sdf_image_media_frame;
  $(document.body).on('click.sdf-open-media-manager', '.sdf-open-media', function(e) {
    e.preventDefault();
    var current_widget_form = $(e.currentTarget).parents('form:first');
    if ( sdf_image_media_frame ) {
      sdf_image_media_frame.open();
      return;
    }

    sdf_image_media_frame = wp.media.frames.sdf_image_media_frame = wp.media({
      className: 'media-frame sdf-image-media-frame',
      frame: 'select',
      multiple: false,
      title: 'Choose an Image',
      library: { type: 'image' },
      button: { text:  'Insert into the Widget' }
    });

    sdf_image_media_frame.on('select', function() {
      var media_attachment = sdf_image_media_frame.state().get('selection').first().toJSON();
      current_widget_form.find('.sdf-image-url').val(media_attachment.url)
      current_widget_form.find('.sdf-image-preview').attr('src', media_attachment.url)
    });

    sdf_image_media_frame.open();
  });

	$( ".wpu-media-upload" ).click( function( e ) {   
		e.preventDefault();
		
		var activeFileUploadContext = $(this).parent().parent(),
		custom_file_frame = null,
		item_clicked = $(this);

		// Create the media frame.
		custom_file_frame = wp.media.frames.customHeader = wp.media({
			title: $(this).data( "choose" ),
			library: {
				type: 'image'
			},
				button: {
						text: $(this).data( "update" )
				}
		});

		custom_file_frame.on( "select", function() {
			var attachment = custom_file_frame.state().get( "selection" ).first();

				// Update value of the targetfield input with the attachment url.
				//$( '.mfn-opts-screenshot', activeFileUploadContext ).attr( 'src', attachment.attributes.url );
				$( 'input.wpu-image', activeFileUploadContext )
				.val( attachment.attributes.url )
				.trigger( 'change' );

				//$( '.wpu-media-upload', activeFileUploadContext ).hide();
			 // $( '.mfn-opts-screenshot', activeFileUploadContext ).show();
		});

		custom_file_frame.open();
	});

});	
})(jQuery);	