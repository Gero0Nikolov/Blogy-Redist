<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../index.php");
	}

	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];

	$url_set = "http://".$_SERVER["HTTP_HOST"];

echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='$url_set/Library/images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='$url_set/Library/images/Blogy-ICO.png' type='image/x-icon'>
			<link href='$url_set/style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='$url_set/fonts.css' rel='stylesheet' type='text/css'>

			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

			<script type='text/javascript' src='$url_set/java.js'></script>

			<link href='$url_set/LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='$url_set/LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='$url_set/LightBox/js/lightbox.min.js'></script>
	";
?>