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
                        photo: "img/hover.png"
                    };
                    this.push(placeholder);
                    
                    // marker with PICTURE
                    friend.user.options = {};
                    friend.user.options.zIndex = 2 + friend.friend_id;
                    friend.user.location.latitude = parseFloat(friend.user.location.latitude) + 0.00055;
                    this.push(friend.user);
                }, markers);
                
                //lets add our location to the markers
                var location = {
                    location: user.location,
                    options: {zIndex:10},
                    photo: "img/point.png"
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
            $scope.poller = $interval($scope.refresh, 1000);
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