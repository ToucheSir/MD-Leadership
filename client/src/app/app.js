/* jshint undef: false */
angular.module("MDLeadership", [
	"ngRoute",
	"templates-app",
	"templates-common",
	"ui.bootstrap",
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

.run(function($rootScope, $location, AccountService, titleService) {
	var whiteListed = "/login";

	/* jshint unused: false */
	$rootScope.$on("$locationChangeStart", function(event, next, current) {
		if ($location.path() !== whiteListed && !AccountService.isLoggedIn()) {
			$location.path("/login");
			return;
		}
	});

	titleService.setSuffix(" | MD Leadership");
})

.controller("AppCtrl", function($scope, $http, titleService) {

	$scope.allResolved = function() {
		return $http.pendingRequests.length > 0;
	};

	$scope.tabActive = function(tabName) {
		return titleService.getTitle() === tabName + titleService.getSuffix();
	};
})

.controller("LoginCtrl", function($scope, $location, titleService, AccountService) {
	titleService.setTitle("Login");


	$scope.login = function () {
		var credentials = {
			id: $scope.loginUser.id,
			password: $scope.loginUser.pass
		};

		AccountService.login(credentials).then(function () {
			$location.path("/home");
		});
	};

	$scope.logout = function() {
		AccountService.logout().then(function () {
			$location.path("/login");
		});
	};

	$scope.signup = function() {
		if ($scope.signupForm.$invalid) {
			$scope.signupForm.$dirty = true;
		}
	};

	$scope.inputInvalid = function(element) {
		return element.$invalid && element.$dirty;
	};

	$scope.getFormStyle = function(element) {
		return $scope.inputInvalid(element) ? "has-error" : "";
	};

});