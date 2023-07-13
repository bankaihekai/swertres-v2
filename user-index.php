<?php

include("dbhelper.php");
session_start();

date_default_timezone_set('Asia/Manila');

$current_date = date("Y-n-j");
$current_time = date("h:i A");

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

if (isset($_POST['submit'])) {

    $swertres_number = $_POST['swertres-number'];
    $straight_amount = null;
    $ramble_amount = null;
    $straight_type = "straight";
    $ramble_type = "ramble";

    if (isset($_POST['straight-amount'])) {
        $straight_amount = $_POST['straight-amount'];
    }

    if (isset($_POST['ramble-amount'])) {
        $ramble_amount = $_POST['ramble-amount'];
    }

    if ($straight_amount != null && $ramble_amount != null) {

        $sql_straight = "INSERT INTO `transaction`
                        (`swertres_no`,`type`,`amount`,`time`,`date`,`status`)
                        VALUES
                        ('$swertres_number','$straight_type','$straight_amount','$current_time','$current_date','pending')";

        $sql_ramble = "INSERT INTO `transaction`
                        (`swertres_no`,`type`,`amount`,`time`,`date`,`status`)
                        VALUES
                        ('$swertres_number','$ramble_type','$ramble_amount','$current_time','$current_date','pending')";

        $query_straight = mysqli_query(connect(), $sql_straight);
        $query_ramble = mysqli_query(connect(), $sql_ramble);

        if ($query_straight && $query_ramble) {
            $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }
    } else if ($straight_amount == null && $ramble_amount != null) {
        $sql_ramble = "INSERT INTO `transaction`
                        (`swertres_no`,`type`,`amount`,`time`,`date`,`status`)
                        VALUES
                        ('$swertres_number','$ramble_type','$ramble_amount','$current_time','$current_date','pending')";

        $query_ramble = mysqli_query(connect(), $sql_ramble);

        if ($query_ramble) {
            $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }
    } else if ($straight_amount != null && $ramble_amount == null) {
        $sql_straight = "INSERT INTO `transaction`
                        (`swertres_no`,`type`,`amount`,`time`,`date`,`status`)
                        VALUES
                        ('$swertres_number','$straight_type','$straight_amount','$current_time','$current_date','pending')";

        $query_straight = mysqli_query(connect(), $sql_straight);

        if ($query_straight) {
            $_SESSION['success-message'] = "Swertres Number Successfully Submitted!";
        } else {
            $_SESSION['error-message'] = "MYSQL Error!";
        }
    } else {
        $_SESSION['error-message'] = "Must input a straight/ramble amount!";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Swertres</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../img/logo.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <!-- jQuery and jQuery UI JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="overlay position-fixed">
        <div class="overlay-content w-75 shadow-sm">
            <div class="input-swertres-content">
                <div id="time" class="fw-bold text-center"></div>
                <div class="pt-5">
                    <form method="post" id="swertres-form">
                        <div>
                            <?php
                            if (isset($_SESSION['error-message'])) {
                                $message = $_SESSION['error-message'];
                                unset($_SESSION['error-message']);
                                echo "<h6 class='alert alert-danger text-center'>$message</h6>";
                            }
                            if (isset($_SESSION['success-message'])) {
                                $message = $_SESSION['success-message'];
                                unset($_SESSION['success-message']);
                                echo "<h6 class='alert alert-success text-center'>$message</h6>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label for="swertres-number" class="form-label">Swertres Number</label>
                            <input type="text" class="form-control" id="swertres-number" name="swertres-number" maxlength="3" pattern="[0-9]{3}" placeholder="000-999" required>
                        </div>
                        <div class="mb-3">
                            <label for="straight-amount" class="form-label">Straight Amount</label>
                            <input type="text" class="form-control" id="straight-amount" name="straight-amount" placeholder="&#8369; 0.00">
                        </div>
                        <div class="mb-3">
                            <label for="ramble-amount" class="form-label">Ramble Amount</label>
                            <input type="text" class="form-control" id="ramble-amount" name="ramble-amount" placeholder="&#8369; 0.00">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="payout-analysis-content">
                <div class="d-flex justify-content-between">
                    <div id="payout-time" class="fw-bold d-flex align-items-center">
                        <div id="payout-time" class="fw-bold d-flex align-items-center">
                            <input type="text" id="datepicker" class="form-control fw-bold border-0" value="<?php echo $date_today; ?>">
                        </div>
                    </div>
                    <div class="w-50">
                        <div class="d-flex align-items-center">
                            &#8369;&nbsp;<input type="text" name="total-amount" class="border-0 form-control bg-transparent" disabled value="0.00">
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Add content for payout analysis here -->
                <div class="">
                    <!-- Tabs navs -->
                    <ul class="nav nav-tabs mb-3 d-flex justify-content-between" id="ex-with-icons" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="main-link nav-link active" id="ex-with-icons-tab-1" data-bs-toggle="pill" href="#ex-with-icons-tabs-1" role="tab" aria-controls="ex-with-icons-tabs-1" aria-selected="true"></i>2PM
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="main-link nav-link" id="ex-with-icons-tab-2" data-bs-toggle="pill" href="#ex-with-icons-tabs-2" role="tab" aria-controls="ex-with-icons-tabs-2" aria-selected="false"></i>5PM
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="main-link nav-link" id="ex-with-icons-tab-3" data-bs-toggle="pill" href="#ex-with-icons-tabs-3" role="tab" aria-controls="ex-with-icons-tabs-3" aria-selected="false"></i>9PM
                            </a>
                        </li>
                    </ul>
                    <!-- Tabs navs -->

                    <!-- Tabs content -->
                    <div class="tab-content" id="ex-with-icons-content">
                        <div class="tab-pane fade show active" id="ex-with-icons-tabs-1" role="tabpanel" aria-labelledby="ex-with-icons-tab-1">
                            <div class="table-responsive overflow-auto" id="table-container">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">
                                            Total Amount
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span>&#8369;&nbsp;</span>
                                                    <?php echo "0.00"; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ex-with-icons-tabs-2" role="tabpanel" aria-labelledby="ex-with-icons-tab-2">
                            <div class="table-responsive overflow-auto" id="table-container">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">
                                            Total Amount
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span>&#8369;&nbsp;</span>
                                                    <?php echo "0.00"; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ex-with-icons-tabs-3" role="tabpanel" aria-labelledby="ex-with-icons-tab-3">
                            <div class="table-responsive overflow-auto" id="table-container">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">
                                            Total Amount
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span>&#8369;&nbsp;</span>
                                                    <?php echo "0.00"; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Tabs content -->
                </div>
            </div>
        </div>
    </div>
    <div class="vh-100">
        <nav class="navbar navbar-expand navbar-light bg-light">
            <div class="container-fluid">
                <div class="collapse navbar-collapse">
                    <div>
                        <ul class="navbar-nav">
                            <!-- Avatar -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center bg-dark text-white rounded" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown">
                                    <i class="fa fa-bars"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="showInputSwertresContent()"><i class="fa fa-pencil"></i> Input Swertres</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="showPayoutAnalysisContent()"><i class="fa fa-bar-chart"></i> Payout Analysis</a>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    <li>
                                        <a class="dropdown-item" href="logout.php?page=user"><i class="fa fa-sign-out"></i> Logout</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-end w-100 ">
                        <h6 class="my-auto">SWERTRES ANALYTICS APP</h6>
                    </div>
                </div>
            </div>
        </nav>

        <div class="bg-dark h-50">
            <!-- this is the background dont touch this -->
        </div>
    </div>

    <script src="js/main.js"></script>
    <script src="js/content.js"></script>
    <script src="js/date.js"></script>
</body>

</html>