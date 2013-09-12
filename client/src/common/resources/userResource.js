angular.module("userResource", ["ngResource"])

.factory("User", function($resource) {
	var userURL = "http://localhost/leadershipAPI/users/:userID";

	var userActions = {
		query: {method: "GET", params: {userID: ""}, isArray: true},
		getUser: {method: "GET"},
		addUser: {method: "PUT"},
		updateUser: {method: "PUT"},
		removeUser: {method: "DELETE"}
	};

	return $resource(userURL, {userID: "@id"}, userActions);
})

.factory("UserEvent", function($resource) {
	var userEventURL = "http://localhost/leadershipAPI/users/:userID/events/:eventID";

	var userEventActions = {
		query: {method: "GET", params: {eventID: "@eventID", userID: ""}},
		getUserEvent: {method: "GET"},
		addUserEvent: {method: "PUT"},
		removeUserEvent: {method: "DELETE"}
	};

	return $resource(userEventURL, {eventID: "@eventID", userID: "@userID"}, userEventActions);
});