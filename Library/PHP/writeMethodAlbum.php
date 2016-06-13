<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header("Location: ../../SignIn.html");
	}
	
	//Connect to data base
	include "Universal/dataBase.php";
	
	$cmd = $_POST['cmd'];
	
	$realoc = (string)NULL;
	
	$senderFirstName = $_POST['fname'];

	$titlePost = trim($_POST['title']);
	if (strpos($titlePost, " ")) {
		$titlePost = str_replace(" ", "6996", $titlePost);
	}
	$titlePost = htmlentities(strip_tags($titlePost));
	if (is_numeric($titlePost)) {
		$titlePost = "%id%$titlePost";
	}

	$contentPost = trim($_POST['content']);
	$contentPost = htmlentities($contentPost);
	$contentPost = str_replace("'", "`", $contentPost);

	$postPic = strip_tags($_POST['photo']);
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$titlePost = trim($_POST['title']);
		$titlePost = str_replace(";", "", $titlePost);
		$titlePost = str_replace("\"", "``", $titlePost);
		$titlePost = htmlentities($titlePost);
		$titlePost = str_replace("'", "`", $titlePost);
		if (is_numeric($titlePost)) {
			$titlePost = "-id-$titlePost";
		}

		$dateTime = date("d.m.Y-H:i:s");

		$sql = "INSERT INTO stack$sender (DATETIME, STORYTITLE, STORYLINK, STORYCONTENT) VALUES ('$dateTime', '$titlePost', '$postPic', '$contentPost')";
		$conn->query($sql);

		//Get the ID from author stories
		$store_post_id = $conn->insert_id;

		$sql = "INSERT INTO worldStories (AuthorTitle, LINK, POST) VALUES ('$sender$$store_post_id$$titlePost', '$postPic', '$contentPost')";
		$conn->query($sql);
	}
	
	sendMail($sender, $senderFirstName, $cmd, $conn);
	
	function sendMail($sender, $senderFirstName, $cmd, $conn) {		
		$followerFullName = (string)NULL;
		$followersIDs = fopen("../Authors/$sender/FollowersID.html", "r") or die("Unable to open file.");
		while (! feof($followersIDs)) {
			$toSend = (string)NULL;
			$parseFollowerConfig = (string)NULL;
			$followerID = trim(fgets($followersIDs));
			$line_count = 0;
			
			$mail = explode("-", $followerID)[0];
			$followerFullName = explode("-", $followerID)[1];
			
			$sql = "CREATE TABLE pushTable$followerFullName (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, PRIMARY KEY (ID))";
			if ($conn->query($sql) === TRUE) {
				buildNotification($sender, $followerFullName, $conn);
			} else {
				buildNotification($sender, $followerFullName, $conn);				
			}
			
			if ($followerFullName != "") {
				$count = 0;
				$parseFollowerConfig = fopen("../Authors/$followerFullName/config.txt", "r") or die("Unable to start parsing.");
				while (! feof($parseFollowerConfig)) {
					$pickLine = fgets($parseFollowerConfig);
					if ($count == 7) {
						$toSend = trim($pickLine);
						break;
					}
					$count++;
				}
				fclose($parseFollowerConfig);
				
				if ($toSend == "1") {
					$mail = trim($mail);
					if ($mail != "") {
						if ($cmd == "1") {
							$subject = "New blog in Blogy";
							$content = "Hello there. $senderFirstName just posted something into Blogy. Come and check it!";
						}
						else
						if ($cmd == "2") {
							$subject = "Your story was shared";
							$content = "Hello $senderFirstName, someone just shared your story.";
						}
						
						include "Universal/functions.php";
						send_custom_mail($sender, $followerFullName,"", $subject, $content);
					}
				}
			}
		}
	}
	
	function buildNotification($sender, $followerID, $conn) {
		$date = date("d.M.Y");
		$sql = "INSERT INTO pushTable$followerID (MEMBER, MESSAGE, DATE) VALUES ('$sender', 'just shared a story', '$date')";
		$conn->query($sql);
	}
	
	header("Location: loadAlbum.php");
	
	die();
?>