<?php
include("dbhelper.php");
session_start();

$query = mysqli_query(connect(), "DELETE FROM `transaction`");

if ($query) {
    $_SESSION['message'] = "Data Cleared!";
    header("Location: ../transaction.php");
    exit;
} else {
    echo "error";
}
?>
