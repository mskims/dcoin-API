<?php
namespace dcoin\controllers;

Class ScopeController {
	public function verifyScope($app_idx, $scope_name){
		$scope = fetch("SELECT * FROM scopes WHERE app_idx=? AND scope=? AND verified = 1", [$app_idx, $scope_name]);
		return $scope ? true : false;
	}
}