<?php

class RegisterService
{
    private $defaultPictureId = 100008244347938;
    public function register($email, $name, $password)
    {
        $user = User::where('email', '=', $email)->first();

        if ($user === null) {
            $user = new User();
        }

        $user->email = $email;
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->photo = "http://graph.facebook.com/$this->defaultPictureId/picture";
        $user->save();
        return $user;
    }

    public function registerFacebookId($facebook_uid)
    {
        $user = User::where('facebook_uid', '=', $facebook_uid)->first();

        if ($user === null) {
            $user = new User();
        }

        $user->facebook_uid = $facebook_uid;
        $user->name = $this->getPublicUser($facebook_uid)->name;
        $user->photo = 'http://graph.facebook.com/'. $facebook_uid .'/picture';
        $user->save();
        return $user;
    }

    public function registerEmail($email)
    {
        $user = User::where('email', '=', $email)->first();

        if ($user === null) {
            $user = new User();
        }

        $user->email = $email;
        $user->name = $email;
        $user->photo = "http://graph.facebook.com/$this->defaultPictureId/picture";
        $user->save();
        return $user;
    }

    private function getPublicUser($facebook_uid)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://graph.facebook.com/'.$facebook_uid,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}