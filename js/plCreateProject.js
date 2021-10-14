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
          nonce: $("#pl-create-preview-edit-screen-nonce").val(),
          action: "pl-create-preview-edit-screen",
        },
        function (res) {
          console.dir(res);
          //const plProject = requestPunchlistProject(res.data.public_url);
          //console.log(plProject);
        }
      ).fail((err) => {
        // alert(err.responseJSON.data.message)
        console.dir(err);
      });

      //requestPunchlistProject("google.com");
    }
  });

  const requestPunchlistProject = (url) => {
    let form = new FormData();
    form.append("domain", url);
    form.append("name", $(".editor-post-title__input").val() || null);
    form.append("type", "web");

    const settings = {
      url: localVars.plUrl + "/projects",
      method: "POST",
      timeout: 0,
      headers: {
        Authorization: "Bearer " + localVars.plApiKey,
      },
      processData: false,
      mimeType: "multipart/form-data",
      contentType: false,
      data: form,
    };

    $.ajax(settings)
      .done(function (res) {
        console.log(response);
      })
      .fail((err) => console.log(err));
  };
  //   axios
  //     .post(
  //       localVars.plUrl + "/projects",
  //       {
  //         name: $(".editor-post-title__input").val() || null,
  //         domain: url,
  //         type: "web",
  //       },
  //       {
  //         headers: {
  //           "content-type": "text/json",
  //         },
  //       }
  //     )
  //     .then((res) => console.log(res))
  //     .catch((err) => console.log(err));
  // };

  // const requestPunchlistProject = (url) =>
  //   $.ajax({
  //     url: localVars.plUrl + "/projects",
  //     type: "POST",
  //     headers: {
  //       Authorization: "Bearer " + localVars.plApiKey,
  //       "Access-Control-Request-Headers": "x-requested-with",
  //       "Access-Control-Allow-Origin": "*",
  //     },
  //     contentType: "application/json",
  //     dataType: "json",
  //     data: {
  //       name: $(".editor-post-title__input").val() || null,
  //       domain: url,
  //       type: "web",
  //     },
  //     success: (res) => {
  //       console.dir(res);
  //     },
  //     error: (err) => {
  //       console.log(err);
  //     },
  //   });
})(this.jQuery);
