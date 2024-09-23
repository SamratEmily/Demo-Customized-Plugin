jQuery(document).ready(function ($) {
  $("#add-order-note").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = $(this).serialize(); // Serialize form data

    $.ajax({
      url: task_ajax_object.ajax_url,
      type: "POST",
      data:
        formData + "&nonce=" + task_ajax_object.nonce + "&action=save_new_task",
      success: function (response) {
        if (response.success) {
          // Display success message
          alert(response.data.message);
          // Optionally refresh part of the page or update content
          location.reload(); // Reload the page to see the changes
        } else {
          // Display error message
          alert(response.data.message);
        }
      },
      error: function () {
        alert("An error occurred while saving data.");
      },
    });
  });
});
