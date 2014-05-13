angular.module('starter.controllers')

    .controller('FriendDetailCtrl', function($scope, $stateParams, MeModel, FindMyFriendsService, $ionicPopup, $ionicLoading, GeoMath, $state) {
        $scope.user = null;
        $scope.markers = [];
        $scope.address = false;
        $scope.walking = false;
        $scope.driving = false;
        $scope.timeSince = GeoMath.timeSince;
        $scope.places = [];

        $scope.friend = MeModel.getFriend($stateParams.friendId);

        if ($scope.friend.user.location) {

            $scope.map = {
                control: {},
                center: {
                    latitude: parseFloat($scope.friend.user.location.latitude),
                    longitude: parseFloat($scope.friend.user.location.longitude)
                },
                zoom: 16,
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

            FindMyFriendsService.getAddress($scope.friend.user.location).success(function(response) {
                if (response.results.length > 0) {
                    $scope.address = {}
                    $scope.address.text = response.results[0].formatted_address;
                    $scope.address.url = FindMyFriendsService.getMapsUrl($scope.friend.user.location);
                }
            })
        }

        $scope.fetch = function() {
            return MeModel.getMe().then(function(user){
                $scope.user = user;
                $scope.markers = MeModel.getMarkers();

                if ($scope.friend.user.location) {
                    FindMyFriendsService.getDistance($scope.friend.user.id, 'walking')
                        .success(function(response) {
                            if (typeof response.rows !== 'undefined' && response.rows.length > 0) {
                                $scope.walking = {};
                                $scope.walking.distance = response.rows[0].elements[0].distance.text;
                                $scope.walking.duration = response.rows[0].elements[0].duration.text;
                                $scope.walking.url = FindMyFriendsService.getMapsUrlFromTo($scope.user.location, $scope.friend.user.location, 'walking');
                            }
                        });

                    FindMyFriendsService.getDistance($scope.friend.user.id, 'driving')
                        .success(function(response) {
                            if (typeof response.rows !== 'undefined' && response.rows.length > 0) {
                                $scope.driving = {};
                                $scope.driving.distance = response.rows[0].elements[0].distance.text;
                                $scope.driving.duration = response.rows[0].elements[0].duration.text;
                                $scope.driving.url = FindMyFriendsService.getMapsUrlFromTo($scope.user.location, $scope.friend.user.location, 'driving');
                            }
                        });

                    FindMyFriendsService.getNearbyPlaces($scope.friend.user.id)
                        .success(function(data) {
                            var items = data.response.groups[0].items;
                            angular.forEach(items, function(item) {
                                var venueLocation = {
                                    latitude: item.venue.location.lat,
                                    longitude: item.venue.location.lng
                                };
                                $scope.places.push({
                                    name: item.venue.name,
                                    distance: item.venue.location.distance,
                                    url: FindMyFriendsService.getMapsUrlFromTo($scope.user.location, venueLocation, 'driving')
                                })
                            })
                        });
                }
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

        $scope.deleteFriend = function(friendId) {
            $ionicPopup.confirm({
                title: 'Delete Friend',
                content: 'Are you sure you want to delete this friend?',
                okType: 'button-assertive',
                okText: 'Delete'
            }).then(function(result) {
                if(result) {
                    $scope.showLoading('Deleting friend. Please wait...');
                    FindMyFriendsService.deleteFriend(friendId)
                        .then(function(response) {
                            $scope.friend = response.data;
                            MeModel.reset();
                            $ionicLoading.hide();
                            MeModel.reset();
                            $state.go('home');
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