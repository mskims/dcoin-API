<?php
namespace dcoin\controllers;

Class UserController {
	public function auth($req, $res){
		$req->pw = md5($req->pw);
		$data = fetchAll("SELECT * FROM member WHERE account_number=? AND pw=?", [$req->id, $req->pw]);
		return json($data, true);
	}
}