<?php
use Facebook\FacebookRequest;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;

// install cordova plugin with:
// cordova plugin add https://github.com/phonegap-build/FacebookConnect.git --variable APP_ID="694514773928221" --variable APP_NAME="Find My Friends"

class FacebookService
{

	private $_user;

	public function __construct()
	{
		self::getConfig();
	}

	static public function getConfig()
	{
		$config = array(
			'appId' => '694514773928221',
			'secret' => '998d17a5f14b9303cba4622e5c7127dc'
		);
		FacebookSession::setDefaultApplication($config['appId'], $config['secret']);

		return $config;
	}

	/**
	 * @return array|bool
	 */
	public function getUserProfile()
	{
		$session = $this->getFacebookSession();
		if ($session) {
			try {
				$request = new FacebookRequest($session, 'GET', '/me');
				$answer = $request->execute();
				return $answer->getResponse();
			} catch (FacebookRequestException $e) {
				if (APPLICATION_ENV == "dev") {
					error_log($e);
				}
			}
		}
		return false;
	}

	public function getAccessToken()
	{
		$user = $this->getFacebookSession();
		if ($user) {
			try {
				return $user->getToken();
			} catch (FacebookRequestException $e) {
				error_log($e);
			}
		}
		return false;
	}

	/**
	 * return a facebook session
	 *
	 * @return \Facebook\FacebookSession
	 */
	public function getFacebookSession()
	{
		if (!$this->_user) {
			$helper = new FacebookJavaScriptLoginHelper();
			try {
				$this->_user = $helper->getSession();
			} catch (FacebookRequestException $ex) {
				// When Facebook returns an error
				//if (APPLICATION_ENV=="dev") {
				error_log($ex->getMessage());
				//}
				return false;
			} catch (\Exception $ex) {
				// When validation fails or other local issues
				if (APPLICATION_ENV == "dev") {
					error_log($ex->getMessage());
				}
				return false;
			}
		}
		return $this->_user;
	}

	public function getFriends()
	{
		$session = $this->getFacebookSession();
		if ($session) {
			try {
				$request = new FacebookRequest($session, 'GET', '/me/friends');
				return $request->execute();
			} catch (FacebookApiException $e) {
				if (APPLICATION_ENV == "dev") {
					error_log($e);
				}
			}
		}
		return false;
	}
}