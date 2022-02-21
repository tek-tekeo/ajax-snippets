<?php
//ショートコード 集
//基本となるリンク作成

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
