<?php
	$pic = strip_tags($_POST['profilePic']);
	$social = strip_tags($_POST['profileHref']);
	$fName = ucfirst(strtolower(strip_tags($_POST['fName'])));
	$lName = ucfirst(strtolower(strip_tags($_POST['lName'])));
	$pass = strip_tags($_POST['pass']);
	$auth_code = $_POST['authCode'];
	$authorID = strip_tags($_POST['sender']);
	$notifyOnPost = strip_tags($_POST['notifyOnPost']);
	$notifyOnMessage = strip_tags($_POST['notifyOnMessage']);

	//Include functions bundle
	include "../Universal/functions.php";
	$pass = bruteDecrypt($pass);

	if(@file_get_contents($pic,0,NULL,0,1)) {
		if (getimagesize($pic) !== false) {} else { $pic = "https://cdn1.iconfinder.com/data/icons/user-pictures/100/unknown-512.png"; }
	} else { $pic = "https://cdn1.iconfinder.com/data/icons/user-pictures/100/unknown-512.png"; }
	
	$count = 0;
	$loadConfig = fopen("../../Authors/$authorID/config.txt", "r") or die("Unable to open config.");
	while (! feof($loadConfig)) {
		$line = fgets($loadConfig);
		if ($count == 6) {
			$mail = trim($line);
		}
		$count++;
	}
	fclose($loadConfig);
	
	$path = "../../Authors/$authorID/config.txt";
	
	$fd = fopen("$path", "w") or die("Unable to open file.");
	fwrite($fd, $pic.PHP_EOL);
	fwrite($fd, $social.PHP_EOL);
	fwrite($fd, $authorID.PHP_EOL);
	fwrite($fd, $fName.PHP_EOL);
	fwrite($fd, $lName.PHP_EOL);
	fwrite($fd, $pass.PHP_EOL);
	fwrite($fd, $mail.PHP_EOL);
	fwrite($fd, $notifyOnPost.PHP_EOL);
	fwrite($fd, $notifyOnMessage);
	fclose($fd);	
	
	$nickname = $fName.$lName;

	//Connect to the Database
	include "../Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Push the changes into the Database
	$sql = "UPDATE WorldBloggers SET Nickname='$nickname', Save_Code='$auth_code' WHERE Author_UID='$authorID'";
	$conn->query($sql);

	//Close the connection
	$conn->close();

/*
	echo "
		<script>
			document.cookie = 'senderImg='+'$pic';
			document.cookie = 'senderHref'+'$social';
			document.cookie = 'senderFN='+'$fName';
			document.cookie = 'senderLN='+'$fName';
		</script>
	";
*/

	session_start();
	$_SESSION['senderImg'] = $pic;
	$_SESSION['senderHref'] = $social;
	$_SESSION['senderFN'] = $fName;
	$_SESSION['senderLN'] = $lName;
	
	header('Location: openSettings.php');
	
	die();
?>