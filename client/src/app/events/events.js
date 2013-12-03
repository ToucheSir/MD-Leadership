angular.module("MDLeadership.events", [
  "ngRoute",
  "titleService",
  "eventResource",
  "userResource",
  "ui.bootstrap",
  "eventsDisplay"
])

.config(function($routeProvider) {
  $routeProvider.when("/events", {
    controller: "EventsCtrl",
    templateUrl: "events/events.tpl.html"
  }).when("/events/:eventID", {
    controller: "SingleEventCtrl",
    templateUrl: "events/singleEvent.tpl.html",
    resolve: {
      singleEvent: function($route, Event) {
        var event = Event.getEvent({eventID: $route.current.params["eventID"]});
        return event.$promise;
      }
    }
  });
})

.controller("EventsCtrl", function(titleService) {
  titleService.setTitle("Events");
})

/**
 * And of course we define a controller for our route.
 */
.controller("SingleEventCtrl", function($scope, $timeout, singleEvent, EventDialog) {
  $scope.event = singleEvent;

  $scope.editingEvent = false;

  $scope.openCalendar = function(calendarName) {
    $timeout(function() {
      $scope[calendarName] = true;
    });
  };

  $scope.editEvent = function() {
    EventDialog.editEvent(angular.copy($scope.event));
  };
});