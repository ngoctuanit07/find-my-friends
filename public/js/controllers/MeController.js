angular.module('starter.controllers')

    .controller('MeCtrl', function($scope, MeModel, FindMyFriendsService, $state, $ionicPopup, $ionicLoading) {
        $scope.user = null;
        $scope.friends = {}

        $scope.showLoading = function(content) {
            $ionicLoading.show({
                content: content,
                animation: 'fade-in',
                showBackdrop: false,
                maxWidth: 200,
                showDelay: 0
            });
        }

        $scope.updateFriend = function(friendId, updatedFriend) {
            for(var i = 0; i < $scope.friends.length; i += 1) {
                if($scope.friends[i]['friend_id'] === friendId) {
                    $scope.friends[i] = updatedFriend;
                    MeModel.reset();
                    return true;
                }
            }
            return false;
        }

        $scope.fetch = function() {
            MeModel.getMe().then(function(user){
                $scope.user = user;
                $scope.friends = user.friends;
                $ionicLoading.hide();
            }, function() {
                $state.go('login');
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
                $state.go('login');
            });
        };

        $scope.stopSharingLocation = function(friendId) {
            $scope.showLoading('Stopping location share. Please wait...');
            FindMyFriendsService.stopSharingLocation(friendId)
                .success(function(result) {
                    $scope.updateFriend(friendId, result);
                    $ionicLoading.hide();
                })
        }

        $scope.startSharingLocation = function(friendId) {
            $scope.showLoading('Starting location share. Please wait...');
            FindMyFriendsService.startSharingLocation(friendId)
                .success(function(result) {
                    $scope.updateFriend(friendId, result);
                    $ionicLoading.hide();
                })
        }

        $scope.acceptFriendRequest = function(friendId) {
            $scope.showLoading('Accepting friend request. Please wait...');
            FindMyFriendsService.acceptFriendRequest(friendId)
                .success(function(result) {
                    $scope.updateFriend(friendId, result);
                    $ionicLoading.hide();
                })
        }

        $scope.declineFriendRequest = function(friendId) {
            $scope.showLoading('Declining friend request. Please wait...');
            FindMyFriendsService.blockFriend(friendId)
                .success(function(result) {
                    $scope.updateFriend(friendId, result);
                    $ionicLoading.hide();
                })
        }

        $scope.unblockFriend = function(friendId) {
            $ionicPopup.confirm({
                title: 'Unblock Friend',
                content: 'Are you sure you want to unblock this friend? He will be able to send you requests.',
                okType: 'button-balanced',
                okText: 'Unblock'
            }).then(function(result) {
                if(result) {
                    $scope.showLoading('Unblocking friend. Please wait...');
                    FindMyFriendsService.unblockFriend(friendId)
                        .success(function(updatedFriend) {
                            $scope.updateFriend(friendId, updatedFriend);
                            $ionicLoading.hide();
                        })
                }
            });
        }
    })
;