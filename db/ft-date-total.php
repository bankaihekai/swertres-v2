<?php
include("dbhelper.php");
session_start();

$all_sql = "SELECT sum(amount) as total FROM `transaction`
                WHERE `date` = '" . $_SESSION['date'] . "'";
$all_query = mysqli_query(connect(),$all_sql);

if (mysqli_num_rows($all_query)>0) {
    $data = mysqli_fetch_assoc($all_query);
    $total = $data['total'];
    if($total==0){
        echo "0.00";
    }else{
        echo $total . ".00";
    }
    
} else {
    echo "Error!";
}
