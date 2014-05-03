<?php

// install cordova plugin with:
// cordova plugin add https://github.com/phonegap-build/FacebookConnect.git --variable APP_ID="694514773928221" --variable APP_NAME="998d17a5f14b9303cba4622e5c7127dc"

class FacebookService
{

    private $_user;

    private $_facebook;

    public function __construct()
    {
        $this->_facebook = new Facebook(self::getConfig());
    }

    static public function getConfig()
    {
        $config = array(
            'appId' => '694514773928221',
            'secret' => '998d17a5f14b9303cba4622e5c7127dc'
        );

        return $config;
    }

    public function getUserProfile()
    {
        $user = $this->getFacebookUserId();
        if ($user) {
            try{
                return $this->_facebook->api('/me');
            }
            catch(FacebookApiException $e){
                if (APPLICATION_ENV=="dev") {
                    error_log($e);
                }
            }
        }
        return false;
    }

    public function getAccessToken()
    {
        $user = $this->getFacebookUserId();
        if ($user){
            try{
                return $this->_facebook->getAccessToken();
            }
            catch(FacebookApiException $e){
                error_log($e);
            }
        }
        return false;
    }

    public function getFacebookUserId()
    {
        if ( ! $this->_user ) {
            $this->_user = $this->_facebook->getUser();
        }
        return $this->_user;
    }

    public function getFriends()
    {
        $user = $this->getFacebookUserId();
        if ($user) {
            try{
                return $this->_facebook->api('/me/friends');
            }
            catch(FacebookApiException $e){
                error_log($e);
            }
        }
        return false;

    }
}