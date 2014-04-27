<?php

class LoginController extends \BaseController {

    public function postPassword()
    {
        if (Auth::check()) {
            return Response::json(['ja tasi']);
        }
        $email = "";
        $password = "";
        if (Input::has('email') && Input::has('password')) {
            $email = Input::get('email');
            $password = Input::get('password');
        } else {
            return Response::json(['iii'=>'iiii']);
        }

        if (Auth::attempt(array('email' => $email, 'password' => $password), true))
        {
            return Response::json(['ola'=>'ola']);
        }
        return Response::json(['bah'=>'bah']);
    }
}
