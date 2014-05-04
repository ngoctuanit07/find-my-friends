angular.module('starter.controllers')

    .controller('MapCtrl', function($scope, MeModel) {
        $scope.user = null;
        $scope.location = {};
        $scope.friends = {};


        MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.friends = user.friends;
        })

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
    })
;