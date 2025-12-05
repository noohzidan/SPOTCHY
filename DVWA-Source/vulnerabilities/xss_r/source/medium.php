<?php

// FIXED: Enable XSS protection
header ("X-XSS-Protection: 1; mode=block");

// Is there any input?
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
	// Get input
	$name = $_GET[ 'name' ];
	
	// FIXED: Proper output encoding instead of input filtering
	$safe_name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	
	// Feedback for end user
	$html .= "<pre>Hello {$safe_name}</pre>";
}

?>
