<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}

	$getId = $_COOKIE['placeId'];

	//Connect to data base
	include "../Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "CREATE TABLE worldPlaces (ID int NOT NULL AUTO_INCREMENT, PLACEID LONGTEXT, PLACECORDS LONGTEXT, PLACESTORY LONGTEXT, TAGGED LONGTEXT, LIKERS LONGTEXT, APPROVED INT, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {/*Successfully created table*/}

		//Find place
		$sql = "SELECT ID, PLACEID, PLACECORDS, PLACESTORY, TAGGED FROM placesOf$sender ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				if ($getId == $row['ID']) {
					$placeTitle = $row['PLACEID'];
					$placeCords = $row['PLACECORDS'];
					$placeStory = $row['PLACESTORY'];
					$taggedFriends = $row['TAGGED'];
					break;
				}
			}
		}

		//echo "$placeTitle<br>$placeCords<br>$placeStory<br>$taggedFriends";

		//Build
		if ($taggedFriends == "NONE") {
			$taggedFriends = $sender;
			$setTags = $taggedFriends;
		} else {
			//$taggedFriends = explode(",", $taggedFriends);
			//array_push($taggedFriends, $sender);
			$setTags = $taggedFriends;
		}

		//Commit
		$sql = "INSERT INTO worldPlaces (PLACEID, PLACECORDS, PLACESTORY, TAGGED, LIKERS, APPROVED) VALUES ('$placeTitle', '$placeCords', '$placeStory', '$setTags', 'NONE', 0)";
		$conn->query($sql);
	}
	$conn->close();

	echo "<script>window.location='previewPlace.php';</script>";
?>