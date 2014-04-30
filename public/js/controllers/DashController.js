angular.module('starter.controllers', [])

    .controller('DashCtrl', function($scope, connection) {
        $scope.map = {
            center: {
                latitude: 45,
                longitude: -73
            },
            zoom: 8
        };

        $scope.friends = {};

        connection.getUser()
            .success(function(users){
                $scope.friends = users;
            });
    })
;