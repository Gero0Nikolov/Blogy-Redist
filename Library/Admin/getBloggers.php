<?php
	$getId = $_POST["authorId"];

	//Connect to the Database
	include "../PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if ($getId == -1) {
		$sql = "SELECT ID FROM WorldBloggers ORDER BY ID DESC LIMIT 1";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$getId = $row['ID'];
			}
		}
	}

	//Get Author UID
	$isFound = 0;
	while ( $isFound == 0 ) {
		$sql = "SELECT Author_UID, BAN FROM WorldBloggers WHERE ID='$getId'";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$isFound = 1;
				$isBanned = $row['BAN'];
				$authorId = $row['Author_UID'];
			}
		} else {
			$getId--;
		}
	}

	//Check if author is online
	$sql = "SELECT USERID FROM logedUsers WHERE USERID='$authorId'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) { $logedIn = 1; } 
	else { $logedIn = 0; }

	//Close the connection
	$conn->close(); 

	//Increment down for the next author ID
	$getId -= 1;

	//Return responce
	$responce = $logedIn."~".$isBanned."~".$authorId."~".$getId;
	echo $responce;
?>