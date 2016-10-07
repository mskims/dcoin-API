<?php
namespace dcoin\controllers;

Class TokenController {
	public function getAccessTokenInfo($access_token){
		$rs = fetch("SELECT * FROM tokens WHERE access_token=?", [$access_token]);
		return $rs ? $rs : false;
	}


	public function createAccessToken(){
		 $access_token = randomstr(344);
		 return isExist($access_token, "tokens", "access_token") ? self::createAccessToken() : $access_token;
	}
	public function setAccessToken($info){
		sql("INSERT INTO tokens SET 
			app_idx=?,
			secret_code=?,
			user_idx=?,
			access_token=?,
			scopes=?,
			created_at=?,
			expires_at=?", [
				$info["app_idx"],
				$info["app_secret_code"],
				$info["user_idx"],
				$info["access_token"],
				$info["scopes"],
				time(),
				time()+$info["expires"]
		]);
	}
}