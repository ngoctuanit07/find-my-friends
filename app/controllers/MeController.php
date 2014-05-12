<?php

class MeController extends \BaseController {

    public function __construct(MeService $meService, FacebookService $facebookService)
    {
        $this->beforeFilter('auth');
        $this->meService = $meService;
        $this->facebookService = $facebookService;
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
                $this->meService->updateLocation($latitude, $longitude, $accuracy, $user);
                return Response::json(['message' => 'Location accepted']);
            } else {
                return Response::error('Missing parameters');
            }
        } else {
            return Response::error('Missing parameters');
        }
    }

    public function getSocialFriends()
    {
        $user = Auth::getUser();
        if ($user->fb_token === null) return Response::ok([]);
        return Response::ok($this->facebookService->getFriends($user->fb_token));
    }

    public function postDevice()
    {
        if (Input::has('device_type') && Input::has('token')) {
            $deviceType = Input::get('device_type');
            $token = Input::get('token');
            $user = Auth::getUser();
            $user->device_type = $deviceType;
            $user->device_token = $token;
            $user->save();
            return Response::ok($user);
        } else {
            return Response::error('Missing parameters');
        }
    }
}
