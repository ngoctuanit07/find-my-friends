angular.module('starter.controllers')

    .controller('AddFriendsCtrl', function($scope, MeModel, FindMyFriendsService, $filter) {
        $scope.user = null;
        $scope.socialFriends = {}
        $scope.invitedFriends = [];

        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.user.friends.forEach(function(friend) {
                if (friend.user.facebook_uid) {
                    $scope.invitedFriends.push(friend.user.facebook_uid);
                }
            })

            FindMyFriendsService.getSocialFriends().then(function(socialFriends) {
                $scope.socialFriends = socialFriends.data;
            })
        })

        $scope.filterInvited = function(friend) {
            return $scope.invitedFriends.indexOf(parseInt(friend.id)) == -1;
        }

        $scope.inviteFriend = function(friendIndex) {
            var friend = $scope.socialFriends[friendIndex];

            FindMyFriendsService.addFacebookFriend(friend.id).then(function() {
                $scope.socialFriends.splice(friendIndex, 1);
            })
        }
    })
;