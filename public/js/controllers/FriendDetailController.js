angular.module('starter.controllers')

    .controller('FriendDetailCtrl', function($scope, $stateParams, connection) {
        console.log($scope.user);
        $scope.friend = connection.get($stateParams.friendId);
    })
;