angular.module('starter.controllers', [])

    .controller('HomeCtrl', function($scope, FindMyFriendsService, MeModel, $interval, $filter, $state) {

        $scope.user = null;
        $scope.location = {};
        $scope.friends = {};
        $scope.markers = [];
        $scope.poller = null;

        // google maps object that controls the map
        $scope.map = {
            center: {
                latitude: 40.175488,
                longitude: -8.5978381
            },
            zoom: 10,
            control: {},
            dragging: false,
            options: {
                panControl: false,
                streetViewControl: false
            },
            infoWindowWithCustomClass: {
                options: {
                    boxClass: 'custom-info-window'
                }
            }
        };

        /*
         $scope.$watch('location', function() {
         $scope.map.control.refresh({
         latitude: $scope.location.latitude,
         longitude: $scope.location.longitude});
         });
         */

        $scope.saveLocation = function(location) {
            $scope.location = location.coords;
            // push to api
        }

        $scope.refresh = function() {
            MeModel.reset();
            $scope.fetch();

            navigator.geolocation.getCurrentPosition($scope.saveLocation);
        }

        $scope.fetch = function() {
            return MeModel.getMe().then(function(user){
                $scope.user = user;

                // TODO handle deleted users, and calculate distance if wasn't cached
                angular.extend($scope.friends, user.friends);

                var markers = [];
                angular.forEach($scope.friends, function(friend){
                    // calculate distance
                    friend.user.distance = getDistanceInKm($scope.user.location, friend.user.location);
                    friend.user.showWindow = true;
                    friend.user.photoSmall = 'img/empty.gif';

                    // little photo for map
                    friend.user.photoThumb = friend.user.photo + '?width=15&height=15';
                    this.push(friend.user);
                }, markers);

                //lets add our location to the markers
                user.photoSmall = "img/point.png";
                markers.push(user);
                angular.extend($scope.markers, markers);

                $scope.location = $scope.user.location;
            }, function(data){
                if (data.status == "error") {
                    $state.go('login');
                } else {
                    // unknown error ?!
                    console.log(data);
                }

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
