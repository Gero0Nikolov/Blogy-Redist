<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$blogerId = $_POST['blogSender'];
	
	if (file_exists("../Authors/$sender/Ohana.txt")) {
		addToOhana($sender, $blogerId);
	} else {
		$build = fopen("../Authors/$sender/Ohana.txt", "w") or die("Fatal: Could not create Ohana.txt");
		fclose($build);
		
		addToOhana($sender, $blogerId);
	}
	
	function addToOhana($sender, $newMember) {
		$addMember = fopen("../Authors/$sender/Ohana.txt", "a") or die("Fatal: Ohana not found.");
		fwrite($addMember, $newMember.PHP_EOL);
		fclose($addMember);
		
		//Connect to data base
		include "Universal/dataBase.php";
	
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			$sql = "CREATE TABLE pushTable$newMember (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				buildNotification($sender, $newMember, $conn);
			} else {
				buildNotification($sender, $newMember, $conn);				
			}
		}
		$conn->close();
		
		header("Location: openBloger.php?$newMember");
	}
	
	function buildNotification($sender, $newMember, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$newMember (MEMBER, MESSAGE, DATE) VALUES ('$sender', 'just added you in Ohana', '$date')";
		$conn->query($sql);
	}
?>