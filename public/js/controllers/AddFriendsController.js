angular.module('starter.controllers')

    .controller('AddFriendsCtrl', function($scope, MeModel) {
        $scope.user = null;
        $scope.friends = {}


        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.friends = user.friends;
        })

    })
;