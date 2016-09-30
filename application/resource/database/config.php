<?php
$databaseSet = array(
	"host" => env("DATABASE_HOST"),
	"dbname" => env("DATABASE_NAME"),
	"user" => env("DATABASE_USER"),
	"password" => env("DATABASE_PASSWORD"),
	"pdo_setting" => array(
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NAMED
	),
);