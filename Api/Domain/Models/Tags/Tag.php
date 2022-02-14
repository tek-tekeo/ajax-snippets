<?php
namespace AjaxSnippets\Api\Domain\Models\Tags;

class Tag
{
  private $id;
  private $tagName;
  private $tagOrder;

  public function __construct(
    int $id = null,
    string $tagName,
    int $tagOrder
  )
  {
    $this->id = $id;
    $this->tagName = $tagName;
    $this->tagOrder = $tagOrder;
  }

  //以下、ドメインの知識のみ
  public function getId()
  {
    return $this->id;
  }

  public function getTagName()
  {
    return $this->tagName;
  }

  public function getTagOrder()
  {
    return $this->tagOrder;
  }

  public function setId(int $id)
  {
    //インクリメントなので重複チェックは不要
  }

  public function setTagName(string $tagName)
  {
    if(mb_strlen($tagName) > 10){ 
      return;
    }
    $this->tagName = $tagName;
  }

  public function setTagOrder(int $tagOrder)
  {
    $this->tagOrder = $tagOrder;
  }
}

?>