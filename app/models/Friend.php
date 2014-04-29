<?php

class Friend extends Eloquent {

    protected $table = 'friend';

    public function setFriendUser(User $user)
    {
        $this->friend_user_id = $user->id;
    }

    public function friendUser()
    {
        return $this->belongsTo('User', 'friend_user_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    public function toArray()
    {
        return [
            'friend_id'=> $this->friend_user_id,
            'friend' => $this->friendUser->toArrayWithoutFriends(),
            'status' => $this->status
        ];
    }
}