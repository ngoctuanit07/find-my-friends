angular.module('starter.controllers', [])

.controller('DashCtrl', function($scope, connection, loginFacebook) {
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

        $scope.facebook = function() {
            loginFacebook.facebook();
        }
})

.controller('FriendsCtrl', function($scope, Friends) {
  $scope.friends = Friends.all();
})

.controller('FriendDetailCtrl', function($scope, $stateParams, Friends) {
  $scope.friend = Friends.get($stateParams.friendId);
})

.controller('AccountCtrl', function($scope) {
});
