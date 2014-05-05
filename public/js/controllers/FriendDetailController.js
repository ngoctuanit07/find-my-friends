angular.module('starter.controllers')

    .controller('FriendDetailCtrl', function($scope, $stateParams, MeModel, FindMyFriendsService) {
        $scope.user = null;
        $scope.markers = [];
        $scope.address = '';
        $scope.distance = '';
        $scope.duration = '';

        // google maps object that controls the map
        $scope.map = {
            center: {
                latitude: 41.1781072,
                longitude: -8.5955717
            },
            zoom: 10,
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
                if (response.data.results.length > 0)
                    $scope.address = response.data.results[0].formatted_address;
            })
        }

        $scope.fetch = function() {
            return MeModel.getMe().then(function(user){
                $scope.user = user;
                $scope.markers = MeModel.getMarkers();

                FindMyFriendsService.getDistance($scope.friend.user.id)
                    .success(function(response) {
                        console.log(response);

                        if (response.rows.length > 0) {
                            $scope.distance = response.rows[0].elements[0].distance.text;
                            $scope.duration = response.rows[0].elements[0].duration.text;
                        }
                    })
            });
        };

        $scope.fetch();
    })
;