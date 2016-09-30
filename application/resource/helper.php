<?php

// dotenv
$dotenv = new Dotenv\Dotenv(__ROOT__);
$dotenv->load();	
function env($key){
		return getenv($key);
}