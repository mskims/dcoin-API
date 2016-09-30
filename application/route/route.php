<?php
include_once __DIR__."/config.php";

$route->respond("GET", "/auth/login", function($req, $res){

	$res->redirect("/login")->send();
});

$route->dispatch();
