<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$objectId = $_POST['pictureId'];
	$src = "../Authors/$sender/Album/$objectId";
	
	//Connect to data base
	include "Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Delete the object
	$sql = "DELETE FROM albumOf$sender WHERE ALBUM='$objectId'";
	$conn->query($sql);
	
	//Update the space on the harddrive of the user
	$sql = "SELECT SPACE FROM albumOf$sender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getFreeSpace = (int)$row['SPACE'];
			break;
		}
	}
	
	$getFileSize = filesize($src);
	
	$calcSpace = $getFreeSpace + $getFileSize;
	$sql = "UPDATE albumOf$sender SET SPACE='$calcSpace' WHERE ALBUM='SPACE'";
	$conn->query($sql);
	
	//Close the connection
	$conn->close();
	
	//Delete the object
	unlink($src);

	//Finish and return the recalculated space
	$calcSpace = $calcSpace * 0.000001;
	$calcSpace = number_format ($calcSpace, 2);
	echo $calcSpace;
?>