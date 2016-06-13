<?php
	session_start();
	$sender = $_SESSION['sender'];
	
	$ohanaMembers = array();
	$build = "";
	
	//Connect to data base
	include "Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {}
		
	
	if (file_exists("../Authors/$sender/Ohana.txt")) {
		$pullOhana = fopen("../Authors/$sender/Ohana.txt", "r") or die("Fatal: Could not open Ohana");
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
					$pullInfo = fopen("../Authors/$member/config.txt", "r") or die("Fatal: Could not load.");
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
						<div id='sideBar-profile-container'>
							<div class='left'>
								<button class='placeholder' onclick=\"window.location=&#39;openBloger.php?$member&#39;\">
									<div style='background: url(\"$memberImg\"); background-size: cover; background-position: 50%;' class='img'></div>
									$memberFN $memberLN
								</button>
								<form id='$member' method='post' style='display: none;'>
									<input type='text' name='blogSender' value='$member'>
									<input type='text' name='blogerFN' value='$memberFN'>
									<input type='text' name='blogerLN' value='$memberLN'>
									<input type='text' name='blogerImg' value='$memberImg'>
									<input type='text' name='blogerHref' value='$memberHref'>
								</form>
							</div>					
					";
						if (!in_array($member, $blockedPersons)) {
							$build .= "
								<div class='right'>
									<button class='iconic' title='Send quick message to ".$memberFN." ".$memberLN."' onclick=\"showMessageBox('$member')\">
										&#xf1d8;
									</button
								</div>
							";
						}
					$build .= "
							</div>
						</div>
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