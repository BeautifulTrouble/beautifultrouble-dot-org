/* global ajaxurl, uploader */

var jQuery,
	mla_add_new_bulk_edit_vars,
	mla = {
		// Properties (for mla-set-parent-scripts)
		// mla.settings.uploadTitle
		// mla.settings.comma for flat taxonomy suggest
		// mla.settings.ajaxFailError for setParent
		// mla.settings.ajaxDoneError for setParent
		// mla.settings.useDashicons for setParent
		settings: {},
	
		// Utility functions
		utility: {
			getId : function( o ) {
				var id = jQuery( o ).closest( 'tr' ).attr( 'id' ),
					parts = id.split( '-' );
				return parts[ parts.length - 1 ];
			}
		},
	
		// Components
		addNewBulkEdit: null,
		setParent: null
	};

( function( $ ) {
	/**
	 * Localized settings and strings
	 */
	mla.settings = typeof mla_add_new_bulk_edit_vars === 'undefined' ? {} : mla_add_new_bulk_edit_vars;
	mla_add_new_bulk_edit_vars = void 0; // delete won't work on Globals

	mla.addNewBulkEdit = {
		init: function() {
			var button, bypass = $( '.upload-flash-bypass' ), 
			    uploadDiv = $( '#mla-add-new-bulk-edit-div' ).hide(); // Start with area closed up

			$( '#bulk-edit-set-parent', uploadDiv ).on( 'click', function(){
				return mla.addNewBulkEdit.parentOpen();
			});

			// Move the Open/Close Bulk Edit area button to save space on the page
			button = $( '#bulk-edit-toggle', uploadDiv ).detach();
			button.appendTo( bypass );

			// Hook the "browser uploader" link to close the Bulk Edit area when it is in use
			button.siblings( 'a' ).on( 'click', function(){
				button.attr( 'title', mla.settings.toggleOpen );
				button.attr( 'value', mla.settings.toggleOpen );
				uploadDiv.hide();
			});

			button.on( 'click', function(){
				return mla.addNewBulkEdit.formToggle();
			});
			
			//auto-complete/suggested matches for flat taxonomies
			$( 'textarea.mla_tags', uploadDiv ).each(function(){
				var taxname = $(this).attr('name').replace(']', '').replace('tax_input[', '');

				$(this).suggest( ajaxurl + '?action=ajax-tag-search&tax=' + taxname, { delay: 500, minchars: 2, multiple: true, multipleSep: mla.settings.comma + ' ' } );
			});

			uploader.bind( 'BeforeUpload', function( up, file ) {
				var formString = $( '#file-form' ).serialize();
				
				up.settings.multipart_params.mlaAddNewBulkEdit['formString'] = formString;
			});
		},

		formToggle : function() {
			var button = $( '#bulk-edit-toggle' ), area = $( '#mla-add-new-bulk-edit-div' );
			
			// Expand/collapse the Bulk Edit area
			if ( 'none' === area.css( 'display' ) ) {
				button.attr( 'title', mla.settings.toggleClose );
				button.attr( 'value', mla.settings.toggleClose );
			} else {
				button.attr( 'title', mla.settings.toggleOpen );
				button.attr( 'value', mla.settings.toggleOpen );
			}
			
			area.slideToggle( 'slow' );
		},

		parentOpen : function() {
			var parentId, postId, postTitle;

			postId = -1;
			postTitle = mla.settings.uploadTitle;
			parentId = $( '#mla-add-new-bulk-edit-div :input[name="post_parent"]' ).val() || -1;
			mla.setParent.open( parentId, postId, postTitle );
			/*
			 * Grab the "Update" button
			 */
			$( '#mla-set-parent-submit' ).on( 'click', function( event ){
				event.preventDefault();
				mla.addNewBulkEdit.parentSave();
				return false;
			});
		},

		parentSave : function() {
			var foundRow = $( '#mla-set-parent-response-div input:checked' ).closest( 'tr' ), parentId, newParent;

			if ( foundRow.length ) {
				parentId = $( ':radio', foundRow ).val() || '';
				newParent = $('#mla-add-new-bulk-edit-div :input[name="post_parent"]').clone( true ).val( parentId );
				$('#mla-add-new-bulk-edit-div :input[name="post_parent"]').replaceWith( newParent );
			}

			mla.setParent.close();
			$('#mla-set-parent-submit' ).off( 'click' );
		},

	}; // mla.addNewBulkEdit

	$( document ).ready( function() {
		mla.addNewBulkEdit.init();
	});
})( jQuery );
