/**
* @Name MD_Leadership
*
* @Description Main app module
*/
var LeadershipModule = angular.module('MD_Leadership', ['dependancies', 'go', 'here']);
// TODO proper app dependancies

LeadershipModule.config(function($routeProvider) {
	$routeProvider
	.when("/home",
			{
				controller: "homeController",
				templateUrl: "app/home/home.html"
			})
	.when("/events",
			{
				controller: "eventsController",
				templateUrl: "app/events/events.html"
			})
	.when("/calendars",
			{
				controller: "calendarController",
				templateUrl: "app/calendars/calendars.html"
			})
	.when("/account",
			{
				controller: "accountController",
				templateUrl: "app/account/account.html"
			})
	.when("/contact", 
			{
				controller: "contactController",
				templateUrl: "app/contact/contact.html"
			})
	.otherwise({ redirectTo: "/home" });
	// TODO proper routing locations
});
