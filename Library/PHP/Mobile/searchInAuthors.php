<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];
	
	//Get blocked users

	//Connect to data base
	include "../Universal/dataBase.php";
	
	$blockedPersons = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT BLOCKEDID FROM blockList$sender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersons, $row['BLOCKEDID']);
			}
		}

		//Load authors
		$stack = array(); // Authors container

		$sql = "SELECT Author_UID FROM WorldBloggers ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ( !in_array($row["Author_UID"], $blockedPersons) ) {
					$blogerId = $row["Author_UID"];
					$blockedPersonsByUser = array();
					$sql = "SELECT BLOCKEDID FROM blockList$blogerId WHERE BLOCKEDID='".$row["Author_UID"]."'";
					$pick1 = $conn->query($sql);
					if ($pick1->num_rows > 0) { /* BLOCKED */ }
					else { array_push($stack, $row["Author_UID"]); }
				}
			}
		}
	}
	
	$configStack = array();
	foreach ($stack as $author) {
		$pickUpCount = 0;
		$parseUser = fopen("../../Authors/$author/config.txt", "r") or die("Unable to start parsing.");
		while (!feof($parseUser)) {
			$pickUpLine = trim(fgets($parseUser));
			if ($pickUpCount == 0) {
				$authorImg = $pickUpLine;
			}
			else
			if ($pickUpCount == 1) {
				$authorHref = $pickUpLine;
			}
			else
			if ($pickUpCount == 3) {
				$authorFN = $pickUpLine;
			}
			else
			if ($pickUpCount == 4) {
				$authorLN = $pickUpLine;
				break;
			}
			$pickUpCount++;
		}
		fclose($parseUser);

		array_push($configStack, "$authorFN#$authorLN#$author#$authorImg#$authorHref");
	}

	$bindFriends = implode(",", $configStack);

echo "
		<html>
			<head>
				<meta name='viewport' content='user-scalable=no'/>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
				<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
				<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
				<title>Search in Authors</title>
				<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen'>
				<link href='../../../fonts.css' rel='stylesheet' type='text/css'>

				<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
				<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

				<script type='text/javascript' src='JAVA/java.js'></script>
				<script type='text/javascript' src='../../../java.js'></script>

				<script type='text/javascript'>
					var pullFriends = '$bindFriends'.split(',');
					function checkInput() {
						searchFriends(pullFriends, 'searchInput', 'searchResults', 1);
					}
				</script>
			</head>
			<body>
";
	include 'loadMenu.php';
echo "	
				<div id='sub-menu-global'>
					<div id='other'>
						<a href='searchInFriends.php'>Friends</a>
					</div>
					<div id='current'>
						<a href='searchInAuthors.php'>Authors</a>
					</div>
				</div>
				<div id='searchContainer'>
					<input type='text' id='searchInput' placeholder='Search an author' onkeyup='checkInput()'></input>
					<div id='searchResults'>
					</div>
				</div>
			</body>
		</html>	
";
?>