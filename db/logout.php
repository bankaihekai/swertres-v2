<?php
    
    session_start();
    $_SESSION["id"] = array();
    session_destroy();
    unset($_SESSION["id"]);

    header("Location: ../index.php");
    exit();