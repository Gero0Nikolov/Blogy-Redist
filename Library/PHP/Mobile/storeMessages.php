<?php
	session_start();
	$sender = $_SESSION['sender'];
	$profilePic = $_SESSION['senderImg'];

	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	
	$fullName = $sender;
	
	//Connect to data base
	include "../Universal/dataBase.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "CREATE TABLE Messages_$sender (ID int NOT NULL AUTO_INCREMENT, Messenger LONGTEXT, Receiver LONGTEXT, Message_Content LONGTEXT, Message_Position INT, Message_Status INT, Message_Date LONGTEXT, PRIMARY KEY (ID))";
	$conn->query($sql);

	//Close the connection
	$conn->close();
	
echo "
	<html>
		<head>
			<meta name='viewport' content='user-scalable=no'/>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
			<title>Messages</title>
			<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../../fonts.css' rel='stylesheet' type='text/css'>

			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

			<script type='text/javascript' src='JAVA/java.js'></script>
			<script type='text/javascript' src='../../../java.js'></script>

			<script type='text/javascript'>
				function readMessage(id) {
					document.cookie = \"receiverId=\"+id;
					window.location=\"readMessage.php\";
				}
			</script>
		</head>
		<body>
";
	include 'loadMenu.php';
echo "
			<form id='post' method='post' style='display: none;'>
				<input name='sender' value='$sender'></input>
			</form>
			
			<div id='sub-logo'>
				<h1>Messages</h1>
			</div>
			<div id='messages'>
";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$stack = array();
	$sql = "SELECT Messenger, Receiver FROM Messages_$sender ORDER BY ID DESC";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			if ( !in_array( $row['Messenger'], $stack )  && !in_array( $row['Receiver'], $stack ) ) { 
				if ( $row['Messenger'] != $sender ) { array_push($stack, $row['Messenger']); }
				elseif ( $row['Receiver'] != $sender ) { array_push($stack, $row['Receiver']); }
			}
		}
	}	

	$blockedPersons = array();
	$sql = "SELECT BLOCKEDID FROM blockList$sender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push($blockedPersons, $row['BLOCKEDID']);
		}
	}
	
	$line_count = 0;
	while ($line_count < count($stack)) {
		$blockedPersonsByFollower = array();
		$blockerId = $stack[$line_count];
		$sql = "SELECT BLOCKEDID FROM blockList$blockerId";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersonsByFollower, $row['BLOCKEDID']);
			}
		}
		
		if (!in_array($stack[$line_count], $blockedPersons) && !in_array($sender, $blockedPersonsByFollower)) {
			$file_count = 0;
			$parseMessenger = fopen("../../Authors/$stack[$line_count]/config.txt", "r") or die("Unable to parse.");
			while (!feof($parseMessenger)) {
				$line = fgets($parseMessenger);
				if ($file_count == 0) {
					$messengerImg = trim($line);
				}
				else
				if ($file_count == 2) {
					$messengerId = trim($line);
				}
				else
				if ($file_count == 3) {
					$messengerFN = trim($line);
				}
				else
				if ($file_count == 4) {
					$messengerLN = trim($line);
					break;
				}

				$file_count++;
			}
			fclose($parseMessenger);
			
			++$count;
			$buildMessage = "
				<a href='readMessage.php?".$messengerId."' class='messenger-container'>
					<div style='background: url(\"$messengerImg\"); background-size: cover; background-position: 50%;' class='img'></div>
					$messengerFN $messengerLN
				</a>
			";
			
			echo "$buildMessage";
		}
		
		$line_count++;
	}
	
	$conn->close(); //Close SQL connection
	
echo "
			</div>
		</body>
	</html>
";
?>