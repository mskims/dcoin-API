<?php
include_once __DIR__."/errors/codes.php";

// dotenv
$dotenv = new Dotenv\Dotenv(__ROOT__);
$dotenv->load();	
function env($key){
		return getenv($key);
}

// db
function sql($sql,$p=null){
	return \dcoin\database\Database::sql($sql, $p);
}
function fetchAll($sql,$p=null){
	return \dcoin\database\Database::fetchAll($sql, $p);
}
function fetch($sql,$p=null){
	return \dcoin\database\Database::fetch($sql, $p);
}
function rcount($sql,$p=null){
	return \dcoin\database\Database::count($sql, $p);
}
function lastIndex(){
	return \dcoin\database\Database::lastIndex();
}


// route
function service(){
	global $route;
	return $route->service();
};
function req(){
	global $route;
	return $route->request();
};
function res(){
	global $route;
	return $route->response();
};

function controller($path){
	$controller = "";

	$path = explode("/", $path);
	$path[count($path)-1] = ucfirst($path[count($path)-1])."Controller";
	$controller = $path[count($path)-1];

	$path = implode("/", $path);
	require_once(__CNTS__.$path.".php");
	$class = '\\dcoin\\controllers\\'.$controller;
	return new $class;
}



function layout($path){
	$service = service();
	$service->layout(__VIEWS__."layouts/".$path.".layout.php");
	return $service;
}
function view($path, $argus=[]){
	$service = service();
	$service->render(__VIEWS__."/".$path.".php", $argus);
	// return $service;
}
function json($data, $pretty=true){
	$options = $pretty ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE : JSON_UNESCAPED_UNICODE;
	if(is_null($data) || $data==false){
		$data = ["error"=>"Data not found"];
	}
	return json_encode($data, $options);
}

// error
function error($error_code){
	$res = res();
	$req = req();
	$url = strtok($req->server()->get("HTTP_REFERER"), "?")."?redirect_url={$req->redirect_url}&error_code={$error_code}";
	$res->redirect($url)->send();
}
function errorstr($codes){
	global $errorCodeSet;
	$res = [];
	$codes = explode("|", $codes);
	foreach($codes as $code){
		$res[] = $errorCodeSet[$code];
	}
	return $res;
}


// enc

function encrypt($str){
	$encrypted = env("SHA512_SALT");
	$encrypted = hash("sha512", str_replace(":value", $str, $encrypted));
	return $encrypted;
}