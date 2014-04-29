<?php

class MeService
{
    public function getMe($user) {
        return User::find($user->id);
    }
}
