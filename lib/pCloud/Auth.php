<?php

namespace pCloud;

class Auth {
	public static $authParams;

	public static function getAuth($credentialPath=NULL) {
		if (self::$authParams) {
			return self::$authParams;
		} else {
			// Compatability
			if (!file_exists($credentialPath)) {
				throw new Exception("Couldn't find credential file");
			}

			$file = file_get_contents($credentialPath);
			$credential = json_decode($file, true);

			if (!isset($credential["access_token"]) || empty($credential["access_token"])) {
				throw new Exception("Couldn't find \"access_token\"");
			}

			return $credential;
		}
	}

	public static function setAuthParams($authParams) {
		if ($authParams['access_token']) {
			self::$authParams = array("access_token"=>$authParams["access_token"]);
		} else if($authParams['auth']) {
			self::$authParams = array("auth"=>$authParams["auth"]);
		} else if($authParams["username"] && $authParams["password"]) {
			self::$authParams = array("username"=>$authParams["username"], "password"=>$authParams["password"]);
		} else if($authParams["username"] && $authParams["digest"] && $authParams["passworddigest"]) {
			self::$authParams = array("username"=>$authParams["username"], "password"=>$authParams["password"], "passworddigest"=>$authParams["passworddigest"]);
		} else {
			throw new Exception("Unknown authentication type");
		}
	}
}
