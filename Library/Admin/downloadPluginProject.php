<?php
	session_start();
	$admin = $_SESSION["admin"];
	if ( !isset($admin) ) { die(); }

	$path_ = $_POST["pluginPath"];
	$slug_ = $_POST["pluginSlug"];
	$author_ = $_POST["pluginAuthor"];

	//Include bundle
	include "../PHP/Universal/functions.php";

	$target_ = "/home/blogycoo/public_html/Library/Authors/$author_/Plugins/$slug_/";
	$destination_ = "/home/blogycoo/public_html/Library/Authors/$author_/Plugins/". $slug_ .".zip";
	$url_ = str_replace("/home/blogycoo/public_html/", "http://blogy.co/", $destination_);

	create_zip($target_, $destination_);
	echo $url_;
?>