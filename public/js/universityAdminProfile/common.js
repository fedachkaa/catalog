(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define("general", [], factory);
	else if(typeof exports === 'object')
		exports["general"] = factory();
	else
		root["general"] = root["general"] || {}, root["general"]["universityAdminProfile"] = factory();
})(this, () => {
return /******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/universityAdminProfile/common.js":
/*!*******************************************************!*\
  !*** ./resources/js/universityAdminProfile/common.js ***!
  \*******************************************************/
/***/ ((module) => {

var searchGroups = function searchGroups(searchParams) {
  var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
  var queryString = '';
  for (var key in searchParams) {
    if (searchParams.hasOwnProperty(key)) {
      var value = searchParams[key];
      queryString += encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
    }
  }
  $.ajax({
    url: '/api/university/' + universityId + '/groups?' + queryString,
    method: 'GET',
    success: function success(response) {
      callback(response.data);
    },
    error: function error(xhr, status, _error) {
      console.error('Помилка:', _error);
    }
  });
};
module.exports = {
  searchGroups: searchGroups
};

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/js/universityAdminProfile/common.js");
/******/ 	
/******/ 	return __webpack_exports__;
/******/ })()
;
});