angular.module('starter.services')

    .service('LoginFacebook', ['FindMyFriendsService', '$window', '$state', '$ionicLoading', '$ionicPopup',
        function(FindMyFriendsService, $window, $state, $ionicLoading, $ionicPopup){
            var $scope = this;

            this.facebook = function() {
                $window.FB.getLoginStatus(function (response) {
                    if (response.authResponse) {
                        $scope._login(response);
                    } else {
                        $window.FB.login(function (response) {
                            if (response.authResponse) {
                                $scope._login(response);
                            }
                        }, {scope: 'email'});
                    }
                });
            };

            this._login = function (response) {
                FindMyFriendsService.loginFacebook(response.authResponse.accessToken)
                    .success( function (data) {
                        if(data.status == "ok") {
                            $state.go("home");
                        }
                        return false;
                    })
                    // TODO transform into promise, would be better to handle this in controller
                    .error( function(response) {
                        $ionicLoading.hide();
                        $ionicPopup.alert({
                            title: 'Error',
                            content: response.message
                        });
                    });
            };

        }])