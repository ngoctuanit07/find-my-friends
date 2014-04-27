'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
  value('version', '0.1')

    .service('connection', ['$http', function($http) {

        this._sendData = function(method, url, data) {
            return $http({
               method: method,
               url: url,
               data: data
            });
        }

        this.login = function(email, pass) {
            return this._sendData('POST', 'login/password', {'email': email, 'password': pass});
        }

        this.loginfacebook = function(token) {
            return this._sendData('GET', 'login?code=' + token, {});
        }
    }])


    .service('loginFacebook', ['$window', function($window){
        this.facebookInited = false;

        this.facebook = function() {
            $window.FB.getLoginStatus(function (response) {
                if (response.authResponse) {
                    this._login(response);
                } else {
                    $window.FB.login(function (response) {
                        if (response.authResponse) {
                            this._login(response);
                        }
                    }, {scope: 'email'});
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
                        } catch (e) {
                            //
                        }
                    });
            });
        };

        this.initFacebook = function() {
            if( ! this.facebookInited ) {
                $window.FB.init({
                    appId      : '694514773928221',
                    status     : true, // check login status
                    cookie     : true, // enable cookies to allow the server to access the session
                    oauth      : true, // enable OAuth 2.0
                    xfbml      : true  // parse XFBML
                });
                this.facebookInited = true;
            }
        };
    }])

;
