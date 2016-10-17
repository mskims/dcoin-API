<?php

include_once __DIR__."/config.php";

$route->respond(function ($request, $response, $service, $app) use ($route) {
    // Handle exceptions => flash the message and redirect to the referrer
});
$route->onError(function ($route, $err_msg) {
	echo $err_msg;
});
$route->respond("GET", "/api/[*]", function($req, $res, $service, $app){
	return controller("api")->auth($req);
});
$route->respond("GET", "/api/token/info", function($req, $res, $service, $app){
	return controller("api")->getAccessTokenInfo($req, $res);
});
$route->respond("GET", "/api/me/info", function($req, $res, $service, $app){
	return controller("api")->getMyInfo($req, $res);
});
$route->respond("GET", "/api/me/transfer_history", function($req){
	return controller("api")->getMyTransferHistory($req);
});
$route->respond("GET", "/api/transfer/create", function($req, $res, $service, $app){
	return controller("api/transfer")->create($req);
});


// AUTH
$route->respond("GET", "/auth/login", function($req, $res, $service, $app){
	return controller("auth")->login($req, $res, $service);
});
$route->respond("POST", "/auth/login_process", function($req, $res, $service, $app){
	return controller("auth")->loginProcess($req, $res, $service);
});

$route->respond("GET", "/auth/transfer", function($req, $res, $service, $app){
	return controller("auth")->transfer($req);
});
$route->respond("POST", "/auth/transfer_process", function($req, $res, $service, $app){
	return controller("auth")->transferProcess($req);
});

$route->respond("/auth/access_token", function($req, $res, $service,$app){
	return controller("auth")->accessToken($req);
});

$route->dispatch();
