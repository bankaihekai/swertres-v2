$(document).ready(function() {

    $(document).on('input', 'input[name="swertres-number"]', function() {
        var value = $(this).val();
        var isValid = /^\d{1,3}$/.test(value);
        $(this).toggleClass('is-invalid', !isValid);

        // Disable/enable the ramble-amount input based on swertres-number value
        var disableRambleAmount = ["000", "111", "222", "333", "444", "555", "666", "777", "888", "999"].includes(value);
        $('input[name="ramble-amount"]').prop('disabled', disableRambleAmount);
    });

    $(document).on('input', 'input[name="straight-amount"]', function() {
        var value = $(this).val();
        var isValid = /^(\d{1,9}|\d{1,9}\.\d{1,2})$/.test(value);
        $(this).toggleClass('is-invalid', !isValid);
    
        if (value === "") {
            $("#straight-amount-error").text("No value entered");
        } else {
            $("#straight-amount-error").empty();
        }
    });
    
    $(document).on('input', 'input[name="ramble-amount"]', function() {
        var value = $(this).val();
        var isValid = /^(\d{1,9}|\d{1,9}\.\d{1,2})$/.test(value);
        $(this).toggleClass('is-invalid', !isValid);
    
        if (value === "") {
            $("#ramble-amount-error").text("No value entered");
        } else {
            $("#ramble-amount-error").empty();
        }
    });
    

});

$(document).ready(function() {
    // Initialize datepicker
    $("#datepicker").datepicker();
});



