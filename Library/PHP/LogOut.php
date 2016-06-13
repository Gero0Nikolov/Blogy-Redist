<?php
	//Connect to data base
	include "Universal/dataBase.php";
	
	session_start();
	$sender = $_SESSION['sender'];
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Remove from TABLE
		$sql = "DELETE FROM logedUsers WHERE USERID='$sender'";
		$conn->query($sql);
	}
	$conn->close();
	
	session_destroy();

	//Unset cookies
	if (isset($_SERVER['HTTP_COOKIE'])) {
	    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
	    foreach($cookies as $cookie) {
	        $parts = explode('=', $cookie);
	        $name = trim($parts[0]);
	        setcookie($name, '', time()-1000);
	        setcookie($name, '', time()-1000, '/');
	    }
	}

	header("Location: http://".$_SERVER["HTTP_HOST"]);
	
	die();
?>