angular.module("MDLeadership", [
	"templates-app",
	"templates-common",
	"titleService"
])

.config(function myAppConfig($routeProvider) {
	$routeProvider.otherwise("/home");
})

.run(function run(titleService) {
	titleService.setSuffix(" | MD Leadership");
})

.controller("AppCtrl", function AppCtrl($scope, $location) {});

