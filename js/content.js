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