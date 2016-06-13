<?php
	include "dataBase.php";

	//Connect to the database
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//Create new table if not exists
	$sql = "CREATE TABLE view_log (ID int NOT NULL AUTO_INCREMENT, VIEW_DATE LONGTEXT, IP_VIEWER LONGTEXT, PRIMARY KEY (ID))";
	$conn->query($sql);

	$getDate = date("Y-m-d");
	$getIp = get_client_ip();

	$sql = "INSERT INTO view_log (VIEW_DATE, IP_VIEWER) VALUES ('$getDate', '$getIp')";
	$conn->query($sql);

	//Close connection
	$conn->close();

	// Check if there is already loged in user
	session_start();
	if ( isset($_SESSION["sender"]) && !empty($_SESSION["sender"]) ) { 
		require_once '../Detect/Mobile_Detect.php';
		$detect = new Mobile_Detect;
		if($detect->isMobile() || $detect->isTablet()) {
			echo "LOGIN-MOBILE";
		} else {
			echo "LOGIN-DESKTOP";
		}
	} else { echo "DONT-LOG"; }

	//Functions: Get client IP address
	function get_client_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}
?>