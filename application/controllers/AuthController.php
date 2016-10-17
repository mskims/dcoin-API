<?php
namespace dcoin\controllers;

Class AuthController {


	// VIEWS
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
		self::verifyCSRF($req->csrf_token);
		$user_info = self::verifyLogin($req->account_number, $req->pw);

		if($user_info != false){
			$code = $user_info["code"];
			$redirect_url = $req->redirect_url;
			return view("auth/login_process", compact("redirect_url", "code"));
		}else{
			return error("002");
		}
	}

	public function transfer($req){
		// Verify hash
		$transfer_info = controller("api/transfer")->verifyHash($req->hash, $req->redirect_url);

		// Verify AccessToken in transfer_info
		$token_info = controller("api/transfer")->verifyAccessToken($transfer_info, controller("token")->getAccessTokenInfo($transfer_info["access_token"]));

		// Verify Money
		$req->money = controller("api/transfer")->verifyMoney($transfer_info["money"]); 

		$csrf_token = md5(uniqid(mt_rand(), true));
		$redirect_url = $transfer_info["redirect_url"];
		$hash = $req->hash;

		$errors = count($req->error_code) > 0 ? errorstr($req->error_code) : null;

		$app_idx = $token_info["app_idx"];
		$app_info = controller("app")->getInfo($app_idx, ["name"]);
		
		$transfer["user_to_account_number"] = $transfer_info["user_to_account_number"];
		$transfer["user_from_name"] = $transfer_info["user_from_name"];
		$transfer["user_to_name"] = $transfer_info["user_to_name"];
		$transfer["money"] = number_format($transfer_info["money"]);

		$_SESSION["token"] = $csrf_token;


		title("송금");
		layout("main");
		view("auth/transfer", compact(
				"csrf_token",
				"redirect_url",
				"hash",
				"errors",
				"app_info",
				"transfer"
			));
	}
	public function transferProcess($req){
		self::verifyCSRF($req->csrf_token);
		$user_info = self::verifyLogin($req->account_number, $req->pw);
		
		if($user_info != false){
			// Verify hash
			$transfer_info = controller("api/transfer")->verifyHash($req->hash, $req->redirect_url);
			
			// 로그인된 사용자가 HASH의 FROM 사용자와 일치하는지 확인.
			$transfer_info["user_from_idx"] = $user_info["idx"];

			// Verify AccessToken in transfer_info
			$token_info = controller("api/transfer")->verifyAccessToken($transfer_info, controller("token")->getAccessTokenInfo($transfer_info["access_token"]));

			// Verify Money
			$transfer_info["money"] = controller("api/transfer")->verifyMoney($transfer_info["money"]);

			// USER_TO_IDX 잔고 확인.
			$user_from_balance = self::verifyUserBalance($transfer_info["user_from_idx"], $transfer_info["money"]);

			// TRANSACTION
			try {
					transaction();
					$transfer_info["sent_at"] = time();

					// USER_FROM money-- WHERE idx
					// USER_TO money++ WHERE idx
					// transfer_info INSERT
					// transfer SENT 처리

					// USER_FROM money-- WHERE idx
			    if(sql("UPDATE member SET money=money-? WHERE idx=? AND money >= ?", [
			    		$transfer_info["money"],
			    		$transfer_info["user_from_idx"],
			    		$transfer_info["money"]
			    	])->rowCount() != 1){
			    	throw new \Exception("user_from_exception");
			    }

			    // USER_TO money++ WHERE idx
			    if(sql("UPDATE member SET money=money+? WHERE idx=?", [
			    		$transfer_info["money"],
			    		$transfer_info["user_to_idx"]
			    	])->rowCount() != 1){
			    	throw new \Exception("user_to_exception");
			    }

			    // transfer_info INSERT
			    $transfer_history = [];
			    $transfer_history["hash"] = controller("api/transfer")->createHash(64, "transfer_history");
			    if(sql("INSERT INTO transfer_history SET 
							hash=?,
							user1=?,
							user2=?,
							user2_account_number=?,
							money=?,
							balance=?,
							type=?,
							type_memo=?,
							memo=?,
							created_at=?
			    	", [
			    		$transfer_history["hash"],
			    		$transfer_info["user_from_idx"],
			    		$transfer_info["user_to_idx"],
			    		$transfer_info["user_to_account_number"],
			    		$transfer_info["money"],
			    		$user_from_balance - $transfer_info["money"],
			    		"계좌이체",
			    		$transfer_info["user_to_name"],
			    		"",
			    		$transfer_info["sent_at"]
			    	])->rowCount() != 1){
			    	throw new \Exception("transfer_history_exception");
			    }

			    // transfer SENT 처리
			    if(sql("UPDATE transfers SET sent=1 WHERE hash=?", [
			    		$transfer_info["hash"]
			    	])->rowCount() != 1){
			    	throw new \Exception("user_to_exception");
			    }
			    $transfer_info["money"] = number_format($transfer_info["money"]);
					$transfer_info["sent_at"] = date("H:i:s", $transfer_info["sent_at"]);
			    commit();
			} catch (\Exception $e) {
			    rollback();
			    errorView($e->getMessage());
			    exit();
			}

			title("송금 완료");
			layout("main");
			return view("auth/transfer_process", compact(
					"transfer_info",
					"now"
				));
		}else{
			return error("002");
		}

		
	}





	public function accessToken($req){
		$app_idx = $req->app_idx;
		$app_secret_code = !empty($req->client_secret) ? $req->client_secret : $req->app_secret_code;

		$code = $req->code;
		$expires = self::verifyExpires($req->expires);

		$scopes = controller("app")->getScopes($app_idx);

		// Verify MEMBER CODE
		$user_idx = self::verifyCode($code);
		controller("user")->updateCode($user_idx);

		// Verify App
		self::verifyApp($app_idx);

		// Verify APP SECRET CODE
		self::verifyAppSecretCode($app_idx, $app_secret_code);

	
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

		// return json(["access_token" => $access_token]);
		return "access_token=".$access_token;
	}
	
	public function getCode($user_idx){
		$rs = fetch("SELECT code FROM member WHERE idx=?", [$user_idx]);
		return $rs ? $rs["code"] : false;
	}

	// verify
		public function verifyLogin($account_number, $pw){
			$chk = fetch("SELECT idx, code FROM member WHERE account_number=? AND pw=?", [
					$account_number,
					encrypt($pw)
				]);
			return $chk ? $chk : false;
		}
		public function verifyCSRF($client_token){
			if($client_token != $_SESSION["token"]){
				return error("001");
			}
		}

		public function verifyCode($client_code){
			$rs = fetch("SELECT idx, COUNT(*) AS count FROM member WHERE code=?", [$client_code]);
			return $rs["count"] == 1 ? $rs["idx"] : errorJSON("code_invalid", "코드 검증 오류");
		}
		public function verifyAppSecretCode($app_idx, $client_secret_code){
			// $secretCode = 
			$secret_code = controller("app")->getInfo($app_idx, ["secret_code"])["secret_code"];
			if($secret_code != $client_secret_code){
				// return errorJSON("secret_code_invalid", "앱 시크릿 코드 검증 오류");
				return errorJSON($app_idx, $client_secret_code);
			}
		}
		public function verifyExpires($expires){
			$expires = !empty($expires) ? $expires : 60*60*24;
			$expires = $expires > 60*60*24*30 ? 60*60*24*30 : $expires;
			return $expires;
		}
		public function verifyApp($app_idx){
			$rs = controller("app")->getInfo($app_idx, ["verified"]);
			// return $rs ? ($rs["verified"] == 1) : false;
			if(!$rs || $rs["verified"] != 1){
				return errorJSON("not_verified_app", "승인되지 않은 앱");		
			}
			
		}
		public function verifyUserBalance($user_idx, $money){
			$rs = fetch("SELECT money FROM member WHERE idx=?", [$user_idx]);
			if(!$rs){
				errorView("user_idx_invalid", "잔액 검증 중 오류가 발생했습니다.");
			}else if($rs["money"] < $money){
				errorView("not_enough_money", "계좌 잔고가 부족합니다.");
			}else{
				return $rs["money"];
			}

		}
}