<?php
if ($_GET['randomId'] != "U5Ki0dpvcM8Xle4FfEH7aypHuOQ5T4TQnPsbGWBEHhYH92jClzhG_7aQ4NoSnTNV") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
