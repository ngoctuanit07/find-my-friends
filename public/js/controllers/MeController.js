angular.module('starter.controllers')

    .controller('MeCtrl', function($scope, MeModel, FindMyFriendsService, $state) {
        $scope.user = null;
        $scope.friends = {}


        $scope.fetch = function() {
            MeModel.getMe().then(function(user){
                $scope.user = user;
                $scope.friends = user.friends;
            });
        }

        $scope.refresh = function() {
            MeModel.reset();
            $scope.fetch();
        }

        $scope.refresh();
        
        $scope.logOut = function() {
            MeModel.reset();
            FindMyFriendsService.logout().then(function() {
                FB.logout(function(response) {
                    $state.go('login');
                });

            });
        };

        $scope.stopSharingLocation = function(friendId) {
            FindMyFriendsService.stopSharingLocation(friendId).then(function() {
                $scope.refresh();
            })
        }

        $scope.startSharingLocation = function(friendId) {
            FindMyFriendsService.startSharingLocation(friendId).then(function() {
                $scope.refresh();
            })
        }

        $scope.acceptFriendRequest = function(friendId) {
            FindMyFriendsService.acceptFriendRequest(friendId).then(function() {
                $scope.refresh();
            })
        }

        $scope.declineFriendRequest = function(friendId) {
            FindMyFriendsService.blockFriend(friendId).then(function() {
                $scope.refresh();
            })
        }

        $scope.unblockFriend = function(friendId) {
            FindMyFriendsService.unblockFriend(friendId).then(function() {
                $scope.refresh();
            })
        }
    })
;