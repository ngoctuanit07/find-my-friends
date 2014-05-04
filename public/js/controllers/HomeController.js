angular.module('starter.controllers', [])

    .controller('HomeCtrl', function($scope, FindMyFriendsService, MeModel, $interval, $filter) {

        $scope.user = null;
        $scope.location = {};
        $scope.friends = {};
        $scope.poller = null;

        $scope.map = {
            center: {
                latitude: 41.175488,
                longitude: -8.5978381
            },
            zoom: 15,
            options: {
                shadow: "http://simpleicon.com/wp-content/uploads/map-marker-1.png"
            }
        };

        $scope.$watch('location', function() {
            $scope.map = {

                center: {
                    latitude: $scope.location.latitude,
                    longitude: $scope.location.longitude
                },
                zoom: 15
            };
        });

        $scope.refresh = function() {
            MeModel.reset();
            $scope.fetch();
        }

        $scope.fetch = function() {
            MeModel.getMe().then(function(user){
                $scope.user = user;

                // TODO handle deleted users, and calculate distance if wasn't cached
                angular.extend($scope.friends, user.friends);

                angular.forEach($scope.friends, function(friend) {
                    friend.user.distance = getDistanceInKm($scope.user.location, friend.user.location)
                });

                $scope.location = user.location;
            });
        }

        $scope.start = function() {
            $scope.poller = $interval($scope.refresh, 10000);
        };

        $scope.stop = function() {
            $interval.cancel($scope.poller);
            $scope.poller = null;
        };

        $scope.fetch();
        $scope.start();

        $scope.$on('$destroy', function(e) {
            $interval.cancel($scope.poller);
        });
    })
;

function getDistanceInKm(location1,location2) {
    var R = 6371; // Radius of the earth in km
    var dLat = deg2rad(location2.latitude-location1.latitude);
    var dLon = deg2rad(location2.longitude-location1.longitude);
    var a =
            Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(location1.latitude)) * Math.cos(deg2rad(location2.latitude)) *
                    Math.sin(dLon/2) * Math.sin(dLon/2)
        ;
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c; // Distance in km
    return d;
}

function deg2rad(deg) {
    return deg * (Math.PI/180)
}