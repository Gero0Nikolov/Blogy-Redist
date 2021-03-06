<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$blockedID = $_POST['blogSender'];
	
	//Connect to data base
	include "Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "CREATE TABLE blockList$sender (BLOCKEDID LONGTEXT)";
		if ($conn->query($sql) === TRUE) {	
			buildBlockDatabase($sender, $blockedID, $conn);
		} else {
			buildBlockDatabase($sender, $blockedID, $conn);
		}
	}
	$conn->close();
	
	function buildBlockDatabase($sender, $blockedID, $conn) {
		$sql = "INSERT INTO blockList$sender (BLOCKEDID) VALUES ('$blockedID')";
		$conn->query($sql);
	}
	
	//Remove from BlockerID followers if following
	$followers = array();
	$followerFound = 0;
	
	if ( file_exists( "../Authors/$sender/FollowersID.html" ) ) {
		$pullFollowers = fopen("../Authors/$sender/FollowersID.html", "r") or die("Fatal: Could not get followers.");
		while (!feof($pullFollowers)) {
			$line = trim(fgets($pullFollowers));
			if ($line != "") {
				if (explode("-", $line)[1] != $blockedID) {
					array_push($followers, $line);
				}
				else
				if (explode("-", $line)[1] == $blockedID) {
					$followerFound = 1;
				}
			}
		}
		fclose($pullFollowers);
	}
	
	$commitFollowers = fopen("../Authors/$sender/FollowersID.html", "w") or die("Fatal: Could not open file.");
	foreach ($followers as $follower) {
		fwrite($commitFollowers, $follower.PHP_EOL);
	}
	fclose($commitFollowers);
	
	if ($followerFound == 1) {
		$followersCountLoad = fopen("../Authors/$sender/Followers.html", "r") or die("Unable to load followers.");
		$followersCount = fread($followersCountLoad, filesize("../Authors/$sender/Followers.html"));
		fclose($followersCountLoad);
		
		$followersCount = (int)$followerFound - 1;
		
		$commitFollowers = fopen("../Authors/$sender/Followers.html", "w") or die("Fatal: Unable to load followers count.");
		fwrite($commitFollowers, $followersCount);
		fclose($commitFollowers);
	}
	//Remove from BlockerID following if following
	$following = array();
	if ( file_exists( "../Authors/$sender/Following.txt" ) ) {
		$pullFollowing = fopen("../Authors/$sender/Following.txt", "r") or die("Fatal: Could not get following.");
		while (!feof($pullFollowing)) {
			$line = trim(fgets($pullFollowing));
			if ($line != "") {
				if ($line != $blockedID) {
					array_push($following, $line);
				}
			}
		}
		fclose($pullFollowing);
	}
	
	$commitFollowing = fopen("../Authors/$sender/Following.txt", "w") or die("Fatal: Could not get following.");
	foreach ($following as $friend) {
		fwrite($commitFollowing, $friend.PHP_EOL);
	}
	fclose($commitFollowing);
	
	//Remove from BlockedID followers if following
	$followers = array();
	$followerFound = 0;
	if ( file_exists( "../Authors/$blockedID/FollowersID.html" ) ) {
		$pullFollowers = fopen("../Authors/$blockedID/FollowersID.html", "r") or die("Fatal: Could not get followers.");
		while (!feof($pullFollowers)) {
			$line = trim(fgets($pullFollowers));
			if ($line != "") {
				if (explode("-", $line)[1] != $sender) {
					array_push($followers, $line);
				}
				else
				if (explode("-", $line)[1] == $sender) {
					$followerFound = 1;
				}
			}
		}
		fclose($pullFollowers);
	}
	
	$commitFollowers = fopen("../Authors/$blockedID/FollowersID.html", "w") or die("Fatal: Could not open file.");
	foreach ($followers as $follower) {
		fwrite($commitFollowers, $follower.PHP_EOL);
	}
	fclose($commitFollowers);
	
	//Remove from BlockedID following if following
	$following = array();
	if ( file_exists( "../Authors/$blockedID/Following.txt" ) ) {
		$pullFollowing = fopen("../Authors/$blockedID/Following.txt", "r") or die("Fatal: Could not get following.");
		while (!feof($pullFollowing)) {
			$line = trim(fgets($pullFollowing));
			if ($line != "") {
				if ($line != $sender) {
					array_push($following, $line);
				}
			}
		}
		fclose($pullFollowing);
	}
	
	$commitFollowing = fopen("../Authors/$blockedID/Following.txt", "w") or die("Fatal: Could not get following.");
	foreach ($following as $friend) {
		fwrite($commitFollowing, $friend.PHP_EOL);
	}
	fclose($commitFollowing);
	
	$newOhana = array();
	if ( file_exists( "../Authors/$blockedID/Ohana.txt" ) ) {
		$getOhanaOfBlockedID = fopen("../Authors/$blockedID/Ohana.txt", "r") or die("Fatal: Could not get Ohana.");
		while (!feof($getOhanaOfBlockedID)) {
			$line = trim(fgets($getOhanaOfBlockedID));
			if ($line != "") {
				if ($line != $sender) {
					array_push($newOhana, $line);
				}
			}
		}
		fclose($getOhanaOfBlockedID);
	}
	
	$commitOhana = fopen("../Authors/$blockedID/Ohana.txt", "w") or die("Fatal: Could not build Ohana.");
	foreach ($newOhana as $member) {
		fwrite($commitOhana, $member.PHP_EOL);
	}
	fclose($commitOhana);
	
	echo "<script>window.location='logedIn.php';</script>";
?>