<?php

session_start();
require('sso_conf.php');

try {

	/* Revoke user's access token */
	$post_data = [
		"access_token" => $_SESSION["access_token"],
		"client_id" => CLIENT_ID,
		"client_secret" => CLIENT_SECRET
	];

	$headers[] = "Content-Type: application/x-www-form-urlencoded";

	$url = "https://api.line.me/oauth2/v2.1/revoke";
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = json_decode(curl_exec($ch), true);
	curl_close($ch);
} catch(Exception $e) {

}

header("Location: index.php");
