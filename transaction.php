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
    <script src="js/content.js"></script>
    <script>
        function fetchAndUpdateData(time) {
            $.ajax({
                type: "GET",
                url: `fetch-data.php?time=${time}`, // PHP script to fetch transaction data
                success: function (response) {
                    // Update the transaction table with the fetched data
                    $(`#transaction-table-body-${time}`).html(response);
                },
            });

            $.ajax({
                type: "GET",
                url: `ft-total-${time}.php`, // PHP script to fetch transaction total
                success: function (response) {
                    // Update the transaction total
                    $(`#transaction-total-${time}`).text(response);
                },
            });
        }

        function ft_date_total() {
            $.ajax({
                type: "GET",
                url: "db/ft-date-total.php", // PHP script to fetch transaction total
                success: function (response) {
                    // Update the transaction total
                    $("#transaction-date-total").text(response);
                },
            });
        }

        function two_pm_draw() {
            $(function () {
                // Set the default date in datepicker
                $("#datepicker").datepicker({
                    dateFormat: "MM d, yy",
                    onSelect: function (dateText, inst) {
                        var formattedDate = $.datepicker.formatDate("yy-m-d", new Date(dateText));
                        $("#hidden-date").val(formattedDate);

                        // Update the session date using AJAX
                        updateSessionDate();
                    },
                });

                // Function to update the session date using AJAX
                function updateSessionDate() {
                    var newDate = $("#hidden-date").val();

                    $.ajax({
                        type: "POST",
                        url: "db/update_session_date.php", // PHP script to update the session date
                        data: {
                            date: newDate,
                        },
                        success: function (response) {
                            // Update the displayed date in the table
                            $("#session-date").text(response);

                            // Fetch and display transaction data using AJAX
                            fetchAndUpdateData("2pm");
                            fetchAndUpdateData("5pm");
                            fetchAndUpdateData("9pm");
                            ft_date_total();
                        },
                    });
                }

                // Initial fetch and display of transaction data and total
                fetchAndUpdateData("2pm");
                fetchAndUpdateData("5pm");
                fetchAndUpdateData("9pm");
                ft_date_total();
            });
        }

        two_pm_draw();
    </script>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="overlay position-fixed">
        <div class="overlay-content shadow-sm">
            <div class="payout-analysis-content">
                <div class="d-flex justify-content-between">
                    <div id="payout-time" class="fw-bold d-flex align-items-center">
                        <form method="post" id="myForm">
                            <input type="text" id="datepicker" class="form-control fw-bold border-0"
                                value="<?php echo date('F j, Y'); ?>">
                            <input type="text" id="hidden-date" name="hidden-date"
                                value="<?php echo htmlspecialchars($_SESSION['date']); ?>" hidden>
                        </form>
                    </div>
                    <div class="w-50">
                        <div class="d-flex align-items-center h-100">
                            <span>&#8369;&nbsp;</span><b id="transaction-date-total"></b>
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    <form method="post" id="myForm2">
                        <input type="number" class="form-control" name="deduction" id="deduction"
                            placeholder="Deduction Amount">
                    </form>
                </div>
                <hr>
                <!-- Add content for payout analysis here -->
                <div class="">
                    <!-- Tabs navs -->
                    <ul class="nav nav-tabs mb-3 d-flex justify-content-between" id="ex-with-icons" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="main-link nav-link active" id="ex-with-icons-tab-1" data-bs-toggle="pill"
                                href="#ex-with-icons-tabs-1" role="tab" aria-controls="ex-with-icons-tabs-1"
                                aria-selected="true">2PM</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="main-link nav-link" id="ex-with-icons-tab-2" data-bs-toggle="pill"
                                href="#ex-with-icons-tabs-2" role="tab" aria-controls="ex-with-icons-tabs-2"
                                aria-selected="false">5PM</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="main-link nav-link" id="ex-with-icons-tab-3" data-bs-toggle="pill"
                                href="#ex-with-icons-tabs-3" role="tab" aria-controls="ex-with-icons-tabs-3"
                                aria-selected="false">9PM</a>
                        </li>
                    </ul>
                    <!-- Tabs navs -->

                    <!-- Tabs content -->
                    <div class="tab-content" id="ex-with-icons-content">
                        <div class="tab-pane fade show active" id="ex-with-icons-tabs-1" role="tabpanel"
                            aria-labelledby="ex-with-icons-tab-1">
                            <div class="table-responsive overflow-auto" id="table-container">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center table-dark">
                                                Total Amount
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="text-center">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <span>&#8369;&nbsp;</span>
                                                        <b id="transaction-total-2pm"></b>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="border border-secondary border-1">
                                            <th class="text-center text-white border-0 bg-dark align-middle"
                                                rowspan="2">Swertres</th>
                                            <th class="text-center text-white border-0 bg-dark" colspan="2">Amount
                                            </th>
                                        </tr>
                                        <tr class="border-0">
                                            <th class="text-center text-white border-0 bg-dark">New</th>
                                            <th class="text-center text-white border-0 bg-dark">Original</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transaction-table-body-2pm">
                                        <!-- Transaction data will be dynamically inserted here -->
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ex-with-icons-tabs-2" role="tabpanel"
                            aria-labelledby="ex-with-icons-tab-2">
                            <div class="table-responsive overflow-auto" id="table-container">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center table-dark">
                                                Total Amount
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="text-center">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <span>&#8369;&nbsp;</span>
                                                        <b id="transaction-total-5pm"></b>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="border border-secondary border-1">
                                            <th class="text-center text-white border-0 bg-dark align-middle"
                                                rowspan="2">Swertres</th>
                                            <th class="text-center text-white border-0 bg-dark" colspan="2">Amount
                                            </th>
                                        </tr>
                                        <tr class="border-0">
                                            <th class="text-center text-white border-0 bg-dark">New</th>
                                            <th class="text-center text-white border-0 bg-dark">Original</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transaction-table-body-5pm">
                                        <!-- Transaction data will be dynamically inserted here -->
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ex-with-icons-tabs-3" role="tabpanel"
                            aria-labelledby="ex-with-icons-tab-3">
                            <div class="table-responsive overflow-auto" id="table-container">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center table-dark">
                                                Total Amount
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="text-center">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <span>&#8369;&nbsp;</span>
                                                        <b id="transaction-total-9pm"></b>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="border border-secondary border-1">
                                            <th class="text-center text-white border-0 bg-dark align-middle"
                                                rowspan="2">Swertres</th>
                                            <th class="text-center text-white border-0 bg-dark" colspan="2">Amount
                                            </th>
                                        </tr>
                                        <tr class="border-0">
                                            <th class="text-center text-white border-0 bg-dark">New</th>
                                            <th class="text-center text-white border-0 bg-dark">Original</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transaction-table-body-9pm">
                                        <!-- Transaction data will be dynamically inserted here -->
                                    </tbody>
                                    <tfoot></tfoot>
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
                                <a class="nav-link dropdown-toggle d-flex align-items-center bg-dark text-white rounded"
                                    href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown">
                                    <i class="fa fa-bars"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li>
                                        <a class="dropdown-item" href="user-index.php"><i class="fa fa-pencil"></i>
                                            Input Swertres</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item bg-primary text-white" href="transaction.php"><i
                                                class="fa fa-bar-chart"></i> Payout Analysis</a>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    <li>
                                        <a class="dropdown-item" href="db/logout.php?page=user" data-bs-toggle="modal"
                                            data-bs-target="#logoutModal"><i class="fa fa-sign-out"></i> Logout</a>
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
                    <a href="db/logout.php?page=user" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script src="js/date.js"></script>
</body>

</html>
