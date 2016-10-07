<?php
namespace dcoin\controllers;

Class ApiController {
	// Defualt auth for all API REQUEST
	public function auth($req){
		controller("token")->getAccessTokenInfo($req->access_token);
	}
	// /API/ME/INFO ? access_token = ?
	public function getPublicInfo($req, $res){
		return 1;
	}



	// Verify

	
}