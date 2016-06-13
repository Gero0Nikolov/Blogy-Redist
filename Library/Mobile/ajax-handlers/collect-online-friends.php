<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }

	$server_path = "/home/blogycoo/public_html/";

	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";
	
	$onlineStack = array();
	$onlineUsers = array();

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

	if ( file_exists( $server_path."Library/Authors/$sender/Following.txt" ) ) {
		$followingPull = fopen( $server_path."Library/Authors/$sender/Following.txt", "r" ) or die("Fatal: Could not start opening.");
		while (!feof($followingPull)) {
			$line = trim(fgets($followingPull));
			if ($line != "") {
				$sugestion = $line;

				//Check if sugestion is online	
				$sql = "SELECT USERID FROM logedUsers WHERE USERID='$sugestion'";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					$lineCount = 0;
					$pullInfo = fopen( $server_path."Library/Authors/$sugestion/config.txt", "r" ) or die("Fatal: Could not load.");
					while (!feof($pullInfo)) {
						$pickupLine = trim(fgets($pullInfo));
						if ($line != "") {
							if ($lineCount == 0) {
								$sugestionImg = $pickupLine;
							}
							else
							if ($lineCount == 1) {
								$sugestionHref = $pickupLine;
							}
							else
							if ($lineCount == 3) {
								$sugestionFN = $pickupLine;
							}
							else
							if ($lineCount == 4) {
								$sugestionLN = $pickupLine;
								break;
							}
						}
						$lineCount++;
					}
					fclose($pullInfo);
					
					$build_ = "
						<button id='$sugestion' class='online-friend' onclick='openUserControlBox(\"$sugestion%%$sugestionFN%%$sugestionLN%%$sugestionImg\", \"sidebar\");'>
							<div style='background: url(\"$sugestionImg\"); background-size: cover; background-position: 50%;' class='profile-picture'></div>
							$sugestionFN $sugestionLN
						</button>
					";
					array_push($onlineStack, $build_);
				}
			}
		}
		fclose($followingPull);
	}

	if (empty($onlineStack)) {
		$response = "<h2 class='sidebar-message'>Nobody is online now :-(</h2>";
	} else {
		$response = implode("", $onlineStack);
	}

	echo $response;
?>