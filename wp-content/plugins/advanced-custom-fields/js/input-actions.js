/*
*  Input Actions
*
*  @description: javascript for fields functionality		
*  @author: Elliot Condon
*  @since: 3.1.4
*/

var acf = {
	admin_url : '',
	post_id : 0,
	validation : false,
	text : {
		'validation_error' : "Validation Failed. One or more fields below are required.",
		'file_tb_title_add' : "Add File to Field",
		'file_tb_title_edit' : "Edit File",
		'image_tb_title_add' : "Add Image to Field",
		'image_tb_title_edit' : "Edit Image",
		'relationship_max_alert' : "Maximum values reached ( {max} values )",
		'gallery_tb_title_add' : "Add Image to Gallery",
		'gallery_tb_title_edit' : "Edit Image",
		'repeater_min_alert' : "Minimum rows reached ( {min} rows )",
		'repeater_max_alert' : "Maximum rows reached ( {max} rows )"
	}
};

(function($){
	
		
	/*
	*  Exists
	*
	*  @description: returns true / false		
	*  @created: 1/03/2011
	*/
	
	$.fn.exists = function()
	{
		return $(this).length>0;
	};
	
	
	/*
	*  Document Ready
	*
	*  @description: adds ajax data
	*  @created: 1/03/2011
	*/
	
	$(document).ready(function(){
	
		// add classes
		$('#poststuff .postbox[id*="acf_"]').addClass('acf_postbox');
		$('#adv-settings label[for*="acf_"]').addClass('acf_hide_label');
		
		// hide acf stuff
		$('#poststuff .acf_postbox').hide();
		$('#adv-settings .acf_hide_label').hide();
		
		// loop through acf metaboxes
		$('#poststuff .postbox.acf_postbox').each(function(){
			
			// vars
			var options = $(this).find('> .inside > .options');
			var show = options.attr('data-show');
			var layout = options.attr('data-layout');
			var id = $(this).attr('id').replace('acf_', '');
			
			// layout
			$(this).addClass(layout);
			
			// show / hide
			if(show == 'true')
			{
				$(this).show();
				$('#adv-settings .acf_hide_label[for="acf_' + id + '-hide"]').show();
			}
			
		});
	
	});
	
	
	/*
	*  Submit form
	*
	*  @description: does validation, deletes all hidden metaboxes (otherwise, post data will be overriden by hidden inputs)
	*  @created: 1/03/2011
	*/
	
	$('form#post').live("submit", function(){
		
		// do validation
		do_validation();
		
		if(acf.validation == false)
		{
			// show message
			$(this).siblings('#message').remove();
			$(this).before('<div id="message" class="error"><p>' + acf.text.validation_error + '</p></div>');
			
			
			// hide ajax stuff on submit button
			$('#publish').removeClass('button-primary-disabled');
			$('#ajax-loading').attr('style','');
			
			return false;
		}
		
		$('.acf_postbox:hidden').remove();
		
		// submit the form
		return true;
		
	});
	
	
	/*
	*  do_validation
	*
	*  @description: checks fields for required input
	*  @created: 1/03/2011
	*/
	
	function do_validation(){
		
		acf.validation = true;
		
		$('.field.required:visible, .form-field.required').each(function(){
			
			var validation = true;

			// text / textarea
			if($(this).find('input[type="text"], input[type="hidden"], textarea').val() == "")
			{
				validation = false;
			}
			
			// select
			if($(this).find('select').exists())
			{
				if($(this).find('select').val() == "null" || !$(this).find('select').val())
				{
					validation = false;
				}
			}
			
			// checkbox
			if($(this).find('input[type="checkbox"]:checked').exists())
			{
				validation = true;
			}
			
			// checkbox
			if($(this).find('.acf_relationship').exists() && $(this).find('input[type="hidden"]').val() != "")
			{
				validation = true;
			}
			
			// repeater
			if($(this).find('.repeater').exists())
			{

				if($(this).find('.repeater tr.row').exists())
				{
					validation = true;
				}
				else
				{
					validation = false;
				}
				
			}
			
			
			// flexible content
			if($(this).find('.acf_flexible_content').exists())
			{
				if($(this).find('.acf_flexible_content .values table').exists())
				{
					validation = true;
				}
				else
				{
					validation = false;
				}
				
			}
			
			
			// gallery
			if($(this).find('.acf-gallery').exists())
			{
				if($(this).find('.acf-gallery .thumbnail').exists())
				{
					validation = true;
				}
				else
				{
					validation = false;
				}
				
			}
			

			// set validation
			if(!validation)
			{
				acf.validation = false;
				$(this).closest('.field').addClass('error');
			}
			
		});
		
		
	}
	
	
	/*
	*  Remove error class on focus
	*
	*  @description: 
	*  @created: 1/03/2011
	*/

	// inputs / textareas
	$('.field.required input, .field.required textarea, .field.required select').live('focus', function(){
		$(this).closest('.field').removeClass('error');
	});
	
	// checkbox
	$('.field.required input:checkbox').live('click', function(){
		$(this).closest('.field').removeClass('error');
	});
	
	// wysiwyg
	$('.field.required .acf_wysiwyg').live('mousedown', function(){
		$(this).closest('.field').removeClass('error');
	});
	
	
	/*
	*  Field: Color Picker
	*
	*  @description: 
	*  @created: 1/03/2011
	*/
	
	var farbtastic;
			
	$(document).ready(function(){
	
		// validate
		if( ! $.farbtastic)
		{
			return;
		}
		
		$('body').append('<div id="acf_color_picker" />');
		
		farbtastic = $.farbtastic('#acf_color_picker');
		
	});
	
	
	// update colors
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('input.acf_color_picker').each(function(i){
			
			// validate
			if( ! $.farbtastic)
			{
				return;
			}
			
			
			$.farbtastic( $(this) ).setColor( $(this).val() ).hsl[2] > 0.5 ? color = '#000' : color = '#fff';
			$(this).css({ 
				backgroundColor : $(this).val(),
				color : color
			});
			
		});
		
	});
	
				
	$('input.acf_color_picker').live('focus', function(){
		
		var input = $(this);
		
		$('#acf_color_picker').css({
			left: input.offset().left,
			top: input.offset().top - $('#acf_color_picker').height(),
			display: 'block'
		});
		
		farbtastic.linkTo(this);
		
	}).live('blur', function(){

		$('#acf_color_picker').css({
			display: 'none'
		});
						
	});
	
	
	/*
	*  Field: File
	*
	*  @description: 
	*  @created: 1/03/2011
	*/
	
	// add file
	$('.acf-file-uploader .add-file').live('click', function(){
				
		// vars
		var div = $(this).closest('.acf-file-uploader');
		
		// set global var
		window.acf_div = div;
			
		// show the thickbox
		tb_show( acf.text.file_tb_title_add , acf.admin_url + 'media-upload.php?post_id=' + acf.post_id + '&type=file&acf_type=file&TB_iframe=1');
	
		return false;
	});
	
	// remove file
	$('.acf-file-uploader .remove-file').live('click', function(){
		
		// vars
		var div = $(this).closest('.acf-file-uploader');
		
		div.removeClass('active').find('input.value').val('');
		
		return false;
		
	});
	
	// edit file
	$('.acf-file-uploader .edit-file').live('click', function(){
		
		// vars
		var div = $(this).closest('.acf-file-uploader'),
			id = div.find('input.value').val();
		

		// set global var
		window.acf_edit_attachment = div;
				
		
		// show edit attachment
		tb_show( acf.text.file_tb_title_edit , acf.admin_url + 'media.php?attachment_id=' + id + '&action=edit&acf_action=edit_attachment&acf_field=file&TB_iframe=1');
		
		
		return false;
			
	});
	
	
	/*
	*  Field: Image
	*
	*  @description: 
	*  @created: 1/03/2011
	*/
	
	// add image
	$('.acf-image-uploader .add-image').live('click', function(){
				
		// vars
		var div = $(this).closest('.acf-image-uploader'),
			preview_size = div.attr('data-preview_size');
		
		// set global var
		window.acf_div = div;
			
		// show the thickbox
		tb_show( acf.text.image_tb_title_add , acf.admin_url + 'media-upload.php?post_id=' + acf.post_id + '&type=image&acf_type=image&acf_preview_size=' + preview_size + 'TB_iframe=1');
	
		return false;
	});
	
	// remove image
	$('.acf-image-uploader .remove-image').live('click', function(){
		
		// vars
		var div = $(this).closest('.acf-image-uploader');
		
		div.removeClass('active');
		div.find('input.value').val('');
		div.find('img').attr('src', '');
		
		return false;
			
	});
	
	// edit image
	$('.acf-image-uploader .edit-image').live('click', function(){
		
		// vars
		var div = $(this).closest('.acf-image-uploader'),
			id = div.find('input.value').val();
		

		// set global var
		window.acf_edit_attachment = div;
				
		
		// show edit attachment
		tb_show( acf.text.image_tb_title_edit , acf.admin_url + 'media.php?attachment_id=' + id + '&action=edit&acf_action=edit_attachment&acf_field=image&TB_iframe=1');
		
		
		return false;
			
	});
	
	
	/*
	*  Field: Relationship
	*
	*  @description: 
	*  @created: 3/03/2011
	*/
	
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.acf_relationship').each(function(){
			
			$(this).find('.relationship_right .relationship_list').unbind('sortable').sortable({
				axis: "y", // limit the dragging to up/down only
				items: 'a:not(.hide)',
			    start: function(event, ui)
			    {
					ui.item.addClass('sortable_active');
			    },
			    stop: function(event, ui)
			    {
			    	ui.item.removeClass('sortable_active');
			    	ui.item.closest('.acf_relationship').update_acf_relationship_value();
			    }
			});
			
		});
		
	});
	
	
	// updates the input value of a relationship field
	$.fn.update_acf_relationship_value = function(){
	
		// vars
		var div = $(this);
		var value = "";
		
		// add id's to array
		div.find('.relationship_right .relationship_list a:not(.hide)').each(function(){
			value += $(this).attr('data-post_id') + ",";
		});
		
		// remove last ","
		value = value.slice(0, -1);
		
		// set value
		div.children('input').val(value);
		
	};
	
	// add from left to right
	$('.acf_relationship .relationship_left .relationship_list a').live('click', function(){
		
		// vars
		var id = $(this).attr('data-post_id');
		var div = $(this).closest('.acf_relationship');
		var max = parseInt(div.attr('data-max')); if(max == -1){ max = 9999; }
		var right = div.find('.relationship_right .relationship_list');
		
		// max posts
		if(right.find('a:not(.hide)').length >= max)
		{
			alert( acf.text.relationship_max_alert.replace('{max}', max) );
			return false;
		}

		// hide / show
		$(this).addClass('hide');
		right.find('a[data-post_id="' + id + '"]').removeClass('hide').appendTo(right);
		
		// update input value
		div.update_acf_relationship_value();
		
		// validation
		div.closest('.field').removeClass('error');
		
		return false;
		
	});
	
	// remove from right to left
	$('.acf_relationship .relationship_right .relationship_list a').live('click', function(){
		
		// vars
		var id = $(this).attr('data-post_id');
		var div = $(this).closest('.acf_relationship');
		var left = div.find('.relationship_left .relationship_list');
		
		// hide / show
		$(this).addClass('hide');
		left.find('a[data-post_id="' + id + '"]').removeClass('hide');
		
		// update input value
		div.update_acf_relationship_value();

		return false;
		
	});
	
	
	// search left
	$.expr[':'].Contains = function(a,i,m){
    	return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
	};
	$('.acf_relationship input.relationship_search').live('change', function()
	{	
		// vars
		var filter = $(this).val();
		var div = $(this).closest('.acf_relationship');
		var left = div.find('.relationship_left .relationship_list');
		
	    if(filter)
	    {
			left.find("a:not(:Contains(" + filter + "))").addClass('filter_hide');
	        left.find("a:Contains(" + filter + "):not(.hide)").removeClass('filter_hide');
	    }
	    else
	    {
	    	left.find("a:not(.hide)").removeClass('filter_hide');
	    }

	    return false;
	    
	})
	.live('keyup', function(){
	    $(this).change();
	})
	.live('focus', function(){
		$(this).siblings('label').hide();
	})
	.live('blur', function(){
		if($(this).val() == "")
		{
			$(this).siblings('label').show();
		}
	});
	
	
	
	/*
	*  Field: WYSIWYG
	*
	*  @description: 
	*  @created: 3/03/2011
	*/
	
	// store wysiwyg buttons
	var acf_wysiwyg_buttons = {};
	
	
	// destroy wysiwyg
	$.fn.acf_deactivate_wysiwyg = function(){
		
		$(this).find('.acf_wysiwyg textarea').each(function(){
			wpActiveEditor = null;
			tinyMCE.execCommand("mceRemoveControl", false, $(this).attr('id'));
		});
		
	};
	
	
	// create wysiwyg
	$.fn.acf_activate_wysiwyg = function(){
		

		// add tinymce to all wysiwyg fields
		$(this).find('.acf_wysiwyg textarea').each(function(){
			
			
			if(tinyMCE != undefined && tinyMCE.settings != undefined)
			{

				// reset buttons
				tinyMCE.settings.theme_advanced_buttons1 = acf_wysiwyg_buttons.theme_advanced_buttons1;
				tinyMCE.settings.theme_advanced_buttons2 = acf_wysiwyg_buttons.theme_advanced_buttons2;
			
				var toolbar = $(this).closest('.acf_wysiwyg').attr('data-toolbar');
				
				if(toolbar == 'basic')
				{
					//'bold', 'italic', 'underline', 'blockquote', 'separator', 'strikethrough', 'bullist', 'numlist', 'justifyleft', 'justifycenter', 'justifyright', 'undo', 'redo', 'link', 'unlink', 'fullscreen'
					tinyMCE.settings.theme_advanced_buttons1 = "bold, italic, underline, blockquote, |, strikethrough, bullist, numlist, justifyleft, justifycenter, justifyright, undo, redo, link, unlink, fullscreen";
					tinyMCE.settings.theme_advanced_buttons2 = "";
				}
				else
				{
					// add images + code buttons
					tinyMCE.settings.theme_advanced_buttons2 += ",code";
				}
				
				
			}
			
			wpActiveEditor = null;
			tinyMCE.execCommand('mceAddControl', false, $(this).attr('id'));

		});
		
	};
	
	
	// create wysiwygs
	$(document).live('acf/setup_fields', function(e, postbox){
		
		if(typeof(tinyMCE) != "object")
		{
			return false;
		}
		
		$(postbox).acf_activate_wysiwyg();

	});
	
	$(document).ready( function(){
		
		// store variables
		if(tinyMCE != undefined && tinyMCE.settings != undefined)
		{
			acf_wysiwyg_buttons.theme_advanced_buttons1 = tinyMCE.settings.theme_advanced_buttons1;
			acf_wysiwyg_buttons.theme_advanced_buttons2 = tinyMCE.settings.theme_advanced_buttons2;
		}
		
	});
	
	$(window).load(function(){
		
		setTimeout(function(){
			$('#acf_settings-tmce').trigger('click');
		}, 1);
		
		setTimeout(function(){
			$(document).trigger('acf/setup_fields', $('#poststuff'));
		}, 10);
		
	});
	
	// Sortable: Start
	$('.repeater > table > tbody, .acf_flexible_content > .values').live( "sortstart", function(event, ui) {
		
		$(ui.item).find('.acf_wysiwyg textarea').each(function(){
			tinyMCE.execCommand("mceRemoveControl", false, $(this).attr('id'));
		});
		
	});
	
	// Sortable: End
	$('.repeater > table > tbody, .acf_flexible_content > .values').live( "sortstop", function(event, ui) {
		
		$(ui.item).find('.acf_wysiwyg textarea').each(function(){
			tinyMCE.execCommand("mceAddControl", false, $(this).attr('id'));
		});
		
	});
	
	
	/*
	*  Field: Repeater
	*
	*  @description: 
	*  @created: 3/03/2011
	*/
	
	// create a unique id
	function uniqid()
    {
    	var newDate = new Date;
    	return newDate.getTime();
    }
    
    
	// update order numbers
	function repeater_update_order( repeater )
	{
		repeater.find('> table > tbody > tr.row').each(function(i){
			$(this).children('td.order').html(i+1);
		});
	
	};
	
	
	// make sortable
	function repeater_add_sortable( repeater ){
		
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};
		
		repeater.find('> table > tbody').unbind('sortable').sortable({
			update: function(event, ui){
				repeater_update_order( repeater );
			},
			items : '> tr.row',
			handle: '> td.order',
			helper: fixHelper,
			forceHelperSize: true,
			forcePlaceholderSize: true,
			scroll: true,
			start: function (event, ui) {
				
				// add markup to the placeholder
				var td_count = ui.item.children('td').length;
        		ui.placeholder.html('<td colspan="' + td_count + '"></td>');
   			}
		});
	};
	
	
	// setup repeater fields
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.repeater').each(function(){
			
			var repeater = $(this),
				min_rows = parseInt( repeater.attr('data-min_rows') ),
				max_rows = parseInt( repeater.attr('data-max_rows') );	
			
			
			// move row-clone to be the first element (to avoid double border css bug)
			var row_clone = repeater.find('> table > tbody > tr.row-clone');
			
			// also, deactivate any wysiwyg in the row clone
			row_clone.acf_deactivate_wysiwyg();
			if( row_clone.index() != 0 )
			{
				row_clone.closest('tbody').prepend( row_clone );
			}
			
			
			// update classes based on row count
			repeater_check_rows( repeater );
			
			
			// sortable
			if( max_rows > 1 ){
				repeater_add_sortable( repeater );
			}
			
		});
			
	});
	
	
	// repeater_check_rows
	function repeater_check_rows( repeater )
	{
		// vars
		var max_rows = parseInt( repeater.attr('data-max_rows') ),
			row_count = repeater.find('> table > tbody > tr.row').length;	

		
		// empty?
		if( row_count == 0 )
		{
			repeater.addClass('empty');
		}
		else
		{
			repeater.removeClass('empty');
		}
		
		
		// row limit reached
		if( row_count >= max_rows )
		{
			repeater.addClass('disabled');
		}
		else
		{
			repeater.removeClass('disabled');
		}
	}
	
	
	// add field
	function repeater_add_field( repeater, before )
	{
		// vars
		var max_rows = parseInt( repeater.attr('data-max_rows') ),
			row_count = repeater.find('> table > tbody > tr.row').length;	
			
			
		// validate
		if( row_count >= max_rows )
		{
			alert( acf.text.repeater_max_alert.replace('{max}', max_rows) );
			return false;
		}
		
	
		// create and add the new field
		var new_id = uniqid(),
			new_field_html = repeater.find('> table > tbody > tr.row-clone').html().replace(/(="[\w-\[\]]*?)(\[999\])/g, '$1[' + new_id + ']'),
			new_field = $('<tr class="row"></tr>').append( new_field_html );
		
		
		// add row
		if( before )
		{
			before.before( new_field );
		}
		else
		{
			repeater.find('> table > tbody').append(new_field); 
		}
		
		
		// trigger mouseenter on parent repeater to work out css margin on add-row button
		repeater.closest('tr').trigger('mouseenter');
		
		
		// update order
		repeater_update_order( repeater );
		
		
		// update classes based on row count
		repeater_check_rows( repeater );
		
		
		// setup fields
		$(document).trigger('acf/setup_fields', new_field);

		
		// validation
		repeater.closest('.field').removeClass('error');
	}
	
	
	// add row - end
	$('.repeater .repeater-footer .add-row-end').live('click', function(){
		
		var repeater = $(this).closest('.repeater');
		
		
		repeater_add_field( repeater, false );
		
		
		return false;
	});
	
	
	// add row - before
	$('.repeater .add-row-before').live('click', function(){
		
		var repeater = $(this).closest('.repeater'),
			before = $(this).closest('tr');
			
			
		repeater_add_field( repeater, before );
		
		
		return false;
	});
	
	
	function repeater_remove_row( tr )
	{	
		// vars
		var repeater =  tr.closest('.repeater'),
			min_rows = parseInt( repeater.attr('data-min_rows') ),
			row_count = repeater.find('> table > tbody > tr.row').length,
			column_count = tr.children('tr.row').length,
			row_height = tr.height();
			
			
		// validate
		if( row_count <= min_rows )
		{
			alert( acf.text.repeater_min_alert.replace('{min}', row_count) );
			return false;
		}
		
		
		// animate out tr
		tr.addClass('acf-remove-item');
		setTimeout(function(){
			
			tr.remove();
			
			
			// trigger mouseenter on parent repeater to work out css margin on add-row button
			repeater.closest('tr').trigger('mouseenter');
		
		
			// update order
			repeater_update_order( repeater );
			
			
			// update classes based on row count
			repeater_check_rows( repeater );
			
		}, 400);
		
	}
	
	
	// remove field
	$('.repeater .remove-row').live('click', function(){
		var tr = $(this).closest('tr');
		repeater_remove_row( tr );
		return false;
	});
	
	
	// hover over tr, align add-row button to top
	$('.repeater tr').live('mouseenter', function(){
		
		var button = $(this).find('> td.remove > a.add-row');
		var margin = ( button.parent().height() / 2 ) + 9; // 9 = padding + border
		
		button.css('margin-top', '-' + margin + 'px' );
		
	});
	
	
	
	/*-----------------------------------------------------------------------------
	*
	*	Flexible Content
	*
	*----------------------------------------------------------------------------*/
	
	
	/*
	*  flexible_content_add_sortable
	*
	*  @description: 
	*  @created: 25/05/12
	*/
	
	function flexible_content_add_sortable( div )
	{
		
		// remove (if clone) and add sortable
		div.children('.values').unbind('sortable').sortable({
			items : '> .layout',
			handle: '> .actions .order'
		});
		
	};
	
	
	/*
	*  Show Popup
	*
	*  @description: 
	*  @created: 25/05/12
	*/
	
	$('.acf_flexible_content .flexible-footer .add-row-end').live('click', function()
	{
		$(this).trigger('focus');
		
	}).live('focus', function()
	{
		$(this).siblings('.acf-popup').addClass('active');
		
	}).live('blur', function()
	{
		var button = $(this);
		setTimeout(function(){
			button.siblings('.acf-popup').removeClass('active');
		}, 250);
		
	});
	
	
	/*
	*  flexible_content_remove_row
	*
	*  @description: 
	*  @created: 25/05/12
	*/
	
	function flexible_content_remove_layout( layout )
	{
		// vars
		var div = layout.closest('.acf_flexible_content');
		var temp = $('<div style="height:' + layout.height() + 'px"></div>');
		
		
		// animate out tr
		layout.addClass('acf-remove-item');
		setTimeout(function(){
			
			layout.before(temp).remove();
			
			temp.animate({'height' : 0 }, 250, function(){
				temp.remove();
			});
		
			if(!div.children('.values').children('.layout').exists())
			{
				div.children('.no_value_message').show();
			}
			
		}, 400);
		
	}
	
	
	$('.acf_flexible_content .actions .delete').live('click', function(){
		var layout = $(this).closest('.layout');
		flexible_content_remove_layout( layout );
		return false;
	});
	
	
	// add layout
	$('.acf_flexible_content .acf-popup ul li a').live('click', function(){

		// vars
		var layout = $(this).attr('data-layout');
		var div = $(this).closest('.acf_flexible_content');
		
		
		// create new field
		var new_id = uniqid(),
			new_field_html = div.find('> .clones > .layout[data-layout="' + layout + '"]').html().replace(/(="[\w-\[\]]*?)(\[999\])/g, '$1[' + new_id + ']'),
			new_field = $('<div class="layout" data-layout="' + layout + '"></div>').append( new_field_html );
			

		// hide no values message
		div.children('.no_value_message').hide();
		
		
		// add row
		div.children('.values').append(new_field); 
		
		
		// activate wysiwyg
		$(document).trigger('acf/setup_fields',new_field);
		
		
		// validation
		div.closest('.field').removeClass('error');
		
		return false;
		
	});
	
	
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.acf_flexible_content').each(function(){
			
			var div =  $(this);
			
			// deactivate any wysiwygs
			div.children('.clones').acf_deactivate_wysiwyg();
			
			// sortable
			flexible_content_add_sortable( div );
		});
		
	});
	
	
	/*
	*  Field: Datepicker
	*
	*  @description: 
	*  @created: 4/03/2011
	*/
	
	$('input.acf_datepicker').live('focus', function(){

		var input = $(this);
		
		if(!input.hasClass('active'))
		{
			
			// vars
			var format = input.attr('data-date_format') ? input.attr('data-date_format') : 'dd/mm/yy';
			
			// add date picker and refocus
			input.addClass('active').datepicker({ 
				dateFormat: format 
			})
			
			// set a timeout to re focus the input (after it has the datepicker!)
			setTimeout(function(){
				input.trigger('blur').trigger('focus');
			}, 1);
			
			// wrap the datepicker (only if it hasn't already been wrapped)
			if($('body > #ui-datepicker-div').length > 0)
			{
				$('#ui-datepicker-div').wrap('<div class="ui-acf" />');
			}
			
		}
		
	});
	
	
	/*
	*  acf.add_message
	*
	*  @description: 
	*  @since: 3.2.7
	*  @created: 10/07/2012
	*/
	
	acf.add_message = function( message, div ){
		
		var message = $('<div class="acf-message-wrapper"><div class="message updated"><p>' + message + '</p></div></div>');
		
		div.prepend( message );
		
		setTimeout(function(){
			
			message.animate({
				opacity : 0
			}, 250, function(){
				message.remove();
			});
			
		}, 1500);
			
	};
	
	
	/*
	*  Field: Gallery
	*
	*  @description: 
	*  @since: 3.2.7
	*  @created: 10/07/2012
	*/
	
	acf.update_gallery_count = function( div )
	{
		// vars
		var count = div.find('.thumbnails .thumbnail').length,
			max_count = ( count > 2 ) ? 2 : count,
			span = div.find('.toolbar .count');
		
		
		span.html( span.attr('data-' + max_count).replace('{count}', count) );
		
	}
	
	
	// view: Grid
	$('.acf-gallery .toolbar .view-grid').live('click', function(){
		
		// vars
		var gallery = $(this).closest('.acf-gallery');
		
		
		// active class
		$(this).parent().addClass('active').siblings('.view-list-li').removeClass('active');
		
		
		// gallery class
		gallery.removeClass('view-list');
		
		
		return false;
			
	});
	
	
	// view: Grid
	$('.acf-gallery .toolbar .view-list').live('click', function(){
		
		// vars
		var gallery = $(this).closest('.acf-gallery');
		
		
		// active class
		$(this).parent().addClass('active').siblings('.view-grid-li').removeClass('active');
		
		
		// gallery class
		gallery.addClass('view-list');
		
		
		return false;
			
	});
	
	
	// remove image
	$('.acf-gallery .thumbnail .remove-image').live('click', function(){
		
		// vars
		var thumbnail = $(this).closest('.thumbnail'),
			gallery = thumbnail.closest('.acf-gallery');
		
		
		thumbnail.animate({
			opacity : 0
		}, 250, function(){
			
			thumbnail.remove();
			
			acf.update_gallery_count( gallery );
			
		});
		
		return false;
			
	});
	
	
	// remove image
	$('.acf-gallery .thumbnail .edit-image').live('click', function(){
		
		// vars
		var div = $(this).closest('.thumbnail'),
			id = div.attr('data-id');
		
		
		// set global var
		window.acf_edit_attachment = div;
				
		
		// show edit attachment
		tb_show( acf.text.gallery_tb_title_edit , acf.admin_url + 'media.php?attachment_id=' + id + '&action=edit&acf_action=edit_attachment&acf_field=gallery&TB_iframe=1');
		
		
		return false;
			
	});
	
	
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.acf-gallery').each(function(i){
			
			// vars
			var div = $(this),
				thumbnails = div.find('.thumbnails'),
				toolbar = div.find('.toolbar'),
				preview_size = div.attr('data-preview_size');
			
			
			// update count
			acf.update_gallery_count( div );
			
			
			// add new
			toolbar.find('.add-image').unbind('click').click( function(){
				
				
				// set global var
				window.acf_div = div;
					
					
				// show the thickbox
				tb_show( acf.text.gallery_tb_title_add , acf.admin_url + 'media-upload.php?post_id=' + acf.post_id + '&type=image&acf_type=gallery&acf_preview_size=' + preview_size + 'TB_iframe=1');
			
			
				return false;	
							
			});
			
			
			// sortable
			thumbnails.find('> .inner').unbind('sortable').sortable({
				items : '> .thumbnail',
				/* handle: '> td.order', */
				forceHelperSize: true,
				forcePlaceholderSize: true,
				scroll: true,
				start: function (event, ui) {
				
					// alter width / height to allow for 2px border
					ui.placeholder.width( ui.placeholder.width() - 4 );
					ui.placeholder.height( ui.placeholder.height() - 4 );
	   			}
			});
			
			
			return false;
			
		});
	
	});
	
	setInterval(function(){
		
		//console.log( tinyMCE.activeEditor );
		
	}, 2000)
	
	
})(jQuery);