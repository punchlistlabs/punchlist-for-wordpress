(function ($) {
  $("#create-quick-project").attr("href", localVars.qpUrl);

  $("#set-up-api").on("submit", (e) => {
    e.preventDefault();

    $.post(
      ajaxurl,
      {
        "api-key": $('input[name="api-key"]').val(),
        action: "pl_check_integration",
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
