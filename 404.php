<head>
	<title>404</title>
	<link rel='shortcut icon' href='http://<?php echo $_SERVER["HTTP_HOST"]; ?>/Library/images/Blogy-ICO.png' type='image/x-icon'>
	<link rel='icon' href='http://<?php echo $_SERVER["HTTP_HOST"]; ?>/Library/images/Blogy-ICO.png' type='image/x-icon'>
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<link href='http://<?php echo $_SERVER["HTTP_HOST"]; ?>/fonts.css' rel='stylesheet' type='text/css'>	

	<style type="text/css">
		body {
			background: #e5e5e5;
		}
		#e-container {
			display: table;
			width: 100%;
			height: 100%;
		}
		#e-container .e-cell {
			display: table-cell;
			width: 100%;
			height: 100%;
			text-align: center;
			vertical-align: middle;

			color: #333;

			
		}

		img {
			display: block;
			width: 200px;
			text-align: center;
			margin: 0px auto;
		}
		h1 {
			font-family: 'Roboto', sans-serif;
			font-size: 3.5rem;
			font-weight: bold;

			letter-spacing: 0.5px;
		}
		a {
			font-family: 'Roboto', sans-serif;
			font-size: 1.5rem;
			font-weight: normal;
			text-decoration: none;
			color: #333;
			letter-spacing: 0.5px;

			display: inline-block;

			-webkit-transition: all 0.15s ease-in-out;
			-moz-transition: all 0.15s ease-in-out;
			-o-transition: all 0.15s ease-in-out;
			transition: all 0.15s ease-in-out;
		}
		a span { 
			font-family: Iconic; 
			display: inline-block; 
			-webkit-transition: all 0.15s ease-in-out;
			-moz-transition: all 0.15s ease-in-out;
			-o-transition: all 0.15s ease-in-out;
			transition: all 0.15s ease-in-out;
		}
		a:hover {
			transform: translateX(5px);
		}
		a:hover span {
			transform: translateX(-5px);
		}

		.travolta {
		    position: fixed;
		    height: 400px;
		    width: 670px;
		    bottom: 0;
		    z-index: 10;
		    pointer-events: none;
		    background: url(http://i.imgur.com/e1IneGq.gif) no-repeat;
		    right: 73px;
		}

		.mascot {
			position: fixed;
			left: 100px;
			bottom: 0;

			width: 200px;
			height: 200px;
		}
	</style>
</head>
<body>
	<div id="e-container">
		<div class="e-cell">
			<img src="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/Library/images/Stickers/Bun/dafack.png" alt="It is dead" />
			<h1>It is dead, Jim...</h1>
			<a href="http://<?php echo $_SERVER["HTTP_HOST"]; ?>"><span>&#xf104;</span>Go back</a>
		</div>
	</div>
</body>