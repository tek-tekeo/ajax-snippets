<?php
namespace AjaxSnippets\Domain\Models;

class BaseModel
{
  const TABLE_NAME = '';
  protected $casts = [];
  protected $attributes = [];

  public function __construct()
  {
    foreach ($this->columns as $col) {
      $this->attributes[$col] = null;
    }
  }

  static private function _getTableName()
  {
    global $wpdb;

    return $wpdb->prefix . 'ajax_snippets_' . static::TABLE_NAME;
  }

  public function exeInsert($data){

    global $wpdb;
    //TODO: 挿入する配列の型が違う場合がある。NULLの時にどう処理するかわからん
    $values = [];
    foreach ($this->columns as $col) {
      $this->attributes[$col] = $data[$col];
      if(is_null($this->attributes[$col])){
        $this->attributes[$col] = "";
      }
    }

    $tableName = self::_getTableName();
    $wpdb->insert( $tableName, $this->attributes);
    $insert_id = $wpdb->insert_id;
    return $insert_id;
  }

  static private function getInsertQuery(array $columns, array $values): string
  {
    $tableName = self::_getTableName();

    $sql = "INSERT INTO {$tableName} ";

    $sql .= "(" . implode(',', $columns) . ")";
    $sql .= " VALUES (";
    $sql .= implode(
      ',',
      array_map(function ($col) use ($values) {
        return "'{$values[$col]}'";
      }, $columns)
    );
    $sql .= ")";

    return $sql;
  }

  public function exeReplace($data, $where){

    global $wpdb;
    //TODO: 挿入する配列の型が違う場合がある。NULLの時にどう処理するかわからん
    $values = [];
    $updateData =[];
    foreach ($this->columns as $col) {
      $updateData[$col] = $data[$col];
    }

    $tableName = self::_getTableName();
    //tabelにprimary keyが必要です
    $rep = $wpdb->replace($tableName, $updateData);
    return $rep;
  }
}

class Apps extends BaseModel
{
  const TABLE_NAME = "apps";

  protected $casts = [
    'app_id' => 'int'
  ];
  protected $columns = [
    'app_id',
    'img',
    'dev',
    'ios_link',
    'android_link',
    'web_link',
    'ios_affi_link',
    'android_affi_link',
    'web_affi_link',
    'article',
    'app_order',
    'app_price'
  ];
}

class Base extends BaseModel
{
  const TABLE_NAME = "base";

  protected $casts = [
    'id' => 'int'
  ];
  protected $columns = [
    'id',
    'name',
    'anken',
    'affi_link',
    's_link',
    'asp_name',
    'affi_img',
    'img_tag'
  ];
}

class Detail extends BaseModel
{
  const TABLE_NAME = "detail";

  protected $casts = [
    'id' => 'int',
    'base_id' => 'int'
  ];
  protected $columns = [
    'id',
    'base_id',
    'item_name',
    'official_item_link',
    'affi_item_link',
    'amazon_asin',
    'rakuten_id',
    'rchart',
    'info',
    'review'
  ];
}

class AffiLog extends BaseModel
{
  const TABLE_NAME = "log";

  protected $casts = [
    'id' => 'int'
  ];
  protected $columns = [
    'id',
    'item_id',
    'date',
    'time',
    'post_addr',
    'place',
    'ip'
  ];
}
