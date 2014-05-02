angular.module('starter.controllers')

    .controller('LoginCtrl', function($scope, $state, connection, loginFacebook) {
        $scope.facebook = function() {
            loginFacebook.facebook();
        }

        $scope.login = function(user) {
            console.log('Login', user.email);
            connection.login(user.email, user.password);
            $state.go('tabs.home');
        };

        $scope.logout = function() {
            connection.logout();
            $state.go('login');
        }
    })
;