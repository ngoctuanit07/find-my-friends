angular.module('starter.services')

    .service('GeoMath', [function() {
    this.getDistanceInKm = function(location1, location2) {
        if(location1 == null || location2 == null) {
            return 0;
        }
        var R = 6371; // Radius of the earth in km
        var dLat = this.deg2rad(location2.latitude-location1.latitude);
        var dLon = this.deg2rad(location2.longitude-location1.longitude);
        var a =
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(this.deg2rad(location1.latitude)) * Math.cos(this.deg2rad(location2.latitude)) *
                        Math.sin(dLon/2) * Math.sin(dLon/2)
            ;
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c; // Distance in km
        return d;
    }

    this.deg2rad = function(deg) {
        return deg * (Math.PI/180)
    }

    this.timeSince = function(date) {
        if (typeof date !== 'object') {
            date = new Date(date);
        }

        var seconds = Math.floor((new Date() - date) / 1000);
        var intervalType;

        var interval = Math.floor(seconds / 31536000);
        if (interval >= 1) {
            intervalType = 'year';
        } else {
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) {
                intervalType = 'month';
            } else {
                interval = Math.floor(seconds / 86400);
                if (interval >= 1) {
                    intervalType = 'day';
                } else {
                    interval = Math.floor(seconds / 3600);
                    if (interval >= 1) {
                        intervalType = "hour";
                    } else {
                        interval = Math.floor(seconds / 60);
                        if (interval >= 1) {
                            intervalType = "minute";
                        } else {
                            intervalType = "second";
                        }
                    }
                }
            }
        }

        if (interval > 1) {
            intervalType += 's';
        }

        if (isNaN(interval)) {
            return 'Not found';
        }

        return interval + ' ' + intervalType + ' ago';
    };
}])