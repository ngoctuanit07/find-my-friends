<?php

class FriendController extends \BaseController {

    public function __construct(MeService $meService)
    {
        $this->beforeFilter('auth');
        $this->meService = $meService;
    }

    public function postIndex()
    {
        $user = Auth::getUser();

        if (Input::has('id')) {
            $friendUser = $this->meService->getUser(Input::get('id'));
            $friend = $this->meService->addFriend($user, $friendUser);
            if ($friend === null) {
                return Response::error('Failed to add friend');
            } else {
                return Response::json(['message' => 'Added new friend']);
            }
        }

        else if (Input::has('facebook_uid')) {
            // ...

        }

        else if (Input::has('email')) {
            // ...

        }

        else {
            return Response::error('Missing parameters');
        }
    }

}