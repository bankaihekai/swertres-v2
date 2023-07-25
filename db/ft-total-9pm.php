<?php
include("dbhelper.php");
session_start();

$total = 0;

$nine_pm_sql = "SELECT amount FROM `transaction` 
                WHERE `date` = '" . $_SESSION['date'] . "'
                    AND (
                        TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                        AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                    ) ";
$nine_pm_query = mysqli_query(connect(),$nine_pm_sql);

if (mysqli_num_rows($nine_pm_query)>0) {
    while($row = mysqli_fetch_assoc($nine_pm_query)){
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

