<?php

// Brute force protection - track failed attempts
function checkBruteForce($user) {
    $now = time();
    $window = 15 * 60; // 15 minute window
    
    // Count failed attempts in last 15 minutes
    $query = "SELECT COUNT(*) as attempts FROM login_attempts 
              WHERE username = ? AND attempt_time > ?";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    $valid_attempts = $now - $window;
    mysqli_stmt_bind_param($stmt, "si", $user, $valid_attempts);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    return ($row['attempts'] >= 5); // Lock after 5 failed attempts
}

function logFailedAttempt($user) {
    $query = "INSERT INTO login_attempts (username, attempt_time) VALUES (?, ?)";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    $now = time();
    mysqli_stmt_bind_param($stmt, "si", $user, $now);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if( isset( $_GET[ 'Login' ] ) ) {
    // Get username
    $user = $_GET[ 'username' ];

    // Get password
    $pass = $_GET[ 'password' ];
    $pass = md5( $pass );

    // FIXED: Check for brute force attack
    if (checkBruteForce($user)) {
        $html .= "<pre><br />Too many failed login attempts. Please try again in 15 minutes.</pre>";
    } else {
        // Check the database
        $query  = "SELECT * FROM `users` WHERE user = '$user' AND password = '$pass';";
        $result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

        if( $result && mysqli_num_rows( $result ) == 1 ) {
            // Get users details
            $row    = mysqli_fetch_assoc( $result );
            $avatar = $row["avatar"];

            // Login successful - clear failed attempts
            $clear_query = "DELETE FROM login_attempts WHERE username = '$user'";
            mysqli_query($GLOBALS["___mysqli_ston"], $clear_query);

            $html .= "<p>Welcome to the password protected area {$user}</p>";
            $html .= "<img src=\"{$avatar}\" />";
        }
        else {
            // Login failed - log attempt
            logFailedAttempt($user);
            
            // Add delay to slow down brute force
            sleep(2);
            
            $html .= "<pre><br />Username and/or password incorrect.</pre>";
        }

        ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
    }
}

?>
