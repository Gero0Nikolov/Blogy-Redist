<?php 
	$getClubTable = $_POST["clubTable"];
	$getClubId = $_POST["clubId"];
	$getPostTitle = htmlentities($_POST["postTitle"]);
	$getPostId = $_POST["postId"];
	
	//Connect to data base
	include "dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$tableName = $getClubTable."_Story_".$getClubId;
		$sql = "DELETE FROM $tableName WHERE ID=".$getPostId;
		$conn->query($sql);

		//Delete comments
		$tableName = $getClubTable."_Story_Comments_".$getClubId;
		$sql = "DELETE FROM $tableName WHERE Post_Id=".$getPostId;
		$conn->query($sql);
	}

	//Close the connection
	$conn->close();

	//Return responce
	echo "READY";
?>