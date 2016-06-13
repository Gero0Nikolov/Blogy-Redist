<?php
	session_start();
	$sender = $_SESSION['sender'];

	if (!isset($sender)) {
		header("Location: ../../../index.html");
	}

	$getId = $_COOKIE['getId'];

	include "../Universal/dataBase.php";
	include "../Universal/functions.php";
	include "loadStories.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT STORYTITLE, STORYLINK, STORYCONTENT, LIKES FROM stack$sender WHERE ID=$getId";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getTitle = $row["STORYTITLE"];
			$getLink = $row["STORYLINK"];
			$getContent = $row["STORYCONTENT"];
			$getLikes = $row["LIKES"];

			$isFinded = 1;
		}
	}

	//Close the connection
	$conn->close();

	$convertTitle = str_replace("6996", " ", $getTitle);

	//Build page overview
	echo "
	<html>
		<head>
			<meta name='viewport' content='user-scalable=no'/>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>

			<title>$convertTitle</title>

			<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../../fonts.css' rel='stylesheet' type='text/css'>		
			
			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

			<script type='text/javascript' src='JAVA/java.js'></script>		
			<script type='text/javascript' src='../../../java.js'></script>
			
			<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../../LightBox/js/lightbox.min.js'></script>
		</head>
		<body>
";
	
	include 'loadMenu.php';

	if (isset($isFinded)) {
echo "
			<div id='body' style='margin-top: 112px;'>
				<table id='main-table'>
";

	//Print story build
	echo parseContent($getTitle, $getLink, $getContent, $getLikes, 0, "3", $getId, $sender);

echo "
				</table>
			</div>
";
	} else {
		echo "<h2 id='error-message'>Oops, it seems that you have deleted this story.</h2>";
	}

echo "
		<body>
		<script>document.cookie = 'getId=; expires=Thu, 01 Jan 1970 00:00:00 UTC';</script>
	</html>
";
?>