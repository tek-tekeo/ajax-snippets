<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>
<h1>小要素の新規登録</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>">編集</a>
</p>
<h1>個別の商品ページを登録する(A8のみ)</h1>
<p>商品別</p>
<?php
  $base_id = $_POST['base_id'];
  $item_name = $_POST['item_name'];
  $official_item_link = $_POST['official_item_link'];
  $amazon_asin = $_POST['amazon_asin'];
  $rakuten_id = $_POST['rakuten_id'];
  $affi_item_link = $_POST['affi_item_link'];
  $info = stripslashes(nl2br($_POST['info']));
  $review = stripslashes($_POST['review']);
  $rchart = stripslashes($_POST['rchart']);

  if($base_id !='' && $item_name !='' && $official_item_link !=''){

    file_put_contents(dirname(__FILE__) .'/prev_anken.txt', $base_id);

    global $wpdb;
    $table = PLUGIN_DB_PREFIX.'base';

    $sql = "SELECT B.asp_name FROM {$table} as B WHERE B.id = {$base_id}";
    $results = $wpdb->get_results($sql,object);
    // 結果を表示
    foreach( $results as $result ) {
      $asp_name= $result->asp_name;
    }

    //場合ワケ
    if($asp_name != ""){
      //a8の場合
      if($official_item_link == ""){
        echo "a8の商品リンク作成に必要な公式リンクを入力していない";die;
      }else{
        //a8の商品リンクが生成
        $affi_item_link = "A8案件なので不要";
      }
    }else{
      //a8以外はしっかりとURLを書く必要がある
      if(!$affi_item_link){
        echo "a8以外の案件なのに、商品別のリンクを入力できていない";die;
      }
    }


    $table = PLUGIN_DB_PREFIX.'detail';

    $data = array('id'=>'','base_id'=>$base_id,'item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link,'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id,'info' => $info, 'review' => $review, 'rchart' => $rchart);
    $format = array('%d','%d','%s','%s','%s','%s','%s','%s','%s','%s');
    $res = $wpdb->insert( $table, $data, $format );
    if($res){echo "商品ページ登録完了";
      $base_id = '';
      $item_name = '';
      $official_item_link = '';
      $amazon_asin = '';
      $rakuten_id = '';
      $affi_item_link = '';
    }
  }else{
    echo '更新が不完全です';
  }
  $prev_value = file_get_contents( dirname(__FILE__) .'/prev_anken.txt');
  ?>
<form method="POST" action="">
<?=CF::sqlSelectBox(PLUGIN_DB_PREFIX.'base', 'base_id', array('id','name'), $prev_value, 'required')?>
<p>商品名（日本語）：<input type="text" name="item_name" size="40" value="<?php echo $item_name;?>"></p>
<p>商品ページURL：<input type="text" name="official_item_link" size="150" value="<?php echo $official_item_link;?>"></p>
<p>アフィリエイトのURL(a8案件以外は必要になる)：<input type="text" name="affi_item_link" size="150" value="<?php echo $affi_item_link;?>" readonly></p>
<p>Amazonのasin：<input type="text" name="amazon_asin" size="150" value="<?php echo $amazon_asin;?>"></p>
<p>楽天のid(例：phiten:111111)：<input type="text" name="rakuten_id" size="150" value="<?php echo $rakuten_id;?>"></p>
<p><input type="submit" value="送信"></p>
<h2>テーブル情報</h2>
<?=CF::textBox('info', $info)?>
<h2>チャート情報</h2>
<?=CF::textBox('rchart', $rchart)?>
<h2>レビュー</h2>
<?=CF::textAreaBox('review', $review, 'review-editor')?>
</form>
