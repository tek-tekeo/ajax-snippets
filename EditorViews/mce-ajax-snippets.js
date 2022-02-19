(function ($, document, window) {
  var path = $('#templateDirectory').val();

  tinymce.PluginManager.add('ajax_snippets', function (editor, url) {
    var dropdownValues = [];
    editor.addCommand('mce_ajax_snippets', function () {
      tinymce.activeEditor.windowManager.open({
        title: "文字入力で案件を抽出",
        url: '../wp-content/plugins/ajaxSnippets/EditorViews/mce-ajax-snippets.php',
        width: 600,
        height: 500,
      }, {
        custom_param: 1
      });
    });

    // Register example button
    editor.addButton('ajax_snippets', {
      title: 'Ajax Snippets',
      cmd: 'mce_ajax_snippets',
      //onclick:pluginWin,
      text: 'アフィリンク',
      icon: false,
      fixedWidth: true
    });
  });
})(jQuery, document, window);

