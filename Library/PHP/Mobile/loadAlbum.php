<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	$checkPath = "../../Authors/$sender/Album";
	if (!file_exists($checkPath)) {
		mkdir($checkPath, 0777);
		$buildSecurity = fopen("../../Authors/$fullName/index.php", "w") or die("Fatal: Unable to build security.");
		fwrite($buildSecurity, "<?php header('Location: ../../../../SignIn.html');?>");
		fclose($buildSecurity);
	}
	
	//Connect to data base
	include "../Universal/dataBase.php";
	
	$getId = 0;
	$stackOrder = array();
	$storeStories = array();
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "CREATE TABLE albumOf$sender (ID int NOT NULL AUTO_INCREMENT, ALBUM LONGTEXT, SPACE LONG, PRIMARY KEY (ID))";
		if ($conn->query($sql) === TRUE) {
			$sql = "INSERT INTO albumOf$sender (ALBUM, SPACE) VALUES ('SPACE', '100000000')";
			$conn->query($sql);
		} else {
			//Get content
			$sql = "SELECT ID, ALBUM, SPACE FROM albumOf$sender ORDER BY ID";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					if ($row['ALBUM'] == "SPACE") {
						$getSizeInMB = (int)$row['SPACE'] * 0.000001;
						$getSizeInMB = number_format ($getSizeInMB, 2);
					} else {
						$getId = $row["ID"];
					}
				}
			}
		}
	}
	$conn->close();

	//Get friends
	$friendsStack = array();
	$pullFriends = fopen("../../Authors/$sender/Following.txt", "r") or die("Fatal: Unable to get friends.");
	while (!feof($pullFriends)) {
		$line = trim(fgets($pullFriends));
		if ($line != "") {
			array_push($friendsStack, $line);
		}
	}
	fclose($pullFriends);
	
	
//Build UI
echo "
	<html>
		<head>
			<meta name='viewport' content='user-scalable=no'/>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
			<title>$profileFirst's album</title>
			<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../../fonts.css' rel='stylesheet' type='text/css'>		

			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

			<script type='text/javascript' src='JAVA/java.js'></script>
			<script type='text/javascript' src='../../../java.js'></script>
			
			<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../../LightBox/js/lightbox.min.js'></script>

			<script>
				function writePost() {
					var title = document.getElementById('titleIdCode').value;
					
					var img = document.getElementById('postImg').value;
					var content = document.getElementById('content').value;
					
					if (title == '') {
						alert('Give title to your post.');
					}
					else
					if (img == '' && content.trim() == '') {
						alert('Well write something or add a picture.');
					}
					else {
						document.getElementById('writePost').action = 'writeMethodAlbum.php';
						document.forms['writePost'].submit();
					}
				}
				
				var sendTo = [];
				function shareAlbumObject(userId, id) {
					if (sendTo.indexOf(userId) > -1) {
						document.getElementById(\"friend\"+id).style.color = 'black';
						var index = sendTo.indexOf(userId);
						sendTo.splice(index, 1);
					} else {
						document.getElementById(\"friend\"+id).style.color = '#0088cc';
						sendTo.push(userId);
					}
					
					if (sendTo.length > 0) {
						document.getElementById('sendButton').style.color = '#0088cc';
					} else {
						document.getElementById('sendButton').style.color = '#ccc';
					}
				}
				
				function sendImage(picture) {
					if (sendTo.length > 0) {
						document.cookie = 'sharePicture='+picture;
						var shareWith = sendTo.toString();
						document.cookie = 'shareWith='+shareWith;
						window.location = 'sendAlbumImage.php';
					} else {
						alert('Choose some friends first.');
					}
				}
			</script>
		</head>
		<body onload='loadAlbum(1, $getId);'>
			<div id='fullPageContainer' onclick='stopVideo()'>
			</div>
			<div id='sub-logo'>
				<h1>Album</h1>
			</div>
			<div id='albumOptions'>
				<span class='free-space'>Free space: $getSizeInMB"."mb</span>
";
	
	if ($getSizeInMB >= 5.00) {
		echo "
			<button type='button' class='uploadButton' title='Upload' onclick='openDialog()'><img src='https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/upload-128.png'></button>
			<form id='toUpload' style='display: none;' method='post' enctype='multipart/form-data'>
				<input type='file' name='fileToUpload' id='fileToUpload' onchange='startToUpload()'>
			</form>
		";
	}
	
echo "
			</div>
";
	include "loadMenu.php";

echo "
			<div id='viewportContainer' onclick='$(\"#viewportContainer\").fadeOut(\"fast\");'>
				<img src='#' alt='Bad img link :(' id='imgShower' />
			</div>
			<div id='imgOptions-main' onclick='hideAlbumOptionsM()'>
				<div id='container' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>
					<button class='split-line' id='view-button'>View</button>
					<button class='split-line' id='make-button'>Make a story</button>
					<button class='split-line' id='share-button'>Send to a friend</button>
					<button class='split-line' id='set-button'>Set as profile picture</button>
					<button class='split-line' id='set-logo'>Set as club logo</button>
";

	if ($getSizeInMB < 100.00) {
		echo "<button type='button' class='split-line' title='Slides' onclick='window.open(\"startSlideShow.php\")'>Start a slide show</button>";
	}

echo "
					<!--<button class='split-line'>Start a slide show</button>-->
					<button id='delete-button'>Delete</button>
				</div>
			</div>
			<div id='makePost-main' onclick='clearReservation()'>
				<div id='container' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>
					<form id='writePost' method='post'>
						<input type='text' placeholder='Give it title.' id='titleIdCode' name='title'>
						<input type='text' style='display: none;' id='postImg' name='photo'>
						<textarea placeholder='What&#39;s up ?' id='content' name='content'></textarea>
						
						<button onclick='writePost()'>Post</button>
						
						<input type='hidden' name='sender' value='$sender'></input>
						<input type='hidden' name='fname' value='$profileFirst'></input>
						<input type='hidden' id='cmd' name='cmd' value='1'></input>
					</form>
				</div>
			</div>
			<div id='storeFriends-main' onclick='clearStore()'>
				<button class='hide-button' onclick='clearStore()'>Close</button>
				<div id='storeFriends' onclick='event.cancelBubble=true;if(event.stopPropagation) event.stopPropagation();return false;'>
					<h1>Send to :</h1>
					<div id='chooseToSend'>
";
	
	$friendId = 0;
	foreach ($friendsStack as $friend) {
		$pickUpCount = 0;
		$parseUser = fopen("../../Authors/$friend/config.txt", "r") or die("Unable to start parsing.");
		while (!feof($parseUser)) {
			$pickUpLine = trim(fgets($parseUser));
			if ($pickUpCount == 0) {
				$friendImg = $pickUpLine;
			}
			else
			if ($pickUpCount == 1) {
				$friendHref = $pickUpLine;
			}
			else
			if ($pickUpCount == 3) {
				$friendFN = $pickUpLine;
			}
			else
			if ($pickUpCount == 4) {
				$friendLN = $pickUpLine;
				break;
			}
			$pickUpCount++;
		}
		fclose($parseUser);
		
		$friendId++;
		$build = "
			<button type='button' id='friend$friendId' onclick='shareAlbumObject(\"$friend\", \"$friendId\")'>
				<div class='img' style='background-image: url($friendImg); background-size: cover; background-position: 50%;'></div>
				$friendFN $friendLN
			</button>
		";
		
		echo $build;
	}

echo "
					</div>
					<div id='options'>
						<button class='sendButton' id='sendButton'>Send</button>
					</div>
				</div>
			</div>
			<!--<div id='makePost'>
				<button class='hideButton' onclick='hideContainerPost()'></button>
				<div id='container'>
					<form id='writePost' method='post'>
						<input type='text' placeholder='Give it title.' id='titleIdCode' name='title'>
						<input type='text' style='display: none;' id='postImg' name='photo'>
						<br>
						<textarea placeholder='What&#39;s up ?' id='content' name='content'></textarea>
						
						<a href='#' onclick='writePost()'>Post</a>
						
						<input type='hidden' name='sender' value='$sender'></input>
						<input type='hidden' name='fname' value='$profileFirst'></input>
						<input type='hidden' id='cmd' name='cmd' value='1'></input>
					</form>
				</div>
			</div>-->
			<div id='albumImages'>
";
	
echo "
			</div>
		</body>
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