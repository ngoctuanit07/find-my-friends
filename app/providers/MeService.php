<?php

class MeService
{
    public function getMe($user)
    {
        return User::find($user->id);
    }

    public function getUser($id)
    {
        return User::find($id);
    }

    public function getUserFromFacebookId($facebookId)
    {
        return User::where('facebook_uid', '=', $facebookId)->take(1)->get();
    }

    public function getUserFromEmail($email)
    {
        return User::where('email', '=', $email)->take(1)->get();
    }

    public function friendshipExists($user, $friendUser)
    {
        return !Friend::where('user_id', '=', $user->id)
                ->where('friend_user_id', '=', $friendUser->id)
                ->get()->isEmpty();
    }

    public function addFriend($user, $friendUser)
    {
        if( $this->friendshipExists($user, $friendUser) ) {
            return null;
        }

        $friend = new Friend();
        $friend->setFriendUser($friendUser);
        $friend->status = 'sent_request';
        $user->friends()->save($friend);

        $friend = new Friend();
        $friend->setFriendUser($user);
        $friend->status = 'pending_confirmation';
        $friendUser->friends()->save($friend);

        return $friend;
    }

    public function addLocation($latitude, $longitude, $accuracy, $user)
    {
        $location = new Location();
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        $location->accuracy = $accuracy;
        $user->locations()->save($location);
    }
}
