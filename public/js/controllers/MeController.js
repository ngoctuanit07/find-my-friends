angular.module('starter.controllers')

    .controller('MeCtrl', function($scope, MeModel) {
        $scope.user = null;
        $scope.friends = {}


        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.friends = user.friends;
        })
    })
;