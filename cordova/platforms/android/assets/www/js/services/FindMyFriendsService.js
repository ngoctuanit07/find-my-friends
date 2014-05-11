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

        this._sendParams = function(method, url, params) {
            return $http({
                method: method,
                url: remoteUrl + 'api/v1/' + url,
                params: params
            });
        }

        this.getAddress = function(location) {
            // https://maps.googleapis.com/maps/api/geocode/json?latlng=41.173103,-8.584697&sensor=true
            return $http({
                method: 'GET',
                url: 'https://maps.googleapis.com/maps/api/geocode/json',
                params: {'latlng': location.latitude + ',' + location.longitude,
                    'sensor': 'true' }
            });
        }

        this.getDistance = function(friendId, mode) {
            return this._sendParams('GET', 'friend/distance/' + friendId, {'mode': mode});
        }

        this.getMe = function () {
            return this._sendData('GET', 'me', {});
        }

        this.addFacebookFriend = function($facebookFriendId) {
            return this._sendData('POST', 'friend', {'facebook_uid': $facebookFriendId});
        }

        this.addEmailFriend = function($friendEmail) {
            return this._sendData('POST', 'friend', {'email': $friendEmail});
        }

        this.addFriend = function($friendId) {
            return this._sendData('POST', 'friend', {'id': $friendId});
        }

        this.getSocialFriends = function () {
            return this._sendData('GET', 'me/social-friends', {});
        }

        this.updateLocation = function (location) {
            return this._sendData('PUT', 'me/location', {'location': location});
        }

        this.sendShareRequest = function (friendId) {
            return this._sendData('POST', 'friend/request/' + friendId, {});
        }

        this.stopSharingLocation = function(friendId) {
            return this._sendData('PATCH', 'friend/status/' + friendId, {'status': 'not_sharing'});
        }

        this.startSharingLocation = function(friendId) {
            return this._sendData('PATCH', 'friend/status/' + friendId, {'status': 'sharing'});
        }

        this.acceptFriendRequest = function(friendId) {
            return this._sendData('PATCH', 'friend/status/' + friendId, {'status': 'not_sharing'});
        }

        this.blockFriend = function(friendId) {
            return this._sendData('PATCH', 'friend/status/' + friendId, {'status': 'blocked'});
        }

        this.unblockFriend = function(friendId) {
            return this._sendData('PATCH', 'friend/status/' + friendId, {'status': 'not_sharing'});
        }

        this.deleteFriend = function(friendId) {
            return this._sendParams('DELETE', 'friend', {'id': friendId});
        }

        this.login = function(email, pass) {
            return this._sendData('POST', 'login/password', {'email': email, 'password': pass});
        }

        this.logout = function() {
            return this._sendData('POST', 'login/logout');
        }

        this.register = function(email, name, password) {
            return this._sendData('POST', 'login/register', {'email': email, 'name': name, 'password': password});
        }

        this.loginFacebook = function(token) {
            return this._sendData('POST', 'login/facebook?code=' + token, {});
        }

        this.registerDevice = function(deviceType, token) {
            return this._sendData('POST', 'me/device', {'device_type': deviceType, 'token': token});
        }

        this.getMapsUrl = function(location) {
            return "http://maps.google.com/maps?" +
                "&q=" + location.latitude + "," + location.longitude +
                "&ll=" + location.latitude + "," + location.longitude;
        }

        this.getMapsUrlFromTo = function(location1, location2, mode) {
            if (mode === 'walking') mode = 'w';
            else if (mode === 'driving') mode = 'd';
            else mode = 'd';

            return "http://maps.google.com/maps?" +
                "&saddr=" + location1.latitude + "," + location1.longitude +
                "&daddr=" + location2.latitude + "," + location2.longitude +
                "&dirflg=" + mode;
        }
    }])
;