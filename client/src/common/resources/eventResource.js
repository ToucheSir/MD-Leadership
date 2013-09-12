angular.module("eventResource", ["ngResource", "dateService"])

.factory("Event", function($resource, $http, DateService) {
	var eventURL = "http://localhost/leadershipAPI/events/:eventID";

	var eventDateParser = function(event) {
		var eventStart = event.timerange.start || new Date();
		var eventEnd = event.timerange.end || angular.copy(event.timerange.start);

		event.timerange.start = DateService.parseDate(eventStart);
		event.timerange.end = DateService.parseDate(eventEnd);

		return event;
	};

	var eventParser = $http.defaults.transformResponse.concat([
		function(data, headersGetter) {

			if(angular.isArray(data)) {
				angular.forEach(data, function(value){
					value = eventDateParser(value);
				});
			} else {
				data = eventDateParser(data);
			}

			return data;
		}
	]);

	var eventActions = {
		query: {
			method: "GET",
			params: {eventID: ""},
			isArray: true,
			transformResponse: eventParser
		},
		getEvent: {method: "GET", transformResponse: eventParser},
		addEvent: {method: "POST", params: {eventID: ""}},
		updateEvent: {method: "PUT"},
		removeEvent: {method: "DELETE"}
	};

	return $resource(eventURL, {eventID: "@id"}, eventActions);
})

.factory("EventUser", function($resource) {
	var eventUserURL = "http://localhost/leadershipAPI/events/:eventID/users/:userID";

	var eventUserActions = {
		query: {method: "GET", params: {eventID: "@eventID", userID: ""}},
		getEventUser: {method: "GET"},
		addEventUser: {method: "PUT"},
		removeEventUser: {method: "DELETE"}
	};

	return $resource(eventUserURL, {eventID: "@eventID", userID: "@userID"}, eventUserActions);
});