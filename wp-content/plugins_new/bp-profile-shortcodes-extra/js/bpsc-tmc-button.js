jQuery(document).ready(function($) {


	if (typeof(tinymce) != "undefined") {

			tinymce.create('tinymce.plugins.bpsc_button', {
				init : function(editor, url) {
						var menuItem = [];
						var ds_img = bpsc_plugin_url +'/images/bppsc.png';
						$.each( bpsc_shortcodes, function( i, val ){
							var tempObj = {
									text : val.title,
									onclick: function() {
										editor.insertContent(val.content)
									}
								};
								
							menuItem.push( tempObj );
						} );
						// Register buttons - trigger above command when clicked
						editor.addButton('bpsc_button', {
							title : 'BP Profile Shortcodes Extra', 
							classes : 'bpsc-ss',
							type  : 'menubutton',
							menu  : menuItem,
							style : ' background-size : 22px; background-repeat : no-repeat; background-image: url( '+ ds_img +' );'
						});
				},   
			});

	}
    // Register our TinyMCE plugin

	if (typeof(tinymce) != "undefined") { 
		tinymce.PluginManager.add('bpsc_button', tinymce.plugins.bpsc_button);
	}
});