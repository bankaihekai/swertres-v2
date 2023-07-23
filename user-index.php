<?php

include("db/dbhelper.php");
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

if (isset($_POST['submit'])) {

    $swertres_number = $_POST['swertres-number'];
    $straight_amount = null;
    $ramble_amount = null;

    if (isset($_POST['straight-amount'])) {
        $straight_amount = $_POST['straight-amount'];
    }

    if (isset($_POST['ramble-amount'])) {
        $ramble_amount = $_POST['ramble-amount'];
    }

    inputSwertres($swertres_number, $straight_amount, $ramble_amount);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Swertres</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="img/logo.png" />

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
                            <input type="text" class="form-control" id="swertres-number" name="swertres-number" maxlength="3" placeholder="000-999" required>
                        </div>
                        <div class="mb-3">
                            <label for="straight-amount" class="form-label">Straight Amount</label>
                            <input type="number" class="form-control" id="straight-amount" name="straight-amount" placeholder="&#8369; 0.00">
                        </div>
                        <div class="mb-3">
                            <label for="ramble-amount" class="form-label">Ramble Amount</label>
                            <input type="number" class="form-control" id="ramble-amount" name="ramble-amount" placeholder="&#8369; 0.00">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" name="submit" id="submit-btn" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
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
                                        <a class="dropdown-item bg-primary text-white" href="user-index.php"><i class="fa fa-pencil"></i> Input Swertres</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="transaction.php"><i class="fa fa-bar-chart"></i> Payout Analysis</a>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    <li>
                                        <a class="dropdown-item" href="db/logout.php?page=user" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fa fa-sign-out"></i> Logout</a>
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

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <a href="db/logout.php" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Incorrect Swertres No.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center"> 
                        <b>Please enter exactly &nbsp;<span class="text-danger">Three digit number!</span></b>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <!-- <script src="js/content.js"></script> -->
    <script src="js/date.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputField = document.getElementById("swertres-number");
            const form = document.querySelector("form");

            // Add an event listener to the form submission
            form.addEventListener("submit", function(event) {
                if (inputField.value.length !== 3 || !/^\d{3}$/.test(inputField.value)) {
                    // Prevent the form submission if the input is not three digits
                    event.preventDefault();
                    // Show the Bootstrap modal
                    const myModal = new bootstrap.Modal(document.getElementById("myModal"));
                    myModal.show();
                }
            });
        });
    </script>
</body>

</html>