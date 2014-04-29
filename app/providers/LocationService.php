<?php

class LocationService
{
    public function addLocation($latitude, $longitude, $accuracy, $user)
    {
        $location = new Location();
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        $location->accuracy = $accuracy;
        $user->locations()->save($location);
    }

}