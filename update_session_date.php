<?php
session_start();

if (isset($_POST['date'])) {
    $_SESSION['date'] = $_POST['date']; // Update the session date
    echo $_SESSION['date']; // Send the updated date back as the response
}
?>
