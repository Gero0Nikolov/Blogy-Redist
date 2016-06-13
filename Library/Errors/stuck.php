<!DOCTYPE html>
<html>
<head>
	<title>Stuck Page</title>
	
	<link href='http://<?php echo $_SERVER["HTTP_HOST"]; ?>/fonts.css' rel='stylesheet' type='text/css'>	

	<style type="text/css">
		body {
			background: #e5e5e5;
			padding: 0 2.5%;
		}

		img {
			display: block;
			width: 500px;
			text-align: center;
			margin: 0px auto 10%;
		}

		.description {
			display: block;
			font-family: OpenSansRegular;
			font-size: 2rem;
			color: #333;
			margin-top: 10%;
			margin-bottom: 10%;
		}

		a { text-decoration: none; outline: 0; }
		.full-button,
		.bordered-button {
			padding: 25px;
			display: block;
			font-family: OpenSansRegular;
			font-size: 2rem;
			border-radius: 3px;
			text-align: center;
			margin: 2.5% auto;
		}

		.full-button {
			background: #59B5FF;
			color: #fff;
		}
		.bordered-button {
			border: 3px solid #59B5FF;
			color: #59B5FF;
		}
	</style>
</head>
<body>
	<div class="description">
		<img src="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/Library/images/Stickers/Bun/confused.png" alt="It is dead" />
		Mobile Blogy has passed away...<br>
		Currently we are redesigning it to make it better.<br>
		Come an see what's new in the Beta. <br>
		We are also opened for a suggestions! <br>
		Cheers :-) <br>
	</div>
	<a href="http://blogy.co/Library/Mobile/logedin.php" class="full-button">Continue in Beta</a>
	<a href="http://blogy.co/Library/PHP/LogOut.php" class="bordered-button">Log out</a>
</body>
</html>