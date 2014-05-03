angular.module('starter.services', [])
    .value('version', '0.1')

    .service('FindMyFriendsService', ['$http', function($http) {

        this._sendData = function(method, url, data) {
            return $http({
                method: method,
                url: remoteUrl + 'api/v1/' + url,
                data: data
            });
        }

        this.getMe = function () {
            return this._sendData('GET', 'me', {});
        }

        this.login = function(email, pass) {
            return this._sendData('POST', 'login/password', {'email': email, 'password': pass});
        }

        this.logout = function() {
            return this._sendData('POST', 'login/logout');
        }

        this.loginfacebook = function(token) {
            return this._sendData('POST', 'login/facebook?code=' + token, {});
        }
    }])

    .factory('PollerService', ['$http', 'FindMyFriendsService', function($http, FindMyFriendsService){
        var defaultPollingTime = 10000;
        var polls = {};

        return {
            start: function(name, url, pollingTime, callback) {
                // Check to make sure poller doesn't already exist
                if (!polls[name]) {
                    var poller = function() {
                        $http.get(url).then(callback);
                    }
                    poller();
                    polls[name] = setInterval(poller, pollingTime || defaultPollingTime);
                }
            },

            stop: function(name) {
                clearInterval(polls[name]);
                delete polls[name];
            }
        }
    }])

    .factory('MeModel', ['$http', 'FindMyFriendsService', '$filter', '$q', '$interval', function($http, FindMyFriendsService, $filter, $q, $interval){
        var $scope = this;
        $scope.user = null;
        var poller;
        
        return {
            
            
            getFriend: function(id) {
                if ($scope.user != undefined) {
                    var found = $filter('filter')($scope.user.friends, {'friend_id': id});
                    if (found[0] !== undefined) {
                        return found[0];
                    }
                }
            },
            
            reset: function() {
                $scope.user = null;  
            },
            
            getMe: function() {
                console.log('oa');
                var deferred = $q.defer();
                if ($scope.user) {
                    deferred.resolve($scope.user);
                }
                else {
                    FindMyFriendsService.getMe()
                     .then(function (data) {
                         // TODO check errors
                        $scope.user = data.data;
                        console.log('done!');
                        deferred.resolve($scope.user);
                     });
                }
                return deferred.promise;
            }
        }
    }])

    .service('loginFacebook', ['FindMyFriendsService', '$window', function(FindMyFriendsService, $window){
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
            FindMyFriendsService.loginfacebook(response.authResponse.accessToken)
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