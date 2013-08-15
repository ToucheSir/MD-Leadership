angular.module("MDLeadership", [
	"ngRoute",
	"templates-app",
	"templates-common",
	"MDLeadership.home",
	"MDLeadership.about"
])

.config(function myAppConfig($routeProvider) {
	$routeProvider.otherwise({ redirectTo: "/home" });
})

.run(function run(titleService) {
	titleService.setSuffix(" | MD Leadership");
})

.controller("AppCtrl", function AppCtrl($scope, $location) {
	$scope.noop = "foo";
});