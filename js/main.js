$(document).ready(function() {

    $(document).on('input', 'input[name="swertres-number"]', function() {
        var value = $(this).val();
        var isValid = /^\d{1,3}$/.test(value);
        $(this).toggleClass('is-invalid', !isValid);
    });

    $(document).on('input', 'input[name="straight-amount"]', function() {
        var value = $(this).val();
        var isValid = /^(\d{1,9}|\d{1,9}\.\d{1,2})$/.test(value);
        $(this).toggleClass('is-invalid', !isValid);
    });

    $(document).on('input', 'input[name="ramble-amount"]', function() {
        var value = $(this).val();
        var isValid = /^(\d{1,9}|\d{1,9}\.\d{1,2})$/.test(value);
        $(this).toggleClass('is-invalid', !isValid);
    });

});

$(document).ready(function() {
    // Initialize datepicker
    $("#datepicker").datepicker();
});



