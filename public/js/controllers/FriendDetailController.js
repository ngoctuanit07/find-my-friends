angular.module('starter.controllers')

    .controller('FriendDetailCtrl', function($scope, $stateParams, MeModel) {
        $scope.friend = MeModel.getFriend($stateParams.friendId);
        
    })
;