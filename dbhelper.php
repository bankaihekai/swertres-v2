<?php

date_default_timezone_set('Asia/Manila');
$date_today = date("F j, Y");
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

// function getalluser(){
//     global $conn, $table;
//     connect();
//     $query = mysqli_query($conn, "SELECT * FROM `$table`");
//     $getrecord = mysqli_fetch_all($query);
//     disconnect();
//     return $getrecord;
// }

// function getrecord($username, $password){
//     global $conn, $table;
//     $sql = "SELECT * FROM `$table` WHERE `uname`='$username' and `pword`='$password'";
//     connect();
//     $query = mysqli_query($conn, $sql);
//     $rows = mysqli_fetch_all($query);
//     disconnect();
//     if(count($rows) > 0)
//         return $rows[0];
//     else
//         return false;
// }
