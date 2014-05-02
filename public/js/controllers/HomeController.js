angular.module('starter.controllers', [])

    .controller('HomeCtrl', function($scope, connection) {
        $scope.friends = {};
        $scope.user = null;

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

        connection.getUser()
            .success(function(user){
                $scope.user = user;
                $scope.friends = user.friends;

                $scope.map = {
                    center: {
                        latitude: user.location.latitude,
                        longitude: user.location.longitude
                    },
                    zoom: 15
                };
            });

    })
;