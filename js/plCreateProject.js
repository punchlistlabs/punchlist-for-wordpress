(function ($) {
  console.log("create project loaded");
  $("#pl-create-project-edit-screen").on("click", function (e) {
    if (typeof e !== "undefined") {
      e.preventDefault();
      e.stopPropagation();
      $.post(
        ajaxurl,
        {
          post_ID: $("#post_ID").val(),
          checked: true,
          name: $(".editor-post-title__input").val() || null,
          nonce: $("#pl-create-project-edit-screen-nonce").val(),
          action: "pl-create-project-edit-screen",
        },
        {
          success: (data, status) => {
            console.log(data);
          },
        }
      ).fail((err) => alert(err.responseJSON.data.message));
    }
  });
})(this.jQuery);
