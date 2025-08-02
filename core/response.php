<?php

function json_success($payload, int $statusCode = 200):void {
	http_response_code($statusCode);
	header("Content-Type: application/json");
	echo json_encode($payload);
	exit;
}

function json_error(string $message, int $statusCode = 400):void {
	http_response_code($statusCode);
	header("Content-Type: application/json");
	echo json_encode(["error" => $message]);
	exit;
}