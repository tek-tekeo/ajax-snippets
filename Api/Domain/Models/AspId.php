<?php
namespace AjaxSnippets\Api\Domain\Models;

class AspId
{
  public int $id;
  public function __construct(int $id = 0)
  {
    if($id === null){
      $this->id = 0; //0を設定していると自動でauto incrementしてくれる
    }else{
      $this->id = $id;
    }
  }
}

?>