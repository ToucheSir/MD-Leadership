angular.module("eventsDisplay", ["eventResource", "userResource", "ui.bootstrap.modal"])
.service("EventsService", function($q, Event, UserEvent) {
  var maxEvents = 100;

  this.getEvents = function(currentUserID) {
    return $q.all([
      Event.query().$promise,
      UserEvent.query({userID: currentUserID}).$promise
    ]).then(function(results) {
      var formattedEvents = results[0], eventIDs = results[1].eventIDs;

      for (var i = formattedEvents.length - 1; i >= 0; i--) {
        for (var j = eventIDs.length - 1; j >= 0; j--) {
          if(eventIDs[j] === formattedEvents[i].id) {
            formattedEvents[i].attending = true;
          } // if
        } // for
      } // for

      return formattedEvents;
    });
  };

  this.setMaxEvents = function(eventsMax) {
    maxEvents = eventsMax;
  };

  this.getMaxEvents = function() {
    return maxEvents;
  };

  this.addUserToEvent = function(user, event) {
    if(!event.attending) {
      UserEvent.addUserEvent({
        eventID: event.id,
        userID: user.id
      }).$promise.then(function() {
        event.attending = true;
      });
    } else {
      alert("Already Attending Event");
    } // else
  };

  this.removeUserFromEvent = function(user, event) {
    if(event.attending) {
      UserEvent.removeUserEvent({
        eventID: event.id,
        userID: user.id
      }).$promise.then(function() {
        event.attending = false;
      });
    } else {
      alert("Not Attending Event");
    } // else
  };
})

.factory("EventDialog", function($modal, Event) {
  var createEventModal = function(eventInstance, modalController) {
    var modal = $modal.open({
      templateUrl: "eventsDisplay/eventModal.tpl.html",
      controller: modalController,
      resolve: {
        modalEvent: function() {
          return angular.copy(eventInstance);
        }
      }
    });
  };

  return {
    createEvent: function(eventInstance) {
      createEventModal(eventInstance || new Event(), "CreateEventCtrl");
    },
    editEvent: function(eventInstance) {
      createEventModal(eventInstance, "EditEventCtrl");
    }
  };
})

.controller("EventsDisplayCtrl", function($scope, EventDialog, EventsService, SessionService) {
  var user = SessionService.getUser();
  // debugger;

  $scope.events = EventsService.getEvents(user.id);
  $scope.selectedEvent = {};
  $scope.maxEvents = EventsService.getMaxEvents();

  $scope.attendEvent = function(event) {
    EventsService.addUserToEvent(user, event);
  };

  $scope.leaveEvent = function(event) {
    EventsService.removeUserFromEvent(user, event);
  };

  $scope.createEvent = function() {
    EventDialog.createEvent();
  };
})

.controller("EditEventCtrl", function($scope, $modalInstance, modalEvent) {
  $scope.modalEvent = modalEvent;
  $scope.eventAction = "Editing";

  $scope.saveEvent = function(event) {
    $scope.modalEvent.$updateEvent();
    $modalInstance.dismiss("saved");
  };

  $scope.cancel = function() {
    $modalInstance.dismiss("cancel");
  };
})

.controller("CreateEventCtrl", function($scope, $modalInstance, UserCredentials, modalEvent) {
  $scope.modalEvent = modalEvent;
  $scope.eventAction = "Editing";

  $scope.saveEvent = function(event) {
    $scope.modalEvent.creator = UserCredentials.getID();
    $scope.modalEvent.$addEvent();
    $modalInstance.dismiss("created");
  };

  $scope.cancel = function() {
    $modalInstance.dismiss("cancel");
  };
});