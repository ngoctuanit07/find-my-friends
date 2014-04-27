<?php

class MeController extends \BaseController {

    public function __construct()
    {
        //$this->beforeFilter('auth');
    }

	public function getIndex()
    {
        $users = User::all();

        return Response::json($users);
    }

    public function getTest()
    {
        $user = new User();
        $user->email = "bla22@bla.com";
        $user->name = "ola2";
        $user->password = Hash::make("123");
        //$user->last_login = new Date();

        $location = new Location();
        $location->latitude = 10.1111;
        $location->longitude = 66.66666;
        $location->accuracy = 10;

        $user->save();

        $user->locations()->save($location);

        $userAntigo = User::find(1);

        $friend = new Friend();
        $friend->setFriendUser($userAntigo);

        $user->friends()->save($friend);

        return Response::json($user);
    }

    public function postLocation()
    {
        echo "post location dddd";
    }
}
