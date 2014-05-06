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
        $session = $this->getFacebookSession();
		if ($session) {
			try {
				return $session->getToken();
			} catch (FacebookRequestException $e) {
				error_log($e);
			}
		}
		return false;
	}

	/**
     * @param access_token
	 * return a facebook session
	 *
	 * @return \Facebook\FacebookSession
	 */
	public function getFacebookSession($access_token = null)
	{
		if (!$this->_user) {
            if ($access_token !== null) {
                $this->_user = $this->_getFacebookSessionFromUserToken($access_token);
            }
			else if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] == "file://" && Input::has('code')) {
				// facebook in ios does not set fbsr_APPIP (signed_request), which makes sessionFromJavascript fail
				// $_SERVER['HTTP_ORIGIN'] seems to be file:// when the request comes from cordova, so instead of hacking let's detect it by this parameter
				$this->_user = $this->_getFacebookSessionFromCode();
			}
            else {
				$this->_user = $this->_getFacebookSessionFromJavaScript();
			}
		}
		return $this->_user;
	}

	private function _getFacebookSessionFromJavaScript()
	{
		$helper = new FacebookJavaScriptLoginHelper();
		try {
			return $helper->getSession();
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

    private function _getFacebookSessionFromUserToken($access_token)
    {
        try {
            $session = new FacebookSession($access_token);
            $session->validate();
            return $session;
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

	private function _getFacebookSessionFromCode()
	{
		try {
			$session = new FacebookSession(Input::get('code'));
			$session->validate();
			return $session;
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

	public function getFriends($access_token = null)
	{
		$session = $this->getFacebookSession($access_token);
		if ($session) {
			try {
				$request = new FacebookRequest($session, 'GET', '/me/friends', ['limit' => 5000]);
                $objectList = $request->execute()->getGraphObjectList();

                $friends = [];
                foreach($objectList as $object) {
                    $friends[] = $object->asArray();
                }
                return $friends;

			} catch (FacebookApiException $e) {
				if (APPLICATION_ENV == "dev") {
					error_log($e);
				}
			}
		}
		return false;
	}
}