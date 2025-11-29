<?php
/*

Only users with admin role are allowed to access this page.

Have a look at these two files for possible vulnerabilities: 

* vulnerabilities/authbypass/get_user_data.php
* vulnerabilities/authbypass/change_user_details.php

*/

// Fixed: Check user role instead of username
if (!dvwaIsUserAdmin()) {
    print "Unauthorised";
    http_response_code(403);
    exit;
}
?>
