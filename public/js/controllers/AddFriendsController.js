angular.module('starter.controllers')

    .controller('AddFriendsCtrl', function($scope, MeModel, FindMyFriendsService, $ionicPopup) {
        $scope.user = null;
        $scope.socialFriends = {}
        $scope.invitedFriends = [];

        $scope.data = {
            isLoading: true
        };

        $scope.errorCallback = function(response) {
            $ionicPopup.alert({
                title: 'Error',
                content: response.message
            });
        }

        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.user.friends.forEach(function(friend) {
                if (friend.user.facebook_uid) {
                    $scope.invitedFriends.push(friend.user.facebook_uid);
                }
            })

            FindMyFriendsService.getSocialFriends().then(function(socialFriends) {
                $scope.socialFriends = socialFriends.data;
                $scope.data.isLoading = false;
            })
        })

        $scope.filterInvited = function(friend) {
            return $scope.invitedFriends.indexOf(parseInt(friend.id)) == -1;
        }

        $scope.inviteFriend = function(friend) {
            FindMyFriendsService.addFacebookFriend(friend.id).then(function() {
                friendIndex = $scope.socialFriends.indexOf(friend);
                $scope.socialFriends.splice(friendIndex, 1);
            })
        }

        $scope.promptEmail = function() {
            $ionicPopup.prompt({
                inputType: 'email',
                inputPlaceholder: 'friend@mail.com',
                title: 'Invite by Email',
                subTitle: 'What\'s your friend\'s email?'
            }).then(function(email) {
                if (email) {
                    FindMyFriendsService.addEmailFriend(email)
                        .success(function() {
                            $ionicPopup.alert({
                                title: 'Invite has been sent to ' + email
                            })
                        })
                        .error($scope.errorCallback);
                }
            });
        };
    })
;