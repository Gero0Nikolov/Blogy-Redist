<?php
	$conversationId = $_COOKIE['conversationId'];

	session_start();
	$loops = $_SESSION['loops'];

	include "functions.php";

	$sender = explode("AND", $conversationId)[0];
	$profilePic = $_COOKIE['senderImg'];

	$authorId = explode("AND", $conversationId)[1];
	$messangerImg = $_COOKIE['messangerImg'];

	//Connect to data base
	include "Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$title = $conversationId;
		$sql = "SELECT MESSANGER, MESSAGE FROM $title ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			$returnMessage = 0;
			$countLoops = 0;
			while ($row = $pick->fetch_assoc()) {
				$countLoops++;
				$authorId = $row['MESSANGER'];
				$message = $row['MESSAGE'];
				
				$message = convertMessage($message);
				
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
			}

			if ($countLoops != $loops) { $returnMessage = 1; $_SESSION['loops'] = $countLoops; }

			if ($returnMessage == 1) echo $build;
			else
			if ($returnMessage == 0) echo $countL;
		}
	}	
?>