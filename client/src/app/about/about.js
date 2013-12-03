angular.module("MDLeadership.about", [
  "ngRoute",
  "placeholders",
  "ui.bootstrap",
  "titleService"
])

.config(function($routeProvider) {
  $routeProvider.when("/about", {
    controller: "AboutCtrl",
    templateUrl: "about/about.tpl.html"
  });
})

.controller("AboutCtrl", function($scope, titleService) {
  titleService.setTitle("About");

  // This is simple a demo for UI Boostrap.
  $scope.dropdownDemoItems = [
    "The first choice!",
    "And another choice for you.",
    "but wait! A third!"
  ];
});