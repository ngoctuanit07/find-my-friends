angular.module('starter.services')

    .factory('MeModel', ['$http', 'FindMyFriendsService', '$filter', '$q', '$interval', 'GeoMath', function($http, FindMyFriendsService, $filter, $q, $interval, GeoMath){
    var $scope = this;
    $scope.user = null;

    return {
        getFriend: function(id) {
            if ($scope.user != undefined) {
                var found = $filter('filter')($scope.user.friends, {'friend_id': id});
                if (found[0] !== undefined) {
                    return found[0];
                }
            }
        },

        getMarkers: function() {
            if ($scope.user != undefined) {
                var markers = [];
                angular.forEach($scope.user.friends, function(friend){
                    if (friend.user.location) {
                        // calculate distance
                        friend.user.distance = GeoMath.getDistanceInKm($scope.user.location, friend.user.location);
                        friend.user.showWindow = true;
                        friend.user.photoSmall = 'img/empty.gif';

                        // little photo for map
                        friend.user.photoThumb = friend.user.photo + '?width='+ window.devicePixelRatio*32 +'&height=' + window.devicePixelRatio*32;
                        this.push(friend.user);
                    }
                }, markers);

                //lets add our location to the markers
                $scope.user.photoSmall = "img/point.png";
                markers.push($scope.user);

                return markers;
            }
        },

        reset: function() {
            $scope.user = null;
        },

        getMe: function() {
            var deferred = $q.defer();
            if ($scope.user) {
                deferred.resolve($scope.user);
            }
            else {
                FindMyFriendsService.getMe()
                    .then(function (data) {
                        // success
                        $scope.user = data.data;
                        deferred.resolve($scope.user);
                    }, function (data) {
                        // error!
                        deferred.reject(data.data);
                    });
            }
            return deferred.promise;
        }
    }
}])