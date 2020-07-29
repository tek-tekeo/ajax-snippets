<?php //テンプレートフォーム

if ( !defined( 'ABSPATH' ) ) exit; ?>

<form name="form1" method="post" action="">
  <?php
  global $wpdb;
  $name = $_POST['name'];
  $anken = $_POST['anken'];
  $affi_link = $_POST['affi_link'];
  $affi_img = $_POST['affi_img'];
  $img_tag = $_POST['img_tag'];
  $official_link = $_POST['official_link'];
  $info = stripslashes(nl2br($_POST['info']));
  $review = stripslashes($_POST['review']);
  $rchart = stripslashes($_POST['rchart']);

  if(isset($_POST['name']) && $_POST['affi_link']){
    $name = $_POST['name'];
    $anken = $_POST['anken'];
    $affi_link = $_POST['affi_link'];
    $affi_img = $_POST['affi_img'];
    $img_tag = $_POST['img_tag'];
    $official_link = $_POST['official_link'];
    $info = stripslashes(nl2br($_POST['info']));
    $review = stripslashes($_POST['review']);
    $rchart = stripslashes($_POST['rchart']);
//    $review = stripslashes($review);

    $table = PLUGIN_DB_PREFIX.'base';

    $data = array('name'=>$name, 'anken'=>$anken, 'affi_img' => $affi_img, 'affi_link' => $affi_link, 'img_tag' => $img_tag, 'info' => $info, 'review' => $review, 'rchart' => $rchart);
    //$format = array('%d','%d','%d','%s','%f');

    $res = $wpdb->insert( $table, $data );
    if($res){
        $base_id = $wpdb->insert_id;
        $table = PLUGIN_DB_PREFIX.'detail';
        $affi_item_link =$affi_link;//トップはアフィリンク共通
        $data = array('id'=>'','base_id'=>$base_id,'item_name'=>'トップ','official_item_link'=>$official_link,'affi_item_link'=>$affi_item_link,'amazon_asin'=>'','rakuten_id'=>'');
        print_r($data);
        $res1 = $wpdb->insert( $table, $data);
        if($res1){
          echo "<p style='color:red'>DETAILトップの登録できました</p>";
          $name ="";
          $anken ="";
          $affi_link ="";
          $affi_img ="";
          $img_tag ="";
          $official_link ="";
          $info ="";
          $review ="";
          $rchart ="";
        }else{
          echo "<p style='color:red'>個別だけミスったぽい</p>";
        }
      echo "<h1 class='red'>BASEの入力完了！</h1>";
    }else{
      echo "<h1 class='red'>変更なし</h1>";
    }
  }else{
    echo "入力不備";
  }
  echo '<h1>親要素(base)の新規追加フォーム</h1>';

  echo '<h2>'.__( '名前', THEME_NAME ).'</h2>';
  generate_textbox_tag('name', $name, __( 'ゴリラクリニック', THEME_NAME ));

  echo '<h2>'.__( '案件コード', THEME_NAME ).'</h2>';
  generate_textbox_tag('anken', $anken, __( 'gorilla-hige', THEME_NAME ));

  echo '<h2>'.__( 'アフィリンク(メイン)  ', THEME_NAME ).'</h2>';
  generate_textbox_tag('affi_link', $affi_link, __( 'https://t.afi-b.com/visit.php?guid=ON&a=E58490-s346143Q&p=1638166I', THEME_NAME ));

  echo '<h2>'.__( 'バナーの画像', THEME_NAME ).'</h2>';
  generate_textbox_tag('affi_img', $affi_img, __( 'https://gorilla.clinic/', THEME_NAME ));

  echo '<h2>'.__( 'バナーの幅', THEME_NAME ).'</h2>';
  generate_textbox_tag('img_width', $img_width, __( '300', THEME_NAME ));

  echo '<h2>'.__( 'バナーの高さ', THEME_NAME ).'</h2>';
  generate_textbox_tag('img_height', $img_height, __( '250', THEME_NAME ));

  echo '<h2>'.__( 'アフィイメージタグ', THEME_NAME ).'</h2>';
  generate_textbox_tag('img_tag', $img_tag, __( '', THEME_NAME ));

  echo '<h2>'.__( '公式サイトのURL', THEME_NAME ).'</h2>';
  generate_textbox_tag('official_link', $official_link, __( 'https://gorilla.clinic/', THEME_NAME ));

  echo '<h2>'.__( 'テーブル情報', THEME_NAME ).'</h2>';
  generate_textarea_tag('info', $info, __( '{"効果": 5, "安さ": 2, "実績": 4, "サービス": 5, "通いやすさ": 5}', THEME_NAME ));

  echo '<h2>'.__( 'チャート情報', THEME_NAME ).'</h2>';
  generate_textbox_tag('rchart', $rchart, __( '{"効果": 5, "安さ": 2, "実績": 4, "サービス": 5, "通いやすさ": 5}', THEME_NAME ));

  generate_visuel_editor_tag('review', $review,  'review-text');
  echo "<h1>名前、バナー、アフィリンクは最低必要</h1>";
?>
<input type="submit" value="新規挿入">
</form>


<h1>個別の商品ページを登録する(A8のみ)</h1>
<p>商品別</p>
      <?php
      echo '<form action="" method="post" name="form2">';
      echo "<select name='base_id'>";
      $records = get_db_table_records(PLUGIN_DB_PREFIX.'base','');
      foreach($records as $r){
        echo "<option value={$r->id}>{$r->name}</option>";
      }
      　?>
</select>
<p><label>商品名（日本語）：<input type="text" name="item_name" size="40"></label></p>
<p><label>商品ページURL：<input type="text" name="official_item_link" size="150"></label></p>
<p><label>アフィリエイトのURL：<input type="text" name="affi_item_link" size="150"></label></p>
<p><label>Amazonのasin：<input type="text" name="amazon_asin" size="150"></label></p>
<p><label>楽天のid(例：phiten:111111)：<input type="text" name="rakuten_id" size="150"></label></p>
<p><input type="submit" value="送信"></p>
</form>
      <?php

      $base_id = $_POST['base_id'];
      $item_name = $_POST['item_name'];
      $official_item_link = $_POST['official_item_link'];
      $amazon_asin = $_POST['amazon_asin'];
      $rakuten_id = $_POST['rakuten_id'];
			$affi_item_link = $_POST['affi_item_link'];
      if($base_id && $item_name){

        global $wpdb;
				$table = PLUGIN_DB_PREFIX.'base';

				$sql = "SELECT B.a8_shohin FROM {$table} as B WHERE B.id = {$base_id}";
				$results = $wpdb->get_results($sql,object);
				// 結果を表示
				foreach( $results as $result ) {
					$a8_shohin= $result->a8_shohin;
				}

				//場合ワケ
				if($a8_shohin != ""){
					//a8の場合
					if($official_item_link == ""){
						echo "a8の商品リンク作成に必要な公式リンクを入力していない";die;
					}else{
						//a8の商品リンクが生成
						$affi_item_link = $a8_shohin."&a8ejpredirect=" . urlencode($official_item_link);
					}
				}else{
					//a8以外はしっかりとURLを書く必要がある
					if(!$affi_item_link){
						echo "a8以外の案件なのに、商品別のリンクを入力できていない";die;
					}
				}


        $table = PLUGIN_DB_PREFIX.'detail';

        $data = array('id'=>'','base_id'=>$base_id,'item_name'=>$item_name,'official_item_link'=>$official_item_link,'affi_item_link'=>$affi_item_link,'amazon_asin'=>$amazon_asin,'rakuten_id'=>$rakuten_id);
        $format = array('%d','%d','%s','%s','%s','%s','%s');
        $res = $wpdb->insert( $table, $data, $format );
        if($res){echo "商品ページ登録完了";}
      }
