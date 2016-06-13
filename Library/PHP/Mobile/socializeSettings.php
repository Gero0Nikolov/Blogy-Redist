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

	echo "
	<head>
		<meta name='viewport' content='user-scalable=no'/>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's panel</title>
		<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen' />
		<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>

		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

		<script type='text/javascript' src='JAVA/java.js'></script>
		<script type='text/javascript' src='../../../java.js'></script>

		<script type = 'text/javascript'> 			
		</script>
	</head>
	<body>
";
	include 'loadMenu.php';
echo "
		<div id='sub-logo'>
			<h1>Statistics</h1>
		</div>
		<div id='socialize-container'>
";

	//Connect to data base
	include "../Universal/dataBase.php";

	$posts = 0;

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "SELECT ID FROM stack$sender";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$posts++;
			}
		}
	}
	$conn->close();

	if ($posts == 1) {
		$cmd = "$posts post";
	} else {
		$cmd = "$posts posts";
	}
	
echo " 
		<div id='left-socialize-container'>
			<button onclick='window.location=\"logedIn.php\"'>$cmd</button>
		</div>
";

	$printMethod = (string)NULL;
	$followers = 0;
	$loadFollowing = fopen("../../Authors/$sender/Following.txt", "r") or die("Unable to open file.");
	while (!feof($loadFollowing)) {
		$line = trim(fgets($loadFollowing));
		if ($line != "") {
			$pickUpCount = 0;
			$parseUser = fopen("../../Authors/$line/config.txt", "r") or die("Unable to start parsing.");
			while (!feof($parseUser)) {
				$pickUpLine = trim(fgets($parseUser));
				if ($pickUpCount == 0) {
					$userImg = $pickUpLine;
				}
				else
				if ($pickUpCount == 1) {
					$userHref = $pickUpLine;
				}
				else
				if ($pickUpCount == 3) {
					$userFN = $pickUpLine;
				}
				else
				if ($pickUpCount == 4) {
					$userLN = $pickUpLine;
					break;
				}
				$pickUpCount++;
			}
			fclose($parseUser);
			
			$authorId = $line;
			
			$printMethod .= "
				<a href='openBloger.php?$authorId'>
					<img src='$userImg' alt='Bad image link :(' />
					$userFN $userLN
				</a>
			";
			
			$followers++;
		}
	}
	fclose($loadFollowing);
	
	$cmd = "$followers following";

echo "
		<div id='middle-socialize-container'>
			<button onclick='$(\"#friends-container\").slideToggle(\"fast\");'>$cmd</button>
			<div id='friends-container'>
				$printMethod
			</div>
		</div>
";	
	$followersCount = -1;
	$countFollowers =  fopen("../../Authors/$sender/FollowersID.html", "r") or die("Fatal: Could not get Followers.");
	while (!feof($countFollowers)) {
		$followersCount++;
		$line = fgets($countFollowers);
	}
	fclose($countFollowers);
	
	if ($followersCount == "1") {
		$cmd = "$followersCount follower";
	} else {
		$cmd = "$followersCount followers";
	}

echo "
			<div id='right-socialize-container'>
				<button onclick='window.location=\"exploreFollowers.php\"'>$cmd</button>
			</div>
		</div>
	</body>
";
?>