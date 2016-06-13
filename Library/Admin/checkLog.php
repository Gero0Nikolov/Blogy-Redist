<?php
	$mail = strtolower($_POST["email"]);
	$pass = $_POST["password"];

	//Connect to the Database
	include "../PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Set flag
	$flag = 0; //Bad EMAIL

	//Check if e-mail exists
	$sql = "SELECT Author_EMAIL, Author_UID FROM WorldBloggers WHERE Author_EMAIL='".$mail."'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$flag = 1; //Bad PASS
			$getUID = $row["Author_UID"];
		
			$fd = fopen("../Authors/".$getUID."/config.txt", "r") or die("Unable to open file.");
			$line_counter = 0;
			while ( !feof($fd) ) {
				$line = fgets($fd);
				if ($line_counter == 0) {
					$senderImg = trim($line);
				}
				else
				if ($line_counter == 1) {
					$senderHref = trim($line);
				}
				else
				if ($line_counter == 2) {
					$sender = trim($line);
				}
				else
				if ($line_counter == 3) {
					$senderFN = trim($line);
				}
				else
				if ($line_counter == 4) {
					$senderLN = trim($line);
				}
				else
				if ($line_counter == 5) {
					$passCode = trim($line);
					break;
				}

				$line_counter++;
			}
			fclose($fd);

			//Check if passwords matches
			if ($passCode == $pass) {
				$flag = 2; //Everything is OK, so proceed..
				
				$buildNotifications = array();
				
				//Creatе new TABLE
				$sql = "CREATE TABLE grantedPermissions (ID int NOT NULL AUTO_INCREMENT, ADMIN LONGTEXT, PRIMARY KEY (ID))";
				if ($conn->query($sql) === TRUE) {}	

				//Check if admin access is granted
				$sql = "SELECT ADMIN FROM grantedPermissions WHERE ADMIN='$sender'";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					while ($row = $pick->fetch_assoc()) {
						$found = 1;
					}
				}

				if (isset($found)) { logInAdmin($sender, $senderImg, $senderHref, $senderFN, $senderLN); }
				else { logInStandart($sender, $senderImg, $senderHref, $senderFN, $senderLN); }
			}
		}
	}

	//Close the connection
	$conn->close();	
	
	if ($flag == 0) {
		header("Location: index.php");
	}
	else 
	if ($flag == 1) {
		header("Location: index.php");
	}
	
	function logInStandart($sender, $senderImg, $senderHref, $senderFN, $senderLN) {
		session_start();
		$_SESSION["sender"] = $sender;
		$_SESSION["senderImg"] = $senderImg;
		$_SESSION["senderHref"] = $senderHref;
 		$_SESSION["senderFN"] = $senderFN;
		$_SESSION["senderLN"] = $senderLN;

		require_once '../PHP/Detect/Mobile_Detect.php';
		$detect = new Mobile_Detect;
		if($detect->isMobile() && !$detect->isTablet()) {
			header("Location: ../PHP/Mobile/logedIn.php");
		} else {
			header("Location: ../PHP/logedIn.php");
		}
	}

	function logInAdmin($sender, $senderImg, $senderHref, $senderFN, $senderLN) {
		session_start();
		$_SESSION["admin"] = $sender;
		$_SESSION["adminImg"] = $senderImg;
		$_SESSION["adminHref"] = $senderHref;
 		$_SESSION["adminFN"] = $senderFN;
		$_SESSION["adminLN"] = $senderLN;

		header("Location: logedIn.php");
/*
		echo "
			<script>
				document.cookie = 'sender='+'$sender';
				document.cookie = 'senderImg='+'$senderImg';
				document.cookie = 'senderHref='+'$senderHref';
				document.cookie = 'senderFN='+'$senderFN';
				document.cookie = 'senderLN='+'$senderLN';
				window.location = 'logedIn.php';
			</script>
		";
*/
	}

	die();
?>