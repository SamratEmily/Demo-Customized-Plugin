jQuery(document).ready(function ($) {
  $("#add-order-note").on("submit", function (event) {
    event.preventDefault();

    var formData = $(this).serialize();
    $.ajax({
      url: task_ajax_object.ajax_url,
      type: "POST",
      data:
        formData + "&nonce=" + task_ajax_object.nonce + "&action=save_new_task",
      success: function (response) {
        if (response.success) {
          alert(response.data.message);
          location.reload();
        } else {
          alert(response.data.message);
        }
      },
      error: function () {
        alert("An error occurred while saving data.");
      },
    });
  });

  $("select[data-custom='custom']").on("change", function () {
    var value = $(this).val();
    var container = $(this).closest(".wporg_row");

    container.find(".pill-desc").hide();
    container.find(".pill-" + value).show();
  });
});
