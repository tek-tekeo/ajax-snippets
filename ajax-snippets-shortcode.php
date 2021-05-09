<?php
use AjaxSnippets\Common\AffiLinkForm as AF;
//ショートコード 集
//基本となるリンク作成
function AjaxSniShortcodeLink($atts, $content = null) {
    extract( shortcode_atts( array(
       'id' => '1',
       'noaf' => '0',
        're_url'=>'0'
    ), $atts ) );
    global $wpdb;
    $sql = "SELECT B.name, B.asp_name, B.affi_link, B.affi_img, B.img_tag, B.s_link, B.s_img_tag, D.id, D.item_name,D.affi_item_link, D.official_item_link FROM ".PLUGIN_DB_PREFIX."base As B RIGHT JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

    $results = $wpdb->get_results($sql,object);

        if(count($results) == 0){
            $rep = "リンクエラー";
        }
    //アフィリエイトリンクにするかどうか？『0:しない』『1:する』
    $all_affi = 1;
            // 結果を表示
            foreach($results as $r){
                $url = "";
                if($noaf == 1){
                    $url = $r->official_item_link;
                }else{
                    if($all_affi == 0){
                        $url = $r->official_item_link;
                    }else{
                      if($r->item_name == "000" || $r->affi_item_link=="top"){
                        $url = $r->affi_link;
                      }else{
                        if(!empty($r->s_img_tag)){
                          $r->img_tag = $r->s_img_tag;
                        }
                        if($r->asp_name == "a8"){
                          $url = $r->s_link."&a8ejpredirect=" . urlencode($r->official_item_link);
                        }else if($r->asp_name == "dmm"){
                          $url = $r->affi_item_link;
                        }else{
                          $url = $r->affi_item_link;
                        }
                      }
                    }
                }
                //urlを返す場合
                if($re_url == 1){
                    return $url;
                }
                //テキストなしリンク
                if(empty($content)){
									//バナーあり
										if (!empty($r->affi_img)) {
										 // code...
$rep .= <<<EOT
<a href="{$url}" rel="nofollow"><img border="0" width="300" height="250" alt="" src="{$r->affi_img}"></a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
									}else{
										//バナーもなし。URLで返す
$rep .= <<<EOT
<a href="{$url}" rel="nofollow"> {$r->official_item_link}</a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
									}
}else{
	//テキストありリンク

$rep .= <<<EOT
<a href="{$url}" rel="nofollow">{$content}</a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
                }
            }
    return $rep;
}
function AjaxRecordShortcodeLink($atts, $content = null) {
    extract( shortcode_atts( array(
       'id' => '1',
       'pl' => '0',
       'ntab' => '0',
       'btn_color'=>''
    ), $atts ) );

    $info = AF::getAffiInfo($id, 0);
    $tagStart = $btn_color == "" ? '<span class="ajaxSnippetsAffiliateLink">':'<div class="btn-wrap btn-wrap-'.$btn_color.' btn-wrap-l ajaxSnippetsAffiliateLink">';
    $tagEnd = $btn_color == "" ? '</span>':'</div>';
  if(empty($content)){
$rep =<<<EOT
<affiliate-link title=" {$info['official_item_link']}" affiurl="{$info['url']}" place="{$pl}" id="{$id}"></affiliate-link>
EOT;
  }else{
    $content = esc_html($content);
$rep =<<<EOT
<affiliate-link title="{$content}" affiurl="{$info['url']}" place="{$pl}" id="{$id}"></affiliate-link>
EOT;
  }
if(!empty($info['img_tag'])){
$rep .=<<<EOT
<img border="0" width="1" height="1" src="{$info['img_tag']}">
EOT;
}
$rep = $tagStart . $rep . $tagEnd;

  wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js');
  wp_enqueue_script( 'axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js');
  wp_enqueue_script( 'vueClick', plugins_url('ajax-snippets/js/vueClick.js'));
  return $rep;
  die();
    global $wpdb;
    $sql = "SELECT B.anken, B.img_tag, D.official_item_link FROM ".PLUGIN_DB_PREFIX."base As B INNER JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

    $results = $wpdb->get_results($sql,object);
        if(count($results) == 0){
            $rep = "リンクエラー";
        }
      foreach($results as $r){
        //$url = home_url() . "/link/".$r->anken . "?no={$id}&pl={$pl}";
        $url = home_url() ."/". $r->anken . "?no={$id}&pl={$pl}";
        if($ntab == 0){
          $a_tab = "rel=\"nofollow\"";
        }else{
          $a_tab = "rel=\"nofollow noopener\" target=\"_blank\"";
        }
        $info = AF::getAffiInfo($id, 0);
        //テキストなしリンク
        if(empty($content)){
$rep .= <<<EOT
<a href="{$info['url']}" {$a_tab}> {$r->official_item_link}</a><img border="0" width="1" height="1" src="{$info['img_tag']}">
EOT;
        }else{
$rep .= <<<EOT
<a href="{$info['url']}" {$a_tab}>{$content}</a><img border="0" width="1" height="1" src="{$info['img_tag']}">
EOT;
        }
}
    return $rep;
}

function AjaxRecordShortcodeBanner($atts) {
    extract( shortcode_atts( array(
       'id' => '1',
       'pl' => '0',
       'ntab'=> '0'
    ), $atts ) );
    $info = AF::getAffiInfo($id, 0);

$rep =<<<EOT
<span class="ajaxSnippetsAffiliateLink">
<affiliate-banner-link title="{$info['affi_img']}" affiurl="{$info['url']}" place="{$pl}" id="{$id}"></affiliate-banner-link>
EOT;
if(!empty($info['img_tag'])){
$rep .=<<<EOT
<img border="0" width="1" height="1" src="{$info['img_tag']}">
EOT;
}
$rep .='</span>';

  wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js');
  wp_enqueue_script( 'axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js');
  wp_enqueue_script( 'vueClick', plugins_url('ajax-snippets/js/vueClick.js'));
  return $rep;
  die();
    global $wpdb;
    $sql = "SELECT B.anken, B.img_tag,B.affi_img, D.detail_img FROM ".PLUGIN_DB_PREFIX."base As B INNER JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

    $results = $wpdb->get_results($sql,object);
        if(count($results) == 0){
            $rep = "リンクエラー";
        }
      foreach($results as $r){
        $url = home_url() . "/".$r->anken . "?no={$id}&pl={$pl}";
        if($ntab == 0){
          $a_tab = "rel=\"nofollow\"";
        }else{
          $a_tab = "rel=\"nofollow noopener\" target=\"_blank\"";
        }
        if($r->detail_img != ""){
          $r->affi_img = $r->detail_img;
        }
        $info = AF::getAffiInfo($id, 0);

$rep .= <<<EOT
<a href="{$info['url']}" {$a_tab}><img border="0" width="300" height="250" alt="" src="{$info['affi_img']}"></a>
EOT;
        if(!empty($info['img_tag'])){
$rep .= <<<EOT
<img border="0" width="1" height="1" src="{$info['img_tag']}">
EOT;
        }
        }
    return $rep;
}

add_shortcode('afLink', 'AjaxSniShortcodeLink');
add_shortcode('afRecord', 'AjaxRecordShortcodeLink');
add_shortcode('afRecordBanner', 'AjaxRecordShortcodeBanner');

function SingleReview($atts, $content = null){
  extract( shortcode_atts( array(
     'detail_id' => '1',
     'color'=>'blue',
     'title'=>'0',
     'is_review' => '0'
  ), $atts ) );
  global $wpdb;
  $sql = "SELECT D.*,B.name, B.affi_link FROM ".PLUGIN_DB_PREFIX."base as B,".PLUGIN_DB_PREFIX."detail as D where D.id={$detail_id} AND B.id=D.base_id";
  $list = $wpdb->get_results( $sql, object);

  foreach($list as $l){

  $chart_ele = json_decode($l->rchart, true);

  if(count((array)$chart_ele) >= 3){
    $chart_rate = implode(",", array_column( $chart_ele, 'value' ));
    $chart_factor = implode(",",array_column( $chart_ele, 'factor' ));
    if($l->detail_img != "" && $l->item_name !="トップ"){
      $l->name = $l->item_name;
    }
    $chart_str = "[ajax_snippets_rchart factor='".$chart_factor."' rate='".$chart_rate."' name='{$l->name}' color='".$color."' title='".$title."']";
  }else{
    $chart_str ="";
  }

  $l->info = json_decode($l->info, true);//wpautop(stripslashes_deep());
  $l->review = wpautop(stripslashes_deep($l->review));
  foreach((array)$l->info as $key => $d){
    $d['value'] = nl2br(stripslashes_deep($d['value']));
$table_elements .=<<<EOT
<tr>
<th>{$d['factor']}</th><td>{$d['value']}</td>
</tr>
EOT;
  }
  if($l->is_show_url){
$isShowUrl =<<<EOT
<tr>
<th>公式サイト</th>
<td>[afRecord id={$detail_id} pl=single]{$l->official_item_link}[/afRecord]</td>
</tr>
EOT;
  }

$rep .=<<<EOT
    <div class="singleReview">
      <div class="firstItem">[afRecordBanner id={$l->id} pl=singleImg]</div>
      <div class="secondItem">
        {$chart_str}
      </div>
    </div>
    <div class="scrollable-table scroll-hint" style="position: relative; overflow: auto;">
      <table class="simple-table">
      <tbody>
      {$table_elements}
      {$isShowUrl}
      </tbody>
      </table>
      <div class="scroll-hint-icon-wrap" data-target="scrollable-icon">
        <span class="scroll-hint-icon">
          <div class="scroll-hint-text">スクロールできます</div>
        </span>
      </div>
    </div>
EOT;
if($is_review) $rep .=$l->review;
if ( current_user_can('administrator') || current_user_can('editor') || current_user_can('author')){
    $kono_url = admin_url('')."admin.php?page=child-config&action=update&child_id={$l->id}";
    $rep .= "<p><a href={$kono_url} target='_blank'>この案件を編集</a>(管理者向け)</p>";
}
  }

  $rep = do_shortcode($rep);
  return $rep;
}
add_shortcode('singleReview', 'SingleReview');

//レーダーチャートの関数
function Radercahrt($atts, $content = null){
  extract( shortcode_atts( array(
     'name' => 'コイツ',
     'factor' => 'aaa,bbb,ccc,ddd,eee',
     'rate' => '3,3,3,3,3',
     'color'=>'blue',
     'title'=>'0'
  ), $atts ) );
if($color == 'blue'){
$rgb = "54, 162, 232";
}else if($color == 'red'){
  $rgb = "247, 133, 133";
}else if($color == 'gray'){
  $rgb = "128, 128, 128";
}else if($color == 'orange'){
  $rgb = "255, 155, 0";
}
  $factor = explode(",", $factor);
  foreach($factor as $f){
    $ff .= "\"".$f."\"";
    if(next($factor)){
      $ff .= ",";
      }
  }
//タイトル指名があれば、名前を変更
  if($title != '0'){
    $name = $title;
  }
  $radar_id=mt_rand();
$rep =<<<EOT
<div id="canvas_wapper_{$radar_id}" style="margin-bottom:0;max-height:400px">
<canvas id="canvas_{$radar_id}" width=400 height=400></canvas>
</div>
<script type="text/javascript">
$(function() {
  // スクロール処理
  var pieGraphAnim_{$radar_id} = function() {
      var wy = window.pageYOffset;
          wb = wy + screen.height - 300; // ウィンドウの最下部位置を取得
          // 各チャートの位置を取得
          chartElPos{$radar_id} = wy + chartEl{$radar_id}.getBoundingClientRect().top;

      // チャートの位置がウィンドウの最下部位置を超えたら起動
      if ( wb > chartElPos{$radar_id} && chartFlag{$radar_id} == false ) {
          chart{$radar_id}Func();
          chartFlag{$radar_id} = true;
      }
  };

  window.addEventListener('load', pieGraphAnim_{$radar_id}); // 読み込み時の処理
  window.addEventListener('scroll', pieGraphAnim_{$radar_id}); // スクロール時の処理

var chartEl{$radar_id} = document.getElementById("canvas_{$radar_id}");
chartFlag{$radar_id} = false;
var chart{$radar_id}Func = function() {
var ctx_{$radar_id} = chartEl{$radar_id}.getContext('2d');
var options = {
  legend: {
            display: false
         },
  title: {
            display: false,
            text: '{$name}の評価'
        },
    scale: {
     angleLines: {
            display: true
        },
        gridLines: {
                    display: true,
                },
        ticks: {
            max: 5,
            min: 0,
            stepSize: 1
        }
    },
　elements:{
　　line:{
　　　tension:0,
　　　borderWidth:3
　　}
　}
};

 chart_{$radar_id} = new Chart(ctx_{$radar_id}, {
    type: 'radar',
    data: {
      labels: [{$ff}],
      datasets: [{label:"評価値",
        data: [{$rate}],
        fill:true,
        backgroundColor:"rgba({$rgb}, 0.2)",
        borderColor:"rgb({$rgb})",
        pointBackgroundColor:"rgb({$rgb})",
        pointBorderColor:"#fff",
        pointHoverBackgroundColor:"#fff",
        pointHoverBorderColor:"rgb({$rgb})"
      }]
    },
    options:options
  });
}
});
</script>
EOT;
return $rep;
}
add_shortcode('ajax_snippets_rchart', 'Radercahrt');

function appLinkGenerater($atts) {
  extract( shortcode_atts( array(
  'detail_id' =>'1',
  'noaffi' =>'0'
  ), $atts ) );
  global $wpdb;
//小要素のIDから親要素のアプリリンクを生成する。WEBの場合はWEBリンクを生成する

  $sql = "SELECT base_id FROM ".PLUGIN_DB_PREFIX."detail where id={$detail_id}";
  $res = $wpdb->get_results($sql,object);
  foreach ($res as $key => $r) {
    $base_id = $r->base_id;
  }
//ユーザーエージェントの取得
  $ua = $_SERVER['HTTP_USER_AGENT'];
  if ( preg_match('/Android/ui', $ua) ) {
    //Android系の端末
    $sql ="SELECT A.app_id, B.name, A.img, A.dev, A.android_link as link, A.android_affi_link as affi_link FROM ".PLUGIN_DB_PREFIX."apps as A, ".PLUGIN_DB_PREFIX."base as B where A.app_id={$base_id} AND B.id=A.app_id";
$src = <<<EOT
<img style="height: 40px; width: 135px;" src="https://nabettu.github.io/appreach/img/gplay_ja.png" alt="" />
EOT;
  }else if ( preg_match('/iPhone|iPod|iPad/ui', $ua) ) {
    //iOS系の端末
    $sql ="SELECT A.app_id, B.name, A.img, A.dev, A.ios_link as link, A.ios_affi_link as affi_link FROM ".PLUGIN_DB_PREFIX."apps as A, ".PLUGIN_DB_PREFIX."base as B where A.app_id={$base_id} AND B.id=A.app_id";
$src = <<<EOT
<img style="height: 40px; width: 135px;" src="https://nabettu.github.io/appreach/img/itune_ja.svg" alt="" />
EOT;
  }else{
    //Webブラウザからのアクセス
    $sql ="SELECT A.app_id, B.name, A.img, A.dev, A.web_link as link, A.web_affi_link as affi_link FROM ".PLUGIN_DB_PREFIX."apps as A, ".PLUGIN_DB_PREFIX."base as B where A.app_id={$base_id} AND B.id=A.app_id";
$src = <<<EOT
<div class="btn btn-light-blue">公式サイトで見てみる</div>
EOT;
  }

  $results = $wpdb->get_results($sql,object);
  foreach ($results as $key => $r) {
    //アフィリンクがあってかつ、アフィリエイトを利用する場合
    if($r->affi_link == "" || $noaffi == 1){
      $app_href = $r->link;
    }else{
      $app_href = $r->affi_link;
    }

    // code...

    $title = $r->name;
    $dev = $r->dev;
    $img = $r->img;

  }

$appLink = <<<EOT
<a class="applink" href="{$app_href}" rel="nofollow">
<div class="applink_box">
<div class="applink_box_item1"><img src="{$img}" alt="{$title}のアイコン"/></div>
<div class="applink_box_item2">
<div class="fz-16px applink_box_title">{$title}</div>
<div class="fz-12px">開発元：{$dev}</div>
{$src}
</div><!--applink_box_item2が終了-->
</div>
</a>
EOT;
      return $appLink;
}
add_shortcode('appLinkG', 'appLinkGenerater');

function GetAfLink($atts) {
  extract( shortcode_atts( array(
     'id' => '1',
     'pl' => '0',
     'ntab'=> '0'
  ), $atts ) );
  global $wpdb;
  $sql = "SELECT B.anken, B.img_tag,B.affi_img, D.detail_img FROM ".PLUGIN_DB_PREFIX."base As B INNER JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

  $results = $wpdb->get_results($sql,object);
      if(count($results) == 0){
          $rep = "リンクエラー";
      }
    foreach($results as $r){
      $url = home_url() . "/".$r->anken . "?no={$id}&pl={$pl}";
    }
  return $url;
}

add_shortcode('getAfLink', 'GetAfLink');

function TagRanking($atts){
  extract( shortcode_atts( array(
    'id' => '1',
    'is_review'=>'1'
 ), $atts ) );

 global $wpdb;
 $sql = "SELECT DISTINCT item_id FROM ".PLUGIN_DB_PREFIX."tag_link where tag_id in (".$id.") group by item_id having count(*) >= ".count(explode(",", $id));
 $tags = $wpdb->get_results($sql, OBJECT);

 $disp_array = array();
 $sum_values = array();
 $names = array();
 foreach($tags as $tag){
   $sql = "SELECT item_name, rchart FROM ".PLUGIN_DB_PREFIX."detail where id=".$tag->item_id;
   $chart = $wpdb->get_row($sql, OBJECT);
   $values = json_decode($chart->rchart, true);

   $sum = 0;

   foreach((array)$values as $v){
    $sum = $sum + $v['value'];
   }

   array_push($disp_array, array('id'=>$tag->item_id, 'name'=>$chart->item_name));
   array_push($sum_values, $sum);
 }
 array_multisort($sum_values, SORT_DESC, $disp_array);

 foreach($disp_array as $ar){
   $rep .= "<h3>".$ar['name']."</h3>";
  $rep .= "[singleReview detail_id=".$ar['id']." is_review=".$is_review."]";
 }
 $rep = do_shortcode($rep);
 return $rep;
}
add_shortcode('tagRanking', 'TagRanking');

//楽天商品リンク作成　その2
if (!shortcode_exists('rakuten2')) {
  add_shortcode('rakuten2', 'rakuten_product_link_shortcode2');
}
if ( !function_exists( 'rakuten_product_link_shortcode2' ) ):
function rakuten_product_link_shortcode2($atts){
  extract( shortcode_atts( array(
    'id' => null,
    'no' => null,
    'search' => null,
    'shop' => null,
    'kw' => null,
    'title' => null,
    'desc' => null,
    'size' => 'm',
    'price' => null,
    'amazon' => 1,
    'rakuten' => 1,
    'yahoo' => 1,
    'border' => 1,
    'logo' => null,
    'sort' => null,
    'image_only' => 0,
    'text_only' => 0,
    'btn1_url' => null,
    'btn1_text' => __( '詳細ページ', THEME_NAME ),
    'btn1_tag' => null,
    'btn2_url' => null,
    'btn2_text' => __( '詳細ページ', THEME_NAME ),
    'btn2_tag' => null,
    'btn3_url' => null,
    'btn3_text' => __( '詳細ページ', THEME_NAME ),
    'btn3_tag' => null,
	'detail_id' => 1,
  'pl'=>0
  ), $atts, 'rakuten' ) );

  $id = sanitize_shortcode_value($id);

  if ($no) {
    $search = $no;
  }
  $search = sanitize_shortcode_value($search);

  //キーワード
  $keyword = sanitize_shortcode_value($kw);
  //全角スペースを半角に置換
  $keyword = str_replace('　', ' ', $keyword);
  //連続した半角スペースを1つに置換
  $keyword = preg_replace('/\s{2,}/', ' ', $keyword);
  //全角のハイフンを半角に置換
  $keyword = str_replace(' －', ' -', $keyword);
  //全角のダッシュを半角に置換
  $keyword = str_replace(' ―', ' -', $keyword);

  $description = $desc;

  $shop = sanitize_shortcode_value($shop);
  $sort = sanitize_shortcode_value($sort);


  //楽天アプリケーションID
  $rakuten_application_id = trim(get_rakuten_application_id());
  //楽天アフィリエイトID
  $rakuten_affiliate_id = trim(get_rakuten_affiliate_id());
  //アソシエイトタグ
  $associate_tracking_id = trim(get_amazon_associate_tracking_id());
  //Yahoo!バリューコマースSID
  $sid = trim(get_yahoo_valuecommerce_sid());
  //Yahoo!バリューコマースPID
  $pid = trim(get_yahoo_valuecommerce_pid());
  //キャッシュ更新間隔
  $days = intval(get_api_cache_retention_period());

  //もしもID
  $moshimo_amazon_id  = trim(get_moshimo_amazon_id());
  $moshimo_rakuten_id = trim(get_moshimo_rakuten_id());
  $moshimo_yahoo_id   = trim(get_moshimo_yahoo_id());



  //楽天アフィリエイトIDがない場合
  if (empty($rakuten_application_id) || empty($rakuten_affiliate_id)) {
    $error_message = __( '「楽天アプリケーションID」もしくは「楽天アフィリエイトID」が設定されていません。「Cocoon設定」の「API」タブから入力してください。', THEME_NAME );
    return wrap_product_item_box($error_message);
  }

  //商品IDがない場合
  if (empty($id) && empty($search)) {
    $error_message = __( 'id, no, searchオプションのいずれかが入力されていません。', THEME_NAME );
    return wrap_product_item_box($error_message);
  }

  if ($id) {
    $search_id = $id;
  } else {
    $search_id = $search;
  }
  $default_rakuten_link_tag = get_default_rakuten_link_tag($rakuten_affiliate_id, $search_id, $keyword);

  if ($id) {
    $cache_id = $id;
  } else {
    $cache_id = $search.$shop;
  }


  //キャッシュの取得
  $transient_id = get_rakuten_api_transient_id($cache_id);
  $transient_bk_id = get_rakuten_api_transient_bk_id($cache_id);
  $json_cache = get_transient( $transient_id );
  //キャッシュ更新間隔（randで次回の同時読み込みを防ぐ）
  $cache_expiration = DAY_IN_SECONDS * $days + (rand(0, 60) * 60);

  //キャッシュがある場合はキャッシュを利用する
  if ($json_cache) {
    // _v('cahce');
    $json = $json_cache;
  } else {
    // _v('api');
    $itemCode = null;
    if ($id) {
      $itemCode = '&itemCode='.$id;
    }


    $sortQuery = '&sort='.get_rakuten_api_sort();
    if ($sort && !$id) {
      $sortQuery = '&sort='.$sort;
    }
    $sortQuery = str_replace('+', '%2B', $sortQuery);

    $shopCode = null;
    if ($shop && !$id) {
      $shopCode = '&shopCode='.$shop;
    }
    $searchkw = null;
    if ($search && !$id) {
      $searchkw = '&keyword='.$search;
    }
    $request_url = 'https://app.rakuten.co.jp/services/api/IchibaItem/Search/20170706?applicationId='.$rakuten_application_id.'&affiliateId='.$rakuten_affiliate_id.'&imageFlag=1'.$sortQuery.$shopCode.'&hits=1'.$searchkw.$itemCode;
    //_v($request_url);
    $args = array( 'sslverify' => true );
    $json = wp_remote_get( $request_url, $args );

    //ジェイソンのリクエスト結果チェック
    $is_request_success = !is_wp_error( $json ) && $json['response']['code'] === 200;
    //JSON取得に失敗した場合はバックアップキャッシュを取得
    if (!$is_request_success) {
      $json_cache = get_transient( $transient_bk_id );
      if ($json_cache) {
        $json = $json_cache;
        // _v('bk');
        // _v($json);
      }
    }
  }


  if ($json) {
    //ジェイソンのリクエスト結果チェック
    $is_request_success = !is_wp_error( $json ) && $json['response']['code'] === 200;
    ///////////////////////////////////////////
    // キャッシュ削除リンク
    ///////////////////////////////////////////
    $cache_delete_tag = get_cache_delete_tag('rakuten', $cache_id);
    //リクエストが成功した時タグを作成する
    if ($is_request_success) {
      $acquired_date = date_i18n(__( 'Y/m/d H:i', THEME_NAME ));

      //キャッシュの保存
      if (!$json_cache) {
        $jb = $json['body'];
        if ($jb) {
          $jb = preg_replace('/{/', '{"date":"'.$acquired_date.'",', $jb, 1);
          $json['body'] = $jb;
        }
        //楽天APIキャッシュの保存
        set_transient($transient_id, $json, $cache_expiration);
        //楽天APIバックアップキャッシュの保存
        set_transient($transient_bk_id, $json, $cache_expiration * 2);
      }


      $body = $json["body"];
      //ジェイソンの配列化
      $body = json_decode( $body );
      //IDの商品が見つからなかった場合
      if (intval($body->{'count'}) > 0) {

        $Item = $body->{'Items'}['0']->{'Item'};
        if ($Item) {

          $itemName = $Item->{'itemName'};
          $itemCode = $Item->{'itemCode'};
          $itemPrice = $Item->{'itemPrice'};
          $itemCaption = esc_html($Item->{'itemCaption'});
          $itemUrl = esc_attr($Item->{'itemUrl'});//affiliateUrlと同じ
          $shopUrl = esc_attr($Item->{'shopUrl'});//shopAffiliateUrlと同じ
          $affiliateUrl = esc_attr($btn1_url);//itemUrlと同じ //$Item->{'affiliateUrl'}
          $shopAffiliateUrl = esc_attr($Item->{'shopAffiliateUrl'});//shopUrlと同じ
          $shopName = esc_html($Item->{'shopName'});
          $shopCode = $Item->{'shopCode'};
          $affiliateRate = $Item->{'affiliateRate'};


          //小さな画像
          $smallImageUrls = $Item->{'smallImageUrls'};
          $smallImageUrl = $smallImageUrls['0']->{'imageUrl'};
          //画像サイズの取得
          $sizes = get_rakuten_image_size($smallImageUrl);
          if ($sizes) {
            $smallImageWidth = $sizes['width'];
            $smallImageHeight = $sizes['height'];
          } else {
            $smallImageUrl = null;
            $smallImageWidth = null;
            $smallImageHeight = null;
          }

          //標準画像
          $mediumImageUrls = $Item->{'mediumImageUrls'};
          $mediumImageUrl = $mediumImageUrls['0']->{'imageUrl'};
          //画像サイズの取得
          $sizes = get_rakuten_image_size($mediumImageUrl);
          if ($sizes) {
            $mediumImageWidth = $sizes['width'];
            $mediumImageHeight = $sizes['height'];
          } else {
            $mediumImageUrl = null;
            $mediumImageWidth = null;
            $mediumImageHeight = null;
          }

          //サイズ設定
          $size = strtolower($size);
          switch ($size) {
            case 's':
              $size_class = 'pis-s';
              if ($smallImageUrl) {
                $ImageUrl = $smallImageUrl;
                $ImageWidth = $smallImageWidth;
                $ImageHeight = $smallImageHeight;
              } else {
                $ImageUrl = NO_IMAGE_150;
                $ImageWidth = '64';
                $ImageHeight = '64';
              }
              break;
            default:
              $size_class = 'pis-m';
              if ($mediumImageUrl) {
                $ImageUrl = $mediumImageUrl;
                $ImageWidth = $mediumImageWidth;
                $ImageHeight = $mediumImageHeight;
              } else {
                $ImageUrl = NO_IMAGE_150;
                $ImageWidth = '128';
                $ImageHeight = '128';
              }
              break;
            }


          ///////////////////////////////////////////
          // 商品リンク出力用の変数設定
          ///////////////////////////////////////////
          if ($title) {
            $Title = $title;
          } else {
            $Title = $itemName;
          }

          $TitleAttr = $Title;
          $TitleHtml = $Title;


          ///////////////////////////////////////////
          // 値段表記
          ///////////////////////////////////////////
          $item_price_tag = null;
          if (isset($body->{'date'})) {
            $acquired_date = $body->{'date'};
          }

          if ((is_rakuten_item_price_visible() || $price === '1')
                && $itemPrice
                && $price !== '0'
              ) {
            $FormattedPrice = '￥ '.number_format($itemPrice);;
            $item_price_tag = get_item_price_tag($FormattedPrice, $acquired_date);
          }

          ///////////////////////////////////////////
          // 説明文タグ
          ///////////////////////////////////////////
          $description_tag = get_item_description_tag($description);

          ///////////////////////////////////////////
          // もしも楽天URL
          ///////////////////////////////////////////
          $moshimo_rakuten_url = null;
          $moshimo_rakuten_impression_tag = null;
          if ($moshimo_rakuten_id && is_moshimo_affiliate_link_enable()) {
            $decoded_affiliateUrl = urldecode($affiliateUrl);
            $decoded_affiliateUrl = str_replace('&amp;', '&', $decoded_affiliateUrl);
            //_v(urldecode($decoded_affiliateUrl));
            if (preg_match_all('{\?pc=(.+?)&m=}i', urldecode($decoded_affiliateUrl), $m)) {
              if ($m[1][0]) {
                $rakuten_product_page_url = $m[1][0];
                $moshimo_rakuten_url = 'https://af.moshimo.com/af/c/click?a_id='.$moshimo_rakuten_id.'&p_id=54&pc_id=54&pl_id=616&url='.urlencode($rakuten_product_page_url);
                $affiliateUrl = $moshimo_rakuten_url;
                //インプレッションタグ
                $moshimo_rakuten_impression_tag = get_moshimo_rakuten_impression_tag();
              }
            }
          }

          ///////////////////////////////////////////
          // 検索ボタンの作成
          ///////////////////////////////////////////
          $info = AF::getAffiInfo($detail_id, 0);
          $affiText = "[afRecord id={$detail_id} pl={$pl}]".$info['name']."公式"."[/afRecord]";
          $affiText = do_shortcode($affiText);

          $args = array(
            'keyword' => $keyword,
            'associate_tracking_id' => $associate_tracking_id,
            'rakuten_affiliate_id' => $rakuten_affiliate_id,
            'sid' => $sid,
            'pid' => $pid,
            'moshimo_amazon_id' => $moshimo_amazon_id,
            'moshimo_rakuten_id' => $moshimo_rakuten_id,
            'moshimo_yahoo_id' => $moshimo_yahoo_id,
            'amazon' => $amazon,
            'rakuten' => $rakuten,
            'yahoo' => $yahoo,
            'amazon_page_url' => null,
            'rakuten_page_url' => $affiliateUrl,
            'btn1_url' => $btn1_url,
            'btn1_text' => $btn1_text,
            'btn1_tag' => $affiText,
            'btn2_url' => $btn2_url,
            'btn2_text' => $btn2_text,
            'btn2_tag' => $btn2_tag,
            'btn3_url' => $btn3_url,
            'btn3_text' => $btn3_text,
            'btn3_tag' => $btn3_tag,
          );
          $buttons_tag = get_search_buttons_tag($args);


          //枠線非表示
          $border_class = null;
          if (!$border) {
            $border_class = ' no-border';
          }

          //ロゴ非表示
          $logo_class = null;
          if ((!is_rakuten_item_logo_visible() && is_null($logo)) || (!$logo && !is_null($logo) )) {
            $logo_class = ' no-after';
          }

          // ///////////////////////////////////////////
          // // キャッシュ削除リンク
          // ///////////////////////////////////////////
          // $cache_delete_tag = get_cache_delete_tag('rakuten', $cache_id);

          ///////////////////////////////////////////
          // アフィリエイト料率タグ
          ///////////////////////////////////////////
          $affiliate_rate_tag = null;
          if (is_user_administrator()) {
            $affiliate_rate_tag = '<span class="product-affiliate-rate">'.__('料率：', THEME_NAME).$affiliateRate.'%</span>';
          }

          ///////////////////////////////////////////
          // 管理者情報タグ
          ///////////////////////////////////////////
          $product_item_admin_tag = get_product_item_admin_tag($cache_delete_tag, $affiliate_rate_tag);

          ///////////////////////////////////////////
          // イメージリンクタグ
          ///////////////////////////////////////////
          $affiText = "[afRecord id={$detail_id} pl={$pl}]".esc_attr($TitleAttr)."[/afRecord]";
          $affiText = do_shortcode($affiText);

$rep =<<<EOT
<span class="ajaxSnippetsAffiliateLink">
<rakuten-banner-link imgsrc="{$ImageUrl}" imgalt="{$TitleAttr}" imgwidth="{$ImageWidth}" imgheight="{$ImageHeight}" affiurl="{$info['url']}" place="{$pl}" id="{$detail_id}"></rakuten-banner-link>
EOT;
if(!empty($info['img_tag'])){
$rep .=<<<EOT
<img border="0" width="1" height="1" src="{$info['img_tag']}">
EOT;
}
$rep .='</span>';

          $image_only_class = null;
          if ($image_only) {
            $image_only_class = ' rakuten-item-image-only product-item-image-only no-icon';
          }
          $image_link_tag = '<a href="'.esc_url($info['url']).'" class="rakuten-item-thumb-link product-item-thumb-link'.esc_attr($image_only_class).'" target="_blank" title="'.esc_attr($TitleAttr).'" rel="nofollow noopener">'.
                  '<img src="'.esc_url($ImageUrl).'" alt="'.esc_attr($TitleAttr).'" width="'.esc_attr($ImageWidth).'" height="'.esc_attr($ImageHeight).'" class="rakuten-item-thumb-image product-item-thumb-image">'.
                  $moshimo_rakuten_impression_tag.
                '</a>';
          $image_link_tag = $rep;
          //画像のみ出力する場合
          if ($image_only) {
            return apply_filters('rakuten_product_image_link_tag', $image_link_tag);
          }

          ///////////////////////////////////////////
          // 楽天テキストリンク
          ///////////////////////////////////////////
          $affiText = "[afRecord id={$detail_id} pl={$pl}]".esc_attr($TitleAttr)."[/afRecord]";
          $affiText = do_shortcode($affiText);

          $text_only_class = null;
          if ($text_only) {
            $text_only_class = ' rakuten-item-text-only product-item-text-only';
          }
          $text_link_tag =
          '<a href="'.esc_url($affiliateUrl).'" class="rakuten-item-title-link product-item-title-link'.esc_attr($text_only_class).'" target="_blank" title="'.esc_attr($TitleAttr).'" rel="nofollow noopener">'.
            esc_html($TitleHtml).
            $moshimo_rakuten_impression_tag.
          '</a>';
          $text_link_tag = $affiText;
          if ($text_only) {
            return apply_filters('rakuten_product_text_link_tag', $text_link_tag);
          }

          ///////////////////////////////////////////
          // 商品リンクタグの生成
          ///////////////////////////////////////////
          $tag =
            '<div class="rakuten-item-box product-item-box no-icon '.$size_class.$border_class.$logo_class.' '.$id.' cf">'.
              '<figure class="rakuten-item-thumb product-item-thumb">'.
              $image_link_tag.
              '</figure>'.
              '<div class="rakuten-item-content product-item-content cf">'.
                '<div class="rakuten-item-title product-item-title">'.
                  $text_link_tag.
                '</div>'.
                '<div class="rakuten-item-snippet product-item-snippet">'.
                  '<div class="rakuten-item-maker product-item-maker">'.
                    $shopName.
                  '</div>'.
                  $item_price_tag.
                  $description_tag.
                '</div>'.
                $buttons_tag.
              '</div>'.
              $product_item_admin_tag.
            '</div>';

          //_v($tag);
          return apply_filters('rakuten_product_link_tag', $tag);
        }
      } else {
        $error_message = __( '商品が見つかりませんでした。', THEME_NAME );
        //楽天商品取得エラーの出力
        if (!$json_cache) {
          error_log_to_rakuten_product($id, $search, $error_message, $keyword);
        }
        return get_rakuten_error_message_tag($default_rakuten_link_tag, $error_message, $cache_delete_tag);
      }

    } else {

      $ebody = json_decode( $json['body'] );
      $error = $ebody->{'error'};
      $error_description = $ebody->{'error_description'};
      switch ($error) {
        case 'wrong_parameter':
        $error_message = $error_description.':'.__( 'ショートコードの値が正しく記入されていない可能性があります。', THEME_NAME );
        //楽天商品取得エラーの出力
        if (!$json_cache) {
          error_log_to_rakuten_product($id, $search, $error_message, $keyword);
        }
        //楽天APIキャッシュの保存
        set_transient($transient_id, $json, $cache_expiration);
        return get_rakuten_error_message_tag($default_rakuten_link_tag, $error_message, $cache_delete_tag);
          break;
        default:
        $error_message = $error_description.':'.__( 'Bad Requestが返されました。リクエスト制限を受けた可能性があります。しばらく時間を置いた後、リロードすると商品リンクが表示される可能性があります。', THEME_NAME );
          break;
      }
      return get_rakuten_error_message_tag($default_rakuten_link_tag, $error_message);
    }
  } else {
    $error_message = __( 'JSONを取得できませんでした。接続環境に問題がある可能性があります。', THEME_NAME );
    return get_rakuten_error_message_tag($default_rakuten_link_tag, $error_message);
  }

}
endif;
