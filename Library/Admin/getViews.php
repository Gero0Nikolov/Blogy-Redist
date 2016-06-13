<?php
	$getType = $_POST["type"];

	include "../PHP/Universal/dataBase.php";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		if ($getType == "all") {
			$storeViewDates = array();

			//Load reports
			$sql = "SELECT ID, VIEW_DATE, IP_VIEWER FROM view_log ORDER BY VIEW_DATE DESC";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					array_push($storeViewDates, $row["VIEW_DATE"]);
				}
			}

			$countViews = count($storeViewDates); // Count the views

			$storeViewDates = array_unique($storeViewDates); // Filter the array

			$build = "<h1>All views: $countViews</h1>";
			foreach ($storeViewDates as $date) {
				$tmpDate = explode("-", $date);
				$date = $tmpDate[2]."-".$tmpDate[1]."-".$tmpDate[0];

				$build .= "
					<div id='row-container' class='$date'>
						<button type='button' class='fullwidth' title='Explore views from $date' onclick='requestViewsOnDate(\"#$date\", \"$date\");'>$date</button>
						<div id='$date' class='views-container'>
						</div>
					</div>
				";
			}
			$build .= "</div>";
		} else {
			$storeViews = array();

			$tmpDate = explode("-", $getType);
			$convType = $tmpDate[2]."-".$tmpDate[1]."-".$tmpDate[0];

			//Load reports
			$sql = "SELECT ID, VIEW_DATE, IP_VIEWER FROM view_log WHERE VIEW_DATE='$convType' ORDER BY VIEW_DATE DESC";
			$pick = $conn->query($sql);
			if ($pick->num_rows > 0) {
				while ($row = $pick->fetch_assoc()) {
					array_push($storeViews, $row["IP_VIEWER"]);
				}
			}

			$countViews = count($storeViews);

			$build = "
				<h1>Views for $getType: $countViews</h1>
				<h2>Addresses :</h2>
			";
			foreach ($storeViews as $ip_viewer) {
				$build .= "<h3>$ip_viewer</h3>";
			}
		}
	}

	//Close the connection
	$conn->close();

	//Return response
	echo $build;
?>