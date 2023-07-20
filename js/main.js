$(document).ready(function () {
  // Function to handle input validation and button state
  function handleInputValidation(inputName, regex) {
    $(document).on("input", `input[name="${inputName}"]`, function () {
      var isValid = true; // Assume valid initially

      // Check all input fields and set isValid to false if any input is invalid
      $('input[name="swertres-number"], input[name="straight-amount"], input[name="ramble-amount"], input[name="deduction"]').each(function () {
        var value = $(this).val();
        if (!regex.test(value)) {
          isValid = false;
          return false; // Exit the loop early, as there's no need to check other inputs
        }
      });

      $('input[name="swertres-number"], input[name="straight-amount"], input[name="ramble-amount"], input[name="deduction"]').toggleClass("is-invalid", !isValid);

      // Enable or disable the button based on isValid
      $("#submit-btn").prop("disabled", !isValid);

      // Change the color of the button based on isValid
      if (!isValid) {
        $("#submit-btn").addClass("btn-secondary");
        $("#submit-btn").removeClass("btn-primary");
      } else {
        $("#submit-btn").addClass("btn-primary");
        $("#submit-btn").removeClass("btn-secondary");
      }
    });
  }

  // Call the function for each input field
  handleInputValidation("swertres-number", /^\d{1,3}$/);
  handleInputValidation("straight-amount", /^(\d{1,9}|\d{1,9}\.\d{1,2})$/);
  handleInputValidation("ramble-amount", /^(\d{1,9}|\d{1,9}\.\d{1,2})$/);
  handleInputValidation("deduction", /^(\d{1,9}|\d{1,9}\.\d{1,2})$/);
});

// document.addEventListener('DOMContentLoaded', function() {
//   var deductionInput = document.getElementById('deduction');
//   var newAmountElements = document.querySelectorAll('.new-amount'); // Elements to update the new_amount

//   deductionInput.addEventListener('input', function() {
//       var deductionValue = parseFloat(this.value) || 0; // Convert deduction input value to a number or 0 if invalid
//       var rOriginalAmount = parseFloat('<?php echo $r_original_amount; ?>');

//       // Calculate the new_amount based on deductionValue
//       var newAmount = (rOriginalAmount - deductionValue).toFixed(2);

//       // Update the new_amount in all relevant elements
//       newAmountElements.forEach(function(element) {
//           element.textContent = newAmount;
//       });
//   });
// });
