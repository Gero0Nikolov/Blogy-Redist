<?php
	$getClubOwner = $_POST["clubOwner"];
	$getClubID = $_POST["clubID"];
	
	$getClubName = htmlentities(trim($_POST["clubName"]));
	$getClubName = str_replace("'", "`", $getClubName);

	$getClubType = htmlentities(trim($_POST["clubType"]));
	$getClubType = str_replace("'", "`", $getClubType);

	$getClubAuthors = htmlentities(trim($_POST["clubAuthors"]));
	$getClubAuthors = str_replace("'", "`", $getClubAuthors);

	$getClubComments = htmlentities(trim($_POST["clubComments"]));
	$getClubComments = str_replace("'", "`", $getClubComments);

	$getClubLikeButton = htmlentities(trim($_POST["clubLikeButton"]));
	$getClubLikeButton = str_replace("'", "`", $getClubLikeButton);

	$getClubSlug = $_POST["clubSlug"];

	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Update users club table
	$sql = "UPDATE ".$getClubOwner." SET Club_Name='".$getClubName."' WHERE ID=".$getClubID;
	$conn->query($sql);

	$sql = "UPDATE ".$getClubOwner." SET Authors='".$getClubAuthors."' WHERE ID=".$getClubID;
	$conn->query($sql);

	$sql = "UPDATE ".$getClubOwner." SET Comments='".$getClubComments."' WHERE ID=".$getClubID;
	$conn->query($sql);

	$sql = "UPDATE ".$getClubOwner." SET Like_Button='".$getClubLikeButton."' WHERE ID=".$getClubID;
	$conn->query($sql);

	$getClubOwner = explode("_", $getClubOwner)[0];

	//Update World Clubs table
	$sql = "UPDATE worldClubs SET Club_Name='".$getClubName."' WHERE Club_Slug='".$getClubSlug."' AND Owner_ID='".$getClubOwner."'";
	$conn->query($sql);

	$sql = "UPDATE worldClubs SET Club_Type='".$getClubType."' WHERE Club_Slug='".$getClubSlug."' AND Owner_ID='".$getClubOwner."'";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	//Resend back
	echo "<script>window.history.back();</script>";
?>