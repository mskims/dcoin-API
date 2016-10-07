<?php

include_once __DIR__."/config.php";


$route->respond("GET", "/api/[*]", function($req, $res, $service, $app){
	return controller("api")->auth($req, $res);
});
$route->respond("GET", "/api/me/info", function($req, $res, $service, $app){
	return controller("user")->getPublicInfo($req, $res);
});
$route->respond("GET", "/api/user/name", function($req, $res, $service, $app){
	return controller("user")->getName($req, $res);
});

$route->respond("GET", "/api/user/transfer", function($req, $res, $service, $app){
	return controller("user")->transfer($req, $res);
});


// AUTH
$route->respond("GET", "/auth/login", function($req, $res, $service, $app){
	return controller("auth")->login($req, $res, $service);
});
$route->respond("POST", "/auth/login_process", function($req, $res, $service, $app){
	return controller("auth")->loginProcess($req, $res, $service);
});
$route->respond("/auth/access_token", function($req, $res, $service,$app){
	return controller("auth")->accessToken($req);
});

$route->dispatch();
