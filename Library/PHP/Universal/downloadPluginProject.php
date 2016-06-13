<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }

	$getSlug = $_POST["pluginSlug"];

	//Include bundle
	include "functions.php";

	$target_ = "/home/blogycoo/public_html/Library/Authors/$sender/Plugins/$getSlug/";
	$destination_ = "/home/blogycoo/public_html/Library/Authors/$sender/Plugins/$getSlug.zip";

	create_zip($target_, $destination_);
	echo "http://blogy.co/Library/Authors/$sender/Plugins/$getSlug.zip";
?>