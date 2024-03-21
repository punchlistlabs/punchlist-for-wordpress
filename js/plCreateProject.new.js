(function ($) {
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
          name: $(".editor-post-title__input").get(0).innerText || 'Punchlist for WordPress',
          action: "pl_create_project_edit_screen",
        },
        function (res) {
          $("#pl-create-project-edit-screen").text("Creating project...");
          setTimeout(() => {
            $("#pl-create-project-edit-screen")
              .text("Go To Punchlist Project")
              .removeClass("button-primary")
              .addClass("button-secondary")
              .attr({ href: res.data.url, target: "_blank", id: "go-to-pl-project" })
              .off("click");
          }, 1000);
        }
      ).fail((err) => {
        alert(err.responseJSON.data.message);
      });
    }
  });

  $("#pl-add-to-project").on("click", function (e) {
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
          action: "pl_add_to_project_edit_screen",
          project_id: $('#pl-add-to-project-select').val()
        },
        function (res) {
          $("#pl-add-to-project").text("Adding page to project...");
          setTimeout(() => {
            $("#pl-add-to-project")
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
      res.data.items.forEach((p) => {
        $projectSelect.append($("<option></option>").val(p.id).text(p.name));
      });
    });
  });

  $('#pl-add-to-project-select').change((e) => {
    $('#pl-add-to-project').css('display', 'inline-block').text(`Add to Project ${e.target.selectedOptions[0].text}`);
  })

})(this.jQuery);
