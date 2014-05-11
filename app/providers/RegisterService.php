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
        else if ($this->isActive($user)) {
            return null; // user already exists
        }

        $user->photo = "http://graph.facebook.com/$this->defaultPictureId/picture";
        $user->email = $email;
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }

    public function registerFacebook($userProfile)
    {
        $user = User::where('facebook_uid', '=', $userProfile->id)->first();

        if ($user === null)
            $user = User::where('email', '=', $userProfile->email)->first();

        if ($user === null)
            $user = new User();

        $user->email = $userProfile->email;
        $user->name = $userProfile->name;
        $user->facebook_uid = $userProfile->id;
        $user->photo = 'http://graph.facebook.com/' . $user->facebook_uid . '/picture';
        $user->save();

        return $user;
    }

    private function isActive($user)
    {
        if ($user->email !== null && $user->password !== null)
            return true; // active user with email

        if ($user->email !== null && $user->facebook_uid !== null)
            return true; // active user with facebook

        return false;
    }

    public function preRegisterFacebookId($facebook_uid)
    {
        $user = User::where('facebook_uid', '=', $facebook_uid)->first();

        if ($user === null) {
            $user = new User();
            $user->facebook_uid = $facebook_uid;
            $user->name = $this->getPublicUser($facebook_uid)->name;
            $user->photo = 'http://graph.facebook.com/'. $facebook_uid .'/picture';
            $user->save();
        }

        return $user;
    }

    public function inviteByEmail($email, $inviterUser)
    {
        $friendUser = User::where('email', '=', $email)->first();

        if ($friendUser === null) {
            $friendUser = $this->preRegisterEmail($email);
            $this->sendEmailInvite($inviterUser, $friendUser);
        }

        return $friendUser;
    }

    public function preRegisterEmail($email)
    {
        $user = new User();
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

    public function sendEmailInvite($user, $friendUser)
    {
        $data = ['user' => $user, 'friendUser' => $friendUser];
        Mail::send('emails.invited', $data, function($message) use ($user, $friendUser)
        {
            $message->to($friendUser->email)->subject("$user->name invited you to use Spot My Friends");
        });
    }
}