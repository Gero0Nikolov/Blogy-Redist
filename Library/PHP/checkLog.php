<?php
	$mail = $_POST['mail'];
	$pass = $_POST['password'];

	$email_uid = $_POST['email_uid'];
	$auth_code = htmlentities( $_POST['save_code'] );
	$auth_code = str_replace( "'" , "&#39;", $auth_code);

	//Set flag
	$flag = 0; //Bad EMAIL
	
	//Include functions bundle
	include "Universal/functions.php";
	require 'helpFunctions.php';

	//Decrypt passoword if !empty();
	if ( !empty($pass) ) { $pass = bruteDecrypt($pass); }

	//Connect to the Database
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	if ( empty($mail) && empty($pass) ) {
		if ( isset($auth_code) && !empty($auth_code) ) {
			$sql = "SELECT Author_UID, Author_EMAIL FROM WorldBloggers WHERE Save_Code='".$auth_code."' AND Author_EMAIL='".$email_uid."'";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					$getUID = $row["Author_UID"];
					$mail = $row["Author_EMAIL"];
				}

				//Catch password
				$fd = fopen("../Authors/".$getUID."/config.txt", "r") or die("Unable to open file.");
				$line_counter = 0;
				while ( !feof($fd) ) {
					$line = trim(fgets($fd));
					if ( $line_counter == 5 ) {
						$pass = $line;
						break;
					}
					$line_counter++;
				}
				fclose($fd);

				$auth_log = 1;
			} else {
				$flag = 4;
				$auth_log = -1;
			}
		}
	}

	//Check if e-mail exists
	$sql = "SELECT Author_EMAIL, Author_UID, BAN, Save_Code, Birthdate, Hobbies, Acti_Key FROM WorldBloggers WHERE Author_EMAIL='".$mail."'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			if ( $row['BAN'] == 0 ) {
				if ( $row["Acti_Key"] == "0" || empty( $row["Acti_Key"] ) ) {
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

						if ( empty($row["Save_Code"]) || !isset($row["Save_Code"]) ) { $auth_log = 2; }
						
						/*
							//Get notes
							$sql = "SELECT NOTEID, NOTETEXT, NOTEDATE FROM notesOf$sender ORDER BY ID DESC";
							$pick = $conn->query($sql);
							if ($pick->num_rows > 0) {
								while ($row = $pick->fetch_assoc()) {
									$getDate = $row['NOTEDATE'];
									if ($getDate == date("m/d/Y")) {
										$getTitle = $row['NOTEID'];
										array_push($buildNotifications, $getTitle);
									}
								}
							}
						*/
						
						$sql = "CREATE TABLE pushTable$sender (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
						$conn->query($sql);
							
						//Creatе new TABLE
						$sql = "CREATE TABLE logedUsers (ID int NOT NULL AUTO_INCREMENT, USERID LONGTEXT, PRIMARY KEY (ID))";
						if ($conn->query($sql) === TRUE) {}					
						
						$sql = "DELETE FROM logedUsers WHERE USERID='$sender'";
						$conn->query($sql);
						
						$sql = "CREATE TABLE stack$sender (ID int NOT NULL AUTO_INCREMENT, DATETIME LONGTEXT, STORYTITLE LONGTEXT, STORYLINK LONGTEXT, STORYCONTENT LONGTEXT, LIKES LONGTEXT, PRIMARY KEY (ID))";
						if ($conn->query($sql) === TRUE) {}

						$sql = "CREATE TABLE worldStories (ID int NOT NULL AUTO_INCREMENT, AuthorTitle LONGTEXT, LINK LONGTEXT, POST LONGTEXT, PRIMARY KEY (ID))";
						if ($conn->query($sql) === TRUE) {}

						logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN, $auth_log);
					}
				} else {
					$flag = 5; // User e-mail is not activated
				}
			} else {
				$flag = 3; // User is banned
			}
		}
	}

	//Close the connection
	$conn->close();	
	
	if ($flag == 0) {
		echo "BMAIL";
	}
	else 
	if ($flag == 1) {
		echo "BPASS";
	}
	else
	if ($flag == 3) {
		echo "BANNED";
	}
	else
	if ($flag == 4) {
		header("Location: ../Errors/E13.html");
		die();
	}
	else
	if ($flag == 5) {
		echo "NOT_ACTIVATED";
	}
	
	function logIn($sender, $senderImg, $senderHref, $senderFN, $senderLN, $auth_log) {
		session_start();
		$_SESSION["sender"] = $sender;
		$_SESSION["senderImg"] = $senderImg;
		$_SESSION["senderHref"] = $senderHref;
 		$_SESSION["senderFN"] = $senderFN;
		$_SESSION["senderLN"] = $senderLN;

		require_once 'Detect/Mobile_Detect.php';
		$detect = new Mobile_Detect;
		if($detect->isMobile() || $detect->isTablet()) {
			if ( $auth_log == 1 ) { header("Location: Errors/stuck.php"); }
			elseif ( $auth_log == 2) { echo "SACMOBILE"; }
			else { echo "LMOBILE"; }
		} else { //Web
			if ( $auth_log == 1 ) { header("Location: logedIn.php"); }
			elseif ( $auth_log == 2) { echo "SACDESKTOP"; }
			else { echo "LDESKTOP"; }
		}

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