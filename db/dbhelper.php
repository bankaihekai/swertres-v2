<?php
date_default_timezone_set('Asia/Manila');
// date for UI
$date_today2 = date("Y-m-j"); // for date picker format
$date_today = date("F j, Y");

// date for query
$current_time = date("h:i A"); // 03:16 PM format
$current_date = date("Y-n-j");
if (strtotime($current_time) >= strtotime("21:00:00")) {
    $current_date = date("Y-n-j", strtotime("+1 day"));
}

function connect()
{
    global $conn;
    $conn = mysqli_connect('127.0.0.1', 'root', '', 'swertres2') or die("Connection Error");

    return $conn;
}

function disconnect()
{
    global $conn;
    mysqli_close($conn);
}

function userLogin($user_email, $user_pass)
{
    $sql_query = mysqli_query(connect(), "SELECT * FROM `user` WHERE BINARY `email` = BINARY '$user_email' AND BINARY `password` = BINARY '$user_pass' LIMIT 1");

    if (mysqli_num_rows($sql_query) > 0) {
        $row = mysqli_fetch_assoc($sql_query);
        $_SESSION['id'] = $row['user_id'];
        header("Location: user-index.php");
        exit();
    } else {
        $message = "Incorrect username or password!";
    }

    $_SESSION['message'] = $message;
    header("Location: index.php");
    exit();
}

function r_2digit_same($number)
{
    $digit1 = ($number / 100) % 10;
    $digit2 = ($number / 10) % 10;
    $digit3 = $number % 10;

    return ($digit1 === $digit2 || $digit1 === $digit3 || $digit2 === $digit3);
}

function r_2digit_data($number)
{
    if (strlen($number) === 1) {
        return [$number];
    }

    $permutations = [];
    $length = strlen($number);

    for ($i = 0; $i < $length; $i++) {
        $char = $number[$i];
        $remaining = substr($number, 0, $i) . substr($number, $i + 1);
        $subPermutations = r_2digit_data($remaining);

        foreach ($subPermutations as $subPermutation) {
            $permutations[] = $char . $subPermutation;
        }
    }

    return $permutations;
}

function ramble_all_combinations($number)
{
    $length = strlen($number);
    $combinations = [];

    for ($i = 0; $i < $length; $i++) {
        $firstDigit = $number[$i];
        $remainingDigits = substr($number, 0, $i) . substr($number, $i + 1);

        if (strlen($remainingDigits) > 1) {
            $subCombinations = ramble_all_combinations($remainingDigits);

            foreach ($subCombinations as $subCombination) {
                $combinations[] = $firstDigit . $subCombination;
            }
        } else {
            $combinations[] = $firstDigit . $remainingDigits;
        }
    }

    return $combinations;
}

function deduction()
{
    $deduct_query = mysqli_query(connect(), "SELECT * FROM `deduction` LIMIT 1");

    if (mysqli_num_rows($deduct_query) > 0) {
        $row = mysqli_fetch_assoc($deduct_query);
        $_SESSION['deduction'] = $row['amount'];
    }
}


function inputSwertres($swertres_number, $straight_amount, $ramble_amount)
{
    $final_combination = array(); // Initialize $final_combination as an empty array
    // if ramble amount have values--------------------------------
    if ($ramble_amount != null) {

        // 2 digit same number
        if (r_2digit_same($swertres_number)) {
            $combinations = array_unique(r_2digit_data($swertres_number));
            $new_amount = round($ramble_amount / 3, 1);
        }
        // 3 digit different number
        else {
            $combinations = r_2digit_data($swertres_number);
            $new_amount = round($ramble_amount / 6, 1);
        }

        foreach ($combinations as $number) {
            $new_combinations[] = $number; // get all random numbers
        }
        $leftover_combination = array();

        $number_id = number_id_checking();

        $check_data_query = mysqli_query(connect(), ramble_checking($swertres_number));

        if (mysqli_num_rows($check_data_query) > 0) {

            while ($data = mysqli_fetch_assoc($check_data_query)) {
                if (in_array($data['swertres_no'], $new_combinations)) {
                    $final_combination[] = $data['swertres_no'];
                    $final_trans_id[] = $data['transaction_id'];
                } else {
                    $leftover_combination[] = $data['swertres_no'];
                }
            }

            $leftover_combination = array_diff($new_combinations, $final_combination); // Get the leftover values (values in $new_combinations that are not in $final_combination)
            $leftover_combination = array_values($leftover_combination); // Reset the index position of the array elements in $leftover_combination

            $found_max_length = count($final_combination);
            $leftover_max_length = count($leftover_combination);

            for ($i = 0; $i < $found_max_length; $i++) {

                $update_sql = "UPDATE `transaction` 
                                SET `amount` = `amount` + '$new_amount',
                                    `original_amount` = `original_amount` + '$ramble_amount'  
                                WHERE `swertres_no` = '$final_combination[$i]'
                                AND `transaction_id` = '$final_trans_id[$i]'";

                $update_query = mysqli_query(connect(), $update_sql);

                if (!$update_query) {
                    $_SESSION['error-message'] = "MYSQL Error!";
                }
            }

            if ($leftover_max_length > 0) {
                ramble_insert_query($leftover_combination, $number_id, $new_amount, $ramble_amount);
            }
        } else {
            ramble_insert_query($combinations, $number_id, $new_amount, $ramble_amount);
        }
    }

    // if straight amount have values--------------------------------
    if ($straight_amount != null) {

        $number_id = number_id_checking();

        // checking for number data time for script
        $check_data_query = mysqli_query(connect(), straight_checking($swertres_number));

        if (mysqli_num_rows($check_data_query) > 0) {
            $data = mysqli_fetch_assoc($check_data_query);
            $transaction_id = $data['transaction_id'];

            straight_update_query($straight_amount, $transaction_id);
        } else {
            straight_insert_query($number_id, $swertres_number, $straight_amount);
        }
    }

    header("Location: user-index.php");
    exit();
}

// echo json_encode(inputSwertres('300', '1', '1'));

function straight_checking($swertres_number)
{
    global $current_date;
    $time_today = date("H:i:s"); // 17:59:00

    if (($time_today >= "21:00:00") || ($time_today < "14:00:00")) {
        // for 2pm draws ----
        $straight_sql = "SELECT * FROM `transaction` 
                    WHERE `swertres_no` = '$swertres_number'
                    AND `date` = '$current_date'
                    AND (
                            TIME_FORMAT(`time`, '%h:%i:%s %p') >= '09:00:00 PM'
                            OR TIME_FORMAT(`time`, '%h:%i:%s %p') < '02:00:00 PM'
                        )";
    } else if (($time_today >= "14:00:00") && ($time_today < "17:00:00")) {
        // for 5pm draws ----
        $straight_sql = "SELECT * FROM `transaction` 
                    WHERE `swertres_no` = '$swertres_number'
                    AND `date` = '$current_date'
                    AND (
                            TIME_FORMAT(`time`, '%h:%i:%s %p') >= '02:00:00 PM'
                            AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '05:00:00 PM'
                        )";
    } else if (($time_today >= "17:00:00") && ($time_today < "21:00:00")) {
        // for 9pm draws ----
        $straight_sql = "SELECT * FROM `transaction` 
                    WHERE `swertres_no` = '$swertres_number'
                    AND `date` = '$current_date'
                    AND (
                            TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                            AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                        )";
    } else {
        $_SESSION['error-message'] = "No Time Detected!";
        exit();
    }
    return $straight_sql;
}

function ramble_checking($swertres_number)
{
    global $current_date;
    $split_number = str_split($swertres_number); // split the number to get single digits
    $time_today = date("H:i:s"); // 17:59:00

    if (($time_today >= "21:00:00") || ($time_today < "14:00:00")) {
        // for 2pm draws ----
        $ramble_sql = "SELECT * FROM `transaction` WHERE
                            `swertres_no` LIKE '%$split_number[0]%'
                            AND `swertres_no` LIKE '%$split_number[1]%'
                            AND `swertres_no` LIKE '%$split_number[2]%'
                            AND `date` = '$current_date'
                            AND (
                                    TIME_FORMAT(`time`, '%h:%i:%s %p') >= '09:00:00 PM'
                                    OR TIME_FORMAT(`time`, '%h:%i:%s %p') < '02:00:00 PM'
                                )";
    } else if (($time_today >= "14:00:00") && ($time_today < "17:00:00")) {
        // for 5pm draws ----
        $ramble_sql = "SELECT * FROM `transaction` WHERE
                            `swertres_no` LIKE '%$split_number[0]%'
                            AND `swertres_no` LIKE '%$split_number[1]%'
                            AND `swertres_no` LIKE '%$split_number[2]%'
                            AND `date` = '$current_date'
                            AND (
                                    TIME_FORMAT(`time`, '%h:%i:%s %p') >= '02:00:00 PM'
                                    AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '05:00:00 PM'
                                )";
    } else if (($time_today >= "17:00:00") && ($time_today < "21:00:00")) {
        // for 9pm draws ----
        $ramble_sql = "SELECT * FROM `transaction` WHERE
                            `swertres_no` LIKE '%$split_number[0]%'
                            AND `swertres_no` LIKE '%$split_number[1]%'
                            AND `swertres_no` LIKE '%$split_number[2]%'
                            AND `date` = '$current_date'
                            AND (
                                    TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                                    AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                                )";
    } else {
        $_SESSION['error-message'] = "No Time Detected!";
        exit();
    }
    return $ramble_sql;
}

function number_id_checking()
{
    do {
        $number_id = rand();
        $check_sql = "SELECT COUNT(*) as count FROM `transaction` WHERE `number_id` = '$number_id'";
        $check_query = mysqli_query(connect(), $check_sql);
        $count_result = mysqli_fetch_assoc($check_query);
    } while ($count_result['count'] > 0);

    return $number_id;
}

function straight_update_query($straight_amount, $transaction_id)
{
    $update_sql = "UPDATE `transaction` 
                SET `amount` = `amount` + '$straight_amount',
                    `original_amount` = `original_amount` + '$straight_amount'  
                WHERE `transaction_id` = '$transaction_id'";

    $update_query = mysqli_query(connect(), $update_sql);

    if ($update_query) {
        return $_SESSION['success-message'] = "Swertres Number Successfully Updated!";
    } else {
        return $_SESSION['error-message'] = "MYSQL Error!";
    }
}

function straight_insert_query($number_id, $swertres_number, $straight_amount)
{

    global $current_date;
    global $current_time;

    $sql_straight = "INSERT INTO `transaction`
                    (`number_id`,`swertres_no`,`type`,`amount`,`original_amount`,`time`,`date`)
                    VALUES
                    ('$number_id','$swertres_number','straight','$straight_amount','$straight_amount','$current_time','$current_date')";

    $query_straight = mysqli_query(connect(), $sql_straight);

    if ($query_straight) {
        return $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
    } else {
        return $_SESSION['error-message'] = "MYSQL Error!";
    }
}

function ramble_insert_query($combinations = array(), $number_id, $new_amount, $ramble_amount)
{

    global $current_date;
    global $current_time;
    $success = true; // Assume success initially

    foreach ($combinations as $swertres_no) {
        $sql_ramble = "INSERT INTO `transaction`
                        (`number_id`, `swertres_no`, `type`, `amount`, `original_amount`, `time`, `date`)
                        VALUES
                        ('$number_id', '$swertres_no', 'ramble', '$new_amount', '$ramble_amount', '$current_time', '$current_date')";

        $query_ramble = mysqli_query(connect(), $sql_ramble);

        if (!$query_ramble) {
            // If any query fails, set $success to false
            $success = false;
            break; // Exit the loop early as there's no point in continuing
        }
    }

    if ($success) {
        return $_SESSION['success-message'] = "Swertres Number(s) Successfully Submitted!";
    } else {
        return $_SESSION['error-message'] = "MYSQL Error!";
    }
}
