<?php
include("dbhelper.php");
session_start();

if (isset($_SESSION['date'])) {
    // $date_today2 = date("Y-m-j"); // for filter query
    $two_pm_sql = "SELECT *
                    FROM `transaction`
                    WHERE `date` = '" . $_SESSION['date'] . "'
                        AND (
                            TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                            AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                        )";

    $two_pm_query = mysqli_query(connect(), $two_pm_sql);

    if (mysqli_num_rows($two_pm_query) > 0) {
        while ($row = mysqli_fetch_assoc($two_pm_query)) {
            $swertres_no = $row['swertres_no'];
            $amount = $row['amount'];
            ?>
            <tr>
                <td class='text-center'>
                    <?php echo $swertres_no; ?>
                </td>
                <td class='text-center'>
                    <?php echo $amount; ?>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan='2'>
                <h6 class='alert alert-danger text-center'>No Transaction Found!</h6>
            </td>
        </tr>
        <?php
    }
}
?>
