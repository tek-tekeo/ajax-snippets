<?php
if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php
  echo '<h2>'.__( '名前', THEME_NAME ).'</h2>';
  generate_textbox_tag('parent', $parent, __( 'ゴリラクリニック', THEME_NAME ));

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
     var url = "<?php echo admin_url('')."admin.php?page=base-config&action=edit&base_id="; ?>";
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
