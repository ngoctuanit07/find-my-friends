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
            }
        };

        /*
        $scope.$watch('location', function() {
            $scope.map.control.refresh({
                latitude: $scope.location.latitude, 
                longitude: $scope.location.longitude});
        });
        */

        $scope.refresh = function() {
            MeModel.reset();
            $scope.fetch();
        }

        $scope.fetch = function() {
            return MeModel.getMe().then(function(user){
                $scope.user = user;

                // TODO handle deleted users
                angular.extend($scope.friends, user.friends);
                
                var markers = [];
                angular.forEach($scope.friends, function(friend){
                    // image placeholder that has an arrow
                    var placeholder = {
                        friend_id: friend.friend_id,
                        location: {
                            latitude: friend.user.location.latitude,
                            longitude: friend.user.location.longitude,
                        } ,
                        options: {zIndex:1 + friend.friend_id},
                        photoSmall: "img/hover.png"
                    };
                    this.push(placeholder);
                    
                    // marker with PICTURE - needs a copy because otherwise JS will change user.friends.user object
                    //   which changes it for everyone - and we need to hack the location =)
                    var friendUser = angular.copy(friend.user);
                    friendUser.options = {};
                    friendUser.options.zIndex = 2 + friend.friend_id;
                    friendUser.location.latitude = parseFloat(friendUser.location.latitude) + 0.00055;
                    friendUser.photoSmall = friendUser.photo + '?width=15&height=15';
                    this.push(friendUser);
                }, markers);
                
                //lets add our location to the markers
                var location = {
                    location: user.location,
                    options: {zIndex:10},
                    photoSmall: "img/point.png"
                };
                markers.push(location);
                angular.extend($scope.markers, markers);

                $scope.location = user.location;
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