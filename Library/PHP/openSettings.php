<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];

echo "
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's panel</title>
		<link href='../../style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../fonts.css' rel='stylesheet' type='text/css'>

		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

		<script type='text/javascript' src='../../java.js'></script>

		<script type = 'text/javascript'> 			
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "
		<div id='sub-logo'>
			<h1>Settings</h1>
		</div>
		<div id='body'>
			<div id='settingsOptions'>
				<a href='generalSettings.php'>
					<img src='https://cdn3.iconfinder.com/data/icons/ballicons-free/128/settings.png' />
					General
				</a>
				<a href='messagingSettings.php'>
					<img src='https://cdn3.iconfinder.com/data/icons/ballicons-free/128/bubbles.png' />
					Messaging
				</a>
				<a href='socializeSettings.php'>
					<img src='https://cdn3.iconfinder.com/data/icons/ballicons-free/128/open-box.png' />
					Statistics
				</a>
				<a href='blockedUsers.php'>
					<img src='https://cdn0.iconfinder.com/data/icons/shift-free/32/Block-128.png' />
					Blocked
				</a>
				<a href='reportSettings.php'>
					<img src='https://cdn3.iconfinder.com/data/icons/ballicons-free/128/target.png' />
					Report
				</a>
			</div>
			<div id='footer'>
				<a>&copy; 2016 Blogy</a>
			</div>
<!--
			<form id='controlPanel' action='../PHP/configBuild.php' method='post'>
				<h1>
				<img src='$profilePic' />
				<br>
				Profile picture
				</h1>
				<input type='text' value='$profilePic' id='profilePic' name='profilePic'></input>
				<br>
				<h1>Social profile</h1>
				<input type='text' value='$profileHref' id='profileHref' name='profileHref'></input>
				<br>
				<h1>First name</h1>
				<input type='text' value='$profileFirst' id='fName' name='fName'></input>
				<br>
				<h1>Last name</h1>
				<input type='text' value='$profileLast' id='lName' name='lName'></input>
				<br>
				<h1>Password</h1>
				<input type='password' value='$pass' id='pass' name='pass'></input>
				<input type='hidden' value='$fullName' name='sender'>
				<br>
				<div class='addMarginSmall'></div>
				<a href='#' onclick='start()'>Save</a>
				<div class='addMarginSmall'></div>
			</form>
-->
		</div>
	</body>
";
?>