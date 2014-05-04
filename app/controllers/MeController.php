<?php

class MeController extends \BaseController {

    public function __construct(MeService $meService)
    {
        $this->beforeFilter('auth');
        $this->meService = $meService;
    }

    public function getIndex()
    {
        $user = Auth::getUser();
        return Response::ok($this->meService->getMe($user));
    }

    public function putLocation()
    {
        if (Input::has('location')) {
            $location = Input::get('location');
            if (isset($location['latitude']) && isset($location['longitude']) && isset($location['accuracy'])) {
                $latitude = $location['latitude'];
                $longitude = $location['longitude'];
                $accuracy = $location['accuracy'];
                $user = Auth::getUser();
                $this->meService->addLocation($latitude, $longitude, $accuracy, $user);
                return Response::json(['message' => 'Location accepted']);
            } else {
                return Response::error('Missing parameters');
            }
        } else {
            return Response::error('Missing parameters');
        }
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
        $user1->email = "pedro@mail.com";
        $user1->name = "Pedro Fernandes";
        $user1->password = Hash::make("123");
        $user1->photo = "http://graph.facebook.com/100001104862080/picture";
        $user1->save();

        $user2 = new User();
        $user2->email = "luis@mail.com";
        $user2->name = "Luis Fernandes";
        $user2->password = Hash::make("123");
        $user2->photo = "http://graph.facebook.com/560685994/picture";

        $user3 = new User();
        $user3->email = "isa@mail.com";
        $user3->name = "Isabel Portugal";
        $user3->password = Hash::make("123");
        $user3->photo = "http://graph.facebook.com/100000534883740/picture";

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
        $friend1->status = 'sharing';
        $user1->friends()->save($friend1);

        $friend1Back = new Friend();
        $friend1Back->setFriendUser($user1);
        $friend1Back->status = 'sharing';
        $user2->friends()->save($friend1Back);

        $friend2 = new Friend();
        $friend2->setFriendUser($user3);
        $friend2->status = 'not_sharing';
        $user1->friends()->save($friend2);

        $friend2Back = new Friend();
        $friend2Back->setFriendUser($user1);
        $friend2Back->status = 'not_sharing';
        $user3->friends()->save($friend2Back);

        return Response::json($user1);
    }
}
