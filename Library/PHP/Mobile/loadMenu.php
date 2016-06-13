<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		echo "<script>window.location='../../../SignIn.html';</script>";
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];

	//Connect to data base
	include "../Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$countNotifications = 0;
	$sql = "SELECT Message_Status FROM Messages_$sender WHERE Message_Status IS NULL || Message_Status=0";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$countNotifications += 1;
		}
	}

//Get notifications	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	//Collect push Notifications
	$collectNotifications = array();
	$sql = "SELECT ID, MEMBER, MESSAGE, DATE, CHECKED FROM pushTable$sender WHERE CHECKED IS NULL ORDER BY ID DESC";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$member = $row['MEMBER'];
			$message = $row['MESSAGE'];
			$date = $row['DATE'];
			array_push($collectNotifications, "$member-$message-$date");
		}
	}
	
	//Close the connection
	$conn->close();

	if ( !empty($collectNotifications) ) {
		$cmd = "class='sidebar-activator-active' onclick='createNotificationsIndex()'";
	} else {
		$cmd = "class='sidebar-activator' onclick='showHideSideBar()'";
	}

	if ((int)$countNotifications > 0) {
		$includeClass = "class='menu-activator-active'";
		$includeSpan = "<span>$countNotifications</span>";
	} else {
		$includeClass = "class='menu-activator'";
		$includeSpan = "";
	}
	
echo "
			<div id='menu'>
				<div id='homeMenu'>
					<button type='button' $includeClass onclick='showHideSideMenu()'>&#xf0c9;</button>
					<button type='button' onclick='showHide()'><div style='background-image:url(\"$profilePic\")'></div>$profileFirst $profileLast</button>
					<button type='button' id='sidebar-activator' $cmd>&#xf111;</button>
				</div>
				<div id='dropDownMenu' onclick='showHide()'>
					<a href='logedIn.php'><content class='iconic'>&#xf0f4;</content>Home</a>
					<a href='loadAlbum.php'><content class='iconic'>&#xf083;</content>Album</a>
					<a href='myPlaces.php'><content class='iconic'>&#xf279;</content>Places</a>
					<a href='exploreMyClubs.php'><content class='iconic'>&#xf005;</content>Clubs</a>
				</div>
				<div id='leftDropMenu' onclick='showHideSideMenu()'>
					<a href='storeMessages.php'><content class='iconic'>&#xf075;</content>Messages$includeSpan</a>
					<a href='openSettings.php'><content class='iconic'>&#xf0ad;</content>Settings</a>
					<a href='loadFriends.php'><content class='iconic'>&#xf0c0;</content>Bloggers</a>
					<a href='exploreStories.php'><content class='iconic'>&#xf1ea;</content>Stories</a>
					<a href='#!' id='search-button' onclick='loadSearchEngine(1)'><content class='iconic'>&#xf002;</content>Search</a>
					<a href='#' onclick='logMeOut()'><content class='iconic'>&#xf08b;</content>Log out</a>
				</div>
";
	/*if ($countNotifications != "0") {
		echo "<a href='storeMessages.php' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='storeMessages.php'>Messages</a>";
	}	*/
echo "
				<!--<a href='openSettings.php'>Settings</a>
				<a href='loadFriends.php'>Bloggers</a>
				<a href='exploreFStories.php'>Stories</a>
				<a href='#' onclick='logMeOut()'>Log out</a>
				<button type='button' class='searchButton' title='Search' onclick='window.location=\"searchInFriends.php\"; '><img src='../images/search.png' /></button>-->
			</div>

			<div id='quickMessageBox' style='display: none;'>
				<div id='title' onclick='$(\"#quickMessageBox\").slideToggle(\"fast\")'>
					<h1 id='receiver'></h1>
				</div>
				<form id='sendArea'>
					<textarea id='messageArea' name='messageArea' placeholder=\"What's up ?\" onkeydown='checkKey(event)'></textarea>
					<button type='button' class='sendButton' onclick='sendMessageBoxM()'>Send</button>
					<div style='display: none;'>
						<input type='text' id='receiverId' name='receiverId'>
					</div>
				</form>
			</div>

			<div id='sideBar'>
				<div id='sub-menu'>
					<button type='button' onclick='$(\"#online-list\").toggle(\"slide\"); loadOnlineFriends(1);'>Explore friends</button>
					<button type='button' onclick='$(\"#notifications-list\").toggle(\"slide\")'>Notifications</button>
					<button type='button' onclick='$(\"#ohana-list\").toggle(\"slide\"); loadOhana(1);'>Ohana</button>
				</div>
";

//Load online friends
echo "
		<div id='online-list'>
			<button type='button' class='back-button' onclick='$(\"#online-list\").toggle(\"slide\")'><span>&#xf104;</span>Back</button>
			<button type='button' class='right-button' onclick='$(\"#suggestions-list\").toggle(\"slide\"); loadSuggestions(1);'>Suggestions<span>&#xf105;</span></button>
			<h1>Friends online</h1>
			<div id='onlineFriends'>
";
	//include 'loadOnlineFriends.php';
echo "
			</div>
		</div>
";

//Load sugestions
echo "
	<div id='suggestions-list'>
		<button type='button' class='back-button' onclick='$(\"#suggestions-list\").toggle(\"slide\")'><span>&#xf104;</span>Back</button>
		<h1>People you may know</h1>
		<div id='suggestions'>
";
	//include "loadSuggestedBlogers.php";
echo "
		</div>
	</div>
";

//Load ohana members
echo "
	<div id='ohana-list'>
		<button type='button' class='back-button' onclick='$(\"#ohana-list\").toggle(\"slide\")'><span>&#xf104;</span>Back</button>
		<h1>Ohana</h1>
		<div id='ohana-members'>
";
	//include "loadOhana.php";
echo "
		</div>
	</div>
";

//Create notifications
	echo "
		<div id='notifications-list'>
			<button type='button' class='back-button' onclick='$(\"#notifications-list\").toggle(\"slide\")'><span>&#xf104;</span>Back</button>
			<h1>Notifications</h1>
			<div id='notifications-stack'>
	";
	
	if ( empty($collectNotifications) ) {
		echo "
			<p class='font-size-32 center-text'>
				No new notifications.
				<button type='button' onclick='loadOlderNotifications(1);' class='just-button width-100'>Load older</button>
			</p>
		";
	} else {	
		foreach ($collectNotifications as $notification) {
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
			
				//Parse and build special notifications
				if (strpos($message, "you a message")) {
					$message = "just send you a <a href='readMessage.php?".$member."'>message</a>";
				} 
				else 
				if (strpos($message, "#tagged you in a place")) {
					$getId = explode("#", $message)[0];
					$message = "tagged you in a <a href='previewPlace.php' onclick='previewPlace(\"$getId\")'>place</a>";
				}
				else
				if (strpos($message, "#shared a place with you")) {
					$getId = explode("#", $message)[0];
					$message = "shared a <a href='previewPlace.php' onclick='previewPlace(\"$getId\")'>place</a> with <a href='logedIn.php'>you</a>";
				}
				else
				if (strpos($message, "#liked your story")) {
					$getId = explode("#", $message)[0];
					$message = "just liked your <a href='previewPostPage.php' onclick='previewPost(\"$getId\")'>story</a>";
				}
				else
				if (strpos($message, "#reposted your story")) {
					$getId = explode("#", $message)[0];
					$message = "just reposted your <a href='previewPostPage.php' onclick='previewPost(\"$getId\")'>story</a>";
				}
				else
				if (strpos($message, "#invitation for club")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "invited you to join a <a href='previewClubInvite.php?".$getClubTableId."'>club</a>";
				}
				else
				if (strpos($message, "#declined club invitation")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "declined to join your <a href='previewClub.php?".$getClubTableId."'>club</a>";
				}
				else
				if (strpos($message, "#accepted club invitation")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "has joined your <a href='previewClub.php?".$getClubTableId."'>club</a>";
				}
				else
				if (strpos($message, "#club admin promotion")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "has promoted you as an admin of a <a href='previewClub.php?".$getClubTableId."'>club</a>";
				}
				else
				if (strpos($message, "#promoted as admin")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "has promoted a member as an admin for your <a href='previewClub.php?".$getClubTableId."'>club</a>";
				}
				else
				if (strpos($message, "#wants to join the club")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "wants to join your <a href='previewClub.php?".$getClubTableId."'>club</a>";	
				}
				else
				if (strpos($message, "#approved you to join the club")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "approved you to join the <a href='previewClub.php?".$getClubTableId."'>club</a>";	
				}
				else
				if (strpos($message, "#declined you to join the club")) {
					$getClubTableId = explode("#", $message)[0];
					$message = "declined your request to join the <a href='previewClub.php?".$getClubTableId."'>club</a>";	
				}
				else
				if (strpos($message, '#delete club request')) {
					$getClubTableId = explode("#", $message)[0];
					$message = "wants to delete your <a href='previewClub.php?".$getClubTableId."'>club</a>";
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
						<p>
							<a href='openBloger.php?".$member."'>
								<img src='$memberImg' />
								$memberFN $memberLN
							</a>
							$message.
						</p>
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
						
			echo "$build";
		}
	}

	echo"
			</div>
		</div>
	</div> <!--For the SideBar-->
	";
// ==== NEW CODING STANDARTS ====
	if ( isset($_COOKIE["set_save_code"]) ) {
		$get_value = $_COOKIE["set_save_code"];
		if ( $get_value == 1 ) {
?>
		
		<div id="full-page-container">
			<div id="inline-fields">
				<h1>You haven't registered your authentication code yet!</h1>
				<input type="password" id="auth_code_container" class="wide-fat" placeholder="Authentication code">
				<button onclick="submitSaveCode(1);" id="submit-auth-code">Submit</button>
			</div>
		</div>

<?php
		}
	}	
?>