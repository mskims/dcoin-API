<?php
namespace dcoin\controllers;

Class AppController {
	public function getInfo($app_idx, $columns){
		$columns = implode(",", $columns);
		return fetch("SELECT {$columns} FROM apps WHERE idx=?", [$app_idx]);
	}
	public function getScopes($app_idx){
		return explode("|", fetch("SELECT GROUP_CONCAT(scope SEPARATOR '|') AS scopes FROM scopes WHERE app_idx=?", [$app_idx])["scopes"]);
	}
	
	
}