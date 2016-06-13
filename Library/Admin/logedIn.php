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

	<title>Admin: <?php echo $adminFN." ".$adminLN; ?></title>

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
<body onload="requestPlaces('#left-container', 0); requestReports('#right-container'); requestPlugins('#left-container', 0);">
	<?php include "loadMenu.php"; ?>
	<!-- Explore new places -->
	<div id="left-container">
		<h1>Requested places</h1>
		<div id="content-container" class="places">
		</div>
		<h1>Requested plugins</h1>
		<div id="content-container" class="plugins">
		</div>
	</div>
	<!-- Explore reports -->
	<div id="right-container">
		<h1>User reports</h1>
		<div id="content-container">
		</div>
	</div>
</body>
</html>