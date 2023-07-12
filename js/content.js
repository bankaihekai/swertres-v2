function showPayoutAnalysisContent() {
  $(".input-swertres-content").hide();
  $(".payout-analysis-content").show();
  // $(".choices").addClass("active");
  // $(".overlay-content").css("margin-top", "150px");
}

function showInputSwertresContent() {
  $(".input-swertres-content").show();
  $(".payout-analysis-content").hide();
  // $(".choices").removeClass("active");
  // $(".overlay-content").css("margin-top", "");
}

// Show the input-swertres-content by default
showInputSwertresContent();

$(document).ready(function () {
  $("#swertres-form").submit(function (e) {
    e.preventDefault();
    // Submit logic goes here

    // Show the success modal
    $("#successModal").modal("show");
  });
});

// function adjustTableHeight() {
//   var overlayHeight = $(".overlay-content").height();
//   var navbarHeight = $("nav.navbar").outerHeight();
//   var containerPadding = 20; // Adjust this value if needed
//   console.log(overlayHeight);
//   console.log(navbarHeight);
//   var tableContainerHeight = overlayHeight - navbarHeight - containerPadding ;
//   $("#table-container").height(tableContainerHeight);
// }

// $(window).resize(adjustTableHeight); // Call the function when the window is resized
// adjustTableHeight(); //
