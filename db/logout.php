<?php
    
    session_start();
    $_SESSION["id"] = array();
    session_destroy();
    unset($_SESSION["id"]);

    $page = $_GET['page'];

    if($page == 'admin'){
        header("Location: ../adminlogin.php");
    }else{
        header("Location: ../login.php");
    }
    exit();