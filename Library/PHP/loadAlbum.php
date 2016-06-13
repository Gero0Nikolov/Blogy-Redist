<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	$profilePic = $_SESSION['senderImg'];
	$profileHref = $_SESSION['senderHref'];
	$profileFirst = $_SESSION['senderFN'];
	$profileLast = $_SESSION['senderLN'];
	
	$checkPath = "../Authors/$sender/Album";
	if (!file_exists($checkPath)) {
		mkdir($checkPath, 0777);
		$buildSecurity = fopen("../Authors/$fullName/index.php", "w") or die("Fatal: Unable to build security.");
		fwrite($buildSecurity, "<?php header('Location: ../../../../SignIn.html');?>");
		fclose($buildSecurity);
	}
	
	//Connect to data base
	include "Universal/dataBase.php";
	
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
			$getSizeInMB = 100.00;
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
	$pullFriends = fopen("../Authors/$sender/Following.txt", "r") or die("Fatal: Unable to get friends.");
	while (!feof($pullFriends)) {
		$line = trim(fgets($pullFriends));
		if ($line != "") {
			array_push($friendsStack, $line);
		}
	}
	fclose($pullFriends);

	//Put text
	if ( $getSizeInMB > 0 ) {
		$putSizeText = $getSizeInMB ."mb";
	} 
	
	
//Build UI
echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>$profileFirst's album</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>	

			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

			<script type='text/javascript' src='../../java.js'></script>
			
			<link href='../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../LightBox/js/lightbox.min.js'></script>
			
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
						document.getElementById('writePost').action = '../PHP/writeMethodAlbum.php';
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
						document.getElementById('sendButton').style.visibility = 'visible';
					} else {
						document.getElementById('sendButton').style.visibility = 'hidden';
					}
				}
				
				function sendImage() {
					if (sendTo.length > 0) {
						var shareWith = sendTo.toString();
						document.cookie = 'shareWith='+shareWith;
						window.location = 'sendAlbumImage.php';
					} else {
						alert('Choose some friends first.');
					}
				}
			</script>
		</head>
		<body onload='loadAlbum(0, $getId);'>
			<div id='fullPageContainer' onclick='stopVideo()'>
			</div>
			<div id='sub-logo'>
				<h1>Album</h1>
			</div>
			<div id='albumOptions'>
				<span class='free-space'>Free space: $putSizeText</span>
";
	
	if ($getSizeInMB < 100.00) {
		echo "<button type='button' class='slideShow' title='Slides' onclick='window.open(\"startSlideShow.php\")'><img src='https://cdn2.iconfinder.com/data/icons/devine-icons-part-2/128/Slideshow.png' /></button>";
	}
	
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
	include 'loadSuggestedBlogers.php';

echo "
			<div id='storeFriends'>
				<button class='sendButton' id='sendButton' onclick='sendImage()'>Send</button>
				<button class='hideButton' onclick='hideContainerFriends()'></button>
				<h1>Choose friends :</h1>
				<div id='chooseToSend'>
";
	
	$friendId = 0;
	foreach ($friendsStack as $friend) {
		$pickUpCount = 0;
		$parseUser = fopen("../Authors/$friend/config.txt", "r") or die("Unable to start parsing.");
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
			</div>
			<div id='makePost'>
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
			</div>
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