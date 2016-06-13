<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	$senderPic = $_SESSION['senderImg'];
	
	$getURI = $_SERVER[REQUEST_URI];

	$authorInURI = 0;

	//Get cookies if needed
	if ( strpos($getURI, "?") ) { 
		$blogerSender = end(explode("?", $getURI));
		$authorInURI = 1; 
	}

	if ($blogerSender == $sender || !isset($blogerSender) || !file_exists("../../Authors/$blogerSender/config.txt") ) {
		header('Location: logedIn.php');
	}
	
	$line_counter = 0;
	$parseSender = fopen("../../Authors/$sender/config.txt", "r") or die("Unable to start parsing.");
	while (!feof($parseSender)) {
		$line = trim(fgets($parseSender));
		if ($line_counter == 6) {
			$senderMail = $line;
			break;
		}
		$line_counter++;
	}
	fclose($parseSender);

	//Parse owner if needed
	if ( $authorInURI == 1 ) {
		$line_counter = 0;
		$parseOwner = fopen("../../Authors/".$blogerSender."/config.txt", "r") or die("Fatal: Unable to start parsing.");
		while ( !feof($parseOwner) ) {
			$line = trim(fgets($parseOwner));
			if ($line_counter == 0) { $blogerImg = $line; }
			elseif ($line_counter == 1) { $blogerHref = $line; }
			elseif ($line_counter == 3) { $blogerFN = $line; }
			elseif ($line_counter == 4) { $blogerLN = $line; break;}
			$line_counter++;
		}

		$profileName = $blogerFN." ".$blogerLN;
	}

	//Connect to data base
	include "../Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//Get blocked list of the blogger
	$sql = "SELECT BLOCKEDID FROM blockList$blogerSender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			if ($row['BLOCKEDID'] == $sender) {
				header("Location: ../Errors/M3.html");
				die();
			}
		}
	}
	
	$followersCount = -1;
	$countFollowers =  fopen("../../Authors/$blogerSender/FollowersID.html", "r") or die("Fatal: Could not get Followers.");
	while (!feof($countFollowers)) {
		$followersCount++;
		$line = trim(fgets($countFollowers));
	}
	fclose($countFollowers);
	
	if ($followersCount == "1") {
		$cmdFollowers = "$followersCount follower";
	} else {
		$cmdFollowers = "$followersCount followers";
	}
	
	//Get blocked persons of the visitor
	$blockedPersons = array();
	$sql = "SELECT BLOCKEDID FROM blockList$sender";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			array_push($blockedPersons, $row['BLOCKEDID']);
		}
	}

	$conn->close();

	//Check if visitor is follower
	$isFollower = 0;
	$loadStack = fopen("../../Authors/$blogerSender/FollowersID.html", "r") or die("Unable to load stack.");
	while (!feof($loadStack)) {
		$line = trim(fgets($loadStack));
		if (explode("-", $line)[0] == $senderMail) {
			$isFollower = 1;
			break;
		}
	}
	fclose($loadStack);
	
echo " 
	<html>
		<head>
			<meta name='viewport' content='user-scalable=no'/>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
			<title>$blogerFN's story</title>
			<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../../fonts.css' rel='stylesheet' type='text/css'>

			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

			<script type='text/javascript' src='../../../java.js'></script>
			<script type='text/javascript' src='JAVA/java.js'></script>
			
			<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../../LightBox/js/lightbox.min.js'></script>
			
			<script type='text/javascript'>
				var flag = 0;
				
				var shareFlag = 0;
				function shareIt() {
					if (shareFlag == 0) {
						$('#shareMethod').slideDown('fast');
						shareFlag = 1;
					}
					else
					if (shareFlag== 1) {
						$('#shareMethod').slideUp('fast');
						shareFlag = 0;
					}
				}
			
				function sendMessage() {
					var text = document.getElementById('content').value;
					if (text == '') {
						alert('Well write something in your message.');
					}
					else
					if (text != '') {
						document.getElementById('messageInput').action = 'sendMessage.php';
						document.forms['messageInput'].submit();
					}
				}
				
				function followAuthor() {
					document.getElementById('post').action = 'followBloger.php';
					document.forms['post'].submit();
				}

				function shareStory() {
					document.getElementById('share').action = '../PHP/writeMethod.php';
					document.forms['share'].submit();
				}

				//Callback function - Load stories
				var container = [];
				var loops = parseInt('$countStories');
				var dinamic = loops;
				
				//Callback function - Load stories
				var loops = 1;
				var lastId = -1;

				function callBack() {
					loops = 1;
					loadStories(lastId, \"1\", 2, \"".$blogerSender."\");
				}

				var flag = 0;
			</script>
		</head>
		<body onload='loadStories(-1, \"1\", 2, \"".$blogerSender."\");' onscroll='checkPos()'>
";
	include 'loadMenu.php';
echo "	
			<form id='accountInfo' method='post' style='display: none;'>
				<input type='text' name='sender' value='$sender'></input>
				<input type='text' id='cmd' name='cmd'></input>
			</form>
			<form id='post' method='post' style='display: none;'>
				<input name='sender' value='$sender'></input>
				<input name='authorId' value='$blogerSender'></input>
			</form>

			<div id='shareMethod' style='display: none;'>
				<div id='buttons'>
					<div id='facebook'>
						<a href='http://www.facebook.com/share.php?u=http://".$_SERVER[HTTP_HOST]."?$blogerSender&title=$blogerFN $blogerLN's story' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-facebook-128.png' />
						</a>
					</div>
					<div id='twitter'>
						<a href='http://twitter.com/home?status=Check+this+story+http://".$_SERVER[HTTP_HOST]."?$blogerSender' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-twitter-128.png' />
						</a>
					</div>
					<div id='googlePlus'>
						<a href='https://plus.google.com/share?url=http://".$_SERVER[HTTP_HOST]."?$blogerSender' target='_blank'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-google-plus-128.png' />
						</a>
					</div>
				</div>
			</div>
			
			<div id='author'>
				<div class='left'>
					<a title='Share' href='#' onclick='shareIt()'>
						<img src='https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_share-128.png' />
					</a>
				</div>
				<div class='right'>
					<a href='#' onclick='showOptionsM()'>
						<img src='https://cdn3.iconfinder.com/data/icons/google-material-design-icons/48/ic_menu_48px-128.png' />
					</a>
					<div id='quickMenuContainer' onclick='hideOptionsM()'>
						<div id='container' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>
";
	if (!in_array($blogerSender, $blockedPersons)) {
		echo "
			<button type='button' class='split-line' onclick='showMessageBox(\"$blogerSender\")'>Send message</button>
			<form id='$blogerSender' method='post' style='display: none;'>
				<input type='text' name='blogSender' value='$blogerSender'></input>
				<input type='text' name='blogerFN' value='$blogerFN'></input>
				<input type='text' name='blogerLN' value='$blogerLN'></input>
				<input type='text' name='blogerImg' value='$blogerImg'></input>
				<input type='text' name='blogerHref' value='$blogerHref'></input>
			</form>
		";
	}

	if ($isFollower == 1) {
		if (file_exists("../../Authors/$sender/Ohana.txt")) {
			$interupt = 0;
			$pullOhana = fopen("../../Authors/$sender/Ohana.txt", "r") or die("Fatal: Could not get ohana.");
			while (!feof($pullOhana)) {
				$line = trim(fgets($pullOhana));
				if ($line != "") {
					if ($line == $blogerSender) {
						$button = "<button type='button' class='split-line' onclick='removeFromOhana(\"$blogerSender\")'>Remove from Ohana</button>";
						$interupt = 1;
						break;
					}
				}
			}
			
			if ($interupt == 0) {
				$button = "<button type='button' class='split-line' onclick='addToOhana(\"$blogerSender\")'>Add to Ohana</button>";
			}
		} else {
			$button = "<button type='button' class='split-line' onclick='addToOhana(\"$blogerSender\")'>Add to Ohana</button>";
		}
	}

	if (!in_array($blogerSender, $blockedPersons)) {
		$blockUnblock = "<button type='button' class='split-line' onclick='blockUser(\"$blogerSender\")'>Block user</button>";
	} else {
		$blockUnblock = "<button type='button' class='split-line' onclick='unBlockUser(\"$blogerSender\")'>Unblock user</button>";
	}

echo "
							$button
							$blockUnblock
							<button type='button' onclick='buildReportContainer(1, \"$blogerSender\");'>Report user</button>
						</div>
					</div>
				</div>
";

	if ($blogerHref != "NULL") {
		echo "
			<div id='profilePictureImg'>
				<a href='$blogerImg' class='profilePicture' data-lightbox='roadtrip'>
					<div style='background-image:url(\"$blogerImg\")'>
					</div>
				</a>
			</div>
			<br>
			<a href='$blogerHref' target='_blank'>
				$profileName
			</a>
		";
	}
	else
	if ($blogerHref == "NULL") {
		echo "
			<div id='profilePictureImg'>
				<a href='$blogerImg' class='profilePicture' data-lightbox='roadtrip'>
					<div style='background-image:url(\"$blogerImg\")'>
					</div>
				</a>
			</div>
			<br>
			<a class='inactive'>
				$profileName
			</a>
		";
	}

echo "
			<br>
			<div id='followers'>
				<h1>$cmdFollowers</h1>
";
	
	if ($isFollower == 0) {
		echo "<a href='#' onclick='followAuthor()'>Follow</a>";
	}
	else
	if ($isFollower == 1) {
		echo "
		<div id='follower'>
			<a href='#' onclick='followAuthor()'>Unfollow</a>
		</div>";
	}
			
			//	<a href='#' onclick='followAuthor()'>Follow</a>

echo "
			</div>
		</div>
		<div id='body'>
			<table id='main-table'>
";
	
echo "
			</table>
		</div>
	</body>
</html>
";

#Scroll to point
	$getScrollPos = $_COOKIE['scrollToPos'];
	if (isset($getScrollPos)) {
		echo "
			<script>
				$(window).scrollTop($getScrollPos);
				document.cookie = 'scrollToPos=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
			</script>
		";
	}
?>