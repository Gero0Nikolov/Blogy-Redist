<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	
	$getClubOwner = $_POST["clubOwner"];
	$getClubID = $_POST["clubID"];

	$pathToOwner = explode("_", $getClubOwner)[0];

	if ( !file_exists("../../Authors/$pathToOwner/Club-Logos/") ) {
		mkdir("../../Authors/$pathToOwner/Club-Logos/", 0755, true);
	}

	$target_dir = "../../Authors/$pathToOwner/Club-Logos/";
	$target_file = $target_dir . strtolower(basename($_FILES["fileToUpload"]["name"]));
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$uploadOk = 0;
		}
	}

	//Connect to data base
	include "dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Get club name
	$sql = "SELECT ID, Club_Slug FROM ".$getClubOwner." WHERE ID=".$getClubID;
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getClubName = $row["Club_Slug"];
		}
	}

	//Catch type and build new name
	$catchType = end(explode(".", $target_file));
	$target_file = $target_dir.$getClubName.".".$catchType;

	if ($_FILES["fileToUpload"]["size"] > 10000000 && $_FILES["fileToUpload"]["size"] <= $getSpace) {
		echo "<script>window.location='../../Errors/E8.html'</script>";
		$uploadOk = 0;
	}
	//&& $imageFileType != "mp4" && $imageFileType != "ogg" && $imageFileType != "webm" - Movie file formats
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
	&& $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF") {
		//echo "<script>window.location='../../Errors/E9.html'</script>";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "<script>window.location='../../Errors/E10.html'</script>";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			$real_URL = "http://".$_SERVER[HTTP_HOST]."/Library/";
			$target_file = str_replace("../../", $real_URL, $target_file);

			$sql = "UPDATE ".$getClubOwner." SET Club_Logo='".$target_file."' WHERE ID=".$getClubID;
			$conn->query($sql);

			//Close the connection
			$conn->close();

			echo "<script>window.location='../previewClub.php?".$getClubOwner."=".$getClubID."'</script>";
		} else {
			echo "<script>window.location='../../Errors/E10.html'</script>";
		}
	}
?>