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

	$sql = "SELECT Plugin_Name, Plugin_Author, Haters FROM Plugin_Store WHERE ID=$plugin_id";
	$pick = $conn->query($sql);
	if ($pick->num_rows > 0) {
		while ($row = $pick->fetch_assoc()) {
			$plugin_name = $row["Plugin_Name"];
			$plugin_author = $row["Plugin_Author"];
			if ( !empty($row["Haters"]) ) { $plugin_haters = explode(",", $row["Haters"]); }
		}
	}

	//Check if there are haters and if the user is one of them
	$flag = 0;
	if ( is_array($plugin_haters) ) {
		if ( in_array($sender, $plugin_haters) ) { // Already hate it
			$revise_haters = array();
			foreach ($plugin_haters as $hater) {
				if ( $hater != $sender ) { array_push($revise_haters, $hater); }
			}

			$plugin_haters = implode(",", $revise_haters);
			$flag = -1;
		} elseif ( !in_array($sender, $plugin_haters) ) { // Add hate
			array_push($plugin_haters, $sender);
			$plugin_haters = implode(",", $plugin_haters);
			$flag = 1;
		}
	} elseif ( !is_array($plugin_haters) ) {
		$plugin_haters = $sender;
		$flag = 1;
	}

	//Update hates
	$sql = "UPDATE Plugin_Store SET Haters='$plugin_haters' WHERE ID=$plugin_id";
	$conn->query($sql);

	//Close the connection
	$conn->close();

	if ( $sender != $plugin_author ) {
		if ( $flag == -1 ) { 
			$notification_message = "unhated your $plugin_name plugin";
			$mail_subject = "Unhated your Blogy plugin";
			$mail_message = "Hello there.<br><a href='http://blogy.co?$sender'>@$senderFN$senderLN</a> unhated your $plugin_name plugin.";
		}
		elseif ( $flag == 1 ) { 
			$notification_message = "hated your $plugin_name plugin";
			$mail_subject = "Hated your Blogy plugin";
			$mail_message = "Hello there.<br><a href='http://blogy.co?$sender'>@$senderFN$senderLN</a> hated your $plugin_name plugin.";
		}
	
		send_notification($sender, $plugin_author, $notification_message);
		send_custom_mail($sender, $plugin_author, "plugin_notification", $mail_subject, $mail_message);
	}

	//Return response
	echo "READY";
?>