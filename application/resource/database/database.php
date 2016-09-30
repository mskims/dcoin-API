<?php
include_once __DIR__."/config.php";

Class Database {
	private static $db = null;
	private function init(){
		global $databaseSet;
		self::$db = new PDO("mysql:host={$databaseSet['host']};dbname={$databaseSet['dbname']}", $databaseSet["user"], $databaseSet["password"], $databaseSet["pdo_settings"]);
	}
	public function sql($sql, $p=null){
		self::init();
		$rs = self::$db->prepare($sql);
		$rs->execute($p);
		return $rs;
	}
	public function fetchAll($sql, $p=null){
		return $this->sql($sql, $p)->fetchAll();
	}
	public function fetch(){
		return $this->sql($sql, $p)->fetch();
	}
	public function count(){
		return $this->sql($sql, $p)->rowCount();	
	}
	public function lastIndex(){
		self::init();
		return self::$db->lastInsertId();
	}
}