<?php
namespace AjaxSneppets\Domain\Models;

use AjaxSnippets\Domain\Services\AspService;

// use AjaxSnippets\Domain\Models\BaseModel;

class Asp
{
  public AspId $aspId;
  private $aspName;
  private $connectString;

  public function __construct(
    AspId $aspId = null,
    string $aspName,
    string $connectString
  )
  {
    $this->aspId = $aspId;
    $this->aspName = $aspName;
    $this->connectString = $connectString;
  }

  //以下、ドメインの知識のみ
  public function getId()
  {
    return $this->aspId->id;
  }

  public function getAspName()
  {
    return $this->aspName;
  }

  public function getConnectString()
  {
    return $this->connectString;
  }

  public function setId(AspId $aspId)
  {
    //インクリメントなので重複チェックは不要
    // $this->aspName = $newName;
  }

  public function setAspName(string $aspName)
  {
    if(mb_strlen($aspName) > 10){ 
      return;
    }
    $this->aspName = $aspName;
  }

  public function setConnectString(string $connectString)
  {
    if(mb_strlen($connectString) > 10){
      return 'エラーです';
    }
    $this->connectString = $connectString;
  }
}

class AspId
{
  public int $id;
  public function __construct(int $id = null)
  {
    if($id === null){
      $this->id = 0; //0を設定していると自動でauto incrementしてくれる
    }else{
      $this->id = $id;
    }
  }
}

?>