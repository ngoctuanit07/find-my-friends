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
                return Response::error('Failed to register');
            } else {
                return Response::json(['message' => 'Registered']);
            }
        } else {
            return Response::error('Missing parameters');
        }
    }

    public function postLogout()
    {
        if (Auth::check()) {
            Auth::logout();
            return Response::json(['message' => 'Logged out']);
        } else {
            return Response::json(['message' => 'Already logged out']);
        }
    }

    public function postPassword()
    {
        if (Auth::check()) {
            return Response::json(['message' => 'Already logged in']);
        }

        if (Input::has('email') && Input::has('password')) {
            $email = Input::get('email');
            $password = Input::get('password');
        } else {
            return Response::error('Missing parameters');
        }

        if (Auth::attempt(array('email' => $email, 'password' => $password), true))
        {
            return Response::json(['message' => 'Logged in']);
        }
        return Response::error('Log in failed');
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

                return Response::json(['messaged' => 'Created user and logged in']);
            }
            return Response::json(['message' => 'Logged in']);
        } else {
            return Response::error('Facebook log in failed');
        }
    }
}
