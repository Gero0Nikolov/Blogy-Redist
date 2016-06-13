<?php
	session_start();
	$sender = $_SESSION["sender"];
	$profilePic = $_SESSION["senderImg"];

	$messangerId = $_SESSION["messangerId"];
	$messangerImg = $_SESSION["messangerImg"];

	$build = "";
	$isFound = 0;
	$jump = 0;

	$isMobile = $_POST["mobile"];

	//Include libraries
	if ( $isMobile == 0 ) { include "../functions.php"; }
	else
	if ( $isMobile == 1 ) { include "../Mobile/mobile-functions.php"; }

	//Connect to Database
	include "dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$title = "Messages_".$sender; // Table title

	$getId = $_POST["lastId"]; // Id of the message
	if ( $getId == -1 ) {
		$sql = "SELECT ID FROM $title WHERE Messenger='$messangerId' || Receiver='$messangerId' ORDER BY ID DESC LIMIT 1";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getId = $row["ID"];
			}
		}
	} else {
		$sql = "SELECT ID FROM $title WHERE (Messenger='$messangerId' || Receiver='$messangerId') AND (ID != $getId AND ID < $getId) ORDER BY ID DESC LIMIT 1";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getId = $row["ID"];
			}
		} else {
			$getId = 0;
		}
	}

	$sql = "SELECT Messenger, Message_Content FROM $title WHERE ID=$getId";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {				
			$authorId = $row['Messenger'];
			$message = $row['Message_Content'];
			
			//setcookie("lastMessage", "$message", time() + 3600, "/", $_SERVER['HTTP_HOST']);

			$message = convertMessage($message);
			
			if ( $isMobile == 0 ) {
				if ($authorId == $sender) {
					$build = "
						<div align='right'>
							<div id='HOST'>
								<div class='profileimg'>
									<img src='$profilePic' alt='Bad image link :(' />
								</div>
								<p class='HOST'>
									$message
								</p>
							</div>
							<br>
						</div>
					";
				}
				else
				if ($authorId == $messangerId) {
					$build = "
						<div align='left'>
							<div id='GUEST'>
								<div class='profileimg'>
									<img src='$messangerImg' alt='Bad image link :(' />
								</div>
								<p class='GUEST'>
									$message
								</p>
							</div>
							<br>
						</div>
					";
				}
			} elseif ( $isMobile == 1 ) {
				if ($authorId == $sender) {
					$build = "
						<div align='right'>
							<div id='HOST'>
								<p class='HOST'>
									$message
								</p>
							</div>
						</div>
					";
				}
				else
				if ($authorId == $messangerId) {
					$build = "
						<div align='left'>
							<div id='GUEST'>
								<p class='GUEST'>
									$message
								</p>
							</div>
						</div>
					";
				}
			}
		}
	}

	//Close the connection
	$conn->close();

	//Return responce
	$responce = $getId."~".$build;
	echo $responce;
?>