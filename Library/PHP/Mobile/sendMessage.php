<?php
	$line_count = 0;
	$toSend = (string)NULL;

	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$senderImg = $_SESSION['senderImg'];
	$senderHref = $_SESSION['senderHref'];
	$senderFirst = $_SESSION['senderFN'];
	$senderLast = $_SESSION['senderLN'];
	
	$authorId = $_COOKIE['authorId'];
	/*if (!isset($authorId)) {
		echo "<script>window.close();</script>";
	}*/

	$receiver = $authorId;

	//Include bundle
	include "../Universal/functions.php";
	
	//Build message
	$message = trim($_POST['content']);
	$message = nl2br($message);
	$message = str_replace("'", "\'", $message);
	$message =  htmlentities($message);
	
	//Connect to data base
	include "../Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
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
	$conn->close();
	
	reCordinateStack($sender, $authorId);
	reCordinateStack($authorId, $sender);
	
	$pullNotification = fopen("../../Authors/$authorId/Messages/Notification.txt", "r") or die("Bad request for pull.");
	$pullCount = fread($pullNotification, filesize("../../Authors/$authorId/Messages/Notification.txt"));
	$count = (int)$pullCount + 1;
	fclose($pullNotification);
	
	$commitNotification = fopen("../../Authors/$authorId/Messages/Notification.txt", "w") or die("Unable to commit.");
	fwrite($commitNotification, $count);
	fclose($commitNotification);

	//Send message
	$content = "Hello there!<br><a href='http://blogy.co?$sender' target='_blank'>@$senderFirst$senderLast</a> just send you a message in <b>Blogy</b>.<br>Come and check it now! :-)";
	send_custom_mail($sender, $authorId, "New message", "New message in Blogy", $content);

	/*//Check notifications
	$lineCount = 0;
	$pullConfig = fopen("../../Authors/$authorId/config.txt", "r") or die("Unable to open author config.");
	while (!feof($pullConfig)) {
		$line = trim(fgets($pullConfig));
		if ($lineCount == 8) {
			$toSend = $line;
			break;
		}
		$lineCount++;
	}
	fclose($pullConfig);
	
	if ($toSend == "1") {
		$subject = "New message in Blogy";
		$content = "Hello there. $senderFN just send you a message. Check it from here: http://www.blogy.sitemash.net/SignIn.html";
		mail($authorMail, $subject, $content);
	}
*/
	echo "<script>window.location='readMessage.php?$authorId'</script>";
	
	function buildNotification($sender, $followerID, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', 'just send you a message', '$date')";
		$conn->query($sql);
	}
	
	function reCordinateStack($sender, $authorId) {
		$stack = array();
		$loadStack = fopen("../../Authors/$sender/Messages/Stack.txt", "r") or die("Unable to load stack.");
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
		$commitStack = fopen("../../Authors/$sender/Messages/Stack.txt", "w") or die("Unable to commit.");
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

	$cmd = $_POST['cmd'];
	if ($cmd == "0") {
		header("Location: openBloger.php?$authorId");
	}
	else
	if ($cmd == "1") {
		header("Location: readMessage.php?$authorId");
	}
?>