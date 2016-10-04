<?php

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
function service(){
	global $route;
	return $route->service();
};


function layout($path, $service){
	$service->layout(__VIEWS__."/".$path.".php");
	return $service;
}
function view($path){
	$service = service();
	$service->render(__VIEWS__."/".$path.".php");
	return $service;
}
function json($data, $pretty=false){
	return json_encode($data, $pretty ? JSON_PRETTY_PRINT : null);
}