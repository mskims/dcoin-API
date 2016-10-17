<?php
namespace dcoin\controllers;

Class ApiController {
	private static $token_info = null;
	// Defualt auth for all API REQUEST
	public function auth($req){
		$token_info = controller("token")->getAccessTokenInfo($req->access_token);

		// VERIFY EXIST
		self::verifyAccessToken($token_info);
		
		// VERIFY EXPIRE
		self::verifyAccessTokenWithExpires($token_info["expires_at"]);

		// VERIFY SECRET-CODE
		self::verifyAccessTokenWithAppSecretCode($token_info["app_idx"], $token_info["secret_code"]);

		self::$token_info = $token_info;
	}
	// /API/TOKEN/INFO ? access_token = ?
	public function getAccessTokenInfo($req, $res){
		return filterJSON(self::$token_info, ["user_idx", "scopes", "created_at", "expires_at"]);
	}

	// /API/ME/INFO ? access_token = ? & user_idx = ?
	public function getMyInfo($req){
		self::verifyScope("public_info");
		self::verifyUserIdxWithAcessToken($req->user_idx, $req->access_token);

		$fields = !empty($req->fields) ? explode(",", $req->fields) : null;
		$my_info = controller("user")->getInfo($req->user_idx, $fields);

		return filterJSON($my_info, ["name", "account_number", "thumb", "money"]);
	}

	// /API/ME/TRANSFER_HISTORY
	public function getMyTransferHistory($req){
		self::verifyScope("transfer_history");
		self::verifyUserIdxWithAcessToken($req->user_idx, $req->access_token);
		
		$from = !empty($req->from) && $req->from > -1 ? $req->from : 0;
		$limit = !empty($req->limit) && $req->limit <= 10 ? $req->limit : 10;

		$datas = fetchAll("SELECT
			transfer_history.idx AS transfer_idx,
			IF(transfer_history.user2=?, 'true', 'false') AS income,
			transfer_history.money,
			transfer_history.balance,
			a.name AS from_name,
			a.account_number AS from_account_number,
			b.name AS to_name,
			b.account_number AS to_account_number,
			transfer_history.type,
			transfer_history.type_memo,
			transfer_history.memo,
			transfer_history.created_at
			FROM transfer_history
			LEFT JOIN member AS a
			ON transfer_history.user1 = a.idx
			LEFT JOIN member AS b
			ON transfer_history.user2 = b.idx
			WHERE
				transfer_history.user1 = ? OR transfer_history.user2 = ?
			ORDER BY 
				transfer_history.idx DESC
			LIMIT {$from}, {$limit}", [
				$req->user_idx,
				$req->user_idx,
				$req->user_idx
			]);

		// INDEXING
		$index = $from+1;
		foreach($datas as $i=>$rs){
			$datas[$i] = ["idx"=>"".$index++] + $datas[$i];
			$datas[$i]["from"] = [
				"name"=>$rs["from_name"],
				"account_number"=>$rs["from_account_number"]
			];
			$datas[$i]["to"] = [
				"name"=>$rs["to_name"],
				"account_number"=>$rs["to_account_number"]
			];
			unset($datas[$i]["from_name"]);
			unset($datas[$i]["from_account_number"]);
			unset($datas[$i]["to_name"]);
			unset($datas[$i]["to_account_number"]);
			
		}
		
		return json($datas);
	}


	// /API/ME/TRANSFER



	// Verify
	public function verifyAccessToken($token_info){
		if($token_info === false){
			return errorJSON("acess_token_invalid", "엑세스 토큰이 유효하지 않습니다.");
		}
	}
	public function verifyAccessTokenWithAppSecretCode($app_idx, $secret_code){
		if(controller("app")->getInfo($app_idx, ["secret_code"])["secret_code"] != $secret_code){
			return errorJSON("secret_code_changed", "시크릿 코드가 만료되었습니다.");	
		}
	}
	public function verifyAccessTokenWithExpires($expires_at){
		if($expires_at < time()){
			return errorJSON("acess_token_expired", "엑세스 토큰이 만료되었습니다.");	
		}
	}
	public function verifyUserIdxWithAcessToken($user_idx, $access_token){
		if(self::$token_info["user_idx"] != $user_idx){
			return errorJSON("user_idx_invalid", "잘못된 사용자 번호입니다.");
		}
	}
	public function verifyScope($scope_name){
		// 1. 토큰 ROW 검사
		if(
			in_array($scope_name, self::$token_info["scopes"]) != true
			|| controller("scope")->verifyScope(self::$token_info["app_idx"], $scope_name) != true
			){
			return errorJSON("{$scope_name}_access_denied", "권한이 없습니다.");
		}

		// 2. 스코프 테이블 검사
		
	}
}