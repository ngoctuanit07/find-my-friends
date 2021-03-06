<?php

class FriendController extends \BaseController {

    public $restful = true;

    public function __construct(MeService $meService, RegisterService $registerService, FoursquareService $foursquareService, GoogleAPIsService $googleAPIsService)
    {
        $this->beforeFilter('auth');
        $this->meService = $meService;
        $this->registerService = $registerService;
        $this->foursquareService = $foursquareService;
        $this->googleAPIsService = $googleAPIsService;
    }

    public function patchStatus($id)
    {
        if (Input::has('status')) {
            $status = Input::get('status');

            if (!in_array($status, Friend::$states)) {
                return Response::error('Invalid status for friend');
            }

            $user = Auth::getUser();
            $friendUser = $this->meService->getUser($id);
            if ($friendUser === null) {
                return Response::error('Invalid friend id');
            }

            $friend = $this->meService->updateStatus($user, $friendUser, $status);
            if ($friend === null) {
                return Response::error('Failed updating status to friend');
            } else {
                return Response::ok($friend);
            }
        }

        else {
            return Response::error('Missing parameters');
        }
    }

    public function getDistance($id)
    {
        $user = Auth::getUser();
        $friendUser = $this->meService->getUser($id);
        if ($friendUser === null) {
            return Response::error('Invalid friend id');
        }
        $friend = $friendUser->friends()->where('friend_user_id', $user->id)->first();

        if ($friend === null)
            return Response::error('Not a friend');

        $mode = 'driving';
        if (Input::has('mode')) {
            $mode = Input::get('mode');
        }

        if ($friend->status === 'sharing') {
            $userLocation = $user->location;
            $friendLocation = $friendUser->location;
            if ($userLocation === null || $friendLocation === null)
                return Response::error('Don\'t have user or friend location');

            $response = $this->googleAPIsService->getDistanceMatrix($user, $friendUser, $mode);

            return Response::ok($response);
        } else
            return Response::json(['message' => 'Friend not sharing location']);

    }

    public function getNearbyPlaces($id)
    {
        $user = Auth::getUser();
        $friendUser = $this->meService->getUser($id);
        if ($friendUser === null) {
            return Response::error('Invalid friend id');
        }
        $friend = $friendUser->friends()->where('friend_user_id', $user->id)->first();

        if ($friend === null)
            return Response::error('Not a friend');

        if ($friend->status === 'sharing') {
            $userLocation = $user->location;
            $friendLocation = $friendUser->location;
            if ($userLocation === null || $friendLocation === null)
                return Response::error('Don\'t have user or friend location');

            $places = $this->foursquareService->getVenuesNear($friendLocation->latitude, $friendLocation->longitude);
            return Response::ok($places);
        } else
            return Response::json(['message' => 'Friend not sharing location']);
    }

    public function postRequest($id)
    {
        $user = Auth::getUser();
        $friendUser = $this->meService->getUser($id);
        if ($friendUser === null) {
            return Response::error('Invalid friend id');
        }
        $friend = $this->meService->requestLocationShare($user, $friendUser);
        if ($friend === null)
            return Response::error('Failed to request location share');
        else
            return Response::ok($friend);
    }

    public function postIndex()
    {
        $user = Auth::getUser();
        $friendUser = null;

        if (Input::has('id')) {
            $friendUser = $this->meService->getUser(Input::get('id'));

            if($friendUser === null) {
                return Reponse::error('User id not found');
            }
        }
        else if (Input::has('facebook_uid')) {
            $facebook_uid = Input::get('facebook_uid');
            $friendUser = $this->registerService->preRegisterFacebookId($facebook_uid);
        }
        else if (Input::has('email')) {
            $email = Input::get('email');
            $friendUser = $this->registerService->inviteByEmail($email, $user);
        }
        else {
            return Response::error('Missing parameters');
        }

        if ($user->id === $friendUser->id) {
            return Response::error('Invited same user');
        }

        $friend = $this->meService->addFriend($user, $friendUser);
        if ($friend === null) {
            return Response::error('Failed to add friend');
        } else {
            return Response::json(['message' => 'Added new friend']);
        }
    }

    public function deleteIndex()
    {
        $user = Auth::getUser();
		if (Input::has('id')) {
			$friendUser = $this->meService->getUser(Input::get('id'));
			if ($friendUser === null) {
				return Response::error('Invalid friend id');
			}

			$response = $this->meService->deleteFriendship($user, $friendUser);
			if ($response !== null) {
				return Response::ok($response);
			} else
                return Response::error('Failed to delete friendship');
        } else
		    return Response::error('Missing parameters');
	}
}