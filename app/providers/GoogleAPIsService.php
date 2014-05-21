<?php

class GoogleAPIsService
{
    private $key = 'AIzaSyBQsgDTkLhr7JfJUzAF_SS8MN4VFOz62fE';

    public function getDistanceMatrix($user, $friendUser, $mode)
    {
        $url = 'http://maps.googleapis.com/maps/api/distancematrix/json?'
            . 'origins=' . $user->location->latitude . ',' . $user->location->longitude
            . '&destinations='. $friendUser->location->latitude . ',' . $friendUser->location->longitude
            . '&sensor=true'
            . '&mode=' . $mode;
        return json_decode(file_get_contents($url));
    }
}
