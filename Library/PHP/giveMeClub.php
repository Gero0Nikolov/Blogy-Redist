<?php
	session_start();
	$sender = $_SESSION["sender"];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}

	$club_slug = $_POST["clubSlug"];
	$club_owner = $_POST["clubOwner"];

	//Connect to the dataBase
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$table_ = $club_owner ."_Clubs";
	$sql = "SELECT ID, Club_Name, Club_Logo FROM $table_ WHERE Club_Slug='$club_slug' AND Owner='$club_owner'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$club_id = $row["ID"];
			$club_name = $row["Club_Name"];
			$club_logo = $row["Club_Logo"];
		}
	}

	//Close the connection
	$conn->close();

	$getBuild = "
		<a id='$club_slug' href='previewClub.php?$table_=$club_id'>
			<div style='background: url(\"$club_logo\"); background-size: cover; background-position: 50%;' class='img'></div>
			$club_name
		</a>
	";	

	echo $getBuild;
?>