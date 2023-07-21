<?php
include("dbhelper.php");
session_start();

$total = 0;

$all_sql = "SELECT original_amount FROM `transaction`
            WHERE `date` = '" . $_SESSION['date'] . "'
            GROUP BY `number_id`";
$all_query = mysqli_query(connect(),$all_sql);

if (mysqli_num_rows($all_query)>0) {
    while($row = mysqli_fetch_assoc($all_query)){
        $total += $row['original_amount'];
    }

    if($total==0){
        echo "0.00";
    }else{
        echo $total . ".00";
    }
    
} else {
    echo "0.00";
}