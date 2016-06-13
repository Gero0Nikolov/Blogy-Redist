<?php
	$getMail = trim($_POST['mail']);
	if (!isset($getMail)) {
		echo "<script>window.location='../../SignIn.html'</script>";
	}

	$isFound = 0;

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get user ID from mail
	$sql = "SELECT Author_EMAIL, Author_UID FROM WorldBloggers WHERE Author_EMAIL='".$getMail."'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$sender = $row["Author_UID"];
			$isFound = 1;
		}
	}

	//Close the connection
	$conn->close();

	//Finish the request
	if ($isFound == 0) {
		echo "<script>window.location='../../Errors/E2.html';</script>";
	}
	else
	if ($isFound == 1) {
		$line_count = 0;
		$getConfig = fopen("../../Authors/$sender/config.txt", "r") or die("Fatal: Unable to get config.");
		while (!feof($getConfig)) {
			$line = trim(fgets($getConfig));
			if ($line != "") {
				if ($line_count == 3) {
					$senderFN = $line;
				}
				else
				if ($line_count == 5) {
					$getPass = $line;
					break;
				}
				$line_count++;
			}
		}
		fclose($getConfig);
		
		$requestTime = date("Y-m-d / H:i:s");
	
		$message = "
			Hello there, $senderFN.
			At $requestTime your profile sends a request for forgotten password.
			This is your password: $getPass
			We recommend you to change it when you log in to your profile.
			Log in from here: http://".$_SERVER[HTTP_HOST]."/SignIn.html
			If you don't send this request log in to your profile and send as a report.
			Thank you and have a nice day, from team of Blogy :)
		";
		
		mail($getMail, "Forgotten password", $message);

		//Navigate to message
		header("Location: ../../Errors/M1.html");
	}
?>