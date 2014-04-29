<?php

class LoginService
{
    public function register($email, $name, $password)
    {
        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }
}