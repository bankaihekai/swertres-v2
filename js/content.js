function two_pm_draw() {
  $(function() {
      // Set the default date in datepicker
      $("#datepicker").datepicker({
          dateFormat: "MM d, yy",
          onSelect: function(dateText, inst) {
              var formattedDate = $.datepicker.formatDate("yy-m-d", new Date(dateText));
              $("#hidden-date").val(formattedDate);

              // Update the session date using AJAX
              updateSessionDate();
          }
      });

      // Function to update the session date using AJAX
      function updateSessionDate() {
          var newDate = $("#hidden-date").val();

          $.ajax({
              type: "POST",
              url: "update_session_date.php", // PHP script to update the session date
              data: {
                  date: newDate
              },
              success: function(response) {
                  // Update the displayed date in the table
                  $("#session-date").text(response);

                  // Fetch and display transaction data using AJAX
                  fetchTransactionData();
                  fetchTransactionTotal();
              }
          });
      }

      // Function to fetch and display transaction data using AJAX
      function fetchTransactionData() {
          $.ajax({
              type: "GET",
              url: "fetch_transaction_data.php", // PHP script to fetch transaction data
              success: function(response) {
                  // Update the transaction table with the fetched data
                  $("#transaction-table-body-2pm").html(response);
              }
          });
      }

      // Function to fetch and display transaction total using AJAX
      function fetchTransactionTotal() {
          $.ajax({
              type: "GET",
              url: "fetch_transaction_total.php", // PHP script to fetch transaction total
              success: function(response) {
                  // Update the transaction total
                  $("#transaction-total-2pm").text(response);
              }
          });
      }

      // Initial fetch and display of transaction data and total
      fetchTransactionData();
      fetchTransactionTotal();
  });
}

// Call the two_pm_draw function to start the application
two_pm_draw();
