<?php 
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	//Connect to data base
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {}

	//Collect push Notifications
	$pushNotifications = array();
	$sql = "SELECT ID, MEMBER, MESSAGE, DATE, CHECKED FROM pushTable$sender WHERE CHECKED IS NULL ORDER BY ID DESC";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$member = $row['MEMBER'];
			$message = $row['MESSAGE'];
			$date = $row['DATE'];
			array_push($pushNotifications, "$member-$message-$date");
		}

		$sql = "UPDATE pushTable$sender SET CHECKED='TRUE' WHERE CHECKED IS NULL";
		$conn->query($sql);
	} else {

		$sql = "SELECT ID, MEMBER, MESSAGE, DATE, CHECKED FROM pushTable$sender ORDER BY ID DESC LIMIT 25";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$member = $row['MEMBER'];
				$message = $row['MESSAGE'];
				$date = $row['DATE'];
				array_push($pushNotifications, "$member-$message-$date");
			}
		}

	}

	//Close the connection
	$conn->close();

	if (empty($pushNotifications)) {
		$responce = "
			<p id='no-notifications' class='center-text'>
				You don't have any notifications.
			</p>
		";
	} else {
		foreach ($pushNotifications as $notification) {
			$member = explode("-", $notification)[0];
			$message = explode("-", $notification)[1];
			$date = explode("-", $notification)[2];
			$lineCount = 0;
			if (!strpos($member, "is*")) {
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
				
				$date = str_replace(".", "-", $date);
				$notification_icon = "";
				$icon_class = "";
			
				//Parse and build special notifications
				if (strpos($message, "you a message")) {
					$message = "just send you a <a href='readMessage.php?".$member."'>message</a>";
					$notification_icon = "&#xf075;";
					$icon_class = "message";
				} 
				else 
				if (strpos($message, "#tagged you in a place")) {
					$getId = explode("#", $message)[0];
					$message = "tagged you in a <a href='previewPlace.php' onclick='previewPlace(\"$getId\")'>place</a>";
					$notification_icon = "&#xf276;";
					$icon_class = "place_tag";
				}
				else
				if (strpos($message, "#shared a place with you")) {
					$getId = explode("#", $message)[0];
					$message = "shared a <a href='previewPlace.php' onclick='previewPlace(\"$getId\")'>place</a> with <a href='logedIn.php'>you</a>";
					$notification_icon = "&#xf276;";
					$icon_class = "place_tag";
				}
				else
				if (strpos($message, "#liked your story")) {
					$getId = explode("#", $message)[0];
					$message = "just liked your <a href='previewPostPage.php' onclick='previewPost(\"$getId\")'>story</a>";
					$notification_icon = "&#xf004;";
					$icon_class = "liked_story";
				}
				else
				if (strpos($message, "#reposted your story")) {
					$getId = explode("#", $message)[0];
					$message = "just reposted your <a href='previewPostPage.php' onclick='previewPost(\"$getId\")'>story</a>";
					$notification_icon = "&#xf064;";
					$icon_class = "reposted_story";
				}
				else
				if (strpos($message, "#invitation for club")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "invited you to join a <a href='previewClubInvite.php?".$getClubTableId."'>club</a>";
					$notification_icon = "&#xf0a4;";
					$icon_class = "club_invite";
				}
				else
				if (strpos($message, "#declined club invitation")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "declined to join your <a href='previewClub.php?".$getClubTableId."'>club</a>";
					$notification_icon = "&#xf00d;";
					$icon_class = "decline";
				}
				else
				if (strpos($message, "#accepted club invitation")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "has joined your <a href='previewClub.php?".$getClubTableId."'>club</a>";
					$notification_icon = "&#xf00c;";
					$icon_class = "accept";
				}
				else
				if (strpos($message, "#club admin promotion")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "has promoted you as an admin of a <a href='previewClub.php?".$getClubTableId."'>club</a>";
					$notification_icon = "&#xf0a6;";
					$icon_class = "admin_promotion";
				}
				else
				if (strpos($message, "#promoted as admin")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "has promoted a member as an admin for your <a href='previewClub.php?".$getClubTableId."'>club</a>";
					$notification_icon = "&#xf0a6;";
					$icon_class = "admin_promotion";
				}
				else
				if (strpos($message, "#wants to join the club")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "wants to join your <a href='previewClub.php?".$getClubTableId."'>club</a>";	
					$notification_icon = "&#xf19d;";
					$icon_class = "club_join";
				}
				else
				if (strpos($message, "#approved you to join the club")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "approved you to join the <a href='previewClub.php?".$getClubTableId."'>club</a>";
					$notification_icon = "&#xf00c;";
					$icon_class = "accept";
				}
				else
				if (strpos($message, "#declined you to join the club")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "declined your request to join the <a href='previewClub.php?".$getClubTableId."'>club</a>";
					$notification_icon = "&#xf00d;";
					$icon_class = "decline";
				}
				else
				if (strpos($message, '#delete club request')) {
					$getClubTableId = explode("#", $message)[0];
					$message = "wants to delete your <a href='previewClub.php?".$getClubTableId."'>club</a>";
					$notification_icon = "&#xf068;";
					$icon_class = "decline";
				}
				else 
				if ($message == "started following you") {
					$notification_icon = "&#xf1ae;";
					$icon_class = "reposted_story";
				}
				else
				if ($message == "just shared a story") {
					$notification_icon = "&#xf0f4;";
					$icon_class = "shared_story";
				}
				else
				if (strpos($message, "installed your")) {
					$notification_icon = "&#xf1e6;";
					$icon_class = "installed_plugin";
				}
				else
				if (strpos($message, "iked your")) {
					$notification_icon = "&#xf087;";
					$icon_class = "liked_plugin";
				}

				//Check message for shortcodes
				if ( strpos($message, "link=!") ) {
					$isLinkFound = 1;
					while ( $isLinkFound == 1 ) :
						if ( strpos($message, "link=!") ) {
							$getLink = explode("!]", explode("[/link]", explode("link=!", $message)[1])[0])[0];

							//Replace the LINK shortcode with the proper content
							$message = str_replace("[link=!".$getLink."!]", "<a href=".$getLink.">", $message);
							$message = str_replace("[/link]", "</a>", $message);
						
							$isLinkFound = 1;
						} else {
							$isLinkFound = 0;
						}
					endwhile;
				}

				$build = "
					<div id='notification'>					
						<a href='openBloger.php?".$member."'>
							<img src='$memberImg' />
							$memberFN $memberLN
						</a>
						".$message.".
						<div class='notification-meta'>
							<span class='notify-icon ".$icon_class."'>".$notification_icon."</span>
							<span class='timeStamp'>$date</span>
						</div>
					</div>
				";
			} else {
				$memberImg = "https://cdn4.iconfinder.com/data/icons/Mobile-Icons/128/07_note.png";
				$message = "<a href='loadNotes.php'>".$message."</a>";
			
				$build = "
					<div id='notification'>					
						<div id='notificationDateContainer'>
							$date
						</div>
						<p>
							<img src='$memberImg' />
							It is
							$message.
						</p>
						<form id='$member' method='post' style='display: none;'>
							<input type='text' name='blogSender' value='$member'></input>
							<input type='text' name='blogerFN' value='$memberFN'></input>
							<input type='text' name='blogerLN' value='$memberLN'></input>
							<input type='text' name='blogerImg' value='$memberImg'></input>
							<input type='text' name='blogerHref' value='$memberHref'></input>
						</form>
					</div>
				";
			}

			$responce .= $build;
		}
	}

	//Return responce
	echo $responce;
?>