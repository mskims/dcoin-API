<?php
namespace dcoin\controllers;

Class AuthController {
	public function login($req, $res, $service){
		$csrf_token = md5(uniqid(mt_rand(), true));
		$redirect_url = $req->redirect_url;
		$errors = count($req->error_code) > 0 ? errorstr($req->error_code) : null;
		$_SESSION["token"] = $csrf_token;
		layout("main");
		view("auth/login", compact("csrf_token", "redirect_url", "errors"));
	}
	public function loginProcess($req, $res, $service){
		if($req->csrf_token == $_SESSION["token"]){

			$chk = fetch("SELECT * FROM member WHERE account_number=? AND pw=?", [
					$req->account_number,
					encrypt($req->pw)
				]);
			if($chk){
				$skey = $chk["skey"];
				$redirect_url = $req->redirect_url;
				return view("auth/login_process", compact("redirect_url", "skey"));
			}else{
				return error("002");
			}
		}else{
			return error("001");
		}
	}
}