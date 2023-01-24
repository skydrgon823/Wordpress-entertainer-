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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./lite/admin/js/main.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./lite/admin/css/style.css":
/*!**********************************!*\
  !*** ./lite/admin/css/style.css ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./lite/admin/css/style.css?");

/***/ }),

/***/ "./lite/admin/js/main.js":
/*!*******************************!*\
  !*** ./lite/admin/js/main.js ***!
  \*******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(global) {/* harmony import */ var _css_style_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../css/style.css */ \"./lite/admin/css/style.css\");\n/* harmony import */ var _css_style_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_style_css__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _src_views_GalleryItemsPage__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./src/views/GalleryItemsPage */ \"./lite/admin/js/src/views/GalleryItemsPage.js\");\n\n\n\nif ('undefined' !== typeof wp.i18n) {\n  global.__ = wp.i18n.__;\n} else {\n  // Create a dummy fallback function incase i18n library isn't available.\n  global.__ = function (text, textDomain) {\n    return text;\n  };\n}\n\nvar campaignGalleryItemsWrapper = document.querySelector('#ig-es-campaign-gallery-items-wrapper');\nvar campaignType = location.search.split('campaign-type=')[1];\nvar campaignId = location.search.split('campaign-id=')[1];\n\nif ('undefined' === typeof campaignType) {\n  campaignType = ig_es_main_js_data.post_notification_campaign_type;\n}\n\nif ('undefined' === typeof campaignId) {\n  campaignId = 0;\n}\n\nm.mount(campaignGalleryItemsWrapper, {\n  view: function view() {\n    return m(_src_views_GalleryItemsPage__WEBPACK_IMPORTED_MODULE_1__[\"default\"], {\n      campaignId: campaignId,\n      campaignType: campaignType\n    });\n  }\n});\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/global.js */ \"./node_modules/webpack/buildin/global.js\")))\n\n//# sourceURL=webpack:///./lite/admin/js/main.js?");

/***/ }),

/***/ "./lite/admin/js/src/models/GalleryItems.js":
/*!**************************************************!*\
  !*** ./lite/admin/js/src/models/GalleryItems.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _views_Loader__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../views/Loader */ \"./lite/admin/js/src/views/Loader.js\");\n\nvar GalleryItems = {\n  items: [],\n  loadItems: function loadItems() {\n    _views_Loader__WEBPACK_IMPORTED_MODULE_0__[\"default\"].showLoader = true;\n    return m.request({\n      method: 'GET',\n      url: ajaxurl,\n      params: {\n        action: 'ig_es_get_gallery_items',\n        security: ig_es_js_data.security\n      },\n      withCredentials: true\n    }).then(function (response) {\n      GalleryItems.items = response.data.items;\n      _views_Loader__WEBPACK_IMPORTED_MODULE_0__[\"default\"].showLoader = false;\n    });\n  },\n  loadTemplatePreviewData: function loadTemplatePreviewData(templateId) {\n    _views_Loader__WEBPACK_IMPORTED_MODULE_0__[\"default\"].showLoader = true;\n    return m.request({\n      method: 'GET',\n      url: ajaxurl,\n      params: {\n        action: 'ig_es_preview_template',\n        security: ig_es_js_data.security,\n        template_id: templateId\n      },\n      withCredentials: true\n    }).then(function (response) {\n      _views_Loader__WEBPACK_IMPORTED_MODULE_0__[\"default\"].showLoader = false;\n      return response;\n    });\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (GalleryItems);\n\n//# sourceURL=webpack:///./lite/admin/js/src/models/GalleryItems.js?");

/***/ }),

/***/ "./lite/admin/js/src/views/EditorChoicePopup.js":
/*!******************************************************!*\
  !*** ./lite/admin/js/src/views/EditorChoicePopup.js ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GalleryItemsPage */ \"./lite/admin/js/src/views/GalleryItemsPage.js\");\n\nvar EditorChoicePopup = {\n  view: function view(vnode) {\n    var create_campaign_url = '';\n\n    if (vnode.attrs.campaignType === 'post_notification') {\n      create_campaign_url = '?page=es_notifications';\n    } else if (vnode.attrs.campaignType === 'newsletter') {\n      create_campaign_url = '?page=es_newsletters';\n    }\n\n    create_campaign_url += '&action=new';\n    return m(\"div\", {\n      id: \"ig-es-campaign-editor-type-popup\"\n    }, m(\"div\", {\n      class: \"fixed top-0 left-0 z-50 flex items-center justify-center w-full h-full\",\n      style: \"background-color: rgba(0,0,0,.5);\"\n    }, m(\"div\", {\n      class: \"absolute h-auto p-4 ml-16 mr-4 text-left bg-white rounded shadow-xl z-80 md:max-w-5xl md:p-3 lg:p-4\"\n    }, m(\"div\", {\n      class: \"py-2 px-4\"\n    }, m(\"div\", {\n      class: \"flex border-b border-gray-200 pb-2\"\n    }, m(\"h3\", {\n      class: \"text-2xl text-center w-11/12\"\n    }, __('Create Campaign', 'email-subscribers')), m(\"button\", {\n      id: \"close-campaign-editor-type-popup\",\n      onclick: function onclick() {\n        _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].hideEditorChoicePopup();\n      },\n      class: \"text-sm font-medium tracking-wide text-gray-700 select-none no-outline focus:outline-none focus:shadow-outline-red hover:border-red-400 active:shadow-lg\"\n    }, m(\"svg\", {\n      class: \"h-5 w-5 inline\",\n      xmlns: \"http://www.w3.org/2000/svg\",\n      fill: \"none\",\n      viewBox: \"0 0 24 24\",\n      stroke: \"currentColor\",\n      \"aria-hidden\": \"true\"\n    }, m(\"path\", {\n      \"stroke-linecap\": \"round\",\n      \"stroke-linejoin\": \"round\",\n      \"stroke-width\": \"2\",\n      d: \"M6 18L18 6M6 6l12 12\"\n    }))))), m(\"div\", {\n      class: \"mx-4 my-2 list-decimal\"\n    }, m(\"div\", {\n      class: \"mx-auto flex justify-center pt-2\"\n    }, m(\"label\", {\n      class: \"inline-flex items-center cursor-pointer mr-3 h-22 w-50\"\n    }, m(\"div\", {\n      class: \"px-3 py-1 border border-gray-200 rounded-lg shadow-md es-mailer-logo es-importer-logo h-18 bg-white\"\n    }, m(\"a\", {\n      href: create_campaign_url + '&editor-type=' + ig_es_main_js_data.dnd_editor_slug,\n      class: \"campaign-editor-type-choice\"\n    }, m(\"div\", {\n      class: \"border-0 es-logo-wrapper\"\n    }, m(\"svg\", {\n      xmlns: \"http://www.w3.org/2000/svg\",\n      class: \"h-5 w-5\",\n      viewBox: \"0 0 20 20\",\n      fill: \"currentColor\"\n    }, m(\"path\", {\n      \"fill-rule\": \"evenodd\",\n      d: \"M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 110-2h4a1 1 0 011 1v4a1 1 0 11-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 112 0v1.586l2.293-2.293a1 1 0 011.414 1.414L6.414 15H8a1 1 0 110 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 110-2h1.586l-2.293-2.293a1 1 0 011.414-1.414L15 13.586V12a1 1 0 011-1z\",\n      \"clip-rule\": \"evenodd\"\n    }))), m(\"p\", {\n      class: \"mb-2 text-sm inline-block font-medium text-gray-600\"\n    }, __('Create using new Drag & Drop Editor', 'email-subscribers'))))), m(\"label\", {\n      class: \"inline-flex items-center cursor-pointer mr-3 h-22 w-50\"\n    }, m(\"div\", {\n      class: \"px-3 py-1 border border-gray-200 rounded-lg shadow-md es-mailer-logo es-importer-logo h-18 bg-white\"\n    }, m(\"a\", {\n      href: create_campaign_url + '&editor-type=' + ig_es_main_js_data.classic_editor_slug,\n      class: \"campaign-editor-type-choice\",\n      \"data-editor-type\": \"<?php echo esc_attr( IG_ES_CLASSIC_EDITOR ); ?>\"\n    }, m(\"div\", {\n      class: \"border-0 es-logo-wrapper\"\n    }, m(\"svg\", {\n      xmlns: \"http://www.w3.org/2000/svg\",\n      class: \"h-6 w-6\",\n      fill: \"none\",\n      viewBox: \"0 0 24 24\",\n      stroke: \"currentColor\"\n    }, m(\"path\", {\n      \"stroke-linecap\": \"round\",\n      \"stroke-linejoin\": \"round\",\n      \"stroke-width\": \"2\",\n      d: \"M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4\"\n    }))), m(\"p\", {\n      class: \"mb-2 text-sm inline-block font-medium text-gray-600\"\n    }, __('Create using Classic Editor', 'email-subscribers'))))))))));\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (EditorChoicePopup);\n\n//# sourceURL=webpack:///./lite/admin/js/src/views/EditorChoicePopup.js?");

/***/ }),

/***/ "./lite/admin/js/src/views/GalleryItem.js":
/*!************************************************!*\
  !*** ./lite/admin/js/src/views/GalleryItem.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GalleryItemsPage */ \"./lite/admin/js/src/views/GalleryItemsPage.js\");\n\nvar GalleryItem = {\n  view: function view(vnode) {\n    var item = vnode.attrs.item; // let campaignType = vnode.attrs.campaignType;\n    // let campaignId = vnode.attrs.campaignId;\n\n    return m(\"div\", {\n      class: \"\"\n    }, m(\"div\", {\n      class: \"h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden\"\n    }, item.thumbnail ? m(\"img\", {\n      class: \"lg:h-48 md:h-36 w-full object-contain object-center\",\n      src: item.thumbnail,\n      alt: \"{item.title}\"\n    }) : m(\"svg\", {\n      xmlns: \"http://www.w3.org/2000/svg\",\n      class: \"h-40 w-full mb-8\",\n      fill: \"none\",\n      viewBox: \"0 0 24 24\",\n      stroke: \"#d2d6dc\"\n    }, m(\"path\", {\n      \"stroke-linecap\": \"round\",\n      \"stroke-linejoin\": \"round\",\n      \"stroke-width\": \"1\",\n      d: \"M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76\"\n    })), m(\"div\", {\n      class: \"p-4 bg-white h-28\"\n    }, m(\"div\", {\n      class: \"flex items-center flex-wrap whitespace-nowrap\"\n    }, item.categories.map(function (name, key) {\n      return m(\"span\", {\n        class: \"es-tmpl-category capitalize mr-2 inline-flex items-center leading-none py-1 px-1 text-xs rounded\"\n      }, item.categories[key].replace(/_/g, ' '));\n    })), m(\"h4\", {\n      onclick: function onclick() {\n        return _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].showPreview(item.ID);\n      },\n      class: \"title-font text-lg font-medium text-gray-900 mb-3 mt-2 sm:truncate cursor-pointer hover:underline\"\n    }, item.title), m(\"div\", {\n      class: \"flex items-center flex-wrap \"\n    }, m(\"a\", {\n      href: '?action=ig_es_import_gallery_item&template-id=' + item.ID + '&campaign-type=' + vnode.attrs.campaignType + '&_wpnonce=' + ig_es_js_data.security,\n      class: \"font-semibold text-base text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0\"\n    }, __('Use this', 'email-subscribers'), m(\"svg\", {\n      class: \"w-4 h-4 ml-2\",\n      viewBox: \"0 0 24 24\",\n      stroke: \"currentColor\",\n      \"stroke-width\": \"2\",\n      fill: \"none\",\n      \"stroke-linecap\": \"round\",\n      \"stroke-linejoin\": \"round\"\n    }, m(\"path\", {\n      d: \"M5 12h14\"\n    }), m(\"path\", {\n      d: \"M12 5l7 7-7 7\"\n    })))))));\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (GalleryItem);\n\n//# sourceURL=webpack:///./lite/admin/js/src/views/GalleryItem.js?");

/***/ }),

/***/ "./lite/admin/js/src/views/GalleryItemFilter.js":
/*!******************************************************!*\
  !*** ./lite/admin/js/src/views/GalleryItemFilter.js ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GalleryItemsPage */ \"./lite/admin/js/src/views/GalleryItemsPage.js\");\n\nvar GalleryItemFilter = {\n  view: function view(vnode) {\n    //let item = vnode.attrs.item;\n    return m(\"div\", {\n      class: \"text-center text-xs font-medium text-green-800\"\n    }, m(\"p\", {\n      class: \"mb-3 text-gray-700 text-sm font-thin\"\n    }, __('Click on the labels to filter out the templates', 'email-subscribers')), m(\"p\", {\n      class: \"mb-3 pr-2 inline border-r border-gray-300\"\n    }, m(\"a\", {\n      href: \"#\",\n      onclick: function onclick() {\n        _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].setActiveFilters('type', ig_es_main_js_data.newsletter_campaign_type);\n      },\n      class: (_GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].activeFilters.type.indexOf(ig_es_main_js_data.newsletter_campaign_type) > -1 ? \"border-green-800 border-solid border \" : \"\") + \"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 \"\n    }, __('Newsletter', 'email-subscribers')), m(\"a\", {\n      href: \"#\",\n      onclick: function onclick() {\n        _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].setActiveFilters('type', ig_es_main_js_data.post_notification_campaign_type);\n      },\n      class: (_GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].activeFilters.type.indexOf(ig_es_main_js_data.post_notification_campaign_type) > -1 ? \"border-green-800 border-solid border \" : \"\") + \"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 \"\n    }, __('Post Notification', 'email-subscribers')), ig_es_js_data.is_pro && m(\"a\", {\n      href: \"#\",\n      onclick: function onclick() {\n        _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].setActiveFilters('type', ig_es_main_js_data.post_digest_campaign_type);\n      },\n      class: (_GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].activeFilters.type.indexOf(ig_es_main_js_data.post_digest_campaign_type) > -1 ? \"border-green-800 border-solid border \" : \"\") + \"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 \"\n    }, __('Post Digest', 'email-subscribers'))), m(\"p\", {\n      class: \"inline pl-2\"\n    }, m(\"a\", {\n      href: \"#\",\n      onclick: function onclick() {\n        _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].setActiveFilters('editor_type', ig_es_main_js_data.classic_editor_slug);\n      },\n      class: (_GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].activeFilters.editor_type.indexOf(ig_es_main_js_data.classic_editor_slug) > -1 ? \"border-green-800 border-solid border \" : \"\") + \"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 \"\n    }, __('Classic Editor', 'email-subscribers')), m(\"a\", {\n      href: \"#\",\n      onclick: function onclick() {\n        _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].setActiveFilters('editor_type', ig_es_main_js_data.dnd_editor_slug);\n      },\n      class: (_GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].activeFilters.editor_type.indexOf(ig_es_main_js_data.dnd_editor_slug) > -1 ? \"border-green-800 border-solid border \" : \"\") + \"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 \"\n    }, __('Drag and Drop editor', 'email-subscribers'))), m(\"a\", {\n      href: \"#\",\n      class: \"text-red-800 m-1 px-3 py-1 cursor-pointer\",\n      onclick: function onclick() {\n        _GalleryItemsPage__WEBPACK_IMPORTED_MODULE_0__[\"default\"].clearAllActiveFilters();\n      }\n    }, __('Clear all filters', 'email-subscribers')));\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (GalleryItemFilter);\n\n//# sourceURL=webpack:///./lite/admin/js/src/views/GalleryItemFilter.js?");

/***/ }),

/***/ "./lite/admin/js/src/views/GalleryItemPreview.js":
/*!*******************************************************!*\
  !*** ./lite/admin/js/src/views/GalleryItemPreview.js ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\nvar GalleryItemPreview = {\n  previewHTML: '',\n  oncreate: function oncreate() {\n    ig_es_load_iframe_preview('#gallery-item-preview-iframe-container', GalleryItemPreview.previewHTML);\n  },\n  view: function view(vnode) {\n    return m(\"div\", {\n      id: \"campaign-preview-popup\"\n    }, m(\"div\", {\n      class: \"fixed top-0 left-0 z-50 flex items-center justify-center w-full h-full\",\n      style: \"background-color: rgba(0,0,0,.5);\"\n    }, m(\"div\", {\n      id: \"campaign-preview-main-container\",\n      class: \"absolute h-auto pt-2 ml-16 mr-4 text-left bg-white rounded shadow-xl z-80 w-1/2 md:max-w-5xl lg:max-w-7xl md:pt-3 lg:pt-2\"\n    }, m(\"div\", {\n      class: \"py-2 px-4\"\n    }, m(\"div\", {\n      class: \"flex border-b border-gray-200 pb-2\"\n    }, m(\"h3\", {\n      class: \"w-full text-2xl text-left\"\n    }, __('Template Preview', 'email-subscribers')), m(\"div\", {\n      class: \"flex\"\n    }, m(\"button\", {\n      id: \"close-campaign-preview-popup\",\n      class: \"text-sm font-medium tracking-wide text-gray-700 select-none no-outline focus:outline-none focus:shadow-outline-red hover:border-red-400 active:shadow-lg\",\n      onclick: function onclick() {\n        GalleryItemPreview.previewHTML = '';\n      }\n    }, m(\"svg\", {\n      class: \"h-5 w-5 inline\",\n      xmlns: \"http://www.w3.org/2000/svg\",\n      fill: \"none\",\n      viewBox: \"0 0 24 24\",\n      stroke: \"currentColor\",\n      \"aria-hidden\": \"true\"\n    }, m(\"path\", {\n      \"stroke-linecap\": \"round\",\n      \"stroke-linejoin\": \"round\",\n      \"stroke-width\": \"2\",\n      d: \"M6 18L18 6M6 6l12 12\"\n    })))))), m(\"div\", {\n      id: \"gallery-item-preview-container\"\n    }, m(\"p\", {\n      class: \"mx-4 mb-2\"\n    }, __('There could be a slight variation on how your customer will view the email content.', 'email-subscribers')), m(\"div\", {\n      id: \"gallery-item-preview-iframe-container\",\n      class: \"py-4 list-decimal popup-preview\"\n    })))));\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (GalleryItemPreview);\n\n//# sourceURL=webpack:///./lite/admin/js/src/views/GalleryItemPreview.js?");

/***/ }),

/***/ "./lite/admin/js/src/views/GalleryItemsPage.js":
/*!*****************************************************!*\
  !*** ./lite/admin/js/src/views/GalleryItemsPage.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _models_GalleryItems__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../models/GalleryItems */ \"./lite/admin/js/src/models/GalleryItems.js\");\n/* harmony import */ var _GalleryItem__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./GalleryItem */ \"./lite/admin/js/src/views/GalleryItem.js\");\n/* harmony import */ var _GalleryItemFilter__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./GalleryItemFilter */ \"./lite/admin/js/src/views/GalleryItemFilter.js\");\n/* harmony import */ var _GalleryItemPreview__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./GalleryItemPreview */ \"./lite/admin/js/src/views/GalleryItemPreview.js\");\n/* harmony import */ var _EditorChoicePopup__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./EditorChoicePopup */ \"./lite/admin/js/src/views/EditorChoicePopup.js\");\n/* harmony import */ var _Loader__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./Loader */ \"./lite/admin/js/src/views/Loader.js\");\n\n\n\n\n\n\nvar GalleryItemsPage = {\n  canShowEditorChoicePopup: false,\n  activeFilters: [],\n  oninit: function oninit(vnode) {\n    _models_GalleryItems__WEBPACK_IMPORTED_MODULE_0__[\"default\"].loadItems();\n    var campaignType = vnode.attrs.campaignType;\n\n    if (!GalleryItemsPage.activeFilters.type) {\n      GalleryItemsPage.activeFilters.type = [];\n    }\n\n    if (!GalleryItemsPage.activeFilters.editor_type) {\n      GalleryItemsPage.activeFilters.editor_type = [ig_es_main_js_data.classic_editor_slug, ig_es_main_js_data.dnd_editor_slug]; // GalleryItemsPage.activeFilters.editor_type.push();\n      // GalleryItemsPage.activeFilters.editor_type.push(ig_es_main_js_data.dnd_editor_slug);\n    }\n\n    if (GalleryItemsPage.activeFilters.type.length >= 0 && GalleryItemsPage.activeFilters.type.indexOf(campaignType) === -1) {\n      GalleryItemsPage.activeFilters.type.push(campaignType);\n      var isPostNotificationCampaign = campaignType === ig_es_main_js_data.post_notification_campaign_type;\n\n      if (isPostNotificationCampaign) {\n        GalleryItemsPage.activeFilters.type.push(ig_es_main_js_data.post_digest_campaign_type);\n      }\n    }\n  },\n  showPreview: function showPreview(id) {\n    _models_GalleryItems__WEBPACK_IMPORTED_MODULE_0__[\"default\"].loadTemplatePreviewData(id).then(function (response) {\n      _GalleryItemPreview__WEBPACK_IMPORTED_MODULE_3__[\"default\"].previewHTML = response.data.template_html;\n    });\n  },\n  showEditorChoicePopup: function showEditorChoicePopup() {\n    GalleryItemsPage.canShowEditorChoicePopup = true;\n  },\n  hideEditorChoicePopup: function hideEditorChoicePopup() {\n    GalleryItemsPage.canShowEditorChoicePopup = false;\n  },\n  setActiveFilters: function setActiveFilters(filter, filterVal) {\n    if (!GalleryItemsPage.activeFilters[filter]) {\n      GalleryItemsPage.activeFilters[filter] = [];\n    }\n\n    if (GalleryItemsPage.activeFilters[filter].indexOf(filterVal) > -1) {\n      GalleryItemsPage.activeFilters[filter] = GalleryItemsPage.activeFilters[filter].filter(function (e) {\n        return e !== filterVal;\n      });\n    } else {\n      GalleryItemsPage.activeFilters[filter].push(filterVal);\n    }\n  },\n  clearAllActiveFilters: function clearAllActiveFilters() {\n    GalleryItemsPage.activeFilters.type = [];\n    GalleryItemsPage.activeFilters.editor_type = [];\n  },\n  view: function view(vnode) {\n    var campaignType = vnode.attrs.campaignType;\n    var sortedGalleryItems = _models_GalleryItems__WEBPACK_IMPORTED_MODULE_0__[\"default\"].items;\n\n    if (_models_GalleryItems__WEBPACK_IMPORTED_MODULE_0__[\"default\"].items.length > 0) {\n      if (Object.keys(GalleryItemsPage.activeFilters).length > 0 || Object.keys(GalleryItemsPage.activeFilters).length > 0) {\n        if (GalleryItemsPage.activeFilters.type.length > 0) {\n          sortedGalleryItems = _models_GalleryItems__WEBPACK_IMPORTED_MODULE_0__[\"default\"].items.filter(function (item) {\n            return GalleryItemsPage.activeFilters.type.includes(item.type);\n          });\n        }\n\n        if (GalleryItemsPage.activeFilters.editor_type.length > 0) {\n          sortedGalleryItems = sortedGalleryItems.filter(function (item) {\n            return GalleryItemsPage.activeFilters.editor_type.includes(item.editor_type);\n          });\n        } // if( GalleryItemsPage.activeFilters['type'] !== undefined && GalleryItemsPage.activeFilters['type'].length <= 0 && GalleryItemsPage.activeFilters['editor_type'] !== undefined && GalleryItemsPage.activeFilters['editor_type'].length <= 0 ){\n        //     sortedGalleryItems = GalleryItems.items;\n        // }\n\n      }\n    }\n\n    return m(\"section\", null, _Loader__WEBPACK_IMPORTED_MODULE_5__[\"default\"].showLoader ? m(_Loader__WEBPACK_IMPORTED_MODULE_5__[\"default\"], null) : null, m(_GalleryItemFilter__WEBPACK_IMPORTED_MODULE_2__[\"default\"], null), m(\"section\", {\n      class: \"overflow-hidden text-gray-700 \"\n    }, m(\"div\", {\n      class: \"container px-5 py-2 mx-auto lg:pt-12 lg:px-24\"\n    }, m(\"div\", {\n      class: \"grid grid-cols-4 gap-4\"\n    }, m(\"div\", {\n      class: \"\"\n    }, m(\"div\", {\n      class: \"h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden\"\n    }, m(\"svg\", {\n      alt: \"{item.title}\",\n      xmlns: \"http://www.w3.org/2000/svg\",\n      class: \"h-40 w-full\",\n      fill: \"none\",\n      viewBox: \"0 0 24 24\",\n      stroke: \"#d2d6dc\",\n      \"stroke-width\": \"2\"\n    }, m(\"path\", {\n      \"stroke-linecap\": \"round\",\n      \"stroke-linejoin\": \"round\",\n      d: \"M12 6v6m0 0v6m0-6h6m-6 0H6\"\n    })), m(\"div\", {\n      class: \"p-4 bg-white h-28 mt-8\"\n    }, m(\"h4\", {\n      href: \"#\",\n      onclick: function onclick() {\n        GalleryItemsPage.showEditorChoicePopup();\n      },\n      class: \"title-font text-lg font-medium text-gray-900 mb-3 sm:truncate cursor-pointer hover:underline mt-6\"\n    }, __('Create from scratch', 'email-subscribers'))))), sortedGalleryItems.map(function (item, key) {\n      if (ig_es_main_js_data.post_digest_campaign_type === item.type) {\n        campaignType = item.type;\n      }\n\n      return m(_GalleryItem__WEBPACK_IMPORTED_MODULE_1__[\"default\"], {\n        key: key,\n        item: item,\n        campaignType: campaignType,\n        campaignId: vnode.attrs.campaignId\n      });\n    })))), _GalleryItemPreview__WEBPACK_IMPORTED_MODULE_3__[\"default\"].previewHTML !== '' ? m(_GalleryItemPreview__WEBPACK_IMPORTED_MODULE_3__[\"default\"], null) : '', GalleryItemsPage.canShowEditorChoicePopup ? m(_EditorChoicePopup__WEBPACK_IMPORTED_MODULE_4__[\"default\"], {\n      campaignType: vnode.attrs.campaignType\n    }) : '');\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (GalleryItemsPage);\n\n//# sourceURL=webpack:///./lite/admin/js/src/views/GalleryItemsPage.js?");

/***/ }),

/***/ "./lite/admin/js/src/views/Loader.js":
/*!*******************************************!*\
  !*** ./lite/admin/js/src/views/Loader.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return ESLoader; });\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, \"prototype\", { writable: false }); return Constructor; }\n\nvar ESLoader = /*#__PURE__*/function () {\n  function ESLoader() {\n    _classCallCheck(this, ESLoader);\n\n    ESLoader.msg = ESLoader.msg || __('Loading', 'email-subscribers');\n  }\n\n  _createClass(ESLoader, [{\n    key: \"view\",\n    value: function view(vnode) {\n      return m(\"div\", {\n        class: \"absolute w-full mt-48 flex flex-col justify-center text-center items-center space-y-4\"\n      }, m(\"div\", {\n        class: \"text-lg text-gray-600\"\n      }, ESLoader.msg || ''), m(\"div\", {\n        class: \"text-indigo-600\"\n      }, m(\"svg\", {\n        xmlns: \"http://www.w3.org/2000/svg\",\n        class: \"w-16 h-16\",\n        stroke: \"currentColor\",\n        fill: \"none\",\n        viewBox: \"0 0 57 57\"\n      }, m(\"g\", {\n        transform: \"translate(1 1)\",\n        \"stroke-width\": \"2\",\n        fill: \"none\",\n        \"fill-rule\": \"evenodd\"\n      }, m(\"circle\", {\n        cx: \"5\",\n        cy: \"50\",\n        r: \"5\"\n      }, m(\"animate\", {\n        attributeName: \"cy\",\n        begin: \"0s\",\n        dur: \"2.2s\",\n        values: \"50;5;50;50\",\n        calcMode: \"linear\",\n        repeatCount: \"indefinite\"\n      }), m(\"animate\", {\n        attributeName: \"cx\",\n        begin: \"0s\",\n        dur: \"2.2s\",\n        values: \"5;27;49;5\",\n        calcMode: \"linear\",\n        repeatCount: \"indefinite\"\n      })), m(\"circle\", {\n        cx: \"27\",\n        cy: \"5\",\n        r: \"5\"\n      }, m(\"animate\", {\n        attributeName: \"cy\",\n        begin: \"0s\",\n        dur: \"2.2s\",\n        from: \"5\",\n        to: \"5\",\n        values: \"5;50;50;5\",\n        calcMode: \"linear\",\n        repeatCount: \"indefinite\"\n      }), m(\"animate\", {\n        attributeName: \"cx\",\n        begin: \"0s\",\n        dur: \"2.2s\",\n        from: \"27\",\n        to: \"27\",\n        values: \"27;49;5;27\",\n        calcMode: \"linear\",\n        repeatCount: \"indefinite\"\n      })), m(\"circle\", {\n        cx: \"49\",\n        cy: \"50\",\n        r: \"5\"\n      }, m(\"animate\", {\n        attributeName: \"cy\",\n        begin: \"0s\",\n        dur: \"2.2s\",\n        values: \"50;50;5;50\",\n        calcMode: \"linear\",\n        repeatCount: \"indefinite\"\n      }), m(\"animate\", {\n        attributeName: \"cx\",\n        from: \"49\",\n        to: \"49\",\n        begin: \"0s\",\n        dur: \"2.2s\",\n        values: \"49;5;27;49\",\n        calcMode: \"linear\",\n        repeatCount: \"indefinite\"\n      }))))));\n    }\n  }]);\n\n  return ESLoader;\n}();\n\n\n\n//# sourceURL=webpack:///./lite/admin/js/src/views/Loader.js?");

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof(obj) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && \"function\" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }, _typeof(obj); }\n\nvar g; // This works in non-strict mode\n\ng = function () {\n  return this;\n}();\n\ntry {\n  // This works if eval is allowed (see CSP)\n  g = g || new Function(\"return this\")();\n} catch (e) {\n  // This works if the window reference is available\n  if ((typeof window === \"undefined\" ? \"undefined\" : _typeof(window)) === \"object\") g = window;\n} // g can still be undefined, but nothing to do about it...\n// We return undefined, instead of nothing here, so it's\n// easier to handle this case. if(!global) { ...}\n\n\nmodule.exports = g;\n\n//# sourceURL=webpack:///(webpack)/buildin/global.js?");

/***/ })

/******/ });