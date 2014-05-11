angular.module('starter.controllers')

    .controller('LoginCtrl', function($scope, $state, FindMyFriendsService, LoginFacebook, MeModel, $ionicLoading, $ionicPopup) {

        $scope.showLoading = function(message) {
            $ionicLoading.show({
                content: message,
                animation: 'fade-in',
                showBackdrop: false,
                maxWidth: 200,
                showDelay: 0
            });
        }

        $scope.facebook = function() {
            $scope.showLoading('Logging in with facebook, please wait a moment.');
            LoginFacebook.facebook();
        }

        $scope.login = function(user) {
            if( typeof user !== "undefined" ) {
                $scope.showLoading('Logging in, please wait a moment.');

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

        $scope.register = function(user) {
            if( typeof user !== "undefined" ) {
                $scope.showLoading('Registering account, please wait a moment.');

                FindMyFriendsService.register(user.email, user.name, user.password)
                    .then(function(){
                        $ionicLoading.hide();
                        FindMyFriendsService.login(user.email, user.password).then(function() {
                            $state.go('home');
                        })
                    },
                    function() {
                        $ionicLoading.hide();
                        $ionicPopup.alert({
                            title: 'Error',
                            content: 'Email already registered.'
                        });
                    });
            }
        }

        // if already logged in, move to home - this could be a login/ping or something instead of me,
        // but Me also works because it's then cached for homecontroller
        MeModel.getMe().then(function(user){
            $state.go('home');
        });
    })
;