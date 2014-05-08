angular.module('starter.controllers')

    .controller('LoginCtrl', function($scope, $state, FindMyFriendsService, LoginFacebook, MeModel, $ionicLoading) {

        $scope.showLoading = function() {
            $ionicLoading.show({
                content: 'Logging in, please wait a moment.',
                animation: 'fade-in',
                showBackdrop: false,
                maxWidth: 200,
                showDelay: 0
            });
        }

        $scope.facebook = function() {
            $scope.showLoading();
            LoginFacebook.facebook();
        }

        $scope.login = function(user) {
            if( typeof user !== "undefined" ) {
                $scope.showLoading();

                FindMyFriendsService.login(user.email, user.password)
                    .success(function(){
                        $state.go('home');
                    })
                    .error(function() {
                        $ionicLoading.hide();
                    })
            }
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