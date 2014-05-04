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

                // TODO handle deleted users
                angular.extend($scope.friends, user.friends);
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