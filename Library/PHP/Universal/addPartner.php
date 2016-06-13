<?php
	$getToken = $_COOKIE['Token'];

	if ($getToken == 1) {
		setcookie("Token", "", time() - 3600);
?>

<!DOCTYPE html>
<html>
<head>
	<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
	<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>

	<title>Blogy</title>

	<link href='MiniLib/style.css' rel='stylesheet' type='text/css' media='screen' />
	<link href='../../../fonts.css' rel='stylesheet' type='text/css'>
	<link rel='stylesheet' href='../../../fonts.css' />
	
	<script type='text/javascript' src='MiniLib/java.js'></script>
	<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
</head>
<body>
	<div id="main-container">
		<div id="header">
			<h1>Thank you !</h1>
			<p>
				Hello there, you just made our day better! 
				In return we don't offer you a lot but if you want, you can tell us who is our new friend. 
				Once again, thank you ! :-)
			</p>
			<h2>Blogy<span>&#8482;</span></h2>
		</div>
		<div id="body">
			<form id="form-input" method="post">
				<input type="text" class="0" id="fName" name="fName" placeholder="First name" onkeyup="checkName('fName');"></input>
				<input type="text" class="1" id="lName" name="lName" placeholder="Last name" onkeyup="checkName('lName');"></input>
				<input type="text" class="2" id="logoLink" name="logoLink" placeholder="Link to your logo" onchange="checkUrl('logoLink');"></input>
				<input type="text" class="3" id="socLink" name="socLink" placeholder="Link your website or social profile" onchange="checkUrl('socLink');"></input>
				
				<div id="nav">
					<button type="button" id="share-button" class="share">Share</button>
					<button type="button" class="decline" onclick="window.location='../../../index.html';">Stay anonimous</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>

<?php 
	} else {
		header("Location: ../../../SignIn.html");
	}
?>