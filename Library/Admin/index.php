<?php
	$get_host = $_SERVER["HTTP_HOST"];
	if ( strpos( $get_host, "dmin.blogy.co" ) ) { header("Location: http://blogy.co/Library/Admin/"); die(); }

	$library_path = "../../Library/";
?>

<html>
<head>
	<meta name='viewport' content='user-scalable=no'/>
	<link rel='shortcut icon' href='<?php echo $library_path; ?>/images/Blogy-ICO.png' type='image/x-icon'>
	<link rel='icon' href='<?php echo $library_path; ?>/images/Blogy-ICO.png' type='image/x-icon'>

	<title>Blogy Admin</title>

	<link href='style.css' rel='stylesheet' type='text/css' media='screen' />
	<link href= '../../fonts.css' rel='stylesheet' type='text/css'>

	<script type='text/javascript' src='main.js'></script>
	<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
</head>
<body onload="Entry();">
	<div id="body">
		<div id="header">
			<h1>Blogy</h1>
			<h2>#Admin</h2>
		</div>
		<form id="login-form" method="post">
			<input type="text" id="username" name="email" placeholder="E-mail">
			<input type="password" id="password" name="password" placeholder="Password">
			<button type="button" onclick="sendLog();">Log in</button>
		</form>
	</div>
</body>
</html>