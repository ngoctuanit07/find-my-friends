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

    public function acceptedRequest($friend, $newStatus)
    {
        return $friend->status === 'pending_confirmation'
            && ( $newStatus === 'sharing' || $newStatus === 'not_sharing' );
    }

    public function updateStatus($user, $friendUser, $newStatus)
    {
        if($newStatus === 'sent_request' || $newStatus === 'pending_confirmation')
            return null;

        $friend = $user->friends()->where('friend_user_id', $friendUser->id)->first();

        if ($friend === null) return null;
        if ($friend->status === 'sent_request') return null;

        if ( $this->acceptedRequest($friend, $newStatus) ) {
            $friendBack = $friendUser->friends()->where('friend_user_id', $user->id)->first();
            if ($friendBack->status === 'sent_request') {
                $friendBack->status = 'not_sharing';
                $friendBack->save();
            }
        }

        $friend->status = $newStatus;
        $friend->save();
        return $friend;
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

        $friendBack = new Friend();
        $friendBack->setFriendUser($user);
        $friendBack->status = 'pending_confirmation';
        $friendUser->friends()->save($friendBack);

        return $friend;
    }

    public function updateLocation($latitude, $longitude, $accuracy, $user)
    {
        $location = $user->location ?: new Location();
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        $location->accuracy = $accuracy;
        $user->location()->save($location);
    }
}
