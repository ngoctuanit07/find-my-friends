<?php

class MeService
{
    public function getMe($user) {
        return User::find($user->id);
    }

    public function addLocation($latitude, $longitude, $accuracy, $user)
    {
        $location = new Location();
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        $location->accuracy = $accuracy;
        $user->locations()->save($location);
    }
}
