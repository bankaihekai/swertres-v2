$(document).ready(function () {
  function validateInputs() {
    var swertresNumber = $('input[name="swertres-number"]').val();
    var straightAmount = parseFloat($('input[name="straight-amount"]').val());
    var rambleAmount = parseFloat($('input[name="ramble-amount"]').val());

    // Check if all inputs are empty
    var isAllInputsEmpty = swertresNumber === "" && isNaN(straightAmount) && isNaN(rambleAmount);

    // "swertres-number" is required and at least one of "straight-amount" or "ramble-amount" should be valid
    return !isNaN(swertresNumber) && swertresNumber !== "" && (!isNaN(straightAmount) || !isNaN(rambleAmount)) && !isAllInputsEmpty;
  }

  function updateSubmitButtonStatus() {
    var isFormValid = validateInputs();
    $('#submit-btn').prop('disabled', !isFormValid);

    if (!isFormValid) {
      $("#submit-btn").addClass("btn-secondary");
      $("#submit-btn").removeClass("btn-primary");
    } else {
      $("#submit-btn").addClass("btn-primary");
      $("#submit-btn").removeClass("btn-secondary");
    }
  }

  $(document).on("input", 'input[name="swertres-number"]', function () {
    var value = $(this).val();
    var isValid = /^\d{1,3}$/.test(value);
    $(this).toggleClass("is-invalid", !isValid);

    // Disable/enable the ramble-amount input based on swertres-number value
    var disableRambleAmount = [
      "000",
      "111",
      "222",
      "333",
      "444",
      "555",
      "666",
      "777",
      "888",
      "999",
    ].includes(value);
    $('input[name="ramble-amount"]').prop("disabled", disableRambleAmount);

    updateSubmitButtonStatus();
  });

  $(document).on("input", 'input[name="straight-amount"]', function () {
    updateSubmitButtonStatus();
  });

  $(document).on("input", 'input[name="ramble-amount"]', function () {
    updateSubmitButtonStatus();
  });

  // On initial load, disable the submit button since no input is provided yet
  updateSubmitButtonStatus();
});
