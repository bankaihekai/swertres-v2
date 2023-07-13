<?php
date_default_timezone_set('Asia/Manila');
$date_today = date("m/j/Y");
function connect()
{
    global $conn;
    $conn = mysqli_connect('127.0.0.1', 'root', '', 'swertres2') or die("Connection Error");

    return $conn;
}

function disconnect()
{
    global $conn;
    mysqli_close($conn);
}

function adminLogin($admin_email, $admin_pass)
{
    $sql_query = mysqli_query(connect(), "SELECT * FROM `admin` WHERE BINARY `email` = BINARY '$admin_email' AND BINARY `password` = BINARY '$admin_pass' LIMIT 1");

    if (mysqli_num_rows($sql_query) > 0) {
        $row = mysqli_fetch_assoc($sql_query);
        $_SESSION['id'] = $row['admin_id'];
        header("Location: admin-index.php");
        exit();
    } else {
        $message = "Incorrect username or password!";
    }

    $_SESSION['message'] = $message;
    header("Location: adminlogin.php");
    exit();
}

function userLogin($user_email, $user_pass)
{
    $sql_query = mysqli_query(connect(), "SELECT * FROM `user` WHERE BINARY `email` = BINARY '$user_email' AND BINARY `password` = BINARY '$user_pass' LIMIT 1");

    if (mysqli_num_rows($sql_query) > 0) {
        $row = mysqli_fetch_assoc($sql_query);
        $_SESSION['id'] = $row['user_id'];
        header("Location: user-index.php");
        exit();
    } else {
        $message = "Incorrect username or password!";
    }

    $_SESSION['message'] = $message;
    header("Location: login.php");
    exit();
}

function inputSwertres($swertres_number, $straight_amount, $ramble_amount)
{

    $current_date = date("Y-n-j");
    $current_time = date("h:i A");

    $straight_type = "straight";
    $ramble_type = "ramble";

    if ($straight_amount != null && $ramble_amount != null) {

        $sql_straight = "INSERT INTO `transaction`
                        (`swertres_no`,`type`,`amount`,`time`,`date`,`status`)
                        VALUES
                        ('$swertres_number','$straight_type','$straight_amount','$current_time','$current_date','pending')";

        $sql_ramble = "INSERT INTO `transaction`
                        (`swertres_no`,`type`,`amount`,`time`,`date`,`status`)
                        VALUES
                        ('$swertres_number','$ramble_type','$ramble_amount','$current_time','$current_date','pending')";

        $query_straight = mysqli_query(connect(), $sql_straight);
        $query_ramble = mysqli_query(connect(), $sql_ramble);

        if ($query_straight && $query_ramble) {
            $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }
    } else if ($straight_amount == null && $ramble_amount != null) {
        $sql_ramble = "INSERT INTO `transaction`
                        (`swertres_no`,`type`,`amount`,`time`,`date`,`status`)
                        VALUES
                        ('$swertres_number','$ramble_type','$ramble_amount','$current_time','$current_date','pending')";

        $query_ramble = mysqli_query(connect(), $sql_ramble);

        if ($query_ramble) {
            $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }
    } else if ($straight_amount != null && $ramble_amount == null) {
        $sql_straight = "INSERT INTO `transaction`
                        (`swertres_no`,`type`,`amount`,`time`,`date`,`status`)
                        VALUES
                        ('$swertres_number','$straight_type','$straight_amount','$current_time','$current_date','pending')";

        $query_straight = mysqli_query(connect(), $sql_straight);

        if ($query_straight) {
            $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }
    } else {
        $_SESSION['error-message'] = "Must input a straight/ramble amount!";
    }
    header("Location: user-index.php");
    exit();
}
