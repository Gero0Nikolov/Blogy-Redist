<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];
	
	$loops = 0;
	$stack = array();

	//Connect to Database
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Collect the information
	$sql = "SELECT Author_UID FROM WorldBloggers ORDER BY ID";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			if ($sender != $row["Author_UID"]) {
				array_push($stack, trim($row["Author_UID"]));
				$loops++;
			}
		}
	}

	//Close the connection
	$conn->close();

	//Convert the stack
	$stack = implode(",", $stack);

echo "
		<html>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
				<title>Authors</title>
				<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../fonts.css' rel='stylesheet' type='text/css'>

				<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

				<script type='text/javascript' src='../../java.js'></script>

				<script type='text/javascript'>
				</script>
			</head>
			<body onload='loadBloggers($loops, \"$stack\", \"blogers-list\")'>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "	
				<div id='sub-menu'>
					<div id='otherOption' class='left'>
						<a href='loadFriends.php'>Friends</a>
					</div>
					<div id='currentRight'>
						<a href='loadBlogers.php'>Authors</a>
					</div>
				</div>
				<div id='body'>
					<div id='blogers-list'>
					</div>
				</div>
			</body>
		</html>	
";
?>