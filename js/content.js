function two_pm_draw() {
  $(function () {
    // Set the default date in datepicker
    $("#datepicker").datepicker({
      dateFormat: "MM d, yy",
      onSelect: function (dateText, inst) {
        var formattedDate = $.datepicker.formatDate(
          "yy-m-d",
          new Date(dateText)
        );
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
          ft_data_2pm();
          ft_trans_total_2pm();
          ft_data_5pm();
          ft_trans_total_5pm();
          ft_data_9pm();
          ft_trans_total_9pm();
          ft_date_total();
        },
      });
    }

    // 2PM transaction ------------------
    function ft_data_2pm() {
      $.ajax({
        type: "GET",
        url: "db/ft-data-2pm.php", // PHP script to fetch transaction data
        success: function (response) {
          // Update the transaction table with the fetched data
          $("#transaction-table-body-2pm").html(response);
        },
      });
    }

    function ft_trans_total_2pm() {
      $.ajax({
        type: "GET",
        url: "db/ft-total-2pm.php", // PHP script to fetch transaction total
        success: function (response) {
          // Update the transaction total
          $("#transaction-total-2pm").text(response);
        },
      });
    }

    // 5PM transaction ------------------
    function ft_data_5pm() {
      $.ajax({
        type: "GET",
        url: "db/ft-data-5pm.php", // PHP script to fetch transaction data
        success: function (response) {
          // Update the transaction table with the fetched data
          $("#transaction-table-body-5pm").html(response);
        },
      });
    }

    function ft_trans_total_5pm() {
      $.ajax({
        type: "GET",
        url: "db/ft-total-5pm.php", // PHP script to fetch transaction total
        success: function (response) {
          // Update the transaction total
          $("#transaction-total-5pm").text(response);
        },
      });
    }

    // 5PM transaction ------------------
    function ft_data_9pm() {
      $.ajax({
        type: "GET",
        url: "db/ft-data-9pm.php", // PHP script to fetch transaction data
        success: function (response) {
          // Update the transaction table with the fetched data
          $("#transaction-table-body-9pm").html(response);
        },
      });
    }

    function ft_trans_total_9pm() {
      $.ajax({
        type: "GET",
        url: "db/ft-total-9pm.php", // PHP script to fetch transaction total
        success: function (response) {
          // Update the transaction total
          $("#transaction-total-9pm").text(response);
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

    // Initial fetch and display of transaction data and total
    ft_data_2pm();
    ft_trans_total_2pm();
    ft_data_5pm();
    ft_trans_total_5pm();
    ft_data_9pm();
    ft_trans_total_9pm();
    ft_date_total();
  });
}

// Call the two_pm_draw function to start the application
two_pm_draw();

