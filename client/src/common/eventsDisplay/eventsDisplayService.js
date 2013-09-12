angular.module("eventsDisplay", ["eventResource", "userResource", "ui.bootstrap.modal"])
.service("FormattedEventsService", function($q, Event, UserEvent) {
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
})

.factory("EventDialog", function($modal, Event) {
	return {
		dialog: function(eventInstance, dialogController) {
			var modal = $modal.open({
				templateUrl: "eventsDisplay/eventDialog.tpl.html",
				controller: dialogController,
				resolve: {
					dialogEvent: function() {
						return angular.copy(eventInstance);
					}
				}
			});
		},
		createEvent: function(eventInstance) {
			this.dialog(eventInstance || new Event(), "CreateEventCtrl");
		},
		editEvent: function(eventInstance) {
			this.dialog(eventInstance, "EditEventCtrl");
		}
	};
})

.controller("EventsDisplayCtrl", function($scope, EventDialog, FormattedEventsService, UserCredentials, UserEvent) {
	$scope.events = FormattedEventsService.getEvents(UserCredentials.getID());
	$scope.selectedEvent = {};

	$scope.attendEvent = function(event) {
		if(!event.attending) {
			UserEvent.addUserEvent({
				eventID: event.id,
				userID: UserCredentials.getID()
			}).$promise.then(function() {
				event.attending = true;
			});
		} else {
			alert("Already Attending Event");
		} // else
	};

	$scope.leaveEvent = function(event) {
		if(event.attending) {
			UserEvent.removeUserEvent({
				eventID: event.id,
				userID: UserCredentials.getID()
			}).$promise.then(function() {
				event.attending = false;
			});
		} else {
			alert("Not Attending Event");
		} // else
	};

	$scope.createEvent = function() {
		EventDialog.createEvent();
	};
})

.controller("EditEventCtrl", function($scope, $modalInstance, dialogEvent) {
	$scope.dialogEvent = dialogEvent;
	$scope.eventAction = "Editing";

	$scope.saveEvent = function(event) {
		$scope.dialogEvent.$updateEvent();
		$modalInstance.dismiss("saved");
	};

	$scope.cancel = function() {
		$modalInstance.dismiss("cancel");
	};
})

.controller("CreateEventCtrl", function($scope, $modalInstance, UserCredentials, dialogEvent) {
	$scope.dialogEvent = dialogEvent;
	$scope.eventAction = "Editing";

	$scope.saveEvent = function(event) {
		$scope.dialogEvent.creator = UserCredentials.getID();
		$scope.dialogEvent.$addEvent();
		$modalInstance.dismiss("created");
	};

	$scope.cancel = function() {
		$modalInstance.dismiss("cancel");
	};
});

