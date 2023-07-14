<?php
include("dbhelper.php");
session_start();

$two_pm_sql = "SELECT sum(amount) as total FROM `transaction`
                WHERE `date` = '" . $_SESSION['date'] . "'
                    AND (
                        TIME_FORMAT(`time`, '%h:%i:%s %p') >= '09:00:00 PM'
                        OR TIME_FORMAT(`time`, '%h:%i:%s %p') < '02:00:00 PM'
                    )";
$two_pm_query = mysqli_query(connect(),$two_pm_sql);

if ($two_pm_query) {
    $data = mysqli_fetch_assoc($two_pm_query);
    $total = $data['total'];
    echo $total . ".00";
} else {
    echo "Error!";
}
