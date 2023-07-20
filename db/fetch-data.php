<?php
include("dbhelper.php");
session_start();

function generateTableRows($combinations, $amount, $r_original_amount) {
    foreach ($combinations as $combination) {
        echo "
        <tr>
            <td class='text-center'>
                ".$combination."
            </td>
            <td class='text-center'>
                ".$amount."
            </td>
            <td class='text-center'>
                ".$r_original_amount."
            </td>
        </tr>";
    }
}

if (isset($_SESSION['date'])) {
    $time_ranges = array(
        '2pm' => array('start' => '09:00:00 PM', 'end' => '02:00:00 PM'),
        '5pm' => array('start' => '02:00:00 PM', 'end' => '05:00:00 PM'),
        '9pm' => array('start' => '05:00:00 PM', 'end' => '09:00:00 PM')
    );

    foreach ($time_ranges as $time => $time_range) {
        $two_pm_sql = "SELECT *
                        FROM `transaction`
                        WHERE `date` = '" . $_SESSION['date'] . "'
                            AND (
                                TIME_FORMAT(`time`, '%h:%i:%s %p') >= '" . $time_range['start'] . "'
                                AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '" . $time_range['end'] . "'
                            )";

        $two_pm_query = mysqli_query(connect(), $two_pm_sql);

        if (mysqli_num_rows($two_pm_query) > 0) {
            while ($row = mysqli_fetch_assoc($two_pm_query)) {
                $swertres_no = $row['swertres_no'];
                $amount = $row['amount'];
                $type = $row['type'];

                if (strtolower($type) == 'straight') {
                    echo "
                    <tr>
                        <td class='text-center'>
                            " . $swertres_no . "
                        </td>
                        <td class='text-center'>
                            " . $amount . "
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
                        generateTableRows($combinations, $new_amount, $amount);
                    } else {
                        $combinations = r_2digit_data($swertres_no);
                        $new_amount = number_format($amount / 6, 2);
                        generateTableRows($combinations, $new_amount, $amount);
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
}
?>