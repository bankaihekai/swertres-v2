<?php
include("dbhelper.php");
session_start();

$total = 0;

$all_sql = "SELECT amount FROM `transaction`
            WHERE `date` = '" . $_SESSION['date'] . "'
            ";
$all_query = mysqli_query(connect(),$all_sql);

if (mysqli_num_rows($all_query)>0) {
    while($row = mysqli_fetch_assoc($all_query)){
        $total += $row['amount'];
    }

    if($total==0){
        echo "0.00";
    }else{
        echo round($total) . "";
    }
    
} else {
    echo "0.00";
}