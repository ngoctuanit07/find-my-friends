<?php

class MeService
{
    public function getUser($userId) {
        return User::find($userId);
    }
}
