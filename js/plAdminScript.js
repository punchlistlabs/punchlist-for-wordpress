(function ($) {
  $("#set-up-api").on("submit", (e) => {
    e.preventDefault();

    $.post(
      ajaxurl,
      {
        "api-key": $('input[name="api-key"]').val(),
        action: "pl_check_integration",
        _ajax_nonce: $("#plnonce").val(),
      },
      (data, status) => {
        alert(
          "API Integration Verified. Create Punchlist projects from the post editor."
        );
      }
    ).fail((err) => {
      alert(err.responseJSON.data.message);
    });
  });
})(this.jQuery);
