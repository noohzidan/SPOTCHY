<?php

// FIXED: Generate random nonce for each request
$nonce = base64_encode(random_bytes(16));
$headerCSP = "Content-Security-Policy: script-src 'self' 'nonce-$nonce';";

header($headerCSP);

// FIXED: Enable XSS protections
header ("X-XSS-Protection: 1; mode=block");

?>
<?php
if (isset ($_POST['include'])) {
    // FIXED: Sanitize user input before including in page
    $sanitized_include = htmlspecialchars($_POST['include'], ENT_QUOTES, 'UTF-8');
    $page[ 'body' ] .= "
        " . $sanitized_include . "
    ";
}

$page[ 'body' ] .= '
<form name="csp" method="POST">
    <p>Whatever you enter here gets dropped directly into the page, see if you can get an alert box to pop up.</p>
    <input size="50" type="text" name="include" value="" id="include" />
    <input type="submit" value="Include" />
</form>
';

// FIXED: If you need to include safe scripts, use the nonce
$page[ 'body' ] .= "<script nonce=\"$nonce\">
    // Safe scripts go here with the dynamic nonce
    console.log('Safe script with nonce');
</script>";
?>
