angular.module('starter.controllers')

    .controller('MeCtrl', function($scope, MeModel, $filter) {
        $scope.user = null;
        $scope.friends = {}


        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.friends = user.friends;
        })
    })
;