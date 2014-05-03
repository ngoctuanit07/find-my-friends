angular.module('starter.controllers')

    .controller('LoginCtrl', function($scope, $state, FindMyFriendsService, loginFacebook) {
        $scope.facebook = function() {
            loginFacebook.facebook();
        }

        $scope.login = function(user) {
            console.log('Login', user.email);
            FindMyFriendsService.login(user.email, user.password);
            $state.go('home');
        };

        $scope.logout = function() {
            FindMyFriendsService.logout();
            $state.go('login');
        }
    })
;