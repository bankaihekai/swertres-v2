document.addEventListener("DOMContentLoaded", function() {
    const inputField = document.getElementById("swertres-number");
    const form = document.getElementById("swertres-form");

    // Add an event listener to the form submission
    form.addEventListener("submit", function(event) {
        // Check if Swertres Number is not three digits
        if (inputField.value.length !== 3 || !/^\d{3}$/.test(inputField.value)) {
            event.preventDefault();
            const myModal = new bootstrap.Modal(document.getElementById("not-3digits"));
            myModal.show();
        }

        // Validate Straight Amount and Ramble Amount
        var straightAmount = parseFloat(document.getElementById("straight-amount").value);
        var rambleAmount = parseFloat(document.getElementById("ramble-amount").value);

        if (straightAmount <= 0 || rambleAmount <= 0) {
        event.preventDefault();
        const amountModal = new bootstrap.Modal(document.getElementById("amount-zero"));
        amountModal.show();
    }
    });
});