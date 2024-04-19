(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define("general", [], factory);
	else if(typeof exports === 'object')
		exports["general"] = factory();
	else
		root["general"] = root["general"] || {}, root["general"]["universityAdminProfile"] = root["general"]["universityAdminProfile"] || {}, root["general"]["universityAdminProfile"]["teacher"] = root["general"]["universityAdminProfile"]["teacher"] || {}, root["general"]["universityAdminProfile"]["teacher"]["student"] = root["general"]["universityAdminProfile"]["teacher"]["student"] || {}, root["general"]["universityAdminProfile"]["teacher"]["student"]["admin"] = factory();
})(this, () => {
return /******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************!*\
  !*** ./resources/js/admin/overview.js ***!
  \****************************************/

/******/ 	return __webpack_exports__;
/******/ })()
;
});