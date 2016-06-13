<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];

echo "
	<head>
		<meta name='viewport' content='user-scalable=no'/>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Worldwide stories</title>
		<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>

		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

		<script type='text/javascript' src='JAVA/java.js'></script>
		<script type='text/javascript' src='../../../java.js'></script>
		
		<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
		<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
		<script src='../../../LightBox/js/lightbox.min.js'></script>

		<script type = 'text/javascript'> 			
			function loadAll() {		
				document.cookie='scrollToPos='+$(window).scrollTop();
				document.getElementById('reSend').action = '../PHP/exploreStories.php';
				document.forms['reSend'].submit();
			}
		</script>

		<script>
			var loops = 1;
			var lastId = -1;

			function callBack() { //Loads stories for explorer
				loops = 1;
				loadExplorerStories(lastId, \"1\", 1);
			}

			var flag = 0;
		</script>
	</head>
	<body onload='loadExplorerStories(-1, \"1\", 1);' onscroll='checkPos()'>
";
	include 'loadMenu.php';
echo "	
		<div id='sub-menu-global'>
		</div>
		<div id='body'>
			<table id='main-table'>
			</table>
		</div>
	</body>
";

/* SUB-MENU
	<div id='sub-menu-global'>
		<div id='other' class='left'>
			<a href='exploreFStories.php'>Following</a>
		</div>
		<div id='current'>
			<a href='exploreStories.php'>Worldwide</a>
		</div>
	</div>
*/

?>