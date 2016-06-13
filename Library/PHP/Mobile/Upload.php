<?php
	session_start();
	$sender = $_SESSION['sender'];
	if (!isset($sender)) {
		header('Location: ../../../SignIn.html');
	}
	
	$target_dir = "../../Authors/$sender/Album/";
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
	include "../Universal/dataBase.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get last ID
	$sql = "SELECT ID FROM albumOf$sender ORDER BY ID DESC LIMIT 1";
	$conn->query($sql);
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getLastIdByOne = (int)$row["ID"] + 1; // And add 1
		}
	}

	//Catch type
	$catchType = end(explode(".", $target_file));

	//Build new Target name
	$target_name = "IMG-".$getLastIdByOne.".".$catchType;
	$target_file = $target_dir.$target_name;

	// Check file size
	$sql = "SELECT ALBUM, SPACE FROM albumOf$sender WHERE ALBUM='SPACE'";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$getSpace = (int)$row['SPACE'];
		}
	}
	
	if ($_FILES["fileToUpload"]["size"] > 10000000 && $_FILES["fileToUpload"]["size"] <= $getSpace) {
		echo "<script>window.location='../../Errors/E8.html'</script>";
		$uploadOk = 0;
	}
	//&& $imageFileType != "mp4" && $imageFileType != "ogg" && $imageFileType != "webm" - Movie file formats
	// Allow certain file formats
	$imageFileType = strtolower($imageFileType);

	if( $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "mp4" && $imageFileType != "webm" && $imageFileType != "mov" && $imageFileType != "ogg" ) {
		echo "<script>window.location='../../Errors/E9.html'</script>";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "<script>window.location='../../Errors/E10.html'</script>";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			$catchType = end(explode(".", basename($_FILES["fileToUpload"]["name"])));

			$fileName = $target_name;
			
			$sql = "INSERT INTO albumOf$sender (ALBUM, SPACE) VALUES ('$fileName', '0')";
			$conn->query($sql);
			
			$freeSpace = $getSpace - filesize($target_dir.$fileName);
			
			$sql = "UPDATE albumOf$sender SET SPACE='$freeSpace' WHERE ALBUM='SPACE'";
			$conn->query($sql);

			//Check if the upload is club logo
			if ( isset($_POST["isClub"]) ) {
				$sql = "UPDATE ".$_POST["clubOwner"]." SET Club_Logo='".$target_file."' WHERE ID=".$_POST["clubID"];
				$conn->query($sql);
			}
	
			$conn->close();
			
			if ( !isset($_POST["isClub"]) ) { echo "<script>window.location='loadAlbum.php'</script>"; }
			else { echo "<script>window.location='previewClub.php?".$_POST["clubOwner"]."=".$_POST["clubID"]."'</script>"; }
		} else {
			echo "<script>window.location='../../Errors/E10.html'</script>";
		}
	}
?>