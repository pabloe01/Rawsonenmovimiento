/**
 * CSS Properties based on WP versions
 * WP-3.9            				|  WP4.0
 * -----------------------------------------------
 * a.more-filters    				|  a.drawer-toggle
 * div.filtering-by    				|  div.filtered-by
**/
			
(function($) {
	
	var l10n = cf7s.l10n; // translation
	
	cf7sAdmin = {
		init : function( url ) {
			var t = this, post_id;
			this.post_id = $("#post_ID").val();
			
			// Check if hidden input for template/style is inside the form			
			if ( $("#cf7s-template").parents("#wpcf7-admin-form-element").length == 1 ) { 
				// input is inside form
			} else {
				$("#wpcf7-admin-form-element").append( $("#cf7s-template") );
				$("#wpcf7-admin-form-element").append( $("#cf7s-style") );
			}
			
			$("#cf7s .nav-tab:nth-child(1)").addClass("nav-tab-active");
			$("#cf7s .nav-tab-content > div:nth-child(1)").addClass("active");
			$("#cf7s .nav-tab").click( function(e) {
				t.tab(this); return false;
			});
			$("#cf7s a.select").live("click", function(){
				t.select(this); return false;
			});
			$("#cf7s a.detail").live("click", function(){
				t.details(this); return false;
			});
			$("#cf7s a.close").live("click", function(){
				t.close(this); return false;
			});
			$("#cf7s a.view").click( function() {
				t.view(this); return false;
			});
			$("#cf7s a.skin-sort").click( function() {
				t.sort(this); return false;
			});			
			$("#cf7s a.more-filters").add("#cf7s a.drawer-toggle").click( function() {
				t.filters(this); return false;
			});
			$("#cf7s input[type='checkbox']").click( function() {
				t.addFilter(this);
			});
			$("#cf7s .clear-filters").add("#cf7s a.drawer-toggle").add("#cf7s a.more-filters").click( function(e) {
				t.clearFilters(e);
			});
			$("#cf7s .apply-filters").click( function(e) {
				t.applyFilters(e);
			});
			$("#cf7s .filtered-by a").click( function(e) {
				t.backToFilters(e);
			});
			$("#cf7s .skins-search").keyup( function() {
				t.skinsSearch(this); 
			});
			$("#cf7s .skins-search").keypress( function(event) {
				if ( event.keyCode == 13 )
					event.preventDefault();
			});
			
			// Skin ordering for template and style tab
			$("#cf7s .dashicons").on( "click", function(e) {
				t.orderSkin(e);
			});
			$("#cf7s select.sort-by").change( function(e) {
				var tab = $(this).closest(".tab-content");
				$(".dashicons", tab).trigger( "click" );
			});
			
			// Set tipsy defaults
			if ( $.isFunction( $.fn.tipsy ) ) {
				$.fn.tipsy.defaults = {
					delayIn: 0,      	// delay before showing tooltip (ms)
					delayOut: 0,     	// delay before hiding tooltip (ms)
					fade: false,     	// fade tooltips in/out?
					fallback: '',    	// fallback text to use when no tooltip text
					gravity: 'n',    	// gravity nw | n | ne | w | e | sw | s | se
					html: false,     	// is tooltip content HTML?
					live: false,     	// use live event support?
					offset: 0,       	// pixel offset of tooltip from element
					opacity: 1,			// opacity of tooltip
					title: 'title',  	// attribute/callback containing tooltip text
					trigger: 'hover' 	// how tooltip is triggered - hover | focus | manual
				};			

				$(".balloon").tipsy({gravity: 'sw'});
				$("span.ext-link > a.help").tipsy({gravity: 'se'}); 
			}
			
			// Check if any changes have been made
			var formmodified = false;
			var submitted = false;
 
			$('#wpcf7-form, #cf7s-style, #cf7s-template').change(function(){
				$('#message').slideToggle( "slow", function() {
					$(this).remove();
				});			
				
				formmodified = true;
			});
			
			// Set new variable value when submitting form
			$("form").submit(function() {
				submitted = true;
			});			
			
			// Display saving notification
			window.onbeforeunload = confirmExit;
			function confirmExit() {
				if ( formmodified && ! submitted ) {					
					return 'Changes have been made, are you sure you want to leave?';
				}
			}
			
			// Expand collapse skin box for CF7 >= 4.2
			$('#cf7skins-42 .handlediv').click( function(e) {
				e.stopPropagation();
				$(this).parent('.postbox').toggleClass('closed');
			});			
		},
		
		tab : function(e) {
			var id = $(e).attr("href");
			$(e).siblings("a").removeClass("nav-tab-active");
			$(e).addClass("nav-tab-active");
			$(".nav-tab-content > div").addClass("hidden");
			$(id).removeClass("hidden").addClass("active");
		},
		
		select : function(e) {
			var inp, pos, wrap, details, skin, textarea;
			skin = $(e).attr("data-value"),
			inp = $(e).attr("href"); // this is the hidden input for storing selected template/style
			wrap = $(e).closest(".tab-content");
			$(inp).val(skin).trigger('change');
			details = $(e).closest(".details");
			
			// Default contact form editor id is #wpcf7-form
			// In case if another plugins modify the textarea for cf7 editor, get the first visible textarea in the container			
			// Check if CF7 is above or 4.2
			if ( $("#formdiv .half-left")[0] )
				textarea = $("#formdiv .half-left").find('textarea').filter(':visible:first');
			else	
				textarea = $("#wpcf7-admin-form-element").find('textarea').filter(':visible:first');
			
			// remove and add highlight to the selected skin
			$(".skin", wrap).removeClass("skin-selected");
			//$(e).closest(".skin").addClass("skin-selected");
			$('a[data-value="'+skin+'"]').closest(".skin").addClass("skin-selected");
			
			// remove link decoration and change text
			$("a.select", wrap).removeClass("selected").text( l10n.select );
			$('a.select[data-value="'+skin+'"]', wrap).addClass("selected").text( l10n.selected );
			$('a.select[data-value="'+skin+'"]', details).addClass("selected").text( l10n.selected );
			
			// Only for template
			if( inp.indexOf( "template" ) != -1 ) { 
				
				// Get the CF7 content position and animate to top
				// pos = $("#wpcf7-form").position();
				// $("body, html").animate({ scrollTop: pos.top }, 800 );
				
				$(textarea).val( l10n.loading );
				
				$.post( ajaxurl, { 
					action: cf7s.load, 					
					template: skin, 
					post: $(e).attr("data-post"), 
					locale: $(e).attr("data-locale"), 
					nonce: cf7s.nonce 
				}, function( data ) {
					$(textarea).val( data ).trigger('change');
				});
			}
		},
		
		details : function(e) {
			var id = $(e).attr("href");
			$(e).closest(".tab-content").find(".skin-details").show();
			$(e).closest(".tab-content").find(".skin-list").hide();
			$(id).removeClass("hidden");
		},
		
		close : function(e) {
			$(".details").addClass("hidden");
			$(".skin-list").show();
			$(".skin-details").hide();
		},
		
		view : function(e) {
			var inp, pos, wrap;
			skin = $(e).attr("data-value"),
			wrap = $(e).closest(".details");
			
			if( l10n.expanded == $(e).text() ) {			
				$(".expanded-view", wrap).show();
				$(".details-view", wrap).hide();
			} else {
				$(".expanded-view", wrap).hide();
				$(".details-view", wrap).show();
			}
		},

		sort : function(e) {
			if( cf7sAdmin.load )
				return false;
			
			cf7sAdmin.load = true;
			
			var tab = $(e).closest(".tab-content");
			
			// add current class
			$(".skin-sort", tab).removeClass("current");
			$( tab ).removeClass( 'filters-applied more-filters-opened' );
			$(e).closest(".tab-content").find(".skin-list").show();
			$(e).addClass("current");
			
			// empty tab content
			$(".skin-list .skin", tab).remove();
			$(".skin-list .spinner", tab).show();
			$(".skin-list .no-skins", tab).remove();	

			// hide current if detailed/expanded view if visible
			$(".skin-details", tab).hide(); 	
			$(".skin-details .details", tab).addClass("hidden"); 	
			
			// sort it
			$.post( ajaxurl, { 
				action: cf7s.sort, 					
				tab: $(tab).attr("id"), 					
				sort: $(e).attr( "data-sort" ),
				id: cf7sAdmin.post_id,
				nonce: cf7s.nonce 
			}, function( data ) {
				$(".skin-list .spinner", tab).hide();				
				$(".skin-list", tab).append(data);
				$(".dashicons", tab).trigger( "click" );
				$(".theme-count", tab).text( $(e).closest(".tab-content").find(".skin").length );
				cf7sAdmin.load = false;
			});
		},
		
		filters : function(e) {
			var activetab = $(e).closest(".tab-content");
			if ( $( activetab ).hasClass( 'filters-applied' ) ) {
				return this.backToFilters();
			}
			if ( $( activetab ).hasClass( 'more-filters-opened' ) && this.filtersChecked() ) {
				return this.addFilter();
			}
			$( activetab ).toggleClass( 'more-filters-opened' );
			$(activetab).find(".skin-list").toggle();
		},
			
		// Clicking on a checkbox to add another filter to the request
		addFilter: function() {
			this.filtersChecked();
		},		
			
		// Applying filters triggers a tag request
		applyFilters: function( event ) {
			var tab = $(event.currentTarget).closest(".tab-content"),
			tabID = $(tab).attr("id");

			var name,
				tags = this.filtersChecked(),
				request = { tag: tags },
				filteringBy = $( '.filtered-by .tags', tab );

			if ( event ) {
				event.preventDefault();
			}
			
			if( ! tags ) {
				alert( l10n.emptyfilter );
				return;
			}

			$( 'body' ).addClass( 'filters-applied' );
			$( '.theme-section.current' ).removeClass( 'current' );
			filteringBy.empty();

			_.each( tags, function( tag ) {
				name = $( 'label[for="' + tabID + '-' + tag + '"]' ).text();
				filteringBy.append( '<span class="tag">' + name + '</span>' );
			});

			$(".skin-list .skin", tab).remove();
			$(".skin-list .no-skins", tab).remove();
			$(".skin-list .spinner", tab).show();
			
			if( tags )
				$.post( ajaxurl, { 
					action: cf7s.sort, 					
					tab: $(tab).attr("id"), 					
					sort: "tag",
					tags: tags,
					nonce: cf7s.nonce 
				}, function( data ) {		
					$(".skin-list .spinner", tab).hide();
					$(".skin-list", tab).append(data).show();
					$(".theme-count", tab).text( $(tab).find(".skin").length );
					cf7sAdmin.load = false;
				});
		},		
		
		filtersChecked: function() {
			var items = $( '.feature-group' ).find( ':checkbox' ),
				tags = [];

			_.each( items.filter( ':checked' ), function( item ) {
				tags.push( $( item ).prop( 'value' ) );
			});

			// When no filters are checked, restore initial state and return
			if ( tags.length === 0 ) {
				$( '.apply-filters' ).find( 'span' ).text( '' );
				$( '.clear-filters' ).hide();
				$( 'body' ).removeClass( 'filters-applied' );
				return false;
			}

			$( '.apply-filters' ).find( 'span' ).text( tags.length );
			$( '.clear-filters' ).css( 'display', 'inline-block' );
			
			return tags;
		},
		
		clearFilters: function( event ) {
			var items = $( '.feature-group' ).find( ':checkbox' ),
				self = this;

			event.preventDefault();

			_.each( items.filter( ':checked' ), function( item ) {
				$( item ).prop( 'checked', false );
				return self.filtersChecked();
			});
		},

		backToFilters: function( event ) {
			if ( event ) {
				event.preventDefault();
			}

			$( 'body' ).removeClass( 'filters-applied' );
		},
			
		skinsSearch: function(e) {
			if( cf7sAdmin.load || $(e).val().length < 3 ) 
				return;
				
			var tab;
			tab = $(e).closest(".tab-content");	
		
			$(".skin-list .skin", tab).remove();
			$(".skin-list .spinner", tab).show();
			$(".skin-list .no-skins", tab).remove();
			
			clearTimeout( $.data( e, "cf7sadmin" ) );
			
			var wait = setTimeout( function() {					
				$.post( ajaxurl, { 
					action: cf7s.sort, 					
					tab: $(tab).attr("id"), 					
					sort: "search",
					id: cf7sAdmin.post_id,
					keyword: $(e).val(),
					nonce: cf7s.nonce 
				}, function( data ) {					
					$(".skin-list", tab).append(data);
					$(".skin-list .spinner", tab).hide();
					$(".theme-count", tab).text( $(e).closest(".tab-content").find(".skin").length );
					cf7sAdmin.load = false;
				});				
			}, 750);
			
			$(e).data( "cf7sadmin", wait );		
		},
		
		orderSkin : function(e) {
			var icon, tab, skinlist, sortby, skins, attrs;
			
			icon = $(e.currentTarget);
			
			// stackoverflow.com/questions/6674669/in-jquery-how-can-i-tell-between-a-programatic-and-user-click#answer-6674806
			if( e.hasOwnProperty('originalEvent') ) {
				if( icon.hasClass("dashicons-arrow-up-alt") )
					icon.removeClass("dashicons-arrow-up-alt").addClass("dashicons-arrow-down-alt");
				else
					icon.removeClass("dashicons-arrow-down-alt").addClass("dashicons-arrow-up-alt");
			}
				
			tab = icon.closest(".tab-content");
			skinlist = $(tab).find(".skin-list");
			sortby = $(tab).find("select.sort-by").val();
			
			skins = [];			
			skinlist.children('.skin').each( function(i,e) {
				skins.push( $(e).attr("data-"+sortby) );
			});
						
			skins.sort();
			
			if( icon.hasClass("dashicons-arrow-up-alt") )
				skins.reverse();
				
			console.log( "["+sortby+"] "+skins );  // Use to inspect sort in console.log
			
			$.each( skins, function(i,e){
				skinlist.append( $(".skin[data-"+sortby+"='"+e+"']", skinlist) );
			});
		},
	};
	
	$(document).ready(function(){cf7sAdmin.init();});
})(jQuery);