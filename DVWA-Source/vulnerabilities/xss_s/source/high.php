<?php

if( isset( $_POST[ 'btnSign' ] ) ) {
	// Get input
	$message = trim( $_POST[ 'mtxMessage' ] );
	$name    = trim( $_POST[ 'txtName' ] );

	// FIXED: Input validation
	if (empty($message) || empty($name)) {
		die("Name and message are required.");
	}

	// FIXED: Length validation
	if (strlen($name) > 50) {
		die("Name too long. Maximum 50 characters.");
	}
	if (strlen($message) > 500) {
		die("Message too long. Maximum 500 characters.");
	}

	// FIXED: Remove ALL HTML tags for storage (clean input)
	$name = strip_tags($name);
	$message = strip_tags($message);

	// FIXED: Use prepared statements for SQL safety
	$query = "INSERT INTO guestbook ( comment, name ) VALUES ( ?, ? )";
	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
	
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, "ss", $message, $name);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
		// Success message
		$html .= "<pre>Thank you for your message!</pre>";
	} else {
		die("Database error.");
	}
}

?>
