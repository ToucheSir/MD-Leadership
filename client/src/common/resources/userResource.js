angular.module("userResource", ["ngResource"])

.factory("User", function($resource) {
	var rootURL = "http://localhost/leadership/api/index.php/users/:userID";

	var userActions = {
		query: {method: "GET", params: {userID: ""}, isArray: true},
		getUser: {method: "GET"},
		addUser: {method: "PUT"},
		updateUser: {method: "PUT"},
		removeUser: {method: "DELETE"},
		getUserEvents: {method: "GET", url: rootURL + "/events/:eventID", params: {eventID: ""}, isArray: true}
	};

	return $resource(rootURL, {}, userActions);
});