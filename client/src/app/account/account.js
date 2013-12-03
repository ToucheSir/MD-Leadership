angular.module("MDLeadership.account", [])
.controller("AccountCtrl", function($scope) {
  $scope.editAccount = function() {
    $scope.editing = true;
  };
});