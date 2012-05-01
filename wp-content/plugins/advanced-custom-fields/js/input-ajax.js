/*
*  Input Ajax
*
*  @description: show / hide metaboxes from changing category / tempalte / etc		
*  @author: Elliot Condon
*  @since: 3.1.4
*/

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
	
		// show metaboxes for this post
		acf.data = {
			action 			:	'get_input_metabox_ids',
			post_id			:	acf.post_id,
			page_template	:	false,
			page_parent		:	false,
			page_type		:	false,
			page			:	acf.post_id,
			post			:	acf.post_id,
			post_category	:	false,
			post_format		:	false,
			taxonomy		:	false
		};
		
		
		// add classes
		$('#poststuff .postbox[id*="acf_"]').addClass('acf_postbox');
		$('#adv-settings label[for*="acf_"]').addClass('acf_hide_label');
		
		// hide acf stuff
		$('#poststuff .acf_postbox').hide();
		$('#adv-settings .acf_hide_label').hide();
		
		// loop through acf metaboxes
		$('#poststuff .postbox.acf_postbox').each(function(){
			
			// vars
			var options = $(this).find('.inside > .options');
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
	*  update_fields
	*
	*  @description: finds the new id's for metaboxes and show's hides metaboxes
	*  @created: 1/03/2011
	*/
	
	function update_fields()
	{
		
		//console.log('update_fields');
		$.ajax({
			url: ajaxurl,
			data: acf.data,
			type: 'post',
			dataType: 'json',
			success: function(result){
				
				// hide all metaboxes
				$('#poststuff .acf_postbox').hide();
				$('#adv-settings .acf_hide_label').hide();
				
				
				// dont bother loading style or html for inputs
				if(result.length == 0)
				{
					return false;
				}
				
				
				// show the new postboxes
				$.each(result, function(k, v) {
					
					
					var postbox = $('#poststuff #acf_' + v);
					postbox.show();
					$('#adv-settings .acf_hide_label[for="acf_' + v + '-hide"]').show();
					
					// load fields if needed
					postbox.find('.acf-replace-with-fields').each(function(){
						
						var div = $(this);
						
						$.ajax({
							url: ajaxurl,
							data: {
								action : 'acf_input',
								acf_id : v,
								post_id : acf.post_id
							},
							type: 'post',
							dataType: 'html',
							success: function(html){
							
								div.replaceWith(html);
								
								$(document).trigger('acf/setup_fields', postbox);
								
							}
						});
						
					});
				});
				
				// load style
				$.ajax({
					url: ajaxurl,
					data: {
						action : 'get_input_style',
						acf_id : result[0]
					},
					type: 'post',
					dataType: 'html',
					success: function(result){
					
						$('#acf_style').html(result);
						
					}
				});
				
			}
		});
	}

	
	/*
	*  update_fields (Live change events)
	*
	*  @description: call the update_fields function on live events
	*  @created: 1/03/2011
	*/
		
	$('#page_template').live('change', function(){
		
		acf.data.page_template = $(this).val();
		update_fields();
	    
	});
	
	$('#parent_id').live('change', function(){
		
		var page_parent = $(this).val();
		
		if($(this).val() != "")
		{
			acf.data.page_type = 'child';
		}
		else
		{
			acf.data.page_type = 'parent';
		}
		
		update_fields();
	    
	});
	
	$('#taxonomy-category input[type="checkbox"]').live('change', function(){
		
		acf.data.post_category = ['0'];
		
		$('#categorychecklist :checked').each(function(){
			acf.data.post_category.push($(this).val())
		});
		
		//console.log(data.post_category);
		update_fields();
		
	});	
	
	
	$('#post-formats-select input[type="radio"]').live('change', function(){
		
		acf.data.post_format = $(this).val();
		update_fields();
		
	});	
	
	// taxonomy
	$('div[id*="taxonomy-"] input[type="checkbox"]').live('change', function(){
		
		// ignore categories
		if($(this).closest('#taxonomy-category').exists()) return false;
		
		acf.data.taxonomy = ['0'];
		
		$(this).closest('ul').find('input[type="checkbox"]:checked').each(function(){
			acf.data.taxonomy.push($(this).val())
		});
		
		update_fields();
		
	});	
	
	
})(jQuery);