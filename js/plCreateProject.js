(function ($) {
  console.dir(localVars);
  $("#pl-create-project-edit-screen").on("click", function (e) {
    if (typeof e !== "undefined") {
      e.preventDefault();
      e.stopPropagation();

      $.post(
        ajaxurl,
        {
          post_ID: $("#post_ID").val(),
          checked: true,
          _ajax_nonce: $("#plnonce").val(),
          name: $(".editor-post-title__input").val() || null,
          action: "pl-create-project-edit-screen",
        },
        function (res) {
          $("#pl-create-project-edit-screen").text("Creating project...");
          setTimeout(() => {
            $("#pl-create-project-edit-screen")
              .text("Go To Punchlist Project")
              .removeClass("button-primary")
              .addClass("button-secondary")
              .attr({ href: res.data.url, target: "_blank" })
              .off("click");
          }, 1000);
        }
      ).fail((err) => {
        alert(err.responseJSON.data.message);
      });
    }
  });

  $(document).ready(() => {
    $projectSelect = $('#pl-create-project-get-projects');
    $.post(ajaxurl, {
      action: "pl_get_projects",
      _ajax_nonce: $("#plnonce2").val(),
    },(res, status) => {
      const $projectSelect = $("#pl-add-to-project-select");
      res.data.forEach((p) => {
        $projectSelect.append($("<option></option>").val(p.id).text(p.name));
      });
    });
  });

})(this.jQuery);
