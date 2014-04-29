<?php

class LoginController extends \BaseController {

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function postPassword()
    {
        if (Auth::check()) {
            return Response::json(['status' => 'already_logged_in']);
        }

        if (Input::has('email') && Input::has('password')) {
            $email = Input::get('email');
            $password = Input::get('password');
        } else {
            return Response::json(['status' => 'missing_parameters']);
        }

        if (Auth::attempt(array('email' => $email, 'password' => $password), true))
        {
            return Response::json(['status' => 'logged_in']);
        }
        return Response::json(['status' => 'error']);
    }

    public function postFacebook()
    {
        $userId = $this->facebookService->getFacebookUserId();
        if ($userId) {
            $userProfile = $this->facebookService->getUserProfile();
            $accessToken = $this->facebookService->getAccessToken();

            if ( ! Auth::attempt(array('facebook_uid' => $userId), true) ) {
                $user = new User();
                $user->email = $userProfile['email'];
                $user->name = $userProfile['name'];
                $user->facebook_uid = $userId;
                $user->photo = 'http://graph.facebook.com/'+$userId+'/picture?type=small';

                $user->save();

                return Response::json(['status' => 'created']);
            }
            return Response::json(['status' => 'logged_in']);
        } else {
            return Response::json(['status' => 'error']);
        }
    }
}
