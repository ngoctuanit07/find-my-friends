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

    this.getAddress = function(location) {
        // https://maps.googleapis.com/maps/api/geocode/json?latlng=41.173103,-8.584697&sensor=true
        return $http({
            method: 'GET',
            url: 'https://maps.googleapis.com/maps/api/geocode/json',
            params: {'latlng': location.latitude + ',' + location.longitude,
                'sensor': 'true' }
        });
    }

    this.getDistance = function(friendId) {
        return this._sendData('GET', 'friend/distance/' + friendId, {});
    }

    this.getMe = function () {
        return this._sendData('GET', 'me', {});
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
        return this._sendData('POST', 'friend/status/' + friendId, {'status': 'not_sharing'});
    }

    this.startSharingLocation = function(friendId) {
        return this._sendData('POST', 'friend/status/' + friendId, {'status': 'sharing'});
    }

    this.acceptFriendRequest = function(friendId) {
        return this._sendData('POST', 'friend/status/' + friendId, {'status': 'not_sharing'});
    }

    this.blockFriend = function(friendId) {
        return this._sendData('POST', 'friend/status/' + friendId, {'status': 'blocked'});
    }

    this.unblockFriend = function(friendId) {
        return this._sendData('POST', 'friend/status/' + friendId, {'status': 'not_sharing'});
    }

    this.login = function(email, pass) {
        return this._sendData('POST', 'login/password', {'email': email, 'password': pass});
    }

    this.logout = function() {
        return this._sendData('POST', 'login/logout');
    }

    this.loginFacebook = function(token) {
        return this._sendData('POST', 'login/facebook?code=' + token, {});
    }
}])
;