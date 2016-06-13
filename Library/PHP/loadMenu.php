<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		echo "<script>window.location='/home/blogycoo/public_html/SignIn.html';</script>";
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];

	//Connect to data base
	include "Universal/dataBase.php";

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
	
echo "
			<div id='menu' ondblclick='slideToTop();'>
				<button type='button' class='hvr-push' onclick='showSideBar()' title='Show / Hide sidebar'></button>
				<div id='homeMenu'>
					<button type='button' class='homeButton' style='background-image:url(\"$profilePic\")'  onclick='window.location=\"logedIn.php\";'></button>
						<div id='dropDownMenu' class='dropDown'>
							<a href='logedIn.php' class='split'>Home</a>
							<a href='loadAlbum.php' class='split'>Album</a>
							<a href='myPlaces.php'>Places</a>
						</div>
				</div>
";

	if ($countNotifications != "0") {
		echo "<a href='storeMessages.php' class='notification'>$countNotifications new</a>";
	}
	else
	if ($countNotifications == "0") {
		echo "<a href='storeMessages.php'>Messages</a>";
	}	

//Collect push Notifications
$countNotifications = 0;
$pushNotifications = array();
$sql = "SELECT ID, MEMBER, MESSAGE, DATE, CHECKED FROM pushTable$sender WHERE CHECKED IS NULL ORDER BY ID DESC";
$pick = $conn->query($sql);
if ($pick->num_rows > 0) {
	while ($row = $pick->fetch_assoc()) {
		$countNotifications++;
	}
}

$add_span_notifications = "";
if ($countNotifications > 0) { $add_span_notifications = "<span>".$countNotifications."</span>"; }


echo "
				<a href='openSettings.php'>Settings</a>
				<a href='loadFriends.php'>Bloggers</a>
				<a href='exploreStories.php'>Stories</a>
				<a href='#' onclick='logMeOut()'>Log out</a>
				<button type='button' id='notifications-button' class='iconic' title='Notifications' onclick='loadNotifications();'>&#xf0f3;$add_span_notifications</button>
				<button type='button' id='search-button' class='searchButton' title='Search' onclick='loadSearchEngine();'><img src='/Library/images/search.png' /></button>
			</div>
";

// ==== NEW CODING STANDARTS ====
	$run_tutorial = "";
	$save_code = "";
	$hobbies = "";
	$sql = "SELECT TUTO, Save_Code, Hobbies, Birthdate FROM WorldBloggers WHERE Author_UID='$sender'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$run_tutorial = $row["TUTO"];
			$save_code = $row["Save_Code"];
			$hobbies = $row["Hobbies"];
			$birthdate = $row["Birthdate"];
		}
	}

	if ( empty( $save_code ) || empty( $hobbies ) || empty( $birthdate ) ) {
?>
		
		<div id="full-page-container">
			<div id="inline-fields" style="margin-top: -140px;">
				<h1>There are a few more things to do :</h1>
				<input type="text" id="profile-picture" class="wide-fat" placeholder="Put a link to a profile picture">
				<input type="text" id="social-profile" class="wide-fat" placeholder="Put a link to a social profile">
				<input type="password" id="authentication-code" class="wide-fat" placeholder="Put your authentication code">
				<input type="text" id="hobbies" class="wide-fat" placeholder="Tell us your hobbies (separete them with comma)">
				<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="birth-date" class="wide-fat" placeholder="When is your birthday ?">
				<button id="submit-auth-code" onclick="saveAuthorMeta();">Save</button>
			</div>
		</div>

<?php
	}	
?>