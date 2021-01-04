<?php
if ( !defined( 'ABSPATH' ) ) exit; ?>
	<h1>小要素の編集ページ</h1>
<a href="<?php echo admin_url('')."admin.php?page=child-config&action=add"; ?>">追加</a>
<?php
  echo '<h2>'.__( '名前', THEME_NAME ).'</h2>';
  generate_textbox_tag('child', $child, __( 'ゴリラクリニック', THEME_NAME ));

?>
<dl id="ajax-item-list" style="width:100%; height:300px;overflow-y: scroll;">

</dl>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
$(function () {

  $(document).ready( function(){
    ajaxLink();
    $('input:visible').eq(0).focus();
  });

   function ajaxLink(){
     var newText = $('#child').val();
         // Ajax通信を開始する

         $.ajax({
             type: "POST",
             data:{
                  'action':"getListChild",
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
   $(document).on('click','input[name="child_id"]',function(){
     var child_id = this.dataset.src;
     if(this.value == '変更'){
       var url = "<?php echo admin_url('')."admin.php?page=child-config&action=update&child_id="; ?>";
       window.location.href = url + child_id;
     }else if(this.value =='削除'){
      var result = confirm('削除を確定しますか？');
      if(result) {
        //はいを選んだときの処理
        $.ajax({
             type: "POST",
             data:{
                  'action':"deleteChild",
                  'id':child_id
             },
             url: ajaxurl,
             success: function(e) {
               console.log(e);
              ajaxLink();
             },
             error: function(e){
                 console.log('失敗');
             }
         });
      } else {
      //いいえを選んだときの処理
      }
     }
   });

   var timeout = null
   $("#child"). keydown(function(e) {
           if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
               return false;
           } else {
               return true;
           }
       });
  $('#child').keyup(function(){

    var text = this.value;
    clearTimeout(timeout);
    timeout = setTimeout(function() {
        // Do AJAX shit here
        ajaxLink();
    }, 500);
    });

});
</script>
