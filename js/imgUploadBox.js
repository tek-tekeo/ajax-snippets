(function ($) {
  var custom_uploader;

  $("input:button[name='upladed_avatar_select']").click(function (e) {

    e.preventDefault();

    if (custom_uploader) {

      custom_uploader.open();
      return;

    }

    custom_uploader = wp.media({

      title: "画像を選択してください。",

      /* ライブラリの一覧は画像のみにする */
      library: {
        type: "image"
      },

      button: {
        text: "画像の選択"
      },

      /* 選択できる画像は 1 つだけにする */
      multiple: false

    });

    custom_uploader.on("select", function () {

      var images = custom_uploader.state().get("selection");

      /* file の中に選択された画像の各種情報が入っている */
      images.each(function (file) {

        /* テキストフォームと表示されたサムネイル画像があればクリア */
        $("input:text[name='img']").val("");
        $("#upladed_avatar_thumbnail").empty();

        /* テキストフォームに画像の URL を表示 */
        $("input:text[name='img']").val(file.attributes.sizes.full.url);

        /* プレビュー用に選択されたサムネイル画像を表示 */
        $("#upladed_avatar_thumbnail").append('<img src="' + file.attributes.sizes.full.url + '" />');

      });
    });

    custom_uploader.open();

  });

  /* クリアボタンを押した時の処理 */
  $("input:button[name='upladed_avatar_clear']").click(function () {

    $("input:text[name='img']").val("");
    $("#upladed_avatar_thumbnail").empty();

  });

})(jQuery);
