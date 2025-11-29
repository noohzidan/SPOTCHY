<?php
/*

Only users with admin role are allowed to access this page.

Have a look at this file for possible vulnerabilities: 

* vulnerabilities/authbypass/change_user_details.php

*/

// Fixed: Check user role instead of username
if (!dvwaIsUserAdmin()) {
    print "Unauthorised";
    http_response_code(403);
    exit;
}
?>
