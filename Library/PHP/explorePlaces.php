<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];

echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>All places</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>

				<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

				<script type='text/javascript' src='../../java.js'></script>

				<script src='http://maps.google.com/maps/api/js?sensor=false'></script>
				<script type='text/javascript'>
				</script>
			</head>
			<body onload='loadPlaces(-1, 0, 0);'>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
				<div id='sub-menu'>
					<div id='otherOption' class='left'>
						<a href='myPlaces.php'>My places</a>
					</div>
					<div id='currentRight'>
						<a href='explorePlaces.php'>All places</a>
					</div>
				</div>
				<div id='tagAPlace'>
					<button type='button' title='Current location' onclick='getLocation()'><img src='https://cdn2.iconfinder.com/data/icons/pittogrammi/142/93-512.png' /></button>
				</div>
				<div id='mapContainer'>					
					<button type='button' class='hideButton' onclick='hideMap()'></button>
					<div id='mapHolder'></div>
				</div>
				<div id='body'>
					<div id='placesContainer'>
";
echo "
					</div>
				</div>
			</body>
		</html>	
";

#Scroll to point
	$getScrollPos = $_COOKIE['scrollToPos'];
	if (isset($getScrollPos)) {
		echo "
			<script>
				$(window).scrollTop($getScrollPos);
				document.cookie = 'scrollToPos=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			</script>
		";
	}
?>