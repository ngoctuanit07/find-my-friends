<?php

class FacebookService
{
    /**
     * @var string
     */
    private $_user;

    /**
     * @var \Facebook
     */
    private $_facebook;

    public function __construct()
    {
        $this->_facebook = new Facebook(self::getConfig());
    }

    /**
     * get facebook config, gives appId and secret to be used
     *
     * @return array
     */
    static public function getConfig()
    {
        $config = array(
            'appId' => '694514773928221',
            'secret' => '998d17a5f14b9303cba4622e5c7127dc'
        );

        return $config;
    }

    /**
     * returns user profile from facebook, an array of data with user info
     *
     * @return bool|array
     */
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

    /**
     * get facebook access token
     *
     * @return string|false
     */
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