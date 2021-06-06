(function ($) {
  $(document).ready(function () {
    console.log("js ok");

    let folder_path;

    $("#folder_path_submit").click(function (event) {
      event.preventDefault();
      //   var post_id = $(".comments").attr("data-post-id");
      folder_path = $("#folder-path-input").val();

      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "save_folder_path",
          folder_path: folder_path,
        },
      })
        .done(function (response) {
          // TODO
          // si 1 ok si 0 raté
          console.log(response);
        })
        .fail(function (error) {
          console.log(error);
        });
    });

    $(".phototeque__import__submit").click(function () {
      folder_path = $("#folder-path-input").val();
      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "search_in_folder",
          folder_path: folder_path,
        },
      })
        .done(function (response) {
          console.log(response);
          if (response.data.status === "422") {
            // Show error message
            $(".phototeque__import__message").css("visibility", "inherit");
            $(".phototeque__import__message").css("color", "red");
            $(".phototeque__import__message").text(response.data.message);
            // Hide images
            $("div.photos-container").empty();
          } else {
            // Show count images message and images
            $(".phototeque__import__message").css("visibility", "inherit");
            $(".phototeque__import__message").css("color", "black");
            $(".phototeque__import__message").text(response.data.message);

            let photosArray = [];
            Object.values(response.data.images).forEach((element) => {
              let photo =
                `<img src="` +
                element.path +
                `"alt="` +
                element.name +
                `" class="photo-item">`;
              photosArray.push(photo);
            });

            $("div.photos-container").append(photosArray);
          }
        })
        .fail(function (error) {
          console.log(error);
        });
    });
  });
})(jQuery);
