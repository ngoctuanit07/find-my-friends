angular.module('starter.controllers')

    .controller('AddFriendsCtrl', function($scope, MeModel, FindMyFriendsService) {
        $scope.user = null;
        $scope.friends = {}

        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.friends = user.friends;
        })

        FindMyFriendsService.getSocialFriends().then(function(socialFriends) {
            console.log(socialFriends);
            $scope.friends = socialFriends;
        })
    })
;