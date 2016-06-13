<?php
	error_reporting(0);

	if (strpos($_SERVER[REQUEST_URI], "?")) $getID = end(explode("?", $_SERVER[REQUEST_URI]));
	else $getID = NULL;

	if (empty($getID) || !isset($getID) || $getID == "") {
		blockAccess();
	}

	$doLine = 0;
	$config = fopen("../../Authors/$getID/config.txt", "r") or blockAccess();
	while (! feof($config)) {
		$line = trim(fgets($config));

		if ($doLine == 0) {
			$profilePic = $line;
		}
		else
		if ($doLine == 1) {
			$profileHref = $line;
		}
		else
		if ($doLine == 2) {
			$fullName = $line;
		}
		else
		if ($doLine == 3) {
			$profileFirst = $line;
		}
		else
		if ($doLine == 4) {
			$profileLast = $line;
			break;
		}
		$doLine++;
	}
	fclose($config);
	$profileName = "$profileFirst $profileLast";
	
	$followersCount = -1;
	$countFollowers =  fopen("../../Authors/$getID/FollowersID.html", "r") or blockAccess();
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

	if ( $profileHref == "NULL" ) { $profileHref = "http://". $_SERVER["HTTP_HOST"] ."?". $getID; }

	$random_background = rand(1, 5);

echo "
<html>
	<META http-equiv='content-type' content='text/html; charset=utf-8'>
	<head>
		<meta name='viewport' content='user-scalable=no'/>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
		<title>$profileFirst's story</title>
		<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen'>
		<link href='../../../fonts.css' rel='stylesheet' type='text/css'>

		<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

		<script type='text/javascript' src='JAVA/java.js'></script>		
		<script type='text/javascript' src='../../../java.js'></script>
		
		<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
		<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
		<script src='../../../LightBox/js/lightbox.min.js'></script>

		<script type='text/javascript'>
			var shareFlag = 0;
			function shareIt() {
				if (shareFlag == 0) {
					document.getElementById('shareMethod').style.visibility='visible'; 
					$('#shareMethod').slideDown('fast');
					shareFlag = 1;
				}
				else
				if (shareFlag== 1) {
					$('#shareMethod').slideUp('fast');
					document.getElementById('shareMethod').style.visibility='hidden'; 
					shareFlag = 0;
				}
			}

			//Callback function - Load stories
			var loops = 1;
			var lastId = -1;
			
			function callBack() {
				loops = 1;
				loadStories(lastId, \"4\", 1, \"$getID\");
			}

			var flag = 0;

			function showStory() {
				$( '#profile-container' ).addClass( 'hide-top' );
				setTimeout(function(){
					$( 'body' ).css( 'overflow', 'auto' );
				}, 300);
			}

			function hideStory() {
				$( 'body' ).css( 'overflow', 'hidden' );
				$( '#profile-container' ).removeClass( 'hide-top' );
			}

			var topHits = 0;

			function showHideBio() {
				scrollPos = $( 'body' ).scrollTop();

				if ( scrollPos > 0 ) { showStory(); }
				else if ( scrollPos < -20 ) { hideStory(); }
			}
		</script>
	</head>
	<body onload='loadStories(-1, \"4\", 1, \"$getID\");' onscroll='checkPos(); showHideBio();'>
		<div id='profile-container' style='background-image: url(\"http://". $_SERVER["HTTP_HOST"] ."/Library/images/backgrounds/$random_background.jpg\");'>
			<div class='author-container'>
				<div class='profile-picture' style='background-image: url($profilePic); background-size: cover; background-position: 50%;'>
				</div>
				<div class='meta-information'>
					<a href='$profileHref' target='_blank'>
						<h1 class='author-name'>$profileName</h1>
					</a>
					<h2 class='author-followers'>$cmdFollowers</h1>
					<div class='social-icons'>
						<a href=\"http://www.facebook.com/share.php?u=http://".$_SERVER[HTTP_HOST]."?$getID&title=$profileFirst $profileLast's story\" target='_blank' title='Share me'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-facebook-128.png' />
						</a>
						<a href=\"http://twitter.com/home?status=Check+this+story+http://".$_SERVER[HTTP_HOST]."?$getID\" target='_blank' title='Tweet me'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-twitter-128.png' />
						</a>
						<a href=\"https://plus.google.com/share?url=http://".$_SERVER[HTTP_HOST]."?$getID\" target='_blank' title='Google me'>
							<img src='https://cdn1.iconfinder.com/data/icons/logotypes/32/square-google-plus-128.png' />
						</a>
					</div>
				</div>
				<div class='action-buttons'>
					<a href='http://". $_SERVER['HTTP_HOST'] ."?f=". $getID ."' class='follow-button'>Follow</a>
					<button class='read-button' onclick='showStory();'>Read me</button>
				</div>
			</div>
		</div>

		<div id='body'>
			<table id='main-table'>
			</table>
		</div>
	</body>
</html>
";
	
	//Functions
	function blockAccess() {
		echo "
			<html>
				<head>
					<link rel='shortcut icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
					<link rel='icon' href='../../images/Blogy-ICO.png' type='image/x-icon'>
					<title>Oops :(</title>
					<link href='CSS/style.css' rel='stylesheet' type='text/css' media='screen' />
					<link href= '../../../fonts.css' rel='stylesheet' type='text/css'>
				</head>
				<body>
					<div class='error-message'>
						<h1>Oops.. It seem that we don't find this author. :(</h1>
						<a href='http://".$_SERVER[HTTP_HOST]."'>Log in</a>
					</div>
				</body>
			</html>
		";

		die();
	}
?>