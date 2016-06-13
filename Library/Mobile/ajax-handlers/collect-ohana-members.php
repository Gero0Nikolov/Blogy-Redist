<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset( $sender ) ) { die(); }

	$ohanaMembers = array();
	$build = "";

	$server_path = "/home/blogycoo/public_html/";
	
	//Connect to data base
	include $server_path."Library/PHP/Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {}
		
	
	if ( file_exists( $server_path."Library/Authors/$sender/Ohana.txt" ) ) {
		$pullOhana = fopen( $server_path."Library/Authors/$sender/Ohana.txt", "r" ) or die("Fatal: Could not open Ohana");
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
					$pullInfo = fopen( $server_path."Library/Authors/$member/config.txt", "r" ) or die("Fatal: Could not load.");
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
										
					//Build and print
					$build .= "
						<button id='$member' class='online-friend' onclick='openUserControlBox(\"$member%%$memberFN%%$memberLN%%$memberImg\", \"sidebar\");'>
							<div style='background: url(\"$memberImg\"); background-size: cover; background-position: 50%;' class='profile-picture'></div>
							$memberFN $memberLN
						</button>
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

	//Return response
	$response = $build;
	echo $response;
?>