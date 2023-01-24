'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

jQuery(function () {
	return MPSUM.init();
});

var $ = jQuery;
var MPSUM = {

	// Used with page number input event
	timer: null,
	delay: 500,

	/**
  * Register event handling
  */
	init: function init() {
		var _this = this;

		this.__register_update_toggle_events();
		this.__register_save_settings_event();
		this.__register_bulk_actions_event();
		this.__register_pagination_events();
		this.__register_filter_link_events();
		this.__register_logs_filter_event();

		this.__register_excluded_user_event();
		this.__register_force_updates_event();
		this.__register_enable_logs_event();
		this.__register_clear_logs_event();
		this.__register_reset_options_event();
		this.__register_advanced_tab_menu_events();
		this.__register_log_search_event();
		this.__register_multisite_get_plugins_list();
		this.__register_multisite_get_themes_list();
		this.__register_disable_admin_bar_options_event();
		this.__register_enable_admin_bar_options_event();
		this.__register_logs_expand_event();

		window.onpopstate = function (e) {
			_this.__register_advanced_on_pop_state(e);
		};

		// Select option if it exists
		this.__register_advanced_on_pop_state({});

		// Select option if it exists
		this.__register_on_resize();
	},


	/**
  * Moves section when on resize
  */
	__register_on_resize: function __register_on_resize() {
		MPSUM.__advanced_section_move();
		$(window).resize(function () {
			MPSUM.__advanced_section_move();
		});
	},


	/**
  * Moves section when on resize
  */
	__advanced_section_move: function __advanced_section_move() {
		if ($(window).width() <= 768) {
			$('.eum-advanced-settings-container:visible').insertAfter('div.active-menu').addClass('active');
		} else {
			$('.eum-advanced-settings-container:visible').appendTo('#eum-adv-settings').addClass('active');
		}
	},


	/**
  * Select option when user refreshes Advanced option or when forward or back button is used
  *
  * @param {object} e - Event object. Can be empty.
  */
	__register_advanced_on_pop_state: function __register_advanced_on_pop_state(e) {

		// Parse URL into an object with query params
		var url = wpAjax.unserialize(window.location.href);

		// Slideup any error messages
		if ('action' in url) {
			$('.eum-updated').delay(5000).slideUp(2000);
		}

		// If option in query var, click it
		if ('option' in url) {
			$('*[data-menu_name="' + url.option + '"]').click();
		} else {
			// We are on the main tab with no options - click it if not active
			var $first_item = $($('*[data-menu_name]')[0]);
			$first_item.trigger('click');
		}
	},


	/**
  * Renders the ajax response
  *
  * @param {array} response - Ajax call result as an array
  */
	render: function render(response) {
		this.__render_filter_links(response.views);
		this.__render_rows(response.rows);
		this.__render_headers(response.headers);
		this.__render_pagination_top(response.pagination.top);
		this.__render_pagination_bottom(response.pagination.bottom);
		this.__unblockUI();
		// Init back our event handlers
		this.init();
	},


	/**
  * Send an action over AJAX. A wrapper around jQuery.ajax.
  *
  * @param {string}   action   - the action to send
  * @param {*}        data     - data to send
  * @param {Function} callback - will be called with the results
  * @param {object}   options  - further options. Relevant properties include:
  * - [json_parse=true] - whether to JSON parse the results
  * - [alert_on_error=true] - whether to show an alert box if there was a problem (otherwise, suppress it)
  * - [action='eum_ajax'] - what to send as the action parameter on the AJAX request (N.B. action parameter to this function goes as the 'sub_action' parameter on the AJAX request)
  * - [nonce=eum_ajax_nonce] - the nonce value to send.
  * - [nonce_key='eum_nonce'] - the key value for the nonce field. Currently ignored on the server side.
  * - [timeout=null] - set a timeout after this number of seconds (or if null, none is set)
  * - [async=true] - control whether the request is asynchronous (almost always wanted) or blocking (would need to have a specific reason)
  * - [type='POST'] - GET or POST
  */
	send_command: function send_command(action, data, callback, options) {
		var block_ui = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;

		if (block_ui) {
			this.__blockUI({
				css: {
					width: '300px',
					border: 'none',
					'border-radius': '10px',
					left: 'calc(50% - 150px)',
					padding: '20px'
				},
				message: '<div style="margin: 8px; font-size:150%;" class="eum_saving_popup"><img src="' + mpsum.logo + '" height="80" width="80"><br>' + mpsum.working + '</div>'
			});
		}

		var default_options = {
			json_parse: true,
			alert_on_error: true,
			action: 'eum_ajax',
			nonce: mpsum.eum_nonce,
			nonce_key: 'eum_nonce',
			timeout: null,
			async: true,
			type: 'POST'
		};

		if ('undefined' === typeof options) options = {};

		for (var opt in default_options) {
			if (!options.hasOwnProperty(opt)) {
				options[opt] = default_options[opt];
			}
		}

		var ajax_data = {
			action: options.action,
			subaction: action,
			nonce: options.nonce,
			nonce_key: options.nonce_key
		};

		ajax_data[options.nonce_key] = options.nonce;

		if ('object' === (typeof data === 'undefined' ? 'undefined' : _typeof(data))) {
			for (var attrname in data) {
				ajax_data[attrname] = data[attrname];
			}
		} else {
			ajax_data.action_data = data;
		}

		var ajax_opts = {
			type: options.type,
			url: ajaxurl,
			data: ajax_data,
			success: function success(response, status) {
				if (options.json_parse) {
					var resp = void 0;
					try {
						resp = MPSUM.parse_json(response);
					} catch (e) {
						console.log(e);
						console.log(response);
						if (options.alert_on_error) {
							alert(mpsum.unexpected_response + ' ' + response);
						}
						return;
					}
					if ('function' === typeof callback) callback(resp, status, response);
				} else {
					if ('function' === typeof callback) callback(response, status);
				}
			},
			error: function error(response, status, error_code) {
				if ('function' === typeof options.error_callback) {
					options.error_callback(response, status, error_code);
				} else {
					console.log('eum_send_command: error: ' + status + '  (' + error_code + ')');
					console.log(response);
				}
			},

			dataType: 'text',
			async: options.async
		};

		if (null != options.timeout) {
			ajax_opts.timeout = options.timeout;
		}

		jQuery.ajax(ajax_opts);
	},


	/**
  * Parse JSON string, including automatically detecting unwanted extra input and skipping it
  *
  * @param {string} json_mix_str - JSON string which need to parse and convert to object
  *
  * @throws SyntaxError|String (including passing on what JSON.parse may throw) if a parsing error occurs.
  *
  * @returns Mixed parsed JSON object. Will only return if parsing is successful (otherwise, will throw)
  */
	parse_json: function parse_json(json_mix_str) {

		// Just try it - i.e. the 'default' case where things work (which can include extra whitespace/line-feeds, and simple strings, etc.).
		try {
			var result = JSON.parse(json_mix_str);
			return result;
		} catch (e) {
			console.log("EUM: Exception when trying to parse JSON (1) - will attempt to fix/re-parse");
			console.log(json_mix_str);
		}

		var json_start_pos = json_mix_str.indexOf('{');
		var json_last_pos = json_mix_str.lastIndexOf('}');

		// Case where some php notice may be added after or before json string
		if (json_start_pos > -1 && json_last_pos > -1) {
			var json_str = json_mix_str.slice(json_start_pos, json_last_pos + 1);
			try {
				var _parsed = JSON.parse(json_str);
				console.log("EUM: JSON re-parse successful");
				return _parsed;
			} catch (e) {
				console.log('EUM: Exception when trying to parse JSON (2) - will attempt to fix/re-parse based upon bracket counting');

				var cursor = json_start_pos;
				var open_count = 0;
				var last_character = '';
				var inside_string = false;

				// Don't mistake this for a real JSON parser. Its aim is to improve the odds in real-world cases seen, not to arrive at universal perfection.
				while ((open_count > 0 || cursor == json_start_pos) && cursor <= json_last_pos) {

					var current_character = json_mix_str.charAt(cursor);

					if (!inside_string && '{' == current_character) {
						open_count++;
					} else if (!inside_string && '}' == current_character) {
						open_count--;
					} else if ('"' == current_character && '\\' != last_character) {
						inside_string = inside_string ? false : true;
					}

					last_character = current_character;
					cursor++;
				}
				console.log("Started at cursor=" + json_start_pos + ", ended at cursor=" + cursor + " with result following:");
				console.log(json_mix_str.substring(json_start_pos, cursor));

				try {
					var parsed = JSON.parse(json_mix_str.substring(json_start_pos, cursor));
					console.log('EUM: JSON re-parse successful');
					return parsed;
				} catch (e) {
					// Throw it again, so that our function works just like JSON.parse() in its behaviour.
					throw e;
				}
			}
		}

		throw "EUM: could not parse the JSON";
	},


	/**
  * Retrieve the current settings from the DOM
  *
  * @param {string} output_format - the output format; valid values are 'string' or 'object'
  *
  * @returns String|Object
  */
	gather_settings: function gather_settings(output_format) {

		var form_data = '';
		output_format = 'undefined' === typeof output_format ? 'string' : output_format;

		if ('object' === output_format) {
			form_data = $('input, select').serializeJSON({ checkboxUncheckedValue: '0', useIntKeysAsArrayIndex: true });
		} else {
			form_data = $('input, select').serialize();
		}

		return form_data;
	},


	/**
  * Composes data to be sent over ajax request
  *
  * @param   {string} request_type - Type of ajax request
  * @param   {object} elm - Element that triggers ajax request
  * @returns {object}
  * @private
  */
	__get_data: function __get_data() {
		var request_type = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
		var elm = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

		var data = {};
		var $current = $('#current-page-selector');
		var query_string = null;
		if (null !== elm) {
			query_string = elm.search.substring(1);
		}

		switch (request_type) {
			case 'pagination':
				// We need to override this as paged data comes from clicked link
				data.paged = this.__query(query_string, 'paged');
				break;

			case 'pagination_input':
			case 'bulk':
			case 'save_settings':
				// Get paged data from input field
				data.paged = parseInt($('input[name=paged]').val()) || '1';
				break;
		}

		data.m = $('#filter-by-date').val();
		data.status = $('#filter-by-success').val();
		data.action_type = $('#filter-by-action').val();
		data.type = $('#filter-by-type').val();
		data.order = $('#filter-by-order').val();
		data.data = this.gather_settings('string');
		data.is_search = false;
		var url_params = wpAjax.unserialize(window.location.href);
		if (data.is_search) {
			data.tab = $current.data('tab') || url_params.tab;
			data.view = 'search';
		} else if (null !== query_string) {
			data.tab = url_params.tab;
			data.view = this.__query(query_string, 'view') || 'all';
		} else {
			data.tab = $current.data('tab') || url_params.tab;
			data.view = $current.data('view') || 'all';
		}

		return data;
	},


	/**
  * Filter the URL Query to extract variables
  *
  * @param {string} query    - The URL query part containing the variables
  * @param {string} variable - Name of the variable we want to get
  * @see   http://css-tricks.com/snippets/javascript/get-url-variables/
  *
  * @return string|boolean The variable value if available, false else.
  */
	__query: function __query(query, variable) {

		var params = query.split("&");
		for (var i = 0; i < params.length; i++) {
			var pair = params[i].split("=");
			if (variable === pair[0]) return pair[1];
		}
		return false;
	},


	/**
  * Blocks UI during ajax call process
  *
  * @private
  */
	__blockUI: function __blockUI() {
		$.blockUI({
			css: {
				width: '300px',
				border: 'none',
				'border-radius': '10px',
				left: 'calc(50% - 150px)',
				padding: '20px'
			},
			message: '<div style="margin: 8px; font-size:150%;" class="eum_saving_popup"><img src="' + mpsum.logo + '" height="80" width="80"><br>' + mpsum.working + '</div>'
		});
	},


	/**
  * Unblocks UI after completing ajax process
  *
  * @private
  */
	__unblockUI: function __unblockUI() {
		$('.wrap .fade').delay(6000).fadeOut(2000);

		$.unblockUI();
	},


	/**
  * Registers update / automatic update toggle button events
  *
  * @private
  */
	__register_update_toggle_events: function __register_update_toggle_events() {
		var _this2 = this;

		$('.eum-toggle-button').unbind("click");
		$('.eum-toggle-button').on('click', function (e) {
			var url_params = wpAjax.unserialize(window.location.href);
			var data_tab = url_params.tab;
			e.preventDefault();
			if (undefined == data_tab || 'general' == data_tab) {
				return;
			}

			// Add class to toggle-wrapper
			// Check for class and if update, automatic update options should be hidden and set to false
			var $this = $(e.currentTarget);
			var $checkbox = $this.closest('.toggle-wrapper').find('input');

			var $parent = $this.closest('.toggle-wrapper');
			var $automatic = $parent.closest('.eum-' + data_tab + '-name-actions').find('.eum-' + data_tab + '-automatic-wrapper');
			var $tr = $this.closest('tr');

			$this.addClass('eum-active');

			if ($this.hasClass('eum-enabled')) {
				$this.next().removeClass('eum-active');
				$checkbox.prop('value', 'true');
				if ($parent.hasClass('toggle-wrapper-' + data_tab)) {
					$tr.removeClass('inactive').addClass('active');
					$automatic.find('input').prop('value', false);
					$automatic.find('.eum-disabled').addClass('eum-active');
					$automatic.find('.eum-enabled').removeClass('eum-active');
					$automatic.slideDown();
				}
			} else {
				if ($parent.hasClass('toggle-wrapper-' + data_tab)) {
					$tr.removeClass('active').addClass('inactive');
					$automatic.slideUp();
				}
				$this.prev().removeClass('eum-active');
				$checkbox.prop('value', 'false');
				$automatic.find('input').prop('value', false);
			}

			var data = _this2.__get_data('save_settings');
			_this2.send_command('save_' + data_tab + '_update_options_and_render', data, function (response) {
				$('.subsubsub').replaceWith(response.views);
				setTimeout(function () {
					MPSUM.__unblockUI();
					MPSUM.init();
				}, 2000);
			});
		});
	},


	/**
  * Registers Save settings button event
  *
  * @private
  */
	__register_save_settings_event: function __register_save_settings_event() {
		var _this3 = this;

		$('#eum-save-settings').on('click', function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();

			var data = _this3.__get_data('save_settings');
			_this3.send_command('save_' + data.tab + '_update_options_and_render', data, function (response) {
				$('.subsubsub').replaceWith(response.views);
				_this3.__unblockUI();
				_this3.init();
			});
		});
	},


	/**
  * Registers bulk actions Apply button click event
  *
  * @private
  */
	__register_bulk_actions_event: function __register_bulk_actions_event() {
		var _this4 = this;

		$('.wrap').on('click', '#doaction, #doaction2', function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			if (0 === $('input:checked').length) return;
			var data = _this4.__get_data('bulk');
			_this4.send_command('bulk_action_' + data.tab + '_update_options_and_render', data, function (response) {
				_this4.render(response);
				$('#bulk-action-selector-top, #bulk-action-selector-bottom').val("-1");
			});
		});
	},


	/**
  * Registers pagination button events
  *
  * @private
  */
	__register_pagination_events: function __register_pagination_events() {
		var _this5 = this;

		// Pagination buttons
		$('.tablenav-pages a').on('click', function (e) {
			e.preventDefault();
			var data = _this5.__get_data('pagination', e.currentTarget);
			_this5.__get_tab_content_and_render(data);
		});

		// Page number input
		$('input[name=paged]').on('keyup', function (e) {

			// If user hit enter, we don't want to submit the form
			if (13 === e.which) e.preventDefault();

			// Using timer to prevent every keyup sending ajax requests
			window.clearTimeout(_this5.timer);
			_this5.timer = window.setTimeout(function () {
				var data = _this5.__get_data('pagination_input');
				_this5.__get_tab_content_and_render(data);
			}, _this5.delay);
		});
	},


	/**
  * Registers filter link events
  *
  * @private
  */
	__register_filter_link_events: function __register_filter_link_events() {
		var _this6 = this;

		$('.subsubsub a').on('click', function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			$('.subsubsub a').removeClass('current');
			$(e.currentTarget).addClass('current');
			var data = _this6.__get_data('filters', e.currentTarget);
			_this6.__get_tab_content_and_render(data);
		});
	},


	/**
  * Registers Logs Filter button click event
  *
  * @private
  */
	__register_logs_filter_event: function __register_logs_filter_event() {
		var _this7 = this;

		$('#post-query-submit').on('click', function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var data = _this7.__get_data();
			_this7.__get_tab_content_and_render(data);
		});

		$('#filter-by-date, #filter-by-success, #filter-by-action, #filter-by-type, #filter-by-order').on('change', function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var data = _this7.__get_data();
			_this7.__get_tab_content_and_render(data);
		});
	},


	/**
  * Register save excludes users event
  *
  * @private
  */
	__register_excluded_user_event: function __register_excluded_user_event() {
		var _this8 = this;

		$('#save-excluded-users').on('click', function (e) {
			e.preventDefault();
			_this8.send_command('save_excluded_users', { data: _this8.gather_settings('string') }, function (response) {
				$('#result').css('display', 'block').html('<div class="updated"><p>' + response + '</p></div>').delay(2000).slideUp(2000);
				_this8.__unblockUI();
			});
		});
	},


	/**
  * Register force updates event
  *
  * @private
  */
	__register_force_updates_event: function __register_force_updates_event() {
		var _this9 = this;

		$('#force-updates').on('click', function (e) {
			e.preventDefault();
			if (typeof updraft_backupnow_inpage_go === 'function' && 1 === $('#backup_force_updates:checked').length) {
				updraft_backupnow_inpage_go(function () {
					$('#updraft-backupnow-inpage-modal').dialog('close');
					_this9.__send_force_updates_request();
				}, 'themes,plugins,wpcore', 'autobackup', null);
			} else {
				_this9.__send_force_updates_request();
			}
		});
	},


	/**
  * Sends force updates ajax request
  *
  * @private
  */
	__send_force_updates_request: function __send_force_updates_request() {
		var _this10 = this;

		var update_version = $('#footer-upgrade strong a').html();
		this.send_command('force_updates', {}, function (response) {
			$('#result').css('display', 'block').html('<div class="updated"><p>' + response.message + '</p></div>').delay(5000).slideUp(2000);
			_this10.__unblockUI();
			if (true === response.ran_immediately) {
				// With `force_updates` call after update are run
				// we can check whether still some updates are pending and
				// can display links/update nag accordingly
				if (response.update_data.counts.total > 0) {
					_this10.__update_notifications(response.update_data);
				} else {
					_this10.__remove_update_notifications(response.update_data);
				}
				if ('undefined' !== typeof update_version) {
					$('#footer-upgrade strong').html(update_version.substr(4));
				}
				$('#result').css('display', 'block').html('<div class="updated"><p>' + response.message + '</p></div>').delay(5000).slideUp(2000);
			} else {
				$('#result').css('display', 'block').html('<div class="updated"><p>' + response.message + '</p></div>').delay(5000).slideUp(2000);
				setTimeout(function () {
					return location.reload();
				}, 60000);
			}
		});
	},


	/**
  * Removes update notification elements
  */
	__remove_update_notifications: function __remove_update_notifications(data) {
		$('#wp-admin-bar-updates').remove();
		$('.update-nag').remove();
		$('.update-plugins').remove();
	},


	/**
  * Update notification elements
  */
	__update_notifications: function __update_notifications(data) {
		$('.update-nag').remove();
		$('#wp-admin-bar-updates').html(data.admin_bar_link);
		$('#menu-dashboard ul li:nth-child(3) a').html(data.updates_link);
		$('#menu-appearance a div:nth-child(3)').html(data.themes_link);
		$('#menu-plugins a div:nth-child(3)').html(data.plugins_link);
	},


	/**
  * Registers enable log event
  *
  * @private
  */
	__register_enable_logs_event: function __register_enable_logs_event() {
		var _this11 = this;

		$('#enable-logs').on('click', function (e) {
			e.preventDefault();
			_this11.send_command('enable_logs', {}, function (response) {
				$('#result').css('display', 'block').html('<div class="updated"><p>' + response + '</p></div>').delay(5000).slideUp(2000);
				_this11.__unblockUI();
			});
		});
	},


	/**
  * Registers clear log event
  *
  * @private
  */
	__register_clear_logs_event: function __register_clear_logs_event() {
		var _this12 = this;

		$('#clear-logs').on('click', function (e) {
			e.preventDefault();
			_this12.send_command('clear_logs', {}, function (response) {
				$('#result').css('display', 'block').html('<div class="updated"><p>' + response + '</p></div>').delay(5000).slideUp(2000);
				var column_count = $('.wp-list-table thead th').length;
				$('#the-list').html('<tr class="no-items"><td class="colspanchange" colspan="' + column_count + '">' + mpsum.logs_no_items + '</td></tr>');
				$('.tablenav-pages').remove();
				_this12.__unblockUI();
			});
		});
	},


	/**
  * Registers reset options event
  *
  * @private
  */
	__register_reset_options_event: function __register_reset_options_event() {
		var _this13 = this;

		$('#reset-options').on('click', function (e) {
			e.preventDefault();
			_this13.send_command('reset_options', {}, function (response) {
				var url = window.location.href;
				url += '&action=reset&option=reset-options';
				window.location.href = url;
			});
		});
	},


	/**
  * Register Disable Admin Bar
  *
  * @private
  */
	__register_disable_admin_bar_options_event: function __register_disable_admin_bar_options_event() {
		var _this14 = this;

		$('#adminbar').on('click', '#disable-admin-bar', function (e) {
			e.preventDefault();
			_this14.send_command('disable_admin_bar', {}, function (response) {
				$('#result').css('display', 'block').html('<div class="updated"><p>' + response.message + '</p></div>').delay(5000).slideUp(2000);
				$('#disable-admin-bar').val(mpsum.enable_admin_bar);
				$('#disable-admin-bar').attr('id', 'enable-admin-bar');
				_this14.__unblockUI();
				window.location.href = window.location.href;
			});
		});
	},


	/**
  * Register Enable Admin Bar
  *
  * @private
  */
	__register_enable_admin_bar_options_event: function __register_enable_admin_bar_options_event() {
		var _this15 = this;

		$('#adminbar').on('click', '#enable-admin-bar', function (e) {
			e.preventDefault();
			_this15.send_command('enable_admin_bar', {}, function (response) {
				$('#result').css('display', 'block').html('<div class="updated"><p>' + response.message + '</p></div>').delay(5000).slideUp(2000);
				$('#enable-admin-bar').val(mpsum.disable_admin_bar);
				$('#enable-admin-bar').attr('id', 'disable-admin-bar');
				_this15.__unblockUI();
				window.location.href = window.location.href;
			});
		});
	},


	/**
  * Renders filter links
  *
  * @param   {string} views - Filter links
  * @private
  */
	__render_filter_links: function __render_filter_links(views) {
		if ('undefined' !== views && views.length) {
			$('.subsubsub').replaceWith(views);
		}
	},


	/**
  * Renders plugin / theme rows
  *
  * @param   {string} rows - Rows of plugins / themes
  * @private
  */
	__render_rows: function __render_rows(rows) {
		if ('undefined' !== rows && rows.length) $('#the-list').html(rows);
	},


	/**
  * Renders table headers
  *
  * @param   {string} headers - Table headers
  * @private
  */
	__render_headers: function __render_headers(headers) {
		if ('undefined' !== headers && headers.length) $('thead tr, tfoot tr').html(headers);
	},


	/**
  * Renders top pagination section
  *
  * @param   {string} top - Top pagination section
  * @private
  */
	__render_pagination_top: function __render_pagination_top(top) {
		if ('undefined' !== top && 0 < top.length) {
			$('.tablenav.top .tablenav-pages').html($(top).html());
		} else {
			$('.tablenav.top .tablenav-pages').html('');
		}
	},


	/**
  * Renders bottom pagination section
  *
  * @param   {string} bottom - Bottom pagination section
  * @private
  */
	__render_pagination_bottom: function __render_pagination_bottom(bottom) {
		if ('undefined' !== bottom && 0 < bottom.length) {
			$('.tablenav.bottom .tablenav-pages').html($(bottom).html());
		} else {
			$('.tablenav.bottom .tablenav-pages').html('');
		}
	},


	/**
  * Renders whole tab
  *
  * @private
  */
	__get_tab_content_and_render: function __get_tab_content_and_render(data) {
		var _this16 = this;

		this.send_command('update_' + data.tab + '_tab', data, function (response) {
			_this16.render(response);
		});
	},


	/**
  * Register advanced tab menu event
  */
	__register_advanced_tab_menu_events: function __register_advanced_tab_menu_events() {
		$('#eum-adv-settings-menu div > div').on('click', function (e) {
			var tool = e.currentTarget.dataset.menu_name;
			$('.eum-advanced-settings-container:not(' + tool + ')').hide();
			$('#eum-adv-settings-menu div > div').removeClass('active-menu');
			$('.eum-advanced-settings-container').removeClass('active');
			$(e.currentTarget).addClass('active-menu');
			$('.eum-advanced-settings-container.' + tool).css('display', 'block').addClass('active');
			MPSUM.__advanced_section_move();

			// Modify URL for advanced tab states
			var url_params = wpAjax.unserialize(window.location.href);
			var url = '' + location.protocol + '//' + ('' + location.host + location.pathname);
			var $first_option = $($('*[data-menu_name]')[0]);
			var option = tool;
			url += '?page=' + url_params.page + '&tab=' + url_params.tab;
			if (option != $first_option.data('menu_name')) {
				url += '&option=' + option;
			}
			if ('action' in url_params && 'reset-options' == option) {
				url += '&action=' + url_params.action;
			}

			// Do not push state if URL is the same
			if (window.location.href != url) {
				history.pushState('', document.title, url);
			}
		});
	},


	/**
  * Register multisite check plugins event
  */
	__register_multisite_get_plugins_list: function __register_multisite_get_plugins_list() {
		var _this17 = this;

		$('.eum-list-plugins-action').on('click', function (e) {
			e.preventDefault();
			var $target = $(e.target);
			var plugin_file = $target.data('plugin-file');
			$target.siblings('.eum-list-plugins').html('<img src="' + mpsum.spinner + '" alt="Loading..." />');
			_this17.send_command('get_multisite_installs_from_plugin', { 'data': { 'plugin_file': plugin_file } }, function (response) {
				$target.siblings('.eum-list-plugins').html(response.message);
			}, {}, false);
		});
	},


	/**
  * Register multisite check themes event
  */
	__register_multisite_get_themes_list: function __register_multisite_get_themes_list() {
		var _this18 = this;

		$('.eum-list-themes-action').on('click', function (e) {
			e.preventDefault();
			var $target = $(e.target);
			var theme_file = $target.data('theme-file');
			$target.siblings('.eum-list-themes').html('<img src="' + mpsum.spinner + '" alt="Loading..." />');
			_this18.send_command('get_multisite_installs_from_theme', { 'data': { 'stylesheet': theme_file } }, function (response) {
				$target.siblings('.eum-list-themes').html('<div class="mpsum-message">' + response.message + '</div>');
			}, {}, false);
		});
	},


	/**
  * Register advanced tab menu event
  */
	__register_log_search_event: function __register_log_search_event() {
		var _this19 = this;

		$('#log-query-search').on('click', function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var data = _this19.__get_data();
			var value = $('#eum-log-search').val().trim();
			if ('' == value) {
				_this19.__get_tab_content_and_render(data);
				return;
			}
			data.is_search = true;
			data.search_term = value;
			data.view = 'search';
			_this19.__get_tab_content_and_render(data);
		});
	},


	/**
  * Registers Save settings button event
  *
  * @private
  */
	__register_logs_expand_event: function __register_logs_expand_event() {
		$('.eum-note-expand').on('click', function (e) {
			e.preventDefault();
			$(e.target).siblings('div').toggle();
		});
	}
};