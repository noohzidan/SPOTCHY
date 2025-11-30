<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	// Where are we going to be writing to?
	$target_path  = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/";
	
	// File information
	$uploaded_name = $_FILES[ 'uploaded' ][ 'name' ];
	$uploaded_tmp  = $_FILES[ 'uploaded' ][ 'tmp_name' ];
	$uploaded_size = $_FILES[ 'uploaded' ][ 'size' ];
	$uploaded_error = $_FILES[ 'uploaded' ][ 'error' ];

	// FIXED: Check for upload errors
	if ($uploaded_error !== UPLOAD_ERR_OK) {
		$html .= '<pre>Upload error occurred.</pre>';
		exit;
	}

	// FIXED: Generate safe filename
	$file_extension = strtolower(pathinfo($uploaded_name, PATHINFO_EXTENSION));
	$allowed_extensions = array('jpg', 'jpeg', 'png');
	
	if (!in_array($file_extension, $allowed_extensions)) {
		$html .= '<pre>Only JPG and PNG files are allowed.</pre>';
		exit;
	}

	// FIXED: Create unique filename to prevent overwrites
	$safe_filename = uniqid() . '_' . md5($uploaded_name) . '.' . $file_extension;
	$target_path .= $safe_filename;

	// FIXED: Server-side MIME type validation
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$actual_mime = finfo_file($finfo, $uploaded_tmp);
	finfo_close($finfo);

	$allowed_mime_types = array('image/jpeg', 'image/png', 'image/jpg');
	if (!in_array($actual_mime, $allowed_mime_types)) {
		$html .= '<pre>Invalid file type detected.</pre>';
		exit;
	}

	// FIXED: Verify image content (check magic bytes)
	$image_info = getimagesize($uploaded_tmp);
	if ($image_info === false) {
		$html .= '<pre>File is not a valid image.</pre>';
		exit;
	}

	// FIXED: Size validation
	if ($uploaded_size > 100000) { // 100KB
		$html .= '<pre>File too large. Maximum size: 100KB.</pre>';
		exit;
	}

	// FIXED: Additional security - check image dimensions
	$max_width = 5000;
	$max_height = 5000;
	if ($image_info[0] > $max_width || $image_info[1] > $max_height) {
		$html .= '<pre>Image dimensions too large.</pre>';
		exit;
	}

	// FIXED: Can we move the file to the upload folder?
	if( !move_uploaded_file( $uploaded_tmp, $target_path ) ) {
		$html .= '<pre>Your image was not uploaded.</pre>';
	}
	else {
		// FIXED: Success - don't reveal full path
		$html .= "<pre>File successfully uploaded!</pre>";
	}
}

?>
