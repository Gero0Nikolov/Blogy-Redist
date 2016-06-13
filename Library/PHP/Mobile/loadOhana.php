<?php
	session_start();
	$sender = $_SESSION['sender'];
	
	$ohanaMembers = array();
	
	//Connect to data base
	include "../Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {}
	
	//Check push notifications
	$pushNotifications = array();
	$sql = "SELECT MEMBER FROM pushTable$sender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push($pushNotifications, $row['MEMBER']);
			/*
			$sql = "DELETE FROM pushTable$sender WHERE MEMBER='".$row['MEMBER']."'";
			$conn->query($sql);
			*/
		}
	}			
	
	if (file_exists("../../Authors/$sender/Ohana.txt")) {
		$pullOhana = fopen("../../Authors/$sender/Ohana.txt", "r") or die("Fatal: Could not open Ohana");
		while (!feof($pullOhana)) {
			$line = trim(fgets($pullOhana));
			if ($line != "") {
				array_push($ohanaMembers, $line);
			}
		}
		fclose($pullOhana);
		
		$blockedPersons = array();
		$sql = "SELECT BLOCKEDID FROM blockList$sender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersons, $row['BLOCKEDID']);
			}
		}
		
		$build = "";
		if (!empty($ohanaMembers)) {
			foreach($ohanaMembers as $member) {
				$blockedPersonsByFollower = array();
				$sql = "SELECT BLOCKEDID FROM blockList$member";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					while ($row = $pick->fetch_assoc()) {
						array_push($blockedPersonsByFollower, $row['BLOCKEDID']);
					}
				}
				
				$lineCount = 0;
				if ($member != NULL && !in_array($sender, $blockedPersonsByFollower)) {
					$pullInfo = fopen("../../Authors/$member/config.txt", "r") or die("Fatal: Could not load.");
					while (!feof($pullInfo)) {
						$line = trim(fgets($pullInfo));
						if ($line != "") {
							if ($lineCount == 0) {
								$memberImg = $line;
							}
							else
							if ($lineCount == 1) {
								$memberHref = $line;
							}
							else
							if ($lineCount == 3) {
								$memberFN = $line;
							}
							else
							if ($lineCount == 4) {
								$memberLN = $line;
								break;
							}
						}
						$lineCount++;
					}
					fclose($pullInfo);
					
					/*
					$blockedPersonsByFollower = array();
					$sql = "SELECT BLOCKEDID FROM blockList$member";
					$pick = $conn->query($sql);
					if ($pick->num_rows > 0) {
						while ($row = $pick->fetch_assoc()) {
							array_push($blockedPersonsByFollower, $row['BLOCKEDID']);
						}
					}
					*/
					
					/*
					//Get today posts
					$latePosts = array();
					$pullPosts = scandir("../Authors/$member/Posts");
					foreach ($pullPosts as $post) {
						if (date("Y-m-d", filemtime("../Authors/$member/Posts/$post")) == date("Y-m-d")) {
							if ($file != "Stack.txt" && $file != "." && $file != "..") {
								$latePosts = array_merge($latePosts, array("$post" => date("Y-m-d H:i:s", filemtime("../Authors/$member/Posts/$post"))));
							}
						}
					}
					
					arsort($latePosts);
					*/
					
					//Build and print
					$build .= "
						<button onclick=\"showQuickMenuOhana('$member')\">
							<img src='$memberImg' />
							$memberFN $memberLN
						</button>
						<div id='ohanaQuickMenu$member' class='ohanaQuickMenu' style='display: none;'>
							<div id='quickMenu'>
								<button type='button' onclick=\"window.location=&#39;openBloger.php?$member&#39;\">
									View story
								</button>
								<form id='$member' method='post' style='display: none;'>
									<input type='text' name='blogSender' value='$member'>
									<input type='text' name='blogerFN' value='$memberFN'>
									<input type='text' name='blogerLN' value='$memberLN'>
									<input type='text' name='blogerImg' value='$memberImg'>
									<input type='text' name='blogerHref' value='$memberHref'>
								</form>
					";
						if (!in_array($member, $blockedPersons)) {
							$build .= "
								<button type='button' onclick=\"showMessageBoxM('$member')\">
									Quick message
								</button>
							";
						}
					$build .= "
							</div>
						</div>
						<br>
					";
				}
			}
		} else {
			$build = "<h2>Your Ohana is still empty :(</h2>";
		}
	} else {
		$build = "<h2>Your Ohana is still empty :(</h2>";
	}
	
	//Close the connection
	$conn->close();

	//Return responce
	$responce = $build;
	echo $responce;
?>