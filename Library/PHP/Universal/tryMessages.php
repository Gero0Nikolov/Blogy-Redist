<?php
	session_start();
	$sender = $_SESSION["sender"];
	$profilePic = $_SESSION["senderImg"];


	$messangerId = $_POST["chatPartner"];
	$messangerImg = $_SESSION["messangerImg"];


	$getLastId = $_POST["lastMessageId"];
	$isMobile = $_POST["mobile"];

	$tableName = "Messages_".$sender;

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$collectIDs = array();

	//Get the last message ID
	$sql = "SELECT ID FROM $tableName WHERE Message_Status IS NULL || Message_Status=0 ORDER BY ID DESC";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push($collectIDs, $row["ID"]);
			$isNewMessages = 1;
		}
	} else {
		$responce = "NNM";
	}

	if ( isset($isNewMessages) ) {
		//Include libraries
		if ( $isMobile == 0 ) { include "../functions.php"; }
		else
		if ( $isMobile == 1 ) { include "../Mobile/mobile-functions.php"; }

		$collectMessages = array();

		foreach ($collectIDs as $id) {
			$sql = "SELECT Messenger, Message_Content FROM $tableName WHERE ID=".$id;
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$authorId = $row['Messenger'];
					$message = $row['Message_Content'];

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

					array_push($collectMessages, $build);
				}
			}

			//Mark as seen
			$sql = "Update $tableName SET Message_Status=1 WHERE ID=$id";
			$conn->query($sql);
		}
	}

	//Close the connection
	$conn->close();

	//Convert messages
	if ( !empty($collectMessages) ) {
		$responce = implode("", $collectMessages)."~".$getNewId;
	}

	//Return responce
	echo $responce;
?>