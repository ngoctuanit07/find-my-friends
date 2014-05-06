angular.module('starter.controllers')

    .controller('AddFriendsCtrl', function($scope, MeModel, FindMyFriendsService) {
        $scope.user = null;
        $scope.socialFriends = {}

        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.socialFriends = user.friends;
        })

        FindMyFriendsService.getSocialFriends().then(function(socialFriends) {
            $scope.socialFriends = socialFriends.data;
        })
    })
;