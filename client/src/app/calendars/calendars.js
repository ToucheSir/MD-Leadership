/**
* MDLeadership.calendars Module
*
* Description
*/
angular.module("MDLeadership.calendars", [
	"ngRoute",
	"titleService",
	"resourceConfig",
	"eventResource",
	"userResource",
	"ui.bootstrap"
])

.config(function($routeProvider) {
	$routeProvider.when("/calendars", {
		controller: "CalendarsCtrl",
		templateUrl: "calendars/calendars.tpl.html"
	});
})

.controller("CalendarsCtrl", function($scope, titleService) {
	titleService.setTitle("Calendars");
});