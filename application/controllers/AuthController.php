<?php
namespace dcoin\controllers;

Class AuthController {
	public function login($req, $res, $service){
		$csrf_token = md5(uniqid(mt_rand(), true));
		$redirect_url = $req->redirect_url;
		$errors = count($req->error_code) > 0 ? errorstr($req->error_code) : null;
		$app_idx = !empty($req->app_idx) ? $req->app_idx : 1000000000;
		$app_info = controller("app")->getInfo($app_idx, ["name"]);

		$_SESSION["token"] = $csrf_token;

		title("로그인");
		layout("main");
		view("auth/login", compact("csrf_token", "redirect_url", "errors", "app_idx", "app_info"));
	}
	public function loginProcess($req, $res, $service){
		if($req->csrf_token == $_SESSION["token"]){

			$chk = fetch("SELECT code FROM member WHERE account_number=? AND pw=?", [
					$req->account_number,
					encrypt($req->pw)
				]);
			if($chk){
				$code = $chk["code"];
				$redirect_url = $req->redirect_url;
				return view("auth/login_process", compact("redirect_url", "code"));
			}else{
				return error("002");
			}
		}else{
			return error("001");
		}
	}
	public function accessToken($req){
		$app_idx = $req->app_idx;
		$app_secret_code = $req->app_secret_code;

		$code = $req->code;
		$expires = self::verifyExpires($req->expires);

		$scopes = controller("app")->getScopes($app_idx);

		// Verify MEMBER CODE
		$user_idx = self::verifyCode($code);
		if($user_idx === false){
			return errorJSON("code_invalid", "코드 검증 오류");
		}else{
			controller("user")->updateCode($user_idx);
		}

		// Verify App
		if(self::verifyApp($app_idx) === false){
			return errorJSON("not_verified_app", "승인되지 않은 앱");	
		}

		// Verify APP SECRET CODE
		if(self::verifyAppSecretCode($app_idx, $app_secret_code) !==true){
			return errorJSON("secret_code_invalid", "앱 시크릿 코드 검증 오류");
		}

	
		// Create
		$access_token = controller("token")->createAccessToken();
		controller("token")->setAccessToken([
				"app_idx" => $app_idx,
				"app_secret_code" => $app_secret_code,
				"user_idx" => $user_idx,
				"access_token" => $access_token,
				"scopes" => implode("|", $scopes),
				"expires" => $expires,
			]);

		return json(["access_token" => $access_token]);
	}
	
	public function getCode($user_idx){
		$rs = fetch("SELECT code FROM member WHERE idx=?", [$user_idx]);
		return $rs ? $rs["code"] : false;
	}

	// verify
		public function verifyCSRF($client_token){
			return $client_token === $_SESSION["token"];
		}
		public function verifyLogin($account_number, $pw){
			$chk = fetch("SELECT idx FROM member WHERE account_number=?AND pw=?", [
				$account_number,
				$pw
			]);
			return $chk ? $chk["idx"] : false;
		}

		public function verifyCode($client_code){
			$rs = fetch("SELECT idx, COUNT(*) AS count FROM member WHERE code=?", [$client_code]);
			return $rs["count"] == 1 ? $rs["idx"] : false;
		}
		public function verifyAppSecretCode($app_idx, $client_secret_code){
			// $secretCode = 
			$secret_code = controller("app")->getInfo($app_idx, ["secret_code"])["secret_code"];
			return $secret_code === $client_secret_code;
		}
		public function verifyExpires($expires){
			$expires = !empty($expires) ? $expires : 60*60*24;
			$expires = $expires > 60*60*24*30 ? 60*60*24*30 : $expires;
			return $expires;
		}
		public function verifyApp($app_idx){
			$rs = controller("app")->getInfo($app_idx, ["verified"]);
			return $rs ? ($rs["verified"] == 1) : false;
		}
}