<?php
namespace dcoin\controllers;

Class UserController {
	private $allowedColumns = ["name", "account_number", "thumb", "money"];
	public function getInfo($user_idx, $fields){
		// $req->fields = explode("|", $req->fields);
		$columns = ["name", "account_number", "money"];
		if(!empty($fields)){

			$columns = [];
			foreach($fields as $column){
				
				if(true === in_array($column, $this->allowedColumns)){
					$columns[] = $column;
				}
			}
		}
		$columns = implode(", ", array_unique($columns));
		$data = fetch("SELECT {$columns} FROM member WHERE idx=?", [$user_idx]);
		return $data;
	}
	public function getName($req, $res){
		$data = fetch("SELECT name FROM member WHERE account_number=?", [$req->account_number]);
		return json($data);
	}
	public function transfer($req, $res){
		// @todo
	}


	public function updateCode($user_idx){
		$new_code = randomstr(255);
		sql("UPDATE member SET code=? WHERE idx=?", [
				$new_code,
				$user_idx
			]);
		return $new_code;
	}
}