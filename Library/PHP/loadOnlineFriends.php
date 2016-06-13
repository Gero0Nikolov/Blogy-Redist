<?php	
	session_start();
	$sender = $_SESSION["sender"];

	//Connect to data base
	include "Universal/dataBase.php";
	
	$onlineStack = array();
	$onlineUsers = array();

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

	if ( file_exists("../Authors/$sender/Following.txt") ) {
		$followingPull = fopen("../Authors/$sender/Following.txt", "r") or die("Fatal: Could not start opening.");
		while (!feof($followingPull)) {
			$line = trim(fgets($followingPull));
			if ($line != "") {
				$sugestion = $line;

				//Check if sugestion is online	
				$sql = "SELECT USERID FROM logedUsers WHERE USERID='$sugestion'";
				$pick = $conn->query($sql);
				if ($pick->num_rows > 0) {
					$lineCount = 0;
					$pullInfo = fopen("../Authors/$sugestion/config.txt", "r") or die("Fatal: Could not load.");
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
					
					//<a href='openBloger.php' type='button' onclick=\"openBloger('$sugestion')\">
					//</a>
					$buildOnline = "
						<div id='sideBar-profile-container'>
								<div class='left'>
									<button class='placeholder' onclick=\"window.location=&#39;openBloger.php?$sugestion&#39;\">
										<div style='background: url(\"$sugestionImg\"); background-size: cover; background-position: 50%;' class='img'></div>
										$sugestionFN $sugestionLN
									</button>
									<form id='$sugestion' method='post' style='display: none;'>
										<input type='text' name='blogSender' value='$sugestion'>
										<input type='text' name='blogerFN' value='$sugestionFN'>
										<input type='text' name='blogerLN' value='$sugestionLN'>
										<input type='text' name='blogerImg' value='$sugestionImg'>
										<input type='text' name='blogerHref' value='$sugestionHref'>
									</form>
								</div>
								<div class='right'>
									<button class='iconic' title='Send quick message to ".$sugestionFN." ".$sugestionLN."' onclick=\"showMessageBox('$sugestion')\">
										&#xf1d8;
									</button
								</div>
						</div>
						</div>
					";
					array_push($onlineStack, $buildOnline);
				}
			}
		}
		fclose($followingPull);
	}

	if (empty($onlineStack)) {
		$responce = "<h2>Nobody is online now :-(</h2>";
	} else {
		$responce = implode("", $onlineStack);
	}

	echo $responce;
?>