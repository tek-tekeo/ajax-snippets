<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

class BaseRepository
{
  protected $db;
  protected $table;

  protected function getSelectSql($element)
  {
    /*配列のkeyを取得*/
    $keys = array_keys($element);
    $placeholder = $this->judgeType($element[$keys[0]]);

    return $this->db->prepare("SELECT * FROM ".$this->table . " WHERE ".$keys[0]."=".$placeholder, $element);
  }

  private function judgeType($type)
  {
    if(gettype($type) == 'integer'){
      return '%d';
    }else if(gettype($type) == 'float'){
      return '%f';
    }else{
      return '%s';
    }
  }

  protected function groupBy($key, array $ary): array
  {
    $groups = [];
    foreach ($ary as $row) {
      if (property_exists($row, $key)) {
          $groups[$row->id][] = $row;
      }
    }
    return $groups;
  }

}