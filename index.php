<?php

require_once __DIR__. '/core/response.php';
require_once __DIR__. '/core/auth.php';
require_once __DIR__. '/core/otp.php';

$secret = "TestDigivo@2025";

$db = new PDO("sqlite:db.sqlite");

header("Content-Type: application/json");


/* Check Header Authorization */
$inputData = verify_hmac_signature($secret);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method==='POST'){
	if($path == '/otp/generate'){
		return store_otp($db, $inputData['user_id']);
	}
	if($path == '/otp/verify'){
		return verify_otp($db, $inputData['user_id'], $inputData['otp']);
	}

	return json_error('path not found', 404);
}

return json_error('method not allowed', 405);
