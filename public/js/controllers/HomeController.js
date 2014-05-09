angular.module('starter.controllers', [])

    .controller('HomeCtrl', function($scope, FindMyFriendsService, MeModel, $interval, $filter, $state, GeoMath, $ionicLoading, push) {

        push.registerPush(function (result) {
            console.log('aaaa');
            console.log(result);
            if (result.type === 'registration') {
                localStorage.setItem('device_id', result.id);
                localStorage.setItem('device', result.device);

                FindMyFriendsService.registerDevice('appNameAndroid', result.id)
                    .then(function() {
                        console.log('registered')    
                    }, function() {
                        console.log('oops nao registado');
                    });
            }
             
            if (result.type === 'message') {
                $scope.showLoading(result.message);
             }
        });

        $ionicLoading.hide();
        $scope.user = null;
        $scope.friends = [];
        $scope.markers = [];
        $scope.poller = null;
        $scope.navPoller = null;
        $scope.timeSince = GeoMath.timeSince;

        // google maps object that controls the map
        $scope.map = {
            center: {
                latitude: 41.1781072,
                longitude: -8.5955717
            },
            zoom: 1,
            control: {},
            dragging: false,
            options: {
                panControl: false,
                streetViewControl: false,
                mapTypeControl: false,
                scaleControl: false,
                zoomControl: false
            },
            infoWindowWithCustomClass: {
                options: {
                    boxClass: 'custom-info-window'
                }
            }
        };

        $scope.saveLocation = function(newLocation) {
            // because our location is inside the markers array, we need to find "us" and update the location right away
            userObjectInMarkers = $filter('filter')($scope.markers, {'photoSmall': "img/point.png"})[0];
            // we need to use $scope.$apply because angular has no way to know that markers was updated
            // because saveLocation() is called async - so we help angular check the variables!
            $scope.$apply(function () {
                userObjectInMarkers.location = newLocation.coords;
            });
            
            // now let's send this to the api
            FindMyFriendsService.updateLocation(newLocation.coords);
        }
        
        $scope.errorLocation = function(error) {
            console.log('code: '    + error.code    + '\n' +
            'message: ' + error.message + '\n');
        }

        $scope.refreshPosition = function() {
            navigator.geolocation.getCurrentPosition($scope.saveLocation, $scope.errorLocation, {
                maximumAge: 30000,
                timeout: 5000,
                enableHighAccuracy: false
            });
        }

        $scope.refresh = function() {
            MeModel.reset();
            $scope.fetch();
        }

        $scope.fetch = function() {
            return MeModel.getMe().then(function(user){
                $scope.user = user;

                // TODO handle deleted users, and calculate distance if wasn't cached
                angular.extend($scope.friends, user.friends);

                var markers = MeModel.getMarkers();
                angular.extend($scope.markers, markers);

                $ionicLoading.hide();
                return user;
                
            }, function(data){
                if (data.status == "error") {
                    $state.go('login');
                } else {
                    // unknown error ?!
                    console.log(data);
                }
                return data;
            });
        }

        $scope.start = function() {
            $scope.poller = $interval($scope.refresh, 10000);
            $scope.navPoller = $interval($scope.refreshPosition, 180000);
        };

        $scope.stop = function() {
            $interval.cancel($scope.poller);
            $interval.cancel($scope.navPoller);
            $scope.poller = null;
            $scope.navPoller = null;
        };

        // only start polling if we are logged in
        $scope.fetch().then(function(data){
            if (data.status === undefined) {
                $scope.refreshPosition();
                $scope.start();  
            }
        });
        

        $scope.$on('$destroy', function(e) {
            $interval.cancel($scope.poller);
            $interval.cancel($scope.navPoller);
        });


        $scope.updateFriend = function(friendId, updatedFriend) {
            for(var i = 0; i < $scope.friends.length; i += 1) {
                if($scope.friends[i]['friend_id'] === friendId) {
                    $scope.friends[i] = updatedFriend;
                    return true;
                }
            }
            return false;
        }

        $scope.showLoading = function(content) {
            $ionicLoading.show({
                content: content,
                animation: 'fade-in',
                showBackdrop: false,
                maxWidth: 200,
                showDelay: 0
            });
        }

        $scope.askForLocation = function(friendId) {
            $scope.showLoading('Requesting friend location');
            FindMyFriendsService.sendShareRequest(friendId)
                .success(function(result) {
                    $scope.updateFriend(friendId, result);
                    $ionicLoading.hide();
                });
        }
    })
;


