<?php

class Friend extends Eloquent {

    protected $table = 'friend';

    public function setFriendUser(User $user)
    {
        $this->friend_user_id = $user->id;
    }

    public function friendUser()
    {
        return $this->hasOne('User', 'id');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    public function toArray()
    {
        return [
            'id'=> $this->friend_user_id,
            'user' => $this->friendUser->toArray(),
            'status' => $this->status
        ];
    }
}