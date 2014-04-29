<?php

class MeController extends \BaseController {

    public function __construct()
    {
        $this->beforeFilter('auth');
    }

	public function getIndex()
    {
        $users = User::all();
        return Response::json($users);
    }

    public function getTest()
    {
        DB::unprepared("SET foreign_key_checks=0");
        User::truncate();
        UserSession::truncate();
        Location::truncate();
        Friend::truncate();
        DB::unprepared("SET foreign_key_checks=1");

        $user1 = new User();
        $user1->email = "pedrorfernandes@yahoo.com";
        $user1->name = "Pedro Fernandes";
        $user1->password = Hash::make("123");
        $user1->photo = "http://graph.facebook.com/100001104862080/picture?type=square";

        $user2 = new User();
        $user2->email = "zipleen@gmail.com";
        $user2->name = "Luis Fernandes";
        $user2->password = Hash::make("123");
        $user2->photo = "http://graph.facebook.com/560685994/picture?type=square";

        $user3 = new User();
        $user3->email = "isabelcportugal@gmail.com";
        $user3->name = "Isabel Portugal";
        $user3->password = Hash::make("123");
        $user3->photo = "http://graph.facebook.com/100000534883740/picture?type=square";

        $user1->save();
        $user2->save();
        $user3->save();

        $location1 = new Location();
        $location1->latitude = 41.1732764;
        $location1->longitude = -8.5833835;
        $location1->accuracy = 10;

        $location2 = new Location();
        $location2->latitude = 41.173103;
        $location2->longitude = -8.5846973;
        $location2->accuracy = 2;

        $location3 = new Location();
        $location3->latitude = 41.1751462;
        $location3->longitude = -8.5858238;
        $location3->accuracy = 5;

        $location4 = new Location();
        $location4->latitude = 41.1725294;
        $location4->longitude = -8.5875624;
        $location4->accuracy = 15;

        $user1->locations()->save($location1);
        $user2->locations()->save($location2);
        $user3->locations()->save($location3);
        $user1->locations()->save($location4);

        $friend1 = new Friend();
        $friend1->setFriendUser($user2);
        $user1->friends()->save($friend1);

        $friend2 = new Friend();
        $friend2->setFriendUser($user3);
        $user1->friends()->save($friend2);

        return Response::json($user1);
    }

    public function postLocation()
    {
        echo "post location dddd";
    }
}
