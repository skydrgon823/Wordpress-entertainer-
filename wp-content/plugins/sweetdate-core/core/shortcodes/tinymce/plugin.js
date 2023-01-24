(function ()
{

	if ( parseInt(window.tinyMCE.majorVersion) > 3) {
		tinymce.PluginManager.add('kleoShortcodes', function(editor, url) {

			editor.addButton( 'kleo_button', function() {

				return {
					title: 'Visual Shortcodes',
					'text': 'SHORTCODES',
					type: 'menubutton',
					menu: [
							{
									text: 'Layouts',
									value: '',
									menu: [
											{
													text: 'Row',
													value: 'row',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Columns',
													value: 'columns',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Section',
													value: 'section',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											}

									]
							},
							{
									text: 'Content elements',
									value: '',
									menu: [
											{
													text: 'Buttons',
													value: 'button',
													onclick: function() {
														tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}
											},
											{
													text: 'Icon',
													value: 'icon',
													onclick: function() {
														tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}
											},
											{
													text: 'Tabs',
													value: 'tabs',
													onclick: function() {
														tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}
											},
											{
													text: 'Accordion',
													value: 'accordion',
													onclick: function() {
														tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}
											},
											{
													text: 'Toggle',
													value: 'toggle',
													onclick: function() {
														tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}
											},
											{
													text: 'Posts Carousel',
													value: 'posts_carousel',
													onclick: function() {
														tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}
											},
                                            {
                                                text: 'Latest Posts',
                                                value: 'articles',
                                                onclick: function() {
                                                    tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
                                                }
                                            },
											{
													text: 'Alerts',
													value: 'alert',
													onclick: function() {
														tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}
											},
											{
													text: 'Colored text',
													value: 'colored_text',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Lead Paragraph',
													value: 'lead_paragraph',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Panel',
													value: 'panel',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Progress bar',
													value: 'progress_bar',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Pricing table',
													value: 'pricing_table',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
									]
							},
							{
									text: 'Media elements',
									value: '',
									menu: [
											{
													text: 'Image slider',
													value: 'slider',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Video Button',
													value: 'button_video',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Rounded image',
													value: 'img_rounded',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											}

									]
							},
							{
									text: 'Headings',
									value: '',
									menu: [
											{
													text: 'H1',
													value: 'h1',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'H2',
													value: 'h2',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'H3',
													value: 'h3',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'H4',
													value: 'h4',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'H5',
													value: 'h5',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'H6',
													value: 'h6',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											}

									]
							},
							{
									text: 'Misc',
									value: '',
									menu: [

											{
													text: 'Only members content',
													value: '[kleo_only_members]Content to show for members only[/kleo_only_members]',
													onclick: function(e) {
															e.stopPropagation();
															editor.insertContent(this.value());
													}       
											},
											{
													text: 'Only guests content',
													value: '[kleo_only_guests]Content to show for guests only[/kleo_only_guests]',
													onclick: function(e) {
															e.stopPropagation();
															editor.insertContent(this.value());
													}       
											},
											{
													text: 'bbPress statistics',
													value: 'bbpress_stats',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											}	

									]
							},
							{
									text: 'Homepage',
									value: '',
									menu: [
											{
													text: 'Call to action box',
													value: 'call_to_action',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Status icon',
													value: 'status_icon',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											}

									]
							},
							{
									text: 'Buddypress',
									value: '',
									menu: [
											{
													text: 'Top Members',
													value: '[kleo_top_members]',
													onclick: function(e) {
															e.stopPropagation();
															editor.insertContent(this.value());
													}       
											},
											{
													text: 'Members Carousel',
													value: 'members_carousel',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Recent Groups',
													value: '[kleo_recent_groups]',
													onclick: function(e) {
															e.stopPropagation();
															editor.insertContent(this.value());
													}       
											},
											{
													text: 'Search form',
													value: 'search_members',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Register form',
													value: 'register_form',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Horizontal Search form',
													value: 'search_members_horizontal',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Total members numbers',
													value: '[kleo_total_members]',
													onclick: function(e) {
															e.stopPropagation();
															editor.insertContent(this.value());
													}       
											},
											{
													text: 'Online members number',
													value: 'members_online',
													onclick: function(e) {
															e.stopPropagation();
															tb_show("Insert Shortcode", url + "/popup.php?popup=" + this.value() + "&width=" + 800);
													}       
											},
											{
													text: 'Members Statistics by field and value',
													value: '[kleo_member_stats field="" value="" online="no"]',
													onclick: function(e) {
															e.stopPropagation();
															editor.insertContent(this.value());
													}       
											},
											{
													text: 'Members List',
													value: '[kleo_members]',
													onclick: function(e) {
															e.stopPropagation();
															editor.insertContent(this.value());
													}       
											}
									]
							}

					]
				}
			});

		});

	}
	else {

		// create kleoShortcodes plugin
		tinymce.create("tinymce.plugins.kleoShortcodes",
		{
			init: function ( ed, url )
			{
				ed.addCommand("kleoPopup", function ( a, params )
				{
					var popup = params.identifier;

					// load thickbox
					tb_show("Insert Shortcode", url + "/popup.php?popup=" + popup + "&width=" + 800);
				});
			},
			createControl: function ( btn, e )
			{
				if ( btn == "kleo_button" )
				{	
					var a = this;

					var btn = e.createSplitButton('kleo_button', {
						title: "Insert Shortcode",
						image: KleoShortcodes.plugin_folder +"/tinymce/images/icon.jpg",
						icons: false
					});

									btn.onRenderMenu.add(function (c, b)
					{	
											a.addWithPopup( b, "Buttons", "button" );
											a.addWithPopup( b, "Alerts", "alert" );
											a.addWithPopup( b, "Tabs", "tabs" );
											a.addWithPopup( b, "Accordion", "accordion" );

											a.addWithPopup(b, "Posts Carousel", "posts_carousel" );
                                            a.addWithPopup(b, "Latest Posts", "articles" );
											a.addWithPopup(b, "Icon", "icon" );
											b.addSeparator();
											c=b.addMenu({title: "Media elements"});
													a.addWithPopup(c, "Image slider", "slider" );
													a.addWithPopup(c, "Video button", "button_video" );
													a.addWithPopup(c, "Rounded image", "img_rounded" );


											c=b.addMenu({title: "Headings"});
													a.addWithPopup(c, "H1", "h1" );
													a.addWithPopup(c, "H2", "h2" );
													a.addWithPopup(c, "H3", "h3" );
													a.addWithPopup(c, "H4", "h4" );
													a.addWithPopup(c, "H5", "h5" );
													a.addWithPopup(c, "H6", "h6" );

											c=b.addMenu({title: "Misc"});
													a.addWithPopup(c, "Colored text", "colored_text" );
													a.addWithPopup(c, "Lead Paragraph", "lead_paragraph" );
													a.addWithPopup(c, "Panel", "panel" );
													a.addWithPopup(c, "Progress bar", "progress_bar" );
													a.addWithPopup(c, "Pricing table", "pricing_table" );
													a.addImmediate(c, "Only members content", "[kleo_only_members]Content to show for members only[/kleo_only_members]" );
													a.addImmediate(c, "Only guests content", "[kleo_only_guests]Content to show for guests only[/kleo_only_guests]" );

											c=b.addMenu({title: "Layouts"});
													a.addWithPopup(c, "Row", "row" );
													a.addWithPopup(c, "Columns", "columns" );
													a.addWithPopup(c, "Section", "section" );

											b.addSeparator();
											c=b.addMenu({title: "Homepage"});
													a.addWithPopup(c, "Call to action box", "call_to_action" );
													a.addWithPopup(c, "Status icon", "status_icon" );

											b.addSeparator();
											c=b.addMenu({title: "Buddypress"});
													a.addImmediate(c, "Top Members", "[kleo_top_members]" );
							                        a.addWithPopup(c, "Members Carousel", "members_carousel" );
													a.addImmediate(c, "Recent Groups", "[kleo_recent_groups]" );
													a.addWithPopup(c, "Search form", "search_members" );
							                        a.addWithPopup(c, "Register form", "register_form" );
													a.addWithPopup(c, "Horizontal Search form", "search_members_horizontal" );
													a.addImmediate(c, "Total members number", "[kleo_total_members]" );
													a.addWithPopup(c, "Online members number", "members_online" );
													a.addImmediate(c, "Members Statistics by field and value", '[kleo_member_stats field="" value="" online="no"]' );
							                        a.addImmediate(c, "Members List", "[kleo_members]" );


					});

									return btn;
				}

				return null;
			},
			addWithPopup: function ( ed, title, id ) {
				ed.add({
					title: title,
					onclick: function () {
						tinyMCE.activeEditor.execCommand("kleoPopup", false, {
							title: title,
							identifier: id
						})
					}
				})
			},
			addImmediate: function ( ed, title, sc) {
				ed.add({
					title: title,
					onclick: function () {
						tinyMCE.activeEditor.execCommand( "mceInsertContent", false, sc )
					}
				})
			}
		});

		// add kleoShortcodes plugin
		tinymce.PluginManager.add("kleoShortcodes", tinymce.plugins.kleoShortcodes);

	}

})();