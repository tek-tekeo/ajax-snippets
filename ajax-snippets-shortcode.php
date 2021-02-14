<?php

//ショートコード 集
function AjaxSniShortcodeLink($atts, $content = null) {
    extract( shortcode_atts( array(
       'id' => '1',
       'noaf' => '0',
        're_url'=>'0'
    ), $atts ) );
    global $wpdb;
    $sql = "SELECT B.name, B.affi_link, B.affi_img, B.img_tag, D.id, D.item_name, D.official_item_link FROM ".PLUGIN_DB_PREFIX."base As B RIGHT JOIN ".PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.id={$id}";

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
                      if($r->item_name == "トップ"){
                        $url = $r->affi_link;
                      }else{
                        $url = $r->affi_link."&a8ejpredirect=" . urlencode($r->official_item_link);
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
       'ntab' => '0'
    ), $atts ) );
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

        //テキストなしリンク
        if(empty($content)){
$rep .= <<<EOT
<a href="{$url}" {$a_tab}> {$r->official_item_link}</a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
        }else{
$rep .= <<<EOT
<a href="{$url}" {$a_tab}>{$content}</a><img border="0" width="1" height="1" src="{$r->img_tag}">
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
$rep .= <<<EOT
<a href="{$url}" {$a_tab}><img border="0" width="300" height="250" alt="" src="{$r->affi_img}"></a><img border="0" width="1" height="1" src="{$r->img_tag}">
EOT;
        }
    return $rep;
}

add_shortcode('afLink', 'AjaxSniShortcodeLink');
add_shortcode('afRecord', 'AjaxRecordShortcodeLink');
add_shortcode('afRecordBanner', 'AjaxRecordShortcodeBanner');

function SingleReview($atts, $content = null){
  extract( shortcode_atts( array(
     'detail_id' => '1',
     'color'=>'blue'
  ), $atts ) );
  global $wpdb;
  $sql = "SELECT D.*,B.name FROM ".PLUGIN_DB_PREFIX."base as B,".PLUGIN_DB_PREFIX."detail as D where D.id={$detail_id} AND B.id=D.base_id";
  $list = $wpdb->get_results( $sql, object);

  foreach($list as $l){

  $chart_ele = json_decode($l->rchart, true);
  if(count($chart_ele) >= 3){
    $chart_rate = implode(",", $chart_ele);
    $chart_factor = implode(",",array_keys($chart_ele));
    if($l->detail_img != "" && $l->item_name !="トップ"){
      $l->name = $l->item_name;
    }
    $chart_str = "[ajax_snippets_rchart factor='".$chart_factor."' rate='".$chart_rate."' name='{$l->name}' color='".$color."']";
  }else{
    $chart_str ="";
  }

  $l->info = json_decode($l->info, true);//wpautop(stripslashes_deep());
  $l->review = wpautop(stripslashes_deep($l->review));
  foreach($l->info as $key => $d){
    $d = stripslashes_deep($d);
$table_elements .=<<<EOT
<tr>
<th>{$key}</th><td>$d</td>
</tr>
EOT;
  }

$rep .=<<<EOT
    <div style="display:flex;align-items: flex-start;justify-content:space-evenly;width:100%">
    <div style="text-align:center;width:30%">[afRecordBanner id={$l->id}]</div>
    <div style="width:50%;max-width:300px">
    {$chart_str}
    </div>
    </div>
    <table class="simple-table">
    <tbody>
    {$table_elements}
    <tr>
    <th>公式サイト</th>
    <td>[afRecord id={$detail_id}]{$l->official_item_link}[/afRecord]</a></td>
    </tr>
    </tbody>
    </table>
    {$l->review}
EOT;
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
     'color'=>'blue'
  ), $atts ) );
if($color == 'blue'){
$rgb = "54, 162, 232";
}else if($color == 'red'){
  $rgb = "247, 133, 133";
}
  $factor = explode(",", $factor);
  foreach($factor as $f){
    $ff .= "\"".$f."\"";
    if(next($factor)){
      $ff .= ",";
      }
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
            display: true,
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
