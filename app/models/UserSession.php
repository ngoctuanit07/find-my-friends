<?php

class UserSession extends Eloquent {
    protected $table = 'user_session';

    public function user()
    {
        return $this->belongsTo('User');
    }
}