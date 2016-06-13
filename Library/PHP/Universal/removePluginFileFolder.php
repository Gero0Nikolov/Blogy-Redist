<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	//Include bundle
	include "functions.php";

	$getBreadCrumbs = $_POST["breadcrumbs"];
	$getType = $_POST["type"];

	if ( $getType == "file" ) {
		remove_file("../../Authors/$sender/Plugins/".$getBreadCrumbs);
	} elseif ( $getType == "folder" ) {
		remove_dir("../../Authors/$sender/Plugins/".$getBreadCrumbs);
	}

	echo "READY";
?>