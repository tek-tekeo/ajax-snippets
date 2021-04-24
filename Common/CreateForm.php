<?php

namespace AjaxSnippets\Common;

class CreateForm{

  public function __construct(){

  }

  public function textBox($name, $value, $is_required=false, $placeholder = null, $size = 60) : string {
    ($is_required)? $required='required' : $required=false;
    $str = "<input type='text' id='compose_text_$name' name=\"$name\" value=\"$value\" placeholder=\"$placeholder\" size=\"$size\" $required>";
    return $str;
  }
  public function vueTextBox($name, $value, $is_required=false, $placeholder = null, $size = 60) : string {
    ($is_required)? $required='required' : $required=false;
    $str = "<input type=\"text\" name=\"$name\" v-model=\"$name\" placeholder=\"$placeholder\" size=\"$size\" $required>";
    return $str;
  }

  public function textAreaBox($name, $content = null, $editor_id = 'wp_editor', $textarea_rows = 16){
    $settings = array(
      'textarea_name' => $name,
      'textarea_rows' => $textarea_rows,
    ); //配列としてデータを渡すためname属性を指定する
    return wp_editor( $content, $editor_id, $settings );
  }

  public function numBox($name, $value, $placeholder = null, $is_required = false){
    $str = "<input type='number' id='compose_num_$name' name=\"$name\" value=\"$value\" placeholder=\"$placeholder\">";
    return $str;
  }

  //テーブルからセレクトボックスを生成する
  public function sqlSelectBox($talbe, $name, $options, $now_value = null, $is_required = null){
    ($is_required) ? $is_required="required" : $is_required="";

    global $wpdb;

    $sql = "SELECT ".$options[0]." as value, ".$options[1]." as name FROM ".$talbe;
    $results = $wpdb->get_results($sql, object);

    $selectbox_content = "";
    $selectbox_content .= "<select name=\"$name\" $is_required>";
    $selectbox_content .= "<option value=\"\">選択してください</option>";

    foreach($results as $r){
      if($r->value == $now_value){
        $selectbox_content .= "<option value=".$r->value." selected>".$r->name."</option>";
      }else{
        $selectbox_content .= "<option value=".$r->value.">".$r->name."</option>";
      }
    }
    $selectbox_content .=  "</select>";

    return $selectbox_content;
  }

  //options: array(array(value, name),array(value, name),...)
  public function selectBox($name, $options, $now_value = null, $is_required = null){
    ($is_required) ? $is_required="required" : $is_required="";
    $selectbox_content = "";
    $selectbox_content .= "<select name=\"$name\" $is_required>";
    $selectbox_content .= "<option value=\"\">選択してください</option>";

    foreach($options as $values){
      if($values[0] == $now_value){
        $selectbox_content .= "<option value=".$values[0]." selected>".$values[1]."</option>";
      }else{
        $selectbox_content .= "<option value=".$values[0].">".$values[1]."</option>";
      }
    }
    $selectbox_content .=  "</select>";

    return $selectbox_content;
  }

  public function showUrlRadioBox(string $name, int $value): string
  {
    if($value === 0){
      $checkStr .= '<input type="radio" name="'.$name.'" value="1">表示';
      $checkStr .= '<input type="radio" name="'.$name.'" value="0" checked="checked">非表示';
    }else{
      $checkStr .= '<input type="radio" name="'.$name.'" value="1" checked="checked">表示';
      $checkStr .= '<input type="radio" name="'.$name.'" value="0">非表示';
    }

    return $checkStr;
  }
  public function vueShowUrlRadioBox(string $name, int $value): string
  {
      $checkStr .= '<label><input type="radio" name="'.$name.'" v-model="is_show_url" value="1">表示</label>';
      $checkStr .= '<label><input type="radio" name="'.$name.'" v-model="is_show_url" value="0">非表示</label>';

    return $checkStr;
  }

  public function imgUploadBox($thumb){
    $imgUploadBox_path = plugins_url('ajax-snippets/js/imgUploadBox.js');
    $str = "<input name=\"img\" type=\"text\" value=\"{$thumb}\">";
    $str .= "<input type=\"button\" name=\"upladed_avatar_select\" value=\"選択\">";
    $str .= "<input type=\"button\" name=\"upladed_avatar_clear\" value=\"クリア\">";
    $str .= "<div id=\"upladed_avatar_thumbnail\" class=\"uploded-thumbnail\">";
    $str .= "</div>";
    $str .= "<script src=\"{$imgUploadBox_path}\" type=\"text/javaScript\" charset=\"utf-8\"></script>";

    return $str;
  }
  public function vueImgUploadBox($thumb){
    $imgUploadBox_path = plugins_url('ajax-snippets/js/imgUploadBox.js');
    $str = "<input v-model=\"detail_img\" name=\"img\" type=\"text\" value=\"{$thumb}\">";
    $str .= "<input type=\"button\" name=\"upladed_avatar_select\" value=\"選択\">";
    $str .= "<input type=\"button\" name=\"upladed_avatar_clear\" value=\"クリア\">";
    $str .= "<div id=\"upladed_avatar_thumbnail\" class=\"uploded-thumbnail\">";
    $str .= "</div>";
    $str .= "<script src=\"{$imgUploadBox_path}\" type=\"text/javaScript\" charset=\"utf-8\"></script>";

    return $str;
  }

}
