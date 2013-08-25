angular.module("MDLeadership.events", [
	"ngRoute",
	"ngAnimate",
	"titleService",
	"eventResource",
	"userResource",
	"ui.bootstrap"
])

.config(function config($routeProvider) {
    $routeProvider.when("/events", {
        controller: "EventsCtrl",
        templateUrl: "events/events.tpl.html"
    });
})

/**
 * And of course we define a controller for our route.
 */
.controller("EventsCtrl", function EventsCtrl($scope, titleService, Event, User) {
	titleService.setTitle("Events");

	$scope.currentUser = {
		name: "Bob",
		id: 123456
	};

	$scope.recentEvents = Event.query();
	$scope.userEvents = User.getUserEvents({userID: 123456});
	console.log($scope.userEvents);

	$scope.showPanel = false;

	$scope.selectedEvent = {};

	$scope.viewEvent = function(event) {
		$scope.selectedEvent.editing = false;
		$scope.selectedEvent = event;
		$scope.showPanel = true;
	};

});