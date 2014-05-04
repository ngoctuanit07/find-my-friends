angular.module('starter.controllers')

    .controller('LoginCtrl', function($scope, $state, FindMyFriendsService, loginFacebook, MeModel) {
        $scope.facebook = function() {
            loginFacebook.facebook();
        }

        $scope.login = function(user) {
            console.log('Login', user.email);
            FindMyFriendsService.login(user.email, user.password)
            .success(function(){
                $state.go('home');
            });
        };

        $scope.logout = function() {
            FindMyFriendsService.logout();
            $state.go('login');
        }
        
        // if already logged in, move to home - this could be a login/ping or something instead of me,
        // but Me also works because it's then cached for homecontroller
        MeModel.getMe().then(function(user){
            $state.go('home');
        });
    })
;