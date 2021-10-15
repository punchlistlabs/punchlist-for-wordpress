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
          }, 5000);
        }
      ).fail((err) => {
        // alert(err.responseJSON.data.message)
        console.dir(err);
      });
    }
  });
})(this.jQuery);
