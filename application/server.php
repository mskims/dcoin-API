<?php
// settings
session_start();
ini_set("display_errors", 1);
date_default_timezone_set("Asia/Seoul");
include_once __ROOT__."/vendor/autoload.php";

// functions
include_once __ROOT__."/application/resource/helper.php";
include_once __ROOT__."/application/resource/database/database.php";


include_once __ROOT__."/application/route/route.php";

