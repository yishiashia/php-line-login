<?php

require('sso_conf.php');

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Line Login</title>
	<link href="css/main.css" rel="stylesheet" />
</head>
<body>
	<a class="line-login-btn" href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=<?php echo urlencode(CLIENT_ID); ?>&redirect_uri=<?php echo urlencode(REDIRECT_URI); ?>&state=test&scope=profile%20openid&nonce=_ls978_&max_age=0"></a>
</body>
</html>

