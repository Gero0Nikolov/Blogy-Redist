<?php
	include "../PHP/Universal/dataBase.php"; 
	
	$buildedReports = "";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//Load reports
		$sql = "SELECT ID, SUBJECT, REPORT FROM worldReports ORDER BY ID DESC";
		$pick = $conn->query($sql);
		if ($pick->num_rows > 0) {
			while ($row = $pick->fetch_assoc()) {
				$reportIdNum = $row['ID'];
				$subject = $row['SUBJECT'];
				$report = nl2br($row['REPORT']);

				$build = "
					<div id='row-container' class='$reportIdNum'>
						<button type='button' class='placeholder' onclick='previewReport(\"$reportIdNum\");' title='Preview report'>
							$subject
						</button>
						<div id='controls-container'>
							<button type='button' class='option-button' onclick='removeReport(\"$reportIdNum\");' title='Check as done'>
								Check
							</button>
						</div>
						<div id='report-container'>
							<p>
								$report
							</p>
						</div>
					</div>
				";
				$buildedReports .= $build;
			}
		}
	}

	//Close connection
	$conn->close();

	echo $buildedReports;
?>