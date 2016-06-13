<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	//Include bundle
	include "functions.php";

	$getTarget = str_replace( "../../", "/home/blogycoo/public_html/Library/", $_POST["targetToMove"] );
	$getTargetFile = end( explode("/", $getTarget) );
	$getDestination = str_replace( "../../", "/home/blogycoo/public_html/Library/", $_POST["targetDestination"] );
	$getTargetType = $_POST["targetType"];

	if ( empty($getTarget) || empty($getDestination) || empty($getTargetType) ) {
		$response = "FAILED";
	} else {
		
		if ( $getTargetType == "file" ) { moveFileTo($getTarget, $getDestination."/".$getTargetFile); }
		else
		if ( $getTargetType == "folder" ) { moveFolderTo($getTarget, $getDestination); }
		
		$response = "READY";
	}

	//Return response
	echo $response;
?>