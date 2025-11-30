<?php

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
    // FIXED: Only allow specific known-safe value
    if ($_GET['redirect'] === 'info.php') {
        header("Location: info.php");
        exit;
    } else {
        http_response_code(500);
        ?>
        <p>You can only redirect to the info page.</p>
        <?php
        exit;
    }
}

http_response_code(500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>
