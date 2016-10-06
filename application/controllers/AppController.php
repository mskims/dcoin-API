<?php
namespace dcoin\controllers;

Class AppController {
	public function getInfo($app_idx, $columns){
		$columns = implode(",", $columns);
		return fetch("SELECT {$columns} FROM apps WHERE idx=?", [$app_idx]);
	}
	
	
}