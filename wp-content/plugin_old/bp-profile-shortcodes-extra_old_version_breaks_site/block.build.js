/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = addQueryArgs;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_url__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_url___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_url__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_querystring__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_querystring___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_querystring__);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

/**
 * External dependencies
 */



/**
 * Appends arguments to the query string of the url
 *
 * @param  {String} url   URL
 * @param  {Object} args  Query Args
 *
 * @return {String}       Updated URL
 */
function addQueryArgs(url, args) {
  var parsedURL = Object(__WEBPACK_IMPORTED_MODULE_0_url__["parse"])(url, true);
  var query = _extends({}, parsedURL.query, args);
  delete parsedURL.search;

  return Object(__WEBPACK_IMPORTED_MODULE_0_url__["format"])(_extends({}, parsedURL, { query: query }));
}

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.decode = exports.parse = __webpack_require__(12);
exports.encode = exports.stringify = __webpack_require__(13);


/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__i18n_js__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__i18n_js___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__i18n_js__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__bp_group_cover_image__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__bp_group_cover_image_link__ = __webpack_require__(16);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__bp_group_header__ = __webpack_require__(21);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__bp_group_members__ = __webpack_require__(26);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__bp_profile_lists__ = __webpack_require__(31);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__bp_profile_cover_image__ = __webpack_require__(36);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__bp_profile_cover_image_link__ = __webpack_require__(41);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__bp_profile_header__ = __webpack_require__(46);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__bp_whats_new__ = __webpack_require__(51);


/**
 * Import example blocks
 */










/***/ }),
/* 3 */
/***/ (function(module, exports) {

wp.i18n.setLocaleData({ '': {} }, 'bp-Profile-Shortcodes-Extra');

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(15);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode1';

var settings = {
	title: __('BP Group Cover Image'),

	description: __('Displays the selected group cover image. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(6);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_group_cover_image]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Group Cover Image')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_group_cover_image]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(14);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
// Copyright Joyent, Inc. and other Node contributors.
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to permit
// persons to whom the Software is furnished to do so, subject to the
// following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
// USE OR OTHER DEALINGS IN THE SOFTWARE.



var punycode = __webpack_require__(8);
var util = __webpack_require__(11);

exports.parse = urlParse;
exports.resolve = urlResolve;
exports.resolveObject = urlResolveObject;
exports.format = urlFormat;

exports.Url = Url;

function Url() {
  this.protocol = null;
  this.slashes = null;
  this.auth = null;
  this.host = null;
  this.port = null;
  this.hostname = null;
  this.hash = null;
  this.search = null;
  this.query = null;
  this.pathname = null;
  this.path = null;
  this.href = null;
}

// Reference: RFC 3986, RFC 1808, RFC 2396

// define these here so at least they only have to be
// compiled once on the first module load.
var protocolPattern = /^([a-z0-9.+-]+:)/i,
    portPattern = /:[0-9]*$/,

    // Special case for a simple path URL
    simplePathPattern = /^(\/\/?(?!\/)[^\?\s]*)(\?[^\s]*)?$/,

    // RFC 2396: characters reserved for delimiting URLs.
    // We actually just auto-escape these.
    delims = ['<', '>', '"', '`', ' ', '\r', '\n', '\t'],

    // RFC 2396: characters not allowed for various reasons.
    unwise = ['{', '}', '|', '\\', '^', '`'].concat(delims),

    // Allowed by RFCs, but cause of XSS attacks.  Always escape these.
    autoEscape = ['\''].concat(unwise),
    // Characters that are never ever allowed in a hostname.
    // Note that any invalid chars are also handled, but these
    // are the ones that are *expected* to be seen, so we fast-path
    // them.
    nonHostChars = ['%', '/', '?', ';', '#'].concat(autoEscape),
    hostEndingChars = ['/', '?', '#'],
    hostnameMaxLen = 255,
    hostnamePartPattern = /^[+a-z0-9A-Z_-]{0,63}$/,
    hostnamePartStart = /^([+a-z0-9A-Z_-]{0,63})(.*)$/,
    // protocols that can allow "unsafe" and "unwise" chars.
    unsafeProtocol = {
      'javascript': true,
      'javascript:': true
    },
    // protocols that never have a hostname.
    hostlessProtocol = {
      'javascript': true,
      'javascript:': true
    },
    // protocols that always contain a // bit.
    slashedProtocol = {
      'http': true,
      'https': true,
      'ftp': true,
      'gopher': true,
      'file': true,
      'http:': true,
      'https:': true,
      'ftp:': true,
      'gopher:': true,
      'file:': true
    },
    querystring = __webpack_require__(1);

function urlParse(url, parseQueryString, slashesDenoteHost) {
  if (url && util.isObject(url) && url instanceof Url) return url;

  var u = new Url;
  u.parse(url, parseQueryString, slashesDenoteHost);
  return u;
}

Url.prototype.parse = function(url, parseQueryString, slashesDenoteHost) {
  if (!util.isString(url)) {
    throw new TypeError("Parameter 'url' must be a string, not " + typeof url);
  }

  // Copy chrome, IE, opera backslash-handling behavior.
  // Back slashes before the query string get converted to forward slashes
  // See: https://code.google.com/p/chromium/issues/detail?id=25916
  var queryIndex = url.indexOf('?'),
      splitter =
          (queryIndex !== -1 && queryIndex < url.indexOf('#')) ? '?' : '#',
      uSplit = url.split(splitter),
      slashRegex = /\\/g;
  uSplit[0] = uSplit[0].replace(slashRegex, '/');
  url = uSplit.join(splitter);

  var rest = url;

  // trim before proceeding.
  // This is to support parse stuff like "  http://foo.com  \n"
  rest = rest.trim();

  if (!slashesDenoteHost && url.split('#').length === 1) {
    // Try fast path regexp
    var simplePath = simplePathPattern.exec(rest);
    if (simplePath) {
      this.path = rest;
      this.href = rest;
      this.pathname = simplePath[1];
      if (simplePath[2]) {
        this.search = simplePath[2];
        if (parseQueryString) {
          this.query = querystring.parse(this.search.substr(1));
        } else {
          this.query = this.search.substr(1);
        }
      } else if (parseQueryString) {
        this.search = '';
        this.query = {};
      }
      return this;
    }
  }

  var proto = protocolPattern.exec(rest);
  if (proto) {
    proto = proto[0];
    var lowerProto = proto.toLowerCase();
    this.protocol = lowerProto;
    rest = rest.substr(proto.length);
  }

  // figure out if it's got a host
  // user@server is *always* interpreted as a hostname, and url
  // resolution will treat //foo/bar as host=foo,path=bar because that's
  // how the browser resolves relative URLs.
  if (slashesDenoteHost || proto || rest.match(/^\/\/[^@\/]+@[^@\/]+/)) {
    var slashes = rest.substr(0, 2) === '//';
    if (slashes && !(proto && hostlessProtocol[proto])) {
      rest = rest.substr(2);
      this.slashes = true;
    }
  }

  if (!hostlessProtocol[proto] &&
      (slashes || (proto && !slashedProtocol[proto]))) {

    // there's a hostname.
    // the first instance of /, ?, ;, or # ends the host.
    //
    // If there is an @ in the hostname, then non-host chars *are* allowed
    // to the left of the last @ sign, unless some host-ending character
    // comes *before* the @-sign.
    // URLs are obnoxious.
    //
    // ex:
    // http://a@b@c/ => user:a@b host:c
    // http://a@b?@c => user:a host:c path:/?@c

    // v0.12 TODO(isaacs): This is not quite how Chrome does things.
    // Review our test case against browsers more comprehensively.

    // find the first instance of any hostEndingChars
    var hostEnd = -1;
    for (var i = 0; i < hostEndingChars.length; i++) {
      var hec = rest.indexOf(hostEndingChars[i]);
      if (hec !== -1 && (hostEnd === -1 || hec < hostEnd))
        hostEnd = hec;
    }

    // at this point, either we have an explicit point where the
    // auth portion cannot go past, or the last @ char is the decider.
    var auth, atSign;
    if (hostEnd === -1) {
      // atSign can be anywhere.
      atSign = rest.lastIndexOf('@');
    } else {
      // atSign must be in auth portion.
      // http://a@b/c@d => host:b auth:a path:/c@d
      atSign = rest.lastIndexOf('@', hostEnd);
    }

    // Now we have a portion which is definitely the auth.
    // Pull that off.
    if (atSign !== -1) {
      auth = rest.slice(0, atSign);
      rest = rest.slice(atSign + 1);
      this.auth = decodeURIComponent(auth);
    }

    // the host is the remaining to the left of the first non-host char
    hostEnd = -1;
    for (var i = 0; i < nonHostChars.length; i++) {
      var hec = rest.indexOf(nonHostChars[i]);
      if (hec !== -1 && (hostEnd === -1 || hec < hostEnd))
        hostEnd = hec;
    }
    // if we still have not hit it, then the entire thing is a host.
    if (hostEnd === -1)
      hostEnd = rest.length;

    this.host = rest.slice(0, hostEnd);
    rest = rest.slice(hostEnd);

    // pull out port.
    this.parseHost();

    // we've indicated that there is a hostname,
    // so even if it's empty, it has to be present.
    this.hostname = this.hostname || '';

    // if hostname begins with [ and ends with ]
    // assume that it's an IPv6 address.
    var ipv6Hostname = this.hostname[0] === '[' &&
        this.hostname[this.hostname.length - 1] === ']';

    // validate a little.
    if (!ipv6Hostname) {
      var hostparts = this.hostname.split(/\./);
      for (var i = 0, l = hostparts.length; i < l; i++) {
        var part = hostparts[i];
        if (!part) continue;
        if (!part.match(hostnamePartPattern)) {
          var newpart = '';
          for (var j = 0, k = part.length; j < k; j++) {
            if (part.charCodeAt(j) > 127) {
              // we replace non-ASCII char with a temporary placeholder
              // we need this to make sure size of hostname is not
              // broken by replacing non-ASCII by nothing
              newpart += 'x';
            } else {
              newpart += part[j];
            }
          }
          // we test again with ASCII char only
          if (!newpart.match(hostnamePartPattern)) {
            var validParts = hostparts.slice(0, i);
            var notHost = hostparts.slice(i + 1);
            var bit = part.match(hostnamePartStart);
            if (bit) {
              validParts.push(bit[1]);
              notHost.unshift(bit[2]);
            }
            if (notHost.length) {
              rest = '/' + notHost.join('.') + rest;
            }
            this.hostname = validParts.join('.');
            break;
          }
        }
      }
    }

    if (this.hostname.length > hostnameMaxLen) {
      this.hostname = '';
    } else {
      // hostnames are always lower case.
      this.hostname = this.hostname.toLowerCase();
    }

    if (!ipv6Hostname) {
      // IDNA Support: Returns a punycoded representation of "domain".
      // It only converts parts of the domain name that
      // have non-ASCII characters, i.e. it doesn't matter if
      // you call it with a domain that already is ASCII-only.
      this.hostname = punycode.toASCII(this.hostname);
    }

    var p = this.port ? ':' + this.port : '';
    var h = this.hostname || '';
    this.host = h + p;
    this.href += this.host;

    // strip [ and ] from the hostname
    // the host field still retains them, though
    if (ipv6Hostname) {
      this.hostname = this.hostname.substr(1, this.hostname.length - 2);
      if (rest[0] !== '/') {
        rest = '/' + rest;
      }
    }
  }

  // now rest is set to the post-host stuff.
  // chop off any delim chars.
  if (!unsafeProtocol[lowerProto]) {

    // First, make 100% sure that any "autoEscape" chars get
    // escaped, even if encodeURIComponent doesn't think they
    // need to be.
    for (var i = 0, l = autoEscape.length; i < l; i++) {
      var ae = autoEscape[i];
      if (rest.indexOf(ae) === -1)
        continue;
      var esc = encodeURIComponent(ae);
      if (esc === ae) {
        esc = escape(ae);
      }
      rest = rest.split(ae).join(esc);
    }
  }


  // chop off from the tail first.
  var hash = rest.indexOf('#');
  if (hash !== -1) {
    // got a fragment string.
    this.hash = rest.substr(hash);
    rest = rest.slice(0, hash);
  }
  var qm = rest.indexOf('?');
  if (qm !== -1) {
    this.search = rest.substr(qm);
    this.query = rest.substr(qm + 1);
    if (parseQueryString) {
      this.query = querystring.parse(this.query);
    }
    rest = rest.slice(0, qm);
  } else if (parseQueryString) {
    // no query string, but parseQueryString still requested
    this.search = '';
    this.query = {};
  }
  if (rest) this.pathname = rest;
  if (slashedProtocol[lowerProto] &&
      this.hostname && !this.pathname) {
    this.pathname = '/';
  }

  //to support http.request
  if (this.pathname || this.search) {
    var p = this.pathname || '';
    var s = this.search || '';
    this.path = p + s;
  }

  // finally, reconstruct the href based on what has been validated.
  this.href = this.format();
  return this;
};

// format a parsed object into a url string
function urlFormat(obj) {
  // ensure it's an object, and not a string url.
  // If it's an obj, this is a no-op.
  // this way, you can call url_format() on strings
  // to clean up potentially wonky urls.
  if (util.isString(obj)) obj = urlParse(obj);
  if (!(obj instanceof Url)) return Url.prototype.format.call(obj);
  return obj.format();
}

Url.prototype.format = function() {
  var auth = this.auth || '';
  if (auth) {
    auth = encodeURIComponent(auth);
    auth = auth.replace(/%3A/i, ':');
    auth += '@';
  }

  var protocol = this.protocol || '',
      pathname = this.pathname || '',
      hash = this.hash || '',
      host = false,
      query = '';

  if (this.host) {
    host = auth + this.host;
  } else if (this.hostname) {
    host = auth + (this.hostname.indexOf(':') === -1 ?
        this.hostname :
        '[' + this.hostname + ']');
    if (this.port) {
      host += ':' + this.port;
    }
  }

  if (this.query &&
      util.isObject(this.query) &&
      Object.keys(this.query).length) {
    query = querystring.stringify(this.query);
  }

  var search = this.search || (query && ('?' + query)) || '';

  if (protocol && protocol.substr(-1) !== ':') protocol += ':';

  // only the slashedProtocols get the //.  Not mailto:, xmpp:, etc.
  // unless they had them to begin with.
  if (this.slashes ||
      (!protocol || slashedProtocol[protocol]) && host !== false) {
    host = '//' + (host || '');
    if (pathname && pathname.charAt(0) !== '/') pathname = '/' + pathname;
  } else if (!host) {
    host = '';
  }

  if (hash && hash.charAt(0) !== '#') hash = '#' + hash;
  if (search && search.charAt(0) !== '?') search = '?' + search;

  pathname = pathname.replace(/[?#]/g, function(match) {
    return encodeURIComponent(match);
  });
  search = search.replace('#', '%23');

  return protocol + host + pathname + search + hash;
};

function urlResolve(source, relative) {
  return urlParse(source, false, true).resolve(relative);
}

Url.prototype.resolve = function(relative) {
  return this.resolveObject(urlParse(relative, false, true)).format();
};

function urlResolveObject(source, relative) {
  if (!source) return relative;
  return urlParse(source, false, true).resolveObject(relative);
}

Url.prototype.resolveObject = function(relative) {
  if (util.isString(relative)) {
    var rel = new Url();
    rel.parse(relative, false, true);
    relative = rel;
  }

  var result = new Url();
  var tkeys = Object.keys(this);
  for (var tk = 0; tk < tkeys.length; tk++) {
    var tkey = tkeys[tk];
    result[tkey] = this[tkey];
  }

  // hash is always overridden, no matter what.
  // even href="" will remove it.
  result.hash = relative.hash;

  // if the relative url is empty, then there's nothing left to do here.
  if (relative.href === '') {
    result.href = result.format();
    return result;
  }

  // hrefs like //foo/bar always cut to the protocol.
  if (relative.slashes && !relative.protocol) {
    // take everything except the protocol from relative
    var rkeys = Object.keys(relative);
    for (var rk = 0; rk < rkeys.length; rk++) {
      var rkey = rkeys[rk];
      if (rkey !== 'protocol')
        result[rkey] = relative[rkey];
    }

    //urlParse appends trailing / to urls like http://www.example.com
    if (slashedProtocol[result.protocol] &&
        result.hostname && !result.pathname) {
      result.path = result.pathname = '/';
    }

    result.href = result.format();
    return result;
  }

  if (relative.protocol && relative.protocol !== result.protocol) {
    // if it's a known url protocol, then changing
    // the protocol does weird things
    // first, if it's not file:, then we MUST have a host,
    // and if there was a path
    // to begin with, then we MUST have a path.
    // if it is file:, then the host is dropped,
    // because that's known to be hostless.
    // anything else is assumed to be absolute.
    if (!slashedProtocol[relative.protocol]) {
      var keys = Object.keys(relative);
      for (var v = 0; v < keys.length; v++) {
        var k = keys[v];
        result[k] = relative[k];
      }
      result.href = result.format();
      return result;
    }

    result.protocol = relative.protocol;
    if (!relative.host && !hostlessProtocol[relative.protocol]) {
      var relPath = (relative.pathname || '').split('/');
      while (relPath.length && !(relative.host = relPath.shift()));
      if (!relative.host) relative.host = '';
      if (!relative.hostname) relative.hostname = '';
      if (relPath[0] !== '') relPath.unshift('');
      if (relPath.length < 2) relPath.unshift('');
      result.pathname = relPath.join('/');
    } else {
      result.pathname = relative.pathname;
    }
    result.search = relative.search;
    result.query = relative.query;
    result.host = relative.host || '';
    result.auth = relative.auth;
    result.hostname = relative.hostname || relative.host;
    result.port = relative.port;
    // to support http.request
    if (result.pathname || result.search) {
      var p = result.pathname || '';
      var s = result.search || '';
      result.path = p + s;
    }
    result.slashes = result.slashes || relative.slashes;
    result.href = result.format();
    return result;
  }

  var isSourceAbs = (result.pathname && result.pathname.charAt(0) === '/'),
      isRelAbs = (
          relative.host ||
          relative.pathname && relative.pathname.charAt(0) === '/'
      ),
      mustEndAbs = (isRelAbs || isSourceAbs ||
                    (result.host && relative.pathname)),
      removeAllDots = mustEndAbs,
      srcPath = result.pathname && result.pathname.split('/') || [],
      relPath = relative.pathname && relative.pathname.split('/') || [],
      psychotic = result.protocol && !slashedProtocol[result.protocol];

  // if the url is a non-slashed url, then relative
  // links like ../.. should be able
  // to crawl up to the hostname, as well.  This is strange.
  // result.protocol has already been set by now.
  // Later on, put the first path part into the host field.
  if (psychotic) {
    result.hostname = '';
    result.port = null;
    if (result.host) {
      if (srcPath[0] === '') srcPath[0] = result.host;
      else srcPath.unshift(result.host);
    }
    result.host = '';
    if (relative.protocol) {
      relative.hostname = null;
      relative.port = null;
      if (relative.host) {
        if (relPath[0] === '') relPath[0] = relative.host;
        else relPath.unshift(relative.host);
      }
      relative.host = null;
    }
    mustEndAbs = mustEndAbs && (relPath[0] === '' || srcPath[0] === '');
  }

  if (isRelAbs) {
    // it's absolute.
    result.host = (relative.host || relative.host === '') ?
                  relative.host : result.host;
    result.hostname = (relative.hostname || relative.hostname === '') ?
                      relative.hostname : result.hostname;
    result.search = relative.search;
    result.query = relative.query;
    srcPath = relPath;
    // fall through to the dot-handling below.
  } else if (relPath.length) {
    // it's relative
    // throw away the existing file, and take the new path instead.
    if (!srcPath) srcPath = [];
    srcPath.pop();
    srcPath = srcPath.concat(relPath);
    result.search = relative.search;
    result.query = relative.query;
  } else if (!util.isNullOrUndefined(relative.search)) {
    // just pull out the search.
    // like href='?foo'.
    // Put this after the other two cases because it simplifies the booleans
    if (psychotic) {
      result.hostname = result.host = srcPath.shift();
      //occationaly the auth can get stuck only in host
      //this especially happens in cases like
      //url.resolveObject('mailto:local1@domain1', 'local2@domain2')
      var authInHost = result.host && result.host.indexOf('@') > 0 ?
                       result.host.split('@') : false;
      if (authInHost) {
        result.auth = authInHost.shift();
        result.host = result.hostname = authInHost.shift();
      }
    }
    result.search = relative.search;
    result.query = relative.query;
    //to support http.request
    if (!util.isNull(result.pathname) || !util.isNull(result.search)) {
      result.path = (result.pathname ? result.pathname : '') +
                    (result.search ? result.search : '');
    }
    result.href = result.format();
    return result;
  }

  if (!srcPath.length) {
    // no path at all.  easy.
    // we've already handled the other stuff above.
    result.pathname = null;
    //to support http.request
    if (result.search) {
      result.path = '/' + result.search;
    } else {
      result.path = null;
    }
    result.href = result.format();
    return result;
  }

  // if a url ENDs in . or .., then it must get a trailing slash.
  // however, if it ends in anything else non-slashy,
  // then it must NOT get a trailing slash.
  var last = srcPath.slice(-1)[0];
  var hasTrailingSlash = (
      (result.host || relative.host || srcPath.length > 1) &&
      (last === '.' || last === '..') || last === '');

  // strip single dots, resolve double dots to parent dir
  // if the path tries to go above the root, `up` ends up > 0
  var up = 0;
  for (var i = srcPath.length; i >= 0; i--) {
    last = srcPath[i];
    if (last === '.') {
      srcPath.splice(i, 1);
    } else if (last === '..') {
      srcPath.splice(i, 1);
      up++;
    } else if (up) {
      srcPath.splice(i, 1);
      up--;
    }
  }

  // if the path is allowed to go above the root, restore leading ..s
  if (!mustEndAbs && !removeAllDots) {
    for (; up--; up) {
      srcPath.unshift('..');
    }
  }

  if (mustEndAbs && srcPath[0] !== '' &&
      (!srcPath[0] || srcPath[0].charAt(0) !== '/')) {
    srcPath.unshift('');
  }

  if (hasTrailingSlash && (srcPath.join('/').substr(-1) !== '/')) {
    srcPath.push('');
  }

  var isAbsolute = srcPath[0] === '' ||
      (srcPath[0] && srcPath[0].charAt(0) === '/');

  // put the host back
  if (psychotic) {
    result.hostname = result.host = isAbsolute ? '' :
                                    srcPath.length ? srcPath.shift() : '';
    //occationaly the auth can get stuck only in host
    //this especially happens in cases like
    //url.resolveObject('mailto:local1@domain1', 'local2@domain2')
    var authInHost = result.host && result.host.indexOf('@') > 0 ?
                     result.host.split('@') : false;
    if (authInHost) {
      result.auth = authInHost.shift();
      result.host = result.hostname = authInHost.shift();
    }
  }

  mustEndAbs = mustEndAbs || (result.host && srcPath.length);

  if (mustEndAbs && !isAbsolute) {
    srcPath.unshift('');
  }

  if (!srcPath.length) {
    result.pathname = null;
    result.path = null;
  } else {
    result.pathname = srcPath.join('/');
  }

  //to support request.http
  if (!util.isNull(result.pathname) || !util.isNull(result.search)) {
    result.path = (result.pathname ? result.pathname : '') +
                  (result.search ? result.search : '');
  }
  result.auth = relative.auth || result.auth;
  result.slashes = result.slashes || relative.slashes;
  result.href = result.format();
  return result;
};

Url.prototype.parseHost = function() {
  var host = this.host;
  var port = portPattern.exec(host);
  if (port) {
    port = port[0];
    if (port !== ':') {
      this.port = port.substr(1);
    }
    host = host.substr(0, host.length - port.length);
  }
  if (host) this.hostname = host;
};


/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module, global) {var __WEBPACK_AMD_DEFINE_RESULT__;/*! https://mths.be/punycode v1.4.1 by @mathias */
;(function(root) {

	/** Detect free variables */
	var freeExports = typeof exports == 'object' && exports &&
		!exports.nodeType && exports;
	var freeModule = typeof module == 'object' && module &&
		!module.nodeType && module;
	var freeGlobal = typeof global == 'object' && global;
	if (
		freeGlobal.global === freeGlobal ||
		freeGlobal.window === freeGlobal ||
		freeGlobal.self === freeGlobal
	) {
		root = freeGlobal;
	}

	/**
	 * The `punycode` object.
	 * @name punycode
	 * @type Object
	 */
	var punycode,

	/** Highest positive signed 32-bit float value */
	maxInt = 2147483647, // aka. 0x7FFFFFFF or 2^31-1

	/** Bootstring parameters */
	base = 36,
	tMin = 1,
	tMax = 26,
	skew = 38,
	damp = 700,
	initialBias = 72,
	initialN = 128, // 0x80
	delimiter = '-', // '\x2D'

	/** Regular expressions */
	regexPunycode = /^xn--/,
	regexNonASCII = /[^\x20-\x7E]/, // unprintable ASCII chars + non-ASCII chars
	regexSeparators = /[\x2E\u3002\uFF0E\uFF61]/g, // RFC 3490 separators

	/** Error messages */
	errors = {
		'overflow': 'Overflow: input needs wider integers to process',
		'not-basic': 'Illegal input >= 0x80 (not a basic code point)',
		'invalid-input': 'Invalid input'
	},

	/** Convenience shortcuts */
	baseMinusTMin = base - tMin,
	floor = Math.floor,
	stringFromCharCode = String.fromCharCode,

	/** Temporary variable */
	key;

	/*--------------------------------------------------------------------------*/

	/**
	 * A generic error utility function.
	 * @private
	 * @param {String} type The error type.
	 * @returns {Error} Throws a `RangeError` with the applicable error message.
	 */
	function error(type) {
		throw new RangeError(errors[type]);
	}

	/**
	 * A generic `Array#map` utility function.
	 * @private
	 * @param {Array} array The array to iterate over.
	 * @param {Function} callback The function that gets called for every array
	 * item.
	 * @returns {Array} A new array of values returned by the callback function.
	 */
	function map(array, fn) {
		var length = array.length;
		var result = [];
		while (length--) {
			result[length] = fn(array[length]);
		}
		return result;
	}

	/**
	 * A simple `Array#map`-like wrapper to work with domain name strings or email
	 * addresses.
	 * @private
	 * @param {String} domain The domain name or email address.
	 * @param {Function} callback The function that gets called for every
	 * character.
	 * @returns {Array} A new string of characters returned by the callback
	 * function.
	 */
	function mapDomain(string, fn) {
		var parts = string.split('@');
		var result = '';
		if (parts.length > 1) {
			// In email addresses, only the domain name should be punycoded. Leave
			// the local part (i.e. everything up to `@`) intact.
			result = parts[0] + '@';
			string = parts[1];
		}
		// Avoid `split(regex)` for IE8 compatibility. See #17.
		string = string.replace(regexSeparators, '\x2E');
		var labels = string.split('.');
		var encoded = map(labels, fn).join('.');
		return result + encoded;
	}

	/**
	 * Creates an array containing the numeric code points of each Unicode
	 * character in the string. While JavaScript uses UCS-2 internally,
	 * this function will convert a pair of surrogate halves (each of which
	 * UCS-2 exposes as separate characters) into a single code point,
	 * matching UTF-16.
	 * @see `punycode.ucs2.encode`
	 * @see <https://mathiasbynens.be/notes/javascript-encoding>
	 * @memberOf punycode.ucs2
	 * @name decode
	 * @param {String} string The Unicode input string (UCS-2).
	 * @returns {Array} The new array of code points.
	 */
	function ucs2decode(string) {
		var output = [],
		    counter = 0,
		    length = string.length,
		    value,
		    extra;
		while (counter < length) {
			value = string.charCodeAt(counter++);
			if (value >= 0xD800 && value <= 0xDBFF && counter < length) {
				// high surrogate, and there is a next character
				extra = string.charCodeAt(counter++);
				if ((extra & 0xFC00) == 0xDC00) { // low surrogate
					output.push(((value & 0x3FF) << 10) + (extra & 0x3FF) + 0x10000);
				} else {
					// unmatched surrogate; only append this code unit, in case the next
					// code unit is the high surrogate of a surrogate pair
					output.push(value);
					counter--;
				}
			} else {
				output.push(value);
			}
		}
		return output;
	}

	/**
	 * Creates a string based on an array of numeric code points.
	 * @see `punycode.ucs2.decode`
	 * @memberOf punycode.ucs2
	 * @name encode
	 * @param {Array} codePoints The array of numeric code points.
	 * @returns {String} The new Unicode string (UCS-2).
	 */
	function ucs2encode(array) {
		return map(array, function(value) {
			var output = '';
			if (value > 0xFFFF) {
				value -= 0x10000;
				output += stringFromCharCode(value >>> 10 & 0x3FF | 0xD800);
				value = 0xDC00 | value & 0x3FF;
			}
			output += stringFromCharCode(value);
			return output;
		}).join('');
	}

	/**
	 * Converts a basic code point into a digit/integer.
	 * @see `digitToBasic()`
	 * @private
	 * @param {Number} codePoint The basic numeric code point value.
	 * @returns {Number} The numeric value of a basic code point (for use in
	 * representing integers) in the range `0` to `base - 1`, or `base` if
	 * the code point does not represent a value.
	 */
	function basicToDigit(codePoint) {
		if (codePoint - 48 < 10) {
			return codePoint - 22;
		}
		if (codePoint - 65 < 26) {
			return codePoint - 65;
		}
		if (codePoint - 97 < 26) {
			return codePoint - 97;
		}
		return base;
	}

	/**
	 * Converts a digit/integer into a basic code point.
	 * @see `basicToDigit()`
	 * @private
	 * @param {Number} digit The numeric value of a basic code point.
	 * @returns {Number} The basic code point whose value (when used for
	 * representing integers) is `digit`, which needs to be in the range
	 * `0` to `base - 1`. If `flag` is non-zero, the uppercase form is
	 * used; else, the lowercase form is used. The behavior is undefined
	 * if `flag` is non-zero and `digit` has no uppercase form.
	 */
	function digitToBasic(digit, flag) {
		//  0..25 map to ASCII a..z or A..Z
		// 26..35 map to ASCII 0..9
		return digit + 22 + 75 * (digit < 26) - ((flag != 0) << 5);
	}

	/**
	 * Bias adaptation function as per section 3.4 of RFC 3492.
	 * https://tools.ietf.org/html/rfc3492#section-3.4
	 * @private
	 */
	function adapt(delta, numPoints, firstTime) {
		var k = 0;
		delta = firstTime ? floor(delta / damp) : delta >> 1;
		delta += floor(delta / numPoints);
		for (/* no initialization */; delta > baseMinusTMin * tMax >> 1; k += base) {
			delta = floor(delta / baseMinusTMin);
		}
		return floor(k + (baseMinusTMin + 1) * delta / (delta + skew));
	}

	/**
	 * Converts a Punycode string of ASCII-only symbols to a string of Unicode
	 * symbols.
	 * @memberOf punycode
	 * @param {String} input The Punycode string of ASCII-only symbols.
	 * @returns {String} The resulting string of Unicode symbols.
	 */
	function decode(input) {
		// Don't use UCS-2
		var output = [],
		    inputLength = input.length,
		    out,
		    i = 0,
		    n = initialN,
		    bias = initialBias,
		    basic,
		    j,
		    index,
		    oldi,
		    w,
		    k,
		    digit,
		    t,
		    /** Cached calculation results */
		    baseMinusT;

		// Handle the basic code points: let `basic` be the number of input code
		// points before the last delimiter, or `0` if there is none, then copy
		// the first basic code points to the output.

		basic = input.lastIndexOf(delimiter);
		if (basic < 0) {
			basic = 0;
		}

		for (j = 0; j < basic; ++j) {
			// if it's not a basic code point
			if (input.charCodeAt(j) >= 0x80) {
				error('not-basic');
			}
			output.push(input.charCodeAt(j));
		}

		// Main decoding loop: start just after the last delimiter if any basic code
		// points were copied; start at the beginning otherwise.

		for (index = basic > 0 ? basic + 1 : 0; index < inputLength; /* no final expression */) {

			// `index` is the index of the next character to be consumed.
			// Decode a generalized variable-length integer into `delta`,
			// which gets added to `i`. The overflow checking is easier
			// if we increase `i` as we go, then subtract off its starting
			// value at the end to obtain `delta`.
			for (oldi = i, w = 1, k = base; /* no condition */; k += base) {

				if (index >= inputLength) {
					error('invalid-input');
				}

				digit = basicToDigit(input.charCodeAt(index++));

				if (digit >= base || digit > floor((maxInt - i) / w)) {
					error('overflow');
				}

				i += digit * w;
				t = k <= bias ? tMin : (k >= bias + tMax ? tMax : k - bias);

				if (digit < t) {
					break;
				}

				baseMinusT = base - t;
				if (w > floor(maxInt / baseMinusT)) {
					error('overflow');
				}

				w *= baseMinusT;

			}

			out = output.length + 1;
			bias = adapt(i - oldi, out, oldi == 0);

			// `i` was supposed to wrap around from `out` to `0`,
			// incrementing `n` each time, so we'll fix that now:
			if (floor(i / out) > maxInt - n) {
				error('overflow');
			}

			n += floor(i / out);
			i %= out;

			// Insert `n` at position `i` of the output
			output.splice(i++, 0, n);

		}

		return ucs2encode(output);
	}

	/**
	 * Converts a string of Unicode symbols (e.g. a domain name label) to a
	 * Punycode string of ASCII-only symbols.
	 * @memberOf punycode
	 * @param {String} input The string of Unicode symbols.
	 * @returns {String} The resulting Punycode string of ASCII-only symbols.
	 */
	function encode(input) {
		var n,
		    delta,
		    handledCPCount,
		    basicLength,
		    bias,
		    j,
		    m,
		    q,
		    k,
		    t,
		    currentValue,
		    output = [],
		    /** `inputLength` will hold the number of code points in `input`. */
		    inputLength,
		    /** Cached calculation results */
		    handledCPCountPlusOne,
		    baseMinusT,
		    qMinusT;

		// Convert the input in UCS-2 to Unicode
		input = ucs2decode(input);

		// Cache the length
		inputLength = input.length;

		// Initialize the state
		n = initialN;
		delta = 0;
		bias = initialBias;

		// Handle the basic code points
		for (j = 0; j < inputLength; ++j) {
			currentValue = input[j];
			if (currentValue < 0x80) {
				output.push(stringFromCharCode(currentValue));
			}
		}

		handledCPCount = basicLength = output.length;

		// `handledCPCount` is the number of code points that have been handled;
		// `basicLength` is the number of basic code points.

		// Finish the basic string - if it is not empty - with a delimiter
		if (basicLength) {
			output.push(delimiter);
		}

		// Main encoding loop:
		while (handledCPCount < inputLength) {

			// All non-basic code points < n have been handled already. Find the next
			// larger one:
			for (m = maxInt, j = 0; j < inputLength; ++j) {
				currentValue = input[j];
				if (currentValue >= n && currentValue < m) {
					m = currentValue;
				}
			}

			// Increase `delta` enough to advance the decoder's <n,i> state to <m,0>,
			// but guard against overflow
			handledCPCountPlusOne = handledCPCount + 1;
			if (m - n > floor((maxInt - delta) / handledCPCountPlusOne)) {
				error('overflow');
			}

			delta += (m - n) * handledCPCountPlusOne;
			n = m;

			for (j = 0; j < inputLength; ++j) {
				currentValue = input[j];

				if (currentValue < n && ++delta > maxInt) {
					error('overflow');
				}

				if (currentValue == n) {
					// Represent delta as a generalized variable-length integer
					for (q = delta, k = base; /* no condition */; k += base) {
						t = k <= bias ? tMin : (k >= bias + tMax ? tMax : k - bias);
						if (q < t) {
							break;
						}
						qMinusT = q - t;
						baseMinusT = base - t;
						output.push(
							stringFromCharCode(digitToBasic(t + qMinusT % baseMinusT, 0))
						);
						q = floor(qMinusT / baseMinusT);
					}

					output.push(stringFromCharCode(digitToBasic(q, 0)));
					bias = adapt(delta, handledCPCountPlusOne, handledCPCount == basicLength);
					delta = 0;
					++handledCPCount;
				}
			}

			++delta;
			++n;

		}
		return output.join('');
	}

	/**
	 * Converts a Punycode string representing a domain name or an email address
	 * to Unicode. Only the Punycoded parts of the input will be converted, i.e.
	 * it doesn't matter if you call it on a string that has already been
	 * converted to Unicode.
	 * @memberOf punycode
	 * @param {String} input The Punycoded domain name or email address to
	 * convert to Unicode.
	 * @returns {String} The Unicode representation of the given Punycode
	 * string.
	 */
	function toUnicode(input) {
		return mapDomain(input, function(string) {
			return regexPunycode.test(string)
				? decode(string.slice(4).toLowerCase())
				: string;
		});
	}

	/**
	 * Converts a Unicode string representing a domain name or an email address to
	 * Punycode. Only the non-ASCII parts of the domain name will be converted,
	 * i.e. it doesn't matter if you call it with a domain that's already in
	 * ASCII.
	 * @memberOf punycode
	 * @param {String} input The domain name or email address to convert, as a
	 * Unicode string.
	 * @returns {String} The Punycode representation of the given domain name or
	 * email address.
	 */
	function toASCII(input) {
		return mapDomain(input, function(string) {
			return regexNonASCII.test(string)
				? 'xn--' + encode(string)
				: string;
		});
	}

	/*--------------------------------------------------------------------------*/

	/** Define the public API */
	punycode = {
		/**
		 * A string representing the current Punycode.js version number.
		 * @memberOf punycode
		 * @type String
		 */
		'version': '1.4.1',
		/**
		 * An object of methods to convert from JavaScript's internal character
		 * representation (UCS-2) to Unicode code points, and back.
		 * @see <https://mathiasbynens.be/notes/javascript-encoding>
		 * @memberOf punycode
		 * @type Object
		 */
		'ucs2': {
			'decode': ucs2decode,
			'encode': ucs2encode
		},
		'decode': decode,
		'encode': encode,
		'toASCII': toASCII,
		'toUnicode': toUnicode
	};

	/** Expose `punycode` */
	// Some AMD build optimizers, like r.js, check for specific condition patterns
	// like the following:
	if (
		true
	) {
		!(__WEBPACK_AMD_DEFINE_RESULT__ = (function() {
			return punycode;
		}).call(exports, __webpack_require__, exports, module),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else if (freeExports && freeModule) {
		if (module.exports == freeExports) {
			// in Node.js, io.js, or RingoJS v0.8.0+
			freeModule.exports = punycode;
		} else {
			// in Narwhal or RingoJS v0.7.0-
			for (key in punycode) {
				punycode.hasOwnProperty(key) && (freeExports[key] = punycode[key]);
			}
		}
	} else {
		// in Rhino or a web browser
		root.punycode = punycode;
	}

}(this));

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(9)(module), __webpack_require__(10)))

/***/ }),
/* 9 */
/***/ (function(module, exports) {

module.exports = function(module) {
	if(!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if(!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),
/* 10 */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {
  isString: function(arg) {
    return typeof(arg) === 'string';
  },
  isObject: function(arg) {
    return typeof(arg) === 'object' && arg !== null;
  },
  isNull: function(arg) {
    return arg === null;
  },
  isNullOrUndefined: function(arg) {
    return arg == null;
  }
};


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
// Copyright Joyent, Inc. and other Node contributors.
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to permit
// persons to whom the Software is furnished to do so, subject to the
// following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
// USE OR OTHER DEALINGS IN THE SOFTWARE.



// If obj.hasOwnProperty has been overridden, then calling
// obj.hasOwnProperty(prop) will break.
// See: https://github.com/joyent/node/issues/1707
function hasOwnProperty(obj, prop) {
  return Object.prototype.hasOwnProperty.call(obj, prop);
}

module.exports = function(qs, sep, eq, options) {
  sep = sep || '&';
  eq = eq || '=';
  var obj = {};

  if (typeof qs !== 'string' || qs.length === 0) {
    return obj;
  }

  var regexp = /\+/g;
  qs = qs.split(sep);

  var maxKeys = 1000;
  if (options && typeof options.maxKeys === 'number') {
    maxKeys = options.maxKeys;
  }

  var len = qs.length;
  // maxKeys <= 0 means that we should not limit keys count
  if (maxKeys > 0 && len > maxKeys) {
    len = maxKeys;
  }

  for (var i = 0; i < len; ++i) {
    var x = qs[i].replace(regexp, '%20'),
        idx = x.indexOf(eq),
        kstr, vstr, k, v;

    if (idx >= 0) {
      kstr = x.substr(0, idx);
      vstr = x.substr(idx + 1);
    } else {
      kstr = x;
      vstr = '';
    }

    k = decodeURIComponent(kstr);
    v = decodeURIComponent(vstr);

    if (!hasOwnProperty(obj, k)) {
      obj[k] = v;
    } else if (isArray(obj[k])) {
      obj[k].push(v);
    } else {
      obj[k] = [obj[k], v];
    }
  }

  return obj;
};

var isArray = Array.isArray || function (xs) {
  return Object.prototype.toString.call(xs) === '[object Array]';
};


/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
// Copyright Joyent, Inc. and other Node contributors.
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to permit
// persons to whom the Software is furnished to do so, subject to the
// following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
// USE OR OTHER DEALINGS IN THE SOFTWARE.



var stringifyPrimitive = function(v) {
  switch (typeof v) {
    case 'string':
      return v;

    case 'boolean':
      return v ? 'true' : 'false';

    case 'number':
      return isFinite(v) ? v : '';

    default:
      return '';
  }
};

module.exports = function(obj, sep, eq, name) {
  sep = sep || '&';
  eq = eq || '=';
  if (obj === null) {
    obj = undefined;
  }

  if (typeof obj === 'object') {
    return map(objectKeys(obj), function(k) {
      var ks = encodeURIComponent(stringifyPrimitive(k)) + eq;
      if (isArray(obj[k])) {
        return map(obj[k], function(v) {
          return ks + encodeURIComponent(stringifyPrimitive(v));
        }).join(sep);
      } else {
        return ks + encodeURIComponent(stringifyPrimitive(obj[k]));
      }
    }).join(sep);

  }

  if (!name) return '';
  return encodeURIComponent(stringifyPrimitive(name)) + eq +
         encodeURIComponent(stringifyPrimitive(obj));
};

var isArray = Array.isArray || function (xs) {
  return Object.prototype.toString.call(xs) === '[object Array]';
};

function map (xs, f) {
  if (xs.map) return xs.map(f);
  var res = [];
  for (var i = 0; i < xs.length; i++) {
    res.push(f(xs[i], i));
  }
  return res;
}

var objectKeys = Object.keys || function (obj) {
  var res = [];
  for (var key in obj) {
    if (Object.prototype.hasOwnProperty.call(obj, key)) res.push(key);
  }
  return res;
};


/***/ }),
/* 14 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 15 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ }),
/* 16 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(17);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(20);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode2';

var settings = {
	title: __('BP Group Cover Image Link'),

	description: __('Displays the selected group cover image with link to the group home. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 17 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(18);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_group_cover_image_link]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Group Cover Image Link')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_group_cover_image_link]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 18 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(19);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 19 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 20 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ }),
/* 21 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(22);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(25);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode3';

var settings = {
	title: __('BP Group Header'),

	description: __('Displays the selected group header image. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 22 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(23);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_group_header]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Group Header')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_group_header]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 23 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(24);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 24 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 25 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ }),
/* 26 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(27);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(30);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode4';

var settings = {
	title: __('BP Group Members'),

	description: __('Displays the selected group members list. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 27 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(28);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_group_members]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Group Members')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_group_members]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 28 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(29);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 29 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 30 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ }),
/* 31 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(32);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(35);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode8';

var settings = {
	title: __('BP Profile Lists'),

	description: __('Displays the selected users friends or groups list. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 32 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(33);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_profile_lists]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Profile Lists')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_profile_lists]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 33 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(34);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 34 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 35 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ }),
/* 36 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(37);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(40);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode5';

var settings = {
	title: __('BP Profile Cover Image'),

	description: __('Displays the selected users profile cover image. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 37 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(38);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_profile_cover_image]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Profile Cover Image')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_profile_cover_image]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 38 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(39);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 39 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 40 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ }),
/* 41 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(42);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(45);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode6';

var settings = {
	title: __('BP Profile Cover Image Link'),

	description: __('Displays the selected users profile cover image with link to profile. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 42 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(43);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_profile_cover_image_link]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Profile Cover Image Link')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_profile_cover_image_link]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 43 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(44);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 44 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 45 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ }),
/* 46 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(50);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode7';

var settings = {
	title: __('BP Profile Header'),

	description: __('Displays the selected users profile header. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 47 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(48);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_profile_header]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Profile Header')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_profile_header]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 48 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(49);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 49 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 50 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ }),
/* 51 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export name */
/* unused harmony export settings */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block__ = __webpack_require__(52);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__icon__ = __webpack_require__(55);
/**
 * WordPress dependencies
 */
var RawHTML = wp.element.RawHTML;
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;

/**
 * Internal dependencies
 */




var name = 'custom/shortcode9';

var settings = {
	title: __('BP Whats New'),

	description: __('Displays the BP Activity whats new input box. '),

	icon: __WEBPACK_IMPORTED_MODULE_1__icon__["a" /* default */],

	category: 'widgets',

	attributes: {
		text: {
			type: 'string',
			source: 'text'
		}
	},

	transforms: {
		from: [{
			type: 'shortcode',
			// Per "Shortcode names should be all lowercase and use all
			// letters, but numbers and underscores should work fine too.
			// Be wary of using hyphens (dashes), you'll be better off not
			// using them." in https://codex.wordpress.org/Shortcode_API
			// Require that the first character be a letter. This notably
			// prevents footnote markings ([1]) from being caught as
			// shortcodes.
			tag: '[a-z][a-z0-9_-]*',
			attributes: {
				text: {
					type: 'string',
					shortcode: function shortcode(attrs, _ref) {
						var content = _ref.content;

						return content;
					}
				}
			}
		}]
	},

	supports: {
		customClassName: false,
		className: false,
		html: false
	},

	edit: __WEBPACK_IMPORTED_MODULE_0__block__["a" /* default */],

	save: function save(_ref2) {
		var attributes = _ref2.attributes;

		return wp.element.createElement(
			RawHTML,
			null,
			attributes.text
		);
	}
};
registerBlockType(name, settings);

/***/ }),
/* 52 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export Shortcode */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__preview__ = __webpack_require__(53);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Dashicon = wp.components.Dashicon;
var withInstanceId = wp.compose.withInstanceId;
var Component = wp.element.Component;
var __ = wp.i18n.__;
var PlainText = wp.editor.PlainText;

/**
 * Internal dependencies
 */



var Shortcode = function (_Component) {
	_inherits(Shortcode, _Component);

	function Shortcode() {
		_classCallCheck(this, Shortcode);

		var _this = _possibleConstructorReturn(this, (Shortcode.__proto__ || Object.getPrototypeOf(Shortcode)).apply(this, arguments));

		_this.state = {};
		return _this;
	}

	_createClass(Shortcode, [{
		key: 'render',
		value: function render() {
			var _props = this.props,
			    instanceId = _props.instanceId,
			    setAttributes = _props.setAttributes,
			    attributes = _props.attributes,
			    isSelected = _props.isSelected;

			var inputId = 'blocks-shortcode-input-' + instanceId;
			var shortcodeContent = (attributes.text || '[bpps_whats_new]').trim();

			if (!isSelected) {
				return [wp.element.createElement(
					'div',
					{ className: 'wp-block', key: 'preview' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_0__preview__["a" /* default */], {
						shortcode: shortcodeContent
					})
				)];
			}

			return [wp.element.createElement(
				'div',
				{ className: 'wp-block-shortcode', key: 'placeholder' },
				wp.element.createElement(
					'label',
					{ htmlFor: inputId },
					wp.element.createElement(Dashicon, { icon: 'editor-code' }),
					__('BP Whats New')
				),
				wp.element.createElement(PlainText, {
					id: inputId,
					value: (attributes.text || '[bpps_whats_new]').trim(),
					placeholder: __('Write shortcode here…'),
					onChange: function onChange(text) {
						return setAttributes({ text: text });
					}
				})
			)];
		}
	}]);

	return Shortcode;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (withInstanceId(Shortcode));

/***/ }),
/* 53 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wordpress_url__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__sandbox_custom__ = __webpack_require__(54);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var Spinner = wp.components.Spinner;
var __ = wp.i18n.__;
var Component = wp.element.Component;




/**
 * Plugin Dependencies
 */


var ShortcodePreview = function (_Component) {
	_inherits(ShortcodePreview, _Component);

	function ShortcodePreview(props) {
		_classCallCheck(this, ShortcodePreview);

		var _this = _possibleConstructorReturn(this, (ShortcodePreview.__proto__ || Object.getPrototypeOf(ShortcodePreview)).call(this, props));

		_this.state = {
			shortcode: '',
			response: {}
		};
		return _this;
	}

	_createClass(ShortcodePreview, [{
		key: 'componentDidMount',
		value: function componentDidMount() {
			var _this2 = this;

			var shortcode = this.props.shortcode;

			var myURL = new URL(window.location.href);
			var apiURL = Object(__WEBPACK_IMPORTED_MODULE_0__wordpress_url__["a" /* addQueryArgs */])(wpApiSettings.root + 'gutenberg/v1/shortcodes', {
				shortcode: shortcode,
				_wpnonce: wpApiSettings.nonce,
				postId: myURL.searchParams.get('post')
			});
			return window.fetch(apiURL, {
				credentials: 'include'
			}).then(function (response) {
				response.json().then(function (data) {
					return {
						data: data,
						status: response.status
					};
				}).then(function (res) {
					if (res.status === 200) {
						_this2.setState({ response: res });
					}
				});
			});
		}
	}, {
		key: 'render',
		value: function render() {
			var response = this.state.response;
			if (response.isLoading || !response.data) {
				return wp.element.createElement(
					'div',
					{ key: 'loading', className: 'wp-block-embed is-loading' },
					wp.element.createElement(Spinner, null),
					wp.element.createElement(
						'p',
						null,
						__('Loading...')
					)
				);
			}

			/*
    * order must match rest controller style is wp_head, html is shortcode, js is footer
    * should really be named better
    */
			var html = response.data.style + ' ' + response.data.html + ' ' + response.data.js;
			return wp.element.createElement(
				'figure',
				{ className: 'wp-block-embed', key: 'embed' },
				wp.element.createElement(
					'div',
					{ className: 'wp-block-embed__wrapper' },
					wp.element.createElement(__WEBPACK_IMPORTED_MODULE_1__sandbox_custom__["a" /* default */], {
						html: html,
						title: 'Preview',
						type: response.data.type
					})
				)
			);
		}
	}]);

	return ShortcodePreview;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (ShortcodePreview);

/***/ }),
/* 54 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * WordPress dependencies
 */
var _wp$element = wp.element,
    Component = _wp$element.Component,
    renderToString = _wp$element.renderToString;

var Sandbox = function (_Component) {
	_inherits(Sandbox, _Component);

	function Sandbox() {
		_classCallCheck(this, Sandbox);

		var _this = _possibleConstructorReturn(this, (Sandbox.__proto__ || Object.getPrototypeOf(Sandbox)).apply(this, arguments));

		_this.trySandbox = _this.trySandbox.bind(_this);
		_this.checkMessageForResize = _this.checkMessageForResize.bind(_this);
		_this.checkFocus = _this.checkFocus.bind(_this);

		_this.state = {
			width: 0,
			height: 0
		};
		return _this;
	}

	_createClass(Sandbox, [{
		key: 'isFrameAccessible',
		value: function isFrameAccessible() {
			try {
				return !!this.iframe.contentDocument.body;
			} catch (e) {
				return false;
			}
		}
	}, {
		key: 'checkMessageForResize',
		value: function checkMessageForResize(event) {
			var iframe = this.iframe;

			// Attempt to parse the message data as JSON if passed as string
			var data = event.data || {};
			if ('string' === typeof data) {
				try {
					data = JSON.parse(data);
				} catch (e) {} // eslint-disable-line no-empty
			}

			// Verify that the mounted element is the source of the message
			if (!iframe || iframe.contentWindow !== event.source) {
				return;
			}

			// Update the state only if the message is formatted as we expect, i.e.
			// as an object with a 'resize' action, width, and height
			var _data = data,
			    action = _data.action,
			    width = _data.width,
			    height = _data.height;
			var _state = this.state,
			    oldWidth = _state.width,
			    oldHeight = _state.height;


			if ('resize' === action && (oldWidth !== width || oldHeight !== height)) {
				this.setState({ width: width, height: height });
			}
		}
	}, {
		key: 'componentDidMount',
		value: function componentDidMount() {
			window.addEventListener('message', this.checkMessageForResize, false);
			window.addEventListener('blur', this.checkFocus);
			this.trySandbox();
		}
	}, {
		key: 'componentDidUpdate',
		value: function componentDidUpdate() {
			this.trySandbox();
		}
	}, {
		key: 'componentWillUnmount',
		value: function componentWillUnmount() {
			window.removeEventListener('message', this.checkMessageForResize);
			window.removeEventListener('blur', this.checkFocus);
		}
	}, {
		key: 'checkFocus',
		value: function checkFocus() {
			if (this.props.onFocus && document.activeElement === this.iframe) {
				this.props.onFocus();
			}
		}
	}, {
		key: 'trySandbox',
		value: function trySandbox() {
			if (!this.isFrameAccessible()) {
				return;
			}

			var body = this.iframe.contentDocument.body;
			if (null !== body.getAttribute('data-resizable-iframe-connected')) {
				return;
			}

			// sandboxing video content needs to explicitly set the height of the sandbox
			// based on a 16:9 ratio for the content to be responsive
			var heightCalculation = 'video' === this.props.type ? 'clientBoundingRect.width / 16 * 9' : 'clientBoundingRect.height';

			var observeAndResizeJS = '\n\t\t\t( function() {\n\t\t\t\tvar observer;\n\n\t\t\t\tif ( ! window.MutationObserver || ! document.body || ! window.parent ) {\n\t\t\t\t\treturn;\n\t\t\t\t}\n\n\t\t\t\tfunction sendResize() {\n\t\t\t\t\tvar clientBoundingRect = document.body.getBoundingClientRect();\n\t\t\t\t\twindow.parent.postMessage( {\n\t\t\t\t\t\taction: \'resize\',\n\t\t\t\t\t\twidth: clientBoundingRect.width,\n\t\t\t\t\t\theight: ' + heightCalculation + '\n\t\t\t\t\t}, \'*\' );\n\t\t\t\t}\n\n\t\t\t\tobserver = new MutationObserver( sendResize );\n\t\t\t\tobserver.observe( document.body, {\n\t\t\t\t\tattributes: true,\n\t\t\t\t\tattributeOldValue: false,\n\t\t\t\t\tcharacterData: true,\n\t\t\t\t\tcharacterDataOldValue: false,\n\t\t\t\t\tchildList: true,\n\t\t\t\t\tsubtree: true\n\t\t\t\t} );\n\n\t\t\t\twindow.addEventListener( \'load\', sendResize, true );\n\n\t\t\t\t// Hack: Remove viewport unit styles, as these are relative\n\t\t\t\t// the iframe root and interfere with our mechanism for\n\t\t\t\t// determining the unconstrained page bounds.\n\t\t\t\tfunction removeViewportStyles( ruleOrNode ) {\n\t\t\t\t\t[ \'width\', \'height\', \'minHeight\', \'maxHeight\' ].forEach( function( style ) {\n\t\t\t\t\t\tif(!ruleOrNode.style) return;\n\t\t\t\t\t\ttry {\n\t\t\t\t\t\t\tif ( /^\\d+(vmin|vmax|vh|vw)$/.test( ruleOrNode.style[ style ] ) ) {\n\t\t\t\t\t\t\t\truleOrNode.style[ style ] = \'\';\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t} catch(err) {\n\n\t\t\t\t\t\t}\n\t\t\t\t\t} );\n\t\t\t\t}\n\n\t\t\t\tArray.prototype.forEach.call( document.querySelectorAll( \'[style]\' ), removeViewportStyles );\n\t\t\t\tArray.prototype.forEach.call( document.styleSheets, function( stylesheet ) {\n\t\t\t\t\tArray.prototype.forEach.call( stylesheet.cssRules || stylesheet.rules, removeViewportStyles );\n\t\t\t\t} );\n\n\t\t\t\tdocument.body.style.position = \'absolute\';\n\t\t\t\tdocument.body.style.width = \'100%\';\n\t\t\t\tdocument.body.setAttribute( \'data-resizable-iframe-connected\', \'\' );\n\n\t\t\t\tsendResize();\n\t\t} )();';

			var style = '\n\t\t\tbody {\n\t\t\t\tmargin: 0;\n\t\t\t}\n\n\t\t\tbody.html,\n\t\t\tbody.html > div,\n\t\t\tbody.html > div > iframe {\n\t\t\t\twidth: 100%;\n\t\t\t\tmin-height: 100%;\n\t\t\t}\n\n\t\t\tbody > div > * {\n\t\t\t\tmargin-top: 0 !important;\t/* has to have !important to override inline styles */\n\t\t\t\tmargin-bottom: 0 !important;\n\t\t\t}\n\t\t';

			// put the html snippet into a html document, and then write it to the iframe's document
			// we can use this in the future to inject custom styles or scripts
			var htmlDoc = wp.element.createElement(
				'html',
				{ lang: document.documentElement.lang },
				wp.element.createElement(
					'head',
					null,
					wp.element.createElement(
						'title',
						null,
						this.props.title
					),
					wp.element.createElement('style', { dangerouslySetInnerHTML: { __html: style } })
				),
				wp.element.createElement(
					'body',
					{ 'data-resizable-iframe-connected': 'data-resizable-iframe-connected', className: this.props.type },
					wp.element.createElement('div', { id: 'content', dangerouslySetInnerHTML: { __html: this.props.html } }),
					wp.element.createElement('script', { type: 'text/javascript', dangerouslySetInnerHTML: { __html: observeAndResizeJS } })
				)
			);

			// writing the document like this makes it act in the same way as if it was
			// loaded over the network, so DOM creation and mutation, script execution, etc.
			// all work as expected
			this.iframe.contentWindow.document.open();
			this.iframe.contentWindow.document.write('<!DOCTYPE html>' + renderToString(htmlDoc));
			this.iframe.contentWindow.document.close();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this2 = this;

			return wp.element.createElement('iframe', {
				ref: function ref(node) {
					return _this2.iframe = node;
				},
				title: this.props.title,
				scrolling: 'no',
				sandbox: 'allow-scripts allow-same-origin allow-presentation',
				onLoad: this.trySandbox,
				width: Math.ceil(this.state.width),
				height: Math.ceil(this.state.height) });
		}
	}], [{
		key: 'defaultProps',
		get: function get() {
			return {
				html: '',
				title: ''
			};
		}
	}]);

	return Sandbox;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Sandbox);

/***/ }),
/* 55 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var icon = wp.element.createElement(
    "svg",
    { version: "1", xmlns: "http://www.w3.org/2000/svg", width: "20", height: "20",
        viewBox: "0 0 600.000000 600.000000" },
    wp.element.createElement("path", { d: "M2685 5750 c-646 -80 -1197 -345 -1644 -791 -424 -425 -682 -941 -778 -1559 -25 -164 -25 -607 0 -770 51 -322 124 -556 262 -835 396 -804 1157 -1357 2056 -1496 208 -33 600 -33 808 0 788 122 1459 552 1897 1216 214 326 349 678 416 1087 30 182 33 621 5 798 -97 622 -356 1138 -788 1569 -412 410 -925 668 -1526 767 -113 19 -593 28 -708 14z m681 -139 c1032 -155 1873 -901 2149 -1906 226 -824 27 -1719 -528 -2380 -428 -508 -1054 -841 -1719 -914 -125 -14 -441 -14 -566 0 -140 15 -338 55 -468 95 -1272 385 -2052 1643 -1833 2952 41 249 115 474 234 717 310 631 860 1110 1528 1330 213 70 374 102 642 129 96 10 436 -4 561 -23z",
        transform: "matrix(.1 0 0 -.1 0 600)" }),
    wp.element.createElement("path", { d: "M2815 5360 c-946 -70 -1762 -704 -2059 -1600 -132 -401 -154 -849 -60 -1268 187 -836 851 -1526 1678 -1743 233 -61 337 -73 611 -73 274 0 378 12 611 73 548 144 1038 500 1357 986 193 294 315 629 363 995 20 156 15 513 -10 660 -42 241 -108 448 -215 665 -421 857 -1325 1375 -2276 1305z m820 -491 c270 -48 512 -261 608 -537 26 -76 31 -104 35 -222 4 -115 1 -149 -17 -220 -62 -250 -237 -457 -467 -553 -63 -27 -134 -48 -134 -41 0 2 15 35 34 72 138 274 138 610 0 883 -110 220 -334 412 -564 483 -30 10 -62 20 -70 23 -21 7 77 56 175 88 126 41 255 49 400 24z m-610 -285 c310 -84 541 -333 595 -641 18 -101 8 -278 -20 -368 -75 -236 -220 -401 -443 -505 -109 -51 -202 -70 -335 -70 -355 0 -650 217 -765 563 -28 84 -31 104 -31 232 -1 118 3 152 22 220 89 306 335 528 650 585 67 13 257 3 327 -16z m1250 -1374 c301 -95 484 -325 565 -710 21 -103 47 -388 37 -414 -6 -14 -30 -16 -182 -16 -96 0 -175 3 -175 6 0 42 -37 236 -60 313 -99 334 -315 586 -567 661 -24 7 -43 17 -43 21 0 5 32 45 72 90 l72 82 106 -6 c67 -3 130 -13 175 -27z m-1703 -510 l258 -255 92 90 c51 49 183 178 293 286 l200 197 75 -9 c207 -26 404 -116 547 -252 170 -161 267 -361 308 -632 15 -100 21 -394 9 -454 l-6 -31 -1519 0 c-1074 0 -1520 3 -1524 11 -14 21 -18 297 -6 407 59 561 364 896 866 950 97 10 55 41 407 -308z",
        transform: "matrix(.1 0 0 -.1 0 600)" })
);

/* harmony default export */ __webpack_exports__["a"] = (icon);

/***/ })
/******/ ]);