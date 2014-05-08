angular.module('starter.controllers')

    .controller('FriendDetailCtrl', function($scope, $stateParams, MeModel, FindMyFriendsService, $ionicPopup, $ionicLoading, GeoMath) {
        $scope.user = null;
        $scope.markers = [];
        $scope.address = false;
        $scope.walking = false;
        $scope.driving = false;
        $scope.timeSince = GeoMath.timeSince;

        // google maps object that controls the map
        $scope.map = {
            center: {
                latitude: 41.1781072,
                longitude: -8.5955717
            },
            zoom: 1,
            control: {},
            dragging: false,
            options: {
                panControl: false,
                streetViewControl: false,
                mapTypeControl: false,
                scaleControl: false,
                zoomControl: false
            },
            infoWindowWithCustomClass: {
                options: {
                    boxClass: 'custom-info-window'
                }
            }
        };

        $scope.friend = MeModel.getFriend($stateParams.friendId);

        if ($scope.friend.user.location) {
            $scope.map.center.latitude = $scope.friend.user.location.latitude;
            $scope.map.center.longitude = $scope.friend.user.location.longitude;
            $scope.map.zoom = 16;

            FindMyFriendsService.getAddress($scope.friend.user.location).then(function(response) {
                if (response.data.results.length > 0) {
                    $scope.address = {}
                    $scope.address.text = response.data.results[0].formatted_address;
                    $scope.address.url = "http://maps.google.com/maps?" +
                        "&q=" + $scope.friend.user.location.latitude + "," + $scope.friend.user.location.longitude +
                        "&ll=" + $scope.friend.user.location.latitude + "," + $scope.friend.user.location.longitude;

                }
            })
        }

        $scope.fetch = function() {
            return MeModel.getMe().then(function(user){
                $scope.user = user;
                $scope.markers = MeModel.getMarkers();

                FindMyFriendsService.getDistance($scope.friend.user.id, 'walking')
                    .success(function(response) {
                        if (typeof response.rows !== 'undefined' && response.rows.length > 0) {
                            $scope.walking = {};
                            $scope.walking.distance = response.rows[0].elements[0].distance.text;
                            $scope.walking.duration = response.rows[0].elements[0].duration.text;
                            $scope.walking.url = "http://maps.google.com/maps?" +
                                "&saddr=" + $scope.user.location.latitude + "," + $scope.user.location.longitude +
                                "&daddr=" + $scope.friend.user.location.latitude + "," + $scope.friend.user.location.longitude +
                                "&dirflg=w";
                        }
                    });

                FindMyFriendsService.getDistance($scope.friend.user.id, 'driving')
                    .success(function(response) {
                        if (typeof response.rows !== 'undefined' && response.rows.length > 0) {
                            $scope.driving = {};
                            $scope.driving.distance = response.rows[0].elements[0].distance.text;
                            $scope.driving.duration = response.rows[0].elements[0].duration.text;
                            $scope.driving.url = "http://maps.google.com/maps?" +
                                "&saddr=" + $scope.user.location.latitude + "," + $scope.user.location.longitude +
                                "&daddr=" + $scope.friend.user.location.latitude + "," + $scope.friend.user.location.longitude +
                                "&dirflg=d";
                        }
                    });
            });
        };

        $scope.showLoading = function(content) {
            $ionicLoading.show({
                content: content,
                animation: 'fade-in',
                showBackdrop: false,
                maxWidth: 200,
                showDelay: 0
            });
        }

        $scope.blockFriend = function(friendId) {
            $ionicPopup.confirm({
                title: 'Block Friend',
                content: 'Are you sure you want to block this friend? His requests will no longer be received.',
                okType: 'button-assertive',
                okText: 'Block'
            }).then(function(result) {
                if(result) {
                    $scope.showLoading('Blocking friend. Please wait...');
                    FindMyFriendsService.blockFriend(friendId)
                        .then(function(response) {
                            $scope.friend = response.data;
                            MeModel.reset();
                            $ionicLoading.hide();
                        })
                }
            });
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
                        .then(function(response) {
                            $scope.friend = response.data;
                            MeModel.reset();
                            $ionicLoading.hide();
                        })
                }
            });
        }

        $scope.askForLocation = function(friendId) {
            $scope.showLoading('Asking for friend location. Please wait...');
            FindMyFriendsService.sendShareRequest(friendId)
                .then(function(response) {
                    $scope.friend = response.data;
                    MeModel.reset();
                    $ionicLoading.hide();
                })
        }

        $scope.fetch();
    })
;