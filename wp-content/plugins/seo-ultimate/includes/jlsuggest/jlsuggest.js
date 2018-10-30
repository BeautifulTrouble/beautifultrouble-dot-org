jQuery(document).ready( function($) {
	$('input.jlsuggest', '.sdf-admin,#su-postmeta-box').each(function() {
		var params = $(this).attr('su:params') ? '&' + $(this).attr('su:params') : '';
		$(this).jlsuggest(ajaxurl + '?action=su-jlsuggest-autocomplete' + params,
			{ delay: 500, minchars: 2, multiple: false, textDest: true, noUrls: true } );
	});
} );


/*
 * jquery.jlsuggest
 * Based on WordPress's jquery.suggest 1.1b (2007-08-06)
 * Modified by John Lamansky (2011-05-16)
 */
 
(function($) {
	
	$.jlsuggest = function(input, options) {
		var $input, $results, timeout, prevLength, cache, cacheSize;
		
		$input = $(input).attr("autocomplete", "off");
		$results = $(document.createElement("ul"));
		
		timeout = false;		// hold timeout ID for suggestion results to appear
		prevLength = 0;			// last recorded length of $input.val()
		cache = [];				// cache MRU list
		cacheSize = 0;			// size of cache in chars (bytes?)
		
		$results.addClass(options.resultsClass).appendTo('body');
		
		
		resetPosition();
		$(window)
			.load(resetPosition)		// just in case user is changing size of page while loading
			.resize(resetPosition);
		
		$input.blur(function() {
			setTimeout(function() { $results.hide() }, 200);
		});
		
		
		// help IE users if possible
		if ( $.browser.msie ) {
			try {
				$results.bgiframe();
			} catch(e) { }
		}
		
		// I really hate browser detection, but I don't see any other way
		if ($.browser.mozilla)
			$input.keypress(processKey);	// onkeypress repeats arrow keys in Mozilla/Opera
		else
			$input.keydown(processKey);		// onkeydown repeats arrow keys in IE/Safari
		
		$('.' + options.textDestCloseClass).click(function() {
			$(this).parent().siblings('.' + options.textDestTextClass + ':first').text('').parent().hide().removeClass(options.textDestDisabledClass).siblings('input:first').val('').show().focus()
		});
		
		
		function resetPosition() {
			// requires jquery.dimension plugin
			var offset = $input.offset();
			$results.css({
				top: (offset.top + input.offsetHeight) + 'px',
				left: offset.left + 'px'
			});
		}
		
		
		function processKey(e) {
			
			// handling up/down/escape requires results to be visible
			// handling enter/tab requires that AND a result to be selected
			if ((/27$|38$|40$/.test(e.keyCode) && $results.is(':visible')) ||
				(/^13$|^9$/.test(e.keyCode) && getCurrentResult())) {
				
				if (e.preventDefault)
					e.preventDefault();
				if (e.stopPropagation)
					e.stopPropagation();
				
				e.cancelBubble = true;
				e.returnValue = false;
				
				switch(e.keyCode) {
					
					case 38: // up
						prevResult();
						break;
					
					case 40: // down
						nextResult();
						break;
					
					case 9:  // tab
					case 13: // return
						selectCurrentResult();
						break;
					
					case 27: //	escape
						$results.hide();
						break;
					
				}

			} else if ($input.val().length != prevLength) {
				
				if (timeout)
						clearTimeout(timeout);
				
				//Only trigger suggestions if the minimum length is met and if this isn't a URL (assuming the noUrls option is set)
				if ($input.val().length >= options.minchars && (!options.noUrls || ($input.val().substring(0, 7) != 'http://' && $input.val().substring(0, 8) != 'https://' && $input.val().indexOf('/') == '-1'))) {
					$input.addClass(options.timeoutClass);
					
					//Wait a bit before giving autocomplete suggestions
					timeout = setTimeout(suggest, options.delay);
				} else {
					$results.hide();
					$input.removeClass(options.timeoutClass);
				}
				
				prevLength = $input.val().length;
				
			}
		
		
		}
		
		
		function suggest() {
			
			var q = $.trim($input.val()), items;
			
			if (q.length >= options.minchars) {

				cached = checkCache(q);

				if (cached) {

					displayItems(cached['items']);

				} else {

					$.get(options.source, {q: q}, function(txt) {
						
						$results.hide();
						
						items = parseTxt(txt, q);
						
						displayItems(items);
						addToCache(q, items, txt.length);

					});

				}

			} else {

				$results.hide();
				$input.removeClass(options.timeoutClass);
			}

		}


		function checkCache(q) {
			var i;
			for (i = 0; i < cache.length; i++)
				if (cache[i]['q'] == q) {
					cache.unshift(cache.splice(i, 1)[0]);
					return cache[0];
				}

			return false;

		}

		function addToCache(q, items, size) {
			var cached;
			while (cache.length && (cacheSize + size > options.maxCacheSize)) {
				cached = cache.pop();
				cacheSize -= cached['size'];
			}

			cache.push({
				q: q,
				size: size,
				items: items
				});

			cacheSize += size;

		}

		function displayItems(items) {
			var i;
			if (!items)
				return;
			
			if (!items.length) {
				$results.hide();
				$input.removeClass(options.timeoutClass);
				return;
			}

			resetPosition(); // when the form moves after the page has loaded
			
			$results.html(items.join('')).show();
			
			$results
				.children('li.' + options.itemClass)
				.mouseover(function() {
					$results.children('li.' + options.itemClass).removeClass(options.selectClass);
					$(this).addClass(options.selectClass);
				})
				.click(function(e) {
					e.preventDefault();
					e.stopPropagation();
					selectCurrentResult();
				});
			
			$input.removeClass(options.timeoutClass);
		}

		function parseTxt(txt, q) {

			var lis = [], items = $.parseJSON(txt), i, item;

			// parse returned data for non-empty items
			for (i = 0; i < items.length; i++) {
				
				item = items[i];
				if (item) {
					
					if (item.isheader)
						html = '<li class="' + options.headerClass + '">' + item.text + '</li>';
					else {
						html = item.text.replace(
							new RegExp(q, 'ig'),
							function(q) { return '<span class="' + options.matchClass + '">' + q + '</span>' }
						);
						html = '<li'
							+ ' class="' + Encoder.htmlEncode(options.itemClass, true) + '"'
							+ ' su:value="' + Encoder.htmlEncode(item.value || '', true) + '"'
							+ ' su:selectedtext="' + Encoder.htmlEncode(item.selectedtext || '', true) + '"'
							+ '>' + html + '</li>';
					}
					lis[lis.length] = html;
				}
				
			}

			return lis;
		}

		function getCurrentResult() {
			var $currentResult;
			if (!$results.is(':visible'))
				return false;

			$currentResult = $results.children('li.' + options.selectClass);

			if (!$currentResult.length)
				$currentResult = false;

			return $currentResult;

		}

		function selectCurrentResult() {

			$currentResult = getCurrentResult();

			if ($currentResult) {
				if (options.textDest) {
					$input
						.hide() //Hide the input box
						.siblings('.' + options.textDestClass + ':first')
						.show() //Show the selection box
						.children('.' + options.textDestTextClass)
						.html($currentResult.attr('su:selectedtext') || $currentResult.text()) //Put the selected item into the selection box
						.parentsUntil('tr') //If we're in a table...
						.next('td')
						.children('input')
						.focus(); //...then focus the next textbox in the table
					
					$input.val($currentResult.attr('su:value'));
				} else {
					$input.val($currentResult.text());
				}
				$results.hide();

				if (options.onSelect)
					options.onSelect.apply($input[0]);

			}

		}

		function nextResult() {

			$currentResult = getCurrentResult();

			if ($currentResult)
				$currentResult
					.removeClass(options.selectClass)
					.nextAll('.' + options.itemClass + ':first')
						.addClass(options.selectClass);
			else
				$results.children('li.' + options.itemClass + ':first').addClass(options.selectClass);

		}

		function prevResult() {
			var $currentResult = getCurrentResult();

			if ($currentResult)
				$currentResult
					.removeClass(options.selectClass)
					.prevAll('.' + options.itemClass + ':first')
						.addClass(options.selectClass);
			else
				$results.children('li.' + options.itemClass + ':last').addClass(options.selectClass);

		}
	}
	
	$.fn.jlsuggest = function(source, options) {
		
		if (!source)
			return;
		
		options = options || {};
		options.source = source;
		options.delay = options.delay || 100;
		options.resultsClass = options.resultsClass || 'jls_results';
		options.selectClass = options.selectClass || 'jls_over';
		options.matchClass = options.matchClass || 'jls_match';
		options.headerClass = options.headerClass || 'jls_header';
		options.itemClass = options.itemClass || 'jls_item';
		options.minchars = options.minchars || 2;
		options.delimiter = options.delimiter || '\n';
		options.onSelect = options.onSelect || false;
		options.maxCacheSize = options.maxCacheSize || 65536;
		options.noUrls = options.noUrls || false;
		options.textDest = options.textDest || false;
		options.textDestClass = options.textDestClass || 'jls_text_dest';
		options.textDestDisabledClass = options.textDestDisabledClass || 'jlsuggest-disabled';
		options.textDestTextClass = options.textDestTextClass || 'jls_text_dest_text';
		options.textDestCloseClass = options.textDestCloseClass || 'jls_text_dest_close';
		options.timeoutClass = options.timeoutClass || 'jls_loading';
		
		this.each(function() {
			new $.jlsuggest(this, options);
		});

		return this;

	};

})(jQuery);
