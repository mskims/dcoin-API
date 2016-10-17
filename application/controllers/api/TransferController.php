<?php
namespace dcoin\controllers;

Class TransferController {
	public function create($req){

		// @todo
		// self::verifyScope("public_info");
		// self::verifyUserIdxWithAcessToken($req->user_idx, $req->access_token);
		// verify to_number

		$hash = self::createHash(32);
		sql("INSERT INTO transfers SET
				hash=?,
				user_from_idx=?,
				access_token=?,
				user_to_account_number=?,
				money=?,
				redirect_url=?,
				created_at=?
			", [
				$hash,
				$req->user_idx,
				$req->access_token,
				$req->user_to_account_number,
				$req->money,
				$req->redirect_url,
				time()
			]);
		return json(["hash" => $hash]);
	}

	public function getInfo($hash){
		return fetch("SELECT transfers.*, member_to.name AS user_to_name, member_to.idx AS user_to_idx, member_from.name AS user_from_name FROM transfers LEFT JOIN member AS member_to ON transfers.user_to_account_number = member_to.account_number LEFT JOIN member AS member_from ON transfers.user_from_idx = member_from.idx WHERE transfers.hash=?", [$hash]);
	}

	public function createHash($size, $table="transfers", $column="hash"){
		 $hash = randomstr($size);
		 return isExist($access_token, $table, $column) ? self::createHash($size) : $hash;
	}


	// Verfy
	public function verifyMoney($money){
		return $money < 1 ? errorView("min_money_invalid", "최소 송금 금액은 1원입니다.") : $money;
	}
	public function verifyHash($hash, $redirect_url){
		$rs = self::getInfo($hash);

		if(!$rs){
			errorView	("hash_invalid", "존재하지 않는 거래입니다.");
		}else if($rs["created_at"]+60*15 <= time()){
			errorView("hash_expired", "만료된 거래입니다");
		}else if($rs["redirect_url"] != $redirect_url){	
			errorView("redirect_url_invalid", "Redirect URL이 일치하지 않습니다.");
		}else if($rs["sent"] == "1"){
			errorView("ended_transfer", "종료된 거래입니다.");
		}else if(empty($rs["user_to_name"]) === true){	
			errorView("to_account_number_invalid", "존재하지 않는 계좌번호 입니다.");
		}else{
			return $rs;
		}
	}
	public function verifyAccessToken($transfer_info, $access_token){
		
		// Verify Access Token !
		self::verifyAccessTokenValid($access_token);
		self::verifyScopeWithAccessToken("transfer", $access_token);
		self::verifyAccessTokenWithAppSecretCode($access_token["app_idx"], $access_token["secret_code"]);
		self::verifyAccessTokenWithExpires($access_token["expires_at"]);
		self::verifyUserIdxWithAcessToken($transfer_info["user_from_idx"], $access_token["user_idx"]);

		// Verify App
		$auth = controller("auth");
		$auth->verifyApp($access_token["app_idx"]);
		$auth->verifyAppSecretCode($access_token["app_idx"], $access_token["secret_code"]);

		
		return $access_token;
	}
	public function verifyScopeWithAccessToken($scope_name, $access_token){
		// 1. 토큰 ROW 검사
		if(
			in_array($scope_name, $access_token["scopes"]) != true
			|| controller("scope")->verifyScope($access_token["app_idx"], $scope_name) != true
			){
			errorView("{$scope_name}_access_denied", "권한이 없습니다.");
		}
	
	}

	// COPY FROM API CONTROLLER
	public function verifyAccessTokenValid($token_info){
		if($token_info === false){
			errorView("acess_token_invalid", "엑세스 토큰이 유효하지 않습니다.");
		}
	}
	public function verifyAccessTokenWithAppSecretCode($app_idx, $secret_code){
		if(controller("app")->getInfo($app_idx, ["secret_code"])["secret_code"] != $secret_code){
			errorView("secret_code_changed", "시크릿 코드가 만료되었습니다.");	
		}
	}
	public function verifyAccessTokenWithExpires($expires_at){
		if($expires_at < time()){
			errorView("acess_token_expired", "엑세스 토큰이 만료되었습니다.");	
		}
	}
	public function verifyUserIdxWithAcessToken($user_idx, $access_token_user_idx){
		if($access_token_user_idx != $user_idx){
			errorView("user_idx_invalid", "잘못된 사용자 번호입니다.");
		}
	}


}