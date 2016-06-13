<?php
	include "../Universal/dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT ID, AuthorTitle FROM worldStories ORDER BY ID";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getAT = $row["AuthorTitle"];

			$getAuthor = explode("$", $getAT);
			$sql_ = "SELECT ID FROM stack$getAuthor ORDER BY ID";
			$pick_ = $conn->query($sql_);
			if ($pick_->num_rows > 0) {
				$getID = $row["ID"];
			}

			$new_postId = $getAuthor."$".$getID;
			$sql = "UPDATE worldStories SET AuthorTitle='$new_postId' WHERE AuthorTitle='$getAT'";
			$conn->query($sql);
		}
	}

	//Close the connection
	$conn->close();
?>