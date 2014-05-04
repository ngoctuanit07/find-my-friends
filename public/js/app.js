// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers', 'starter.services', 'google-maps'])

    .run(function($ionicPlatform) {
        $ionicPlatform.ready(function() {
            if(window.StatusBar) {
                // org.apache.cordova.statusbar required
                StatusBar.styleDefault();
            }
        });
    })

    .config(function($stateProvider, $urlRouterProvider) {

        $stateProvider
            .state('login', {
                url: "/login",
                templateUrl: "templates/login.html",
                controller: 'LoginCtrl'
            })
            .state('forgotpassword', {
                url: "/forgot-password",
                templateUrl: "templates/forgot-password.html"
            })
            .state('home', {
                url: "/home",
                templateUrl: "templates/home.html",
                controller: 'HomeCtrl'
            })
            .state('friend', {
                url: "/friend/:friendId",
                templateUrl: "templates/friend-detail.html",
                controller: "FriendDetailCtrl"
            })
            .state('me', {
                url: "/me",
                templateUrl: "templates/me.html",
                controller: "MeCtrl"
            })
            .state('map', {
                url: "/map",
                templateUrl: "templates/map.html",
                controller: "MapCtrl"
            })
        ;

        $urlRouterProvider.otherwise("/login");

    });

