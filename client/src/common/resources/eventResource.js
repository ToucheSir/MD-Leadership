angular.module("eventResource", ["ngResource"])

.factory("Event", function($resource) {
	"use strict";

	var rootURL = "http://localhost/leadership/api/index.php/";

	var transformer = function(data, headersGetter) {
		headersGetter()["FakeAuth"] = 123456;
	};

	var eventActions = {
		query: {
			method: "GET",
			params: {eventID: ""},
			isArray: true,
			transformRequest: transformer
		},
		getEvent: {method: "GET"},
		addEvent: {method: "POST", params: {eventID: ""}},
		updateEvent: {method: "PUT"},
		removeEvent: {method: "DELETE"}
	};

	return $resource(rootURL + "events/:eventID", {}, eventActions);
});