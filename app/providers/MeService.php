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
        return User::where('facebook_uid', '=', $facebookId)->first();
    }

    public function getUserFromEmail($email)
    {
        return User::where('email', '=', $email)->first();
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

    private $invalidStatusUpdates = [
        'sent_request', 'pending_confirmation', 'asked_to_share',
    ];

    public function updateStatus($user, $friendUser, $newStatus)
    {
        if(in_array($newStatus, $this->invalidStatusUpdates)) return null;

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

    public function requestLocationShare($user, $friendUser)
    {
        $friend = $friendUser->friends()->where('friend_user_id', $user->id)->first();

        if ($friend === null) return null;
        if($friend->status !== 'not_sharing') return null;

        $friend->status = 'asked_to_share';
        $friend->save();

        $this->pushNotification($friendUser, "$user->name is requesting your location!");

        // return user friendship
        $friend = $user->friends()->where('friend_user_id', $friendUser->id)->first();
        return $friend;
    }

    public function pushNotification(User $user, $message)
    {
        if ($user->device_token === null) return;
        // only support android notifications for now
        PushNotification::app('appNameAndroid')
            ->to($user->device_token)
            ->send($message);
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
