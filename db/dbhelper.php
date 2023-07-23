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


function inputSwertres($swertres_number, $straight_amount, $ramble_amount)
{

    $current_date = date("Y-n-j");
    $current_time = date("h:i A"); // 03:16 PM
    $time_today = date("H:i:s"); // 17:59:00
    $straight_type = "straight";
    $ramble_type = "ramble";

    if (strtotime($current_time) >= strtotime("21:00:00")) {
        $current_date = date("Y-n-j", strtotime("+1 day"));
    }

    // straight and ramble have values--------------------------------
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

        // checking for number data time for script
        if ( ($time_today >= "21:00:00") || ($time_today < "14:00:00") ) {
            // for 2pm draws ----
            $straight_sql = "SELECT * FROM `transaction` 
                        WHERE `swertres_no` = '$swertres_number' 
                        AND `type` = '$straight_type'
                        AND `date` = '$current_date'
                        AND (
                                TIME_FORMAT(`time`, '%h:%i:%s %p') >= '09:00:00 PM'
                                OR TIME_FORMAT(`time`, '%h:%i:%s %p') < '02:00:00 PM'
                            )";
        }
        else if ( ($time_today >= "14:00:00") && ($time_today < "17:00:00") ) {
            // for 5pm draws ----
            $straight_sql = "SELECT * FROM `transaction` 
                        WHERE `swertres_no` = '$swertres_number' 
                        AND `type` = '$straight_type'
                        AND `date` = '$current_date'
                        AND (
                                TIME_FORMAT(`time`, '%h:%i:%s %p') >= '02:00:00 PM'
                                AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '05:00:00 PM'
                            )";
        }
        else if ( ($time_today >= "17:00:00") && ($time_today < "21:00:00") ) {
            // for 9pm draws ----
            $straight_sql = "SELECT * FROM `transaction` 
                        WHERE `swertres_no` = '$swertres_number' 
                        AND `type` = '$straight_type'
                        AND `date` = '$current_date'
                        AND (
                                TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                                AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                            )";
        }else {
            $_SESSION['error-message'] = "No Time Detected!";
            exit();
        }

        $straight_query = mysqli_query(connect(),$straight_sql);

        if(mysqli_num_rows($straight_query)>0){
            $data = mysqli_fetch_assoc($straight_query);
            $swertres_id = $data['transaction_id'];

            $update_sql = "UPDATE `transaction` 
                            SET `amount` = `amount` + '$straight_amount',
                                `original_amount` = `original_amount` + '$straight_amount'  
                            WHERE `transaction_id` = '$swertres_id'";

            $update_query = mysqli_query(connect(),$update_sql);

            if ($update_query) {
                $result += 1;
            } else {
                $_SESSION['error-message'] = "MYSQL Error!";
            }
        }
        else{
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
        }

        
        // ---------------------------------------------------------- 
        // 2 digit same number
        if (r_2digit_same($swertres_number)) {

            $combinations = array_unique(r_2digit_data($swertres_number));
            $new_amount = round($ramble_amount / 3);
        } 
        // 3 digit different number
        else {
            $combinations = r_2digit_data($swertres_number);
            $new_amount = round($ramble_amount / 6);
        }
        
        $split_number = str_split($swertres_number); // split the number to get single digits

        foreach($combinations as $number){
            $new_combinations[] = $number; // get all random numbers
        }
        
        do {
            $number_id = rand();
            $check_sql = "SELECT COUNT(*) as count FROM `transaction` WHERE `number_id` = '$number_id'";
            $check_query = mysqli_query(connect(), $check_sql);
            $count_result = mysqli_fetch_assoc($check_query);
        } while ($count_result['count'] > 0);

        if (($time_today >= "21:00:00") || ($time_today < "14:00:00")) {
            // for 2pm draws ----
            $ramble_sql = "SELECT * FROM `transaction` WHERE
                                `swertres_no` LIKE '%$split_number[0]%'
                                AND `swertres_no` LIKE '%$split_number[1]%'
                                AND `swertres_no` LIKE '%$split_number[2]%'
                                AND `type` = '$ramble_type'
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
                                AND `type` = '$ramble_type'
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
                                AND `type` = '$ramble_type'
                                AND `date` = '$current_date'
                                AND (
                                        TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                                        AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                                    )";
        } else {
            $_SESSION['error-message'] = "No Time Detected!";
            exit();
        }

        $ramble_query = mysqli_query(connect(), $ramble_sql);

        if (mysqli_num_rows($ramble_query) > 0) {

            while ($data = mysqli_fetch_assoc($ramble_query)) {

                if (in_array($data['swertres_no'], $new_combinations)) {
                    $final_combination[] = $data['swertres_no'];
                    $final_trans_id[] = $data['transaction_id'];
                }
            }
            $max_length = count($final_combination);
            $result = 0;

            for($i=0;$i < $max_length;$i++){
                $update_sql = "UPDATE `transaction` 
                                SET `amount` = `amount` + '$new_amount',
                                    `original_amount` = `original_amount` + '$ramble_amount'  
                                WHERE `swertres_no` = '$final_combination[$i]'
                                AND `transaction_id` = '$final_trans_id[$i]'";

                $update_query = mysqli_query(connect(),$update_sql);   

                if ($update_query>0) {
                    $result += 1;
                } else {
                    $_SESSION['error-message'] = "MYSQL Error!";
                }
            }
        
            if ($result>0) {
                $result += 1;
            } else {
                $_SESSION['error-message'] = "MYSQL Error!";
            }
        }else{
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

        // only ramble has values----------------------------------------
    } else if ($straight_amount == null && $ramble_amount != null) {

        // 2 digit same number
        if (r_2digit_same($swertres_number)) {

            $combinations = array_unique(r_2digit_data($swertres_number));
            $new_amount = round($ramble_amount / 3);
        } 
        // 3 digit different number
        else {
            $combinations = r_2digit_data($swertres_number);
            $new_amount = round($ramble_amount / 6);
        }
        
        $split_number = str_split($swertres_number); // split the number to get single digits

        foreach($combinations as $number){
            $new_combinations[] = $number; // get all random numbers
        }
        
        do {
            $number_id = rand();
            $check_sql = "SELECT COUNT(*) as count FROM `transaction` WHERE `number_id` = '$number_id'";
            $check_query = mysqli_query(connect(), $check_sql);
            $count_result = mysqli_fetch_assoc($check_query);
        } while ($count_result['count'] > 0);

        if (($time_today >= "21:00:00") || ($time_today < "14:00:00")) {
            // for 2pm draws ----
            $check_data_sql = "SELECT * FROM `transaction` WHERE
                                `swertres_no` LIKE '%$split_number[0]%'
                                AND `swertres_no` LIKE '%$split_number[1]%'
                                AND `swertres_no` LIKE '%$split_number[2]%'
                                AND `type` = '$ramble_type'
                                AND `date` = '$current_date'
                                AND (
                                        TIME_FORMAT(`time`, '%h:%i:%s %p') >= '09:00:00 PM'
                                        OR TIME_FORMAT(`time`, '%h:%i:%s %p') < '02:00:00 PM'
                                    )";
        } else if (($time_today >= "14:00:00") && ($time_today < "17:00:00")) {
            // for 5pm draws ----
            $check_data_sql = "SELECT * FROM `transaction` WHERE
                                `swertres_no` LIKE '%$split_number[0]%'
                                AND `swertres_no` LIKE '%$split_number[1]%'
                                AND `swertres_no` LIKE '%$split_number[2]%'
                                AND `type` = '$ramble_type'
                                AND `date` = '$current_date'
                                AND (
                                        TIME_FORMAT(`time`, '%h:%i:%s %p') >= '02:00:00 PM'
                                        AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '05:00:00 PM'
                                    )";
        } else if (($time_today >= "17:00:00") && ($time_today < "21:00:00")) {
            // for 9pm draws ----
            $check_data_sql = "SELECT * FROM `transaction` WHERE
                                `swertres_no` LIKE '%$split_number[0]%'
                                AND `swertres_no` LIKE '%$split_number[1]%'
                                AND `swertres_no` LIKE '%$split_number[2]%'
                                AND `type` = '$ramble_type'
                                AND `date` = '$current_date'
                                AND (
                                        TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                                        AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                                    )";
        } else {
            $_SESSION['error-message'] = "No Time Detected!";
            exit();
        }

        $check_data_query = mysqli_query(connect(), $check_data_sql);

        if (mysqli_num_rows($check_data_query) > 0) {

            while ($data = mysqli_fetch_assoc($check_data_query)) {

                if (in_array($data['swertres_no'], $new_combinations)) {
                    $final_combination[] = $data['swertres_no'];
                    $final_trans_id[] = $data['transaction_id'];
                }
            }
            $max_length = count($final_combination);
            $result = 0;

            for($i=0;$i < $max_length;$i++){
                $update_sql = "UPDATE `transaction` 
                                SET `amount` = `amount` + '$new_amount',
                                    `original_amount` = `original_amount` + '$ramble_amount'  
                                WHERE `swertres_no` = '$final_combination[$i]'
                                AND `transaction_id` = '$final_trans_id[$i]'";

                $update_query = mysqli_query(connect(),$update_sql);   

                if ($update_query>0) {
                    $result += 1;
                } else {
                    $_SESSION['error-message'] = "MYSQL Error!";
                }
            }
        
            if ($result>0) {
                $_SESSION['success-message'] = "Swertres Number Successfully Updated!";
            } else {
                $_SESSION['error-message'] = "MYSQL Error!";
            }
        }else{
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
        
        // checking for number data time for script
        if ( ($time_today >= "21:00:00") || ($time_today < "14:00:00") ) {
            // for 2pm draws ----
            $check_data_sql = "SELECT * FROM `transaction` 
                        WHERE `swertres_no` = '$swertres_number' 
                        AND `type` = '$straight_type'
                        AND `date` = '$current_date'
                        AND (
                                TIME_FORMAT(`time`, '%h:%i:%s %p') >= '09:00:00 PM'
                                OR TIME_FORMAT(`time`, '%h:%i:%s %p') < '02:00:00 PM'
                            )";
        }
        else if ( ($time_today >= "14:00:00") && ($time_today < "17:00:00") ) {
            // for 5pm draws ----
            $check_data_sql = "SELECT * FROM `transaction` 
                        WHERE `swertres_no` = '$swertres_number' 
                        AND `type` = '$straight_type'
                        AND `date` = '$current_date'
                        AND (
                                TIME_FORMAT(`time`, '%h:%i:%s %p') >= '02:00:00 PM'
                                AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '05:00:00 PM'
                            )";
        }
        else if ( ($time_today >= "17:00:00") && ($time_today < "21:00:00") ) {
            // for 9pm draws ----
            $check_data_sql = "SELECT * FROM `transaction` 
                        WHERE `swertres_no` = '$swertres_number' 
                        AND `type` = '$straight_type'
                        AND `date` = '$current_date'
                        AND (
                                TIME_FORMAT(`time`, '%h:%i:%s %p') >= '05:00:00 PM'
                                AND TIME_FORMAT(`time`, '%h:%i:%s %p') < '09:00:00 PM'
                            )";
        }else {
            $_SESSION['error-message'] = "No Time Detected!";
            exit();
        }

        $check_data_query = mysqli_query(connect(),$check_data_sql);

        if(mysqli_num_rows($check_data_query)>0){
            $data = mysqli_fetch_assoc($check_data_query);
            $swertres_id = $data['transaction_id'];

            $update_sql = "UPDATE `transaction` 
                            SET `amount` = `amount` + '$straight_amount',
                                `original_amount` = `original_amount` + '$straight_amount'  
                            WHERE `transaction_id` = '$swertres_id'";

            $update_query = mysqli_query(connect(),$update_sql);

            if ($update_query) {
                $_SESSION['success-message'] = "Swertres Number Successfully Updated!";
            } else {
                $_SESSION['error-message'] = "MYSQL Error!";
            }
        }
        else{
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
        }
    } else {
        $_SESSION['error-message'] = "Must input a straight/ramble amount!";
    }
    header("Location: user-index.php");
    exit();
}

// echo nl2br("\n")."".json_encode(inputSwertres('093','','5'));

