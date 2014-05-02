<?php

class Friend extends Eloquent {

    protected $table = 'friend';

    public static $states = [
        'sent_request', 'pending_confirmation' ,
        'sharing', 'not_sharing',
        'blocked'
    ];

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
        return $this->belongsTo('User');
    }

    public function canShareLocation()
    {
        if ($this->status === 'sharing') return true;
        else return false;
    }

    public function toArray()
    {
        return [
            'friend_id'=> $this->friend_user_id,
            'user' => $this->friendUser->toArrayForFriend($this->user_id),
            'status' => $this->status
        ];
    }
}