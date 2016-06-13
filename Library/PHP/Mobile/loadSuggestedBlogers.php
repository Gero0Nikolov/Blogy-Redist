<?php
	session_start();
	$sender = $_SESSION["sender"];

	$pullFollowing = array();
	$followingPull = fopen("../../Authors/$sender/Following.txt", "r") or die("Fatal: Could not start opening.");
	while (!feof($followingPull)) {
		$line = trim(fgets($followingPull));
		if ($line != "") {
			array_push($pullFollowing, $line);
		}
	}
	fclose($followingPull);
	
	//Connect to data base
	include "../Universal/dataBase.php";
	
	$blockedPersons = array();
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT BLOCKEDID FROM blockList$sender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				array_push($blockedPersons, $row['BLOCKEDID']);
			}
		}
	}
	
	if (!empty($pullFollowing)) {
		$returnStack = array();
		foreach ($pullFollowing as $authorId) {
			if (filesize("../../Authors/$authorId/Following.txt") > 0) {
				$loadFollowers = fopen("../../Authors/$authorId/Following.txt", "r") or die("Fatal: Could not load.");
				while (!feof($loadFollowers)) {
					$line = trim(fgets($loadFollowers));
					if ($line != "" && $line != $sender) {
						$blockedPersonsByFollower = array();
						$sql = "SELECT BLOCKEDID FROM blockList$line";
						$pick = $conn->query($sql);
						if ($pick->num_rows > 0) {
							while ($row = $pick->fetch_assoc()) {
								array_push($blockedPersonsByFollower, $row['BLOCKEDID']);
							}
						}
						
						if (!in_array($line, $pullFollowing) && !in_array($line, $blockedPersons) && !in_array($sender, $blockedPersonsByFollower)) {
							array_push($returnStack, $line);
						}
					}
				}
				fclose($loadFollowers);
			}
		}
		
		$returnStack = array_unique($returnStack);
		sort($returnStack);
		$build = "";
		foreach ($returnStack as $sugestion) {
			$lineCount = 0;
			if ($sugestion != NULL) {
				$pullInfo = fopen("../../Authors/$sugestion/config.txt", "r") or die("Fatal: Could not load.");
				while (!feof($pullInfo)) {
					$line = trim(fgets($pullInfo));
					if ($line != "") {
						if ($lineCount == 0) {
							$sugestionImg = $line;
						}
						else
						if ($lineCount == 1) {
							$sugestionHref = $line;
						}
						else
						if ($lineCount == 3) {
							$sugestionFN = $line;
						}
						else
						if ($lineCount == 4) {
							$sugestionLN = $line;
							break;
						}
					}
					$lineCount++;
				}
				fclose($pullInfo);
								
				//Build and print
				$build .= "
					<button onclick=\"window.location='openBloger.php?$sugestion'\">
						<img src='$sugestionImg' />
						$sugestionFN $sugestionLN
					</button>
					<br>
				";
			}
		}
		
		if (empty($returnStack)) {
			$build = "<h2>There is no new suggestions for you.</h2>";
		}
	} else {
		$build = "<h2>You don't follow anybody.</h2>";
	}
	
	$conn->close(); //Close SQL Connection

	//Return responce
	$responce = $build;
	echo $responce;
?>