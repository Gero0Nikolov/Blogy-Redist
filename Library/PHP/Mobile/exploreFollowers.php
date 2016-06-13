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
	
	//Pull followers
	$loops = 0;
	$stackSize = filesize("../../Authors/$sender/FollowersID.html");
	if ($stackSize != 0) {
		$stack = array();
		$loadFollowers = fopen("../../Authors/$sender/FollowersID.html", "r") or die("Unable to load followers.");
		while (! feof($loadFollowers)) {
			$line = trim(fgets($loadFollowers));
			if ($line != "") {
				array_push($stack, explode("-", $line)[1]);
				$loops++;
			}
		}
	}

	if (!empty($stack)) $stack = implode(",", $stack);
	
echo "
	<head>
		<meta name='viewport' content='user-scalable=no'/>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
		<title>Your followers</title>
		<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>

		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

		<script type='text/javascript' src='JAVA/java.js'></script>	
		<script type='text/javascript' src='../../../java.js'></script>
	</head>
	<body onload='loadBloggers($loops, \"$stack\", \"blogers-list\")'>
";
	include 'loadMenu.php';
echo "		
		<form id='accountInfo' method='post' style='display: none;'>
			<input type='text' name='sender' value='$sender'></input>
			<input type='text' id='cmd' name='cmd'></input>
		</form>
		
		<div id='sub-logo'>
			<h1>Followers</h1>
		</div>
		<div id='blogers-list'>
";
	if ($stackSize == 0) {
		echo "
			<h1>
				You don't have followers yet :(
			</h1>
		";
	}

echo "
		</div>
	</body>
";
?>