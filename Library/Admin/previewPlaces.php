<?php
	session_start();
	$admin = $_SESSION["admin"];
	if (!isset($admin)) {
		header("Location: index.php");
	}
	$adminImg = $_SESSION["adminImg"];
	$adminHref = $_SESSION["adminHref"];
	$adminFN = $_SESSION["adminFN"];
	$adminLN = $_SESSION["adminLN"];
?>

<html>
<head>
	<meta name='viewport' content='user-scalable=no'/>
	<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
	<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>

	<title>Places</title>

	<link href='style.css' rel='stylesheet' type='text/css' media='screen' />
	<link href= '../../fonts.css' rel='stylesheet' type='text/css'>

	<script type='text/javascript' src='main.js'></script>
	<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

	<script src='http://maps.google.com/maps/api/js?sensor=false'></script>

	<script type="text/javascript">
		var selectedPlaces = [];
	</script>
</head>
<body onload="requestPlaces('#content-container', 1);">
	<?php include "loadMenu.php"; ?>
	<!-- Explore new places -->
	<div id="fullpage-container">
		<h1>All places</h1>
		<div id="content-container">
			<div class="places">
			</div>
		</div>
	</div>
</body>
</html>