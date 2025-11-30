<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // FIXED: Generate cryptographically secure session ID
    $cookie_value = bin2hex(random_bytes(32));
    
    // FIXED: Set secure cookie with proper flags
    setcookie(
        "dvwaSession", 
        $cookie_value,
        [
            'expires' => time() + 3600, // 1 hour expiration
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true, // HTTPS only
            'httponly' => true, // Not accessible via JavaScript
            'samesite' => 'Strict' // CSRF protection
        ]
    );
    
    // FIXED: Regenerate session ID if using PHP sessions
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

?>
