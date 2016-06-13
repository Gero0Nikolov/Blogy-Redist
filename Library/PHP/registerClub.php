<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) {
		header("Location: ../../../index.php");
		die();
	}

	$getName = trim($_POST["clubName"]);
	$getColor = trim($_POST["clubColor"]);

	//Connect to the Database
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Create the World Clubs table if not exist
	$sql = "CREATE TABLE worldClubs (ID int NOT NULL AUTO_INCREMENT, Club_Name LONGTEXT, Club_Slug LONGTEXT, Club_Type LONGTEXT, Owner_ID LONGTEXT, First_Club INT, Registration_Date LONGTEXT, PRIMARY KEY (ID))";
	$conn->query($sql);

	//Check if this is first club of the user
	$sql = "SELECT ID FROM ".$sender."_Clubs LIMIT 1";
	$pick = $conn->query($sql);
	if ($pick->num_rows <= 0) { $responce = registerClub($getName, $sender, $getColor, $conn); }
	else {
		//Check if this club already exists in the users Clubs table
		$sql = "SELECT Club_Name, Owner_ID FROM worldClubs WHERE Club_Name='$getName' AND Owner_ID='$sender'";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$responce = "Already exists";
			}
		} else {
			//Make payed registration..
			$responce = registerClub($getName, $sender, $getColor, $conn);
		}
	}

	//Close the connection
	$conn->close();

	//Return resonce
	echo $responce;



	//Register club function
	function registerClub($clubName, $sender, $clubColor, $conn) {
		/*//Upload the logo and get the new path
		$clubLogo_URL = upload_and_get_url($clubLogo, $sender);

		if ( $clubLogo_URL != "Fake image" && $clubLogo_URL != "Image is too big" && $clubLogo_URL != "Only images are allowed" && $clubLogo_URL != "Something went wrong" ) {

			
		} else {
			$responce = $clubLogo_URL;
		}*/

		$getDate = date("Y-m-d");

		$clubSlug = strtolower(str_replace(" ", "_", $clubName));

		//Insert the new club into the World Clubs
		$sql = "INSERT INTO worldClubs (Club_Name, Club_Slug, Club_Type, Owner_ID, First_Club, Registration_Date) VALUES ('".$clubName."', '".$clubSlug."', 'SECRET', '".$sender."', 1, '".$getDate."')";
		$conn->query($sql);

		//Table name
		$table_name = $sender."_Clubs";

		//Create the Clubs table of the user
		$sql = "CREATE TABLE $table_name (
			ID int NOT NULL AUTO_INCREMENT, 
			Club_Name LONGTEXT,
			Club_Slug LONGTEXT, 
			Club_Color LONGTEXT, 
			Club_Logo LONGTEXT, 
			Registration_Date LONGTEXT, 
			Authors INT,
			Comments INT,
			Like_Button INT,
			Visits INT,
			Owner LONGTEXT, 
			Members LONGTEXT,
			Invited LONGTEXT, 
			Administrators LONGTEXT,
			Promoted LONGTEXT,
			Requesters LONGTEXT,
			PRIMARY KEY (ID))";
		$conn->query($sql);
		
		//Insert the club into the user Clubs table
		$sql = "INSERT INTO $table_name (
			Club_Name, 
			Club_Slug, 
			Club_Color, 
			Club_Logo, 
			Registration_Date, 
			Authors, 
			Comments,
			Like_Button,
			Visits,
			Owner, 
			Members,
			Invited, 
			Administrators,
			Promoted,
			Requesters) VALUES (
			'".$clubName."', 
			'".$clubSlug."', 
			'".$clubColor."', 
			NULL, 
			'".$getDate."', 
			0, 
			1,
			1,
			0,
			'".$sender."', 
			'".$sender."',
			NULL, 
			'".$sender."',
			NULL,
			NULL)";
		$conn->query($sql);

		return "READY";
	}

	//Upload Logo and Get the new URL
	function upload_and_get_url($clubLogo, $sender) {
		$target_dir = "http://".$_SERVER[HTTP_HOST]."/Library/Authors/".$sender."/Album/Club_Logos/";

		//Check if folder exists
		if ( !is_dir($target_dir) ) { mkdir($target_dir, 0755, true); }

		$target_file = $target_dir . strtolower(basename($_FILES["clubLogo"]["name"]));
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["clubLogo"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$responce = "Fake image";
			$uploadOk = 0;
		}

		if ($_FILES["clubLogo"]["size"] > 10000000 && $_FILES["clubLogo"]["size"] <= $getSpace) {
			$responce = "Image is too big";
			$uploadOk = 0;
		}
		//&& $imageFileType != "mp4" && $imageFileType != "ogg" && $imageFileType != "webm" - Movie file formats
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
		&& $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF") {
			$responce = "Only pictures allowed";
			$uploadOk = 0;
		}

		// if everything is ok, try to upload file
		if ($uploadOk == 1) {
			if (move_uploaded_file($_FILES["clubLogo"]["tmp_name"], $target_file)) {
				$responce = $target_file;
			} else {
				$responce = "Something went wrong";
			}
		}

		return $responce;
	}
?>