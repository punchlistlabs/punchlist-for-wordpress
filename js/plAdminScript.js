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
      {
        success: (data, status) => {
          alert(
            "API Integration Verified. Create Punchlist projects from the post editor."
          );
        },
        error: (xhr, status, err) => {
          console.log(err);
        },
      }
    );
  });
})(this.jQuery);
