<?php
include("dbhelper.php");
session_start();

$query = mysqli_query(connect(), "DELETE FROM `transaction`");

if ($query) {
    $_SESSION['message'] = "All Data Deleted Successfully!";
    header("Location: ../transaction.php");
    exit;
} else {
    echo "error";
}
?>
