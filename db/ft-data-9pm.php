<?php
include("dbhelper.php");
session_start();

if (isset($_SESSION['date'])) {
    // $date_today2 = date("Y-m-j"); // for filter query
    $nine_pm_sql = "SELECT * FROM `transaction`
                    WHERE `date` = '" . $_SESSION['date'] . "'
                        AND (
                            TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                            AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                        )
                    ORDER BY `amount` DESC";

    $nine_pm_query = mysqli_query(connect(), $nine_pm_sql);

    if (mysqli_num_rows($nine_pm_query) > 0) {
        while ($row = mysqli_fetch_assoc($nine_pm_query)) {
            $swertres_no = $row['swertres_no'];
            $old_amount = number_format($row['amount']);
            $amount = floatval(str_replace(',', '', $row['amount'])); 
            $type = $row['type'];

            $total = $amount - $_SESSION['deduction'];

            echo "
                <tr>
                    <td class='text-center'>
                        " . $swertres_no . "
                    </td>
                    <td class='text-center'>";
            if ($total < 0) {
                echo "<b class='text-danger'>" . number_format($total, 1) . "</b>";
            } else {
                echo "<b class='text-success'>" . number_format($total, 1) . "</b>";
            }
            echo "
                    </td>
                    <td class='text-center'>
                        " . $old_amount . "
                    </td>
                </tr>";
        }
    } else {
?>
        <tr>
            <td colspan='3'>
                <h6 class='alert alert-danger text-center'>No Transaction Found!</h6>
            </td>
        </tr>
<?php
    }
}
?>
