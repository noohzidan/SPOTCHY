<?php

// Is there any input?
if ( array_key_exists( "default", $_GET ) && !is_null ($_GET[ 'default' ]) ) {
	$default = $_GET['default'];
	
	// FIXED: Use whitelist of allowed values
	$allowed_values = array('English', 'French', 'Spanish', 'German');
	
	if (!in_array($default, $allowed_values)) {
		header ("location: ?default=English");
		exit;
	}
}

?>
