<?php
	session_start();
	$sender = $_SESSION['sender'];
	$senderFirst = $_SESSION['senderFN'];
	$senderLast = $_SESSION['senderLN'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$authorIds = explode(",", $_COOKIE['shareWith']);
	$elementId = $_COOKIE['sharePicture'];

	//Include bundle
	include "Universal/functions.php";

	$parseType = ".".explode(".", $elementId)[1];
	if (strpos($parseType, "mp4") || strpos($parseType, "ogg") || strpos($parseType, "webm")) {
		if (strpos($parseType, "mp4")) {
			$type = "mp4";
		}
		else
		if (strpos($parseType, "ogg")) {
			$type = "ogg";
		}
		else
		if (strpos($parseType, "webm")) {
			$type = "webm";
		}

		$message = "
			<video controls> 
				<source src='../Authors/$sender/Album/$elementId' type='video/$type'>
			</video>
		";
	} else {
		$message = "
			@src#../Authors/$sender/Album/$elementId
		";
	}

	$message = str_replace("'", "\'", $message);
	$message =  htmlentities($message);
	
	//Connect to data base
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		foreach ($authorIds as $authorId) {
			$receiver = $authorId;

			$title = "Messages_".$receiver;
			$sql = "CREATE TABLE $title (ID int NOT NULL AUTO_INCREMENT, Messenger LONGTEXT, Receiver LONGTEXT, Message_Content LONGTEXT, Message_Position INT, Message_Status INT, Message_Date LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				sendMessage($sender, $receiver, "0", $conn, $message, $title);
			} else {
				sendMessage($sender, $receiver, "0", $conn, $message, $title);
			}
			
			$title = "Messages_".$sender;
			$sql = "CREATE TABLE $title (ID int NOT NULL AUTO_INCREMENT, Messenger LONGTEXT, Receiver LONGTEXT, Message_Content LONGTEXT, Message_Position INT, Message_Status INT, Message_Date LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				sendMessage($sender, $receiver, "-1", $conn, $message, $title);
			} else {
				sendMessage($sender, $receiver, "-1", $conn, $message, $title);
			}
			
			$sql = "CREATE TABLE pushTable$authorId (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				buildNotification($sender, $authorId, $conn);
			} else {
				buildNotification($sender, $authorId, $conn);				
			}
		}
	}
	$conn->close();

	//Send message
	$content = "Hello there!<br><a href='http://blogy.co?$sender' target='_blank'>@$senderFirst$senderLast</a> just send you a message in <b>Blogy</b>.<br>Come and check it now! :-)";
	send_custom_mail($sender, $authorId, "New message", "New message in Blogy", $content);
	
	echo "
		<script>
			document.cookie = 'shareWith=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			document.cookie = 'sharePicture=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			window.location = 'loadAlbum.php';
		</script>
	";
	
//..........................................//	
	function buildNotification($sender, $followerID, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', 'just send you an image', '$date')";
		$conn->query($sql);
	}
	
	function reCordinateStack($sender, $authorId) {
		$stack = array();
		$loadStack = fopen("../Authors/$sender/Messages/Stack.txt", "r") or die("Unable to load stack.");
		while (!feof($loadStack)) {
			$line = trim(fgets($loadStack));
			if ($line != "") {
				array_push($stack, $line);
			}
		}
		fclose($loadStack);
		
		$line_count = 0;
		while ($line_count < count($stack)) {
			if ($stack[$line_count] == $authorId) {
				$stack = array_merge(array_diff($stack, array("$authorId")));
				break;
			}
			$line_count++;
		}
		array_push($stack, $authorId);
		
		$line_count = 0;
		$stack_reverse = array_reverse($stack);
		$commitStack = fopen("../Authors/$sender/Messages/Stack.txt", "w") or die("Unable to commit.");
		while ($line_count < count($stack_reverse)) {
			fwrite($commitStack, $stack_reverse[$line_count].PHP_EOL);
			$line_count++;
		}
		fclose($commitStack);
	}

	function addToMessages($title, $authorId, $conn) {
		$sql = "INSERT INTO $title (MESSANGER) VALUES ('$authorId')";
		$conn->query($sql);
	}
	
	function sendMessage($sender, $receiver, $owner_message, $conn, $message, $title) {
		$date = date("Y-m-d");

		$message_status = NULL;
		if ( $owner_message == "-1" ) { $message_status = "1"; }

	 	$sql = "INSERT INTO $title (Messenger, Receiver, Message_Content, Message_Position, Message_Status, Message_Date) VALUES ('$sender', '$receiver', '$message', '0', '$message_status', '$date')";
		$conn->query($sql);
	}
?>