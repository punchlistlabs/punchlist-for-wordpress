(function ($) {
  $("#create-quick-project").attr("href", localVars.qpUrl);

  // $(document).on("load", () => {
  //   $projectSelect = $('#set-default-project');
  //   $.post(ajaxurl, {
  //     action: "pl_get_projects",
  //   },(res, status) => {
  //     res.data.forEach((p) => {
  //       $projectSelect.append($("<option></option>").val(p.id).text(p.name));
  //     });
  //   });
  // });

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
