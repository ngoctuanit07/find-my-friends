angular.module('starter.controllers')

    .controller('MeCtrl', function($scope, MeModel) {
        $scope.user = null;

        MeModel.getMe().then(function(user){
            $scope.user = user;
        })
    })
;