<?php

class LoginController extends \BaseController {

    public function __construct(RegisterService $registerService, FacebookService $facebookService, MeService $meService)
    {
        $this->registerService = $registerService;
        $this->facebookService = $facebookService;
        $this->meService = $meService;
    }

    public function postRegister()
    {
        if (Input::has('email') && Input::has('password') && Input::has('name')) {
            $email = Input::get('email');
            $password = Input::get('password');
            $name = Input::get('name');

            $user = $this->registerService->register($email, $name, $password);
            if ( $user === NULL ) {
                return Response::error('Failed to register');
            } else {
                return Response::ok($user);
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
            Auth::getUser()->updateLastLoginTime();
            return Response::json(['message' => 'Logged in']);
        }
        return Response::error('Log in failed');
    }

    private function getTokenAndUpdateLoginTime()
    {
        $user = Auth::getUser();
        $user->updateLastLoginTime();
        $user->fb_token = $this->facebookService->getAccessToken();
        $user->save();
    }

    public function postFacebook()
    {
        $userProfile = $this->facebookService->getUserProfile();
        if ($userProfile) {

            $user = $this->meService->getUserFromFacebookId($userProfile->id);

            if ($user === null)
                $user = $this->meService->getUserFromEmail($userProfile->email);

            if ($user === null)
                $user = new User();

            $user->email = $userProfile->email;
            $user->name = $userProfile->name;
            $user->facebook_uid = $userProfile->id;
            $user->photo = 'http://graph.facebook.com/' . $user->facebook_uid . '/picture';
            $user->save();

            Auth::login($user, true);
            $this->getTokenAndUpdateLoginTime();

            return Response::json(['status' => 'ok', 'message' => 'Logged in with facebook']);
        }

        else {
            return Response::error('Facebook log in failed');
        }
    }
}
