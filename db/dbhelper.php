<?php
date_default_timezone_set('Asia/Manila');
$date_today2 = date("Y-m-j"); // for date picker format
$date_today = date("F j, Y");

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

function inputSwertres($swertres_number, $straight_amount, $ramble_amount)
{

    $current_date = date("Y-n-j");
    $current_time = date("h:i A");

    $straight_type = "straight";
    $ramble_type = "ramble";

    if (strtotime($current_time) >= strtotime("21:00:00")) {
        $current_date = date("Y-n-j", strtotime("+1 day"));
    }

    // straight and ramble have values
    if ($straight_amount != null && $ramble_amount != null) {

        // for straight number
        do {
            $number_id = rand();
            $check_sql = "SELECT COUNT(*) as count FROM `transaction` WHERE `number_id` = '$number_id'";
            $check_query = mysqli_query(connect(), $check_sql);
            $count_result = mysqli_fetch_assoc($check_query);
        } while ($count_result['count'] > 0);

        $str_id = $number_id + 1;
        $result = 0;

        $sql_straight = "INSERT INTO `transaction`
                        (`number_id`,`swertres_no`,`type`,`amount`,`original_amount`,`time`,`date`)
                        VALUES
                        ('$str_id','$swertres_number','$straight_type','$straight_amount','$straight_amount','$current_time','$current_date')";

        $query_straight = mysqli_query(connect(), $sql_straight);
        if ($query_straight) {
            $result += 1;
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }

        if (r_2digit_same($swertres_number)) {

            $combinations = array_unique(r_2digit_data($swertres_number));
            $new_amount = number_format($ramble_amount / 3, 2);

            foreach ($combinations as $swertres_no) {

                $sql_ramble = "INSERT INTO `transaction`
                                (`number_id`,`swertres_no`,`type`,`amount`,`original_amount`,`time`,`date`)
                                VALUES
                                ('$number_id','$swertres_no','$ramble_type','$new_amount','$ramble_amount','$current_time','$current_date')";

                $query_ramble = mysqli_query(connect(), $sql_ramble);

                if ($query_ramble) {
                    $result += 1;
                } else {
                    $_SESSION['error-message'] = "MYSQL Error!";
                }
            }
        } else {
            $combinations = r_2digit_data($swertres_number);
            $new_amount = number_format($ramble_amount / 6, 2);

            foreach ($combinations as $swertres_no) {

                $sql_ramble = "INSERT INTO `transaction`
                                (`number_id`,`swertres_no`,`type`,`amount`,`original_amount`,`time`,`date`)
                                VALUES
                                ('$number_id','$swertres_no','$ramble_type','$new_amount','$ramble_amount','$current_time','$current_date')";

                $query_ramble = mysqli_query(connect(), $sql_ramble);

                if ($query_ramble) {
                    $result += 1;
                } else {
                    $_SESSION['error-message'] = "MYSQL Error!";
                }
            }
        }

        if ($result > 0) {
            $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }

        // only ramble has values
    } else if ($straight_amount == null && $ramble_amount != null) {

        if (r_2digit_same($swertres_number)) {

            $combinations = array_unique(r_2digit_data($swertres_number));
            $new_amount = number_format($ramble_amount / 3, 2);

            do {
                $number_id = rand();
                $check_sql = "SELECT COUNT(*) as count FROM `transaction` WHERE `number_id` = '$number_id'";
                $check_query = mysqli_query(connect(), $check_sql);
                $count_result = mysqli_fetch_assoc($check_query);
            } while ($count_result['count'] > 0);

            foreach ($combinations as $swertres_no) {

                $sql_ramble = "INSERT INTO `transaction`
                                (`number_id`,`swertres_no`,`type`,`amount`,`original_amount`,`time`,`date`)
                                VALUES
                                ('$number_id','$swertres_no','$ramble_type','$new_amount','$ramble_amount','$current_time','$current_date')";

                $query_ramble = mysqli_query(connect(), $sql_ramble);

                if ($query_ramble) {
                    $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
                } else {
                    $_SESSION['error-message'] = "MYSQL Error!";
                }
            }
        } else {

            $combinations = r_2digit_data($swertres_number);
            $new_amount = number_format($ramble_amount / 6, 2);
            
            do {
                $number_id = rand();
                $check_sql = "SELECT COUNT(*) as count FROM `transaction` WHERE `number_id` = '$number_id'";
                $check_query = mysqli_query(connect(), $check_sql);
                $count_result = mysqli_fetch_assoc($check_query);
            } while ($count_result['count'] > 0);

            foreach ($combinations as $swertres_no) {

                $sql_ramble = "INSERT INTO `transaction`
                                (`number_id`,`swertres_no`,`type`,`amount`,`original_amount`,`time`,`date`)
                                VALUES
                                ('$number_id','$swertres_no','$ramble_type','$new_amount','$ramble_amount','$current_time','$current_date')";

                $query_ramble = mysqli_query(connect(), $sql_ramble);

                if ($query_ramble) {
                    $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
                } else {
                    $_SESSION['error-message'] = "MYSQL Error!";
                }
            }
        }

        // only straight has values
    } else if ($straight_amount != null && $ramble_amount == null) {

        do {
            $number_id = rand();
            $check_sql = "SELECT COUNT(*) as count FROM `transaction` WHERE `number_id` = '$number_id'";
            $check_query = mysqli_query(connect(), $check_sql);
            $count_result = mysqli_fetch_assoc($check_query);
        } while ($count_result['count'] > 0);

        $sql_straight = "INSERT INTO `transaction`
                        (`number_id`,`swertres_no`,`type`,`amount`,`original_amount`,`time`,`date`)
                        VALUES
                        ('$number_id','$swertres_number','$straight_type','$straight_amount','$straight_amount','$current_time','$current_date')";

        $query_straight = mysqli_query(connect(), $sql_straight);

        if ($query_straight) {
            $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }
    } else {
        $_SESSION['error-message'] = "Must input a straight/ramble amount!";
    }
    header("Location: user-index.php");
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

function deduction(){
    $deduct_query = mysqli_query(connect(),"SELECT * FROM `deduction` LIMIT 1");

    if(mysqli_num_rows($deduct_query)>0){
        $row = mysqli_fetch_assoc($deduct_query);
        $_SESSION['deduction'] = $row['amount'];
    }
}
