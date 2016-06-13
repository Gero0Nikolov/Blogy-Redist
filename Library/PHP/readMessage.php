<?php
	session_start();
	$sender = $_SESSION['sender'];
	$profilePic = $_SESSION['senderImg'];

	if (!isset($sender)) {
		header('Location: ../../SignIn.html');
	}
	
	$line_count = 0;
	
	$messangerId = explode("?", $_SERVER['REQUEST_URI'])[1];

	if ( !isset($messangerId) || empty($messangerId) ) {
		header("Location: storeMessages.php");
		die();
	}

	if ( !file_exists("../Authors/$messangerId/config.txt") ) {
		header("Location: storeMessages.php");
		die();
	}

	$parseMessanger = fopen("../Authors/$messangerId/config.txt", "r") or die("Unable to start parsing.");
	while (!feof($parseMessanger)) {
		$line = fgets($parseMessanger);
		if ($line_count == 0) {
			$messangerImg = trim($line);
		}
		else
		if ($line_count == 1) {
			$messangerHref = trim($line);
		}
		else
		if ($line_count == 3) {
			$messangerFN = trim($line);
		}
		else
		if ($line_count == 4) {
			$messangerLN = trim($line);
			break;
		}
		$line_count++;
	}
	fclose($parseMessanger);
	$line_count = 0;

	//TMP_SESSION VARIABLES
	$_SESSION["messangerId"] = $messangerId;
	$_SESSION["messangerImg"] = $messangerImg;

	//Connect to data base
	include "Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Update message notifications
	$sql = "UPDATE Messages_$sender SET Message_Status = 1 WHERE Messenger='$messangerId'";
	$conn->query($sql);
	
	//Close the connection
	$conn->close();

echo "
	<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
			<link rel='shortcut icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<link rel='icon' href='../images/Blogy-ICO.png' type='image/x-icon'>
			<title>Story with $messangerFN</title>
			<link href='../../style.css' rel='stylesheet' type='text/css' media='screen'>
			<link href='../../fonts.css' rel='stylesheet' type='text/css'>

			<link href='../../../LightBox/css/lightbox.css' type='text/css' rel='stylesheet' />
			<script src='../../../LightBox/js/jquery-1.11.0.min.js'></script>
			<script src='../../../LightBox/js/lightbox.min.js'></script>

			<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
			<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>

			<script type='text/javascript' src='../../java.js'></script>

			<script type='text/javascript'>
				var flag = 0;
				var imgFlag = 0;

				var lastMessageId = -1;
				
				function addImg() {
					if (imgFlag == 0) {
						$('#imgPlaceholder').slideDown('fast');
						imgFlag = 1;
					}
					else
					if (imgFlag == 1) {
						$('#imgPlaceholder').slideUp('fast');
						imgFlag = 0;
					}
				}

				function sendMessage() {
					var text = document.getElementById('messageTXT').value;
					var img = document.getElementById('imgHolder').value;
					
					if (text.trim() == '' && img == '') {
						alert('Message needs something.');
					} else {
						if (text != '' && img != '') {
							document.getElementById('messageTXT').value +=  '^type*img~nl2br~src*'+img;
						}
						else
						if (text == '' && img != '') {
							document.getElementById('messageTXT').value += '^type*img~src*'+img;
						}
						
						document.getElementById('answer').action = 'sendMessage.php';
						document.forms['answer'].submit();
					}
				}

				function showFullContainer() {
					$('#answer > textarea').animate({height: '100px'}, 300);
				}

				function hideFullContainer() {
					$('#answer > textarea').animate({height: '50px'}, 300);
				}
			</script>
		</head>
		<body onload='loadMessages(0, -1); requestNewMessages(\"".$sender."\", \"".$messangerId."\", 0);'>
";
	include 'loadMenu.php';
	include 'loadSuggestedBlogers.php';
echo "
			<div id='body'>
				<form id='answer' method='post' tabindex='1' onblur='hideFullContainer();'>
					<textarea id='messageTXT' name='content' placeholder='What&#39;s up' onfocus='showFullContainer();'></textarea><br>
					<div id='imgPlaceholder' style='display: none;'>
						<div class='separate'></div>
						<input type='text' placeholder='Share image link' id='imgHolder'></input><br>
					</div>
					<a href='#!' class='leftOption' onclick='addImg()'>
						<img id='imgHolder' src='https://cdn4.iconfinder.com/data/icons/adiante-apps-app-templates-incos-in-grey/128/app_type_photographer_512px_GREY.png' alt='Bad link :('>
					</a>
					<a href='#!' class='rightOption' onclick='showEmojiContainer()'>
						<img src='https://cdn4.iconfinder.com/data/icons/imoticons/105/imoticon_12-128.png' alt='Bad link :(' />
					</a>
					<a href='#!' onclick='sendMessage()'>Send</a>
					<div style='display: none;'>
						<input type='text' name='sender' value='$sender'></input>
						<input type='text' name='authorId' value='$messangerId'></input>
						<input type='text' name='cmd' value='1'></input>
					</div>
					<div class='emoji_container' id='emojis'>
						<button type='button' onclick='addEmoji(\"lol\")'><img src='../images/Emoji/lol.png' title='Laugh' /></button>
						<button type='button' onclick='addEmoji(\"smile\")'><img src='../images/Emoji/smile.png' title='Smile' /></button>
						<button type='button' onclick='addEmoji(\"lolo\")'><img src='../images/Emoji/lolo.png' title='Laugh out loud' /></button>
						<button type='button' onclick='addEmoji(\"tongue\")'><img src='../images/Emoji/tongue.png' title='Tongue' /></button>
						<button type='button' onclick='addEmoji(\"inlove\")'><img src='../images/Emoji/inlove.png' title='Inlove' /></button>
						<button type='button' onclick='addEmoji(\"kiss\")'><img src='../images/Emoji/kiss.png' title='Kiss' /></button>
						<button type='button' onclick='addEmoji(\"scare\")'><img src='../images/Emoji/scare.png' title='Scare' /></button>
						<button type='button' onclick='addEmoji(\"cry\")'><img src='../images/Emoji/cry.png' title='Cry' /></button>
						<button type='button' onclick='addEmoji(\"ooh\")'><img src='../images/Emoji/ooh.png' title='Ooh' /></button>
						<button type='button' onclick='addEmoji(\"wat\")'><img src='../images/Emoji/wat.png' title='What' /></button>
						<button type='button' onclick='addEmoji(\"wink\")'><img src='../images/Emoji/wink.png' title='Wink' /></button>
						<button type='button' onclick='addEmoji(\"mybad\")'><img src='../images/Emoji/mybad.png' title='Oops' /></button>
						<button type='button' onclick='addEmoji(\"meh\")'><img src='../images/Emoji/meh.png' title='Meh' /></button>
						<button type='button' onclick='addEmoji(\"sad\")'><img src='../images/Emoji/sad.png' title='Sad' /></button>
						<button type='button' onclick='addEmoji(\"muchCry\")'><img src='../images/Emoji/muchCry.png' title='Very very sad' /></button>
						<button type='button' onclick='addEmoji(\"calm\")'><img src='../images/Emoji/calm.png' title='Calm' /></button>
						<button type='button' onclick='addEmoji(\"sexy\")'><img src='../images/Emoji/sexy.png' title='Hey sexy' /></button>
						<button type='button' onclick='addEmoji(\"angry\")'><img src='../images/Emoji/angry.png' title='You are going to ahh..' /></button>
						<button type='button' onclick='addEmoji(\"redH\")'><img src='../images/Emoji/RH.png' title='Red Heart' /></button>
						<button type='button' onclick='addEmoji(\"blueH\")'><img src='../images/Emoji/BH.png' title='Blue Heart' /></button>
						<button type='button' onclick='addEmoji(\"greenH\")'><img src='../images/Emoji/GH.png' title='Green Heart' /></button>
					</div>
				</form>
				<div id='message'>
					<a id='messenger' href='openBloger.php?$messangerId'>
						<div class='img' style='background-image: url($messangerImg); background-size: cover; background-position: 50%;'></div>
						$messangerFN $messangerLN
					</a>
					<div id='message-text'>
";	
echo "
					</div>
				</div>
			</div>
		</body>
	</html>
";
?>