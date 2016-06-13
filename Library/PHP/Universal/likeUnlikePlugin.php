<?php
	session_start();
	$sender = $_SESSION["sender"];
	if ( !isset($sender) ) { die(); }
	$senderFN = $_SESSION['senderFN'];
	$senderLN = $_SESSION['senderLN'];

	$plugin_id = $_POST["pluginID"];

	//Include bundle
	include "functions.php";

	//Connect to the Database
	include "dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT Plugin_Name, Plugin_Author, Likers FROM Plugin_Store WHERE ID=$plugin_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_name = $row["Plugin_Name"];
			$plugin_author = $row["Plugin_Author"];
			if ( !empty($row["Likers"]) ) { $plugin_likers = explode(",", $row["Likers"]); }
		}
	}

	//Check if there are likers and if the user is one of them
	$flag = 0;
	if ( is_array($plugin_likers) ) {
		if ( in_array($sender, $plugin_likers) ) { // Already liked it
			$revise_likers = array();
			foreach ($plugin_likers as $liker) {
				if ( $liker != $sender ) { array_push($revise_likers, $liker); }
			}

			$plugin_likers = implode(",", $revise_likers);
			$flag = -1;
		} elseif ( !in_array($sender, $plugin_likers) ) { // Add like
			array_push($plugin_likers, $sender);
			$plugin_likers = implode(",", $plugin_likers);
			$flag = 1;
		}
	} elseif ( !is_array($plugin_likers) ) {
		$plugin_likers = $sender;
		$flag = 1;
	}

	//Update likes
	$sql = "UPDATE Plugin_Store SET Likers='$plugin_likers' WHERE ID=$plugin_id";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	if ( $sender != $plugin_author ) {
		if ( $flag == -1 ) { 
			$notification_message = "unliked your $plugin_name plugin";
			$mail_subject = "Unliked your Blogy plugin";
			$mail_message = "Hello there.<br><a href='http://blogy.co?$sender'>@$senderFN$senderLN</a> unliked your $plugin_name plugin.";
		}
		elseif ( $flag == 1 ) { 
			$notification_message = "liked your $plugin_name plugin";
			$mail_subject = "Liked your Blogy plugin";
			$mail_message = "Hello there.<br><a href='http://blogy.co?$sender'>@$senderFN$senderLN</a> liked your $plugin_name plugin.";
		}
	
		send_notification($sender, $plugin_author, $notification_message);
		send_custom_mail($sender, $plugin_author, "plugin_notification", $mail_subject, $mail_message);
	}

	//Return response
	echo "READY";
?>