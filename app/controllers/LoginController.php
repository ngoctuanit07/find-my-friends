<?php

class LoginController extends \BaseController {

    public function __construct(LoginService $loginService, FacebookService $facebookService)
    {
        $this->loginService = $loginService;
        $this->facebookService = $facebookService;
    }

    public function postRegister()
    {
        if (Input::has('email') && Input::has('password') && Input::has('name')) {
            $email = Input::get('email');
            $password = Input::get('password');
            $name = Input::get('name');

            $user = $this->loginService->register($email, $name, $password);
            if ( $user === NULL ) {
                return Response::json(['status' => 'error']);
            } else {
                return Response::json(['status' => 'registered']);
            }
        } else {
            return Response::json(['status' => 'missing_parameters']);
        }
    }

    public function postLogout()
    {
        if (Auth::check()) {
            Auth::logout();
            return Response::json(['status' => 'logged_out']);
        } else {
            return Response::json(['status' => 'already_logged_out']);
        }
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
