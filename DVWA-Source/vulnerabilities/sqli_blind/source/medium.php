<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$id = $_POST[ 'id' ];
	$exists = false;

	// FIXED: Validate input is numeric
	if (!is_numeric($id)) {
		$html .= '<pre>Error: User ID must be numeric.</pre>';
		exit;
	}

	// FIXED: Convert to integer
	$id = (int)$id;

	switch ($_DVWA['SQLI_DB']) {
		case MYSQL:
			// FIXED: Use prepared statements
			$query = "SELECT first_name, last_name FROM users WHERE user_id = ?";
			$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
			
			if ($stmt) {
				mysqli_stmt_bind_param($stmt, "i", $id);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
				
				$exists = (mysqli_stmt_num_rows($stmt) > 0);
				mysqli_stmt_close($stmt);
			} else {
				$html .= '<pre>Database error.</pre>';
				exit;
			}
			break;
			
		case SQLITE:
			global $sqlite_db_connection;
			
			// FIXED: Use parameterized queries for SQLite
			$query = "SELECT first_name, last_name FROM users WHERE user_id = ?";
			$stmt = $sqlite_db_connection->prepare($query);
			
			if ($stmt) {
				$stmt->bindValue(1, $id, SQLITE3_INTEGER);
				$result = $stmt->execute();
				$row = $result->fetchArray();
				$exists = ($row !== false);
			} else {
				$html .= '<pre>Database error.</pre>';
				exit;
			}
			break;
	}

	if ($exists) {
		// Feedback for end user
		$html .= '<pre>User ID exists in the database.</pre>';
	} else {
		// Feedback for end user
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}
}

?>
