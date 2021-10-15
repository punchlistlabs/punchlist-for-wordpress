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
          nonce: $("#pl-create-project-edit-screen-nonce").val(),
          name: $(".editor-post-title__input").val() || null,
          action: "pl-create-project-edit-screen",
        },
        function (res) {
          console.dir(res.data.url);
          $("#pl-create-project-edit-screen").before(`<p>${res.data.url}</p>`);
          //const plProject = requestPunchlistProject(res.data.public_url);
          //console.log(plProject);
        }
      ).fail((err) => {
        // alert(err.responseJSON.data.message)
        console.dir(err);
      });
    }
  });
})(this.jQuery);
