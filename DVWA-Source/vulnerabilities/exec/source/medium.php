<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$target = $_REQUEST[ 'ip' ];

	// FIXED: Enhanced blacklist with more command separators
	$substitutions = array(
		'&&' => '',
		';'  => '',
		'|'  => '',
		'||' => '',
		'&'  => '',
		'`'  => '',
		'$'  => '',
		'('  => '',
		')'  => '',
		'>'  => '',
		'<'  => '',
		"\n" => '',
		"\r" => '',
	);

	// Remove any of the characters in the array (blacklist).
	$target = str_replace( array_keys( $substitutions ), $substitutions, $target );

	// FIXED: Additional validation - only allow IP format characters
	if (!preg_match('/^[0-9.\s]+$/', $target)) {
		$html .= "<pre>Error: Invalid characters in IP address.</pre>";
		return;
	}

	// FIXED: Use escapeshellarg for extra safety
	$target_escaped = escapeshellarg($target);

	// Determine OS and execute the ping command.
	if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
		// Windows
		$cmd = shell_exec( 'ping ' . $target_escaped );
	}
	else {
		// *nix
		$cmd = shell_exec( 'ping -c 4 ' . $target_escaped );
	}

	// FIXED: Sanitize output
	$cmd_safe = htmlspecialchars($cmd, ENT_QUOTES, 'UTF-8');
	$html .= "<pre>{$cmd_safe}</pre>";
}

?>
