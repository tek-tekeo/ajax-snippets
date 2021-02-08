<?php
if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php

$base_id = $_GET['base_id'];

if(is_numeric($base_id)){
  //base_idがGET送信されると、親要素も小要素も全て削除
  global $wpdb;
  $sql = "DELETE FROM ".PLUGIN_DB_PREFIX."base where id=".$base_id;
  $results = $wpdb->get_results($sql,object);
  $sql = "DELETE FROM ".PLUGIN_DB_PREFIX."detail where base_id=".$base_id;
  $results = $wpdb->get_results($sql,object);
  echo "削除が完了しました";
}
?>

<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets"; ?>">追加</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=edit"; ?>">編集</a>
</p>

<h1>親要素の削除ページ</h1>
<h2>名前</h2>
<input type="text" id="parent">
<dl id="ajax-item-list" style="width:100%; overflow-y: scroll;">

</dl>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
$(function () {
  $(document).ready( function(){
    ajaxLink();
    $('input:visible').eq(0).focus();
  });

   function ajaxLink(){
     var newText = $('#parent').val();
         // Ajax通信を開始する

         $.ajax({
             type: "POST",
             data:{
                  'action':"getListBase",
                  'name':newText
             },
             url: ajaxurl,
             success: function(e) {

               $alist = $('#ajax-item-list');
               $alist.empty();
               $alist.append(e);

             },
             error: function(e){
                 console.log('失敗');
             }
         });
         return false;
   }

   $(document).on('click','input[name="base_id"]',function(){
     var val = $("input[name='base_id']:checked");
     var base_id = val.val();
     var result = confirm('削除を確定しますか？');

      if(result) {
        //はいを選んだときの処理
         var url = "<?php echo admin_url('')."admin.php?page=ajax-snippets&action=delete&base_id="; ?>";
          window.location.href = url + base_id;
      } else {
      //いいえを選んだときの処理
      }
   });

   var timeout = null
   $("#parent"). keydown(function(e) {
           if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
               return false;
           } else {
               return true;
           }
       });
  $('#parent').keyup(function(){

    var text = this.value;
    clearTimeout(timeout);
    timeout = setTimeout(function() {
        // Do AJAX shit here
        ajaxLink();
    }, 500);
    });

});
</script>
