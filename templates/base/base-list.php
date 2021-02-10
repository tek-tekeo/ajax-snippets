<?php

if ( !defined( 'ABSPATH' ) ) exit; ?>
<h1>親要素の編集を選択する</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=add"; ?>">追加ページへ</a>
<a href="<?php echo admin_url('')."admin.php?page=ajax-snippets&action=delete"; ?>">削除ページへ</a>
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>" style="font-size:20px;">小要素一覧ページへ</a>
</p>
<h2>名前を入力</h2>
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
     var url = "<?php echo admin_url('')."admin.php?page=ajax-snippets&action=update&base_id="; ?>";
     window.location.href = url + base_id;
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
