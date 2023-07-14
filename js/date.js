$(document).ready(function() {
    updateTime();
  });

  function updateTime() {
    $.ajax({
      url: 'db/date.php',
      success: function(data) {
        $('#time').html(data);
        setTimeout(updateTime, 1000); // Update every second
      }
    });
  }

  