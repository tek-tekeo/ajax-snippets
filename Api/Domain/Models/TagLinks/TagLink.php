<?php
namespace AjaxSnippets\Api\Domain\Models\TagLinks;

class TagLink
{
  private $id;
  private $itemId;
  private $tagId;

  public function __construct(
    int $id = null,
    int $itemId,
    int $tagId
  )
  {
    $this->id = $id;
    $this->itemId = $itemId;
    $this->tagId = $tagId;
  }

  //以下、ドメインの知識のみ
  public function getId()
  {
    return $this->id;
  }

  public function getItemId()
  {
    return $this->itemId;
  }

  public function getTagId()
  {
    return $this->tagId;
  }
}

?>