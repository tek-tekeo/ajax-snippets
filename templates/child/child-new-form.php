<?php //テンプレートフォーム
use AjaxSnippets\Common\CreateForm as CF;
use AjaxSnippets\Domain\Models\BaseModel;
use AjaxSnippets\Domain\Models\Base;
use AjaxSnippets\Domain\Models\Detail;
use AjaxSnippets\Domain\Models\Apps;

if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php
  $base_id = $_POST['base_id'];
  $item_name = $_POST['item_name'];
  $official_item_link = $_POST['official_item_link'];
  $detail_img = $_POST['img'];
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

    $data = array('id'=>'','base_id'=>$base_id,'item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link, 'detail_img'=>$detail_img, 'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id,'info' => $info, 'review' => $review, 'rchart' => $rchart);
    $format = array('%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s');
    $res = $wpdb->insert( $table, $data, $format );
    if($res){echo "商品ページ登録完了";
      $base_id = '';
      $item_name = '';
      $official_item_link = '';
      $amazon_asin = '';
      $rakuten_id = '';
      $affi_item_link = '';
      $detail_img = '';
    }
  }else{
    echo '更新が不完全です';
  }
  $prev_value = file_get_contents( dirname(__FILE__) .'/prev_anken.txt');
  ?>
<h1>小要素の新規登録</h1>
<p style="font-size:20px">
<a href="<?php echo admin_url('')."admin.php?page=child-config"; ?>">編集</a>
</p>

<form method="POST" action="">
  <table class="input_column2_table">
    <tbody><caption>個別の商品ページを登録する(A8のみ)</caption>
      <tr>
      <th>追加する案件</th>                               <td><?=CF::sqlSelectBox(PLUGIN_DB_PREFIX.'base', 'base_id', array('id','name'), $prev_value, 'required')?></td>
      </tr>
      <tr>
      <th>商品名（日本語）</th>                            <td><?=CF::textBox('item_name', $item_name, true)?></td>
      </tr>
      <tr>
      <th>商品ページURL</th>                              <td><?=CF::textBox('official_item_link', $official_item_link, true)?></td>
      </tr>
      <tr>
      <th>アフィリエイトのURL<br>(a8案件以外は必要になる)</th><td><input type="text" name="affi_item_link" value="<?php echo $affi_item_link;?>" readonly></td>
      </tr>
      <tr>
      <th>Amazonのasin</th>                             <td><?=CF::textBox('amazon_asin', $amazon_asin)?></td>
      </tr>
      <tr>
      <th>楽天のid(例：phiten:111111)</th>               <td><?=CF::textBox('rakuten_id', $rakuten_id)?></td>
      </tr>
      <tr>
      <th colspan=2><input type="submit" value="新規登録する" name="child_add" style="width:100%;padding:30px"></th>
      </tr>
      <tr>
      <th>アイテム別写真<br>（レビュー時などこちらを優先）</th><td><?=CF::imgUploadBox($detail_img)?></td>
      </tr>
      <tr>
      <th>テーブル情報<br>例){"料金":"500"}</th>           <td><?=CF::textBox('info', $info)?></td>
      </tr>
      <tr>
      <th>チャート情報</th>                               <td><?=CF::textBox('rchart', $rchart)?></td>
      </tr>
      <tr>
      <th>レビュー</th>                                  <td><?=CF::textAreaBox('review', $review, 'review-editor')?></td>
      </tr>
    </tbody>
  </table>
</form>
<style>
  table.input_column2_table{
    width:80%;
    border:solid 1px #000;
    padding:10px;
  }
  table.input_column2_table caption{
    font-weight:bold;
    font-size:20px;
  }
  table.input_column2_table tr td{
    padding:10px;
  }
</style>
