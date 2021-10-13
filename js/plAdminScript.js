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
          // send the key, save it, mark the site as integrated, do cool shit
        },
        error: (xhr, status, err) => {
          console.log(err);
        },
      }
    );
  });
})(this.jQuery);
