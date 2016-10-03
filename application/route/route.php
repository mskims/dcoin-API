<?php

include_once __DIR__."/config.php";


$route->respond("GET", "/auth/user", function($req, $res, $service, $app){
	return controller("user")->auth($req, $res);
});


$route->dispatch();
