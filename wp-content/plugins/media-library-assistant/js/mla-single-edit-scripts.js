// These functions are adapted from wp-admin/js/post.js

var tagBox;

// return an array with any duplicate, whitespace or values removed
function array_unique_noempty(a) {
	var out = [];
	jQuery.each( a, function(key, val) {
		val = jQuery.trim(val);
		if ( val && jQuery.inArray(val, out) == -1 )
			out.push(val);
		} );
	return out;
}

(function($){

	tagBox = {
		clean : function(tags) {
			var comma = mla_single_edit_vars.comma;
			if ( ',' !== comma )
				tags = tags.replace(new RegExp(comma, 'g'), ',');
			tags = tags.replace(/\s*,\s*/g, ',').replace(/,+/g, ',').replace(/[,\s]+$/, '').replace(/^[,\s]+/, '');
			if ( ',' !== comma )
				tags = tags.replace(/,/g, comma);
			return tags;
		},

	parseTags : function(el) {
		var id = el.id, num = id.split('-check-num-')[1], taxbox = $(el).closest('.tagsdiv'),
			thetags = taxbox.find('.the-tags'), comma = mla_single_edit_vars.comma,
			current_tags = thetags.val().split(comma), new_tags = [];
		delete current_tags[num];

		$.each( current_tags, function(key, val) {
			val = $.trim(val);
			if ( val ) {
				new_tags.push(val);
			}
		});

		thetags.val( this.clean( new_tags.join(comma) ) );

		this.quickClicks(taxbox);
		return false;
	},

	quickClicks : function(el) {
		var thetags = $('.the-tags', el),
			tagchecklist = $('.tagchecklist', el),
			id = $(el).attr('id'),
			current_tags, disabled;

		if ( !thetags.length )
			return;

		disabled = thetags.prop('disabled');

		current_tags = thetags.val().split(mla_single_edit_vars.comma);
		tagchecklist.empty();

		$.each( current_tags, function( key, val ) {
			var span, xbutton;

			val = $.trim( val );

			if ( ! val )
				return;

			// Create a new span, and ensure the text is properly escaped.
			span = $('<span />').text( val );

			// If tags editing isn't disabled, create the X button.
			if ( ! disabled ) {
				xbutton = $( '<a id="' + id + '-check-num-' + key + '" class="ntdelbutton">X</a>' );
				xbutton.click( function(){ tagBox.parseTags(this); });
				span.prepend('&nbsp;').prepend( xbutton );
			}

			// Append the span to the tag list.
			tagchecklist.append( span );
		});
	},

	flushTags : function(el, a, f) {
		a = a || false;
		var tags = $('.the-tags', el),
			newtag = $('input.newtag', el),
			comma = mla_single_edit_vars.comma,
			newtags, text;

		text = a ? $(a).text() : newtag.val();
		tagsval = tags.val();
		newtags = tagsval ? tagsval + comma + text : text;

		newtags = this.clean( newtags );
		newtags = array_unique_noempty( newtags.split(comma) ).join(comma);
		tags.val(newtags);
		this.quickClicks(el);

		if ( !a )
			newtag.val('');
		if ( 'undefined' == typeof(f) )
			newtag.focus();

		return false;
	},

	get : function(id) {
		var tax = id.substr(id.indexOf('-')+1);

		$.post(ajaxurl, {'action':'get-tagcloud', 'tax':tax}, function(r, stat) {
			if ( 0 == r || 'success' != stat )
				r = wpAjax.broken;

			r = $('<p id="tagcloud-'+tax+'" class="the-tagcloud">'+r+'</p>');
			$('a', r).click(function(){
				tagBox.flushTags( $(this).closest('.inside').children('.tagsdiv'), this);
				return false;
			});

			$('#'+id).after(r);
		});
	},

	init : function() {
		var t = this, ajaxtag = $('div.ajaxtag');

	    $('.tagsdiv').each( function() {
	        tagBox.quickClicks(this);
	    });

		$('input.tagadd', ajaxtag).click(function(){
			t.flushTags( $(this).closest('.tagsdiv') );
		});

		$('div.taghint', ajaxtag).click(function(){
			$(this).css('visibility', 'hidden').parent().siblings('.newtag').focus();
		});

		$('input.newtag', ajaxtag).blur(function() {
			if ( this.value == '' )
	            $(this).parent().siblings('.taghint').css('visibility', '');
	    }).focus(function(){
			$(this).parent().siblings('.taghint').css('visibility', 'hidden');
		}).keyup(function(e){
			if ( 13 == e.which ) {
				tagBox.flushTags( $(this).closest('.tagsdiv') );
				return false;
			}
		}).keypress(function(e){
			if ( 13 == e.which ) {
				e.preventDefault();
				return false;
			}
		}).each(function(){
			var tax = $(this).closest('div.tagsdiv').attr('id');
			$(this).suggest( ajaxurl + '?action=ajax-tag-search&tax=' + tax, { delay: 500, minchars: 2, multiple: true, multipleSep: mla_single_edit_vars.comma + ' ' } );
		});

	    // save tags on post save/publish
	    $('#post').submit(function(){
			$('div.tagsdiv').each( function() {
	        	tagBox.flushTags(this, false, 1);
			});
		});

		// tag cloud
		$('a.tagcloud-link').click(function(){
			tagBox.get( $(this).attr('id') );
			$(this).unbind().click(function(){
				$(this).siblings('.the-tagcloud').toggle();
				return false;
			});
			return false;
		});
	}
};

})(jQuery);

jQuery(document).ready( function($) {
//	alert('jQuery(document).ready(function($)');
//	console.log('jQuery(document).ready(function($)');
	
	// multi-taxonomies
	$('#side-info-column').children('div.postbox').each(function(){
		if ( this.id.indexOf('tagsdiv-') === 0 ) {
			tagBox.init();
			return false;
		}
	});
	
	// categories
	$('.categorydiv').each( function(){
		var this_id = $(this).attr('id'), noSyncChecks = false, syncChecks, catAddAfter, taxonomyParts, taxonomy, settingName;

		taxonomyParts = this_id.split('-');
		taxonomyParts.shift();
		taxonomy = taxonomyParts.join('-');
 		settingName = taxonomy + '_tab';
// 		if ( taxonomy == 'category' )
// 			settingName = 'cats';

		// TODO: move to jQuery 1.3+, support for multiple hierarchical taxonomies, see wp-lists.dev.js
		$('a', '#' + taxonomy + '-tabs').click( function(){
			var t = $(this).attr('href');
			$(this).parent().addClass('tabs').siblings('li').removeClass('tabs');
			$('#' + taxonomy + '-tabs').siblings('.tabs-panel').hide();
			$(t).show();
			if ( '#' + taxonomy + '-all' == t )
				deleteUserSetting(settingName);
			else
				setUserSetting(settingName, 'pop');
			return false;
		});

		if ( getUserSetting(settingName) )
			$('a[href="#' + taxonomy + '-pop"]', '#' + taxonomy + '-tabs').click();

		// Ajax Cat
		$('#new' + taxonomy).one( 'focus', function() { $(this).val( '' ).removeClass( 'form-input-tip' ) } );
		$('#' + taxonomy + '-add-submit').click( function(){ $('#new' + taxonomy).focus(); });

		syncChecks = function() {
			if ( noSyncChecks )
				return;
			noSyncChecks = true;
			var th = jQuery(this), c = th.is(':checked'), id = th.val().toString();
			$('#in-' + taxonomy + '-' + id + ', #in-' + taxonomy + '-category-' + id).prop( 'checked', c );
			noSyncChecks = false;
		};

		catAddBefore = function( s ) {
			if ( !$('#new'+taxonomy).val() )
				return false;
			s.data += '&' + $( ':checked', '#'+taxonomy+'checklist' ).serialize();
			$( '#' + taxonomy + '-add-submit' ).prop( 'disabled', true );
			return s;
		};

		catAddAfter = function( r, s ) {
			var sup, drop = $('#new'+taxonomy+'_parent');

			$( '#' + taxonomy + '-add-submit' ).prop( 'disabled', false );
			if ( 'undefined' != s.parsed.responses[0] && (sup = s.parsed.responses[0].supplemental.newcat_parent) ) {
				drop.before(sup);
				drop.remove();
			}
		};

		$('#' + taxonomy + 'checklist').wpList({
			alt: '',
			response: taxonomy + '-ajax-response',
			addBefore: catAddBefore,
			addAfter: catAddAfter
		});

		$('#' + taxonomy + '-add-toggle').click( function() {
			$('#' + taxonomy + '-adder').toggleClass( 'wp-hidden-children' );
			$('a[href="#' + taxonomy + '-all"]', '#' + taxonomy + '-tabs').click();
			$('#new'+taxonomy).focus();
			return false;
		});

		$('#' + taxonomy + 'checklist li.popular-category :checkbox, #' + taxonomy + 'checklist-pop :checkbox').live( 'click', function(){
			var t = $(this), c = t.is(':checked'), id = t.val();
			if ( id && t.parents('#taxonomy-'+taxonomy).length )
				$('#in-' + taxonomy + '-' + id + ', #in-popular-' + taxonomy + '-' + id).prop( 'checked', c );
		});

	}); // end cats

});
