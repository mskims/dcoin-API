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
function transaction(){
       return \dcoin\database\Database::transaction();
}
function rollBack(){
       return \dcoin\database\Database::rollBack();
}
function commit(){
       return \dcoin\database\Database::commit();
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


function title($str){
	$service = service();
	$service->pageTitle = $str;
	return $service;
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
function filterJSON($data, $allowed){
	return pureJSON($data, $allowed);
}
function pureJSON($data, $filter=false){
	return json($data, true, false, $filter);
}
function json($data, $pretty=true, $errors=true, $filter=false){
	$options = $pretty ? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE : JSON_UNESCAPED_UNICODE;
	if(is_null($data) || $data==false){
		if($errors === true){
			return errorJSON("data_not_found", "일치하는 데이터가 없습니다.");	
		}else{
			return;	
		}
	}

	if($filter !== false){
		$data = array_intersect_key($data, array_flip($filter));
	}
	return json_encode($data, $options);
}

// error
function error($error_code){
	$res = res();
	$req = req();
	// $url = strtok($req->server()->get("HTTP_REFERER"), "?")."?redirect_url={$req->redirect_url}&error_code={$error_code}";
	$url = strtok($req->server()->get("HTTP_REFERER"), "?")."?redirect_url={$req->redirect_url}";
	$url .= isset($req->hash) ? "&hash={$req->hash}" : "";
	$url .= "&error_code={$error_code}";
	$res->redirect($url)->send();
}
function errorView($type, $message="error"){
	title("오류");
	layout("main");
	view("error", errorArray($type, $message));
	exit();
}
function errorJSON($type, $message="error"){

	$rs = ["error"=>["type"=>$type, "message"=>$message]];
	echo json($rs);
	exit();
}
function errorArray($type, $message){
	$rs = ["error"=>["type"=>$type, "message"=>$message]];
	return $rs;
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

// etc

function randomstr($length){
	$base = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-_";
	$baselen = strlen($base);
	$str = "";
	for($i=0;$i<$length;$i++){
		$str .= $base[rand(0, $baselen-1)];
	}
	return $str;
}
function isExist($needle, $table, $column){
	$rs = fetch("SELECT COUNT(*) AS count FROM {$table} WHERE {$column}=?", [$needle]);
	return $rs["count"] > 0;
}


// verifiy