<?php
	$fName = escapeAttr($_POST['fName']);
	$lName = escapeAttr($_POST['lName']);
	$mail = escapeAttr($_POST['mail']);
	$picture = escapeAttr($_POST['picture']);
	$profile = escapeAttr($_POST['social']);
	$pass = $_POST['pass'];

	if(@file_get_contents($picture,0,NULL,0,1)) {
		if (getimagesize($picture) !== false) {} else { $picture = "https://cdn1.iconfinder.com/data/icons/user-pictures/100/unknown-512.png"; }
	} else { $picture = "https://cdn1.iconfinder.com/data/icons/user-pictures/100/unknown-512.png"; }
	
	
	if (!ctype_alnum($fName) || !ctype_alnum($lName)) {
		echo "<script>window.location='../Errors/E12.html'</script>";
		die();
	}
	
	if ($profile == "") {
		$profile = "NULL";
	}
	
	$error = "Unable to open file.";
	$fullName = $fName.$lName;
	$nickname = $fName.$lName;
	$freeName = 0;
	$flag = 0;

	//Include functions.php
	include "Universal/functions.php";

	//Connect to Database
	include "Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Create Blacklist table if not excist
	$sql = "CREATE TABLE Blacklist (ID int NOT NULL AUTO_INCREMENT, BLACK_IP LONGTEXT, PRIMARY KEY (ID))";
	if ($conn->query($sql) === TRUE) { /* Blacklist is created */ }

	//Get clients IP
	$getIp = get_client_ip();

	//Add current IP address into the black list
	$sql = "INSERT INTO Blacklist (BLACK_IP) VALUES ('".$getIp."')";
	$conn->query($sql);

	//Check if e-mail is already registered
	$sql = "SELECT Author_EMAIL FROM WorldBloggers WHERE Author_EMAIL='".$mail."'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		header('Location: ../Errors/E5.html');
		die();
	}

	//Check if there is already registerd UID // If TRUE Loop till found free UID..
	$isFreeUID = 0;
	while ($isFreeUID == 0) {
		$sql = "SELECT Author_UID FROM WorldBloggers WHERE Author_UID='".$fullName."'";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			//Someone already registered this UID
			$randomNum = rand(1, 100000);
			$fullName .= $randomNum;
 		} else {
 			//This UID is free
 			$isFreeUID = 1; //Time to end the loop

 			//Insert the new Blogger
 			$sql = "INSERT INTO WorldBloggers (Author_EMAIL, Author_UID, Author_PASS, BAN, TUTO, Nickname) VALUES ('".$mail."', '".$fullName."', '".$pass."', 0, 1, '".$nickname."')";
			$conn->query($sql);
		}
	}

	//Close the connection
	$conn->close();
	
	//Build Author
	mkdir("../Authors/$fullName", 0755, true);
	
	//Create security index
	$buildSecurity = fopen("../Authors/$fullName/index.php", "w") or die("Fatal: Unable to build security.");
	fwrite($buildSecurity, "<?php header('Location: ../../../SignIn.html');?>");
	fclose($buildSecurity);
	
	//Follow button
	$iFollow = fopen("../Authors/$fullName/Following.txt", "w") or die("Unable to build file.");
	fclose($iFollow);
	
	/*
		//Author vision
	$content = logedTemplate("../Templates/Loged.php");
	$loged = fopen("../Authors/$fullName/Loged.php", "w") or die("Unable to open file.");
	fwrite($loged, $content);
	fclose($loged);
	*/
	
	buildConfig("../Authors/$fullName", $picture, $profile, $fullName, $fName, $lName, $pass, $mail);
	
	mkdir("../Authors/$fullName/Messages", 0755, true);
	$stack = fopen("../Authors/$fullName/Messages/Stack.txt", "w") or die("Unable to build stack.");
	fclose($stack);
	$notifications = fopen("../Authors/$fullName/Messages/Notification.txt", "w") or die("Unable to build notifications.");
	fwrite($notifications, "0");
	fclose($notifications);

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		$sql = "CREATE TABLE pushTable$fullName (ID int NOT NULL AUTO_INCREMENT, MEMBER LONGTEXT, MESSAGE LONGTEXT, DATE LONGTEXT, CHECKED LONGTEXT, PRIMARY KEY (ID))";
		$conn->query($sql);
	}
	
	function authorTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file.");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function confFollowTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function followingTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file.");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function logedTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file.");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function settingsTemplate($path) {
		$fd = fopen($path, "r") or die("Unable to open file.");
		$template = fread($fd, filesize($path));
		fclose($fd);
		return $template;
	}
	
	function buildConfig($path, $picture, $profile, $fullName, $fName, $lName, $pass, $mail) {
		$fd = fopen("$path/config.txt", "w") or die("Unable to open file.");
		fwrite($fd, $picture.PHP_EOL);
		fwrite($fd, $profile.PHP_EOL);
		fwrite($fd, $fullName.PHP_EOL);
		fwrite($fd, $fName.PHP_EOL);
		fwrite($fd, $lName.PHP_EOL);
		fwrite($fd, $pass.PHP_EOL);
		fwrite($fd, $mail.PHP_EOL);
		fwrite($fd, "1".PHP_EOL);
		fwrite($fd, "1");
		fclose($fd);

		chmod("$path/config.txt", 0640);
		
		$followersID = fopen("$path/FollowersID.html", "w") or die("Unable to open file.");
		fclose($followersID);
	}

	function escapeAttr($object) {
		$converted = str_replace("'", "`", $object);
		$converted = str_replace("\"", "``", $object);
		$converted = str_replace("<?php", "<?php_tag", $object);
		$converted = strip_tags($converted);

		return $converted;
	}
	
	//Send mail to admin
	$content = "User $fName $lName with e-mail: $mail has just join the community of Blogy. Check his/hers blog from here: http://www.blogy.sitemash.net/Library/Authors/$fullName/Author.php";
	mail('vtm.sunrise@gmail.com', 'New bloger', $content);
	
	header('Location: ../../SignIn.html');
	die();
?>