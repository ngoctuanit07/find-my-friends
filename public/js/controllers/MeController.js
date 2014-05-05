angular.module('starter.controllers')

    .controller('MeCtrl', function($scope, MeModel, FindMyFriendsService, $state) {
        $scope.user = null;
        $scope.friends = {}


        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.friends = user.friends;
        });
        
        $scope.logOut = function() {
            MeModel.reset();
            FindMyFriendsService.logout().then(function() {
                $state.go('login');
            });
        };
    })
;