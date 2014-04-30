angular.module('starter.controllers')

    .controller('LoginCtrl', function($scope, loginFacebook) {
        $scope.facebook = function() {
            loginFacebook.facebook();
        }
    })
;