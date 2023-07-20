<?php
include("dbhelper.php");
session_start();

if (isset($_SESSION['date'])) {
    // $date_today2 = date("Y-m-j"); // for filter query
    $two_pm_sql = "SELECT *
                    FROM `transaction`
                    WHERE `date` = '" . $_SESSION['date'] . "'
                        AND (
                            TIME_FORMAT(`time`, '%h:%i:%s %p') >= '09:00:00 PM'
                            OR TIME_FORMAT(`time`, '%h:%i:%s %p') < '02:00:00 PM'
                        )
                    ORDER BY `amount` DESC";

    $two_pm_query = mysqli_query(connect(), $two_pm_sql);

    if (mysqli_num_rows($two_pm_query) > 0) {
        while ($row = mysqli_fetch_assoc($two_pm_query)) {
            $swertres_no = $row['swertres_no'];
            $amount = $row['amount'];
            $type = $row['type'];
            $r_original_amount = number_format($amount / 3, 2);

            if (strtolower($type) == 'straight') {

                $total = $amount - $_SESSION['deduction'];

                echo "
                <tr>
                    <td class='text-center'>
                        " . $swertres_no . "
                    </td>
                    <td class='text-center'>";
                if ($total < 0) {
                    echo "<b class='text-danger'>" . $total . "</b>";
                } else {
                    echo "<b class='text-success'>" . $total . "</b>";
                }
                echo "
                    </td>
                    <td class='text-center'>
                        " . $amount . "
                    </td>
                </tr>";
            } else {
                if (r_2digit_same($swertres_no)) {

                    $combinations = array_unique(r_2digit_data($swertres_no));
                    $count = count($combinations);
                    $new_amount = number_format($amount / 3, 2);
                    $total = $new_amount - $_SESSION['deduction'];

                    foreach ($combinations as $combination) {
                        echo "
                        <tr>
                            <td class='text-center'>
                                " . $combination . "
                            </td>
                            <td class='text-center'>";
                        if ($total < 0) {
                            echo "<b class='text-danger'>" . $total . "</b>";
                        } else {
                            echo "<b class='text-success'>" . $total . "</b>";
                        }
                        echo "
                            </td>
                            <td class='text-center'>
                                " . $r_original_amount . "
                            </td>
                        </tr>";
                    }
                } else {
                    $combinations = r_2digit_data($swertres_no);
                    $new_amount = number_format($amount / 6, 2);
                    $total = $new_amount - $_SESSION['deduction'];

                    foreach ($combinations as $combination) {
                        echo "
                        <tr>
                            <td class='text-center'>
                                " . $combination . "
                            </td>
                            <td class='text-center'>";
                        if ($total < 0) {
                            echo "<b class='text-danger'>" . $total . "</b>";
                        } else {
                            echo "<b class='text-success'>" . $total . "</b>";
                        }
                        echo "
                            </td>
                            <td class='text-center'>
                                " . $new_amount . "
                            </td>
                        </tr>";
                    }
                }
            }
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