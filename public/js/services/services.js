angular.module('starter.services', [])

/**
 * A simple example service that returns some data.
 */
.factory('Friends', function() {
  // Might use a resource here that returns a JSON array

  // Some fake testing data
  var friends = [
    { id: 0, name: 'Scruff McGruff' },
    { id: 1, name: 'G.I. Joe' },
    { id: 2, name: 'Miss Frizzle' },
    { id: 3, name: 'Ash Ketchum' }
  ];

  return {
    all: function() {
      return friends;
    },
    get: function(friendId) {
      // Simple index lookup
      return friends[friendId];
    }
  }
});

angular.module('myApp.services', []).
    value('version', '0.1')

    .service('connection', ['$http', function($http) {

        this._sendData = function(method, url, data) {
            return $http({
                method: method,
                url: 'api/v1/' + url,
                data: data
            });
        }

        this.getUser = function () {
            return this._sendData('GET', 'me', {});
        }

        this.login = function(email, pass) {
            return this._sendData('POST', 'login/password', {'email': email, 'password': pass});
        }

        this.logout = function() {
            return this._sendData('POST', 'log')
        }

        this.loginfacebook = function(token) {
            return this._sendData('POST', 'login/facebook?code=' + token, {});
        }
    }])


    .service('loginFacebook', ['connection', '$window', function(connection, $window){
        var $scope = this;

        this.facebook = function() {
            $window.FB.getLoginStatus(function (response) {
                if (response.authResponse) {
                    $scope._login(response);
                } else {
                    $window.FB.login(function (response) {
                        if (response.authResponse) {
                            $scope._login(response);
                        }
                    }, {scope: ['email']});
                }
            });
        };

        this._login = function (response) {
            connection.loginfacebook(response.authResponse.accessToken)
                .success( function (data) {
                    angular.forEach(data, function (html, element) {
                        try {
                            // resposta ao teu codigo
                            console.log("fiz login!");
                            console.log(html);
                        } catch (e) {
                            //
                        }
                    });
                });
        };
    }])

;