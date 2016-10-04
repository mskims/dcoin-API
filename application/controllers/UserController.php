<?php
namespace dcoin\controllers;

Class UserController {
	private $allowedColumns = ["name", "account_number", "thumb", "money"];
	public function getInfo($req, $res){
		$req->items = explode("|", $req->items);
		$columns = ["name", "account_number", "money"];
		foreach($req->items as $column){
			if(true === in_array($column, $this->allowedColumns)){
				$columns[] = $column;
			}
		}
		$columns = implode(", ", array_unique($columns));
		$data = fetch("SELECT {$columns} FROM member WHERE skey=?", [$req->key]);
		return json($data);
	}
	public function getName($req, $res){
		$data = fetch("SELECT name FROM member WHERE account_number=?", [$req->account_number]);
		return json($data);
	}
	public function transfer($req, $res){
		// @todo
	}
}