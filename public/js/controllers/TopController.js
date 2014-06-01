angular.module('starter.controllers')
    .controller('TopCtrl', function($location) {
        $location.path("/home");
    });