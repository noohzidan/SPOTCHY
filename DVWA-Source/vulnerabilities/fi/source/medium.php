<?php

// The page we wish to display
$file = $_GET[ 'page' ];

// FIXED: Enhanced path traversal protection
function sanitizePath($input) {
    // Remove all occurrences of directory traversal sequences
    $patterns = array(
        '/\.\.\//',      // ../ 
        '/\.\.\\\/',     // ..\
        '/\/\//',        // //
        '/\\\/\\\/',     // \\
    );
    
    do {
        $original = $input;
        $input = preg_replace($patterns, '', $input);
    } while ($original !== $input); // Keep cleaning until no changes
    
    return $input;
}

$file = sanitizePath($file);

// FIXED: Remove any URL protocols
$file = preg_replace('/^https?:\/\//', '', $file);

// FIXED: Set base directory and restrict access
$base_dir = '/var/www/html/includes/';
$full_path = $base_dir . $file;

// FIXED: Use realpath to resolve any remaining path issues
$real_path = realpath($full_path);

// FIXED: Verify the file is within the base directory
if ($real_path === false || strpos($real_path, realpath($base_dir)) !== 0) {
    die('Invalid file path detected.');
}

// FIXED: Check file extension if needed
$allowed_extensions = array('php', 'html', 'htm', 'txt');
$file_extension = pathinfo($real_path, PATHINFO_EXTENSION);
if (!in_array(strtolower($file_extension), $allowed_extensions)) {
    die('Invalid file type.');
}

// Include the safe file
include($real_path);

?>
