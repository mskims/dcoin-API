<?php
namespace dcoin\database;
include_once __DIR__."/config.php";

Class Database {
	private static $db = null;
	private function init(){
		global $databaseSet;
		self::$db = new \PDO("mysql:host={$databaseSet['host']};dbname={$databaseSet['dbname']}", $databaseSet["user"], $databaseSet["password"], $databaseSet["pdo_setting"]);
	}
	public function sql($sql, $p=null){
		self::init();
		$rs = self::$db->prepare($sql);
		$rs->execute($p);
		return $rs;
	}
	public function fetchAll($sql, $p=null){
		return self::sql($sql, $p)->fetchAll();
	}
	public function fetch($sql, $p=null){
		return self::sql($sql, $p)->fetch();
	}
	public function count($sql, $p=null){
		return self::sql($sql, $p)->rowCount();	
	}
	public function lastIndex(){
		self::init();
		return self::$db->lastInsertId();
	}
}