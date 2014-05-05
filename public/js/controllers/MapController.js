angular.module('starter.controllers')

    .controller('MapCtrl', function($scope, MeModel, $state) {
        $scope.user = null;
        $scope.markers = [];

        // google maps object that controls the map
        $scope.map = {
            center: {
                latitude: 40.175488,
                longitude: -8.5978381
            },
            zoom: 10,
            control: {},
            dragging: true,
            options: {
                panControl: false,
                streetViewControl: false,
                mapTypeControl: true,
                scaleControl: true,
                zoomControl: true, 
                rotateControl: true,
                scrollwheel: true
            },
            infoWindowWithCustomClass: {
                options: {
                    boxClass: 'custom-info-window'
                }
            }
        };

         MeModel.getMe().then(function(user){
            $scope.user = user;
            $scope.markers = MeModel.getMarkers();

            _.each($scope.markers, function (marker) {
                marker.onClicked = function () {
                    $state.go("friend", marker.id);
                };
            });

        });
    })
;