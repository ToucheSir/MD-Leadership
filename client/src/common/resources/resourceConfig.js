/**
* resourceConfig
*
* sets default http interceptors, headers, etc on behalf of ngResource
*/
angular.module("resourceConfig", [])
.config(function($httpProvider) {
	// $httpProvider.defaults.cache = true;
})

.service("resourceConfigService", function($http) {
	"use strict";

	var headers = $http.defaults.headers;

	this.setHeaders = function(headers) {
		angular.extend(headers.common, headers);
	};

	this.header = function(name, newValue) {
		if(newValue) {
			headers.common[name] = newValue;
		}

		return headers.common[name];
	};
});