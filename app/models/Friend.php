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

    public function toArray()
    {
        return [
            'friend_id'=> $this->friend_user_id,
            'friend' => $this->friendUser->toArrayForFriend($this->userId),
            'status' => $this->status
        ];
    }
}