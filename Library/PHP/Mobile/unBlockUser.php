<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	
	$blockedId = $_POST['blogSender'];
	
	//Connect to data base
	include "../Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "DELETE FROM blockList$sender WHERE BLOCKEDID='$blockedId'";
		$conn->query($sql);
	}
	$conn->close();
	
	echo "<script>window.location='openBloger.php';</script>";
?>