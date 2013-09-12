angular.module("MDLeadership", [
	"ngRoute",
	"templates-app",
	"templates-common",
	"authModules",
	"titleService",
	"MDLeadership.home",
	"MDLeadership.about",
	"MDLeadership.events",
	"MDLeadership.calendars"
])

.config(function($routeProvider) {
	$routeProvider.when("/login", {
		templateUrl: "login/login.tpl.html", controller: "LoginCtrl"
	})
	.otherwise({ redirectTo: "/home" });
})

.run(function(titleService, $location) {
	titleService.setSuffix(" | MD Leadership");
})

.controller("AppCtrl", function($scope, $http, titleService) {

	$scope.currentUser = {name: "Bob", id: 123456};

	$scope.allResolved = function() {
		return $http.pendingRequests.length > 0;
	};

	$scope.tabActive = function(tabName) {
		return titleService.getTitle() === tabName + titleService.getSuffix();
	};
})

.controller("LoginCtrl", function($scope, $location, UserCredentials, titleService, User) {
	titleService.setTitle("Login");

	$scope.login = function() {
		UserCredentials.setCredentials($scope.userID, $scope.userPass);

		var user = User.getUser({userID: $scope.userID});
		user.$promise.then(function() {
			$location.path("/home");
		}, function() {
			alert("Login Failed");
		});
	};

});
