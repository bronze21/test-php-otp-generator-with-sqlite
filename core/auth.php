<?php

require_once __DIR__. '/response.php';

function verify_hmac_signature(string $secret) {
	$body = file_get_contents('php://input');
	$body = json_encode(json_decode($body, true));

	$currentSignature = hash_hmac('sha512', $body, $secret);

	$headers = getallheaders();
	$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';	
	$headerSignature = str_replace('HMAC ', '', $authHeader);
	// var_dump(['x-body'=>$headers['x-body'], 'body'=>$body, 'auth'=>$authHeader, 'sig'=>$headerSignature, 'current'=>$currentSignature]);exit;

	if (empty($headerSignature) || $headerSignature !== $currentSignature) {
		return json_error('unauthorized', 401);
	}

	$input = json_decode($body, true);
	if (!isset($input['user_id'])){
		return json_error('unauthorized', 401);
	}
	return $input;
}

