(function($){
	var media = wp.media, mlaStrings = {},  mlaSettings = {};
	
/*	// for debug : trace every event triggered in the Region controller
	var originalTrigger = wp.media.view.MediaFrame.prototype.trigger;
	wp.media.view.MediaFrame.prototype.trigger = function(){
		console.log('MediaFrame Event: ', arguments[0]);
		originalTrigger.apply(this, Array.prototype.slice.call(arguments));
	} // */
	
/*	// for Network debug
	var originalAjax = media.ajax;
	media.ajax = function( action ) {
		console.log( 'media.ajax: action = ' + JSON.stringify( action ) );
		return originalAjax.apply(this, Array.prototype.slice.call(arguments));
	}; // */
	
	/**
	 * Localized settings and strings
	 */
	mlaStrings = typeof media.view.l10n.mla_strings === 'undefined' ? {} : media.view.l10n.mla_strings;
	delete media.view.l10n.mla_strings;
	
	mlaSettings = typeof wp.media.view.settings.mla_settings === 'undefined' ? {} : wp.media.view.settings.mla_settings;
	delete wp.media.view.settings.mla_settings;
	
	/**
	 * Extended Filters dropdown with more mimeTypes
	 */
	media.view.AttachmentFilters.Mla = media.view.AttachmentFilters.extend({
		createFilters: function() {
			var filters = {};

			_.each( mlaSettings.mimeTypes || {}, function( text, key ) {
				filters[ key ] = {
					text: text,
					props: {
						type:    key,
						uploadedTo: null,
						orderby: 'date',
						order:   'DESC'
					}
				};
			});

			filters.all = {
				text:  media.view.l10n.allMediaItems,
				props: {
					type:    null,
					uploadedTo: null,
					orderby: 'date',
					order:   'DESC'
				},
				priority: 10
			};

			filters.uploaded = {
				text:  media.view.l10n.uploadedToThisPost,
				props: {
					type:    null,
					uploadedTo: media.view.settings.post.id,
					orderby: 'menuOrder',
					order:   'ASC'
				},
				priority: 20
			};

			this.filters = filters;
		}
	});

	/**
	 * Extended Filters dropdown with month and year selection values
	 */
	media.view.AttachmentFilters.MlaMonths = media.view.AttachmentFilters.extend({
		className: 'attachment-months',

		createFilters: function() {
			var filters = {};

			_.each( mlaSettings.months || {}, function( text, key ) {
				filters[ key ] = {
					text: text,
					props: {
						m:    key,
					}
				};
			});

			this.filters = filters;
		},
		
		select: function() {
			var model = this.model,
				value = '0', // Show all dates
				props = model.toJSON();

			_.find( this.filters, function( filter, id ) {
				var equal = _.all( filter.props, function( prop, key ) {
					return prop === ( _.isUndefined( props[ key ] ) ? null : props[ key ] );
				});

				if ( equal )
					return value = id;
			});

			this.$el.val( value );
		}	});

	/**
	 * Extended Filters dropdown with taxonomy term selection values
	 */
	media.view.AttachmentFilters.MlaTerms = media.view.AttachmentFilters.extend({
		className: 'attachment-terms',

		createFilters: function() {
			var filters = {};

			_.each( mlaSettings.termsText || {}, function( text, key ) {
				filters[ key ] = {
					text: text,
					props: {
						mla_filter_term: parseInt( mlaSettings.termsValue[ key ] ),
					}
				};
			});

			this.filters = filters;
		},
		
		select: function() {
			var model = this.model,
				value = '0', // All terms
				props = model.toJSON();

			_.find( this.filters, function( filter, id ) {
				var equal = _.all( filter.props, function( prop, key ) {
					return prop === ( _.isUndefined( props[ key ] ) ? null : props[ key ] );
				});

				if ( equal )
					return value = id;
			});

			this.$el.val( value );
		}	});

	/**
	 * Extended wp.media.view.Search
	 */
	media.view.MlaSearch = media.View.extend({
		tagName:   'div',
		className: 'mla-search-box',
		template: media.template('mla-search-box'),

		attributes: {
			type:        'mla-search-box',
			placeholder: mlaStrings.searchBoxPlaceholder
		},

		events: {
//			'input':  'search',
//			'keyup':  'search',
			'change': 'search',
			'click': 'search',
			'search': 'search',
			'MlaSearch': 'search'
		},

		render: function() {
			this.$el.html( this.template( ) );
			return this;
		},

		search: function( event ) {
			// console.log( 'media.view.MlaSearch search: ' + event.type + ', ' + event.target.name + ', ' + event.target.value );
			if ( ( 'click' == event.type ) && ( 'mla_search_submit' != event.target.name ) ) {
				return;
			}
				
			switch ( event.target.name ) {
				case 'mla_search_value':
					mlaSettings.searchValue = event.target.value;
				case 'mla_search_submit':
					this.model.set({
						's': mlaSettings.searchValue + mlaSettings.searchFields + mlaSettings.searchConnector,
						'mla_search_value': mlaSettings.searchValue,
						'mla_search_fields': mlaSettings.searchFields,
						'mla_search_connector': mlaSettings.searchConnector });
				break;
				case 'mla_search_connector':
					mlaSettings.searchConnector = event.target.value;
				break;
				case 'mla_search_title':
				index = mlaSettings.searchFields.indexOf( 'title' );
				if ( -1 == index )
					mlaSettings.searchFields.push( 'title' )
				else
					mlaSettings.searchFields.splice( index, 1 );
				break;
				case 'mla_search_name':
				index = mlaSettings.searchFields.indexOf( 'name' );
				if ( -1 == index )
					mlaSettings.searchFields.push( 'name' )
				else
					mlaSettings.searchFields.splice( index, 1 );
				break;
				case 'mla_search_alt_text':
				index = mlaSettings.searchFields.indexOf( 'alt-text' );
				if ( -1 == index )
					mlaSettings.searchFields.push( 'alt-text' )
				else
					mlaSettings.searchFields.splice( index, 1 );
				break;
				case 'mla_search_excerpt':
				index = mlaSettings.searchFields.indexOf( 'excerpt' );
				if ( -1 == index )
					mlaSettings.searchFields.push( 'excerpt' )
				else
					mlaSettings.searchFields.splice( index, 1 );
				break;
				case 'mla_search_content':
				index = mlaSettings.searchFields.indexOf( 'content' );
				if ( -1 == index )
					mlaSettings.searchFields.push( 'content' )
				else
					mlaSettings.searchFields.splice( index, 1 );
				break;
			}
		}
	});

	/**
	 * Replace the media-toolbar with our own
	 */
	media.view.AttachmentsBrowser = media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			var filters, FiltersConstructor;

			// Add a query arg identifying this as an MLA extended query, so the backend can reroute the request
			media.model.Query.defaultArgs.mla_source = 'MLA';
			
			if ( mlaSettings.enableSearchBox ) {
				media.model.Query.defaultArgs.mla_search_value = mlaSettings.searchValue;
				media.model.Query.defaultArgs.mla_search_fields = mlaSettings.searchFields;
				media.model.Query.defaultArgs.mla_search_connector = mlaSettings.searchConnector;
			}

			this.toolbar = new media.view.Toolbar({
				controller: this.controller
			});

			this.views.add( this.toolbar );

			filters = this.options.filters;
			if ( 'uploaded' === filters )
				FiltersConstructor = media.view.AttachmentFilters.Uploaded;
			else if ( 'all' === filters ) {
				if ( mlaSettings.enableMimeTypes )
					FiltersConstructor = media.view.AttachmentFilters.Mla;
				else
					FiltersConstructor = media.view.AttachmentFilters.All;
			}

			if ( FiltersConstructor ) {
				this.toolbar.set( 'filters', new FiltersConstructor({
					controller: this.controller,
					model:      this.collection.props,
					priority:   -80
				}).render() );
			}

			if ( filters && mlaSettings.enableMonthsDropdown ) {
				this.toolbar.set( 'months', new media.view.AttachmentFilters.MlaMonths({
					controller: this.controller,
					model:      this.collection.props,
					priority:   -80
				}).render() );
			}

			if ( filters && mlaSettings.enableTermsDropdown ) {
				this.toolbar.set( 'terms', new media.view.AttachmentFilters.MlaTerms({
					controller: this.controller,
					model:      this.collection.props,
					priority:   -80
				}).render() );
			}

			if ( this.options.search ) {
				if ( mlaSettings.enableSearchBox ) {
					this.toolbar.set( 'MlaSearch', new media.view.MlaSearch({
						controller: this.controller,
						model:      this.collection.props,
						priority:   60
					}).render() );
				} else {
					this.toolbar.set( 'search', new media.view.Search({
						controller: this.controller,
						model:      this.collection.props,
						priority:   60
					}).render() );
				}
			}

			if ( this.options.dragInfo ) {
				this.toolbar.set( 'dragInfo', new media.View({
					el: $( '<div class="instructions">' +  media.view.l10n.dragInfo + '</div>' )[0],
					priority: -40
				}) );
			}
		}
	});
}(jQuery));
