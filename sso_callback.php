<?php

session_start();
require('sso_conf.php');

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Line Login</title>
	<link href="css/main.css" rel="stylesheet" />
</head>
<body>

<?php
if(isset($_GET["code"])) {
	try {
		/*  Step 1. GET Access Token */
		$post_data = [
			"grant_type" => 'authorization_code',
			"client_id" => CLIENT_ID,
			"client_secret" => CLIENT_SECRET,
			"code" => $_GET["code"],
			"redirect_uri" => REDIRECT_URI
		];

		$headers[] = "Content-Type: application/x-www-form-urlencoded";

		$url = "https://api.line.me/oauth2/v2.1/token";
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = json_decode(curl_exec($ch), true);
		curl_close($ch);


		/*  Step 2. GET User Profile */
		$accessToken = $response["access_token"];
		if(!empty($accessToken)) {

			$_SESSION["access_token"] = $accessToken;

			$headerData = [
				"content-type: application/x-www-form-urlencoded",
				"charset=UTF-8",
				'Authorization: Bearer ' . $accessToken,
			];
			$ch2 = curl_init();
			curl_setopt($ch2, CURLOPT_HTTPHEADER, $headerData);
			curl_setopt($ch2, CURLOPT_URL, "https://api.line.me/v2/profile");
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch2);
			$httpcode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
			curl_close($ch2);
			$result = json_decode($result, true);
			if($httpcode == 200) {
?>
	<a href="do_logout.php">Logout</a>
	<br /><br />
	<h2> Welcome, here is your prifile:</h2>
	<table>
		<tr>
			<th>Column</th>
			<th>Data</th>
		</tr>
		<tr>
			<td>UserID</td>
			<td><?php echo $result["userId"]; ?></td>
		</tr>
		<tr>
			<td>Name</td>
			<td><?php echo $result["displayName"]; ?></td>
		</tr>
		<tr>
			<td>Picture</td>
			<td><img style="max-width: 100px" src="<?php echo $result['pictureUrl']; ?>" /></td>
		</tr>
		<tr>
			<td>Status Message</td>
			<td><?php echo $result["statusMessage"]; ?></td>
		</tr>
	</table>		
<?php
			} else {
				echo "<h2>Login fail, try again later.</h2>";
			}
		}
	} catch(Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}
?>

</body>
</html>