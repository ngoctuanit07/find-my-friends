<?php

class FriendController extends \BaseController {

    public $restful = true;

    public function __construct(MeService $meService, RegisterService $registerService)
    {
        $this->beforeFilter('auth');
        $this->meService = $meService;
        $this->registerService = $registerService;
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
                return Response::ok($this->meService->updateStatus($user, $friendUser, $status));
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

            $url = 'http://maps.googleapis.com/maps/api/distancematrix/json?'
                . 'origins=' . $userLocation->latitude . ',' . $userLocation->longitude
                . '&destinations='. $friendLocation->latitude . ',' . $friendLocation->longitude
                . '&sensor=true'
                . '&mode=' . $mode;

            return Response::ok(json_decode(file_get_contents($url)));
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
            $friendUser = $this->registerService->registerFacebookId($facebook_uid);
        }
        else if (Input::has('email')) {
            $email = Input::get('email');

            $friendUser = $this->meService->getUserFromEmail($email);
            if ($friendUser === null) {
                $friendUser = $this->registerService->registerEmail($email);
                $this->registerService->sendEmailInvite($user, $friendUser);
            }
        }
        else {
            return Response::error('Missing parameters');
        }

        $friend = $this->meService->addFriend($user, $friendUser);
        if ($friend === null) {
            return Response::error('Failed to add friend');
        } else {
            return Response::json(['message' => 'Added new friend']);
        }
    }

    public function destroy($id)
    {
        $user = Auth::getUser();
        $friendUser = $this->meService->getUser($id);
        if ($friendUser === null) {
            return Response::error('Invalid friend id');
        }

        $response = $this->meService->deleteFriendship($user, $friendUser);
        if ($response === null)
            return Response::error('Failed to delete friendship');
        else
            return Response::ok($response);

    }
}