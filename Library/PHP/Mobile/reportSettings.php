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
		<title>$profileFirst's panel</title>
		<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>

		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

		<script type='text/javascript' src='JAVA/java.js'></script>
		<script type='text/javascript' src='../../../java.js'></script>

		<script type = 'text/javascript'> 			
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
echo "	
		<div id='sub-logo'>
			<h1>Report a problem</h1>
		</div>
		<div id='body'>
			<form id='reportData' method='post'>
				<textarea id='reportedData' name='reportedData' placeholder='What is the problem ?'></textarea>
				<button type='button' onclick='reportData()'>Report</button>
			</form>
";
echo "
		</div>
	</body>
";
?>