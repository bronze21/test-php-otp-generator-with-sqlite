<?php

function generate_otp(int $length = 6) {
	return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

function check_otp_active(PDO $db, string|int $user_id) {
	$currentTime = date('Y-m-d H:i:s');
	$stmt = $db->prepare("SELECT user_id, token, expired_at FROM tokens WHERE user_id = :user_id AND expired_at > :currentTime");
	$stmt->execute(['user_id' => $user_id, 'currentTime' => $currentTime]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	return $result ?? null;
}

function check_otp(PDO $db, string|int $user_id, string $otp) {
	$currentTime = date('Y-m-d H:i:s');
	$stmt = $db->prepare("SELECT user_id, token, expired_at FROM tokens WHERE user_id = :user_id AND token = :token AND expired_at > :currentTime");
	$stmt->execute(['user_id' => $user_id, 'token' => $otp, 'currentTime' => $currentTime]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if(!$result){
		return null;
	}
	return $result;
}

function store_otp(PDO $db, string|int $user_id){
	$otp = generate_otp();
	$expiredTime = date('Y-m-d H:i:s', strtotime('+15 minutes'));
	$checkTokenActive = check_otp_active($db, $user_id);
	if($checkTokenActive){
		return json_success([
			'user_id' => $checkTokenActive['user_id'],
			'otp' => (string)$checkTokenActive['token'],
			'expired_at' => $checkTokenActive['expired_at']
		]);
	}
	$checkTokenAvailable = check_otp($db, $user_id, $otp);
	if ($checkTokenAvailable != null) {
		return store_otp($db, $user_id);
	}
	$stmt = $db->prepare("INSERT INTO tokens (user_id, token, expired_at) VALUES (:user_id, :token, :expired_at)");
	$stmt->execute(['user_id' => $user_id, 'token' => $otp, 'expired_at' => $expiredTime]);
	return json_success([
		'user_id' => $user_id,
		'otp' => (string)$otp,
		'expired_at' => $expiredTime
	]);
}

function verify_otp(PDO $db, string|int $user_id, string $otp) {

	$result = check_otp($db, $user_id, $otp);
	
	if(!$result){
		return json_error('token invalid', 401);
	}
	return json_success(['status'=>'token valid']);
	
}