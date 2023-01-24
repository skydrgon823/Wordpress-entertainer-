jQuery(document).ready(function( $ ) {
	"use strict";

	var WprTemplatesKit = {

		requiredTheme: false,
		requiredPlugins: false,

		init: function() {

			// Overlay Click
			$('.wpr-templates-kit-grid').find('.image-overlay').on('click', function(){
				WprTemplatesKit.showImportPage( $(this).closest('.grid-item') );
				WprTemplatesKit.renderImportPage( $(this).closest('.grid-item') );
			});

			// Logo Click
			$('.wpr-templates-kit-logo').find('.back-btn').on('click', function(){
				WprTemplatesKit.showTemplatesMainGrid();
			});

			// Import Templates Kit
			$('.wpr-templates-kit-single').find('.import-kit').on('click', function(){
				if ( $('.wpr-templates-kit-grid').find('.grid-item[data-kit-id="'+ $(this).attr('data-kit-id') +'"]').data('price') === 'pro' ) {
					return false;
				}

				var confirmImport = confirm('Are you sure you want to import this Template Kit?\n\nElementor Header, Footer, Pages, Media Files, Menus and some required plugins will be installed on your website.');
				
				if ( confirmImport ) {
					WprTemplatesKit.importTemplatesKit( $(this).attr('data-kit-id') );
					$('.wpr-import-kit-popup-wrap').fadeIn();
				}
			});

			// Close Button Click
			$('.wpr-import-kit-popup-wrap').find('.close-btn').on('click', function(){
				$('.wpr-import-kit-popup-wrap').fadeOut();
			});

			// Search Templates Kit
			var searchTimeout = null;  
			$('.wpr-templates-kit-search').find('input').keyup(function(e) {
				if ( e.which === 13 ) {
					return false;
				}

				var val = $(this).val();

				if (searchTimeout != null) {
					clearTimeout(searchTimeout);
				}

				searchTimeout = setTimeout(function() {
					searchTimeout = null;
					WprTemplatesKit.searchTemplatesKit( val );

					// Final Adjustments
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'wpr_search_query_results',
							search_query: val
						},
						success: function( response ) {}
					});
				}, 1000);  
			});

			// Price Filter
			$('.wpr-templates-kit-price-filter ul li').on('click', function() {
				var price = $(this).text(),
					price = 'premium' == price.toLowerCase() ? 'pro' : price.toLowerCase();

				WprTemplatesKit.fiterFreeProTemplates( price );
				$('.wpr-templates-kit-price-filter').children().first().attr( 'data-price', price );
				$('.wpr-templates-kit-price-filter').children().first().text( 'Price: '+ $(this).text() );
			});

			// Import Single Template // TODO: Disable Single Template import for now
			// $('.wpr-templates-kit-single').find('.import-template').on('click', function(){
			// 	var confirmImport = confirm('Are you sure you want to import this Template?');
				
			// 	if ( confirmImport ) {
			// 		console.log($('.import-kit').attr('data-kit-id'))
			// 		console.log($(this).attr('data-template-id'))
			// 		WprTemplatesKit.importSingleTemplate( $('.import-kit').attr('data-kit-id'), $(this).attr('data-template-id') );
			// 	}
			// });

		},

		installRequiredTheme: function( kitID ) {
			var themeStatus = $('.wpr-templates-kit-grid').data('theme-status');

			if ( 'ashe-active' === themeStatus ) {
				WprTemplatesKit.requiredTheme = true;
				return;
			} else if ( 'ashe-inactive' === themeStatus ) {
		        $.post(
		            ajaxurl,
		            {
		                action: 'wpr_activate_reuired_theme',
		            }
		        );

		        WprTemplatesKit.requiredTheme = true;
		        return;			
			}

			wp.updates.installTheme({
				slug: 'ashe',
				success: function() {
			        $.post(
			            ajaxurl,
			            {
			                action: 'wpr_activate_reuired_theme',
			            }
			        );

			        WprTemplatesKit.requiredTheme = true;
				}
			});
		},

		installRequiredPlugins: function( kitID ) {
			WprTemplatesKit.installRequiredTheme();

			var kit = $('.grid-item[data-kit-id="'+ kitID +'"]');
				WprTemplatesKit.requiredPlugins = kit.data('plugins') !== undefined ? kit.data('plugins') : false;
			
			// Install Plugins
			if ( WprTemplatesKit.requiredPlugins ) {
				if ( 'contact-form-7' in WprTemplatesKit.requiredPlugins && false === WprTemplatesKit.requiredPlugins['contact-form-7'] ) {
					WprTemplatesKit.installPluginViaAjax('contact-form-7');
				}
				
				if ( 'media-library-assistant' in WprTemplatesKit.requiredPlugins && false === WprTemplatesKit.requiredPlugins['media-library-assistant'] ) {
					WprTemplatesKit.installPluginViaAjax('media-library-assistant');
				}
			}
		},

		installPluginViaAjax: function( slug ) {
            wp.updates.installPlugin({
                slug: slug,
                success: function() {
			        $.post(
			            ajaxurl,
			            {
			                action: 'wpr_install_reuired_plugins',
			                plugin: slug,
			            }
			        );
			        WprTemplatesKit.requiredPlugins[slug] = true;
                },
                error: function( xhr, ajaxOptions, thrownerror ) {
                    console.log(xhr.errorCode)
                    if ( 'folder_exists' === xhr.errorCode ) {
				        $.post(
				            ajaxurl,
				            {
				                action: 'wpr_install_reuired_plugins',
				                plugin: slug,
				            }
				        );
				        WprTemplatesKit.requiredPlugins[slug] = true;
                    }
                },
            });
		},

		importTemplatesKit: function( kitID ) {
			console.log('Installing Plugins...');
			WprTemplatesKit.importProgressBar('plugins');
			WprTemplatesKit.installRequiredPlugins( kitID );

	        var installPlugins = setInterval(function() {

	        	if ( Object.values(WprTemplatesKit.requiredPlugins).every(Boolean) && WprTemplatesKit.requiredTheme ) {
					console.log('Importing Kit: '+ kitID +'...');
					WprTemplatesKit.importProgressBar('content');

					// Import Kit
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'wpr_import_templates_kit',
							wpr_templates_kit: kitID,
							wpr_templates_kit_single: false
						},
						success: function( response ) {
							console.log('Setting up Final Settings...');
							WprTemplatesKit.importProgressBar('settings');

							// Final Adjustments
							$.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {
									action: 'wpr_final_settings_setup'
								},
								success: function( response ) {
									setTimeout(function(){
										console.log('Import Finished!');
										WprTemplatesKit.importProgressBar('finish');
									}, 1000 );
								}
							});
						}
					});

	        		// Clear
	        		clearInterval( installPlugins );
	        	}
	        }, 1000);
		},

		importSingleTemplate: function( kitID, templateID ) {

			// Import Kit
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wpr_import_templates_kit',
					wpr_templates_kit: kitID,
					wpr_templates_kit_single: templateID
				},
				success: function( response ) {
					console.log(response)
				}
			});
		},

		importProgressBar: function( step ) {
			if ( 'plugins' === step ) {
				$('.wpr-import-kit-popup .progress-wrap strong').html('Step 1: Installing/Activating Plugins<span class="dot-flashing"></span>');
			} else if ( 'content' === step ) {
				$('.wpr-import-kit-popup .progress-bar').animate({'width' : '33%'}, 500);
				$('.wpr-import-kit-popup .progress-wrap strong').html('Step 2: Importing Demo Content<span class="dot-flashing"></span>');
			} else if ( 'settings' === step ) {
				$('.wpr-import-kit-popup .progress-bar').animate({'width' : '66%'}, 500);
				$('.wpr-import-kit-popup .progress-wrap strong').html('Step 3: Importing Settings<span class="dot-flashing"></span>');
			} else if ( 'finish' === step ) {
				var href = window.location.href,
					index = href.indexOf('/wp-admin'),
					homeUrl = href.substring(0, index);

				$('.wpr-import-kit-popup .progress-bar').animate({'width' : '100%'}, 500);
				$('.wpr-import-kit-popup .progress-wrap strong').html('Step 4: Import Finished - <a href="'+ homeUrl +'" target="_blank">Visit Site</a>');
				$('.wpr-import-kit-popup header h3').text('Import was Successfull!');
				$('.wpr-import-kit-popup-wrap .close-btn').show();
			}
		},

		showTemplatesMainGrid: function() {
			$(this).hide();
			$('.wpr-templates-kit-single').hide();
			$('.wpr-templates-kit-page-title').show();
			$('.wpr-templates-kit-grid.main-grid').show();
			$('.wpr-templates-kit-search').show();
			$('.wpr-templates-kit-price-filter').show();
			// $('.wpr-templates-kit-filters').show();
			$('.wpr-templates-kit-logo').find('.back-btn').css('display', 'none');
		},

		showImportPage: function( kit ) {
			$('.wpr-templates-kit-page-title').hide();
			$('.wpr-templates-kit-grid.main-grid').hide();
			$('.wpr-templates-kit-search').hide();
			$('.wpr-templates-kit-price-filter').hide();
			// $('.wpr-templates-kit-filters').hide();
			$('.wpr-templates-kit-single .action-buttons-wrap').css('margin-left', $('#adminmenuwrap').outerWidth());
			$('.wpr-templates-kit-single').show();
			$('.wpr-templates-kit-logo').find('.back-btn').css('display', 'flex');
			$('.wpr-templates-kit-single .preview-demo').attr('href', 'https://demosites.royal-elementor-addons.com/'+ kit.data('kit-id') +'?ref=rea-plugin-backend-templates');
		},

		renderImportPage: function( kit ) {
			var kitID = kit.data('kit-id'),
				pagesAttr = kit.data('pages') !== undefined ? kit.data('pages') : false,
				pagesArray = pagesAttr ? pagesAttr.split(',') : false,
				singleGrid = $('.wpr-templates-kit-grid.single-grid');

			// Reset
			singleGrid.html('');

			// Render
			if ( pagesArray ) {
				for (var i = 0; i < pagesArray.length - 1; i++ ) {
					singleGrid.append('\
				        <div class="grid-item" data-page-id="'+ pagesArray[i] +'">\
				        	<a href="https://demosites.royal-elementor-addons.com/'+ kit.data('kit-id') +'?ref=rea-plugin-backend-templates" target="_blank">\
				            <div class="image-wrap">\
				                <img src="https://royal-elementor-addons.com/library/templates-kit/'+ kitID +'/'+ pagesArray[i] +'.jpg">\
				            </div>\
				            <footer><h3>'+ pagesArray[i] +'</h3></footer>\
				            </a>\
				        </div>\
					');
				};
			} else {
				// just one page
			}

			if ( $('.wpr-templates-kit-grid').find('.grid-item[data-kit-id="'+ kit.data('kit-id') +'"]').data('price') === 'pro' ) {
				$('.wpr-templates-kit-single').find('.import-kit').hide();
				$('.wpr-templates-kit-single').find('.get-access').show();
			} else {
				$('.wpr-templates-kit-single').find('.get-access').hide();
				$('.wpr-templates-kit-single').find('.import-kit').show();

				// Set Kit ID
				$('.wpr-templates-kit-single').find('.import-kit').attr('data-kit-id', kit.data('kit-id'));
			}

			// Set Active Template ID by Default // TODO: Disable Single Template import for now
			// WprTemplatesKit.setActiveTemplateID(singleGrid.children().first());

			// singleGrid.find('.grid-item').on('click', function(){
			// 	WprTemplatesKit.setActiveTemplateID( $(this) );
			// });
		},

		setActiveTemplateID: function( template ) {
			// Reset
			$('.wpr-templates-kit-grid.single-grid').find('.grid-item').removeClass('selected-template');
			
			// Set ID
			template.addClass('selected-template');
			var id = $('.wpr-templates-kit-grid.single-grid').find('.selected-template').data('page-id');

			$('.wpr-templates-kit-single').find('.import-template').attr('data-template-id', id);
			$('.wpr-templates-kit-single').find('.import-template strong').text(id);

			// Set Preview Link
			$('.wpr-templates-kit-single').find('.preview-demo').attr('href', $('.wpr-templates-kit-single').find('.preview-demo').attr('href') +'/'+ id );
		},

		searchTemplatesKit: function( tag ) {
			var price = $('.wpr-templates-kit-price-filter').children().first().attr( 'data-price' ),
				priceAttr = 'mixed' === price ? '' : '[data-price*="'+ price +'"]';

			if ( '' !== tag ) {
				$('.main-grid .grid-item').hide();
				$('.main-grid .grid-item[data-tags*="'+ tag +'"]'+ priceAttr).show();
			} else {
				$('.main-grid .grid-item'+ priceAttr).show();
			}

			if ( ! $('.main-grid .grid-item').is(':visible') ) {
				$('.wpr-templates-kit-page-title').hide();
				$('.wpr-templates-kit-not-found').css('display', 'flex');
			} else {
				$('.wpr-templates-kit-not-found').hide();
				$('.wpr-templates-kit-page-title').show();
			}
		},

		fiterFreeProTemplates: function( price ) {
			var tag = $('.wpr-templates-kit-search').find('input').val(),
				tagAttr = '' === tag ? '' : '[data-tags*="'+ tag +'"]';

			if ( 'free' == price ) {
				$('.main-grid .grid-item').hide();
				$('.main-grid .grid-item[data-price*="'+ price +'"]'+ tagAttr).show();
			} else if ( 'pro' == price ) {
				$('.main-grid .grid-item').hide();
				$('.main-grid .grid-item[data-price*="'+ price +'"]'+ tagAttr).show();
			} else {
				$('.main-grid .grid-item'+ tagAttr).show();
			}
		},

	}

	WprTemplatesKit.init();

}); // end dom ready